<div class="modal fade" @if (isset($contact_connection)) id="edit_relation_{{$contact_connection->id}}" @else id="add_new_relation" @endif tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form method="post" @if (isset($contact_connection)) action="{{route('head_office.contact.add_new_relation',[$contact->id,$contact_connection->id])}}" @else action="{{route('head_office.contact.add_new_relation',$contact->id)}}" @endif>
                @csrf
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    Add New Relation
                </h4>
                <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Contacts</label>
                    <select class="form-control select_2 w-100" name="contact_ids[]" multiple>
                        @foreach ($contacts as $c)
                            @if(!in_array($c->id,$connection_ids) && $contact->id != $c->id)
                            <option value="{{$c->id}}" @if ( isset($contact_connection) && $c->id == $contact_connection->connected_with_id) selected @endif>{{$c->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Relation Type</label>
                    <input type="text" class="form-control" name="relation_type" id="relation_type" value="{{optional($contact_connection)->relation_type}}" required>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group right">
                    <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                        data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
