@if($action)
<div class="psa_action_with_comments_box comment_section_{{$action->id}}">
            <p>by {{$action->user->name}} <span class="float-right right text-black font-weight-bold">{{$action->created_at->format('d/m/Y h:i a')}}<span></p>
            <p>
            <strong>Do you have defective stock requiring quarantine?</strong> {{$action->have_defective_stock}}
            @if($action->have_defective_stock == 'Yes'), 
                Quantity: {{$action->defective_quantity}} @endif<br>
            
            @if($action->have_defective_stock == 'Yes')
            
                <strong>Has the stock been quarantined?</strong>{{$action->stock_been_quarantined}} 
                @if($action->stock_been_quarantined == 'Yes')
                    , Location: {{$action->stock_been_quarantined_location}} 
                @else
                    , Reason: {{$action->stock_been_quarantined_reason}}
                @endif<br>

                <strong>Has defective stock been returned to the supplier/manufacturer? </strong>{{$action->stock_been_returned}} 
                @if($action->stock_been_returned == 'No')
                    , Reason: {{$action->stock_been_returned_reason}} 
                @endif <br>

                @if($alert->national_alert->patient_level_recall)
                <strong>Have you checked any deliveries/parcels awaiting collection/mds that need recall? </strong>{{$action->recall_awaiting_collection}} 
                <br><strong>Have patients been contacted/notified? </strong><br>{{$action->patients_contacted}} 
                @endif

                @if(!empty($action->addtional_comments))<br><strong>Additional Comments:</strong> {{$action->addtional_comments}} @endif
            @endif
        </p>
        <div class="action_comments">
            <div class="comment_list">
                @if(count($action->comments))
                <hr>
                @foreach($action->comments as $comment)
                <p> 
                by {{$comment->user->name}} <span class="float-right right text-black font-weight-bold">{{$comment->created_at->format('d/m/Y h:i a')}}</span>
                    @if($comment->canEditAndDelete())
                    <span class="right float-right">
                        <a href="#" data-toggle="modal" data-target="#pas_comment_edit_modal_{{$comment->id}}"  class="text-info"><i class="fa fa-edit"></i></a>
                        <a href="{{route('location.patient_safety_alert_delete_comment',['id'=>$comment->id,'_token'=>csrf_token()])}}" title="Delete Comment" class="remove_comment_btn text-danger"><i class="fa fa-trash"></i></a>
                    </span>
                    @endif
                </p>
                <p>{{$comment->comment}}</p><hr>
                @if($comment->canEditAndDelete())
                    <div class="modal fade" id="pas_comment_edit_modal_{{$comment->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                    <form method="post" action="{{route('location.patient_safety_alert_add_comment',$action->id)}}">
                                    @csrf
                                        <input type="hidden" name="alert_id" value="{{$alert->id}}">
                                        <input type="hidden" name="comment_id" value="{{$comment->id}}">
                                        <p>Edit Comment</p>
                                        <textarea spellcheck="true"  name="comment" class="comment form-control" required>{{$comment->comment}}</textarea>
                                        <br>
                                        <button type="submit" class="btn btn-info add_comment">Save Comment</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
                @endif
            </div>
            
        </div>  

        <hr>
    <form method="post" action="{{route('location.patient_safety_alert_add_comment',$action->id)}}">
        @csrf
        <div class="form-group">
            <label>Comment</label>
            <input type="hidden" name="alert_id" value="{{$alert->id}}">
            <textarea spellcheck="true"  name="comment" class="comment form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-info add_comment">Add Comment</button>
    </form>
</div>





@endif
