@extends('layouts.app')

@section('content')

{{-- STYLE TAMBAHAN --}}
<style>
    .edit-card {
        border-radius: 18px;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 18px rgba(0, 123, 255, 0.15);
    }

    .edit-header {
        background: linear-gradient(#0b5ed7);
        padding: 20px;
        color: white;
        border-bottom: 4px solid #eaf5ff;
    }

    .edit-header h1 {
        font-size: 1.4rem;
        font-weight: 600;
        margin: 0;
    }

    .btn-back {
        border-radius: 30px;
        padding: 8px 18px;
        font-weight: 600;
    }

    /* TABEL */
    .table-edit thead {
        background: #0b5ed7;
        color: white;
        text-transform: capitalize;
    }

    .table-edit tbody tr:hover {
        background: #eef6ff;
    }

    .table-edit input {
        border-radius: 10px;
    }

    .btn-save {
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: bold;
    }
</style>


<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-cash-register mr-2"></i> {{ $title }}
</h1>

<div class="card shadow edit-card">
    <div class="edit-header d-flex justify-content-between align-items-center">

        <a href="{{ route('penjualan') }}" class="btn btn-light btn-back">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('penjualanUpdate', $penjualan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Petugas</label>
                    <input type="text" class="form-control shadow-sm" 
                        value="{{ $penjualan->nama_user }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Metode Pembayaran</label>

                    @php
                        $selectedPayment = old('metode_pembayaran') 
                            ?? $penjualan->metode_pembayaran 
                            ?? $lastPayment 
                            ?? 'Tunai';
                    @endphp

                    <select name="metode_pembayaran" class="form-control shadow-sm" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="Tunai" {{ $selectedPayment == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="Transfer" {{ $selectedPayment == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="QRIS" {{ $selectedPayment == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                    </select>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold text-primary">Detail Obat</h6>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle table-edit">
                    <thead class="text-center">
                        <tr>
                            <th>Obat</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->details as $detail)
                        <tr>
                            <td>
                                {{ $detail->obat->nama_obat ?? 'Obat dihapus' }}
                                <input type="hidden" name="obat_id[]" value="{{ $detail->obat_id }}">
                            </td>

                            <td style="width:120px;">
                                <input type="number" name="jumlah[]" 
                                    value="{{ old('jumlah.' . $loop->index, $detail->jumlah) }}" 
                                    class="form-control shadow-sm" 
                                    min="1" required>
                            </td>

                            <td>
                                <input type="text" value="{{ $detail->satuan }}" 
                                    class="form-control shadow-sm" readonly>
                            </td>

                            <td>
                                <input type="text" value="{{ number_format($detail->harga_satuan, 0, ',', '.') }}" 
                                    class="form-control shadow-sm" readonly>
                            </td>

                            <td>
                                <input type="text" value="{{ number_format($detail->total_harga, 0, ',', '.') }}" 
                                    class="form-control shadow-sm" readonly>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-save">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection