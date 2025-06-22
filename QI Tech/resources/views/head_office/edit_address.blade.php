

<div class="modal fade" @if (isset($address)) id="add_new_address_{{$address->id}}" @else id="add_new_address" @endif tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form method="post" @if(isset($address)) action="{{route('head_office.contact.add_new_normal_address',$address->id)}}" @else action="{{route('head_office.contact.add_new_normal_address')}}" @endif>
                @csrf
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    Add New Address
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="organisation-structure-add-content hide-placeholder-parent">
                    <label class="inputGroup">Address : 
                        <input type="text" name="address" placeholder="Add address" value="{{optional($address)->address}}" class="free-type-address pac-target-input" required="">
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
        </form>
        </div>
    </div>
</div>

