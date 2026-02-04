@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-plus mr-2"></i> {{ $title }}
</h1>

<style>
    /* --- CARD UTAMA --- */
    .kasir-card {
        border-radius: 18px;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);
    }

    /* --- HEADER CARD --- */
    .kasir-header {
        background: linear-gradient(#0b5ed7);
        padding: 22px;
        color: white;
        border-bottom: 4px solid #e9f3ff;
    }

    .kasir-header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    /* --- TOMBOL TAMBAH --- */
    .tombol-tambah {
        border-radius: 50px;
        font-weight: 600;
        padding: 8px 20px;
        background: #005ec4;
        border: none;
    }

    .tombol-tambah:hover {
        background: #0b5ed7;
    }

    /* --- TABEL --- */
    .table-modern {
        border-radius: 10px;
        overflow: hidden;
    }

    .table-modern thead {
        background: #0b5ed7;
        color: white;
        text-transform: capitalize;
        font-size: 13px;
    }

    .table-modern tbody tr {
        transition: 0.2s;
    }

    .table-modern tbody tr:hover {
        background: #eef6ff;
    }

    .detail-obat li {
        margin-bottom: 6px;
        font-size: 14px;
    }

    /* Tombol Aksi */
    .btn-action {
        border-radius: 10px;
        padding: 6px 10px;
    }
</style>

<div class="card shadow kasir-form-card mb-4">

    <div class="kasir-header d-flex justify-content-between align-items-center">
        <a href="{{ route('penjualan') }}" class="btn btn-light text-primary shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="card-body">

        <form action="{{ route('penjualanStore') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label><strong>Tanggal Transaksi</strong></label>
                    <input type="date" name="tanggal_transaksi" class="form-control"
                           value="{{ date('Y-m-d') }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label><strong>Nama Petugas</strong></label>
                    <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                </div>
            </div>

            <hr>

            <h5 class="mb-3"><i class="fas fa-capsules me-2"></i>Detail Penjualan</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-modern" id="table-obat">
                    <thead class="text-center">
                        <tr>
                            <th>Obat</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="obat-body">
                        <tr>
                            <td>
                                <select name="obat_id[]" class="form-control obat-select" required>
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}"
                                                data-satuan="{{ $obat->satuan }}"
                                                data-harga="{{ $obat->harga }}"
                                                data-stok="{{ $obat->stok }}">
                                            {{ $obat->nama_obat }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td><input type="text" name="satuan[]" class="form-control satuan" readonly></td>
                            <td><input type="number" name="stok[]" class="form-control stok" readonly></td>
                            <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" required></td>
                            <td><input type="text" name="harga_satuan[]" class="form-control harga" readonly></td>
                            <td><input type="text" name="total_harga[]" class="form-control total" readonly></td>

                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-primary btn-sm mb-3" id="addRow">
                <i class="fas fa-plus"></i> Tambah Obat
            </button>

            <div class="mb-3 text-right">
                <label><strong>Total Transaksi:</strong></label>
                <input type="text" id="grandTotal"
                       class="form-control text-right font-weight-bold"
                       readonly style="font-size: 20px; color:#007bff;">
            </div>

            <div class="mb-3">
                <label><strong>Metode Pembayaran</strong></label>
                <select name="metode_pembayaran" class="form-control" required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer">Transfer</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-save shadow-sm">
                <i class="fas fa-save mr-2"></i> Simpan
            </button>

        </form>

    </div>

</div>

{{-- ===================== SELECT2 ===================== --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 6px 8px;
        border-radius: 10px;
    }
</style>

{{-- ===================== SCRIPT ===================== --}}
<script>
$(document).ready(function() {

    // Inisialisasi Select2
    function initSelect2() {
        $('.obat-select').select2({
            placeholder: "-- Pilih Obat --",
            allowClear: true,
            width: '100%'
        });
    }
    initSelect2();

    const tbody = $("#obat-body");

    // Tambah baris
    $("#addRow").click(function() {
        const firstRow = tbody.find("tr:first");
        const newRow = firstRow.clone();

        newRow.find("input").val("");
        newRow.find("select").val("").trigger("change");
        newRow.find(".select2-container").remove();

        tbody.append(newRow);
        initSelect2();
    });

    // Hapus baris
    tbody.on("click", ".remove-row", function() {
        if (tbody.find("tr").length > 1) {
            $(this).closest("tr").remove();
            hitungGrandTotal();
        }
    });

    // Saat obat dipilih
    tbody.on("change", ".obat-select", function() {
        const tr = $(this).closest("tr");
        const selected = $(this).find(":selected");

        tr.find(".satuan").val(selected.data("satuan") || "");
        tr.find(".stok").val(selected.data("stok") || "");
        tr.find(".harga").val(selected.data("harga") || "");
        tr.find(".jumlah").val("");
        tr.find(".total").val("");
    });

    // Saat jumlah diubah
    tbody.on("input", ".jumlah", function() {
        const tr = $(this).closest("tr");
        const stok = parseInt(tr.find(".stok").val()) || 0;
        const jumlah = parseInt($(this).val()) || 0;

        if (jumlah > stok && stok > 0) {
            alert("Jumlah melebihi stok (" + stok + ")");
            $(this).val(stok);
        }

        hitungTotal(tr);
    });

    // Hitung total
    function hitungTotal(tr) {
        const jumlah = parseFloat(tr.find(".jumlah").val()) || 0;
        const harga = parseFloat(tr.find(".harga").val()) || 0;
        const total = jumlah * harga;
        tr.find(".total").val(total.toLocaleString("id-ID"));
        hitungGrandTotal();
    }

    // Hitung total keseluruhan
    function hitungGrandTotal() {
        let grand = 0;
        $(".total").each(function() {
            grand += parseFloat($(this).val().replace(/\./g, '').replace(',', '.')) || 0;
        });
        $("#grandTotal").val(grand.toLocaleString("id-ID"));
    }

});
</script>

@endsection