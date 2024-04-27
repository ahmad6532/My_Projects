<nav class="main-header navbar navbar-expand navbar-white navbar-light ">
    <ul class="navbar-nav w-100 d-flex justify-content-end align-items-center ">
        <li class="nav-item m-2">
            {{-- <span>Hi, <span class=" fw-bold ">{{auth()->user()->name}}</span></span> --}}
        </li>
        <li class="nav-item m-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class=" bg-transparent border-0" type="submit">Logout</button>
            </form>
        </li>


    </ul>
</nav>
