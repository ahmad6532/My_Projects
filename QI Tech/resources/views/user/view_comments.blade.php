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
               @if($comment->currentUserIsAuthor() )
                {{-- <a  data-to-focus=".cm_comment_edit_form_{{$comment->id}}" href="#" class="cm_edit_comment_btn dropdown-item">Edit</a> --}}
                <a  href="{{route('user.share_case.seen_comment',['comment_id'=>$comment->id,'__token'=>csrf_token()])}}" class=" dropdown-item">Mark as seen</a>
                <a href="{{route('user.share_case.delete_comment',['comment_id'=>$comment->id,'__token'=>csrf_token()])}}" data-msg="Are you sure, you want to delete this comment?" class="dropdown-item delete_button text-danger">Delete</a>
                @endif
            </div>
            
        @endif

        
        </div>
        <b>{{$comment->user->name}}</b> @if($comment->type) {{$comment->type}} @else commented  @endif     
        ({{$comment->days_ago()}}) 
        @if (isset($comment->type) && $comment->type == 'messages at')
            from shared case
        @endif
        <span class="float-right">{{date('d/m/Y h:i a',strtotime($comment->updated_at))}}</span>
    </div>
    <div class="cm_comment_comment">
        {!! ($comment->message) !!}
    </div>
    <div class="cm_comment_people">
        @if (isset($comment->type) && $comment->type == 'messages at')
        <span data-bs-toggle="tooltip" title="shared by {{$comment->user->name}}" class="badge badge-primary badge-user">{{$comment->user->name}}</span>
         @else
        @foreach($comment->views as $view)

        <div class="user-icon-circle new-card-wrap" style="position: absoloute;">
           
            <span data-bs-toggle="tooltip" title="Viewed by {{$view->head_office_user->user->name}}">
                <img src="{{ $view->head_office_user->user->logo }}" alt="{{$view->head_office_user->user->name}}'s logo" class="user-logo" style="width: 30px; height: 30px; border-radius: 50%;">
            </span>
            @include('head_office.user_card_component',['user'=>$view->head_office_user->user])
        </div>
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

        
        @endforeach
        @endif
        <span class="float-right"> 
            <a href="#" class="cm_comment_reply_btn">Reply?</a>
        </span>
    </div>
    @if(count($comment->documents))
    <div class="cm_comment_attachments mt-1">
        <ul class="list-style-none p-0">
            @foreach($comment->documents as $doc)
                 <li class="relative ">
                    @if ($doc->type == 'audio')
                    <div class="mt-2">
                        <audio class="m-0" controls src="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"></audio>
                    </div>
                    @else
                        <a class="relative 
                        @if($doc->type == 'image') cm_image_link @endif " href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}" target="_blank"><i class="fa fa-link"></i> {{$doc->document->original_file_name()}}
                        @if($doc->type == 'image')
                            <div class="cm_image_hover">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <img class="image-responsive" width="300" src="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}">
                                    </div>
                                </div>
                            </div>
                        @endif
                        </a>

                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="cm_comment_reply" style="display:none">
        @include('user.form_comment',['parent'=>$comment])
    </div>
    @include('user.form_comment',['comment'=>$comment,'edit_comment'=>true])
    
    @if(count($comment->replies))<hr> @endif
    @foreach($comment->replies as $reply)
        <div class="cm_comment_replies shadow">
            @include('user.view_comments',['comment'=>$reply])
        </div>
    @endforeach

    @if ($comment->currentUserIsAuthor() && $comment->views->where('is_seen',1)->count() > 0)
        @include('head_office.users_seen_component',['comment'=>$comment,'route'=>route('user.share_case.unseen_comment',['id'=>$comment->id,'_token'=>csrf_token()])])
    @endif
</div>