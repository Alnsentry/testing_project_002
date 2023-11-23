<?php

namespace App\Exports;

use App\Models\DaopsGroundcheckKebakaran;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

class PemadamanExport implements FromQuery, WithTitle, WithHeadings
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    
    public function query()
    {
        $query = DaopsGroundcheckKebakaran::query();

        $query->whereBetween('tanggal', [$this->start_date, explode("/",$this->end_date)[0]."/".(explode("/",$this->end_date)[1] + 1)."/".explode("/",$this->end_date)[2]]);

        return $query;
    }

    public function title(): string
    {
        return 'Laporan Pemadaman';
    }

    public function headings(): array
    {
        $table = $this->query()->getModel()->getTable();
        return Schema::getColumnListing($table);
    }
}
