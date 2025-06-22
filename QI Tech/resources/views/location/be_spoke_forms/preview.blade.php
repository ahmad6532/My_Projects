@extends('layouts.location_app')
@section('title', 'Be Spoke Forms Preview')
@section('content')
<div id="content">
@if(isset($form))
<form @if (isset($record)) action="{{route('external_link.be_spoke_forms.be_spoke_form.save',$record->id)}}" @else
    action="{{route('external_link.be_spoke_forms.be_spoke_form.save')}}" @endif method="post">
    @csrf
    <input type="hidden" id="form_name" name="form_name" value="{{$form->name}}">
    <input type="hidden" id="form_stages" name="form_stages" value="{{count($form->stages)}}">
    <input type="hidden" id="form_id" name="form_id" value="{{$form->id}}">

    @include('layouts.error')
    <?php $formToFillConditions = request()->query('condtions');
      if(!empty($formToFillConditions)) { $formToFillConditions =  explode('-',$formToFillConditions);}
      $currentCondition = \App\Models\Forms\ActionCondition::formToSubmit(request()->query('current_condition'));
      //dd(request()->query('current_condition'));
?>
    @if(isset($formToFillConditions) && count($formToFillConditions))
    @foreach($formToFillConditions as $c)
    <input type="hidden" id="to_fill" name="to_fill[]" value="{{$c}}">
    @endforeach
    @endif

    @if($currentCondition)
    <div class="alert alert-success">{{$currentCondition['message']}}</div>
    @endif
    <div class="" id="form">
        <div class="s2">
            <div class="mb-3">
                <div class="btn-group btn-group-sm float-right" role="group">
                    <a href="{{route('be_spoke_forms.be_spoke_form.index')}}" class="btn btn-info next-step"
                        title=" Be Spoke Form list">
                        <span class="fas fa-list" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
            <div class="headingWithSearch">
        
                <div class="heading-center">
                    {{$form->name}}
                </div>
            </div>
            <div class="col-md-12">
                <div class="px-0 pt-4 pb-0 mt-3 mb-3">

                    <ul id="progressbar" class="m-auto">
                        @foreach($form->stages as $key=>$stage)
                        <li class="progress-bar-list @if( $key == 0 ) active @endif" data-stage="{{($key+1)}}"
                            id="step{{($key+1)}}"><strong>{{$stage->stage_name}}</strong></li>
                        @endforeach
                    </ul>
                    <div class="progress">
                        <div class="progress-bar" style="width: 33%"></div>
                    </div>
                    <br>

                </div>
                <span class="stage_name_top">Stage</span>
            </div>
            <script>
                var conditions = [];
            let condition;
            </script>
            @foreach($form->stages as $key=>$stage)
            <div class="stages stage_{{$stage->id}} stage_data_{{$key+1}}" @if($key !=0) style="display:none"
                @endif>
                    @foreach($stage->groups as $group)
                    <div class=" group group_{{$group->id}}">
                        <div class="profile-page-contents hide-placeholder-parent" style="max-width:50%">
                            <div class="inputSection activeColor">{{$group->group_name}}</div>
                            
                                @foreach($group->questions as $question)

                                @include('location.be_spoke_forms.question')
                                @endforeach
                            </div>
                    </div>
                    @endforeach
            </div>
            @endforeach
            <div class="center m-t-10">
                <button class="previous-step btn btn-info stagePrevButton" style="float:none">Previous</button>
                <button class="next-step btn btn-info stageNextButton" style="float:none">Next</button>
                <input type="submit" style="display:none" class="btn btn-info formSubmitButton" name="submit"
                    value="Submit">
            </div>

        </div>
    </div>
</form>
</div>
@endif
@endsection
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_assets/css/progress-step.css')}}">
<link rel="stylesheet" href="{{asset('/easyautocomplete/easy-autocomplete.min.css')}}">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('/easyautocomplete/jquery.easy-autocomplete.min.js')}}"></script>
@include('location.be_spoke_forms.script')
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzxvYQxd1yHydcBFIRNOLQjcbQtThH6rI&libraries=places&callback=initPlaces">
</script>
@endsection