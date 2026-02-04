<!-- Modal Hapus Obat -->
<div class="modal fade" id="hapusObat{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusObatLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="hapusObatLabel{{ $item->id }}">Hapus {{ $title }} ?</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body text-left">
        <div class="row mb-1">
          <div class="col-5 font-weight-bold">Nama Obat</div>
          <div class="col-7">: {{ $item->nama_obat }}</div>
        </div>
        <div class="row mb-1">
          <div class="col-5 font-weight-bold">Kategori</div>
          <div class="col-7">: {{ $item->kategori ?? '-' }}</div>
        </div>
        <div class="row mb-1">
          <div class="col-5 font-weight-bold">Satuan</div>
          <div class="col-7">: {{ $item->satuan ?? '-' }}</div>
        </div>
        <div class="row mb-1">
          <div class="col-5 font-weight-bold">Harga</div>
          <div class="col-7">: Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
        </div>
        <div class="row mb-1">
          <div class="col-5 font-weight-bold">Stok</div>
          <div class="col-7">: {{ $item->stok }}</div>
        </div>
        <div class="row mb-1">
          <div class="col-5 font-weight-bold">Kadaluarsa</div>
          <div class="col-7">: 
            {{ $item->tanggal_kadaluarsa 
                ? \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') 
                : '-' }}
          </div>
        </div>
        <div class="row mb-1">
          <div class="col-5 font-weight-bold">Gambar</div>
          <div class="col-7">
            @if ($item->gambar)
              <img src="{{ asset('images/obat/'.$item->gambar) }}" width="60" height="60" class="rounded" style="object-fit: cover;">
            @else
              <span class="text-muted">Tidak ada</span>
            @endif
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Tutup
        </button>
        <form action="{{ route('obatDestroy', $item->id) }}" method="post">
          @csrf
          @method('delete')
          <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i> Hapus
          </button>
        </form>
      </div>
    </div>
  </div>
</div>