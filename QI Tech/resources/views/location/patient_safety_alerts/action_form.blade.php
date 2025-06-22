@csrf
<input type="hidden" name="action_type" @if($alert->action()) value="{{$alert->action()->action_type}}" @endif class="action_type">
<input type="hidden" name="alert_type" value="{{$alert->national_alert->type}}" class="alert_type">
@if($alert->action())<input type="hidden" name="action_id" value="{{$alert->action()->id}}" class="action_id">@endif
<div class="mt-2 have_you_shared_this_alert">
    <div class="center form-buttons psa_action_header text-danger">
        <input type="hidden" name="shared_this_alert"  @if($alert->action()) value="{{$alert->action()->shared_this_alert}}" @endif class="shared_this_alert" data-targets=".btn_shared">
        <i class="fa fa-users"></i><br>
        <span class="">Have you shared this alert?</span><br>  
        <button type="button" data-target=".shared_this_alert" data-value="yes" value="yes" class="btn @if($alert->action() && $alert->action()->shared_this_alert == 'yes') active @endif btn-white btn_shared" >Yes</button>
        <button type="button" data-target=".shared_this_alert" data-value="no" value="no" class="btn btn-white btn_shared @if($alert->action() && $alert->action()->shared_this_alert == 'no') active @endif" >No</button>    
    </div>
    <div class="center form-buttons shared_with_team_wrapper" @if(!$alert->action() || $alert->action()->shared_this_alert != 'yes' ) style="display:none" @endif>
        <span class="font-weight-bold">Shared With:</span>
        <input type="hidden" name="shared_with_team" @if($alert->action()) value="{{$alert->action()->shared_with_team}}" @endif class="shared_with_team" data-targets=".btn_shared_with_team">
        <button type="button" data-target=".shared_with_team" data-value="whole_team" class="btn @if($alert->action() && $alert->action()->shared_with_team == 'whole_team') active @endif btn-white btn_shared_with_team" >Whole Team</button>
        <button type="button" data-target=".shared_with_team" data-value="selected_staff" class="btn @if($alert->action() && $alert->action()->shared_with_team == 'selected_staff') active @endif btn-white btn_shared_with_team" >Selected Staff</button>    
    </div>
    <div class="center selected_team_wrapper" @if(!$alert->action() || $alert->action()->shared_with_team != 'selected_staff') style="display:none;" @endif>
    @if(count($quickLogins))
        <!-- <select name="shared_with_selected_staff[]" data-placeholder="Select team members" multiple="multiple" class="shared_with_selected_staff form-control w-50 select2">
            @foreach($quickLogins as $login)
            <option value="{{$login->user_id}}">{{$login->user->nameWithPosition()}}</option>
            @endforeach
        </select> -->
        @foreach($quickLogins as $login)
        <label class="selected_staff_label" style="margin:0 auto;">
            <input type="checkbox" name="shared_with_selected_staff[]" @if($alert->action() && $alert->action()->hasStaffMember($login->user_id)) checked @endif value="{{$login->user_id}}" class="shared_with_selected_staff">
            <span class="">{{$login->user->nameWithPosition()}}</span>
        </label>
        @endforeach
    @else 
        <p class="font-italic">No other staff member found.</p>
    @endif
    </div>
    <div class="center m-t-10">
        <input type="submit" @if(!$alert->action()) style="display:none" @endif class="psa_action_save btn btn-info" value="Save">
    </div>
</div>
<div class="medicine_recall_wrapper mt-2 center text-center" style="display:none">
    <h4 class="text-info"><i class="fa fa-server"></i><br></h4>
    <h4 class="text-info pb-3">Read & Changed Practice</h4>
    <div class="radio">
        <label>Do you have defective stock requiring quarantine?</label>&nbsp;
        <input type="radio" name="have_defective_stock" class="have_defective_stock" @if($alert->action() && $alert->action()->have_defective_stock == 'Yes') checked @endif value="Yes"> Yes
        <input type="radio" name="have_defective_stock" class="have_defective_stock" @if($alert->action() && $alert->action()->have_defective_stock == 'No') checked @endif value="No"> No
    </div>
    <div class="form-group defective_quantity_wrapper" @if(!$alert->action() || $alert->action()->have_defective_stock !='Yes') style="display:none" @endif>
        <label>Quantity</label>
        <input type="number" name="defective_quantity"  @if($alert->action()) value="{{$alert->action()->defective_quantity}}" @endif data-toggle="tooltip" title="Example: Type 56 (if you have 56 tablets in total)" class="min-w-300 defective_quantity form-control">
    </div>
    <div class="stock_been_quarantined_wrapper" @if(!$alert->action() || $alert->action()->have_defective_stock != 'Yes') style="display:none" @endif>
        <label>Has the stock been quarantined?</label>&nbsp;
        <input type="radio" name="stock_been_quarantined" class="stock_been_quarantined" value="Yes" @if($alert->action() && $alert->action()->stock_been_quarantined == 'Yes') checked @endif> Yes
        <input type="radio" name="stock_been_quarantined" class="stock_been_quarantined" value="No" @if($alert->action() && $alert->action()->stock_been_quarantined == 'No') checked @endif> No
    </div>
    <div class="form-group stock_been_quarantined_location_wrapper" @if(!$alert->action() || $alert->action()->stock_been_quarantined != 'Yes') style="display:none" @endif>
        <label>Location</label>
        <input type="text" name="stock_been_quarantined_location" @if($alert->action()) value="{{$alert->action()->stock_been_quarantined_location}}" @endif  data-toggle="tooltip" title="This is the location where the stock is/was separated" class="min-w-300 stock_been_quarantined_location form-control">
    </div>
    <div class="form-group stock_been_quarantined_reason_wrapper" @if(!$alert->action() || $alert->action()->stock_been_quarantined != 'No') style="display:none" @endif>
        <label>Reason</label>
        <input type="text" name="stock_been_quarantined_reason" @if($alert->action()) value="{{$alert->action()->stock_been_quarantined_reason}}" @endif class="min-w-300 stock_been_quarantined_reason form-control">
    </div>
    <div class="stock_been_returned_wrapper" @if(!$alert->action() || $alert->action()->have_defective_stock != 'Yes') style="display:none" @endif>
        <label>Has defective stock been returned to the supplier/manufacturer?</label>&nbsp;
        <input type="radio" name="stock_been_returned" class="stock_been_returned" value="Yes" @if($alert->action() && $alert->action()->stock_been_returned == 'Yes') checked @endif > Yes
        <input type="radio" name="stock_been_returned" class="stock_been_returned" value="No" @if($alert->action() && $alert->action()->stock_been_returned == 'No') checked @endif> No
    </div>
    <div class="form-group stock_been_returned_reason_wrapper" @if(!$alert->action() || $alert->action()->stock_been_returned != 'No') style="display:none" @endif>
        <label>Reason</label>
        <input type="text" name="stock_been_returned_reason"  @if($alert->action()) value="{{$alert->action()->stock_been_returned_reason}}" @endif class="min-w-300 stock_been_returned_reason form-control">
    </div>
    @if($alert->national_alert->patient_level_recall)
    <div class="patient_level_recall_wrapper" @if(!$alert->action() || $alert->action()->have_defective_stock !='Yes') style="display:none" @endif>
        <label>Have you checked any deliveries/parcels awaiting collection/mds that need recall?</label>&nbsp;
        <input type="radio" name="recall_awaiting_collection" class="recall_awaiting_collection" value="Yes" @if($alert->action() && $alert->action()->recall_awaiting_collection == 'Yes') checked @endif > Yes
        <input type="radio" name="recall_awaiting_collection" class="recall_awaiting_collection" value="No" @if($alert->action() && $alert->action()->recall_awaiting_collection == 'No') checked @endif > No
        <br>
        <label>Have patients been contacted/notified?</label>&nbsp;
        <input type="radio" name="patients_contacted" class="patients_contacted" value="Yes"  @if($alert->action() && $alert->action()->patients_contacted == 'Yes') checked @endif> Yes
        <input type="radio" name="patients_contacted" class="patients_contacted" value="No"  @if($alert->action() && $alert->action()->patients_contacted == 'No') checked @endif> No
    </div>
    @endif
    <div class="form-group additional_comments_wrapper" @if(!$alert->action() || $alert->action()->have_defective_stock !='Yes') style="display:none" @endif>
        <label>Additional Comments (optional)</label>
        <textarea spellcheck="true"  name="addtional_comments" class="min-w-300 addtional_comments form-control">@if($alert->action()){{$alert->action()->addtional_comments}}@endif </textarea>
    </div>

    <button type="button" @if(!$alert->action()) style="display:none" @endif class="btn btn-info mt-2 btn_save_medicine_recall_action">Next</button>
</div>