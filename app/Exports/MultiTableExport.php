<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiTableExport implements WithMultipleSheets
{
    protected $exports;

    public function __construct(array $exports)
    {
        $this->exports = $exports;
    }

    public function sheets(): array
    {
        // return collect($this->exports)->map(function ($export) {
        //     return new $export['class']($export['data']);
        // })->toArray();
        return collect($this->exports)->map(function ($export) {
            return $export; // Mengirimkan objek eksport langsung
        })->toArray();
    }
}
