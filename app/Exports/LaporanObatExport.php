<?php

namespace App\Exports;

use App\Models\Obat;
use App\Models\RestokObat;
use App\Models\PenjualanDetail;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanObatExport implements FromView
{
    public function __construct($laporans)
{
    $this->laporans = $laporans;
}

public function view(): View
{
    return view('admin.laporan.laporanObat.excel', [
        'laporans' => $this->laporans
    ]);
}
}