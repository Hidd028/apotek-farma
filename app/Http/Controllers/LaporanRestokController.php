<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Obat;
use App\Models\RestokObat;
use Illuminate\Http\Request;
use App\Models\LaporanRestok;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanRestokExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanRestokController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();

    // Key status tanda dibaca per user
    $dibacaKey = 'notifikasi_dibaca_' . $user->id;
    $sudahDibaca = session($dibacaKey, false);

    // --- Hitung notifikasi real-time ---
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

    // Jika user sudah klik "Tandai Dibaca"
    if ($sudahDibaca) {
        // Hanya nolkan badge, bukan menghapus list notif
        $jumlah_notifikasi = 0;
    } else {
        $jumlah_notifikasi = count($notifikasi);
    }

    // -------------------------------------------------------
    //  BAGIAN LAPORAN RESTOK (PUNYA ANDA, TIDAK DIUBAH)
    // -------------------------------------------------------

    $title = 'Laporan Restok Obat';
    $menuLaporanRestok = true;

    $periode = $request->input('periode');
    $tanggal_awal = $request->input('tanggal_awal');
    $tanggal_akhir = $request->input('tanggal_akhir');

    // Auto-set tanggal berdasarkan periode
    if ($periode) {
        switch ($periode) {
            case 'harian':
                $tanggal_awal = now()->startOfDay()->format('Y-m-d');
                $tanggal_akhir = now()->endOfDay()->format('Y-m-d');
                break;
            case 'mingguan':
                $tanggal_awal = now()->startOfWeek()->format('Y-m-d');
                $tanggal_akhir = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'bulanan':
                $tanggal_awal = now()->startOfMonth()->format('Y-m-d');
                $tanggal_akhir = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'tahunan':
                $tanggal_awal = now()->startOfYear()->format('Y-m-d');
                $tanggal_akhir = now()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    // Query restok + relasi
    $query = RestokObat::with(['obat', 'supplier', 'user']);

    if ($tanggal_awal && $tanggal_akhir) {
        $start = Carbon::parse($tanggal_awal)->startOfDay();
        $end = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereBetween('tanggal_masuk', [$start, $end]);
    }

    $restoks = $query->orderBy('tanggal_masuk', 'desc')->get();

    if ($restoks->isEmpty()) {
        return view('admin.laporan.laporanRestok.index', [
            'title' => $title,
            'menuLaporanRestok' => $menuLaporanRestok,
            'laporans' => collect(),
            'periode' => $periode,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'notifikasi' => $notifikasi,
        ]);
    }

    // Konversi setiap transaksi menjadi 1 baris laporan
    $laporans = collect();

    foreach ($restoks as $r) {
        $laporans->push((object)[
            'periode_awal' => Carbon::parse($r->tanggal_masuk)->format('Y-m-d'),
            'periode_akhir' => Carbon::parse($r->tanggal_masuk)->format('Y-m-d'),
            'nama_obat' => $r->obat->nama_obat ?? '-',  // <--- ini wajib
            'jumlah' => $r->jumlah,
            'total_pengeluaran' => $r->harga_beli, // sesuai permintaan
            'supplier_terbanyak' => $r->supplier->nama_supplier ?? '-',
            'petugas_terbanyak' => $r->user->nama ?? '-',
            'created_at' => $r->created_at,
        ]);
    }

    $laporans = $laporans->sortByDesc('periode_awal')->values();

    return view('admin.laporan.laporanRestok.index', compact(
        'title',
        'menuLaporanRestok',
        'laporans',
        'periode',
        'tanggal_awal',
        'tanggal_akhir',
        'notifikasi',
        'jumlah_notifikasi',
    ));
}

    // =====================================================
    // EXPORT PDF
    // =====================================================
    public function exportPdf(Request $request)
{
    $periode = $request->periode;
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    // Isi otomatis tanggal jika periode dipilih
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

    // Ambil data RestokObat
    $restoks = RestokObat::with(['obat', 'supplier', 'user'])
    ->when($tanggal_awal && $tanggal_akhir, function ($q) use ($tanggal_awal, $tanggal_akhir) {
        $start = Carbon::parse($tanggal_awal)->startOfDay();
        $end = Carbon::parse($tanggal_akhir)->endOfDay();
        $q->whereBetween('tanggal_masuk', [$start, $end]);
    })
    ->orderBy('tanggal_masuk', 'desc')
    ->get();

    // 🔁 Buat $laporans agar Blade PDF bisa pakai
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

    $filename = 'LaporanRestok_' . now()->format('d-m-Y_H.i.s') . '.pdf';
    $pdf = Pdf::loadView('admin.laporan.laporanRestok.pdf', compact('laporans', 'periode', 'tanggal_awal', 'tanggal_akhir'))
        ->setPaper('a4', 'landscape');

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

        // Isi otomatis tanggal jika periode dipilih
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

        $filename = 'LaporanRestok_' . now()->format('d-m-Y_H.i.s') . '.xlsx';
        return Excel::download(new LaporanRestokExport($tanggal_awal, $tanggal_akhir, $periode), $filename);
    }
}