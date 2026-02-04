@extends('layouts.app')

@section('content')

<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-home mr-2"></i> {{ $title }}
</h1>

<div class="row">

    <!-- CARD USER -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            TOTAL USER
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $jumlahUser }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD ADMIN -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            TOTAL ADMIN
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $jumlahAdmin }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD KASIR -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            TOTAL KASIR
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $jumlahKasir }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD OBAT -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            TOTAL OBAT
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $jumlahObat }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- GRAFIK + OBAT TERLARIS -->
<div class="row">

    <!-- Grafik Penjualan -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan Bulanan</h6>
            </div>

            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Tabel Obat Terlaris -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">

            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">Top 5 Obat Terlaris</h6>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Obat</th>
                            <th>Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($obatTerlaris as $index => $o)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $o->nama_obat }}</td>
                            <td>{{ $o->total_terjual }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById("myAreaChart");

    new Chart(ctx, {
        type: "line",
        data: {
            labels: {!! json_encode($bulan) !!},  // <= bulan singkat: Jan, Feb, Mar
            datasets: [{
                label: "Total Penjualan",
                data: {!! json_encode($nilai) !!}, // <= angka total_penjualan
                borderColor: "rgba(78, 115, 223, 1)",
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                lineTension: 0.3,
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        callback: function (value) {
                            return "Rp " + value.toLocaleString("id-ID");
                        }
                    }
                }
            }
        }
    });
</script>

@endsection