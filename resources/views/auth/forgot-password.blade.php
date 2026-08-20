<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Family</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-gray-100 min-h-screen flex items-center justify-center">


<div class="bg-yellow-700 p-2 rounded-3xl shadow-xl">


    <div class="bg-white w-96 rounded-3xl p-8">


        <h1 class="text-2xl font-bold text-center text-black mb-4">
            Forgot Password?
        </h1>


        <p class="text-gray-600 text-center mb-6">
            Enter your email address and we will send you a password reset link.
        </p>



        @if(session('status'))

            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('status') }}
            </div>

        @endif




        <form method="POST" action="{{ route('password.email') }}">

            @csrf


            <label class="block mb-2 text-gray-700">
                Email Address
            </label>


            <input 
                type="email"
                name="email"
                class="w-full border rounded-xl p-3 mb-5"
                placeholder="Enter your email"
                required
            >



            <button 
                type="submit"
                class="w-full bg-yellow-700 hover:bg-yellow-800 text-white py-3 rounded-xl font-bold"
            >
                Send Reset Link
            </button>



        </form>



        <div class="text-center mt-5">

            <a href="{{ route('login') }}"
               class="text-yellow-700 hover:underline">

                Back to Login

            </a>

        </div>


    </div>


</div>


</body>
</html>