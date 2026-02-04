<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Apotek Farma - Kesehatan Anda, Prioritas Kami</title>
    <meta name="description" content="Apotek Farma: Menyediakan layanan farmasi terpercaya dan produk kesehatan berkualitas dengan sistem manajemen digital modern (SIMAF).">
    <meta name="keywords" content="Apotek, Farma, Kesehatan, Obat, Digital, Modern, Premium">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:wght@400;500;600;700&display=swap');

        :root {
            --color-primary-green: #388e3c; /* A deep, rich green */
            --color-light-green: #e8f5e9; /* Very light, subtle green */
            --color-accent-gold: #c2a74c; /* Soft gold for subtle accent */
            --color-text-dark: #212529; /* Almost black */
            --color-text-light: #555;
            --font-body: 'Lora', serif; /* Elegant serif for body */
            --font-heading: 'Poppins', sans-serif; /* Modern sans-serif for headings */
        }

        body {
            font-family: var(--font-body);
            color: var(--color-text-dark);
            background-color: #fcfcfc;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            font-weight: 700;
        }

        /* Header */
        #header {
            background: #fff;
            box-shadow: 0 1px 15px rgba(0, 0, 0, 0.04);
            padding: 18px 0;
            z-index: 1020; /* Ensure header stays on top */
        }
        .navbar-brand h1 {
            font-size: 1.9rem;
            color: var(--color-primary-green);
            letter-spacing: -0.5px;
            margin-left: 5px; /* Adjust spacing next to logo */
        }
        .navbar-brand img {
            height: 45px; /* Adjust logo height */
            width: 45px; /* Adjust logo width */
            object-fit: contain;
        }
        .nav-link {
            font-weight: 500;
            color: var(--color-text-dark) !important;
            transition: color 0.3s ease-in-out;
            padding: 8px 18px;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--color-primary-green) !important;
        }
        .btn-main-action {
            background-color: var(--color-primary-green);
            color: #fff;
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            letter-spacing: 0.5px;
        }
        .btn-main-action:hover {
            background-color: var(--color-accent-gold);
            color: #fff; /* Ensure text remains white for contrast */
            box-shadow: 0 6px 15px rgba(194, 167, 76, 0.4);
            transform: translateY(-2px);
        }
        .btn-outline-secondary {
            border-color: #ced4da;
            color: var(--color-text-dark);
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-outline-secondary:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }
        /* Sticky scroll-top button */
        #scroll-top {
            position: fixed;
            visibility: hidden;
            opacity: 0;
            right: 15px;
            bottom: 15px;
            z-index: 99999;
            background-color: var(--color-primary-green);
            width: 40px;
            height: 40px;
            border-radius: 50px;
            transition: all 0.3s ease-in-out;
        }
        #scroll-top i {
            font-size: 1.4rem;
            color: #fff;
            line-height: 0;
        }
        #scroll-top.active {
            visibility: visible;
            opacity: 1;
        }


        /* Hero Section */
        #hero {
            position: relative;
            padding: 120px 0;
            min-height: 70vh;
            display: flex;
            align-items: center;
            /* Using a placeholder image for hero - replace with your actual apotek image */
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), url('enno/assets/img/gemini2.png') center center / cover no-repeat;
            background-attachment: fixed; /* Optional: for parallax effect */
        }
        #hero h1 {
            font-size: 3.8rem;
            line-height: 1.25;
            color: var(--color-primary-green);
            margin-bottom: 25px;
            max-width: 800px;
        }
        #hero p {
            font-size: 1.25rem;
            color: var(--color-text-light);
            max-width: 700px;
            margin-bottom: 40px;
        }
        .hero-img {
            border-radius: 10px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        /* Section Titles */
        .section-title {
            margin-bottom: 70px;
        }
        .section-title h2 {
            font-size: 2.8rem;
            color: var(--color-text-dark);
            margin-bottom: 15px;
            position: relative;
        }
        .section-title h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 5px;
            background: var(--color-primary-green);
            margin: 20px auto 0;
            border-radius: 3px;
        }
        .section-title p {
            font-size: 1.15rem;
            color: var(--color-text-light);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Feature Cards */
        #features {
            padding: 100px 0;
            background-color: var(--color-light-green); /* Light background for features */
        }
        #features .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            background-color: #fff;
        }
        #features .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.15);
        }
        #features .icon-wrap {
            width: 90px;
            height: 90px;
            background: var(--color-light-green); /* Use the light green accent */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px auto;
        }
        #features .icon-wrap i {
            font-size: 3rem;
            color: var(--color-primary-green);
        }
        #features .card h4 {
            color: var(--color-text-dark);
            margin-bottom: 15px;
            font-size: 1.8rem;
        }
        #features .card p {
            color: var(--color-text-light);
            font-size: 1.05rem;
        }

        /* About Section */
        #about {
            padding: 100px 0;
        }
        #about h3 {
            font-size: 2.5rem;
            color: var(--color-primary-green);
            margin-bottom: 25px;
        }
        #about p {
            font-size: 1.1rem;
            color: var(--color-text-light);
            line-height: 1.8;
        }
        #about ul {
            list-style: none;
            padding: 0;
            margin-top: 30px;
        }
        #about ul li {
            margin-bottom: 15px;
            font-size: 1.1rem;
            color: var(--color-text-dark);
        }
        #about ul li i {
            color: var(--color-primary-green);
            margin-right: 12px;
            font-size: 1.3rem;
        }
        .about-image-stack {
            position: relative;
            min-height: 400px;
        }
        .about-image-stack img {
            position: absolute;
            border-radius: 10px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            transition: all 0.4s ease;
            object-fit: cover; /* Ensure images cover their area */
        }
        .about-image-stack img:nth-child(1) {
            top: 0;
            left: 0;
            width: 70%;
            height: 300px; /* Fixed height for consistency */
            z-index: 2;
        }
        .about-image-stack img:nth-child(2) {
            bottom: 0;
            right: 0;
            width: 70%;
            height: 300px; /* Fixed height for consistency */
            z-index: 1;
            transform: translateX(10%) translateY(10%);
        }
        @media (max-width: 991px) {
            .about-image-stack img:nth-child(2) {
                display: none; /* Hide second image on smaller screens */
            }
            .about-image-stack img:nth-child(1) {
                position: static;
                width: 100%;
                height: 350px;
                margin: 0 auto;
            }
        }


        /* CTA Section */
        #cta {
            background: linear-gradient(rgba(56, 142, 60, 0.9), rgba(56, 142, 60, 0.9)), url('enno/assets/img/services.jpg') center center / cover no-repeat;
            background-attachment: fixed; /* Optional: for parallax effect */
            color: #fff;
            padding: 100px 0;
            text-align: center;
        }
        #cta h2 {
            font-size: 3.2rem;
            margin-bottom: 30px;
        }
        #cta p {
            font-size: 1.25rem;
            max-width: 900px;
            margin: 0 auto 50px auto;
        }
        .btn-cta-light {
            background-color: #fff;
            color: var(--color-primary-green);
            border-radius: 30px;
            padding: 12px 40px;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .btn-cta-light:hover {
            background-color: var(--color-accent-gold);
            color: #fff;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
            transform: translateY(-3px);
        }
        
        /* Footer */
        #footer {
            background-color: var(--color-text-dark); /* Dark charcoal for footer */
            color: #ccc;
            padding: 80px 0 40px 0;
            font-size: 0.95rem;
        }
        #footer h4 {
            color: var(--color-accent-gold);
            font-size: 1.4rem;
            margin-bottom: 30px;
        }
        #footer a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        #footer a:hover {
            color: #fff;
        }
        #footer .social-links a {
            font-size: 1.8rem;
            margin-right: 20px;
            color: #999;
        }
        #footer .social-links a:hover {
            color: #fff;
        }
        #footer .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            margin-top: 50px;
        }
    </style>
</head>

<body>

<header id="header" class="d-flex align-items-center sticky-top">
    <div class="container d-flex align-items-center justify-content-between">

        <a href="#hero" class="navbar-brand d-flex align-items-center text-decoration-none">
            <img src="{{ asset('enno/assets/img/logo-apotek.png') }}" alt="Logo Apotek Farma" class="me-2 rounded-circle shadow-sm">
            <h1 class="m-0">Apotek Farma</h1>
        </a>

        <nav id="navbar" class="navbar navbar-expand-lg">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2" style="color: var(--color-primary-green);"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item me-3"><a class="nav-link active" href="#hero">Beranda</a></li>
                    <li class="nav-item me-3"><a class="nav-link" href="#features">Layanan</a></li>
                    <li class="nav-item me-3"><a class="nav-link" href="#about">Tentang Kami</a></li>
                    <li class="nav-item me-3"><a class="nav-link" href="#cabang">Cabang</a></li>
                    <li class="nav-item me-lg-4"><a class="nav-link" href="#cta">Kontak</a></li>
                </ul>
                <!-- Button -->
                @auth
                <a class="btn btn-success rounded-pill px-4 ms-3" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                <a class="btn btn-success rounded-pill px-4 ms-3" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </nav>
    </div>
</header>
<main id="main">

    <section id="hero" class="d-flex align-items-center">
        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-lg-7 text-center text-lg-start" data-aos="fade-up">
                    <h1>
                        Kesehatan Anda, <br><span style="color: var(--color-accent-gold);">Prioritas Utama</span> Kami
                    </h1>
                    <p>
                        Apotek Farma hadir sebagai mitra terpercaya Anda dalam menjaga kesehatan. Kami menyediakan obat-obatan berkualitas dan layanan farmasi profesional, didukung oleh sistem digital modern (SIMAF).
                    </p>
                    <div class="d-flex justify-content-center justify-content-lg-start mt-4">
                        <a href="#" class="btn btn-main-action btn-lg me-3">Temukan Obat Anda <i class="bi bi-arrow-right"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-lg">Tentang SIMAF</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center d-none d-lg-block" data-aos="fade-left">
                    <img src="{{ asset('enno/assets/img/gemini1.png') }}" class="img-fluid hero-img" alt="Toko Apotek Farma">
                </div>
            </div>
        </div>
    </section>
    <section id="features" class="py-5">
        <div class="container">
            <div class="section-title text-center" data-aos="fade-up">
                <h2>Layanan Apotek Farma</h2>
                <p>Kami berdedikasi untuk memberikan pelayanan terbaik bagi kesehatan Anda dan keluarga.</p>
            </div>
            
            <div class="row gy-4">
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="card h-100">
                        <div class="icon-wrap"><i class="bi bi-capsule-pill"></i></div>
                        <h4>Obat & Resep</h4>
                        <p>Menyediakan beragam obat-obatan dan melayani resep dokter dengan akurat.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100">
                        <div class="icon-wrap"><i class="bi bi-person-lines-fill"></i></div>
                        <h4>Konsultasi Apoteker</h4>
                        <p>Dapatkan informasi lengkap dan saran penggunaan obat dari apoteker profesional.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100">
                        <div class="icon-wrap"><i class="bi bi-heart-pulse-fill"></i></div>
                        <h4>Produk Kesehatan</h4>
                        <p>Tersedia suplemen, vitamin, alat kesehatan, dan produk perawatan diri berkualitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="about" class="py-5">
        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-lg-6 order-2 order-lg-1" data-aos="fade-right">
                    <h3>Tentang Apotek Farma & Inovasi SIMAF</h3>
                    <p>
                        Sejak didirikan, Apotek Farma telah tumbuh menjadi jaringan apotek terdepan. Kami percaya bahwa akses mudah ke kesehatan adalah hak setiap orang. Untuk itu, kami berinvestasi dalam "Sistem Informasi Manajemen Apotek Farma (SIMAF)".
                    </p>
                    <p>
                        SIMAF bukan hanya sebuah software, tapi jantung operasional kami yang memastikan setiap proses, dari manajemen stok hingga layanan pelanggan, berjalan efisien, akurat, dan transparan di semua cabang.
                    </p>
                    <ul>
                        <li><i class="bi bi-check-circle-fill"></i> Stok obat terupdate "real-time" di seluruh cabang.</li>
                        <li><i class="bi bi-check-circle-fill"></i> Proses transaksi cepat & tanpa kesalahan.</li>
                        <li><i class="bi bi-check-circle-fill"></i> Laporan keuangan otomatis & mudah diakses.</li>
                        <li><i class="bi bi-check-circle-fill"></i> Integrasi data resep dan riwayat pasien aman.</li>
                    </ul>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 text-center" data-aos="fade-left">
                    <div class="about-image-stack">
                        <img src="{{ asset('enno/assets/img/gemini4.png') }}" class="img-fluid" alt="Apoteker Bekerja">
                        <img src="{{ asset('enno/assets/img/localpharmacy4.jpg') }}" class="img-fluid" alt="SIMAF di Tablet">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="cabang" class="py-5 bg-light">
        <div class="container">
            <div class="section-title text-center" data-aos="fade-up">
                <h2>Kunjungi Cabang Kami</h2>
                <p>Temukan Apotek Farma terdekat untuk kebutuhan kesehatan Anda.</p>
            </div>
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-5 col-md-6" data-aos="fade-up">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.5257021219236!2d106.0902292!3d-6.3258491999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e423cca524efdeb%3A0x4785025597b6da4d!2sApotek%20Maja%20Farma!5e0!3m2!1sid!2sid!4v1757924169632!5m2!1sid!2sid" class="rounded-top" style="border:0;" allowfullscreen loading="lazy"></iframe>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold" style="color: var(--color-primary-green);">Apotek Maja Farma (Pusat)</h5>
                            <p class="card-text text-muted mb-2"><i class="bi bi-geo-alt-fill me-2"></i> Sukaratu, Majasari, Pandeglang</p>
                            <p class="card-text text-muted"><i class="bi bi-phone-fill me-2"></i> 0821-1234-5678</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.47264396658!2d106.05066977445391!3d-6.33276069365688!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e423b6cfb6c4091%3A0xf1c0848f1e51087d!2sApotek%20MENGGER!5e0!3m2!1sid!2sid!4v1757924556190!5m2!1sid!2sid" class="rounded-top" style="border:0;" allowfullscreen loading="lazy"></iframe>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold" style="color: var(--color-primary-green);">Apotek Mengger Farma</h5>
                            <p class="card-text text-muted mb-2"><i class="bi bi-geo-alt-fill me-2"></i> Mandalasari, Kaduhejo, Pandeglang</p>
                            <p class="card-text text-muted"><i class="bi bi-phone-fill me-2"></i> 0812-1111-2222</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.34815989214!2d106.04427779999999!3d-6.3489469!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e423b6b836dfcdb%3A0x5a1bfbf11c288ac9!2sApotek%20Batubantar!5e0!3m2!1sid!2sid!4v1757924628508!5m2!1sid!2sid" class="rounded-top" style="border:0;" allowfullscreen loading="lazy"></iframe>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold" style="color: var(--color-primary-green);">Apotek Apotek Batubantar Farma</h5>
                            <p class="card-text text-muted mb-2"><i class="bi bi-geo-alt-fill me-2"></i> Batubantar, Cimanuk, Pandeglang</p>
                            <p class="card-text text-muted"><i class="bi bi-phone-fill me-2"></i> 0812-3333-4444</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.1295385965836!2d106.0133437!3d-6.3772744!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e423b695c5a3551%3A0x6eadee4b0d8206fc!2sApotek%20Afiat%20Cipeucang!5e0!3m2!1sid!2sid!4v1757924685705!5m2!1sid!2sid" class="rounded-top" style="border:0;" allowfullscreen loading="lazy"></iframe>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold" style="color: var(--color-primary-green);">Apotek Apotek Cipeucang Farma</h5>
                            <p class="card-text text-muted mb-2"><i class="bi bi-geo-alt-fill me-2"></i> Palanyar, Cipeucang, Pandeglang</p>
                            <p class="card-text text-muted"><i class="bi bi-phone-fill me-2"></i> 0812-5555-6666</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section id="cta" class="py-5">
        <div class="container" data-aos="zoom-in">
            <h2>Siap Mengoptimalkan Manajemen Apotek Anda?</h2>
            <p>
                Hubungi tim kami untuk demo sistem SIMAF atau konsultasi kebutuhan apotek Anda.
            </p>
            <a href="https://wa.me/6282115700260?text=Halo%2C%20saya%20ingin%20bertanya" class="btn btn-cta-light">Hubungi Kami Sekarang <i class="bi bi-telephone-fill"></i></a>
        </div>
    </section>
    </main>

<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h4 class="mb-3">APOTEK FARMA</h4>
                <p class="small mb-3">
                    Dedikasi kami untuk kesehatan yang lebih baik melalui layanan farmasi terpercaya dan teknologi inovatif.
                </p>
                <div class="social-links mt-3">
                    <a href="#" class="text-white"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-facebook"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h4>Navigasi</h4>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-chevron-right me-2"></i> <a href="#hero">Beranda</a></li>
                    <li><i class="bi bi-chevron-right me-2"></i> <a href="#features">Layanan</a></li>
                    <li><i class="bi bi-chevron-right me-2"></i> <a href="#about">Tentang</a></li>
                    <li><i class="bi bi-chevron-right me-2"></i> <a href="#cabang">Cabang</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h4>Modul SIMAF</h4>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-chevron-right me-2"></i> <a href="#">Manajemen Inventaris</a></li>
                    <li><i class="bi bi-chevron-right me-2"></i> <a href="#">Point of Sale</a></li>
                    <li><i class="bi bi-chevron-right me-2"></i> <a href="#">Laporan Analitik</a></li>
                    <li><i class="bi bi-chevron-right me-2"></i> <a href="#">Pengelolaan Resep</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h4>Kontak Pusat</h4>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-geo-alt-fill me-2"></i> Jl. Kesehatan No. 12, Pandeglang</li>
                    <li><i class="bi bi-telephone-fill me-2"></i> (021) 1234 5678</li>
                    <li><i class="bi bi-envelope-fill me-2"></i> info@apotekfarma.co.id</li>
                    <li><i class="bi bi-clock-fill me-2"></i> Buka Setiap Hari (08:00 - 22:00)</li>
                </ul>
            </div>
        </div>

        <div class="text-center copyright mt-4">
            <p class="m-0 small">Nurwahid © <span id="currentYear"></span> Apotek Farma.</p>
        </div>
    </div>
</footer>
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center btn-main-action rounded-circle"><i class="bi bi-arrow-up-short"></i></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();

    // Scroll Top Visibility
    document.addEventListener('DOMContentLoaded', () => {
        const scrollTop = document.querySelector('#scroll-top');
        if (scrollTop) {
            const toggleScrollTop = () => {
                window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
            }
            window.addEventListener('load', toggleScrollTop);
            document.addEventListener('scroll', toggleScrollTop);
            toggleScrollTop(); // Set initial state
        }
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-link");

    function activateMenu() {
        let scrollPos = window.scrollY + 200; // lebih presisi

        sections.forEach(section => {
            let top = section.offsetTop;
            let height = section.offsetHeight;
            let id = section.getAttribute("id");

            if (scrollPos >= top && scrollPos <= top + height) {
                navLinks.forEach(link => link.classList.remove("active"));
                const activeLink = document.querySelector(`.nav-link[href="#${id}"]`);
                if (activeLink) activeLink.classList.add("active");
            }
        });
    }

    window.addEventListener("scroll", activateMenu);
    activateMenu();
});
</script>

</body>
</html>