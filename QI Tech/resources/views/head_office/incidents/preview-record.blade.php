@extends('layouts.head_office_app')
@section('title', 'Import location incidents')
@section('sidebar')
@include('layouts.company.sidebar')
@endsection
@section('content')
<div id="content">
@include('layouts.error')

<div class="card">

    <div class="card-body">
        <div class="mb-3">

            <div class="float-left">
                <a href="#"><i class=""></i></a>
                <h4 class="text-info font-weight-bold">Bespoke Forms Record - {{$record->createdDate()}}</h4>
            </div>
        </div>
        <div class="table-responsive">
        @foreach($record->form->stages as $s)  
            <div class="card m-10">
                <div class="card-body">
                <h4 class="preview-stage-name center">{{$s->stage_name}}</h4>
                @foreach($s->groups as $g)
                <table class="table table-bordered">
                <thead>
                        <tr>
                            <th>{{$g->group_name}}</th>
                            <th>Submission</th>
                            <!-- <th>Question</th>
                            <th>Value</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($g->questions as $q)
                        <tr>
                            <td>{{$q->question_title}}</td>
                            <td class="">{{$q->displaySubmission($record->id)}}</td>
                        </tr>
                        @endforeach
                        @if(count($g->questions) == 0)
                            <tr><td colspan="2" class="text text-primary">No question are found for this group.</td></tr>
                        @endif
                    </tbody>
                </table>
                @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
</div>
@endsection