<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Basic Website - @yield('title')</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>
    @include('layouts.menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('cart.index') }}">
            Cart 
            @if(count(session('cart', [])))
                <span class="badge bg-danger">
                    {{ array_sum(session('cart', [])) }}
                </span>
            @endif
        </a>
    </li>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
