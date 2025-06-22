@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())

@section('topbar_nav_items')
<li> <a class="@if(request()->route()->getName() == 'case_manager.view') active @endif"
        href="{{route('case_manager.view',$case->id)}}"><span>Case Notes</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_report') active @endif"
        href="{{route('case_manager.view_report',$case->id)}}"><span>View Report</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_root_cause_analysis') active @endif"
        href="{{route('case_manager.view_root_cause_analysis',$case->id)}}"><span>Root Cause Analysis</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_sharing') active @endif"
        href="{{route('case_manager.view_sharing',$case->id)}}"><span>Sharing</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_intelligence') active @endif"
        href="{{route('case_manager.view_intelligence',$case->id)}}"><span>Intelligence</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_drafts') active @endif"
        href="{{route('case_manager.view_drafts',$case->id)}}"><span>Drafts</span></a> </li>
@endsection

@section('content')
@include('layouts.error')
@section('styles')
<link href="{{asset('admin_assets/css/five-why-model.css')}}" rel="stylesheet" />
@endsection
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <div class="">
                <h4 class="text-info font-weight-bold">Five Why's</h4>
            </div>
        </div>
        
        <!-- 5 Whys design model -->
            @csrf
        <div class="five-why-container">
            <div class="problem">
                
               
                <p>
                    {{$root_cause_analysis->name}}
                </p>
            </div>
            <div class="five_whys">
                <!-- Q 1 -->
                @foreach ($root_cause_analysis->five_whys_questions as $key => $question)
                    
                
                <div class="five-why counter_{{$key}}" >
                    <div class="element-shape why">
                        <span class="before"></span>
                        <p>Why?
                        </p>
                        <span class="after"></span>
                    </div>
                    <div class="answer element-shape">
                        <span class="before"></span>
                        <p>
                           
                                <span>{{optional($question->answers)->answer}}</span>
                            
                        </p>
                        <span class="after"></span>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>


        <!-- End of 5 whys -->


        
           
        @if($root_cause_analysis->note)
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="">Note</label>
                    <textarea spellcheck="true"  readonly class="form-control">
                        {{$root_cause_analysis->note}}
                    </textarea>
                </div>
            </div>

        </div>
        @endif
    </div>

</div>
@section('scripts')
<script>
    jQuery(document).on('click','.organisation-tag, .btn-level ',function(e){
        // Prevent child clicks hidding the modal
        if (e.target !== this){
            //return;
        }
        jQuery('.action-bar').not(jQuery(this).find('.action-bar')).removeClass('show');
        jQuery(this).find('.action-bar').first().toggleClass('show');

    });

</script>
@endsection
@endsection