<?php

namespace Wali\FilamentWordExport\Actions\Concerns;

use Filament\Actions\BulkAction;

// Filament version compatibility base class
if (class_exists(BulkAction::class)) {
    // Filament 4.x
    abstract class FilamentBulkActionBase extends BulkAction
    {
        //
    }
} else {
    // Filament 3.x
    abstract class FilamentBulkActionBase extends \Filament\Tables\Actions\BulkAction
    {
        //
    }
}
