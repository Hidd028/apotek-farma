@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-boxes mr-2"></i> {{ $title }}
</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
        <div class="mb-1 mr-2">
            <a href="{{ route('restokCreate') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-2"></i> Tambah Restok
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal Masuk</th>
                        <th>Nama Petugas</th>
                        <th>Obat</th>
                        <th>Supplier</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($restoks as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y H:i') }}</td>
                        <td class="text-center">{{ $item->user->nama ?? '-' }}</td>
                        <td>{{ $item->obat->nama_obat ?? 'Obat dihapus' }}</td>
                        <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                        <td class="text-center">{{ $item->jumlah }}</td>
                        <td class="text-center">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-center align-middle">
                            <a href="{{ route('restokEdit', $item->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusRestok{{ $item->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            @include('admin.transaksi.restok.modal')
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection