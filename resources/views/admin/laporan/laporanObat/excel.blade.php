<table>
    <thead>
        <tr>
            <th colspan="11" align="center"><strong>Laporan Obat</strong></th>
        </tr>
        <tr>
            <th width="20" align="center"><strong>No</strong></th>
            <th width="30" align="center"><strong>Periode Awal</strong></th>
            <th width="30" align="center"><strong>Periode Akhir</strong></th>
            <th width="40" align="center"><strong>Nama Obat</strong></th>
            <th width="30" align="center"><strong>Kategori</strong></th>
            <th width="25" align="center"><strong>Stok Awal</strong></th>
            <th width="25" align="center"><strong>Stok Masuk</strong></th>
            <th width="25" align="center"><strong>Stok Keluar</strong></th>
            <th width="25" align="center"><strong>Stok Akhir</strong></th>
            <th width="40" align="center"><strong>Total Nilai</strong></th>
            <th width="40" align="center"><strong>Dibuat</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporans as $laporan)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td align="center">{{ $laporan['periode_awal'] }}</td>
            <td align="center">{{ $laporan['periode_akhir'] }}</td>
            <td align="center">{{ $laporan['nama_obat'] }}</td>
            <td align="center">{{ $laporan['kategori'] }}</td>
            <td align="center">{{ $laporan['stok_awal'] }}</td>
            <td align="center">{{ $laporan['jumlah_masuk'] }}</td>
            <td align="center">{{ $laporan['jumlah_keluar'] }}</td>
            <td align="center">{{ $laporan['stok_akhir'] }}</td>
            <td align="center">Rp {{ number_format($laporan['total_nilai'], 0, ',', '.') }}</td>
            <td align="center">{{ $laporan['tanggal_terbaru'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>