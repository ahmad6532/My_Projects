@extends('layouts.head_office_app')
@section('title', 'To be removed links')
@section('content')
<div class="card card-qi">
    <div class="card-body">
        @include('layouts.error')
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            Date to be removed
                        </th>
                        <th>
                            Incident ID
                        </th>
                        <th>
                            Case Status
                        </th>
                        <th>
                            Link
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($to_be_removed_links as $link)
                    <tr>
                        <td>
                            {{$link->date_to_be_removed->format(config('app.dateFormat'))}}
                        </td>
                        <td>
                            {{$link->link_case->id}}
                        </td>
                        <td>
                            @if(!$link->link_case->case_closed)
                            Open
                            @else
                            Closed
                            @endif
                        </td>
                        <td>
                            {{$link->link}}
                        </td>
                        <td>
                            <a href="{{route('links.link.delete',['task_id'=>$link->id,'_token'=>csrf_token()])}}" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                    @endforeach
                <tbody>
            </table>
            <br><br><br>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')
<script src="{{asset('js/alertify.min.js')}}"></script>
@endsection