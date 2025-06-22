@extends('layouts.admin_app')

@section('title', 'Create Head Office')
@section('content')
<div class="card" ng-app="myApp">

<div class="card-body">
<div class="mb-3 clearfix">
            <span class="float-left">
                <h4 class="text-info font-weight-bold">Create New Head Office</h4>
            </span>
            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('head_offices.head_office.index') }}" class="btn btn-info" title="Show All Head Office">
                    <span class="fas fa-th-list" aria-hidden="true"></span>
                </a>
            </div>

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


            <form method="POST" action="{{ route('head_offices.head_office.store') }}" accept-charset="UTF-8" id="create_head_office_form" name="create_head_office_form" class="form-horizontal">
            {{ csrf_field() }}
            @include ('admin.head_offices.form', [
                                        'headOffice' => null,
                                      ])

            <hr />
                <div class="col-md-10">

            <div class="form-group">
                <label class=""><b>First Admin Details</b></label><br>
            </div>
                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" class="form-control" maxlength="60" name="position" placeholder="Enter the position or leave it blank" value="{{old('position')}}" title="Position">
                </div>
                </div>
                <div class="col-md-10">

                <!-- Existing -->
            <div class="form-group">
                <label for="user">Assign from existing User</label>
                <select name="user_id" id="user" class="form-control w-100 form-control-lg" ng-model="user_id">
                    <option value="">Select A User</option>
                    <option value="-1">Create A New User</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}} , {{$user->email}}, {{$user->position->name}}</option>
                    @endforeach
                </select>
            </div>
                </div>

            <!-- New User -->
            <div ng-if="user_id == -1">
            @include ('admin.users.form', [
                'user' => null,
              ])
              </div>

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-info" type="submit" value="Add">
                    </div>
                </div>

            </form>
</div>
</div>


</div>
</div>

@endsection


@section('scripts')
<script src="{{asset('js/angular.min.js')}}"></script>
<script>
    var app = angular.module('myApp',[]);
</script>

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            $(document).ready(function() {
                $('select[name="user_id"]').select2();
            });
        });
    </script>
@endsection