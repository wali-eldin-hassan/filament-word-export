<?php

namespace Wali\FilamentWordExport\Actions\Concerns;

use Filament\Actions\BulkAction;

/*
 * Filament version compatibility base class.
 *
 * This class provides a stable inheritance hierarchy for Rector static analysis
 * while maintaining compatibility with both Filament 3.x and 4.x.
 */
if (class_exists(BulkAction::class)) {
    // Filament 4.x - Use the new namespace
    abstract class FilamentBulkActionBase extends BulkAction
    {
        // Explicitly define setUp to help Rector understand the inheritance
        #[\Override]
        protected function setUp(): void
        {
            parent::setUp();
        }
    }
} else {
    // Filament 3.x - Use the legacy namespace
    abstract class FilamentBulkActionBase extends \Filament\Tables\Actions\BulkAction
    {
        // Explicitly define setUp to help Rector understand the inheritance
        #[\Override]
        protected function setUp(): void
        {
            parent::setUp();
        }
    }
}
