 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('welcome') }}">
    <div class="sidebar-brand-icon">
        <i class="fas fa-clinic-medical"></i>
    </div>
    <div class="sidebar-brand-text mx-2">Apotek Farma</div>
</a>


<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item {{$menuDashboard ?? ''}}">
    <a class="nav-link" href="{{ route('dashboard') }}">
        <i class="fas fa-fw fa-home"></i>
        <span>Beranda</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

@if (auth()->user()->jabatan == 'Admin')
    <!-- Heading -->
<div class="sidebar-heading">
    Menu Admin
</div>

<li class="nav-item {{$menuAdminUser ?? ''}}">
    <a class="nav-link" href="{{ route('user') }}">
        <i class="fas fa-fw fa-user"></i>
        <span>Data User</span></a>
</li>

<li class="nav-item {{$menuAdminObat ?? ''}}">
    <a class="nav-link" href="{{ route('obat') }}">
        <i class="fas fa-fw fa-notes-medical"></i>
        <span>Data Obat</span></a>
</li>

<li class="nav-item {{ $menuAdminSupplier ?? '' }}">
    <a class="nav-link" href="{{ route('supplier') }}">
        <i class="fas fas fa-truck"></i>
        <span>Data Supplier</span></a>
</li>

<!-- Nav Item - Transaksi -->
<li class="nav-item {{ isset($menuAdminPenjualan) || isset($menuAdminRestok) ? 'active' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransaksi"
        aria-expanded="{{ isset($menuAdminPenjualan) || isset($menuAdminRestok) ? 'true' : 'false' }}"
        aria-controls="collapseTransaksi">
        <i class="fas fa-exchange-alt"></i>
        <span>Transaksi</span>
    </a>
    <div id="collapseTransaksi"
         class="collapse {{ isset($menuAdminPenjualan) || isset($menuAdminRestok) ? 'show' : '' }}"
         aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu Transaksi:</h6>
            <a class="collapse-item {{ isset($menuAdminPenjualan) ? 'active' : '' }}" href="{{ route('penjualan') }}">
                Penjualan
            </a>
            <a class="collapse-item {{ isset($menuAdminRestok) ? 'active' : '' }}" href="{{ route('restok') }}">
                Restok Obat
            </a>
        </div>
    </div>
</li>

<li class="nav-item {{ isset($menuLaporanPenjualan) || isset($menuLaporanRestok) || isset($menuLaporanObat) ? 'active' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
        aria-expanded="{{ isset($menuLaporanPenjualan) || isset($menuLaporanRestok) || isset($menuLaporanObat) ? 'true' : 'false' }}"
        aria-controls="collapseLaporan">
        <i class="fas fa-file-invoice"></i>
        <span>Laporan</span>
    </a>
    <div id="collapseLaporan"
         class="collapse {{ isset($menuLaporanPenjualan) || isset($menuLaporanRestok) || isset($menuLaporanObat) ? 'show' : '' }}"
         aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu Laporan:</h6>

            <a class="collapse-item {{ isset($menuLaporanPenjualan) ? 'active' : '' }}" 
               href="{{ route('laporan.penjualan') }}">
                Laporan Penjualan
            </a>

            <a class="collapse-item {{ isset($menuLaporanRestok) ? 'active' : '' }}" 
               href="{{ route('laporan.restok') }}">
                Laporan Restok
            </a>

            <a class="collapse-item {{ isset($menuLaporanObat) ? 'active' : '' }}" 
               href="{{ route('laporan.obat') }}">
                Laporan Obat
            </a>
        </div>
    </div>
</li>

@else
    <!-- Heading -->
<div class="sidebar-heading">
    Menu Kasir
</div>

<li class="nav-item {{$menuKasirObat ?? ''}}">
    <a class="nav-link" href="{{ route('obat') }}">
        <i class="fas fa-fw fa-notes-medical"></i>
        <span>Data Obat</span></a>
</li>

<li class="nav-item {{ $menuKasirSupplier ?? '' }}">
    <a class="nav-link" href="{{ route('supplier') }}">
        <i class="fas fas fa-truck"></i>
        <span>Data Supplier</span></a>
</li>

<!-- Nav Item - Transaksi -->
<li class="nav-item {{ isset($menuKasirPenjualan) || isset($menuKasirRestok) ? 'active' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransaksi"
        aria-expanded="{{ isset($menuKasirPenjualan) || isset($menuKasirRestok) ? 'true' : 'false' }}"
        aria-controls="collapseTransaksi">
        <i class="fas fa-exchange-alt"></i>
        <span>Transaksi</span>
    </a>
    <div id="collapseTransaksi"
         class="collapse {{ isset($menuKasirPenjualan) || isset($menuKasirRestok) ? 'show' : '' }}"
         aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu Transaksi:</h6>
            <a class="collapse-item {{ isset($menuKasirPenjualan) ? 'active' : '' }}" href="{{ route('penjualan') }}">
                Penjualan
            </a>
            <a class="collapse-item {{ isset($menuKasirRestok) ? 'active' : '' }}" href="{{ route('restok') }}">
                Restok Obat
            </a>
        </div>
    </div>
</li>

<li class="nav-item {{ isset($menuLaporanPenjualan) || isset($menuLaporanRestok) || isset($menuLaporanObat) ? 'active' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
        aria-expanded="{{ isset($menuLaporanPenjualan) || isset($menuLaporanRestok) || isset($menuLaporanObat) ? 'true' : 'false' }}"
        aria-controls="collapseLaporan">
        <i class="fas fa-file-invoice"></i>
        <span>Laporan</span>
    </a>
    <div id="collapseLaporan"
         class="collapse {{ isset($menuLaporanPenjualan) || isset($menuLaporanRestok) || isset($menuLaporanObat) ? 'show' : '' }}"
         aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu Laporan:</h6>

            <a class="collapse-item {{ isset($menuLaporanPenjualan) ? 'active' : '' }}" 
               href="{{ route('laporan.penjualan') }}">
                Laporan Penjualan
            </a>

            <a class="collapse-item {{ isset($menuLaporanRestok) ? 'active' : '' }}" 
               href="{{ route('laporan.restok') }}">
                Laporan Restok
            </a>

            <a class="collapse-item {{ isset($menuLaporanObat) ? 'active' : '' }}" 
               href="{{ route('laporan.obat') }}">
                Laporan Obat
            </a>
        </div>
    </div>
</li>
@endif

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->