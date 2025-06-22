<style>
    .user-container {
        position: relative;
    }

    /* Initially hide the card */
    .user-card {
        display: none;
        position: absolute;
        top: 40px;
        left: 0;
        z-index: 1000;
        background-color: white;
        border: 1px solid #ddd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 10px;
        width: 300px;
    }

    /* Display the card on hover */
    .user-container:hover .user-card {
        display: block;
    }

    /* Styling for user info */
    .user-info-details {
        padding: 10px;
    }

    /* Styling for the user's image */
    .user-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    /* Expertise section */
    .expirtise-wrap hr {
        margin: 5px 0;
    }
</style>
@php $comment->addIntoCommentViews(); @endphp
<div data-comment="{{$comment->id}}" class="cm_comment card @if( $key%2 == 0  ) cm_comment_grey @endif @if($comment->parent_id) cm_comment_reply @endif">
    <div class="cm_comment_author_date">
        <div class="cm_comment_action_bar float-right text-right dropdown no-arrow">
            @if(!$comment->type)
        @if($comment->currentUserIsAuthor())
            <a href="#"  class="ml-2 dropdown-toggle float-right" id="dropdownMenuButton_{{$comment->id}}" data-bs-toggle="dropdown">
                <i class="fa fa-ellipsis-h text-primary"></i>
            </a>          
        @endif
            <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton__{{$comment->id}}" style="">
               @if($comment->currentUserIsAuthor() && (isset($case) && $case->isArchived == false))
                <a  data-to-focus=".cm_comment_edit_form_{{$comment->id}}" href="#" class="cm_edit_comment_btn dropdown-item">Edit</a>
                <a  href="{{route('case_manager.seen_comment',['comment_id'=>$comment->id,'__token'=>csrf_token()])}}" class=" dropdown-item">Mark as seen</a>
                <a href="{{route('case_manager.delete_comment',['comment_id'=>$comment->id,'__token'=>csrf_token()])}}" data-msg="Are you sure, you want to delete this comment?" class="dropdown-item delete_button text-danger">Delete</a>
                @endif
            </div>
            
        @endif

        
        </div>
        <img src="{{ $comment->user->logo }}" alt="{{$comment->user->name}}'s logo" class="user-logo" style="width: 30px; height: 30px; border-radius: 50%;">
        <b>{{$comment->user->name}}</b> @if($comment->type) {{$comment->type}} @else commented  @endif     
        ({{$comment->days_ago()}}) 
        @if (isset($comment->type) && $comment->type == 'messages at')
            from shared case
        @endif
        <span class="float-right">{{date('d/m/Y h:i a',strtotime($comment->updated_at))}}</span>
    </div>
    <div class="cm_comment_comment">
        {!! ($comment->comment) !!}
        @if(isset($comment->record_update))
                    <p class="m-0">{{$comment->record_update->update}}</p>
                    @foreach ($comment->record_update->documents as $doc)
                                                                            <span data-bs-toggle="tooltip" title=""
                                                                                class="badge badge-primary badge-user">
                                                                                <a style="color: white"
                                                                                    class="relative @if ($doc->type == 'image') cm_image_link @endif "
                                                                                    href="{{ route('head_office.be_spoke_forms.record.update.view.new_attachment', $doc->document->unique_id) . $doc->document->extension() }}"
                                                                                    target="_blank">
                                                                                    @if ($doc->type == 'image')
                                                                                    <i class="fa fa-image"></i> @else<i
                                                                                            class="fa fa-link"></i>
                                                                                    @endif
                                                                                    {{ $doc->document->original_file_name() }}
                                                                                    @if ($doc->type == 'image')
                                                                                        <div class="cm_image_hover">
                                                                                            <div class="card shadow">
                                                                                                <div class="card-body">
                                                                                                    <img class="image-responsive"
                                                                                                        width="300"
                                                                                                        src="{{ route('head_office.be_spoke_forms.record.update.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                </a>
                                                                            </span>
                                                                        @endforeach
                @endif
    </div>
    {{-- commenting out this because Taskeel says that it is not needed because of the new feature of ticks --}}
    {{-- <div class="cm_comment_people d-flex align-items-center justify-content-between mt-3">
        @if (isset($comment->type) && $comment->type == 'messages at')
        <span data-bs-toggle="tooltip" title="shared by {{$comment->user->name}}" class="badge badge-primary badge-user">{{$comment->user->name}}</span>
         @else
         <div class="d-flex " style="width:fit-content">
             @foreach($comment->views as $view)
     
             <div class="user-icon-circle new-card-wrap" style="position: absoloute;">
                
                 <span data-bs-toggle="tooltip" title="Viewed by {{$view->head_office_user->user->name}}">
                     <img src="{{ $view->head_office_user->user->logo }}" alt="{{$view->head_office_user->user->name}}'s logo" class="user-logo" style="width: 30px; height: 30px; border-radius: 50%;">
                 </span>
                 @include('head_office.user_card_component',['user'=>$view->head_office_user->user])
             </div>
     
             
             @endforeach

         </div>
        @endif

        <div style="display: flex; flex-direction: column; align-items: flex-end; margin-left: auto;">
            @if(isset($case) && $case->status == 'open' && !$comment->type)
            <span style="margin-bottom: 10px;">
                <a href="#" class="cm_comment_reply_btn">Reply?</a>
            </span>
            @endif
            @if ($comment->type == 'update submited from location') 
            <span>
                <a href="{{ route('case_manager.view_report', $case->id ?? 0) }}?tab=updatesed" class="view-all-updates">
                    View all Updates
                </a>
            </span>      
            @endif
            
        </div>    
    </div> --}}
    @if(count($comment->documents))
    <div class="cm_comment_attachments mt-1">
        <ul class="list-style-none p-0">
            @foreach($comment->documents as $doc)
                <li class="relative">
                    @if ($doc->type == 'audio')
                        <div class="mt-2">
                            <audio class="m-0" controls src="{{route('headoffice.view.attachment', $doc->document->unique_id) . $doc->document->extension()}}"></audio>
                        </div>
                    @else
                        <a class="relative @if($doc->type == 'image') cm_image_link @endif" 
                           href="{{route('headoffice.new_view.attachment', $doc->document->unique_id).$doc->document->extension()}}" 
                           target="_blank" 
                           onClick="handleDocumentClick(event, '{{route('headoffice.new_view.attachment', $doc->document->unique_id).$doc->document->extension() }}', '{{ $doc->type}}')">
                            <i class="fa fa-link"></i> {{$doc->document->original_file_name()}}
                            @if($doc->type == 'image')
                                <div class="cm_image_hover">
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <img class="image-responsive" width="300" 
                                                 src="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
        
        <script>
        function handleDocumentClick(event, url, type) {
            // event.preventDefault();
        
            if (type === 'image') {
                // openImageWithDownloadButton(url);
            } else {
                // downloadFile(url);
            }
        }
        
        function openImageWithDownloadButton(url) {
            const newTab = window.open(url, '_blank');
        
            const downloadButton = document.createElement('button');
            downloadButton.style.position = 'absolute';
            downloadButton.style.top = '20px';
            downloadButton.style.right = '20px';
            downloadButton.style.backgroundColor = 'transparent';
            downloadButton.style.border = 'none';
            downloadButton.style.cursor = 'pointer';
            downloadButton.style.zIndex = '1000';
        
            const svg = `
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" 
                     xmlns:xlink="http://www.w3.org/1999/xlink" 
                     width="24px" height="24px" viewBox="0 0 122.433 122.88" 
                     enable-background="new 0 0 122.433 122.88" xml:space="preserve">
                    <g>
                        <polygon fill="#007bff" fill-rule="evenodd" clip-rule="evenodd" 
                                 points="61.216,122.88 0,59.207 39.403,59.207 39.403,0 83.033,0 
                                 83.033,59.207 122.433,59.207 61.216,122.88"/>
                    </g>
                </svg>`;
            
            downloadButton.innerHTML = svg;
        
            downloadButton.onclick = function() {
                const downloadLink = document.createElement('a');
                downloadLink.href = url;
                downloadLink.download = '';
                downloadLink.click();
            };
        
            // Wait for the new tab to load, then add the download button
            newTab.onload = function() {
                const style = newTab.document.createElement('style');
                style.innerHTML = `
                    body {
                        position: relative; /* Allow absolute positioning */
                        margin: 0; /* Remove default margin */
                        background-color: #fff; /* Ensure white background */
                    }
                `;
                newTab.document.head.appendChild(style);
                newTab.document.body.appendChild(downloadButton);
            };
        }
        
        function downloadFile(url) {
            const downloadLink = document.createElement('a');
            downloadLink.href = url;
            downloadLink.download = '';
            downloadLink.click();
        }
        </script>
        
        
    </div>
    @endif
    @if(isset($case) && $case->isArchived == false)
        <div class="cm_comment_reply" style="display:none">
            @include('head_office.case_manager.notes.form_comment',['parent'=>$comment])
        </div>
        @include('head_office.case_manager.notes.form_comment',['comment'=>$comment,'edit_comment'=>true])
    @endif

    @if(count($comment->replies))<hr> @endif
    @foreach($comment->replies as $reply)
        <div class="cm_comment_replies shadow">
            @include('head_office.case_manager.notes.view_comments',['comment'=>$reply])
        </div>
    @endforeach


    @if ($comment->currentUserIsAuthor() && isset($case) && $case->isArchived == false && $comment->views->where('is_seen',1)->count() > 0)
        @include('head_office.users_seen_component',['comment'=>$comment,'route'=>route('case_manager.unseen_comment',['id'=>$comment->id,'_token'=>csrf_token()])])
    @endif
</div>