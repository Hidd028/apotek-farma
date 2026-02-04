<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\ObatExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ObatController extends Controller
{
    // 🧩 1. Tampilkan data obat
    public function index()
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();

    // Key status baca per user
    $dibacaKey = 'notifikasi_dibaca_' . $user->id;

    // STATUS: apakah user sudah baca notif?
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

    // Jika sudah dibaca → jangan tampilkan notif
    if ($sudahDibaca) {
        // Hanya nolkan badge, bukan menghapus list notif
        $jumlah_notifikasi = 0;
    } else {
        $jumlah_notifikasi = count($notifikasi);
    }

    $data = [
        'title' => 'Data Obat',
        'obat' => $obats,
        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ];

    return $user->jabatan == 'Admin'
        ? view('admin.obat.index', $data)->with('menuAdminObat', 'active')
        : view('kasir.obat.index', $data)->with('menuKasirObat', 'active');
}

    // 🧩 2. Form tambah obat
    public function create()
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();
    $dibacaKey = 'notifikasi_dibaca_' . $user->id;
    $sudahDibaca = session($dibacaKey, false);

    // --- Hitung notifikasi ---
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
                'pesan' => "Obat <b>{$o->nama_obat}</b> hampir expired ({$today->diffInDays($kadaluarsa)} hari lagi)",
                'icon'  => 'fas fa-exclamation-triangle',
                'warna' => 'bg-warning'
            ];
        }
    }

    $jumlah_notifikasi = $sudahDibaca ? 0 : count($notifikasi);

    $data = [
        'title' => 'Tambah Data Obat',
        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ];

    if ($user->jabatan == 'Admin') {
        $data['menuAdminObat'] = 'active';
        return view('admin.obat.create', $data);
    }
}

    // 🧩 3. Simpan data obat (Tambah)
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255|unique:obats,nama_obat',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'tanggal_kadaluarsa' => 'required|date|after:today',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'nama_obat.unique' => 'Nama obat sudah terdaftar, silakan gunakan nama lain.',
            'kategori.required' => 'Kategori obat wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'satuan.required' => 'Satuan obat wajib diisi.',
            'satuan.string' => 'Satuan harus berupa teks.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi.',
            'tanggal_kadaluarsa.date' => 'Tanggal kadaluarsa harus berupa tanggal yang valid.',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus lebih dari tanggal hari ini.',
            'gambar.required' => 'Gambar obat wajib diunggah.',
            'gambar.image' => 'File gambar harus berupa format JPG, JPEG, atau PNG.',
            'gambar.mimes' => 'Format gambar tidak valid (hanya JPG, JPEG, PNG).',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);              

        DB::beginTransaction();
        try {
            $data = $request->only([
                'nama_obat', 'kategori', 'stok', 'harga', 'satuan', 'tanggal_kadaluarsa'
            ]);

            // ✅ Upload gambar jika ada
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/obat'), $namaFile);
                $data['gambar'] = $namaFile;
            }

            // ✅ Simpan user yang menambahkan
            $data['updated_by'] = Auth::id();

            Obat::create($data);

            DB::commit();
            return redirect()->route('obat')->with('success', 'Data obat berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 🧩 4. Form edit obat
    public function edit(Obat $obat)
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();
    $dibacaKey = 'notifikasi_dibaca_' . $user->id;
    $sudahDibaca = session($dibacaKey, false);

    // --- Hitung notifikasi ---
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
                'pesan' => "Obat <b>{$o->nama_obat}</b> hampir expired ({$today->diffInDays($kadaluarsa)} hari lagi)",
                'icon'  => 'fas fa-exclamation-triangle',
                'warna' => 'bg-warning'
            ];
        }
    }

    $jumlah_notifikasi = $sudahDibaca ? 0 : count($notifikasi);

    $data = [
        'title' => 'Edit Data Obat',
        'obat' => $obat,
        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ];

    if ($user->jabatan == 'Admin') {
        $data['menuAdminObat'] = 'active';
        return view('admin.obat.edit', $data);
    }
}

    // 🧩 5. Update data obat
    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255|unique:obats,nama_obat,' . $obat->id,
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'tanggal_kadaluarsa' => 'required|date|after:today',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'nama_obat.unique' => 'Nama obat sudah terdaftar, silakan gunakan nama lain.',
            'kategori.required' => 'Kategori obat wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'satuan.required' => 'Satuan obat wajib diisi.',
            'satuan.string' => 'Satuan harus berupa teks.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi.',
            'tanggal_kadaluarsa.date' => 'Tanggal kadaluarsa harus berupa tanggal yang valid.',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus lebih dari tanggal hari ini.',
            'gambar.image' => 'File gambar harus berupa format JPG, JPEG, atau PNG.',
            'gambar.mimes' => 'Format gambar tidak valid (hanya JPG, JPEG, PNG).',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);               

        DB::beginTransaction();
        try {
            $data = $request->only([
                'nama_obat', 'kategori', 'stok', 'satuan', 'harga', 'tanggal_kadaluarsa'
            ]);

            // ✅ Jika ada gambar baru, hapus lama
            if ($request->hasFile('gambar')) {
                if ($obat->gambar && file_exists(public_path('images/obat/' . $obat->gambar))) {
                    unlink(public_path('images/obat/' . $obat->gambar));
                }

                $file = $request->file('gambar');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/obat'), $namaFile);
                $data['gambar'] = $namaFile;
            }

            // ✅ Simpan siapa yang memperbarui
            $data['updated_by'] = Auth::id();

            $obat->update($data);

            DB::commit();
            return redirect()->route('obat')->with('success', 'Data obat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 🧩 6. Hapus data obat
    public function destroy(Obat $obat)
    {
        if ($obat->gambar && file_exists(public_path('images/obat/' . $obat->gambar))) {
            unlink(public_path('images/obat/' . $obat->gambar));
        }

        $obat->delete();

        return redirect()->route('obat')->with('success', 'Data obat berhasil dihapus.');
    }
}