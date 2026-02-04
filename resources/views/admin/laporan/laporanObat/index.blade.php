@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-pills mr-2"></i>
    {{ $title }}
</h1>

<div class="card shadow mb-4">
    <div class="card-body">

        {{-- Tombol Periode Otomatis --}}
        <div class="text-center mb-4 d-flex justify-content-center flex-wrap" style="gap: 5px;">
            <a href="{{ route('laporan.obat', ['periode' => 'harian']) }}"
                class="btn btn-outline-primary btn-sm {{ request('periode') == 'harian' ? 'active' : '' }}">Harian</a>
            <a href="{{ route('laporan.obat', ['periode' => 'mingguan']) }}"
                class="btn btn-outline-primary btn-sm {{ request('periode') == 'mingguan' ? 'active' : '' }}">Mingguan</a>
            <a href="{{ route('laporan.obat', ['periode' => 'bulanan']) }}"
                class="btn btn-outline-primary btn-sm {{ request('periode') == 'bulanan' ? 'active' : '' }}">Bulanan</a>
            <a href="{{ route('laporan.obat', ['periode' => 'tahunan']) }}"
                class="btn btn-outline-primary btn-sm {{ request('periode') == 'tahunan' ? 'active' : '' }}">Tahunan</a>
        </div>

        {{-- Filter Manual (Tanggal Awal - Akhir) --}}
        <form action="{{ route('laporan.obat') }}" method="GET" class="row g-3 align-items-end justify-content-center mb-3">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Tanggal Awal</label>
                <input 
                    type="date" 
                    name="tanggal_awal" 
                    class="form-control form-control-sm"
                    value="{{ $showTanggalFilter ? $tanggal_awal_formatted : '' }}">
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Tanggal Akhir</label>
                <input 
                    type="date" 
                    name="tanggal_akhir" 
                    class="form-control form-control-sm"
                    value="{{ $showTanggalFilter ? $tanggal_akhir_formatted : '' }}">
            </div>

            <div class="col-12 col-md-4 text-md-end text-center">
                <button type="submit" class="btn btn-primary btn-sm me-2 mt-2">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
                <a href="{{ route('laporan.obat') }}" class="btn btn-secondary btn-sm mt-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>

        <hr>

        {{-- Tombol Export --}}
        <div class="d-flex flex-wrap justify-content-center justify-content-xl-between align-items-center mb-3">
            <div></div>
            <div class="text-center text-xl-end">
                <a href="{{ route('laporan.obat.export.excel', [
                    'tanggal_awal' => request('tanggal_awal'),
                    'tanggal_akhir' => request('tanggal_akhir'),
                    'periode' => request('periode'),
                ]) }}" class="btn btn-sm btn-success me-2">
                    <i class="fas fa-file-excel"></i> Excel
                </a>

                <a href="{{ route('laporan.obat.export.pdf', [
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
                    <tr class="text-center">
                        <th>No</th>
                        <th>Periode Awal</th>
                        <th>Periode Akhir</th>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Stok Awal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Stok Akhir</th>
                        <th>Harga Jual</th>
                        <th>Total Nilai</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporans as $laporan)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $laporan['periode_awal'] ?? '-' }}</td>
                            <td class="text-center">{{ $laporan['periode_akhir'] ?? '-' }}</td>
                            <td>{{ $laporan['nama_obat'] }}</td>
                            <td>{{ $laporan['kategori'] }}</td>
                            <td class="text-center">{{ $laporan['stok_awal'] }}</td>
                            <td class="text-center">{{ $laporan['jumlah_masuk'] }}</td>
                            <td class="text-center">{{ $laporan['jumlah_keluar'] }}</td>
                            <td class="text-center">{{ $laporan['stok_akhir'] }}</td>
                            <td>Rp{{ number_format($laporan['harga_jual'], 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($laporan['total_nilai'], 0, ',', '.') }}</td>
                            <td class="text-center">
                                {{ $laporan['tanggal_terbaru'] ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-muted py-3 text-center">Tidak ada data laporan obat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection