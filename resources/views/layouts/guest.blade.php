<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/logowo.png') }}">

     
        
        <script src="https://cdn.tailwindcss.com"></script>
      
        <title>Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex justify-center items-center pt-6 sm:pt-0 bg-blue-100">
            <!-- Form Container -->
            <div class="w-11/12 sm:w-96 bg-white p-6 rounded-md h-[400px] sm:h-auto">
                {{ $slot }}
            </div>
        </div>
        
    </body>
</html>