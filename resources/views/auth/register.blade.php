<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Register | DepEd CDO Client Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/depedcdo.png') }}">

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Template Main CSS File -->
    <link href="assets2/css/style.css" rel="stylesheet">
</head>

<body class="auth-body-bg position-relative">
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
                    <li><a class="nav-link scrollto" href="{{ route('register') }}">Register</a></li>
                    @endif
                </ul>
                <i class="ri-menu-line mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
        </div>
    </header><!-- End Header -->
    <div class=""></div>
    <div class="wrapper-page">
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-body">

                    <div class="text-center mt-4">
                        <a href="{{ url('/') }}">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <img class="" style="width: 22rem;" src="{{ asset('assets/images/dtslogo.png') }}"
                                    alt="Header Avatar">
                            </div>
                        </a>
                    </div>

                    <div class="p-2">
                        <form action="{{ route('guestStore') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body p-0">
                                <div class="d-flex flex-column gap-3">
                                    <div class="form-group">
                                        <label for="role" class="form-label">Role</label>
                                        <select name="role" id="role" class="form-select" placeholder="Choose role"
                                            required>
                                            <option value="0" selected>Office Terminal</option>
                                            <option value="1">Decision Maker</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            placeholder="Enter first name..." value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" id="middle_name" class="form-control"
                                            placeholder="Enter middle name..." value="{{ old('middle_name') }}">
                                        @error('middle_name')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                            placeholder="Enter last name..." value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="office_id" class="form-label">Office/Unit</label>
                                        <select name="office_id" id="office_id" class="form-select"
                                            placeholder="Choose Office/Unit" required>
                                            <option value="">Choose Office/Unit</option>
                                            @foreach ($offices as $office)
                                            <option value="{{ $office->id }}"
                                                {{ old('office_id') == $office->id? 'selected':'' }}>
                                                {{ $office->office_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('office_id')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="terminal_name" class="form-label">Terminal Name</label>
                                        {{-- <small>(This will be the name of your office terminal and can be used to forward the documents.)</small> --}}
                                        <input type="text" name="terminal_name" id="terminal_name" class="form-control"
                                            placeholder="Enter terminal name..." value="{{ old('terminal_name') }}"
                                            required>
                                        @error('terminal_name')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Enter email..." value="{{ old('email') }}" required>
                                        @error('email')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-between gap-2">
                                        <div class="form-group w-100">
                                            <label for="password" class="form-label">Password</label>
                                            <input name="password" type="password" id="password" class="form-control"
                                                placeholder="Enter password..." required>
                                            @error('password')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group w-100">
                                            <label for="password_confirmation" class="form-label">Confirm
                                                Password</label>
                                            <input name="password_confirmation" type="password"
                                                id="password_confirmation" class="form-control"
                                                placeholder="Enter password..." required>
                                            @error('password_confirmation')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center row mt-3 pt-4">
                                <div class="col-12">
                                    <button class="btn btn-info w-100 waves-effect waves-light"
                                        type="submit">Register</button>
                                </div>
                            </div>

                            <div class="form-group mt-2 mb-0 row">
                                <div class="col-12 mt-3 text-center">
                                    <a href="{{ route('login') }}" class="text-muted">Already have account?</a>
                                </div>
                            </div>
                        </form>
                        <!-- end form -->
                    </div>
                </div>
                <!-- end cardbody -->
            </div>
            <!-- end card -->
        </div>
        <!-- end container -->
    </div>
    <!-- end -->
    @include('sweetalert::alert')


    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>

    <script src="assets/js/app.js"></script>

</body>

</html>
