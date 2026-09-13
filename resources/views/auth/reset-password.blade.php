<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Reset Password | Inventra</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

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
                    #f8f9fc 100%
                );

            font-family:
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            overflow: hidden;
        }

        body::before {
            content: "";

            position: fixed;

            width: 420px;
            height: 420px;

            top: -180px;
            left: -150px;

            border-radius: 50%;

            background:
                rgba(13, 110, 253, 0.10);

            animation:
                backgroundOne 8s ease-in-out infinite;
        }

        body::after {
            content: "";

            position: fixed;

            width: 380px;
            height: 380px;

            right: -140px;
            bottom: -170px;

            border-radius: 50%;

            background:
                rgba(13, 110, 253, 0.08);

            animation:
                backgroundTwo 9s ease-in-out infinite;
        }

        @keyframes backgroundOne {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(20px, 20px);
            }

        }

        @keyframes backgroundTwo {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(-20px, -20px);
            }

        }

        .reset-page {
            width: 100%;

            padding: 20px;

            position: relative;

            z-index: 2;

            animation:
                pageFade 0.7s ease-out;
        }

        @keyframes pageFade {

            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }

        }

        .reset-card {
            width: 100%;

            max-width: 430px;

            margin: auto;

            padding: 35px;

            background: rgba(255, 255, 255, 0.97);

            border-radius: 22px;

            border-top: 5px solid #0d6efd;

            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.10);

            animation:
                cardIn 0.6s ease-out;
        }

        @keyframes cardIn {

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

        .reset-logo {
            width: 78px;
            height: 78px;

            object-fit: contain;

            margin-bottom: 10px;

            animation:
                logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

        }

        .brand-title {
            font-size: 29px;

            font-weight: 750;

            margin: 0;
        }

        .brand-subtitle {
            color: #6c757d;

            font-size: 14px;

            margin-top: 5px;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            min-height: 47px;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                transform 0.2s ease;
        }

        .form-control:focus {
            transform: translateY(-1px);

            box-shadow:
                0 0 0
                0.2rem
                rgba(13, 110, 253, 0.12);
        }

        .input-group-text {
            background: #f8f9fa;

            border-right: 0;

            color: #6c757d;
        }

        .input-group .form-control {
            border-left: 0;
        }

        .reset-btn {
            min-height: 48px;

            border-radius: 10px;

            font-weight: 600;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .reset-btn:hover {
            transform: translateY(-2px);

            box-shadow:
                0 8px 20px
                rgba(13, 110, 253, 0.25);
        }

        .alert {
            animation:
                alertIn 0.4s ease;
        }

        @keyframes alertIn {

            from {
                opacity: 0;

                transform: translateY(-8px);
            }

            to {
                opacity: 1;

                transform: translateY(0);
            }

        }

        @media (max-width: 576px) {

            .reset-card {
                padding: 28px 22px;
            }

        }

    </style>

</head>


<body>


<div class="reset-page">

    <div class="reset-card">

        <div class="text-center mb-4">

            <img
                src="{{ asset('images/inventra-logo.png') }}"
                alt="Inventra"
                class="reset-logo"
            >

            <h1 class="brand-title">
                Reset Password
            </h1>

            <p class="brand-subtitle">
                Create a new secure password for your Inventra account.
            </p>

        </div>


        @if ($errors->any())

            <div class="alert alert-danger">

                <i class="bi bi-exclamation-circle-fill me-1"></i>

                {{ $errors->first() }}

            </div>

        @endif


        <form
            action="{{ route('password.update') }}"
            method="POST"
        >

            @csrf


            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            >


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
                        value="{{ old('email', $email) }}"
                        placeholder="Enter your email"
                        required
                        autofocus
                    >

                </div>

            </div>


            <div class="mb-3">

                <label
                    for="password"
                    class="form-label"
                >
                    New Password
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
                        placeholder="Enter new password"
                        required
                    >

                </div>

            </div>


            <div class="mb-4">

                <label
                    for="password_confirmation"
                    class="form-label"
                >
                    Confirm Password
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-shield-lock"></i>
                    </span>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirm new password"
                        required
                    >

                </div>

            </div>


            <button
                type="submit"
                class="btn btn-primary reset-btn w-100"
            >
                <i class="bi bi-arrow-repeat me-1"></i>
                Reset Password
            </button>


            <div class="text-center mt-3">

                <a
                    href="{{ route('login') }}"
                    class="text-decoration-none"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Login
                </a>

            </div>

        </form>

    </div>

</div>


</body>

</html>