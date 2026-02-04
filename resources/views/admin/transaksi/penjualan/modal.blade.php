<!-- Modal Hapus Penjualan -->
<div class="modal fade" id="hapusPenjualan{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusPenjualanLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="hapusPenjualanLabel{{ $item->id }}">
          Hapus Data Penjualan?
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body text-left">
        <div class="row mb-2">
          <div class="col-5">Tanggal & Jam</div>
          <div class="col-7">: 
            {{ $item->tanggal_transaksi 
                ? \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y H:i:s') 
                : '-' }}
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-5">Nama Petugas</div>
          <div class="col-7">: {{ $item->nama_user ?? '-' }}</div>
        </div>

        <div class="row mb-2">
          <div class="col-5">Detail Obat</div>
            <div class="col-7">
              @foreach ($item->details as $index => $detail)
                @if ($index == 0)
                  : <strong>{{ $detail->obat->nama_obat ?? 'Obat dihapus' }}</strong>
                    ({{ $detail->jumlah }} {{ $detail->satuan ?? '' }}) -
                    Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                @else
                  <br>
                  <span style="margin-left: 11px;">
                    <strong>{{ $detail->obat->nama_obat ?? 'Obat dihapus' }}</strong>
                    ({{ $detail->jumlah }} {{ $detail->satuan ?? '' }}) -
                    Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                  </span>
                @endif
              @endforeach
            </div>
          </div>

        <div class="row mb-2">
          <div class="col-5">Total Harga</div>
          <div class="col-7">: Rp {{ number_format($item->total_harga, 0, ',', '.') }}</div>
        </div>

        <div class="row mb-2">
          <div class="col-5">Pembayaran</div>
          <div class="col-7">: {{ $item->metode_pembayaran }}</div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Tutup
        </button>
        <form action="{{ route('penjualanDestroy', $item->id) }}" method="POST">
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