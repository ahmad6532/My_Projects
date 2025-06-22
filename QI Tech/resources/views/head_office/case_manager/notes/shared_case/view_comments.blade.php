
<div data-comment="{{$comment->id}}" class="cm_comment card @if( !$comment->is_user) cm_comment_grey @endif">
    <div class="cm_comment_author_date">
        <b>{{$comment->user->name}}</b> @if(!$comment->is_user) Head Office User @else commented  @endif {{$comment->created_at->diffForHumans()}}
        <span class="float-right">{{date('d/m/Y h:i a',strtotime($comment->updated_at))}}</span>
    </div>
    <div class="cm_comment_comment">
        {!! ($comment->message) !!}
    </div>
    
    @if(count($comment->documents))
    <div class="cm_comment_attachments mt-1">
        <ul class="list-style-none p-0">
            @foreach($comment->documents as $doc)
                 <li class="relative ">
                    <a class="relative @if($doc->type == 'image') cm_image_link @endif " href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}" target="_blank"><i class="fa fa-link"></i> {{$doc->document->original_file_name()}}
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
                </li>
            @endforeach
        </ul>
    </div>
    @endif
    @if ($comment->currentUserIsAuthor() && isset($case) && $case->isArchived == false && $comment->views->where('is_seen',1)->count() > 0)
        @include('head_office.users_seen_component',['comment'=>$comment,'route'=>route('case_manager.unseen_comment',['id'=>$comment->id,'_token'=>csrf_token()])])
    @endif
</div>