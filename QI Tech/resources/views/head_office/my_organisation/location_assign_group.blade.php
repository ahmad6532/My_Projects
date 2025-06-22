@extends('layouts.head_office_app')
@section('title', 'Assign a Assign to')
@section('sidebar')
@include('layouts.company.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="card card-qi">
        <div class="card-body">
            @include('layouts.error')
            <h3 class="text-info h3 font-weight-bold inline">Assign to</h3><br>
            <p><strong>Location: </strong> {{$head_office_location->location->name()}}</p>
            <form method="post" action="{{route('head_office.organisation.assign_groups_save',$head_office_location->id)}}">
                @csrf
                <input type="hidden" name="location_id" value="{{$head_office_location->location_id}}">
                <p>Please select a group/tier</p>
                @include('head_office.my_organisation.tree-list',['groups' => $allGroups])
                <input type="submit" name="save" value="Save" class="btn btn-info">
            </form>
        </div>
    </div>
</div>
@endsection