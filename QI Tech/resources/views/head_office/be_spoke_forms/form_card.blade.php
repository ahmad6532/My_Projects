<form method="post" action="{{route('head_office.be_spoke_form.form_card_save')}}" class="cm_task_form">
    @csrf

    @if(isset($form))
    <input type="hidden" name="form_id" value="{{$form->id}}">
    @endif
    @if(isset($card))
    <input type="hidden" name="default_card_id" value="{{$card->id}}">
    @endif
    <div class="modal fade file_upload_model " @if(isset($card)) id="default_card_form_{{$card->id}}" @else
        id="default_card_form" @endif tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Involvements
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="card_name">Name</label>
                               <input type="text" value="@if(isset($card)){{$card->name}}@endif" class="form-control" name="card_name" id="card_name">
                            </div>
                            <div class="form-group">
        
                                
                            </div>
                        </div>
                        <div class="col-sm-6">
        
                            <div class="form-group profiles">
        
                                {{-- <label>
                                    Select card
                                </label>
                                <select class="form-control select_2 w-100" name="card_id">
                                    @foreach ($cards as $c)
                                    <option value="{{$c->id}}" @if (isset($card) && $c->id == $card->default_card_id ) selected @endif >{{$c->type}}
                                    </option>
                                    @endforeach
                                </select> --}}

                                <label>
                                    Link with
                                </label>
                                <select class="form-control select_2 w-100" name="link_ids[]" multiple>
                                    @foreach ($form_cards as $c)
                                        @if(optional($card)->id != $c->id)
                                        <option value="{{$c->id}}" @if(in_array($c->id,optional($card)->connected_form_card_ids ?? [])) 
                                            selected @endif>{{$c->name}}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>