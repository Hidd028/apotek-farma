@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
       <i class="fas fa-edit mr-2"></i> 
    {{$title}}
</h1>

                    <div class="card">
                        <div class="card-header">
                            <div class="mb-1 mr-2">
                                <a href="{{ route('user') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-arrow-left mr-2"></i>    
                                Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('userUpdate', $user->id) }}" method="post">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-xl-6 mb-2">
                                    <label class="form-label">
                                        Nama
                                    </label>
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ $user->nama }}">
                                    @error('nama')
                                        <small class="text-danger">
                                            {{$message }}
                                        </small>                                       
                                    @enderror
                                </div>
                                <div class="col-xl-6">
                                    <label class="form-label">
                                        Email
                                    </label>
                                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $user->email }}">
                                    @error('email')
                                        <small class="text-danger">
                                            {{$message }}
                                        </small>                                       
                                    @enderror
                                </div>
                                <div class="col-xl-6">
                                    <label class="form-label">
                                        Nomor HP
                                    </label>
                                    <input type="text" name="nomor_hp" class="form-control @error('nomor_hp') is-invalid @enderror" value="{{ $user->nomor_hp }}">
                                    @error('nomor_hp')
                                        <small class="text-danger">
                                            {{$message }}
                                        </small>                                       
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-xl-12">
                                    <label class="form-label">
                                        Jabatan
                                    </label>
                                    <select name="jabatan" class="form-control @error('jabatan') is-invalid @enderror">
                                    <option disabled>-- Pilih Jabatan --</option>
                                    <option value="Admin" 
                                    {{ $user->jabatan == 'Admin'  ? 'selected' :''}}>
                                        Admin</option>
                                    <option value="Karyawan" 
                                    {{ $user->jabatan == 'Karyawan'  ? 'selected' : ''}}>
                                        Karyawan</option>
                                    </select>
                                    @error('jabatan')
                                        <small class="text-danger">
                                            {{$message }}
                                        </small>                                       
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-xl-6 mb-2">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" autocomplete="new-password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-xl-6">
                                    <label class="form-label">Password Konfirmasi</label>
                                    <input type="password" name="password_confirmation" autocomplete="new-password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror">
                                    @error('password_confirmation')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
@endsection