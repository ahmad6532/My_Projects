<nav class="main-header navbar navbar-expand navbar-white navbar-light ">
    <ul class="navbar-nav w-100 d-flex justify-content-end align-items-center ">
        <li class="nav-item m-2">
              </li>
        <li class="nav-item m-2">
            <div class="logout-div">
                <span>{{ auth()->user()->firstName }} {{ auth()->user()->lastName }}</span>
                <i class="fa-solid fa-caret-down"></i>
            </div>
            <div class="border-1  border-black shadow-sm" id="logout-dropdown">
                <span
                
                @role('ADMIN')
                data-url ="{{route('admin.show',auth()->user()->id)}}"
                 id="managerProfileView"
                @elserole('MANAGER')
                data-url ="{{route('manager.show',auth()->user()->id)}}"
                 id="managerProfileView"
                @elserole('USER')
                data-url ="{{route('user.show',auth()->user()->id)}}"
                 id="userProfileView"
                @endrole
                 class=" w-100 text-center h-auto  p-2">Profile</span>
                <span class=" w-100 text-center p-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class=" bg-transparent border-0" type="submit">Logout</button>
                    </form>
                </span>
            </div>

        </li>


    </ul>
</nav>
@include('user.modal.viewModal')