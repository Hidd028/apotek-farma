<table>
    <thead>
        <tr>
            <th colspan="5" align="center"><strong>Data User</strong>
            </th>
        </tr>
    <tr>
        <th width="20" align="center"><strong>No</strong></th>
        <th width="20" align="center"><strong>Nama</strong></th>
        <th width="20" align="center"><strong>Email</strong></th>
        <th width="20" align="center"><strong>Nomor HP</strong></th>
        <th width="20" align="center"><strong>Jabatan</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($user as $item)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td align="center">{{ $item->nama }}</td>
            <td align="center">{{ $item->email }}</td>
            <td align="center">{{ $item->nomor_hp }}</td>
            <td align="center">{{ $item->jabatan }}</td>
        </tr>
    @endforeach
    </tbody>
</table>