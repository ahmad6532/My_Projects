<div style="position: absolute">
    <div>
<h1>Insert Data</h1>
<form action="{{route('go')}}" method="post">
    @csrf

    Name:<input type="text" name="name"><br>
    Phone:<input type="text" name="phone"><br>
    <button type="submit">submit</button>


</form>
</div>
<div style="margin-left:250px; margin-top: -140px">

    <h1>Update Data</h1>
    <form action="{{route('up')}}" method="post">
        @csrf
        ID: <input type="text" name="id"><br>
        Name:<input type="text" name="name"><br>
        Phone:<input type="text" name="phone"><br>
        <button type="submit">update</button>


    </form>



</div>
<div style="margin-left:530px; margin-top: -160px">

    <h1>Delete Data</h1>
    <form action="{{route('del')}}" method="post">
        @csrf
        ID: <input type="text" name="id"><br>

        <button type="submit">Delete</button>


    </form>



</div>
<div style="margin-left:250px; margin-top: 110px; ">

    <h1>Display Data</h1>
    <table border="1" style="text-align: center">
        <tbody>
        <tr>
            <td><b>ID</b></td>
            <td><b>Name </b></td>
            <td><b>Phone </b> </td>
        </tr>
@foreach($data as $data)

        <tr>
            <td> {{$data->id}}</td>
            <td>{{$data->Name}} </td>
            <td>{{$data->phone}} </td>
        </tr>
@endforeach
        </tbody>
    </table>



</div>
</div>
