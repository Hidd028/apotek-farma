@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
       <i class="fas fa-plus mr-2"></i> 
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
                        <form action="{{ route('userStore') }}" method="post">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-xl-6 mb-2">
                                    <label class="form-label">
                                        Nama
                                    </label>
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
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
                                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
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
                                    <input type="text" name="nomor_hp" class="form-control @error('nomor_hp') is-invalid @enderror" value="{{ old('nomor_hp') }}">
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
                                    <option selected disabled>-- Pilih Jabatan --</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Karyawan">Karyawan</option>
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
                                    <i class="fas fa-save mr-1"></i>
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

@endsection