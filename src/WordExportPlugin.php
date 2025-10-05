<?php

namespace Wali\FilamentWordExport;

use Filament\Contracts\Plugin;
use Filament\Panel;

class WordExportPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-word-export';
    }

    public function register(Panel $panel): void
    {
        // You can register actions, widgets, or components here
    }

    public function boot(Panel $panel): void
    {
        // Boot logic when Filament panel is loaded
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
