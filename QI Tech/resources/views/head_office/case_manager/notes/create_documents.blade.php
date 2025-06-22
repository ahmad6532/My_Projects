<form method="post" action="{{route('case_docuemnts.case_docuemnt.case_docuemnt_store')}}" class="cm_task_form">
    @csrf

    @if(isset($case))
    <input type="hidden" name="case_id" value="{{$case->id}}">
    @endif
    @if(isset($document))
        <input type="hidden" name="document_id" value="{{$document->id}}">
    @endif
    <div class="modal fade file_upload_model " @if(isset($document)) id="document_form_{{$document->id}}"  @else id="document_form" @endif   tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100"><p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Document</h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
                <div class="modal-body">
                <div class="form-group">
                    <label>Document Title</label>
                    <input type="text" name="title" class="form-control" required @if(isset($document)) value="{{$document->title}}" @endif>
                </div>
                <div class="form-group">
                    <label>Document Description</label>
                    <textarea spellcheck="true"  type="text" name="description" class="form-control" required>@if(isset($document)){{$document->description}}@endif</textarea>
                </div>
                
                <div class="uploaded_files mt-2 mb-2">
                @if(isset($document))
                @foreach($document->documents as $doc)
                    <li>
                        <input type='hidden' name='documents[]' class='file document' value='{{$doc->document->unique_id}}'>
                        <span class="fa fa-file"></span>&nbsp;{{$doc->document->original_file_name()}}
                        <a href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}" target="_blank" title='Preview' class="preview_btn"> <span class="fa fa-eye"></span></a>
                        <a href="#" title='Delete File' class="remove_btn"> <span class="fa fa-times"></span></a>
                    </li>
                @endforeach
                @endif
                </div>
                <h6 class="text-info">Select documents/images to upload</h6>
                <div class="cm_upload_box_with_model center">
                    <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                </div>
                    <input type="file" name="file" multiple value="" class="form-control commentMultipleFiles">
                </div>
                <div class="modal-footer">
                <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info" >Save</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</form>