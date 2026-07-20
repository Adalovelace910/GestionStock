<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Family</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body
    class="min-h-screen flex items-center justify-center bg-cover bg-center"
    style="background-image: url('/images/Fa.jpeg');">

    <!-- Login Card -->
    <div class="bg-white w-full max-w-md rounded-1xl shadow-xl p-5">

        <!-- Title -->
        <div class="text-center mb-1">
            <h1 class="text-2xl font-bold text-black">
                Login
            </h1>
        </div>

        <!-- Error message -->
        @if($errors->any())
            <div class="bg-red-100 text-red-300 p-2 rounded-lg mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">

            @csrf

            <!-- Email -->
            <div class="mb-2">

                <label class="block text-gray-500 mb-1">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    autocomplete="off"
                    value=""
                    placeholder="Enter your email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600 focus:outline-none"
                    required>

            </div>

            <!-- Password -->
            <div class="mb-4">

                <label class="block text-gray-500 mb-1">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    autocomplete="new-password"
                    value=""
                    placeholder="Enter your password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600 focus:outline-none"
                    required>

            </div>

            <!-- Remember + Forgot password -->
            <div class="flex justify-between items-center mb-6">

                <label class="flex items-center text-sm text-gray-600">

                    <input
                        type="checkbox"
                        name="remember"
                        class="mr-2">

                    Remember me

                </label>

                <a
                    href="{{ route('password.request') }}"
                    class="text-green-600 hover:text-green-700 hover:underline text-sm font-semibold">

                    Forgot password?

                </a>

            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition">

                Sign In

            </button>

        </form>

</body>

</html>