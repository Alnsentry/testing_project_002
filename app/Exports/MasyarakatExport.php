<?php

namespace App\Exports;

use App\Models\LaporanInfoKebakaran;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

class MasyarakatExport implements FromQuery, WithTitle, WithHeadings
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $start = strtotime($start_date);
        $end = strtotime(explode("/",$end_date)[0]."/".(explode("/",$end_date)[1] + 1)."/".explode("/",$end_date)[2]);

        $this->start_date = date('Y-m-d', $start);
        $this->end_date = date('Y-m-d', $end);
    }

    public function query()
    {
        $query = LaporanInfoKebakaran::query();

        $query->whereBetween('created_at', [$this->start_date, $this->end_date]);

        return $query;
    }

    public function title(): string
    {
        return 'Laporan Masyarakat';
    }

    public function headings(): array
    {
        $table = $this->query()->getModel()->getTable();
        return Schema::getColumnListing($table);
    }
}
