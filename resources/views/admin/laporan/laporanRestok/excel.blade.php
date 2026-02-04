<table>
    <thead>
        <tr>
            <th colspan="8" align="center"><strong>Laporan Restok Obat</strong></th>
        </tr>
        <tr>
            <th width="20" align="center"><strong>No</strong></th>
            <th width="30" align="center"><strong>Periode Awal</strong></th>
            <th width="30" align="center"><strong>Periode Akhir</strong></th>
            <th width="30" align="center"><strong>Nama Obat</strong></th>
            <th width="30" align="center"><strong>Jumlah</strong></th>
            <th width="40" align="center"><strong>Total Pengeluaran</strong></th>
            <th width="40" align="center"><strong>Supplier Terbanyak</strong></th>
            <th width="40" align="center"><strong>Petugas Terbanyak</strong></th>
            <th width="40" align="center"><strong>Tanggal Dibuat</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporans as $laporan)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td align="center">{{ \Carbon\Carbon::parse($laporan->periode_awal)->format('d/m/Y') }}</td>
            <td align="center">{{ \Carbon\Carbon::parse($laporan->periode_akhir)->format('d/m/Y') }}</td>
            <td align="center">{{ $laporan->nama_obat }}</td>
            <td align="center">{{ $laporan->jumlah }}</td>
            <td align="center">Rp {{ number_format($laporan->total_pengeluaran, 0, ',', '.') }}</td>
            <td align="center">{{ $laporan->supplier_terbanyak }}</td>
            <td align="center">{{ $laporan->petugas_terbanyak }}</td>
            <td align="center">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y H:i:s') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>