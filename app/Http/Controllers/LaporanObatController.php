<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\RestokObat;
use Illuminate\Http\Request;
use App\Models\PenjualanDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanObatExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanObatController extends Controller
{

    public function index(Request $request)
{
    $title = 'Laporan Obat';
    $menuLaporanObat = true;

    /**
     * ======================================================
     * NOTIFIKASI
     * ======================================================
     */
    $user = Auth::user();
    $today = Carbon::today();

    $dibacaKey = 'notifikasi_dibaca_' . $user->id;
    $sudahDibaca = session($dibacaKey, false);

    $notifikasi = [];
    $obatNotif = Obat::orderBy('stok', 'asc')->get();

    foreach ($obatNotif as $o) {
        if (!$o->tanggal_kadaluarsa) continue;

        $kadaluarsa = Carbon::parse($o->tanggal_kadaluarsa);

        if ($kadaluarsa->lt($today)) {
            $notifikasi[] = [
                'pesan' => "Obat <b>{$o->nama_obat}</b> sudah expired!",
                'icon'  => 'fas fa-times-circle',
                'warna' => 'bg-danger'
            ];
        } elseif ($today->diffInDays($kadaluarsa) <= 30) {
            $notifikasi[] = [
                'pesan' => "Obat <b>{$o->nama_obat}</b> hampir expired (" .
                    $today->diffInDays($kadaluarsa) . " hari lagi)",
                'icon'  => 'fas fa-exclamation-triangle',
                'warna' => 'bg-warning'
            ];
        }
    }

    if ($sudahDibaca) {
        // Hanya nolkan badge, bukan menghapus list notif
        $jumlah_notifikasi = 0;
    } else {
        $jumlah_notifikasi = count($notifikasi);
    }    
    
    /**
     * ======================================================
     * END NOTIFIKASI
     * ======================================================
     */

    $periode = $request->periode;
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    /**
     * -------------------------------------------------------
     * 1. Tentukan tanggal berdasarkan periode
     * -------------------------------------------------------
     */
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

    /**
     * -------------------------------------------------------
     * 2. Reset default jika tidak ada filter
     * -------------------------------------------------------
     */
    if (!$tanggal_awal && !$tanggal_akhir && !$periode) {

        $earliestRestok = RestokObat::min('tanggal_masuk');
        $latestRestok   = RestokObat::max('tanggal_masuk');

        $earliestPenjualan = Penjualan::min('tanggal_transaksi');
        $latestPenjualan   = Penjualan::max('tanggal_transaksi');

        $start = collect([$earliestRestok, $earliestPenjualan])->filter()->min();
        $end   = collect([$latestRestok, $latestPenjualan])->filter()->max();

        $start = $start ? Carbon::parse($start)->startOfDay() : now()->startOfDay();
        $end   = $end ? Carbon::parse($end)->endOfDay()   : now()->endOfDay();

        $tanggal_awal_formatted = '';
        $tanggal_akhir_formatted = '';

    } else {

        $start = $tanggal_awal
            ? Carbon::parse(str_replace('/', '-', $tanggal_awal))->startOfDay()
            : now()->startOfMonth();

        $end = $tanggal_akhir
            ? Carbon::parse(str_replace('/', '-', $tanggal_akhir))->endOfDay()
            : now()->endOfMonth();

        $tanggal_awal_formatted = $tanggal_awal ? $start->format('Y-m-d') : '';
        $tanggal_akhir_formatted = $tanggal_akhir ? $end->format('Y-m-d') : '';
    }

    /**
     * -------------------------------------------------------
     * 3. Ambil seluruh obat untuk pengecekan stok
     * -------------------------------------------------------
     */
    $obats = Obat::all();
    $laporans = collect();

    foreach ($obats as $obat) {

        $stok_masuk = RestokObat::where('obat_id', $obat->id)
            ->whereBetween('tanggal_masuk', [$start, $end])
            ->sum('jumlah');

        $stok_keluar = PenjualanDetail::where('obat_id', $obat->id)
            ->whereHas('penjualan', function ($query) use ($start, $end) {
                $query->whereBetween('tanggal_transaksi', [$start, $end]);
            })
            ->sum('jumlah');

        if ($stok_masuk == 0 && $stok_keluar == 0) {
            continue;
        }

        $latestRestok = RestokObat::where('obat_id', $obat->id)->max('tanggal_masuk');

        $latestPenjualan = PenjualanDetail::where('obat_id', $obat->id)
            ->join('penjualan', 'penjualan_detail.penjualan_id', '=', 'penjualan.id')
            ->max('penjualan.tanggal_transaksi');

        $tanggal_terbaru = collect([$latestRestok, $latestPenjualan])->filter()->max();

        $today = now()->toDateString();

        $adaRestokHariIni = RestokObat::where('obat_id', $obat->id)
            ->whereDate('tanggal_masuk', $today)
            ->exists();

        $adaPenjualanHariIni = PenjualanDetail::where('obat_id', $obat->id)
            ->join('penjualan', 'penjualan_detail.penjualan_id', '=', 'penjualan.id')
            ->whereDate('penjualan.tanggal_transaksi', $today)
            ->exists();

        if ($adaRestokHariIni || $adaPenjualanHariIni) {
            $tanggal_terbaru = now();
        }

        $stok_awal  = $obat->stok + $stok_keluar - $stok_masuk;
        $stok_akhir = $obat->stok;
        $total_nilai = $stok_akhir * $obat->harga;

        $laporans->push([
            'periode_awal' => $start->format('d/m/Y'),
            'periode_akhir' => $end->format('d/m/Y'),
            'nama_obat' => $obat->nama_obat,
            'kategori' => $obat->kategori,
            'stok_awal' => $stok_awal,
            'jumlah_masuk' => $stok_masuk,
            'jumlah_keluar' => $stok_keluar,
            'stok_akhir' => $stok_akhir,
            'harga_jual' => $obat->harga,
            'total_nilai' => $total_nilai,
            'tanggal_terbaru' => $tanggal_terbaru
                ? Carbon::parse($tanggal_terbaru)->format('d/m/Y H:i:s')
                : '-',
        ]);
    }

    /**
     * -------------------------------------------------------
     * 4. Format tampilan
     * -------------------------------------------------------
     */
    $periode_tampil = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');
    $showTanggalFilter = ($periode || ($tanggal_awal && $tanggal_akhir));

    return view('admin.laporan.laporanObat.index', compact(
        'title',
        'menuLaporanObat',
        'laporans',
        'periode',
        'periode_tampil',
        'tanggal_awal_formatted',
        'tanggal_akhir_formatted',
        'showTanggalFilter',
        'notifikasi',
        'jumlah_notifikasi'
    ));
}

    // =====================================================
    // EXPORT PDF
    // =====================================================
    public function exportPdf(Request $request)
{
    // Ambil parameter filter
    $periode = $request->periode;
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    // Otomatis isi tanggal berdasarkan periode (sama dengan index)
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

    // ✅ Ambil data langsung dari method index() agar hasil identik dengan Excel
    $laporans = app(LaporanObatController::class)
        ->index($request)
        ->getData()['laporans'];

    // Siapkan nama file
    $filename = 'Laporan_Obat_' . now()->format('d-m-Y_H.i.s') . '.pdf';

    // ✅ Load view yang sama, dengan variabel yang sama
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.laporanObat.pdf', compact(
        'laporans',
        'periode',
        'tanggal_awal',
        'tanggal_akhir'
    ))->setPaper('a4', 'landscape');

    return $pdf->download($filename);
}

    // =====================================================
    // EXPORT EXCEL
    // =====================================================
    public function exportExcel(Request $request)
{
    // ambil parameter filter
    $periode = $request->periode;
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    // proses tanggal sesuai logic index()
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

    // Jalankan logika index() agar hasil perhitungan sama
    $laporans = app(LaporanObatController::class)->index($request)->getData()['laporans'];

    // Kirim ke export view Excel
    $filename = 'Laporan_Obat_' . now()->format('d-m-Y_H.i.s') . '.xlsx';
    return Excel::download(new \App\Exports\LaporanObatExport($laporans), $filename);
}
}