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

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Template Main CSS File -->
    <link href="assets2/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="position-relative vh-100 d-flex justify-content-between align-items-center " style="background-image: url('assets/images/cdobg2.jpg'); background-size: cover;">
        <!-- ======= Header ======= -->
        <header id="header" class="fixed-top">
            <div class="container d-flex align-items-center justify-content-between">
                <a href="{{ url('/') }}">
                    <img class="home-logo" src="{{ asset('assets/images/home_logo.png') }}" alt="DEPED CDO Logo">
                </a>
                <nav id="navbar" class="navbar">
                    <ul>
                        @if (auth()->user())
                        <li><a class="nav-link scrollto" href="{{ route('document.incoming') }}">Dashboard</a></li>
                        @else
                        <li><a class="nav-link scrollto" href="{{ route('login') }}">Login</a></li>
                        {{-- <li><a class="nav-link scrollto" href="{{ route('register') }}">Register</a></li> --}}
                        @endif
                    </ul>
                    <i class="ri-menu-line mobile-nav-toggle"></i>
                </nav><!-- .navbar -->
            </div>
        </header><!-- End Header -->

        @include('sweetalert::alert')
        <!-- ======= Hero Section ======= -->
        <section id="hero" class="d-flex align-items-center w-100"
            >
            <div class="container h-100">
                <div class="welcome-content row d-flex justify-content-between flex-md-row flex-column">
                    <div class="col-lg-7 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"
                        data-aos="fade-up" data-aos-delay="200">
                        <h1>Document Tracking System</h1>
                        <h2>Track your document using tracking numbers!</h2>
                        <div class="d-flex justify-content-center justify-content-lg-start">
                            <a href="{{ route('dts') }}" class="scrollto btn btn-info px-5 py-3" id="track_here"
                                target="_blank"><b>Track Here</b></a>
                        </div>
                    </div>
                    <div class="col-lg-5 order-1 order-lg-2" data-aos="zoom-in" data-aos-delay="200">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center mt-2 mb-4">
                                    <h3 style="color: #0097a7; font-weight: 700; font-size: 2rem;">Create Tracking Number</h3>
                                </div>
                                <div class="">
                                    <form class="form-horizontal mt-4" id="create-form" action="{{ route('guest.guestCreate') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group mb-3 row">
                                            <div class="col-12">
                                                <small>Please fill in your name, agency, or school name below.</small>
                                                <input id="name_of_client" class="form-control" type="text"
                                                    @error('name_of_client') is-invalid @enderror name="name_of_client"
                                                    value="{{ old('name_of_client') }}"
                                                    autocomplete="off"
                                                    placeholder="Enter your details">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row">
                                            <div class="col-12">
                                                <input id="contact" class="form-control" type="text" @error('contact')
                                                    is-invalid @enderror name="contact" value="{{ old('contact') }}" autocomplete="off"
                                                    placeholder="Contact number (optional)">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row" id="category_radio_group">
                                            <div class="col-12">
                                                <small>Please select the type of document you will submit to the receiving office.</small>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="category_radio" type="radio" id="regular" value="regular" checked>
                                                    <label class="form-check-label" for="regular">
                                                        Regular Transaction
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="category_radio" type="radio" id="separation" value="separation" >
                                                    <label class="form-check-label" for="separation">
                                                        Separation from Service
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="form-group mb-3 row d-none" id="separation_desc">
                                            <div class="col-12">
                                                <select name="separation_desc" class="form-select" >
                                                    <option value="" selected>Select Category</option>
                                                    <option value="Retirement">Retirement</option>
                                                    <option value="Resignation">Resignation</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3 row" id="regular_desc">
                                            <div class="col-12">
                                                <input class="form-control" type="text"
                                                    @error('regular_desc') is-invalid @enderror name="regular_desc"
                                                    value="{{ old('regular_desc') }}" autocomplete="regular_desc"
                                                    placeholder="Enter the title of the file to be received">
                                            </div>

                                        </div>
                                        <div class="form-group mb-3 text-center row mt-4 pt-1">
                                            <div class="col-12">
                                                <button id="create_btn" class="btn btn-info text-white w-100 waves-effect waves-light"
                                                    type="button">Create</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section><!-- End Hero -->


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
    </div>

    <!-- Vendor JS Files -->
    <script src="assets2/vendor/aos/aos.js"></script>
    <script src="assets2/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}" ></script>

    <!-- Template Main JS File -->
    <script defer>
        if ('{{ session("success") }}' == 'true') {
            window.open(window.location.href + 'printPDF/{{ session("code") }}', '_blank');
        }
    </script>
    <script src="assets2/js/main.js" defer></script>
</body>

</html>


