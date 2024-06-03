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

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <!-- Vendor CSS Files -->
    <link href="assets2/vendor/aos/aos.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets2/css/style.css" rel="stylesheet">
</head>

<body class="position-relative vh-100">
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ url('/') }}">
                <img class="image-class" style="width: 25rem; float: left;"
                    src="{{ asset('assets/images/home_logo.png') }}" alt="DEPED CDO Logo">
            </a>

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

    @include('sweetalert::alert')

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center h-100" style="background-image: url('assets/images/cdobg2.jpg');">
        <div class="container">
            <div class="row d-flex justify-content-between">
                <div class="col-lg-7 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-1 order-lg-1"
                    data-aos="fade-up" data-aos-delay="200">
                    <h1>Document Tracking System</h1>
                    <h2>Track your document using tracking numbers!</h2>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="{{ route('dts') }}" class="scrollto btn btn-info px-5 py-3" id="track_here"
                            target="_blank"><b>Track Here</b></a>
                    </div>
                </div>
                <div class="col-lg-5 order-2 order-lg-2" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mt-4">
                                <h3>Create Document</h3>
                            </div>
                            <div class="p-3">
                                <form class="form-horizontal mt-3" method="POST" action="{{ route('guest.guestCreate') }}">
                                    @csrf
                                    <div class="form-group mb-3 row">
                                        <div class="col-12">
                                            <input id="name_of_client" class="form-control" type="text"
                                                @error('name_of_client') is-invalid @enderror name="name_of_client"
                                                value="{{ old('name_of_client') }}" required autocomplete="name_of_client"
                                                autofocus placeholder="Agency/Clients/School Name">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <div class="col-12">
                                            <input id="contact" class="form-control" type="text" @error('contact')
                                                is-invalid @enderror name="contact" value="{{ old('contact') }}"
                                                required autocomplete="contact" autofocus placeholder="Contact Number">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <div class="col-12">
                                            <input id="description" class="form-control" type="text" @error('description')
                                                is-invalid @enderror name="description" value="{{ old('description') }}"
                                                required autocomplete="description" autofocus placeholder="Subject">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-center row mt-5 pt-1">
                                        <div class="col-12">
                                            <button class="btn btn-info text-white w-100 waves-effect waves-light"
                                                type="submit">Create</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- end -->
                        </div>
                        <!-- end cardbody -->
                    </div>
                </div>
            </div>
        </div>

    </section><!-- End Hero -->


    <main id="main"></main><!-- End #main -->


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
    <script src="assets2/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>

    <!-- Template Main JS File -->
    <script>
        if('{{ session("success") }}' == 'true') {
            window.open(window.location.href + 'printPDF/{{ session("code") }}', '_blank');
        }
    </script>
    <script src="assets2/js/main.js" defer></script>

</body>

</html>
