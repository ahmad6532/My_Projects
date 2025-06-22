@php
    if (isset($ho_location)) {
        $comment->addIntoCommentViews($ho_location->id);
    }else{
        $comment->addIntoCommentViews();
    }
@endphp
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
               @if($comment->currentUserIsAuthor())
                @if (!isset($ho_location) && !isset($address))
                <a  data-to-focus=".cm_comment_edit_form_{{$comment->id}}" href="#" class="cm_edit_comment_btn dropdown-item">Edit</a>
                @endif

                @if (isset($ho_location))
                    <a href="{{route('head_office.location.delete_comment',['comment_id'=>$comment->id,'__token'=>csrf_token()])}}" data-msg="Are you sure, you want to delete this comment?" class="dropdown-item delete_button text-danger">Delete</a>
                    @elseif(isset($address))
                    <a href="{{route('head_office.address.delete_comment',['comment_id'=>$comment->id,'__token'=>csrf_token()])}}" data-msg="Are you sure, you want to delete this comment?" class="dropdown-item delete_button text-danger">Delete</a>
                    @else
                    <a href="{{route('case_manager.delete_comment',['comment_id'=>$comment->id,'__token'=>csrf_token()])}}" data-msg="Are you sure, you want to delete this comment?" class="dropdown-item delete_button text-danger">Delete</a>
                @endif
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
        {!! ($comment->comment) !!}
    </div>
    <div class="cm_comment_people">
        @if (isset($comment->type) && $comment->type == 'messages at')
        <span data-bs-toggle="tooltip" title="shared by {{$comment->user->name}}" class="badge badge-primary badge-user">{{$comment->user->name}}</span>
         @else
        @foreach($comment->views as $view)
        <div class="user-container" style="position: relative;">
            <!-- The clickable span -->
            <span data-bs-toggle="tooltip" title="Viewed by {{$view->head_office_user->user->name}}" onclick="toggleCard(this)">
                <img src="{{ $view->head_office_user->user->logo }}" alt="{{$view->head_office_user->user->name}}'s logo" class="user-logo" style="width: 30px; height: 30px; border-radius: 50%;">
            </span>
        
            <!-- The card that will appear upon clicking -->
            <div class="user-card" style="display: none; position: absolute; top: 40px; left: 0; z-index: 1000; background-color: white; border: 1px solid #ddd; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; padding: 10px; width: 300px;">
                <!-- Card Content -->
                <div class=" new-info-wrapper" style="z-index: 15">
                    <div class="new-user-wrapper">
                        @if (isset($user->logo))
                            <img src="{{ $user->logo }}" alt="png_img">
                        @else
                            <div class="user-img-placeholder" id="user-img-place">
                                {{ implode('', array_map(function ($word) {
                                    return strtoupper(mb_substr($word, 0, 1));
                                }, array_filter(explode(' ', $user->name)))) }}
                            </div>
                            @endif
                        <div class="user-info-details">
                            <h5 class="user-info-name">{{ $user->name }}</h5>
                            <h6>{{ $user->getHeadOfficeUser()->position }}</h6>
                            <p class="m-0 d-flex align-items-center gap-1"><i class="fa-solid fa-phone"></i> {{ $user->getHeadOfficeUser()->user->mobile_no }}</p>
                            <p class="m-0 d-flex align-items-center gap-1"><i class="fa-regular fa-envelope"></i> {{ $user->email }}
                            </p>
                            <p class="m-0 d-flex align-items-center gap-1">
                                {{ !empty($user->getHeadOfficeUser()->about_me) ? $user->getHeadOfficeUser()->about_me : '' }}
                            </p>
                        </div>
                    </div>
                    <div class="expirtise-wrap" style="display: none;">
                        <hr class="w-100" style="margin-block: 5px!important;">
                        @if (count($user->getHeadOfficeUser()->head_office_user_area) !=0)
                            <p class="m-0" style="color: #999;font-size:14px;">Expertise</p>
                            <div>
                                @foreach ($user->getHeadOfficeUser()->head_office_user_area as $area)
                                    <p class="m-0">Area: <strong>{{$area->area}}</strong></p>
                                    <p class="m-0" style="margin-bottom: 5px;">Level: <strong>{{$area->level}}</strong></p>
                                @endforeach
                            </div>
                            @else
                            @endif
                    </div>
                        <button class="btn btn-outline-info view-info-btn" style="border: 0 !important;">View info</button>
                </div>
            </div>
        </div>
        
        <!-- Script -->
        <script>
            // Function to toggle the visibility of the card
            function toggleCard(element) {
                var card = element.nextElementSibling; // Get the next sibling (user card)
                
                // Check if the card is currently visible
                if (card.style.display === 'none' || card.style.display === '') {
                    // Hide all other cards
                    var allCards = document.querySelectorAll('.user-card');
                    allCards.forEach(function(otherCard) {
                        otherCard.style.display = 'none';
                    });
        
                    // Show the clicked user's card
                    card.style.display = 'block';
                } else {
                    // Hide the card if it's already visible
                    card.style.display = 'none';
                }
            }
        </script>
        
        @endforeach
        @endif
        @if(!$comment->type && !isset($address))
        <span class="float-right"> 
            <a href="#" class="cm_comment_reply_btn">Reply?</a>
        </span>
        @endif
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
        @if (isset($ho_location))
                @include('head_office.location_comments',['parent'=>$comment])
            @else
                @include('head_office.contacts.contact_comments',['parent'=>$comment])
        @endif
    </div>
    {{-- @include('head_office.contacts.contact_comments',['comment'=>$comment,'edit_comment'=>true]) --}}
    
    @if(count($comment->replies))<hr> @endif
    @foreach($comment->replies as $reply)
        <div class="cm_comment_replies shadow">
            @if (isset($ho_location))
                @include('head_office.contacts.view_comments',['comment'=>$reply])
            @else
                @include('head_office.contacts.view_comments',['comment'=>$reply])
        @endif
            {{-- @include('head_office.case_manager.notes.view_comments',['comment'=>$reply]) --}}
        </div>
    @endforeach
</div>