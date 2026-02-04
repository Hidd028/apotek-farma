@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-chart-line mr-2"></i>
    {{ $title }}
</h1>

<div class="card shadow mb-4">
    <div class="card-body">

        {{-- Tombol Periode Otomatis --}}
        <div class="text-center mb-4 d-flex justify-content-center flex-wrap" style="gap: 5px;">
            <a href="{{ route('laporan.penjualan', ['periode' => 'harian']) }}"
            class="btn btn-outline-primary btn-sm {{ request('periode') == 'harian' ? 'active' : '' }}">Harian</a>
            <a href="{{ route('laporan.penjualan', ['periode' => 'mingguan']) }}"
            class="btn btn-outline-primary btn-sm {{ request('periode') == 'mingguan' ? 'active' : '' }}">Mingguan</a>
            <a href="{{ route('laporan.penjualan', ['periode' => 'bulanan']) }}"
            class="btn btn-outline-primary btn-sm {{ request('periode') == 'bulanan' ? 'active' : '' }}">Bulanan</a>
            <a href="{{ route('laporan.penjualan', ['periode' => 'tahunan']) }}"
            class="btn btn-outline-primary btn-sm {{ request('periode') == 'tahunan' ? 'active' : '' }}">Tahunan</a>
        </div>

        {{-- Filter Manual (Tanggal Awal - Akhir) --}}
        <form action="{{ route('laporan.penjualan') }}" method="GET" class="row g-3 align-items-end justify-content-center mb-3">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="{{ $tanggal_awal }}" class="form-control form-control-sm">
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ $tanggal_akhir }}" class="form-control form-control-sm">
            </div>

            <div class="col-12 col-md-4 text-md-end text-center">
                <button type="submit" class="btn btn-primary btn-sm me-2 mt-2">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
                <a href="{{ route('laporan.penjualan') }}" class="btn btn-secondary btn-sm mt-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>

        <hr>

        {{-- Tombol Export --}}
        <div class="d-flex flex-wrap justify-content-center justify-content-xl-between align-items-center mb-3">
            <div></div> <!-- sisi kiri bisa diisi tombol lain nanti -->
            
            <div class="text-center text-xl-end">
                <a href="{{ route('laporan.export.excel', [
                    'tanggal_awal' => request('tanggal_awal'),
                    'tanggal_akhir' => request('tanggal_akhir'),
                    'periode' => request('periode'),
                ]) }}" class="btn btn-sm btn-success me-2">
                    <i class="fas fa-file-excel"></i> Excel
                </a>

                <a href="{{ route('laporan.export.pdf', [
                    'tanggal_awal' => request('tanggal_awal'),
                    'tanggal_akhir' => request('tanggal_akhir'),
                    'periode' => request('periode'),
                ]) }}" class="btn btn-sm btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        {{-- Tabel Laporan --}}
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                    <th>No</th>
                    <th>Tanggal Transaksi</th>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                    <th>Nama Kasir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detailTransaksi as $i => $d)
                        <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($d->tanggal_transaksi)->format('d/m/Y H:i:s') }}</td>
                        <td class="text-center">{{ $d->nama_obat }}</td>
                        <td class="text-center">{{ $d->jumlah }}</td>
                        <td class="text-center">Rp{{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">Rp{{ number_format($d->total_harga, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $d->metode_pembayaran }}</td>
                        <td class="text-center">{{ $d->kasir }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-3 text-center">Tidak ada data laporan</td>
                        </tr>
                    @endforelse

                    {{-- BARIS TOTAL PENDAPATAN --}}
                    <tfoot class="bg-primary text-white">
                        <tr>
                            <th colspan="5" class="text-end">TOTAL PENDAPATAN</th>
                            <th class="text-center">
                                Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection