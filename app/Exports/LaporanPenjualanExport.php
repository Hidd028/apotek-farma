<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPenjualanExport implements FromView
{
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $periode;

    public function __construct($tanggal_awal, $tanggal_akhir, $periode)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->periode = $periode;
    }

    public function view(): View
    {
        // 🔍 Ambil detail transaksi
        $detailTransaksi = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan_detail.penjualan_id', '=', 'penjualan.id')
            ->join('obats', 'penjualan_detail.obat_id', '=', 'obats.id')
            ->select(
                'penjualan.tanggal_transaksi',
                'obats.nama_obat',
                'penjualan_detail.jumlah',
                'penjualan_detail.harga_satuan',
                'penjualan_detail.total_harga',
                'penjualan.metode_pembayaran',
                'penjualan.nama_user as kasir'
            )
            ->when($this->tanggal_awal && $this->tanggal_akhir, function ($q) {
                $q->whereBetween('penjualan.tanggal_transaksi', [
                    Carbon::parse($this->tanggal_awal)->startOfDay(),
                    Carbon::parse($this->tanggal_akhir)->endOfDay()
                ]);
            })
            ->orderBy('penjualan.tanggal_transaksi', 'desc')
            ->get();

        // 💰 Hitung total
        $totalPendapatan = $detailTransaksi->sum('total_harga');

        return view('admin.laporan.laporanPenjualan.excel', [
            'detailTransaksi' => $detailTransaksi,
            'totalPendapatan' => $totalPendapatan,
            'periode' => $this->periode,
        ]);
    }
}