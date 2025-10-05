<?php

namespace Wali\FilamentWordExport\Providers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WordExportServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-word-export')
            ->hasConfigFile() // expects config/filament-word-export.php
            ->hasViews()      // optional, if you have Blade views
            ->hasAssets();    // optional, if you have JS/CSS
    }
}
