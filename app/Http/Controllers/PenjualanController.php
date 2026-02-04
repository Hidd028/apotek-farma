<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function index()
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

    // Data untuk view
    $data = [
        'title' => 'Data Penjualan',
        'menuAdminPenjualan' => 'active',
        'penjualans' => Penjualan::with('details.obat')
                        ->orderBy('tanggal_transaksi', 'desc')
                        ->get(),

        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ];

    return $user->jabatan == 'Admin'
        ? view('admin.transaksi.penjualan.index', $data)->with('menuAdminPenjualan', 'active')
        : view('kasir.transaksi.penjualan.index', $data)->with('menuKasirPenjualan', 'active');
}

public function create()
{
    // --- Notifikasi sama seperti index() ---
    $user = Auth::user();
    $today = \Carbon\Carbon::today();
    $dibacaKey = 'notifikasi_dibaca_' . $user->id;
    $sudahDibaca = session($dibacaKey, false);

    $notifikasi = [];
    $obatsNotif = Obat::orderBy('stok', 'asc')->get();

    foreach ($obatsNotif as $o) {
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

    $jumlah_notifikasi = $sudahDibaca ? 0 : count($notifikasi);

    // Data tambahan create
    $lastPayment = Penjualan::orderBy('created_at', 'desc')->value('metode_pembayaran');

    $data = [
        'title' => 'Tambah Data Penjualan',
        'menuAdminPenjualan' => 'active',

        'obats' => Obat::orderBy('nama_obat', 'asc')->get(),
        'tanggal_transaksi' => now(),
        'lastPayment' => $lastPayment,

        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ];

    return $user->jabatan == 'Admin'
        ? view('admin.transaksi.penjualan.create', $data)->with('menuAdminPenjualan', 'active')
        : view('kasir.transaksi.penjualan.create', $data)->with('menuKasirPenjualan', 'active');
}

public function store(Request $request)
{
    $request->validate([
        'tanggal_transaksi' => 'required|date',
        'obat_id' => 'required|array|min:1',
        'jumlah' => 'required|array|min:1',
        'metode_pembayaran' => 'required|string',
    ]);

    // 🚫 CEK OBAT DUPLIKAT
    if (count($request->obat_id) !== count(array_unique($request->obat_id))) {
        return back()
            ->with('error', 'Obat yang sama tidak boleh dipilih lebih dari satu kali.')
            ->withInput();
    }

    DB::beginTransaction();

    try {
        // Simpan data utama penjualan
        $penjualan = Penjualan::create([
            'tanggal_transaksi' => now(),
            'nama_user' => auth()->user()->nama,
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_harga' => 0,
        ]);

        $grandTotal = 0;

        foreach ($request->obat_id as $key => $obat_id) {
            $obat = Obat::findOrFail($obat_id);
            $jumlah = (int) $request->jumlah[$key];
            $harga = $obat->harga;
            $total = $jumlah * $harga;

            if ($jumlah > $obat->stok) {
                throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi.");
            }

            // Simpan detail transaksi
            PenjualanDetail::create([
                'penjualan_id' => $penjualan->id,
                'obat_id' => $obat_id,
                'jumlah' => $jumlah,
                'harga_satuan' => $harga,
                'total_harga' => $total,
                'satuan' => $obat->satuan,
            ]);

            // Kurangi stok
            $obat->decrement('stok', $jumlah);

            $grandTotal += $total;
        }

        // Update total harga
        $penjualan->update(['total_harga' => $grandTotal]);

        DB::commit();

        // 🔄 Perbarui laporan otomatis
        app(\App\Http\Controllers\LaporanPenjualanController::class)->updateLaporanHarian();

        return redirect()->route('penjualan')
            ->with('success', 'Transaksi berhasil disimpan dan laporan harian diperbarui!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
    }
}

    public function edit($id)
    {
        // --- Notifikasi sama seperti index() ---
        $user = Auth::user();
        $today = \Carbon\Carbon::today();
        $dibacaKey = 'notifikasi_dibaca_' . $user->id;
        $sudahDibaca = session($dibacaKey, false);

        $notifikasi = [];
        $obatsNotif = Obat::orderBy('stok', 'asc')->get();

        foreach ($obatsNotif as $o) {
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

        $jumlah_notifikasi = $sudahDibaca ? 0 : count($notifikasi);

        // Data edit
        $penjualan = Penjualan::with('details.obat')->findOrFail($id);
        $lastPayment = Penjualan::orderBy('created_at', 'desc')->value('metode_pembayaran');

        $data = [
            'title' => 'Edit Data Penjualan',
            'menuAdminPenjualan' => 'active',

            'penjualan' => $penjualan,
            'obats' => Obat::orderBy('nama_obat', 'asc')->get(),
            'lastPayment' => $lastPayment,

            'notifikasi' => $notifikasi,
            'jumlah_notifikasi' => $jumlah_notifikasi,
        ];

        return view('admin.transaksi.penjualan.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'obat_id' => 'required|array|min:1',
            'jumlah' => 'required|array|min:1',
            'metode_pembayaran' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $penjualan = Penjualan::with('details')->findOrFail($id);

            // Kembalikan stok lama
            foreach ($penjualan->details as $detail) {
                $obat = Obat::find($detail->obat_id);
                if ($obat) {
                    $obat->increment('stok', $detail->jumlah);
                }
                $detail->delete();
            }

            $grandTotal = 0;

            foreach ($request->obat_id as $key => $obat_id) {
                if (empty($obat_id)) continue;

                $obat = Obat::findOrFail($obat_id);
                $jumlah = max(1, (int)$request->jumlah[$key]);
                $harga = $obat->harga;
                $total = $jumlah * $harga;

                if ($jumlah > $obat->stok) {
                    throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi (tersisa {$obat->stok}).");
                }

                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'obat_id' => $obat->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'total_harga' => $total,
                    'satuan' => $obat->satuan,
                ]);

                $obat->decrement('stok', $jumlah);
                $grandTotal += $total;
            }

            $penjualan->update([
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_harga' => $grandTotal,
                'nama_user' => auth()->user()->nama,
            ]);

            DB::commit();

            // 🔄 Perbarui laporan harian setelah update
            app(\App\Http\Controllers\LaporanPenjualanController::class)->updateLaporanHarian();

            return redirect()->route('penjualan')
                ->with('success', 'Data penjualan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $penjualan = Penjualan::with('details')->findOrFail($id);

            foreach ($penjualan->details as $detail) {
                $obat = Obat::find($detail->obat_id);
                if ($obat) {
                    $obat->increment('stok', $detail->jumlah);
                }
            }

            $penjualan->delete();
            DB::commit();

            // 🔄 Perbarui laporan harian setelah hapus
            app(\App\Http\Controllers\LaporanPenjualanController::class)->updateLaporanHarian();

            return redirect()->route('penjualan')
                ->with('success', 'Data penjualan berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}