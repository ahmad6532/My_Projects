<div class="text-center">
    <img class="circle-img" src="{{asset('v2/images/user.jpg')}}" />
    <div class="content-page-heading">
        {{$user->name}}
    </div>
</div>

<nav class='page-menu bordered'>
    <ul class="nav nav-tab main_header main_menu">
        <li><a class="@if(request()->route()->getName() =='user.view_profile' ) active @endif" href="{{route('user.view_profile')}}">Account<span></span></a></li>
        <li><a class="@if(request()->route()->getName() =='user.shared_cases' ) active @endif" href="{{route('user.shared_cases')}}">Shared<span></span></a></li>
        <li><a class="@if(request()->route()->getName() =='user.statement' ) active @endif" href="{{route('user.statement')}}">Information Requests<span></span></a></li>
        <li><a class="@if(request()->route()->getName() =='user.shared_cases' ) active @endif" href="{{route('user.shared_cases')}}">Drafts<span></span></a></li>
    </ul>
</nav>
<hr class="hrBeneathMenu">