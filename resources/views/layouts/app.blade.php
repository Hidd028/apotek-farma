@include('layouts/header')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

@include('layouts/sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Alerts -->

                        <li class="nav-item dropdown no-arrow mx-1">

                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <i class="fas fa-bell fa-fw"></i>

                                {{-- Badge Jumlah Notifikasi --}}
                                @if(isset($jumlah_notifikasi) && $jumlah_notifikasi > 0)
                                <span class="badge bg-danger">{{ $jumlah_notifikasi }}</span>
                                @endif
                            </a>

                            <!-- Dropdown List -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">

                                <h6 class="dropdown-header">
                                    Notifikasi Obat
                                </h6>

                                {{-- List Notifikasi --}}
                                @forelse ($notifikasi as $n)
                                    <a class="dropdown-item" href="#">
                                        <span class="small text-gray-500">{!! $n['pesan'] !!}</span>
                                    </a>
                                @empty
                                    <span class="dropdown-item text-center text-muted">
                                        Tidak ada notifikasi
                                    </span>
                                @endforelse

                                {{-- Tombol Tandai Dibaca --}}
                                @if(isset($jumlah_notifikasi) && $jumlah_notifikasi > 0)

                                    <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" class="text-center">
                                        @csrf
                                        <button class="dropdown-item text-primary" type="submit">
                                            Tandai Semua Sudah Dibaca
                                        </button>
                                    </form>
                                @endif

                            </div>

                            </li>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    {{ auth()->user()->nama }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('sbadmin2/img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                <div class="badge {{ auth()->user()->jabatan == 'Admin' ? 'badge-success' : 'badge-danger' }} d-flex justify-content-center align-items-center text-center">{{ auth()->user()->jabatan }}
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('profilEdit') }}">
                                    <i class="fas fa-cog fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Pengaturan
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Nurwahid &copy; 2020 Apotek Farma.</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

@include('layouts.footer')