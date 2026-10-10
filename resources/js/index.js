/*
 * Drives the sticky `top` on the nav sidebar (and the open mobile drawer) so a menu taller than
 * the viewport travels with the page in both directions, pinning to the bottom on the way down
 * and the top on the way up. A menu that fits stays pinned at the top.
 */
(() => {
    const DESKTOP_MIN_WIDTH = 1024;
    const MOBILE_CLASS = 'sidebar-coscroll-mobile';

    const getTopbarOffset = () => document.querySelector('.fi-topbar-ctn')?.offsetHeight ?? 0;
    const isDesktop = () => window.innerWidth >= DESKTOP_MIN_WIDTH;
    const isMobileDrawerEnabled = () => window.filamentData?.sidebarCoscroll?.mobileDrawer !== false;

    let sidebar = null;
    let bounds = null;
    let currentTop = null;
    let lastScrollY = window.scrollY;
    let closeTimer = null;

    const setTop = (top) => {
        if (top === currentTop) {
            return;
        }

        currentTop = top;
        sidebar.style.setProperty('top', `${top}px`, 'important');
    };

    const clampTop = (top) => Math.min(bounds.max, Math.max(bounds.min, top));

    /**
     * Layout is only read here, never in the scroll handler, so scrolling does not force a
     * reflow after each `top` write.
     */
    const measure = () => {
        lastScrollY = window.scrollY;

        if (! sidebar) {
            return;
        }

        // The stylesheet only makes the sidebar sticky where it should co-scroll. Elsewhere, such
        // as a closed drawer, top navigation or a closed fully collapsible sidebar, Filament keeps
        // it `fixed` and the script leaves it alone.
        if (getComputedStyle(sidebar).position !== 'sticky') {
            bounds = null;
            currentTop = null;
            sidebar.style.removeProperty('top');
            sidebar.style.removeProperty('margin-top');

            return;
        }

        const desktop = isDesktop();
        const offset = getTopbarOffset();

        if (desktop) {
            sidebar.style.removeProperty('margin-top');
        } else {
            sidebar.style.setProperty('margin-top', `-${offset}px`);
        }

        const max = desktop ? offset : 0;

        bounds = {
            min: Math.min(max, window.innerHeight - sidebar.offsetHeight),
            max,
        };

        setTop(clampTop(currentTop ?? (desktop ? sidebar.getBoundingClientRect().top : 0)));
    };

    const onScroll = () => {
        const delta = window.scrollY - lastScrollY;

        lastScrollY = window.scrollY;

        if (bounds) {
            setTop(clampTop(currentTop - delta));
        }
    };

    /**
     * The mobile class outlives `fi-sidebar-open` until the slide-out finishes, otherwise the
     * drawer snaps back to `fixed` mid-transition. Only acts on a real change, because the
     * class observer also sees this function's own class writes. Returns whether it measured.
     */
    const syncMobileState = () => {
        if (! sidebar) {
            return false;
        }

        const shouldCoScroll = isMobileDrawerEnabled() && ! isDesktop() && sidebar.classList.contains('fi-sidebar-open');
        const isCoScrolling = sidebar.classList.contains(MOBILE_CLASS);

        if (shouldCoScroll) {
            clearTimeout(closeTimer);
            closeTimer = null;

            if (isCoScrolling) {
                return false;
            }

            currentTop = null;
            sidebar.classList.add(MOBILE_CLASS);
            measure();

            return true;
        }

        if (isCoScrolling && ! closeTimer) {
            closeTimer = setTimeout(() => {
                closeTimer = null;
                sidebar?.classList.remove(MOBILE_CLASS);
                measure();
            }, isDesktop() ? 0 : 350);
        }

        return false;
    };

    let resizeObserver = null;
    let classObserver = null;
    let frame = null;

    // Debounced so the read happens after layout settles, not mid-resize.
    const scheduleMeasure = () => {
        if (frame) {
            cancelAnimationFrame(frame);
        }

        frame = requestAnimationFrame(() => {
            frame = null;

            if (! syncMobileState()) {
                measure();
            }
        });
    };

    const init = () => {
        sidebar = document.querySelector('.fi-sidebar');
        bounds = null;
        currentTop = null;
        clearTimeout(closeTimer);
        closeTimer = null;

        resizeObserver?.disconnect();
        classObserver?.disconnect();

        if (! sidebar) {
            return;
        }

        resizeObserver = new ResizeObserver(scheduleMeasure);
        resizeObserver.observe(sidebar);

        // Toggling `fi-sidebar-open` on desktop can switch the sidebar between `fixed` and sticky.
        classObserver = new MutationObserver(() => {
            if (! syncMobileState()) {
                scheduleMeasure();
            }
        });
        classObserver.observe(sidebar, { attributes: true, attributeFilter: ['class'] });

        if (! syncMobileState()) {
            measure();
        }
    };

    if (! window.__sidebarCoscrollBound) {
        window.__sidebarCoscrollBound = true;

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', scheduleMeasure);
        document.addEventListener('livewire:navigated', init);
    }

    init();
})();
