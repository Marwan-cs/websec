<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('even') }}">Even Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('prime') }}">Prime Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('multable') }}">Multiplication Table</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('products_list') }}">Products List</a>
            </li>
            
        </ul>
        <ul class="navbar-nav">
            @auth
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('do_logout') }}">Logout</a>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            </li>
            @endauth
        </ul>
    </div>
</nav>
