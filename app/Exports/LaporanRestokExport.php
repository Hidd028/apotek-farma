<?php

namespace App\Exports;

use App\Models\RestokObat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class LaporanRestokExport implements FromView
{
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $periode;

    public function __construct($tanggal_awal = null, $tanggal_akhir = null, $periode = null)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->periode = $periode;
    }

    public function view(): View
    {
        $query = RestokObat::with(['obat', 'supplier', 'user']);

        if ($this->tanggal_awal && $this->tanggal_akhir) {
            $start = Carbon::parse($this->tanggal_awal)->startOfDay();
            $end = Carbon::parse($this->tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_masuk', [$start, $end]);
        }

        $restoks = $query->orderBy('tanggal_masuk', 'desc')->get();

        // 🔁 Buat $laporans sama seperti di index()
        $grouped = $restoks->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_masuk)->format('Y-m-d');
        });

        $laporans = collect();
        foreach ($grouped as $tanggal => $items) {
            $total_transaksi = $items->count();
            $total_pengeluaran = $items->sum(fn($r) => is_numeric($r->harga_beli) ? $r->harga_beli : 0);

            $supplier_terbanyak = $items->groupBy('supplier_id')
                ->sortByDesc(fn($g) => $g->count())
                ->keys()
                ->first();

            $petugas_terbanyak = $items->whereNotNull('user_id')->groupBy('user_id')
                ->sortByDesc(fn($g) => $g->count())
                ->keys()
                ->first();

            $nama_supplier = $supplier_terbanyak ? ($items->firstWhere('supplier_id', $supplier_terbanyak)->supplier->nama_supplier ?? '-') : '-';
            $nama_petugas = $petugas_terbanyak ? ($items->firstWhere('user_id', $petugas_terbanyak)->user->nama ?? '-') : '-';

            $laporans->push((object)[
                'periode_awal' => $tanggal,
                'periode_akhir' => $tanggal,
                'nama_obat' => $items->first()->obat->nama_obat ?? '-',
                'jumlah' => $items->sum('jumlah'),
                'total_transaksi' => $total_transaksi,
                'total_pengeluaran' => $total_pengeluaran,
                'supplier_terbanyak' => $nama_supplier,
                'petugas_terbanyak' => $nama_petugas,
                'created_at' => now(),
            ]);
        }

        return view('admin.laporan.laporanRestok.excel', [
            'laporans' => $laporans,
            'periode' => $this->periode,
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
        ]);
    }
}