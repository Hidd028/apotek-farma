<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UserExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Obat;

class UserController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();

    // Key status baca per user
    $dibacaKey = 'notifikasi_dibaca_' . $user->id;

    // STATUS apakah user sudah baca notif?
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
        'title' => 'Data User',
        'menuAdminUser' => 'active',
        'user' => User::orderBy('jabatan', 'asc')->get(),

        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ];

    return view('admin/user/index', $data);
}   

public function create()
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();
    $dibacaKey = 'notifikasi_dibaca_' . $user->id;
    $sudahDibaca = session($dibacaKey, false);

    // Hitung notifikasi
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

    return view('admin/user/create', [
        'title' => 'Tambah Data User',
        'menuAdminUser' => 'active',
        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ]);
}

    public function store(Request $request){
    $request->validate([
        'nama' => 'required',
        'email' => 'required|email|unique:users,email',
        'nomor_hp' => 'required|unique:users,nomor_hp',
        'jabatan' => 'required',
        'password' => 'required|min:8|confirmed', // otomatis cek password_confirmation
    ], [
        'nama.required' => 'Nama Tidak Boleh Kosong',
        'email.required' => 'Email Tidak Boleh Kosong',
        'email.email' => 'Format Email Tidak Valid',
        'email.unique' => 'Email Sudah Terdaftar',
        'nomor_hp.required' => 'Nomor Tidak Boleh Kosong',
        'nomor_hp.unique' => 'Nomor HP Sudah Terdaftar',
        'jabatan.required' => 'Jabatan Harus Dipilih',

        // password
        'password.required' => 'Password Tidak Boleh Kosong',
        'password.min' => 'Password Minimal 8 Karakter',
        'password.confirmed' => 'Password Konfirmasi Tidak Sama',
    ]);

        $user = new User;
        $user->nama    = $request->nama;
        $user->email   = $request->email;
        $user->nomor_hp = $request->nomor_hp;
        $user->jabatan = $request->jabatan;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
{
    $user = Auth::user();
    $today = \Carbon\Carbon::today();
    $dibacaKey = 'notifikasi_dibaca_' . $user->id;
    $sudahDibaca = session($dibacaKey, false);

    // Hitung notifikasi
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

    return view('admin/user/edit', [
        'title' => 'Edit Data User',
        'menuAdminUser' => 'active',
        'user' => User::findOrFail($id),

        'notifikasi' => $notifikasi,
        'jumlah_notifikasi' => $jumlah_notifikasi,
    ]);
}

    public function update(Request $request, $id){
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'nomor_hp' => 'required|unique:users,nomor_hp,'.$id,
            'jabatan' => 'required',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'nama.required' => 'Nama Tidak Boleh Kosong',
            'email.required' => 'Email Tidak Boleh Kosong',
            'email.email' => 'Format Email Tidak Valid',
            'email.unique' => 'Email Sudah Terdaftar',
            'nomor_hp.required' => 'Nomor Tidak Boleh Kosong',
            'nomor_hp.unique' => 'Nomor HP Sudah Terdaftar',
            'jabatan.required' => 'Jabatan Harus Dipilih',
            'password.min' => 'Password Minimal 8 Karakter',
            'password.confirmed' => 'Password Konfirmasi Tidak Sama',
        ]);
    
            $user = User::findOrFail($id);
            $user->nama    = $request->nama;
            $user->email   = $request->email;
            $user->nomor_hp = $request->nomor_hp;
            $user->jabatan = $request->jabatan;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
    
            return redirect()->route('user')->with('success', 'Data berhasil Di Edit');
        } 
        
        public function destroy($id){
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('user')->with('success', 'Data berhasil Di Hapus');
        }

        public function excel(){
            $filename = now()->format('d-m-Y_H.i.s');
            return Excel::download(new UserExport, 'DataUser_'.$filename.'.xlsx');
        }

        public function pdf(){
            $filename = now()->format('d-m-Y_H.i.s');
            $data = array(
            'user' => User::get(),
            );

            $pdf = Pdf::loadView('admin/user/pdf', $data);
            return $pdf->setPaper('a4', 'portrait')->stream('DataUser_'.$filename.'.pdf');
        }

        public function editProfil()
{
    $user = auth()->user();
    $title = 'Pengaturan Profil';
    return view('admin.user.profil', compact('user', 'title'));
}

public function updateProfil(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'nama' => 'required|string|max:255',
        'nomor_hp' => 'required|string|max:20|unique:users,nomor_hp,' . $user->id,
        'password' => 'nullable|min:8|confirmed',
    ], [
        'nama.required' => 'Nama Tidak Boleh Kosong',
        'nomor_hp.required' => 'Nomor HP Tidak Boleh Kosong',
        'nomor_hp.unique' => 'Nomor HP Sudah Terdaftar',
        'password.min' => 'Password Minimal 8 Karakter',
        'password.confirmed' => 'Konfirmasi Password Tidak Sama',
    ]);

    $user->nama = $request->nama;
    $user->nomor_hp = $request->nomor_hp;

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    if ($user->jabatan === 'Admin') {
        return redirect()->route('user')->with('success', 'Profil berhasil diperbarui');
    } else {
        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui');
    }
}
}   
