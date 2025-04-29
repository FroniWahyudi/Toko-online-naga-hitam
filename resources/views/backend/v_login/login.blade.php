<!DOCTYPE html>
<html lang="id" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Login Page Toko Online">
    <meta name="author" content="">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon_univ_bsi.png') }}">
    <title>Login | Toko Online</title>

    <link href="{{ asset('backend/dist/css/style.min.css') }}" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
            <div class="auth-box bg-dark border-top border-secondary">
                <div class="text-center p-t-20 p-b-20">
                    <span class="db"><img src="{{ asset('image/icon_univ_bsi.png') }}" alt="logo"/></span>
                </div>

                <!-- Form Login -->
                <form class="form-horizontal m-t-20" id="form-login" action="{{ route('backend.login.submit') }}" method="POST">
                    @csrf
                    <div class="row p-b-30">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-success text-white"><i class="ti-user"></i></span>
                                </div>
                                <input type="text" name="email" value="{{ old('email') }}" class="form-control form-control-lg"
                                    placeholder="Username" required autofocus>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-warning text-white"><i class="ti-pencil"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control form-control-lg"
                                    placeholder="Password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top border-secondary">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="p-t-20">
                                    <button type="button" id="to-recover" class="btn btn-info">
                                        <i class="fa fa-lock m-r-5"></i> Lost password?
                                    </button>
                                    <button type="submit" class="btn btn-success float-right">Login</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Form Recover Password (Sembunyikan dulu) -->
                <form class="form-horizontal" id="form-recover" style="display:none;">
                    <div class="text-center">
                        <span class="text-white">Form Recover Password (belum aktif)</span>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-12">
                            <button type="button" id="to-login" class="btn btn-info btn-block">
                                Back to Login
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- JS Files -->
    <script src="{{ asset('backend/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('backend/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script>
    $(function() {
        $(".preloader").fadeOut();

        // Switch between Login & Recover
        $('#to-recover').click(function() {
            $("#form-login").slideUp();
            $("#form-recover").fadeIn();
        });
        $('#to-login').click(function() {
            $("#form-recover").hide();
            $("#form-login").fadeIn();
        });
    });
    </script>
</body>

</html>
