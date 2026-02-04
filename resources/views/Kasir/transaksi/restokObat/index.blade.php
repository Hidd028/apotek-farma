@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-boxes mr-2"></i> {{ $title }}
</h1>

<div class="card shadow mb-4">

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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection