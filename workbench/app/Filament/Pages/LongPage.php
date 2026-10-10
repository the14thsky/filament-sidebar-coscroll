<?php

namespace Workbench\App\Filament\Pages;

use Filament\Pages\Dashboard;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

/**
 * A home page much taller than the sidebar, so the sidebar has room to co-scroll both ways.
 */
class LongPage extends Dashboard
{
    protected static ?string $title = 'Long page';

    public function content(Schema $schema): Schema
    {
        return $schema->components(array_map(
            fn (int $number): Section => Section::make("Section {$number}")
                ->schema([
                    Text::make(str_repeat('Filler text to make the page scroll. ', 12)),
                ]),
            range(1, 30),
        ));
    }
}
