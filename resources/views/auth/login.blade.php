<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Family</title>

    <!-- Bootstrap CSS (local) -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
</head>

<body
    class="d-flex align-items-center justify-content-center min-vh-100 bg-cover"
    style="background-image: url('/images/Fa.jpeg'); background-size: cover; background-position: center;">

    <!-- Login Card -->
    <div class="bg-white w-100 rounded-4 shadow p-4" style="max-width: 400px;">

        <!-- Title -->
        <div class="text-center mb-1">
            <h1 class="fs-3 fw-bold text-dark">
                Connexion
            </h1>
        </div>

        <!-- Success message -->
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <!-- Error message -->
        @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">

            @csrf

            <!-- Email -->
            <div class="mb-2">

                <label class="form-label text-secondary">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    autocomplete="off"
                    value=""
                    placeholder="Enter your email"
                    class="form-control rounded-3"
                    required>

            </div>

            <!-- Password -->
            <div class="mb-4">

                <label class="form-label text-secondary">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    autocomplete="new-password"
                    value=""
                    placeholder="Enter your password"
                    class="form-control rounded-3"
                    required>

            </div>

            <!-- Remember + Forgot password -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <label class="d-flex align-items-center small text-secondary">

                    <input
                        type="checkbox"
                        name="remember"
                        class="form-check-input me-2">

                    Remember me

                </label>


                <a href="{{ route('password.request') }}" class="text-success text-decoration-none small fw-semibold">

                    Forgot password?

                </a>

            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-100 btn btn-success fw-bold py-2 rounded-3">

                Se Connecter

            </button>

        </form>

</body>

</html>