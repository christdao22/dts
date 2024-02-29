<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>DepEd - CDO | Document Tracking System</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/images/dts_logo.png" rel="icon">


    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets2/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets2/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets2/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets2/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets2/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets2/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets2/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets2/css/style.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"
        integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <img class="image-class" style="width: 25rem; float: left;" src="{{ asset('assets/images/home_logo.png') }}"
                alt="DEPED CDO Logo">&nbsp;
            &nbsp;
            <h1 class="logo me-auto"><a href="#"></a> </h1>

            <nav id="navbar" class="navbar">
                <ul>
                    @if (auth()->user())
                    <li><a class="nav-link scrollto" href="{{ route('document.incoming') }}">Dashboard</a></li>
                    @else
                    <li><a class="nav-link scrollto" href="{{ route('login') }}">Login</a></li>
                    <li><a class="nav-link scrollto" href="{{ route('register') }}">Register</a></li>
                    @endif
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->



    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center" style="background-image: url('assets/images/cdobg.jpg');">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"
                    data-aos="fade-up" data-aos-delay="200">
                    <h1>Document Tracking System</h1>
                    <h2>Track your document using tracking numbers!</h2>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="{{ route('dts') }}" class="btn-get-started scrollto" target="_blank">Track Here</a>
                        {{-- <a href="https://www.youtube.com/watch?v=jDDaplaOz7Q" class="glightbox btn-watch-video"><i class="bi bi-play-circle"></i><span>Watch Video</span></a> --}}
                    </div>

                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
                    <img src="assets2/img/hero2.png" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>

    </section><!-- End Hero -->


    <main id="main">

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">

        <div class="container footer-bottom clearfix">

            <div class="credits">
                ⓒ 2023 DepEd CDO | ICT Unit
            </div>
        </div>
    </footer><!-- End Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets2/vendor/aos/aos.js"></script>
    <script src="assets2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets2/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets2/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets2/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets2/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets2/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets2/js/main.js"></script>

</body>

</html>
