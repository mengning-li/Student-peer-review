<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('css/wp.css') }}" type="text/css">
    </head>
    <body class="antialiased">
    <div class="b-example-divider"></div>
<!-- header form -->
    <header class="p-3 text-bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
        </a>

        <!-- <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="#" class="nav-link px-2 text-secondary">Home</a></li>
            <li><a href="#" class="nav-link px-2 text-white">Features</a></li>
            <li><a href="#" class="nav-link px-2 text-white">Pricing</a></li>
            <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 text-white">About</a></li>
        </ul> -->

        <div class="text-end">
            <a href="{{ route('login') }}" class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 focus:outline focus:outline-2 focus:outline-blue-500">Login</a>
            <a href="{{ route('register') }}" class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 focus:outline focus:outline-2 focus:outline-blue-500">Register</a>
        </div>
        </div>
    </div>
    </header>
  

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">                   
                <img src="{{ asset('images/Griffith_University_Logo.png') }}" alt="My Logo" class="logo" width="200" height="auto">
                </div>               
            </div>
        </div>
    </body>
</html>
