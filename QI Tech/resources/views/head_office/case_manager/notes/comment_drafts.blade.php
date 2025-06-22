@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())

@section('sub-header')
@include('head_office.case_manager.notes.sub-header')
@endsection

@section('content')
<div id="content">
@include('layouts.error')
<div class="card card-qi content_widthout_sidebar">
    <div class="card-body">
        <div class="cm_content pt-2">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center text-dark h3 font-weight-bold mb-4">Autosaved Comments


                            </h3>
                            <!-- <h3 class="text-center text-info h3 font-weight-bold mb-4">Autosaved Comments</h3> -->
                            <div class="table-responsive">
                            @if($drafts->isEmpty())
                           
                            <div class="text-left my-4">
                                <p>You don't have any draft comments</p>
                            </div>
                                @else
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    {{-- <thead>
                                    <tr>
                    <th></th>
                    <th></th>
                </tr>
                                    </thead> --}}
                                    <tbody>
                @foreach($drafts as $key => $comment)
                <tr>
                    {{-- <td data-session-id="{{ $comment->id }}"></td>
                    <td> --}}
                        <div data-comment="{{$comment->id}}" class="cm_comment card @if( $key % 2 == 0 ) cm_comment_grey @endif @if($comment->parent_id) cm_comment_reply @endif">
                            <div class="cm_comment_author_date">
                                <div class="cm_comment_action_bar float-right text-right dropdown no-arrow">
                                    @if(!$comment->type)
                                        @if($comment->currentUserIsAuthor())
                                        <a href="#" class="ml-2 dropdown-toggle float-right" id="dropdownMenuButton_{{$comment->id}}" data-bs-toggle="dropdown" style="font-size: 1.5rem; display: inline-block; vertical-align: middle;">
                                        </a>
                                        @endif
                                        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton__{{$comment->id}}" style="">
                                            @if($comment->currentUserIsAuthor())
                                                <a href="{{route('case_manager.view',['id' => $case->id, 'comment_id' => $comment->id])}}" class="dropdown-item">Continue</a>
                                                <a href="{{route('case_manager.delete_comment', ['comment_id'=>$comment->id,'__token'=>csrf_token()])}}" data-msg="Are you sure, you want to delete this comment?" class="dropdown-item delete_button text-danger">Delete</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <b>{{$comment->user->name}}</b> @if($comment->type) {{$comment->type}} @else comment autosaved @endif ({{$comment->days_ago()}})
                                <span class="float-right">{{date('d/m/Y h:i a',strtotime($comment->updated_at))}}</span>
                            </div>
                            <div class="cm_comment_comment">
                                {!! ($comment->comment) !!}
                            </div>
                            <div class="cm_comment_people">
                                @foreach($comment->views as $view)
                                    <span data-bs-toggle="tooltip" title="Viewed by {{$view->head_office_user->user->name}}" class="badge badge-primary badge-user">{{$view->head_office_user->user->name}}</span>
                                @endforeach
                            </div>
                            @if(count($comment->documents))
                            <div class="cm_comment_attachments mt-1">
                                <ul class="list-style-none p-0">
                                    @foreach($comment->documents as $doc)
                                         <li class="relative ">
                                            <a class="relative @if($doc->type == 'image') cm_image_link @endif" href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}" target="_blank"><i class="fa fa-link"></i> {{$doc->document->original_file_name()}}
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
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<div class="d-none" hidden>
    <form id="delete-form" hidden action="{{route('case_manager.delete_comment_multi')}}" method="POST">
        @csrf
        <input type="text" name="sessionIds[]" value="" id="sessionid-input">
    </form>
</div>

<div id="draggable" class="bottom-nav position-fixed " style="z-index: 9999;" aria-describedby="drag">
    <div class="left-side">
        <div class="info-wrapper">
            <div class="selected-show">
                <h5 id="count">0</h5>
            </div>
            <div class="info-heading" style="max-width: 180px;overflow:hidden;">
                <p>Items Selected</p>
                <div class="dots-wrapper">
                    <span class="dot"></span>
                </div>
            </div>
        </div>

        <div class="btn-wrapper">
            <button id='delete-btn' class="bar-btn" style="width: 180px;" title="Remove selected records">
                <img src="{{ asset('images/trash-01.svg') }}" alt="icon">
                <p>Remove Selected</p>
            </button>
        </div>
    </div>
    <button class="drag-btn">
        <img src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
        <img style="margin-top:-15px;" src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
    </button>
</div>

</div>

@section('styles')
@endsection

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let table = new DataTable('#session-dataTable', {
            paging: false,
            info: false,
            language: {
                search: ""
            },
            'columnDefs': [{
                "select": 'multi',
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': '',
                'render': function(data, type, full, meta) {
                    return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                }
            }],
        });

        let sessionIds = []; // Array to store data-session-id values
        table.on('change', 'input', function() {
            let rowData = table.column(0).nodes().filter(function(value, index) {
                let inputElement = $(value).find('input');
                return inputElement.prop('checked');
            });

            sessionIds = [];
            $.each(rowData, function(index, obj) {
                let sessionId = $(obj).data('session-id');
                if (sessionId) {
                    sessionIds.push(sessionId);
                }
            });

            if (rowData.length > 0) {
                $('#draggable').addClass('anim').removeClass('reverse-anim');
            } else {
                $('#draggable').addClass('reverse-anim').removeClass('anim');
            }

            const dotsWrapper = $('.dots-wrapper');
            dotsWrapper.empty();

            for (let i = 0; i < rowData.length; i++) {
                dotsWrapper.append('<span class="dot" style="width:8px;height:8px;"></span>')
            }

            $('#count').text(rowData.length);
        });

        $('#delete-btn').on('click', function() {
            $('#sessionid-input').val(sessionIds);
            $('#delete-form').submit();
        });
    });

    $(document).ready(function() {
        const dragBtn = document.querySelector('.drag-btn');
        const draggable = document.getElementById('draggable');

        let posX = 0,
            posY = 0,
            mouseX = 0,
            mouseY = 0;

        dragBtn.addEventListener('mousedown', mouseDown, false);
        window.addEventListener('mouseup', mouseUp, false);

        function mouseDown(e) {
            e.preventDefault();
            posX = e.clientX - draggable.offsetLeft;
            posY = e.clientY - draggable.offsetTop;
            window.addEventListener('mousemove', moveElement, false);
        }

        function mouseUp() {
            window.removeEventListener('mousemove', moveElement, false);
        }

        function moveElement(e) {
            mouseX = e.clientX - posX;
            mouseY = e.clientY - posY;

            const maxX = 1000;
            const maxY = window.innerHeight - draggable.offsetHeight;

            mouseX = Math.min(Math.max(mouseX, 0), maxX);
            mouseY = Math.min(Math.max(mouseY, 0), maxY);
            draggable.style.left = mouseX + 'px';
            draggable.style.top = mouseY + 'px';
        }
    });
</script>

@endsection
