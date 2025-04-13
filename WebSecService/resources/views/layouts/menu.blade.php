<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/even') }}">Even Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/prime') }}">Prime Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/multable') }}">Multiplication Table</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('products.list') }}">Products</a>
            </li>

            @role('Admin')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users') }}">Users</a>
            </li>
            @endrole

            @role('Employee')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('list_customers') }}">Customers</a>
            </li>
            @endrole
            @role('Customer')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('products_purchases') }}">My Purchases</a>
            </li>
            @endrole

            <!-- @role('Admin')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('create_employee') }}">Add Employee</a>
            </li>
            @endrole -->
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
