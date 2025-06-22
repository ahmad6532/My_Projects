<form method="post" action="{{route('head_office.case.share_case_edit_duration',[$case->id,$share->id])}}" class="cm_task_form">
    @csrf

                     @php
                         use Carbon\Carbon;
                    @endphp
    <div class="modal fade file_upload_model" id="edit_duration_{{$share->id}}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
           
            <div class="modal-content">
            
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                    Edit Duration
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                @if($share->duration_of_access > Carbon::now())
                <div class="cm_comment_comment" style="margin-left: 25px">
                    <b style="color: black; align:center">Access Revoked</b>
                    <br>
                    @php
                        $duration = $share->duration_of_access->diffInHours();
                        $formattedDate = $share->duration_of_access->format('D d/m/Y');
                        $formattedTime = $share->duration_of_access->format('h:i A');
                        @endphp

                        {!! 
                            "In <strong>{$duration} hours</strong> [{$formattedDate} at {$formattedTime}]" 
                        !!}
                      </div>
                    @else
                <div class="cm_comment_comment">
                    <b style="color: red">Expired</b> on {!! $share->duration_of_access->format(config('app.dateFormat')) !!} {!! $share->duration_of_access->format(config('app.timeFormat')) !!} ({!! $share->duration_of_access->diffForHumans() !!})
                </div>
                @endif
                <div class="modal-body">
                    <div class="row dead_line_date">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="duration_date">Select Date</label>
                                <input type="date" name="duration_date" id="duration_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
