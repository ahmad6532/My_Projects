
<h3 class="text-info h3 font-weight-bold">Verified Devices</h3>

<table border="0" id="scheduleTable" class="table table-responsive table_full_width">
    <thead>
        <tr>
            <th>Device</th>
            <th>Location</th>
            <th>IP</th>
            <th></th>
        </tr>
    </thead>
    @foreach ($l_u_s as $session)
        
    
        <tr>
            <td>
                {{$session->browser}}
            </td>
            <td>
                {{$session->city}}, {{$session->country}}
            </td>
            
            <td>
                {{$session->ip}}
            </td>
            <td>
                @if($session->user_session && (session('user_session') !== $session->user_session))
                <a href="{{route('head_office.end_head_office_user_session',['id' =>$session->user_session,'_token' => csrf_token()])}}">End Session</a>
                @endif
            </td>
        </tr>
        @endforeach
    <tbody>
    </tbody>
</table>