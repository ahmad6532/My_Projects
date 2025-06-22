@foreach($records as $date => $records)
<div class="line line-date date_{{date('Y_m_d',strtotime($date))}}">
    <div class="timeline-label">@if($date == date('Y-m-d') ) TODAY @else {{date('D jS F
        Y',strtotime($date))}} @endif</div>
</div>
@foreach($records as $key=>$record)
<div

    class="line nearmiss_hidden @if( $counter%2  == 0) left-record @else  right-record @endif nearmiss_record nearmiss_{{$record->id}} nearmiss_status_{{strtolower(str_replace(' ','_',$record->status))}}">
    <div class="date time">{{$record->created_at->format(config('app.dateFormat'))}}</div>
    <div class="content-timeline">
        <div class="actions">
            @if($record->created_at > Carbon\Carbon::now()->sub(config('app.incident_edit_capability_time_out')))
            <a href="{{route('be_spoke_forms.be_spoke_form.preview', [$form->id,$record->id])}}" target="_blank"
                title="Edit" class="text-info"><i class="fa fa-edit"></i></a><br>

            @endif
            {{-- <a href="javascript:void(0)" data-id="{{$record->id}}" target="_blank"
                title="Updates" class="text-info see_update_btn"><i class="fa fa-wrench"></i></a><br> --}}

        </div>

        <h2 class="timeline_category_title">
            <span @if( $counter%2  == 0) style="float: left" @else  style="float: right" @endif>
                {{$record->form->name}}
            </span>
            <img class="timeline_icon icon" src="{{asset('images/prescription_timeline.png')}}" width="32">
            <span
                class="timeline_what_was_error_title {{strtolower(str_replace(' ','_',$record->location->trading_name))}}_title">{{$record->location->trading_name}}</span>
        </h2>

        <div class="details details_{{$record->id}}" style="display:none">
           
            @foreach($record->data as $data)
            @if($data->question)
            <p>
                <span class="detail-title"> {{$data->question->question_name}}: </span>
                {{$data->question_value}}
            </p>
            <br>
            @endif
            @endforeach
            @if($record->recorded_case && (count($record->recorded_case->root_cause_analysis) > 0))
                        @foreach ($record->recorded_case->root_cause_analysis as $request)
                        @if(!$request->status)
                        <a target="_blank" href="{{route('be_spoke_forms.be_spoke_form.root_cause_analysis_request',[$request->id,$request->type])}}">{{$request->root_cause_analysis_type}}</a>
                        @endif
                        @endforeach
            @endif
            @if(count($record->updates))
            <div class="">
                {{-- <div class="card-header">
                    Updates
                </div> --}}
                <div class="">
                    @foreach ($record->updates as $key => $update)
                        <div data-comment="{{$update->id}}" class="cm_comment card @if( $key%2 == 0  ) cm_comment_grey @endif">
                            <div class="cm_comment_author_date">
                                <b>{{optional($update->user)->name}}</b> Updated {{$update->created_at->diffForHumans()}}
                                <span class="float-right">{{$update->created_at->format(config('app.dateFormat'))}} {{$update->created_at->format(config('app.timeFormat'))}}</span>
                            </div>
                            <div class="cm_comment_comment">
                                {!! ($update->update) !!}
                            </div>
                            <div class="cm_comment_attachments mt-1">
                                <div class="cm_comment_people">               
                                    @foreach($update->documents as $doc)
                                        <span data-toggle="tooltip" title="" class="badge badge-primary badge-user">
                                            <a style="color: white" class="relative @if($doc->type == 'image') cm_image_link @endif " href="{{route('head_office.be_spoke_forms.record.update.view.attachment', $doc->document->unique_id).$doc->document->extension()}}" target="_blank">@if($doc->type == 'image') <i class="fa fa-image"></i> @else<i class="fa fa-link"></i> @endif {{$doc->document->original_file_name()}}
                                            @if($doc->type == 'image')
                                                <div class="cm_image_hover">
                                                    <div class="card shadow">
                                                        <div class="card-body">
                                                            <img class="image-responsive" width="300" src="{{route('head_office.be_spoke_forms.record.update.view.attachment', $doc->document->unique_id).$doc->document->extension()}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            </a>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="card update_card_{{$record->id}}" style="display: none;">
                <div class="card-header">
                    New Update
                </div>
                <div class="card-body">
                    <form class="cm_task_form" method="post" action="{{route('be_spoke_forms.be_spoke_form.update',$record->id)}}">
                        @csrf
                        <div class="form-group">
                            <label>Data</label>
                            <input type="text" name="update" class="form-control" required>
                        </div>
                        <div class="uploaded_files mt-2 mb-2">
                        </div>
                        {{-- <div class="uploaded_files mt-2 mb-2">
                            @foreach($record->documents as $doc)
                            <li>
                                <input type='hidden' name='documents[]' class='file document'
                                    value='{{$doc->document->unique_id}}'>
                                <span class="fa fa-file"></span>&nbsp;{{$doc->document->original_file_name()}}
                                <a href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                    target="_blank" title='Preview' class="preview_btn"> <span
                                        class="fa fa-eye"></span></a>
                                <a href="#" title='Delete File' class="remove_btn"> <span
                                        class="fa fa-times"></span></a>
                            </li>
                            @endforeach
                        </div> --}}
                        <h6 class="text-info">Select documents/images to upload</h6>
                        <div class="cm_upload_box_with_model center">
                            <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                        </div>
                        <input type="file" name="file" multiple value="" class="form-control commentMultipleFiles">
                        <button type="submit" class="btn btn-info">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <p class="see_details">
            <a href="#" data-id="{{$record->id}}" class="text-info see_details_btn see_details_btn_{{$record->id}} ">See
                Details</a>
            <a href="#" style="display:none" data-id="{{$record->id}}"
                class="text-info show_less_btn show_less_btn_{{$record->id}} ">Show Less</a>
        </p>

    </div>
</div>
<!-- Modal -->
<div class="modal modal-md fade" id="delete_model_{{$record->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="text-danger">Why are you deleting this?</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-2">
                    <form method="post" action="{{route('location.near_miss.delete', $record->id)}}">
                        @csrf
                        <div class="form-group">
                            <label>Reason for deleting this near misss?</label>
                            <input type="text" name="delete_reason" class="form-control" required value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info">Delete</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $counter++ ?>
@endforeach
@endforeach

<input type="hidden" value="{{route('location.document.uploadHashed')}}" id="route_document">
<input type="hidden" value="{{route('location.document.removedHashed')}}" id="route_document_removedHashed">
