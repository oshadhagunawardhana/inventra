<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Forgot Password | Inventra</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        body {
            min-height: 100vh;
            margin: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #eef4ff,
                    #f8f9fc
                );

            font-family: system-ui, sans-serif;
        }

        .forgot-card {
            width: 100%;
            max-width: 430px;

            padding: 35px;

            background: white;

            border-radius: 22px;
            border-top: 5px solid #0d6efd;

            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.10);

            animation:
                cardIn 0.55s ease-out;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform:
                    translateY(25px)
                    scale(0.96);
            }

            to {
                opacity: 1;
                transform:
                    translateY(0)
                    scale(1);
            }
        }

        .forgot-logo {
            width: 75px;
            height: 75px;

            object-fit: contain;

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

        .btn,
        .form-control {
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

<div class="forgot-card">

    <div class="text-center mb-4">

        <img
            src="{{ asset('images/inventra-logo.png') }}"
            alt="Inventra"
            class="forgot-logo mb-2"
        >

        <h3 class="mb-1">
            Forgot Password?
        </h3>

        <p class="text-muted mb-0">
            Enter your registered email address to receive a password reset link.
        </p>

    </div>


    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif


    @error('email')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror


    <form
        action="{{ route('password.email') }}"
        method="POST"
    >
        @csrf

        <div class="mb-4">

            <label
                for="email"
                class="form-label fw-semibold"
            >
                Email Address
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>

                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                    autofocus
                >

            </div>

        </div>


        <button
            type="submit"
            class="btn btn-primary w-100"
        >
            <i class="bi bi-send me-1"></i>
            Send Reset Link
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

</body>

</html>