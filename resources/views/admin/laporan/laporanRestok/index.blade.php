@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-boxes mr-2"></i>
    {{ $title }}
</h1>

<div class="card shadow mb-4">
    <div class="card-body">

        {{-- Tombol Periode Otomatis --}}
        <div class="text-center mb-4 d-flex justify-content-center flex-wrap" style="gap: 5px;">
            <a href="{{ route('laporan.restok', ['periode' => 'harian']) }}"
               class="btn btn-outline-primary btn-sm {{ request('periode') == 'harian' ? 'active' : '' }}">Harian</a>
            <a href="{{ route('laporan.restok', ['periode' => 'mingguan']) }}"
               class="btn btn-outline-primary btn-sm {{ request('periode') == 'mingguan' ? 'active' : '' }}">Mingguan</a>
            <a href="{{ route('laporan.restok', ['periode' => 'bulanan']) }}"
               class="btn btn-outline-primary btn-sm {{ request('periode') == 'bulanan' ? 'active' : '' }}">Bulanan</a>
            <a href="{{ route('laporan.restok', ['periode' => 'tahunan']) }}"
               class="btn btn-outline-primary btn-sm {{ request('periode') == 'tahunan' ? 'active' : '' }}">Tahunan</a>
        </div>

        {{-- Filter Manual --}}
        <form action="{{ route('laporan.restok') }}" method="GET" class="row g-3 align-items-end justify-content-center mb-3">
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
                <a href="{{ route('laporan.restok') }}" class="btn btn-secondary btn-sm mt-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>

        <hr>

        {{-- Tombol Export --}}
        <div class="d-flex flex-wrap justify-content-center justify-content-xl-between align-items-center mb-3">
            <div></div>
            <div class="text-center text-xl-end">
                <a href="{{ route('laporan.restok.export.excel', [
                    'tanggal_awal' => request('tanggal_awal'),
                    'tanggal_akhir' => request('tanggal_akhir'),
                    'periode' => request('periode'),
                ]) }}" class="btn btn-sm btn-success me-2">
                    <i class="fas fa-file-excel"></i> Excel
                </a>

                <a href="{{ route('laporan.restok.export.pdf', [
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
                <thead class="bg-primary text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Periode Awal</th>
                        <th>Periode Akhir</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Total Pengeluaran</th>
                        <th>Supplier Terbanyak</th>
                        <th>Petugas Terbanyak</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporans as $laporan)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $laporan->periode_awal ? \Carbon\Carbon::parse($laporan->periode_awal)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $laporan->periode_akhir ? \Carbon\Carbon::parse($laporan->periode_akhir)->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">{{ $laporan->nama_obat }}</td>
                            <td class="text-center">{{ $laporan->jumlah }}</td>
                            <td>Rp{{ number_format($laporan->total_pengeluaran ?? 0, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $laporan->supplier_terbanyak ?? '-' }}</td>
                            <td class="text-center">{{ $laporan->petugas_terbanyak ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-3 text-center">Tidak ada data laporan restok</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection