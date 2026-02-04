@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-plus mr-2"></i>
        {{ $title }}
    </h1>

    <div class="card">
        <div class="card-header">
            <div class="mb-1 mr-2">
                <a href="{{ route('obat') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('obatStore') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-2">
                    <div class="col-xl-6 mb-2">
                        <label class="form-label">Nama Obat</label>
                        <input type="text" name="nama_obat" class="form-control @error('nama_obat') is-invalid @enderror" value="{{ old('nama_obat') }}">
                        @error('nama_obat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror" value="{{ old('kategori') }}">
                        @error('kategori')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6 mt-2">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan"
                            class="form-control @error('satuan') is-invalid @enderror"
                            value="{{ old('satuan') }}">
                        @error('satuan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok') }}">
                        @error('stok')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-xl-6 mb-2">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="text" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}">
                        @error('harga')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-xl-6">
                        <label class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" name="tanggal_kadaluarsa" class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" value="{{ old('tanggal_kadaluarsa') }}">
                        @error('tanggal_kadaluarsa')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-xl-12">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror">
                        @error('gambar')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection