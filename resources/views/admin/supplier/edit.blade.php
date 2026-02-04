@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-edit mr-2"></i>
        {{ $title }}
    </h1>

    <div class="card">
        <div class="card-header">
            <div class="mb-1 mr-2">
                <a href="{{ route('supplier') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('supplierUpdate', $supplier->id) }}" method="post">
                @csrf
                <div class="row mb-2">
                    <div class="col-xl-6 mb-2">
                        <label class="form-label">Kode Supplier</label>
                        <input type="text" name="kode_supplier"
                            class="form-control @error('kode_supplier') is-invalid @enderror"
                            value="{{ $supplier->kode_supplier }}">
                        @error('kode_supplier')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6 mb-2">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_supplier"
                            class="form-control @error('nama_supplier') is-invalid @enderror"
                            value="{{ $supplier->nama_supplier }}">
                        @error('nama_supplier')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-12 mb-2">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                            rows="3">{{ $supplier->alamat }}</textarea>
                        @error('alamat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6 mb-2">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="telepon"
                            class="form-control @error('telepon') is-invalid @enderror"
                            value="{{ $supplier->telepon }}">
                        @error('telepon')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6 mb-2">
                        <label class="form-label">Email</label>
                        <input type="text" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ $supplier->email }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection