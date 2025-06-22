@extends('layouts.head_office_app')
@section('content')
@php $comment->addIntoCommentViews(); @endphp
<div data-comment="{{$comment->id}}" class=" mx-auto cm_comment card @if( 0 == 0  ) cm_comment_grey @endif @if($comment->parent_id) cm_comment_reply @endif">
    <div class="cm_comment_author_date">
        <div class="cm_comment_action_bar float-right text-right dropdown no-arrow">
            @if(!$comment->type)
        @if($comment->currentUserIsAuthor())
            <a href="#"  class="ml-2 dropdown-toggle float-right" id="dropdownMenuButton_{{$comment->id}}" data-bs-toggle="dropdown">
                <i class="fa fa-ellipsis-h text-primary"></i>
            </a>
        @endif
            {{-- <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton__{{$comment->id}}" style="">
               @if($comment->currentUserIsAuthor())
                <a  data-to-focus=".cm_comment_edit_form_{{$comment->id}}" href="#" class="cm_edit_comment_btn dropdown-item">Edit</a>
                <a href="{{route('case_manager.delete_comment',$comment->id)}}" data-msg="Are you sure, you want to delete this comment?" class="dropdown-item delete_button text-danger">Delete</a>
                @endif
            </div> --}}
            
        @endif
        </div>
        <b>{{$comment->user->name}}</b> @if($comment->type) {{$comment->type}} @else commented  @endif     
        ({{$comment->days_ago()}})
        <span class="float-right">{{date('d/m/Y h:i a',strtotime($comment->updated_at))}}</span>
    </div>
    <div class="cm_comment_comment">
        {!! ($comment->comment) !!}
    </div>
    <div class="cm_comment_people">
        @foreach($comment->views as $view)
            <span data-bs-toggle="tooltip" title="Viewed by {{$view->head_office_user->user->name}}" class="badge badge-primary badge-user">{{$view->head_office_user->user->name}}</span>
        @endforeach
        {{-- @if($case->status == 'open' && !$comment->type)
        <span class="float-right"> 
            <a href="#" class="cm_comment_reply_btn">Reply?</a>
        </span>
        @endif --}}
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
    
</div>
@endsection