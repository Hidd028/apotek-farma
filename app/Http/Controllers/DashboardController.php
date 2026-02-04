<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // ========================
        // NOTIFIKASI STOK & EXP
        // ========================
        $dibacaKey = 'notifikasi_dibaca_' . $user->id;
        $sudahDibaca = session($dibacaKey, false);

        $notifikasi = [];
        $obats = Obat::orderBy('stok', 'asc')->get();

        foreach ($obats as $o) {
            if (!$o->tanggal_kadaluarsa) continue;

            $kadaluarsa = Carbon::parse($o->tanggal_kadaluarsa);

            if ($kadaluarsa->lt($today)) {
                $notifikasi[] = [
                    'pesan' => "Obat <b>{$o->nama_obat}</b> sudah expired!",
                    'icon' => 'fas fa-times-circle',
                    'warna' => 'bg-danger'
                ];
            } elseif ($today->diffInDays($kadaluarsa) <= 30) {
                $notifikasi[] = [
                    'pesan' => "Obat <b>{$o->nama_obat}</b> hampir expired (" . $today->diffInDays($kadaluarsa) . " hari lagi)",
                    'icon' => 'fas fa-exclamation-triangle',
                    'warna' => 'bg-warning'
                ];
            }
        }

        $jumlah_notifikasi = $sudahDibaca ? 0 : count($notifikasi);

        // ========================
        // GRAFIK PENJUALAN PER BULAN (JAN - DES)
        // ========================
        $tahun = date('Y');

        $grafikDb = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan_detail.penjualan_id', '=', 'penjualan.id')
            ->selectRaw("
                DATE_FORMAT(penjualan.tanggal_transaksi, '%m') AS bulan,
                SUM(penjualan_detail.total_harga) AS total_penjualan
            ")
            ->whereYear('penjualan.tanggal_transaksi', $tahun)
            ->groupBy('bulan')
            ->pluck('total_penjualan', 'bulan')
            ->toArray();

        $bulan = [];
        $nilai = [];

        for ($i = 1; $i <= 12; $i++) {
            $key = str_pad($i, 2, '0', STR_PAD_LEFT);

            $bulan[] = Carbon::createFromDate($tahun, $i, 1)
                        ->locale('id')
                        ->translatedFormat('M');

            $nilai[] = isset($grafikDb[$key]) ? (float)$grafikDb[$key] : 0;
        }

        // ========================
        // TOP 5 OBAT TERLARIS
        // ========================
        $obatTerlaris = DB::table('penjualan_detail')
            ->join('obats', 'penjualan_detail.obat_id', '=', 'obats.id')
            ->select(
                'obats.id',
                'obats.nama_obat',
                DB::raw('SUM(penjualan_detail.jumlah) AS total_terjual')
            )
            ->groupBy('obats.id', 'obats.nama_obat')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // ========================
        // KIRIM KE VIEW
        // ========================
        return view('dashboard', [
            'title' => 'Beranda',
            'menuDashboard' => 'active',
            'jumlahUser' => User::count(),
            'jumlahAdmin' => User::where('jabatan', 'Admin')->count(),
            'jumlahKasir' => User::where('jabatan', 'Kasir')->count(),
            'jumlahObat' => Obat::count(),
            'notifikasi' => $notifikasi,
            'jumlah_notifikasi' => $jumlah_notifikasi,
            'bulan' => $bulan,
            'nilai' => $nilai,
            'obatTerlaris' => $obatTerlaris,
        ]);        
    }
}