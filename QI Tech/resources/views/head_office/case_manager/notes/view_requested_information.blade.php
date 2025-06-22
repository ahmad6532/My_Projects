@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())

@section('sub-header')
@include('head_office.case_manager.notes.sub-header')
@endsection

@section('content')
<div id="content">
@include('layouts.error')
<div class="card card-qi content_widthout_sidebar">
    <div class="card-body">
        <h3 class="text-info h3 font-weight-bold">Statement</h3>
        <h4>Hello</h4>
        <p>We need your account of events regarding an incident that was reported</p>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Requested From</th>
                            <th>From</th>
                            <th>Days Since</th>
                            <th>Status</th>
                            <th>Note</th>
                        </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td>{{$requested_information->created_at->format(config('app.dateFormat'))}}</td>
                                    <td>{{$requested_information->user->name}}</td>

                                    <td>{{$requested_information->case->case_head_office->company_name}}</td>
                                    <td>{{$requested_information->created_at->diff(\Carbon\Carbon::now())->days}}</td>
                                    <td>
                                        @if($requested_information->status == 0)
                                        Waiting
                                        @else
                                        Submited
                                        @endif
                                    </td>
                                    
                                    <td>{{$requested_information->note}}</td>
                                </tr>
                           
                        
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body intelligence-container">
                        @foreach($requested_information->case->link_case_with_form->form->stages as $key=>$stage)
                    
                            <div class="card stages stage_{{$stage->id}} stage_data_{{$key+1}}" >
                                
                                <div class="card-body">
                                    <h5>{{$stage->stage_name}}</h5>
                                    @foreach($stage->groups as $gk => $group)
                                    <div class=" group group_{{$group->id}} s_{{$key +1}}">
                                        <div class="">
                                            <h5 class="form-group-name qg_{{$key.'-' .$gk}}_name">{{$group->group_name}}</h5>
                                            <div class="row">
                                                @foreach($group->questions as $question)
                                                @php $value = $requested_information->case->link_case_with_form->data->where('question_id',$question->id)->first() ; @endphp
                                                @if($value && $value->radact)
                                                <div class="col-md-6 qg_{{$key.'-' .$gk}} ">
                                                    <div class="form-group question_{{$question->id}}">
                                                        <label for="question_{{$question->id}}">{{$question->question_title}}</label>
                                                       
                                                        <input type="text" readonly class="form-control" value="{{$value->question_value}}" title="" />
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        {{-- @foreach($case->link_case_with_form->data as $data)
                        @if($data->question)
                        <p>
                            <span class="detail-title"> {{$data->question->question_name}}: </span>
                            {{$data->question_value}}
                        </p>
                        <br>
                        @endif
                        @endforeach --}}
                    </div>
                </div>
            </div>
        </div>



        <div class="table-responsive">
            @if ($requested_information->status)
           
            @foreach ($requested_information->questions as $question)
            <b style="display: block; margin-bottom: 10px;">{{$question->question}}</b>
            <p style="">{{$question->answer}}</p>

           
            @endforeach
            @foreach($requested_information->documents as $doc)
            <li>
                <input type='hidden' name='documents[]' class='file document'
                    value='{{$doc->document->document->unique_id}}'>
                <span class="fa fa-file"></span>&nbsp;{{$doc->document->document->original_file_name()}}
                <a href="{{route('user.view.attachment', $doc->document->document->unique_id).$doc->document->document->extension()}}"
                    target="_blank" title='Preview' class="preview_btn"> <span class="fa fa-eye"></span></a>
                <a href="#" title='Delete File' class="remove_btn"> <span class="fa fa-times"></span></a>
            </li>
            @endforeach
            @endif
        </div>
    </div>
</div>
</div>



@section('styles')

<link rel="stylesheet" href="{{asset('tribute/tribute.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('scripts')
<script src="{{asset('admin_assets/js/view_case.js')}}"></script>
<script src="{{asset('admin_assets/js/form-template.js')}}"></script>
<script>
    $(document).on( "click", ".delete_share_case", function(e) {
        e.preventDefault();
        let href= $(this).attr('href');
        
        let msg = $(this).data('msg');
        alertify.defaults.glossary.title = 'Alert!';
        alertify.confirm("Are you sure?", msg,
        function(){
            window.location.href= href;
        },function(i){
            console.log(i);
        });
    });

    for(let j=0;j<10;j++){
        if($('.s_'+(j+1) +' input').length == 0)
        {
            $('.stage_data_'+(j+1)).hide();
        }
        for(let i=0;i<10;i++)
        {
            
            let cl = '.qg_'+i+"-" +j;
            if($(cl).length == 0)
            {
                $(cl+'_name').hide();
            }
        }
    }
</script>
@endsection

@endsection