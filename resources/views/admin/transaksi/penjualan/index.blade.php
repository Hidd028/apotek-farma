@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-cash-register mr-2"></i>
    {{ $title }}
</h1>

<style>
    .kasir-card {
        border-radius: 18px;
        overflow: hidden;
        border: none;
    }
    .kasir-header {
        background: linear-gradient(#0b5ed7);
        padding: 20px;
        color: white;
        border-bottom: 4px solid #f8f9fc;
    }
    .tombol-tambah {
        border-radius: 50px;
        font-weight: bold;
        padding: 8px 18px;
    }
    .table-modern thead {
        background: #0b5ed7;
        color: white;
        text-transform: capitalize;
    }
    .table-modern tbody tr:hover {
        background: #eef6ff;
    }
    .detail-obat li {
        margin-bottom: 4px;
    }
</style>

<div class="card shadow kasir-card mb-4">
    <div class="kasir-header d-flex justify-content-between align-items-center">
        <a href="{{ route('penjualanCreate') }}" class="btn btn-light text-primary tombol-tambah shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Data
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-modern align-middle" id="dataTable" width="100%">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal & Jam</th>
                        <th>Nama Petugas</th>
                        <th>Detail Obat</th>
                        <th>Total Harga</th>
                        <th>Metode Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($penjualans as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>

                        <td class="text-center">
                            {{ $item->tanggal_transaksi
                                ? \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y H:i:s')
                                : '-' }}
                        </td>

                        <td class="text-center">{{ $item->nama_user ?? '-' }}</td>

                        <td>
                            <ul class="detail-obat list-unstyled mb-0">
                                @foreach ($item->details as $detail)
                                <li>
                                    <strong>{{ $detail->obat->nama_obat ?? 'Obat dihapus' }}</strong>
                                    ({{ $detail->jumlah }} {{ $detail->satuan ?? '' }}) -
                                    Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </li>
                                @endforeach
                            </ul>
                        </td>

                        <td class="text-center fw-bold">
                            Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                        </td>

                        <td class="text-center">
                                {{ $item->metode_pembayaran }}
                        </td>

                        <td class="text-center">
                            <a href="{{ route('penjualanEdit', $item->id) }}" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapusPenjualan{{ $item->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            @include('admin.transaksi.penjualan.modal')
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection