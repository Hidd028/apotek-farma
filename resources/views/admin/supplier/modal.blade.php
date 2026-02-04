<!-- Modal Hapus Supplier -->
<div class="modal fade" id="hapusSupplier{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusSupplierLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="hapusSupplierLabel">Hapus {{ $title }} ?</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body text-left">
        <div class="row mb-2">
            <div class="col-5">Kode Supplier</div>
            <div class="col-7">: {{ $item->kode_supplier }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-5">Nama Supplier</div>
            <div class="col-7">: {{ $item->nama_supplier }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-5">Alamat</div>
            <div class="col-7">: {{ $item->alamat ?? '-' }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-5">Telepon</div>
            <div class="col-7">: {{ $item->telepon ?? '-' }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-5">Email</div>
            <div class="col-7">: {{ $item->email ?? '-' }}</div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Tutup
        </button>
        <form action="{{ route('supplierDestroy', $item->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">
              <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
      </div>
    </div>
  </div>
</div>