@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-boxes mr-2"></i> {{ $title }}
</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('restok') }}" class="btn btn-sm btn-success">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('restokStore') }}" method="POST">
            @csrf

            {{-- Dropdown Nama Obat dengan pencarian --}}
            <div class="form-group">
                <label>Nama Obat</label>
                <select name="obat_id" id="obat_id" class="form-control @error('obat_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Obat --</option>
                    @foreach($obats as $obat)
                        <option value="{{ $obat->id }}" {{ old('obat_id') == $obat->id ? 'selected' : '' }}>
                            {{ $obat->nama_obat }} (Stok: {{ $obat->stok }})
                        </option>
                    @endforeach
                </select>
                @error('obat_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Supplier --}}
            <div class="form-group">
                <label>Supplier</label>
                <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->nama_supplier }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jumlah --}}
            <div class="form-group">
                <label>Jumlah Masuk</label>
                <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" min="1" value="{{ old('jumlah') }}" required>
                @error('jumlah')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Harga Beli --}}
            <div class="form-group">
                <label>Harga Beli</label>
                <input type="number" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" min="0" value="{{ old('harga_beli') }}" required>
                @error('harga_beli')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Tanggal Masuk --}}
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', $tanggal_masuk) }}" readonly>
                @error('tanggal_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#obat_id').select2({
        placeholder: "-- Ketik untuk mencari obat --",
        width: '100%'
    });
});
</script>

<style>
.select2-container {
    width: 100% !important;
}
.select2-selection--single {
    height: 38px !important;
    border: 1px solid #ced4da !important;
    border-radius: .35rem !important;
    display: flex;
    align-items: center;
    padding-left: 8px;
}
.select2-selection__rendered {
    line-height: 38px !important;
}
.select2-selection__arrow {
    height: 36px !important;
}
</style>
@endsection