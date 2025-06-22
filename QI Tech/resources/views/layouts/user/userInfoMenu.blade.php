<nav class='page-menu'>
    <ul>
        <li><a class="@if(request()->route()->getName() == 'userInfo') active2 @endif" href="/userInfo">My Info</a></li>
        <li><a class="@if(request()->route()->getName() == 'userPasswordAndSecurity') active2 @endif"
                href="/userPasswordAndSecurity">Password & Security</a></li>
    </ul>
</nav>