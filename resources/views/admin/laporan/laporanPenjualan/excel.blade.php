<table>
    <thead>
        <tr>
            <th colspan="8" align="center">
                <strong>Laporan Penjualan</strong>
            </th>
        </tr>

        <tr>
        <th width="20" align="center"><strong>No</strong></th>
        <th width="35" align="center"><strong>Tanggal Transaksi</strong></th>
        <th width="45" align="center"><strong>Nama Obat</strong></th>
        <th width="25" align="center"><strong>Jumlah</strong></th>
        <th width="35" align="center"><strong>Harga Satuan</strong></th>
        <th width="35" align="center"><strong>Total Harga</strong></th>
        <th width="35" align="center"><strong>Metode Pembayaran</strong></th>
        <th width="35" align="center"><strong>Nama Kasir</strong></th>
        </tr>
    </thead>

    <tbody>
        @foreach($detailTransaksi as $d)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($d->tanggal_transaksi)->format('d/m/Y H:i:s') }}</td>
                <td align="center">{{ $d->nama_obat }}</td>
                <td align="center">{{ $d->jumlah }}</td>
                <td align="center">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                <td align="center">Rp {{ number_format($d->total_harga, 0, ',', '.') }}</td>
                <td align="center">{{ $d->metode_pembayaran }}</td>
                <td align="center">{{ $d->kasir }}</td>
            </tr>
        @endforeach

        {{-- BARIS TOTAL PENDAPATAN --}}
        <tr>
            <td colspan="5"></td>
            <td align="center"><strong>Total Pendapatan:</strong></td>
            <td colspan="2" align="center">
                <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
            </td>
        </tr>
    </tbody>
</table>
