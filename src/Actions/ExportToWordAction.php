<?php

namespace Wali\FilamentWordExport\Actions;

use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Wali\FilamentWordExport\Services\WordExportService;

class ExportToWordAction extends BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'exportToWord';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Export to Word')
            ->icon('heroicon-o-document-text')
            ->requiresConfirmation()
            ->action(function (Collection $records) {
                $data = $records->map(function ($record) {
                    if ($record instanceof Model) {
                        return $record->toArray();
                    }

                    return is_array($record) ? $record : (array) $record;
                })->toArray();

                return app(WordExportService::class)->export($data);
            });
    }
}
