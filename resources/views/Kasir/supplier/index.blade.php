@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-truck mr-2"></i>
        {{ $title }}
    </h1>

    <!-- DataTales Example -->
    <div class="card">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Kode Supplier</th>
                            <th>Nama Supplier</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplier as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->kode_supplier }}</td>
                                <td>{{ $item->nama_supplier }}</td>
                                <td>{{ $item->alamat ?? '-' }}</td>
                                <td class="text-center">{{ $item->telepon ?? '-' }}</td>
                                <td class="text-center">{{ $item->email ?? '-' }}</td>           
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection