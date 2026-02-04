<!-- jQuery -->
<script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap 4 (SB Admin 2) -->
<script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Plugin Tambahan -->
<script src="{{ asset('sbadmin2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SB Admin 2 -->
<script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>

<!-- DataTables Demo (opsional) -->
<script src="{{ asset('sbadmin2/js/demo/datatables-demo.js') }}"></script>

<!-- Script tambahan per halaman -->
@yield('scripts')

<!-- SweetAlert Global -->
@if (session('success'))
<script>
    Swal.fire({
        title: "Sukses",
        text: "{{ session('success') }}",
        icon: "success"
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        title: "Gagal",
        text: "{{ session('error') }}",
        icon: "error"
    });
</script>
@endif

</body>
</html>