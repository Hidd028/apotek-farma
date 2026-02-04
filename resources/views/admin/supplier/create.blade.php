@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-plus mr-2"></i> {{ $title }}
    </h1>

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ route('supplier') }}" class="btn btn-sm btn-success">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('supplierStore') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <label class="form-label font-weight-bold">Kode Supplier</label>
                        <input type="text" name="kode_supplier" class="form-control @error('kode_supplier') is-invalid @enderror" value="{{ old('kode_supplier') }}">
                        @error('kode_supplier')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6 mb-3">
                        <label class="form-label font-weight-bold">Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control @error('nama_supplier') is-invalid @enderror" value="{{ old('nama_supplier') }}">
                        @error('nama_supplier')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-12 mb-3">
                        <label class="form-label font-weight-bold">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6 mb-3">
                        <label class="form-label font-weight-bold">Telepon</label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}">
                        @error('telepon')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6 mb-3">
                        <label class="form-label font-weight-bold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection