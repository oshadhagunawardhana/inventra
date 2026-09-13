<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Login | Inventra
    </title>


    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #eef4ff 0%,
                    #f7f9fc 50%,
                    #edf4ff 100%
                );

            font-family:
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            overflow: hidden;
        }


        /* =====================================
           BACKGROUND DECORATION
        ===================================== */

        body::before {
            content: "";

            position: fixed;

            width: 420px;
            height: 420px;

            top: -170px;
            left: -150px;

            border-radius: 50%;

            background:
                rgba(
                    13,
                    110,
                    253,
                    0.10
                );

            animation:
                backgroundFloatOne
                8s ease-in-out
                infinite;
        }


        body::after {
            content: "";

            position: fixed;

            width: 380px;
            height: 380px;

            right: -130px;
            bottom: -170px;

            border-radius: 50%;

            background:
                rgba(
                    13,
                    110,
                    253,
                    0.08
                );

            animation:
                backgroundFloatTwo
                9s ease-in-out
                infinite;
        }


        @keyframes backgroundFloatOne {

            0%,
            100% {
                transform:
                    translate(
                        0,
                        0
                    );
            }

            50% {
                transform:
                    translate(
                        20px,
                        25px
                    );
            }

        }


        @keyframes backgroundFloatTwo {

            0%,
            100% {
                transform:
                    translate(
                        0,
                        0
                    );
            }

            50% {
                transform:
                    translate(
                        -20px,
                        -20px
                    );
            }

        }


        /* =====================================
           PAGE
        ===================================== */

        .login-page {
            width: 100%;

            padding: 20px;

            position: relative;

            z-index: 2;

            animation:
                loginPageFade
                0.7s ease-out;
        }


        @keyframes loginPageFade {

            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }

        }


        /* =====================================
           LOGIN CARD
        ===================================== */

        .login-card {
            width: 100%;

            max-width: 430px;

            margin: auto;

            padding: 35px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.96
                );

            border-radius: 22px;

            border:
                1px solid
                rgba(
                    13,
                    110,
                    253,
                    0.08
                );

            border-top:
                5px solid
                #0d6efd;

            box-shadow:
                0 20px 50px
                rgba(
                    0,
                    0,
                    0,
                    0.10
                );

            animation:
                loginCardIn
                0.65s ease-out;

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }


        .login-card:hover {
            transform:
                translateY(-3px);

            box-shadow:
                0 25px 60px
                rgba(
                    0,
                    0,
                    0,
                    0.12
                );
        }


        @keyframes loginCardIn {

            from {
                opacity: 0;

                transform:
                    translateY(30px)
                    scale(0.96);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }

        }


        /* =====================================
           BRAND
        ===================================== */

        .brand-area {
            text-align: center;

            margin-bottom: 30px;
        }


        .login-logo {
            width: 82px;
            height: 82px;

            object-fit: contain;

            margin-bottom: 12px;

            animation:
                logoFloat
                3s ease-in-out
                infinite;
        }


        @keyframes logoFloat {

            0%,
            100% {
                transform:
                    translateY(0);
            }

            50% {
                transform:
                    translateY(-6px);
            }

        }


        .brand-title {
            margin: 0;

            font-size: 30px;

            font-weight: 750;

            color: #212529;
        }


        .brand-subtitle {
            margin-top: 5px;
            margin-bottom: 0;

            color: #6c757d;

            font-size: 14px;
        }


        /* =====================================
           FORM
        ===================================== */

        .form-label {
            font-weight: 600;

            margin-bottom: 7px;

            color: #343a40;
        }


        .input-group-text {
            background: #f8f9fa;

            border-right: 0;

            color: #6c757d;

            transition:
                border-color 0.25s ease,
                color 0.25s ease;
        }


        .input-group .form-control {
            border-left: 0;
        }


        .form-control {
            min-height: 47px;

            transition:
                border-color 0.25s ease,
                box-shadow 0.25s ease,
                transform 0.25s ease;
        }


        .input-group:focus-within
        .input-group-text {
            border-color: #86b7fe;

            color: #0d6efd;
        }


        .form-control:focus {
            transform:
                translateY(-1px);

            box-shadow:
                0 0 0
                0.2rem
                rgba(
                    13,
                    110,
                    253,
                    0.12
                );
        }


        /* =====================================
           LOGIN BUTTON
        ===================================== */

        .login-btn {
            min-height: 48px;

            font-weight: 600;

            border-radius: 10px;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }


        .login-btn:hover {
            transform:
                translateY(-2px);

            box-shadow:
                0 8px 20px
                rgba(
                    13,
                    110,
                    253,
                    0.28
                );
        }


        .login-btn:active {
            transform:
                scale(0.98);
        }


        /* =====================================
           ERRORS
        ===================================== */

        .login-alert {
            border-radius: 10px;

            font-size: 14px;

            animation:
                errorSlide
                0.4s ease;
        }


        @keyframes errorSlide {

            from {
                opacity: 0;

                transform:
                    translateY(-10px);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0);
            }

        }


        /* =====================================
           FOOTER
        ===================================== */

        .login-footer {
            margin-top: 25px;

            text-align: center;

            color: #9aa0a6;

            font-size: 12px;
        }


        /* =====================================
           MOBILE
        ===================================== */

        @media (
            max-width: 576px
        ) {

            .login-card {
                padding: 28px 22px;

                border-radius: 18px;
            }


            .login-logo {
                width: 70px;
                height: 70px;
            }


            .brand-title {
                font-size: 27px;
            }

        }


        /* =====================================
           ACCESSIBILITY
        ===================================== */

        @media (
            prefers-reduced-motion: reduce
        ) {

            *,
            *::before,
            *::after {
                animation: none !important;

                transition: none !important;
            }

        }

    </style>

</head>


<body>


<div class="login-page">


    <div class="login-card">


        {{-- =========================
             BRAND
        ========================== --}}

        <div class="brand-area">


            <img
                src="{{ asset('images/inventra-logo.png') }}"
                alt="Inventra Logo"
                class="login-logo"
            >


            <h1 class="brand-title">
                Inventra
            </h1>


            <p class="brand-subtitle">
                Inventory & Sales Management System
            </p>


        </div>



        {{-- =========================
             ERRORS
        ========================== --}}

        @if ($errors->any())

            <div
                class="alert alert-danger login-alert"
                role="alert"
            >

                <i
                    class="bi bi-exclamation-circle-fill me-1"
                ></i>

                {{ $errors->first() }}

            </div>

        @endif



        {{-- =========================
             LOGIN FORM
        ========================== --}}

        <form
            action="{{ route('login.submit') }}"
            method="POST"
        >

            @csrf


            {{-- Email --}}

            <div class="mb-3">

                <label
                    for="email"
                    class="form-label"
                >
                    Email Address
                </label>


                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-envelope"></i>

                    </span>


                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter your email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                    >

                </div>

            </div>



            {{-- Password --}}

            <div class="mb-4">

                <label
                    for="password"
                    class="form-label"
                >
                    Password
                </label>


                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-lock"></i>

                    </span>


                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >

                </div>

            </div>

            <div class="text-end mb-3">
    <a
        href="{{ route('password.request') }}"
        class="text-decoration-none"
    >
        Forgot Password?
    </a>
</div>



            {{-- Login Button --}}

            <button
                type="submit"
                class="btn btn-primary login-btn w-100"
            >

                <i
                    class="bi bi-box-arrow-in-right me-1"
                ></i>

                Login

            </button>


        </form>



        {{-- =========================
             FOOTER
        ========================== --}}

        <div class="login-footer">

            Inventra Inventory & Sales System

        </div>


    </div>


</div>


</body>

</html>