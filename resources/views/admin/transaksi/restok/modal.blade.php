<!-- Modal Hapus Restok -->
<div class="modal fade" id="hapusRestok{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusRestokLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="hapusRestokLabel{{ $item->id }}">
          Hapus Data Restok?
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body text-left">
        <div class="row mb-2">
          <div class="col-5">Tanggal Masuk</div>
          <div class="col-7">: 
            {{ $item->tanggal_masuk 
                ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y H:i') 
                : '-' }}
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-5">Nama Petugas</div>
          <div class="col-7">: {{ $item->user->nama ?? '-' }}</div>
        </div>

        <div class="row mb-2">
          <div class="col-5">Nama Obat</div>
          <div class="col-7">: 
            <strong>{{ $item->obat->nama_obat ?? 'Obat dihapus' }}</strong>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-5">Supplier</div>
          <div class="col-7">: {{ $item->supplier->nama_supplier ?? '-' }}</div>
        </div>

        <div class="row mb-2">
          <div class="col-5">Jumlah Masuk</div>
          <div class="col-7">: {{ $item->jumlah }} {{ $item->obat->satuan ?? '' }}</div>
        </div>

        <div class="row mb-2">
          <div class="col-5">Harga Beli</div>
          <div class="col-7">: Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</div>
        </div>

        @if($item->keterangan)
        <div class="row mb-2">
          <div class="col-5">Keterangan</div>
          <div class="col-7">: {{ $item->keterangan }}</div>
        </div>
        @endif
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Tutup
        </button>
        <form action="{{ route('restokDestroy', $item->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i> Hapus
          </button>
        </form>
      </div>
    </div>
  </div>
</div>