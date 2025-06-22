<style>
    .btn{
        width: 100px;
        border: none;
    height: 45px;
    }
    .btn-primary{
        background-color: #2BAFA5;
        color:#fff;
    }
    </style>
<h2>Location details update requested</h2>
<p>Company: {{ $location->trading_name }}</p>
<br />
A user {{ $user->name }} has requested to update company's details according to following:<br />
If you want to update the details, please click confirm. Otherwise ignore this email.
<table border="1">
    <thead>
        <tr>
            <th>Attribute</th>
            <td>Update</td>
        </tr>
    </thead>
    <tbody>
        @foreach($vals as $vk => $vv)
        <tr>
            <th>{{ $vk }}</th>
            <td>{{ $vv }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2">
                <a href="{{ $link }}" class="btn btn-primary">Confirm</a>
            </td>
        </tr>
    </tbody>

</table>
<p>
    This is a system generated email. Please don't reply.
</p>
<p>
    Copyright &copy; {{ \Carbon\Carbon::now()->year }} {{ env('APP_NAME') }} 
</p>