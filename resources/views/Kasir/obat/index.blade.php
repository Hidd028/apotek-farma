@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-notes-medical mr-2"></i> 
        {{ $title }}
    </h1>

    <!-- DataTales Example -->
    <div class="card">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Kadaluarsa</th>
                            <th>Gambar</th>
                            <th>Diperbarui Oleh</th>
                            <th>Tanggal & Jam Update</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($obat as $item)
                            <tr>
                                <!-- Nomor urut -->
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <!-- Data obat -->
                                <td>{{ $item->nama_obat }}</td>
                                <td>{{ $item->kategori ?? '-' }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->stok }}</td>
                                <td class="text-center">{{ $item->satuan ?? '-' }}</td>
                                <td class="text-center">
                                    @php
                                        $kadaluarsa = \Carbon\Carbon::parse($item->tanggal_kadaluarsa);
                                        $today = \Carbon\Carbon::today();
                                    @endphp

                                    {{ $kadaluarsa->format('d/m/Y') }}

                                    {{-- Jika sudah expired --}}
                                    @if ($kadaluarsa->lt($today))
                                        <div>
                                            <span class="badge bg-danger text-white mt-1">Expired</span>
                                        </div>

                                    @elseif ($today->lt($kadaluarsa) && $today->diffInDays($kadaluarsa) <= 30)
                                        <div>
                                            <span class="badge bg-warning text-white mt-1">Hampir Expired</span>
                                        </div>
                                    @endif
                                </td>
                                
                                <!-- Gambar Obat (klik untuk perbesar) -->
                                <td class="text-center">
                                    @if ($item->gambar)
                                        <!-- Thumbnail -->
                                        <img src="{{ asset('images/obat/'.$item->gambar) }}"
                                             width="70" height="70"
                                             class="rounded shadow-sm"
                                             style="object-fit: cover; cursor: pointer;"
                                             data-bs-toggle="modal"
                                             data-bs-target="#modalGambar{{ $item->id }}">

                                        <!-- Modal Gambar Besar -->
                                        <div class="modal fade" id="modalGambar{{ $item->id }}" tabindex="-1"
                                             aria-labelledby="modalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset('images/obat/'.$item->gambar) }}"
                                                             alt="{{ $item->nama_obat }}"
                                                             class="img-fluid rounded shadow-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>

                                <!-- Diperbarui oleh -->
                                <td class="text-center">
                                    {{ $item->user->nama ?? '-' }}
                                </td>

                                <!-- Waktu update -->
                                <td class="text-center">
                                    {{ $item->updated_at
                                        ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i:s')
                                        : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection