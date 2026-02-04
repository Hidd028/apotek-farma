<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Exports\SupplierExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
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
            'title' => 'Data Supplier',
            'menuAdminSupplier' => 'active',
            'supplier' => Supplier::orderBy('nama_supplier', 'asc')->get(),

            'notifikasi' => $notifikasi,
            'jumlah_notifikasi' => $jumlah_notifikasi,
        ];

        return $user->jabatan == 'Admin'
        ? view('admin.supplier.index', $data)->with('menuAdminSupplier', 'active')
        : view('kasir.supplier.index', $data)->with('menuKasirSupplier', 'active');
    }

    public function create()
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();
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

    $jumlah_notifikasi = $sudahDibaca ? 0 : count($notifikasi);

    return view('admin/supplier/create', [
        'title' => 'Tambah Data Supplier',
        'menuAdminSupplier' => 'active',
        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'kode_supplier' => 'required|unique:suppliers,kode_supplier',
            'nama_supplier' => 'required',
            'alamat' => 'required|string',
            'telepon' => [
                'required',
                'string',
                'max:20',
                'unique:suppliers,telepon',
                function ($attribute, $value, $fail) {
                    if (DB::table('users')->where('nomor_hp', $value)->exists()) {
                        $fail('Nomor telepon sudah digunakan.');
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                'unique:suppliers,email',
                function ($attribute, $value, $fail) {
                    if (DB::table('users')->where('email', $value)->exists()) {
                        $fail('Email sudah digunakan.');
                    }
                },
            ],
        ], [
            'kode_supplier.required' => 'Kode supplier tidak boleh kosong',
            'kode_supplier.unique' => 'Kode supplier sudah terdaftar',
            'nama_supplier.required' => 'Nama supplier tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'telepon.required' => 'Nomor telepon tidak boleh kosong',
            'telepon.unique' => 'Nomor telepon sudah terdaftar di supplier lain',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar di supplier lain',
        ]);

        Supplier::create($request->all());

        return redirect()->route('supplier')->with('success', 'Data supplier berhasil ditambahkan');
    }

    public function edit($id)
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();
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

    $jumlah_notifikasi = $sudahDibaca ? 0 : count($notifikasi);

    return view('admin/supplier/edit', [
        'title' => 'Edit Data Supplier',
        'menuAdminSupplier' => 'active',
        'supplier' => Supplier::findOrFail($id),
        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ]);
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_supplier' => 'required|unique:suppliers,kode_supplier,'.$id,
            'nama_supplier' => 'required',
            'alamat' => 'required|string',
            'telepon' => [
                'required',
                'string',
                'max:20',
                'unique:suppliers,telepon,'.$id,
                function ($attribute, $value, $fail) use ($id) {
                    if (DB::table('users')->where('nomor_hp', $value)->exists()) {
                        $fail('Nomor telepon sudah digunakan.');
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                'unique:suppliers,email,'.$id,
                function ($attribute, $value, $fail) use ($id) {
                    if (DB::table('users')->where('email', $value)->exists()) {
                        $fail('Email sudah digunakan.');
                    }
                },
            ],
        ], [
            'kode_supplier.required' => 'Kode supplier tidak boleh kosong',
            'kode_supplier.unique' => 'Kode supplier sudah terdaftar',
            'nama_supplier.required' => 'Nama supplier tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'telepon.required' => 'Nomor telepon tidak boleh kosong',
            'telepon.unique' => 'Nomor telepon sudah terdaftar di supplier lain',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar di supplier lain',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return redirect()->route('supplier')->with('success', 'Data supplier berhasil diperbarui');
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return redirect()->route('supplier')->with('success', 'Data supplier berhasil dihapus');
    }

    public function excel()
    {
        $filename = now()->format('d-m-Y_H.i.s');
        return Excel::download(new SupplierExport, 'DataSupplier_'.$filename.'.xlsx');
    }

    public function pdf()
    {
        $filename = now()->format('d-m-Y_H.i.s');
        $data = ['supplier' => Supplier::get()];
        $pdf = Pdf::loadView('admin/supplier/pdf', $data);
        return $pdf->setPaper('a4', 'portrait')->stream('DataSupplier_'.$filename.'.pdf');
    }
}