@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())

@section('sub-header')
@include('head_office.case_manager.notes.sub-header')
@endsection

@section('content')
<div id="content">
@include('layouts.error')
<div class="row mt-4 content_widthout_sidebar">
    <div class="comments col-6">
        <div class="card card-qi">
            <div class="card-body pt-0">
                @if($case->case_closed)
                <nav class="nav nav-tabs nav-h-bordered">
                    <a href="#" class="active"><span class="item_with_border">Case Closed</span></a>
                </nav>

                @endif

                <form method="get" class="form print-display-none w-85 inline-block">
                    <div class="input-group form-group mb-3 mt-0">
                        <input type="text" class="form-control search-nearmiss" name="search"
                            @if(request()->query('search')) value="{{request()->query('search')}}" @endif>
                        <button type="submit" class="btn btn-info search_button"><i class="fa fa-search"></i></button>
                    </div>
                </form>

                <button class="primary-btn dropdown-toggle inline-block" type="button" id="dropdownMenuButton"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
                    @if(!$case->root_cause_analysis->where('type','fish_bone')->first() || !$case->root_cause_analysis->where('type','five_whys')->first())
                <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                    @if(!$case->root_cause_analysis->where('type','fish_bone')->first())
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#request_fish_bone"
                        @if($case->case_closed) style="display:none" @endif onclick="resetFrom()" title="Close
                        Case">Fish Bone</a>
                    @endif

                    @if(!$case->root_cause_analysis->where('type','five_whys')->first())
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#request_five_whys"
                        @if($case->case_closed) style="display:none" @endif onclick="resetFrom()" title="Close
                        Case">Five Whys</a>
                    @endif
                </div>
                @endif
                <div class="cm_comments_wrapper relative scrollbar_custom_green">
                    
                    <div class="nav nav-tabs" style="display:block;" id="myTab" role="tablist">
                        @foreach ($case->root_cause_analysis as $key=> $root_cause_analysis)
                        <div class="pointer @if($key == 0) active @endif" id="item_{{$root_cause_analysis->id}}_tab" data-bs-toggle="tab" data-bs-target="#item_{{$root_cause_analysis->id}}" role="tab" aria-controls="item_{{$root_cause_analysis->id}}" aria-selected="true">
                            <div class="case_1 relative">
                                <div class="card border-left-secondary shadow w-100">
                                    <div class="card-body">
                                        <div class="row align-items-center ">
                                            <div class="col-sm-4 ">
                                                {{-- <div
                                                    class="cm_case_number font-weight-bold text-black text-uppercase"
                                                    title="Case Number">
                                                    {{$root_cause_analysis->root_cause_analysis_type}}
                                                </div> --}}
                                                <p>
                                                    {{$root_cause_analysis->root_cause_analysis_type}}
                                                </p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p>
                                                    Time of request :
                                                    {{$root_cause_analysis->created_at->format(config('app.dateFormat'))}}
                                                </p>
                                            </div>
                                            <div class="col-sm-2 ">
                                                @if($root_cause_analysis->status == 1)
                                                <a href="" class="float-right" id="dropdownMenuButton"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                        class="fa fa-angle-right fa-2x text-gray-300"></i></a>
                                                <div class="dropdown-menu animated--fade-in"
                                                    aria-labelledby="dropdownMenuButton">
                                                    <a href="#" class="dropdown-item">Print Analysis</a>
                                                    <a href="#" class="dropdown-item" title="Close Case">Download
                                                        Analysis</a>
                                                    <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#request_again_{{$root_cause_analysis->id}}" @if($case->case_closed)
                                                        style="display:none" @endif title="Request again">Request
                                                        New</a>
                                                </div>
                                                @elseif($root_cause_analysis->status == 2)
                                                Requested New one
                                                @else
                                                Requested
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="request_again_{{$root_cause_analysis->id}}"  @if(isset($remove_backdrop)) data-backdrop="false" @endif tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title text-info w-100">
                                            Request Again
                                        </h4>
                                        <button type="button" class="btn-close float-right"
                                            data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="{{route('head_office.request_new_analysis.request_new',[$case->id,$root_cause_analysis->id])}}" >
                                            @csrf
                                            <div class="new_link_wrapper">
                                                <div class="form-group">
                                                    <label>Note</label>
                                                    <input type="text" name="note"
                                                        class="form-control" required />
                                                </div>

                                                <button type="submit" class="btn btn-info btn-submit inline-block mb-0">
                                                    <i class="fa fa-location-arrow"></i>
                                                </button>
                                            </div>
                                        </form> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="comments col-6 ">
        <div class="tab-content" id="myTabContent">
            @foreach ($case->root_cause_analysis as $key => $root_cause_analysis)
            <div class="tab-pane fade show @if($key == 0) active @endif" id="item_{{$root_cause_analysis->id}}" role="tabpanel" aria-labelledby="item_{{$root_cause_analysis->id}}-tab">
                <div class="card card-qi">
                    <div class="card-body">
                        <div class="cm_case_overview active show" id="cm_case_overview">
                            <div>Status:  @if($root_cause_analysis->status == 1)<b class="text-success">Completed</b> @elseif($root_cause_analysis->status == 2)<b class="text-warning">Requested Again</b> @else <b class="text-warning">Requested</b> @endif
                                @if(!$root_cause_analysis->status)
                                <a href="#" class="btn btn-info" style="float: right" data-bs-toggle="modal" @if($root_cause_analysis->type == 'fish_bone') data-bs-target="#edit_fish_bone_{{$root_cause_analysis->id}}" @else data-bs-target="#five_whys_bone_{{$root_cause_analysis->id}}" @endif  @if($case->case_closed) style="display:none" @endif title="Edit request {{$root_cause_analysis->type}}">Edit</a>
                                @endif
                            </div>
                            <br>
                            <div><b>Problem</b></div>
                            <div>
                                {{$root_cause_analysis->name}}
                                <br>
                                Editable : 
                                @if($root_cause_analysis->is_editable)
                                Yes
                                @else No @endif
                            </div>
                            <br>
                            @if($root_cause_analysis->type == 'fish_bone')
                            <div><b>Fish Bones</b></div>
                            @endif
                            @if($root_cause_analysis->type == 'fish_bone')
                            @foreach($root_cause_analysis->fish_bone_questions as $question)
                                <div>{{$question->question}}</div>
                            @endforeach

                            @else
                            @foreach ($root_cause_analysis->five_whys_questions as $question)
                                <div>{{$question->question}}</div>
                            @endforeach
                            @endif
                                <br>
                            @if($root_cause_analysis->note)
                            <div>
                                <b>
                                    Comment
                                </b>
                                <p>
                                    {{$root_cause_analysis->note}}
                                </p>
                            </div>
                            @endif

                            @if ($root_cause_analysis->status)
                            <div>
                                <b>
                                {{$root_cause_analysis->root_cause_analysis_type}}
                                </b>
                           
                                <b>Completed By :</b> {{$root_cause_analysis->user->name}}
                            </div>
                            <div>
                                <b>Submitted Time :</b> {{$root_cause_analysis->updated_at->format(config('app.dateFormat'))}} {{$root_cause_analysis->updated_at->format('H:i:s')}}
                            </div>
                            <div>
                               
                                <a href="{{route('view_root_cause_analysis_results',[$case->id,$root_cause_analysis->id])}}" target="_blank" class="btn btn-info">View</a>
                                
                            </div>
                            @endif
                            

                        </div>
                    </div>
                </div>
            </div>
            @if($root_cause_analysis->type == 'fish_bone')
            <div class="modal fade" id="edit_fish_bone_{{$root_cause_analysis->id}}" tabindex="-1" @if(isset($remove_backdrop)) data-backdrop="false" @endif
                role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title text-info w-100">
                                Edit Fish Bone
                            </h4>
                            <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="{{route('route_cause_analysis.request.fish_bone_edit',[$case->id,$root_cause_analysis->id])}}">
                                <div class="new_link_wrapper">
                                    @csrf
                                    <div class="form-group">
                                        <label>Problem</label>
                                        <input type="text" name="problem" value="{{$root_cause_analysis->name}}" class="form-control" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>Problem Eidtable?</label>
                                        <input type="checkbox" class="form-control" @if ($root_cause_analysis->is_editable) value="1" checked @endif name="id_editable">
                                    </div>
                                    
                                    @foreach ($root_cause_analysis->fish_bone_questions as $question)
                                    <div class="form-group">
                                        <label>Question {{$loop->iteration}}</label>
                                        <input type="text" name="question_{{$question->id}}" class="form-control" value="{{$question->question}}" required="">
                                        
                                        <input type="hidden" name="question_id_{{$question->id}}" value="{{$question->id}}" class="form-control" required="">
                                    </div>
                                    @endforeach
                                    
                                    {{-- <div class="custom" style="display: none">
                                    </div> --}}

                                    <button type="submit" name="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                                            class="fa fa-location-arrow"></i> </button>
                                    <button style="display: none" id="custom_button" type="button"
                                        class="btn btn-info btn-submit inline-block mb-0" title="Add new question"><i
                                            class="fa fa-plus"></i> </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="modal fade" id="edit_five_whys_{{$root_cause_analysis->id}}" tabindex="-1" @if(isset($remove_backdrop)) data-backdrop="false" @endif role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title text-info w-100">
                                {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                                --}}
                                Edit Five Whys
                            </h4>
                            <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="{{route('route_cause_analysis.request.five_whys_edit',[$case->id,$root_cause_analysis->id])}}" >
                                <div class="new_link_wrapper">
                                    @csrf

                                    <div class="form-group">
                                        <label>Problem</label>
                                        <input type="text" name="problem" value="{{$root_cause_analysis->name}}" class="form-control" required="">
                                    </div>

                                    <div class="form-group">
                                        <label>Problem Eidtable?</label>
                                        <input type="checkbox" class="form-control" @if ($root_cause_analysis->is_editable) value="1" checked @endif name="id_editable">
                                    </div>
                                    
                                    @foreach ($root_cause_analysis->five_whys_questions as $question)
                                    <div class="form-group">
                                        <label>Question {{$loop->iteration}}</label>
                                        <input type="text" name="question_{{$question->id}}" class="form-control" value="{{$question->question}}" required="">
                                        
                                        <input type="hidden" name="question_id_{{$question->id}}" value="{{$question->id}}" class="form-control" required="">
                                    </div>
                                    @endforeach

                                    <br />

                                    <button type="submit" name="submit" title="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                                            class="fa fa-location-arrow"></i> </button>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="request_fish_bone" tabindex="-1" @if(isset($remove_backdrop)) data-backdrop="false" @endif
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Fish Bone
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('route_cause_analysis.request.fish_bone',$case->id)}}">
                    <div class="new_link_wrapper">
                        @csrf
                        <div class="form-group">
                            <label>Problem</label>
                            <input type="text" name="problem" class="form-control" required="">
                        </div>
                        
                        <div class="form-group">
                            <div class="form-inline">
                                <label>Problem Eidtable?</label>
                                <input type="checkbox" class="" value="default" name="problem_editable">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-inline">
                            <label>Default Questions?</label>
                            <input type="radio" class="custom_question" checked value="default"
                                name="custom_question">
                        </div>
                    </div>
                        <div class="form-group">
                            <div class="form-inline">
                            <label>Custom Questions?</label>
                            <input type="radio" class="custom_question" value="custom"
                                name="custom_question">
                        </div>
                    </div>
                        <div id="custom" style="display: none">
                        </div>
                        <br />

                        <button type="submit" name="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                                class="fa fa-location-arrow"></i> </button>
                        <button style="display: none" id="custom_button" type="button"
                            class="btn btn-info btn-submit inline-block mb-0" title="Add new question"><i
                                class="fa fa-plus"></i> </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="request_five_whys" tabindex="-1" @if(isset($remove_backdrop)) data-backdrop="false" @endif
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Five Whys
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('route_cause_analysis.request.five_whys',$case->id)}}" >
                    <div class="new_link_wrapper">
                        @csrf
                        <div class="form-group">
                            <label>Problem</label>
                            <input type="text" name="problem" class="form-control" required="">
                        </div>
                        {{-- <div class="form-group">
                            <label>Default Questions?</label>
                            <input type="radio" class="form-control five_why_custom_question" checked value="default"
                                name="custom_question">
                        </div>
                        <div class="form-group">
                            <label>Custom Questions?</label>
                            <input type="radio" class="form-control five_why_custom_question" value="custom"
                                name="custom_question">
                        </div>
                        <div id="five_why_custom" style="display: none">
                        </div>
                        <br /> --}}

                        <button type="submit" name="submit" title="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                                class="fa fa-location-arrow"></i> </button>
                        {{-- <button style="display: none" id="five_why_custom_button" type="button"
                            class="btn btn-info btn-submit inline-block mb-0" title="Add new question"><i
                                class="fa fa-plus"></i> </button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection


@section('scripts')
<script>
    $('.custom_question').on('change',function(){
        $(".custome_question").is(':checked')
        {
            if($(this).val() === 'custom')
            {
                $('#custom').show();
                $("#custom_button").show();
                $("#custom").append('<div class="form-group"><label>Question 1</label><input type="text" name="questions[]" multiple="multiple" class="form-control" required=""></div>');
            }
            else
            {
                $('#custom').empty();
                $("#custom_button").hide()
                $('#custom').hide();
            }
        }
    });
    $("#custom_button").on('click',function(){
        if($('#custom').find('.form-group'))
            var len = 1 + parseInt($('#custom').find('.form-group').length);
        else
            var len = 1;
        if(len > 14)
        {
            alert('Questions can be more then 14');
        }
        else
            $("#custom").append('<div class="form-group"><label>Question '+ len +'</label><input type="text" name="questions[]" multiple="multiple" class="form-control" required=""></div>');
    });

    $('.five_why_custom_question').on('change',function(){
        $(".five_why_custome_question").is(':checked')
        {
            if($(this).val() === 'custom')
            {
                $('#five_why_custom').show();
                $("#five_why_custom_button").show();
                $("#five_why_custom").append('<div class="form-group"><label>Question 1</label><input type="text" name="questions[]" multiple="multiple" class="form-control" required=""></div>');
            }
            else
            {
                $('#five_why_custom').empty();
                $("#five_why_custom_button").hide()
                $('#five_why_custom').hide();
            }
        }
    });
    $("#five_why_custom_button").on('click',function(){
        if($('#five_why_custom').find('.form-group'))
            var len = 1 + parseInt($('#five_why_custom').find('.form-group').length);
        else
            var len = 1;
        if(len > 14)
        {
            alert('Questions can be more then 14');
        }
        else
            $("#five_why_custom").append('<div class="form-group"><label>Question '+ len +'</label><input type="text" name="questions[]" multiple="multiple" class="form-control" required=""></div>');
    });
</script>
<script src="{{asset('tribute/tribute.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('admin_assets/speech-to-text.js')}}"></script>
@endsection