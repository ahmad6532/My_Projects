<form method="post" action="{{route('head_office.approved_locaton.store',$key)}}" class="cm_task_form">
    @csrf
    @if(isset($location) && $key)
    <input type="hidden" name="user_id" value="{{$key}}">
    @endif
    <div class="modal fade" @if(isset($location)) id="edit_approved_location_{{$key}}" @else id="add_approved_location" @endif>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">Add Approved User</h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="organisation-structure-add-content">
                        <label class="inputGroup">Email
                            <input type="email" @isset($location) aria-describedby="validationServer03Feedback_{{$key}}" @else aria-describedby="validationServer03Feedback" @endisset name="group_name" placeholder="Email" @isset($location) readonly value="{{$head_office->findUser($key)->email}}" @endisset required="">
                            @if(!$location) <a style="float: right;" onclick="searchEmail(this)" href="javascript:void(0)" class="btn btn-page-content"><span class="fa fa-search"></span></a>@endif 
                            <div class="invalid-feedback" style="display: none;">
                                No access found
                            </div> 
                        </label>
                                           
                        
                    </div>      
                   
                    <div class="groups organisation-structure-add-content">
                        <div class="inline-block">
                            <label class="inputGroup">Select Groups
                            <select name="groups[]"  multiple class="select2 @if(isset($location))group_{{$key}}@else general_group_select @endif">
                                @foreach ($groups as $group)
                                    <option value="{{$group->id}}" @if((isset($location)) && (in_array($group->id,$group_ids) )) selected @endif> {{$group->group}} </option>
                                @endforeach
                            </select>
                            </label>
                        </div>
                    </div>
                    <div class="locations organisation-structure-add-content">
                        <label class="inputGroup">Locations
                        <select name="locations[]"  multiple class="select2 @if(isset($location))location_{{$key}}@else general_location_select @endif">
                            @foreach ($locations as $loc)
                                <option value="{{$loc->location->id}}" @if((isset($location)) && ( in_array($loc->location->id ,$location_ids))) selected @endif> {{$loc->location->registered_company_name}} </option>
                            @endforeach
                        </select>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>