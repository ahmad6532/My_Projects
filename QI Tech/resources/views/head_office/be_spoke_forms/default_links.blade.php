<div>
<form method="post" action="{{route('default_links.default_link.store')}}" class="cm_task_form">
    @csrf

    @if(isset($form))
    <input type="hidden" name="form_id" value="{{$form->id}}">
    @endif
    @if(isset($document))
        <input type="hidden" name="default_link_id" value="{{$document->id}}">
    @endif
    <div class="modal fade file_upload_model " @if(isset($document)) id="default_links_form_{{$document->id}}"  @else id="default_links_form" @endif   tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100"><p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Default Links</h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
                <div class="modal-body">
                <div class="form-group">
                    <label>Link Title</label>
                    <input spellcheck="true" type="text" name="title" class="form-control" required @if(isset($document)) value="{{$document->title}}" @endif>
                </div>
                <div class="form-group">
                    <label>Link</label>
                    <input type="text" name="link" class="form-control" required @if(isset($document)) value="{{$document->link}}" @endif>
                </div>
                <div class="form-group">
                    <label>Link Description</label>
                    <textarea spellcheck="true"  spellcheck="true" type="text" name="link_description" class="form-control" required>@if(isset($document)){{$document->link_description}}@endif</textarea>
                </div>
                
                
                <div class="modal-footer">
                <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info" >Save</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>

</div>