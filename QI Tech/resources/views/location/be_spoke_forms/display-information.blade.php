@extends('layouts.location_app')
@section('title', 'Form Information')

@section('content')
    <div class="">
        @if($rootCauseAnalysis['five_whys'] || $rootCauseAnalysis['fish_bone'])
            <div class="card">
                <div class="card-body row">
                    <p>Based on your submission you have to complete root cause analysis.</p>
                    @if($rootCauseAnalysis['five_whys'])
                    @endif
                    @if($rootCauseAnalysis['fish_bone'])
                    @endif
                </div>
        </div>
        @endif
        <div class="col-md-12">
            <div class="card">
            <div class="card-body">
            <div class="">
                    @if(count($formsToFill))
                    <div class="">You have to fill {{count($formsToFill)}} Form(s) based on your submission</div>
                    <ol>
                    @foreach($formsToFill as $f)
                        <?php 
                            $formID = \App\Models\Forms\ActionCondition::formToSubmit($f);
                            $form = \App\Models\Forms\Form::find($formID['form_id']); 
                        ?>
                        <li>{{$form->name}}</li>
                    @endforeach
                    </ol>
                    <?php 
                        $firstformCondition = array_shift($formsToFill);
                        $firstform = \App\Models\Forms\ActionCondition::formToSubmit($firstformCondition); 
                        $conditionsToFill = implode(',',$formsToFill);
                    ?>
                        <a href="{{route('be_spoke_forms.be_spoke_form.preview',[ $firstform['form_id'],'condtions' => $conditionsToFill,'current_condition' =>$firstformCondition  ]) }}" class="btn btn-success">Continue to Next Form <i class="fa fa-arrow-right"></i></a>
                    @else
                        <a href="{{route('be_spoke_forms.be_spoke_form.index')}}" class="btn btn-success">Finish</a>
                    @endif
                </div>
            </div>
        </div>
        </div>
        <div class="col-md-12">
        @if($information && !empty($information))
            <div class="card">
                <div class="card-body">
                    {!! $information !!}
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection