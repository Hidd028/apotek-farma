<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Obat;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
{
    /**
     * -------------------------------------------------------
     *  NOTIFIKASI (SAMA SEPERTI HALAMAN LAIN)
     * -------------------------------------------------------
     */
    $user = Auth::user();
    $today = \Carbon\Carbon::today();

    // Key status tanda dibaca per user
    $dibacaKey = 'notifikasi_dibaca_' . $user->id;
    $sudahDibaca = session($dibacaKey, false);

    $notifikasi = [];
    $obats = Obat::orderBy('stok', 'asc')->get();

    foreach ($obats as $o) {
        if (!$o->tanggal_kadaluarsa) continue;

        $kadaluarsa = \Carbon\Carbon::parse($o->tanggal_kadaluarsa);

        if ($kadaluarsa->lt($today)) {
            $notifikasi[] = [
                'pesan' => "Obat <b>{$o->nama_obat}</b> sudah expired!",
                'icon'  => 'fas fa-times-circle',
                'warna' => 'bg-danger'
            ];
        } elseif ($today->diffInDays($kadaluarsa) <= 30) {
            $notifikasi[] = [
                'pesan' => "Obat <b>{$o->nama_obat}</b> hampir expired (" . $today->diffInDays($kadaluarsa) . " hari lagi)",
                'icon'  => 'fas fa-exclamation-triangle',
                'warna' => 'bg-warning'
            ];
        }
    }

    // Jika user sudah klik "tandai dibaca"
    if ($sudahDibaca) {
        // Hanya nolkan badge, bukan menghapus list notif
        $jumlah_notifikasi = 0;
    } else {
        $jumlah_notifikasi = count($notifikasi);
    }

    /**
     * -------------------------------------------------------
     *  BAGIAN LAPORAN PENJUALAN
     * -------------------------------------------------------
     */

    $title = 'Laporan Penjualan';
    $menuLaporanPenjualan = true;

    $periode = $request->input('periode');
    $tanggal_awal = $request->input('tanggal_awal');
    $tanggal_akhir = $request->input('tanggal_akhir');

    // Periode otomatis
    if ($periode) {
        switch ($periode) {
            case 'harian':
                $tanggal_awal = now()->startOfDay();
                $tanggal_akhir = now()->endOfDay();
                break;
            case 'mingguan':
                $tanggal_awal = now()->startOfWeek();
                $tanggal_akhir = now()->endOfWeek();
                break;
            case 'bulanan':
                $tanggal_awal = now()->startOfMonth();
                $tanggal_akhir = now()->endOfMonth();
                break;
            case 'tahunan':
                $tanggal_awal = now()->startOfYear();
                $tanggal_akhir = now()->endOfYear();
                break;
        }
    }

    // Query laporan
    $query = LaporanPenjualan::query();

    if ($tanggal_awal && $tanggal_akhir) {
        $start = Carbon::parse($tanggal_awal)->startOfDay();
        $end = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereBetween('periode_awal', [$start, $end]);
    }

    $laporans = $query->orderBy('created_at', 'desc')->get();

    // Detail transaksi
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
        ->when($tanggal_awal && $tanggal_akhir, function ($q) use ($tanggal_awal, $tanggal_akhir) {
            $q->whereBetween('penjualan.tanggal_transaksi', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ]);
        })
        ->orderBy('penjualan.tanggal_transaksi', 'desc')
        ->get();

    // Total pendapatan
    $totalPendapatan = $detailTransaksi->sum('total_harga');

    // Format tanggal input
    $tanggal_awal = $tanggal_awal ? Carbon::parse($tanggal_awal)->toDateString() : '';
    $tanggal_akhir = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->toDateString() : '';

    /**
     * -------------------------------------------------------
     *  RETURN VIEW
     * -------------------------------------------------------
     */

    return view('admin.laporan.laporanPenjualan.index', compact(
        'title',
        'menuLaporanPenjualan',
        'laporans',
        'detailTransaksi',
        'totalPendapatan',
        'periode',
        'tanggal_awal',
        'tanggal_akhir',
        'notifikasi',
        'jumlah_notifikasi',
    ));
}


    public function updateLaporanHarian()
    {
        $tanggalHariIni = Carbon::today();

        // 🔹 Hitung total transaksi (jumlah penjualan hari ini)
        $totalTransaksi = Penjualan::whereDate('tanggal_transaksi', $tanggalHariIni)->count();

        // 🔹 Hitung total pendapatan (penjumlahan total_harga)
        $totalPendapatan = Penjualan::whereDate('tanggal_transaksi', $tanggalHariIni)
            ->sum('total_harga');

        // 🔹 Hitung metode pembayaran terbanyak
        $metodeTerbanyak = Penjualan::whereDate('tanggal_transaksi', $tanggalHariIni)
            ->select('metode_pembayaran', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('metode_pembayaran')
            ->orderByDesc('jumlah')
            ->value('metode_pembayaran');

        // 🔹 Cari kasir dengan transaksi terbanyak
        $kasirTerbanyak = Penjualan::whereDate('tanggal_transaksi', $tanggalHariIni)
            ->select('nama_user', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('nama_user')
            ->orderByDesc('jumlah')
            ->value('nama_user');

        // 🔹 Simpan atau update ke tabel laporan_penjualan
        LaporanPenjualan::updateOrCreate(
            ['periode_awal' => $tanggalHariIni, 'periode_akhir' => $tanggalHariIni],
            [
                'total_transaksi' => $totalTransaksi,
                'total_pendapatan' => $totalPendapatan,
                'metode_terbanyak' => $metodeTerbanyak,
                'kasir_terbanyak' => $kasirTerbanyak,
                'updated_at' => now(),
            ]
        );

        return true;
    }

    // =====================================================
    // EXPORT PDF
    // =====================================================
    public function exportPdf(Request $request)
    {
        $periode = $request->periode;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        // Jika user pakai tombol periode otomatis
        if ($periode && (!$tanggal_awal || !$tanggal_akhir)) {
            switch ($periode) {
                case 'harian':
                    $tanggal_awal = now()->startOfDay();
                    $tanggal_akhir = now()->endOfDay();
                    break;
                case 'mingguan':
                    $tanggal_awal = now()->startOfWeek();
                    $tanggal_akhir = now()->endOfWeek();
                    break;
                case 'bulanan':
                    $tanggal_awal = now()->startOfMonth();
                    $tanggal_akhir = now()->endOfMonth();
                    break;
                case 'tahunan':
                    $tanggal_awal = now()->startOfYear();
                    $tanggal_akhir = now()->endOfYear();
                    break;
            }
        }

        // 🔥 DETAIL TRANSAKSI (bukan rekap)
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
            ->when($tanggal_awal && $tanggal_akhir, function ($q) use ($tanggal_awal, $tanggal_akhir) {
                $q->whereBetween('penjualan.tanggal_transaksi', [
                    Carbon::parse($tanggal_awal)->startOfDay(),
                    Carbon::parse($tanggal_akhir)->endOfDay()
                ]);
            })
            ->orderBy('penjualan.tanggal_transaksi', 'desc')
            ->get();

        // 🔥 TOTAL PENDAPATAN
        $totalPendapatan = $detailTransaksi->sum('total_harga');

        // Nama file PDF
        $filename = 'LaporanPenjualan_' . now()->format('d-m-Y_H.i.s') . '.pdf';

        // 🔥 KIRIM DATA KE VIEW PDF BARU
        $pdf = Pdf::loadView('admin.laporan.laporanPenjualan.pdf', [
            'detailTransaksi' => $detailTransaksi,
            'totalPendapatan' => $totalPendapatan,
            'periode' => $periode,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    // =====================================================
    // EXPORT EXCEL
    // =====================================================
    public function exportExcel(Request $request)
    {
        $periode = $request->periode;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        // ⏱ Isi otomatis tanggal jika periode dipilih
        if ($periode && (!$tanggal_awal || !$tanggal_akhir)) {
            switch ($periode) {
                case 'harian':
                    $tanggal_awal = now()->startOfDay();
                    $tanggal_akhir = now()->endOfDay();
                    break;
                case 'mingguan':
                    $tanggal_awal = now()->startOfWeek();
                    $tanggal_akhir = now()->endOfWeek();
                    break;
                case 'bulanan':
                    $tanggal_awal = now()->startOfMonth();
                    $tanggal_akhir = now()->endOfMonth();
                    break;
                case 'tahunan':
                    $tanggal_awal = now()->startOfYear();
                    $tanggal_akhir = now()->endOfYear();
                    break;
            }
        }

        $filename = 'LaporanPenjualan_' . now()->format('d-m-Y_H.i.s') . '.xlsx';

        return Excel::download(new LaporanPenjualanExport($tanggal_awal, $tanggal_akhir, $periode), $filename);
    }
}