<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Restok Obat</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #e9ecef; }
        h2 { text-align: center; margin-bottom: 0; }
        p { text-align: center; margin-top: 5px; }
    </style>
</head>
<body>
    <h2>Laporan Restok Obat</h2>

    <table>
        <thead>
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
            @foreach ($laporans as $index => $laporan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($laporan->periode_awal)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($laporan->periode_akhir)->format('d/m/Y') }}</td>
                    <td>{{ $laporan->nama_obat }}</td>
                    <td>{{ $laporan->jumlah }}</td>
                    <td>Rp{{ number_format($laporan->total_pengeluaran, 0, ',', '.') }}</td>
                    <td>{{ $laporan->supplier_terbanyak }}</td>
                    <td>{{ $laporan->petugas_terbanyak }}</td>
                    <td>{{ \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>