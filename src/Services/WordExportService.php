<?php

namespace Wali\FilamentWordExport\Services;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class WordExportService
{
    public function export(array $records)
    {
        $phpWord = new PhpWord;
        $section = $phpWord->addSection();

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);

        foreach ($records as $record) {
            $table->addRow();
            // $record is already an array, no need to call toArray()
            foreach ($record as $value) {
                $table->addCell(2000)->addText($value ?? '-');
            }
        }

        $filename = 'filament-export-'.now()->format('Ymd_His').'.docx';
        $path = 'exports/'.$filename;

        Storage::disk('local')->put($path, '');

        $tempPath = Storage::disk('local')->path($path);
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }
}
