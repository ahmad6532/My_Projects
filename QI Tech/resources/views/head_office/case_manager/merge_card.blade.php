<form method="post" action="{{route('case_manager.intelligence.mrege_contact')}}" class="cm_task_form">
    @csrf



    <div class="modal fade file_upload_model " @if($type == 'patient') id="merge_contact_patient_{{$contact->id}}" @else
        id="merge_contact_prescriber_{{$contact->id}}" @endif tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Merge Card
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="card_name">Merge With</label>
                                <input type="text" readonly  class="form-control" value="{{$contact_from->id}} = {{$contact_from->first_name}} {{$contact_from->last_name}} ">
                                <input type="hidden" name="c1" value="{{$contact_from->id}}">
                            </div>
                           
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="card_name">Merge From </label>
                                <input type="text" readonly  class="form-control" value="{{$contact->id}} = {{$contact->first_name}} {{$contact->last_name}} ">
                                <input type="hidden" name="c2" value="{{$contact->id}}">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="type" value="{{$type}}">
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