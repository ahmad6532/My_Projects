@extends('layouts.users_app')
@section('content')
<div class="wrapper">
    <nav id="sidebar" class="sidebar-container-user">
        @yield('sidebar');
    </nav>
    <!-- Page Content  -->
    <div id="content">

        <!-- Profile page heading -->

        <div style="display: flex; justify-content: flex-end;">

            <div>

                <div class="right-side-items">
                    <div class="notification-icon signout-label">
                        <a href="#" title="Signout">
                            Sign out
                        </a>
                    </div>
                    <!--

                    <div class="notification-icon">
                        <a href="#" title="Notifications">
                            <i class="fa-regular fa-bell" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="help-icon">
                        <a href="#" title="Help">
                            <i class="fa-regular fa-question" aria-hidden="true"></i>
                        </a>
                    </div> 
                -->
                </div>
            

            </div>

        </div>



        <div class="profile-center-area">
            
            <div class="text-center">
                <img class="circle-img" src="{{asset('v2/images/user.jpg')}}" />
                <div class="content-page-heading">
                    Hello Raja!
                </div>
            </div>

            <nav class='page-menu bordered'>
                <ul>
                    <li><a class="active" href="#">Account<span></span></a></li>
                    <li><a href="#">Shared<span></span></a></li>
                    <li><a href="#">Information Requests<span></span></a></li>
                    <li><a href="#">Drafts<span></span></a></li>
                </ul>
            </nav>
            <hr class="hrBeneathMenu">

            @yield('sub-content')

            <!-- profile page contents -->


        </div>
    </div>
</div>


@endsection