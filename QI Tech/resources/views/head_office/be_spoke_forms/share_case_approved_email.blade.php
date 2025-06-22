<div class="modal modal-md fade" @if(isset($shared_case_approved_email)) id="share_case_{{$shared_case_approved_email->id}}" @else id="share_case" @endif  tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Approved Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            @isset($form)
            <form action="{{route('share_emails.share_email.store', $form->id)}}" method="post" >
                @csrf
                @if(isset($shared_case_approved_email))
                
                <input type="hidden" name="shared_case_approved_email_id" value="{{$shared_case_approved_email->id}}">
                @endif
            <div class="modal-body">
                <div class="mt-2">
                    <div class="form-group">
                        <label>Email</label>
                        <input spellcheck="true" type="email" name="shared_case_approved_email" class="form-control" required @if(isset($shared_case_approved_email)) value="{{$shared_case_approved_email->email}}" @endif>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea spellcheck="true"  spellcheck="true" type="text" name="description" class="form-control" required>@if(isset($shared_case_approved_email)) {{$shared_case_approved_email->description}} @endif</textarea>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info" >Save</button>
                
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        @endisset
        </div>
    </div>
</div>