<form method="post" @if (isset($edit_comment)) style="display:none" @endif
    class="@if (isset($edit_comment)) cm_comment_edit_form_{{ $comment->id }} @endif cm_comment_form mt-1 shadow @if (isset($parent)) form_reply_{{ $parent->id }} @endif"
    action="{{ route('head_office.location_comment.save') }}" enctype="multipart/form-data">

    <div class="location-check"></div>
    <style>
        .modal-backdrop {
            z-index: 0;
        }

        .new_comment_form.cm_upload_box_with_model.highlight {
            border: 2px dashed #007bff;
            background-color: #f0f8ff;
        }
    </style>

    <div class="new_comment_form cm_upload_box_with_model ">
        @csrf
        <input type="hidden" id="ho_location_id" name="ho_location_id" value="{{ $ho_location->id }}">
        <input type="hidden" name="users_list_url" class="users_list_url"
            value="{{ route('head_office.contacts.users_list') }}">
        @if (isset($parent))
            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
        @endif
        @if (isset($edit_comment))
            <input type="hidden" name="id" value="{{ $comment->id }}">
        @endif
        <!-- <input type="hidden" name="links[]" value=""> -->
        <span class="inline-block links relative">
            <span class="microphone-status"></span>
            <a href="#" data-bs-toggle="modal"
                @if (isset($parent)) data-bs-target="#add_links_{{ $parent->id }}"
                @elseif(isset($edit_comment)) data-bs-target="#edit_add_links_{{ $comment->id }}" @else
                data-bs-target="#add_links" @endif
                title="Add link to externally stored files (e.g. on cloud)"><i class="fa fa-link"></i></a>
            <a href="#" data-bs-toggle="modal"
                @if (isset($parent)) data-bs-target="#add_files_{{ $parent->id }}"
                @elseif(isset($edit_comment)) data-bs-target="#edit_add_files_{{ $comment->id }}" @else
                data-bs-target="#add_files" @endif
                title="Add file"><i class="fa-solid fa-file"></i></a>
            <a href="#" data-bs-toggle="modal"
                @if (isset($parent)) data-bs-target="#add_files_{{ $parent->id }}"
                @elseif(isset($edit_comment)) data-bs-target="#edit_add_files_{{ $comment->id }}" @else
                data-bs-target="#add_files" @endif
                title="Add image"><i class="fa fa-image"></i></a>



            <a href="#" title="Click to speak" class="microphone-btn"><i class="fa fa-microphone"></i></a>
            <a href="#" title="Click to speak" class="start-record-btn"><svg width="18" height="18"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 10L3 14M7.5 11V13M12 6V18M16.5 3V21M21 10V14" stroke="#b9b9b9" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
            <a href="#" class="stop-record-btn" style="display: none;color:red;"><i
                    class="fa-solid fa-record-vinyl"></i></a>

            @if (isset($parent))
                <br><a href="#" class="cm_comment_reply_cancel">Cancel</a>
            @endif
            @if (isset($edit_comment))
                <br><a href="#" onclick="jQuery(this).closest('form').hide()">Cancel</a>
            @endif
        </span>
        <p class="inline-block cm_comment_box" name="comment" required placeholder="Type notes here & drop files here"
            contenteditable="true">
            @if (isset($edit_comment))
                {!! strip_tags($comment->comment, $comment->allowedHtmlTags()) !!}
            @elseif(isset($draftComment))
                {{ $draftComment->comment }}
            @endif
        </p>
        <textarea spellcheck="true"  id="main-comment" style="display:none" rows="3" class="cm_comment_box_hidden" name="comment">
@if (isset($edit_comment))
{!! strip_tags($comment->comment, $comment->allowedHtmlTags()) !!}
@elseif(isset($draftComment))
{{ $draftComment->comment }}
@endif
</textarea>
        <br />

        <button type="submit" name="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                class="fa fa-location-arrow"></i> </button>


        <div class="uploaded_files2 mt-2 mb-2"></div>

        <div class="modal fade file_upload_model unique_file"
            @if (isset($parent)) id="add_files_{{ $parent->id }}"
            @elseif(isset($edit_comment)) id="edit_add_files_{{ $comment->id }}" @else id="add_files" @endif
            @if (isset($remove_backdrop)) data-backdrop="false" @endif tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="z-index: 20000;">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title text-info w-100">
                            {{-- <p class="text-success"><i class="fa fa-cloud fa-3x"></i></p> --}}
                            Upload file
                        </h4>
                        <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="uploaded_files mt-2 mb-2">
                            @if (isset($edit_comment))
                                @foreach ($comment->documents as $doc)
                                    <li>
                                        <input type='hidden' name='documents[]' class='file document'
                                            value='{{ $doc->document->unique_id }}'>
                                        <span
                                            class="fa fa-file"></span>&nbsp;{{ $doc->document->original_file_name() }}
                                        <a href="{{ route('headoffice.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}"
                                            target="_blank" title='Preview' class="preview_btn"> <span
                                                class="fa fa-eye"></span></a>
                                        <a href="#" title='Delete File' class="remove_btn"> <span
                                                class="fa fa-times"></span></a>
                                    </li>
                                @endforeach
                            @endif
                        </div>
                        <h6 class="text-info">Select documents/images to upload</h6>
                        <input type="file" name="file" multiple value=""
                            class="form-control commentMultipleFiles">

                    </div>
                    <div class="modal-footer">
                        <div class="btn-group right">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Continue <i
                                    class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade"
            @if (isset($parent)) id="add_links_{{ $parent->id }}" @elseif(isset($edit_comment))
            id="edit_add_links_{{ $comment->id }}" @else id="add_links" @endif
            @if (isset($remove_backdrop)) data-backdrop="false" @endif tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title text-black fw-bold w-100 my-3">
                            {{-- <p class="text-black"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p> --}}
                            Add Link
                        </h4>
                        <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="new_link_wrapper">
                            <div class="form-group">
                                <label>Link URL</label>
                                <input type="text" name="link_url" id="link_url" class="form-control link_url">
                            </div>
                            <div class="form-group">
                                <label>Link Name</label>
                                <input type="text" name="link_title" id="link_title"
                                    class="form-control link_title">
                            </div>
                            <div class="form-group">
                                <label>Link Comment</label>
                                <input type="text" name="link_comment" class="form-control link_comment">
                            </div>
                            <div class="form-inline mt-3">

                                <div class="notify-wrap" style="display: none;">
                                    <label class="my-2 fw-semibold">Notify Me in</label>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <input type="number" name="duration" style="width: 100px"
                                            class="form-control duration">
                                        <select class="form-control duration_units">
                                            <option value="days">Days</option>
                                            <option value="weeks">Weeks</option>
                                            <option value="months">Months</option>
                                            <option value="years">Years</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="reminder_links[]" multiple="multiple"
                                    class="reminder_links">
                            </div>
                            <button data-dismiss="modal" class="btn btn-white add_link">Add Link</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="_token" value="{{ csrf_token() }}">


    </div>
</form>
<script>
    var saveDraftTimeout;

    function saveDraft(comment) {
        console.log('calling api', comment)
        let data = {
            comment: comment,
            case_id: $('#case_id').val()
        }
        $.ajax({
            url: '{{ route('case_manager.save_comment_draft') }}',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': $('#_token').val()
            },
            success: function(response) {
                alertify.alert('Draft AutoSaved', `Comment AutoSaved as draft | ${response.time}`);
                console.log('Draft saved successfully:', response);
            },
            error: function(xhr, status, error) {
                console.error('Error saving draft:', error);
            }
        });
    }

    $('.cm_comment_box').on('input', function() {
        var comment = $(this).html().trim();

        var decodedComment = $("<div/>").html(comment).text();

        var commentWithoutWhitespace = decodedComment.replace(/\s/g, '');

        clearTimeout(saveDraftTimeout);

        if (commentWithoutWhitespace !== '') {
            saveDraftTimeout = setTimeout(function() {
                saveDraft(commentWithoutWhitespace);
            }, 120000);
        }
    });
</script>
