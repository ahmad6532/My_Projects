<form method="post" class=" cm_comment_form mt-1 shadow " action="{{route('head_office.share_case.share_case_comment',[$case->id,$shared_case->id])}}">

    <div class="new_comment_form cm_upload_box_with_model">
        @csrf       
        <!-- <input type="hidden" name="links[]" value=""> -->
        <span class="inline-block links relative">
            <span class="microphone-status"></span>
            <a href="#" data-bs-toggle="modal" data-bs-target="#add_links" title="Add external link"><i class="fa fa-link"></i></a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#add_files" title="Add file"><i class="fa fa-paperclip fa-flip-horizontal"></i></a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#add_files" title="Add image"><i class="fa fa-image"></i></a>
        </span>
        <p class="inline-block cm_comment_box" name="comment" required placeholder="Type notes here & drop files here"
            contenteditable="true"></p>
        <textarea spellcheck="true"  style="display:none" rows="3" class="cm_comment_box_hidden"
            name="comment"></textarea>
        <br />

        <button type="submit" name="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                class="fa fa-location-arrow"></i> </button>


        <div class="uploaded_files2 mt-2 mb-2"></div>

    </div>
    <div class="modal fade" @if(isset($parent)) id="add_links_{{$parent->id}}" @elseif(isset($edit_comment))
        id="edit_add_links_{{$comment->id}}" @else id="add_links" @endif @if(isset($remove_backdrop))
        data-backdrop="false" @endif tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                        External Link Box
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="new_link_wrapper">
                        <div class="form-group">
                            <label>Link Title</label>
                            <input type="text" name="link_title" id="link_title" class="form-control link_title">
                        </div>
                        <div class="form-group">
                            <label>Link Url</label>
                            <input type="text" name="link_url" id="link_url" class="form-control link_url">
                        </div>
                        <div class="form-inline">
                            <h5>File Removal Reminder</h5>
                            <p>If this is a link to a file, you can set reminder to notify you to remove this files in line with your data retention policy when the retention period is exceeded.  </p>
    
                            <label>Remind Me</label>&nbsp;&nbsp;
                            <input type="checkbox" name="is_remind_me" id="is_remind_me" class="form-control is_remind_me">
                            &nbsp;&nbsp;&nbsp;
                            
                            <label>Notify Me in</label>&nbsp;&nbsp;&nbsp;
                            <input type="number" name="duration"  style="width: 100px" class="form-control duration">
                            <select class="form-control duration_units">
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                                <option value="years">Years</option>
                            </select>
                            <input type="hidden" name="reminder_links[]" multiple="multiple" class="reminder_links">
                        </div>
                        <button data-bs-dismiss="modal" class="btn btn-white add_link">Add Link</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade file_upload_model" @if(isset($parent)) id="add_files_{{$parent->id}}"
        @elseif(isset($edit_comment)) id="edit_add_files_{{$comment->id}}" @else id="add_files" @endif
        @if(isset($remove_backdrop)) data-backdrop="false" @endif tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text-success"><i class="fa fa-cloud fa-3x"></i></p>
                        File Upload Box
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="uploaded_files mt-2 mb-2">
                        @if(isset($edit_comment))
                        @foreach($comment->documents as $doc)
                        <li>
                            <input type='hidden' name='documents[]' class='file document'
                                value='{{$doc->document->unique_id}}'>
                            <span class="fa fa-file"></span>&nbsp;{{$doc->document->original_file_name()}}
                            <a href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                target="_blank" title='Preview' class="preview_btn"> <span
                                    class="fa fa-eye"></span></a>
                            <a href="#" title='Delete File' class="remove_btn"> <span
                                    class="fa fa-times"></span></a>
                        </li>
                        @endforeach
                        @endif
                    </div>
                    <h6 class="text-info">Select documents/images to upload</h6>
                    <input type="file" name="file" multiple value="" class="form-control commentMultipleFiles">
    
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Continue <i
                                class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>



