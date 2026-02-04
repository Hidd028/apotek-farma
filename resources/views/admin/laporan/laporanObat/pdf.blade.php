<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Obat</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; }
        th { background-color: #e9ecef; }
        h2, h3 { text-align: center; margin: 0; }
        p { margin: 5px 0; }
        tfoot th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <h2>Laporan Obat</h2>
    <table>
        <thead>
            <tr>
                <th width="20">No</th>
                <th width="30">Periode Awal</th>
                <th width="30">Periode Akhir</th>
                <th width="60">Nama Obat</th>
                <th width="40">Kategori</th>
                <th width="25">Stok Awal</th>
                <th width="25">Stok Masuk</th>
                <th width="25">Stok Keluar</th>
                <th width="25">Stok Akhir</th>
                <th width="50">Total Nilai</th>
                <th width="40">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporans as $laporan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $laporan['periode_awal'] }}</td>
                    <td>{{ $laporan['periode_akhir'] }}</td>
                    <td>{{ $laporan['nama_obat'] }}</td>
                    <td>{{ $laporan['kategori'] }}</td>
                    <td>{{ $laporan['stok_awal'] }}</td>
                    <td>{{ $laporan['jumlah_masuk'] }}</td>
                    <td>{{ $laporan['jumlah_keluar'] }}</td>
                    <td>{{ $laporan['stok_akhir'] }}</td>
                    <td>Rp {{ number_format($laporan['total_nilai'], 0, ',', '.') }}</td>
                    <td>
                        @if($laporan['tanggal_terbaru'] && $laporan['tanggal_terbaru'] != '-')
                            {{ $laporan['tanggal_terbaru'] }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>