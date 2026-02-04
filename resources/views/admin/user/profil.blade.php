@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 text-gray-800">
        <i class="fas fa-user-cog mr-2"></i> Pengaturan Profil
    </h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body">

        <form action="{{ route('profilUpdate') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama"
                    class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama', $user->nama) }}"
                    placeholder="Masukkan nama lengkap">
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label>Nomor HP</label>
                <input type="text" name="nomor_hp"
                    class="form-control @error('nomor_hp') is-invalid @enderror"
                    value="{{ old('nomor_hp', $user->nomor_hp) }}"
                    placeholder="Masukkan nomor HP aktif">
                @error('nomor_hp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label>Password Baru (opsional)</label>
                <input type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Kosongkan jika tidak ingin mengubah password">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-4">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="form-control"
                    placeholder="Ulangi password baru">
            </div>
            <div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-save mr-2">
                        Simpan
                    </i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection