#!/usr/bin/env bash
# Renders art/source/banner.html to the plugin image (2560x1440) and thumbnail (1280x720) JPEGs.
set -euo pipefail

cd "$(dirname "$0")/.."

chrome="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
src="file://$PWD/source/banner.html"
tmp="$(mktemp -d)"

render() {
    local query="$1" scale="$2" out="$3"

    "$chrome" --headless=new --disable-gpu --hide-scrollbars --virtual-time-budget=5000 \
        --window-size=1280,720 --force-device-scale-factor="$scale" \
        --screenshot="$tmp/shot.png" "$src$query" >/dev/null 2>&1

    sips -s format jpeg -s formatOptions 92 "$tmp/shot.png" --out "$out" >/dev/null
}

render "" 2 plugin-image.jpg
render "?thumb" 1 plugin-thumbnail.jpg

rm -r "$tmp"

sips -g pixelWidth -g pixelHeight plugin-image.jpg plugin-thumbnail.jpg
