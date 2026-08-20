<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password - Family</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-yellow-700 p-2 rounded-3xl shadow-xl">

        <div class="bg-white w-96 rounded-3xl p-8">

            <h1 class="text-2xl font-bold text-center text-black mb-4">
                Reset Password
            </h1>

            <p class="text-gray-600 text-center mb-6">
                Enter your new password below.
            </p>

            {{-- Messages d'erreur --}}
            @if ($errors->any())

                <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4">

                    <ul class="list-disc list-inside">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form method="POST" action="{{ route('password.reset.submit') }}">

                @csrf

                {{-- Token de réinitialisation --}}
                <input
                    type="hidden"
                    name="token"
                    value="{{ $token }}"
                >


                {{-- Email --}}
                <label class="block mb-2 text-gray-700">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', request()->email) }}"
                    class="w-full border rounded-xl p-3 mb-5"
                    placeholder="Enter your email"
                    required
                    autofocus
                >


                {{-- Nouveau mot de passe --}}
                <label class="block mb-2 text-gray-700">
                    New Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-xl p-3 mb-5"
                    placeholder="Enter your new password"
                    required
                >


                {{-- Confirmation --}}
                <label class="block mb-2 text-gray-700">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full border rounded-xl p-3 mb-5"
                    placeholder="Confirm your new password"
                    required
                >


                {{-- Bouton --}}
                <button
                    type="submit"
                    class="w-full bg-yellow-700 hover:bg-yellow-800 text-white py-3 rounded-xl font-bold"
                >
                    Reset Password
                </button>

            </form>


            {{-- Retour connexion --}}
            <div class="text-center mt-5">

                <a
                    href="{{ route('login') }}"
                    class="text-yellow-700 hover:underline"
                >
                    Back to Login
                </a>

            </div>

        </div>

    </div>

</body>

</html>
