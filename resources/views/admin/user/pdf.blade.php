<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data User</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        hr {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <h2>Data User</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Nomor HP</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->nomor_hp }}</td>
                    <td>{{ $item->jabatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>