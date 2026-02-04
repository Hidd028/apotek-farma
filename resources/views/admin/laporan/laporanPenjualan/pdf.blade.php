<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #e9ecef; }
        h2 { text-align: center; margin-bottom: 0; }
        .total-box { margin-top: 20px; text-align: right; font-size: 14px; }
    </style>
</head>
<body>

    <h2>Laporan Penjualan</h2>

    <table>
        <thead>
            <tr>
                <th width="20"><strong>No</strong></th>
                <th width="35"><strong>Tanggal Transaksi</strong></th>
                <th width="45"><strong>Nama Obat</strong></th>
                <th width="25"><strong>Jumlah</strong></th>
                <th width="35"><strong>Harga Satuan</strong></th>
                <th width="35"><strong>Total Harga</strong></th>
                <th width="35"><strong>Metode Pembayaran</strong></th>
                <th width="35"><strong>Nama Kasir</strong></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($detailTransaksi as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $t->nama_obat }}</td>
                    <td>{{ $t->jumlah }}</td>
                    <td>Rp{{ number_format($t->harga_satuan, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $t->metode_pembayaran }}</td>
                    <td>{{ $t->kasir }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTAL PENDAPATAN --}}
    <div class="total-box">
        <strong>Total Pendapatan: </strong>
        Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
    </div>

</body>
</html>