@extends('layouts.admin_app')
@section('title', 'Assign to Head Offiec')
@section('content')
    <div class="card mb-2">
        <div class="card-body">
            <div class="mb-3 clearfix">
            <span class="float-left">
                <h4 class="text-info font-weight-bold">Assign this location to a Head Office</h4>
                @if($location->head_office())<p><b>Current Head Office:</b> {{$location->head_office()->name()}}</p>@endif
            </span>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif


                    <form method="POST" action="{{route('admins.location.save_assign_ho',$location->id)}}" accept-charset="UTF-8" id="assign_super_admin_form" name="assign_super_admin_form" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="user">Please select a head office</label>
                            <select name="head_office_id" id="user" class="form-control w-50 head_office_id">
                                <option value="">Select a head office</option>
                                @foreach($headoffices as $office)
                                    <option value="{{$office->id}}" @if($location->head_office() && $location->head_office()->id == $office->id) selected @endif>{{$office->name()}}</option>
                                @endforeach
                            </select>
                            <p class="info mt-1"><i class="fa fa-info text-info"></i> If the location is already assigned to a head office, this will change the current head office of that location.</p>
                            <p class="info">Note: Location data will not be shown to "Previous" head office upon change.</p>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <input class="btn btn-info" type="submit" value="Assign">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
        $(document).ready(function() {
            $('.head_office_id').select2();
        });
        });
    </script>

@endsection


