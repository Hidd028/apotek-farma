<?php

namespace App\Http\Controllers;

use App\Models\RestokObat;
use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RestokObatController extends Controller
{

    private function generateNotifikasi()
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();

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
                'pesan' => "Obat <b>{$o->nama_obat}</b> hampir expired (" . 
                           $today->diffInDays($kadaluarsa) . " hari lagi)",
                'icon'  => 'fas fa-exclamation-triangle',
                'warna' => 'bg-warning'
            ];
        }
    }

    $jumlah = $sudahDibaca ? 0 : count($notifikasi);

    return [
        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah
    ];
}

    // 🟢 TAMPILKAN SEMUA DATA RESTOK
    public function index()
{
    // Data untuk view
    $user = Auth::user();
    
    $notif = $this->generateNotifikasi();

    $data = [
        'title' => 'Data Restok Obat',
        'menuAdminRestok' => 'active',
        'restoks' => RestokObat::with(['obat', 'supplier', 'user'])
                        ->orderBy('tanggal_masuk', 'desc')
                        ->get(),

        'notifikasi' => $notif['notifikasi'],
        'jumlah_notifikasi' => $notif['jumlah_notifikasi'],
    ];

    return $user->jabatan == 'Admin'
        ? view('admin.transaksi.restok.index', $data)->with('menuAdminRestok', 'active')
        : view('kasir.transaksi.restokObat.index', $data)->with('menuKasirRestok', 'active');
}

    // 🟢 FORM TAMBAH
    public function create()
    {
        $notif = $this->generateNotifikasi();

        $data = [
            'title' => 'Tambah Restok Obat',
            'obats' => Obat::orderBy('nama_obat')->get(),
            'suppliers' => Supplier::orderBy('nama_supplier')->get(),
            'tanggal_masuk' => now()->format('Y-m-d'),

            'notifikasi' => $notif['notifikasi'],
            'jumlah_notifikasi' => $notif['jumlah_notifikasi'],
        ];

        return view('admin.transaksi.restok.create', $data);
    }

    // 🟢 SIMPAN DATA RESTOK BARU
    public function store(Request $request)
    {
        $validated = $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'obat_id.required' => 'Nama obat wajib dipilih.',
            'obat_id.exists' => 'Obat yang dipilih tidak valid.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah masuk wajib diisi.',
            'jumlah.integer' => 'Jumlah masuk harus berupa angka.',
            'jumlah.min' => 'Jumlah masuk minimal 1.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.numeric' => 'Harga beli harus berupa angka.',
            'harga_beli.min' => 'Harga beli tidak boleh negatif.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Tanggal masuk harus berupa tanggal yang valid.',
            'keterangan.string' => 'Keterangan harus berupa teks.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ]);

        $validated['user_id'] = Auth::id();

        $validated['tanggal_masuk'] = $validated['tanggal_masuk'] . ' ' . now()->format('H:i:s');

        // Simpan data restok
        $restok = RestokObat::create($validated);

        // Update stok obat
        $obat = Obat::findOrFail($validated['obat_id']);
        $obat->stok += $validated['jumlah'];
        $obat->save();

        return redirect()->route('restok')->with('success', 'Data restok berhasil disimpan dan stok obat diperbarui.');
    }

    // 🟢 FORM EDIT
    public function edit($id)
    {
        $notif = $this->generateNotifikasi();

        $data = [
            'title' => 'Edit Restok Obat',
            'restok' => RestokObat::findOrFail($id),
            'obats' => Obat::orderBy('nama_obat')->get(),
            'suppliers' => Supplier::orderBy('nama_supplier')->get(),

            'notifikasi' => $notif['notifikasi'],
            'jumlah_notifikasi' => $notif['jumlah_notifikasi'],
        ];

        return view('admin.transaksi.restok.edit', $data);
    }

    // 🟢 UPDATE DATA RESTOK
    public function update(Request $request, $id)
    {
        $restok = RestokObat::findOrFail($id);

        $validated = $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'obat_id.required' => 'Nama obat wajib dipilih.',
            'obat_id.exists' => 'Obat yang dipilih tidak valid.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah masuk wajib diisi.',
            'jumlah.integer' => 'Jumlah masuk harus berupa angka.',
            'jumlah.min' => 'Jumlah masuk minimal 1.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.numeric' => 'Harga beli harus berupa angka.',
            'harga_beli.min' => 'Harga beli tidak boleh negatif.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Tanggal masuk harus berupa tanggal yang valid.',
            'keterangan.string' => 'Keterangan harus berupa teks.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ]);

        // Hitung selisih jumlah untuk update stok
        $selisih = $validated['jumlah'] - $restok->jumlah;

        $validated['user_id'] = Auth::id();

        $validated['tanggal_masuk'] = $restok->tanggal_masuk;

        // Update stok obat
        $obat = Obat::findOrFail($validated['obat_id']);
        $obat->stok += $selisih;
        if ($obat->stok < 0) $obat->stok = 0;
        $obat->save();

        // Update data restok
        $restok->update($validated);

        return redirect()->route('restok')->with('success', 'Data restok berhasil diperbarui dan stok obat disesuaikan.');
    }

    // 🟢 HAPUS DATA RESTOK
    public function destroy($id)
    {
        $restok = RestokObat::findOrFail($id);

        // Kurangi stok obat saat restok dihapus
        if ($restok->obat) {
            $restok->obat->stok -= $restok->jumlah;
            if ($restok->obat->stok < 0) $restok->obat->stok = 0;
            $restok->obat->save();
        }

        $restok->delete();

        return redirect()->route('restok')->with('success', 'Data restok berhasil dihapus dan stok obat diperbarui.');
    }
}
