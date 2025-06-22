@extends('layouts.head_office_app')
@section('title', 'Tracking Links ')

@section('sub-header')
@include('head_office.case_manager.notes.sub-header')
@endsection
<style>
    .no-shadow{
        box-shadow: none !important;
        font-size: 12px !important;
        opacity: 0.5;
        transition: 0.2s ease !important;
    }
    .no-shadow:hover,.no-shadow:focus{
        transition: 0.2s ease-in-out !important;
        opacity: 1;
    }
    .link-wrap a{
        color: #2BAFA5;
        text-decoration: underline;
    }
</style>
@section('content')
<div id="content">
@include('layouts.error')
<h5 class="h4 text-center">Tracking Links</h5>
<div class="w-75 mx-auto" style="overflow-x:auto ">
    <div>
        @if(count($links) == 0)
        <h4 class="h2 text-secondary text-center mt-5"> No Links Yet 🙂!</h4>
        @else
        <table class="table table-bordered table-striped w-100" id="session-dataTable">
            <thead >
                <th>ID</th>
                <th>Clicks</th>
                <th>Comment</th>
                <th>Status</th>
                <th>Redirect to</th>
                <th>Created At</th>
                <th>Actions</th>

            </thead>
            <tbody>
                @foreach ($links as $link )
                    <tr class="text-center">
                        <td>{{$link->id}}</td>
                        <td>{{$link->clicks}}</td>
                        <td class="link-wrap" style="max-width: 100px;overflow-x:auto;">{!! $link->comment()->comment !!}</td>
                        <td><span class="badge rounded-pill {{$link->is_active ? 'text-bg-success' : 'text-bg-danger'}}">{{$link->is_active ? 'active' : 'In-active'}}</span></td>
                        <td><input type="text" class="form-control no-shadow" value="{{$link->link}}" onfocusout="updateLink({{$link->id }},this)"></td>
                        <td>{{$link->created_at->format('d M Y h:i A')}}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2 mx-auto">
                                <a class="delete_form" href="{{ route('head_office.tracking_link.active', ['id'=>$link->id,'_token'=>csrf_token()]) }}">
                                                @if ($link->is_active)
                                                <img title="Deactivate"
                                                    src="{{ asset('v2/images/icons/arrow-circle-broken-up-left.svg') }}"
                                                    alt="">
                                                @else
                                                <img title="Activate"
                                                    src="{{ asset('v2/images/icons/arrow-circle-broken-up-right.svg') }}"
                                                    alt="">
                                                @endif
                                            </a>
                                            <a class="text-info delete_form" title="Delete"
                                            href="{{ route('head_office.tracking_link.delete', $link->id) }}">
                                            <img src="{{ asset('v2/images/icons/trash.svg') }}" alt=""></a>
                            </div>
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

</div>



@section('styles')



@endsection

@section('scripts')
<script src="{{ asset('/js/alertify.min.js') }}"></script>
<script>
    $(document).ready(function (){
            let table = new DataTable('#session-dataTable', {
                paging: false,
                info: false,
                language: {
                    search: ""
                },
                'columnDefs': [{
                    "select": 'multi',
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': ''
                }],
            });
    });
    function updateLink(id,element)
    {
        var value = $(element).val();
        var _token = "{{csrf_token()}}";
        var data = {
            id : id,
            value : value,
            _token : _token
        }
        var route = "{{route('headoffice.tracking_link.update_link')}}";
        $.post(route,data)
        .then(function(response)
        {
            if(response)
            {
                alertify.success("Link Updated!");
            }
        })
        .catch(function(error){
            console.log(error);
        })
    }
</script>


{{-- @if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@elseif(Session::has('error'))
<script>
    alertify.success("{{ Session::get('error') }}");
</script>
@endif --}}
@endsection

@endsection