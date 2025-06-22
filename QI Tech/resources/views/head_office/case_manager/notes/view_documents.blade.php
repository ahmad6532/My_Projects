<div class="cm_case_task_add  p-0">
    @if($case->status == 'open' && $case->isArchived == false)
    <a href="#" data-bs-toggle="modal" data-bs-target="#document_form"  class="btn btn-outline-cirlce bg-white"><i class="fa fa-plus"></i></a>
    @endif
</div>

@if(!count($documents)) <p class="font-italic">There are no documents available</p> @else

{{-- <nav class="nav nav-tabs nav-h-bordered">
    <a href="#" class="active" data-bs-toggle="tab" data-bs-target="#defualt_document" ><span class="item_with_border" style="font-size: 14px;">Default Documents</span> <span class="badge badge-danger">{{count($documents->where('is_default_document',1))}}</span></a>
    <a href="#" data-bs-toggle="tab" data-bs-target="#normal_document"><span class="item_with_border" style="font-size: 14px;">Uploaded Documents</span><span class="badge badge-danger">{{count($documents->where('is_default_document',0))}}</span></a>
</nav> --}}
<div>
    <p style="color: #51a9a3;" class="fw-bold mb-1">Case Media & Documents</p>
    <div  class="mb-2">
        @foreach ($documents as $document)
        @if ($document->active && $document->is_default_document)
            <div class="shadow p-2 pl-4 shadow-custom border cus-wrap mt-2" style="padding-left:1rem !important;border-radius:4px;background:linear-gradient(90deg, rgb(129, 157, 166) 0.8%, rgba(255,255,255,1) 0.8%);">
                <div class="cm_case_task_actions dropdown no-arrow">
                    <a href="#" class="btn btn-outline-cirlc float-right" style="transform: rotateZ(90deg);font-size:20px;"  id="dropdownMenuButton_x" data-bs-toggle="dropdown">
                        <i class="fa fa-ellipsis-h"></i>
                    </a>
                    <div class="dropdown-menu  animated--fade-in" aria-labelledby="dropdownMenuButton_x">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#document_form_{{$document->id}}" class="dropdown-item">Edit Document</a>
                            <a href="{{route('case_docuemnts.case_docuemnt.case_docuemnt_delete',['id'=>$document->id,'_token'=>csrf_token()])}}" data-msg="Are you sure, you want to delete this Document?" class="dropdown-item delete_button text-danger">Delete Document</a>
                    </div>
                </div>
                <p class="fw-bold mb-1 text-black" style="font-size: 18px;">{{$document->title}}</p>
                <p class="fw-bold text-body-tertiary mb-0" style="font-size: 14px;">{{$document->description}}</p>
                @if ($document->from_case_log || $document->is_default_document && $document->uploadedByUser)
                <span class="badge text-bg-primary" style="background: #3EB9DC !important;">{{$document->uploadedByUser->first_name .' '. $document->uploadedByUser->surname}}</span>
                @endif
                <div class="cm_case_task_attachments mt-1">
                    <ul class="list-style-none p-0">
                        @foreach($document->documents as $doc)
                            <li class="relative">
                                <a class="relative @if($doc->type == 'image') cm_image_link @endif" 
                                   href="{{route('headoffice.new_view.attachment', $doc->document->unique_id).$doc->document->extension()}}" 
                                   target="_blank" 
                                   onClick="openImage(event, '{{route('headoffice.new_view.attachment', $doc->document->unique_id).$doc->document->extension()}}')">
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
                            </li>
                        @endforeach
                    </ul>
                    
                    <script>
                    function openImage(event, url) {
                        event.preventDefault();
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
                    
                        newTab.onload = function() {
                            const style = newTab.document.createElement('style');
                            style.innerHTML = `
                                body {
                                    position: relative;
                                    margin: 0;
                                    background-color: #fff;
                                }
                            `;
                            newTab.document.head.appendChild(style);
                            newTab.document.body.appendChild(downloadButton);
                        };
                    }
                    </script>
                    
                </div>
            </div>
            @include('head_office.case_manager.notes.create_documents2',['document'=>$document])
        @endif
        @endforeach
        
    </div>
</div>

<div class="mt-4">
    <p style="color: #51a9a3;" class="fw-bold mb-1">In Case Log</p>
    <div  class="mb-2">
        @if ($case->comments->every(function($comment) {
            return $comment->documents->isEmpty();
        }))

            <p>There are no files in the case log</p>
        @else
            @foreach ($case->comments as $comment)
                @if (count($comment->documents) != 0)
                    @foreach ($comment->documents as $docs)
                        <div class="shadow p-2 pl-4 shadow-custom border cus-wrap mt-2" style="padding-left:1rem !important;border-radius:4px;background:linear-gradient(90deg, rgb(129, 157, 166) 0.8%, rgba(255,255,255,1) 0.8%);">
                            <div class="cm_case_task_attachments mt-1">
                                <ul class="list-style-none p-0">
                                    <li class="relative">
                                        <a class="relative @if($docs->type == 'image') cm_image_link @endif" 
                                           href="{{ route('headoffice.new_view.attachment', $docs->document->unique_id) . $docs->document->extension() }}" 
                                           target="_blank" 
                                           onClick="openImage(event, '{{ route('headoffice.new_view.attachment', $docs->document->unique_id) . $docs->document->extension() }}')">
                                            <i class="fa fa-link"></i> {{ $docs->document->original_file_name() }}
                                            @if($docs->type == 'image')
                                                <div class="cm_image_hover">
                                                    <div class="card shadow">
                                                        <div class="card-body">
                                                            <img class="image-responsive" width="300" 
                                                                 src="{{ route('headoffice.view.attachment', $docs->document->unique_id) . $docs->document->extension() }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                                
                                <script>
                                function openImage(event, url) {
                                    event.preventDefault();
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
                                
                                    newTab.onload = function() {
                                        const style = newTab.document.createElement('style');
                                        style.innerHTML = `
                                            body {
                                                position: relative;
                                                margin: 0;
                                                background-color: #fff;
                                            }
                                        `;
                                        newTab.document.head.appendChild(style);
                                        newTab.document.body.appendChild(downloadButton);
                                    };
                                }
                                </script>
                                
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        @endif
        
        
    </div>
</div>
{{-- <div class="tab-content" id="myTabContent">
    <div id="defualt_document" class="defualt_document scrollbar_custom_green relative tab-pane show active" >
        <div class=" relative">
            @foreach($documents as $key=>$document)
            @if($document->is_default_document)
            <div class="cm_case_task_normal">
                <div class="mb-2 shadow-custom cm_case_task_wrapper rounded-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            @isset($document->uploadedByUser)
                            <p class="m-0 fw-semibold" style="font-size: 12px;">Uploaded by {{$document->uploadedByUser->first_name}} {{$document->uploadedByUser->surname}} ({{$document->created_at->format('D')}}) {{$document->created_at->format('d/m/Y g:iA')}}</p>
                            @isset($document->updatedByUser)
                            <p class="m-0 fw-semibold" style="font-size: 12px;">Last Updated {{$document->updatedByUser->first_name}} {{$document->updatedByUser->surname}} ({{$document->updated_at->format('D')}}) {{$document->updated_at->format('d/m/Y g:iA')}}</p>
                            @endisset
                            @endisset
                        </div>
                        <div class="d-flex gap-5 align-items-start">
                            <div class="live-wrapper {{$document->active ? '' : 'not-active'}}">
                                <span class="live-circle"></span>
                                {{$document->active ? 'Live' : 'Not Live'}}
                            </div>
                            <div class="cm_case_task_actions dropdown no-arrow">
                                <a href="#" class="btn btn-outline-cirlc float-right" style="transform: rotateZ(90deg);font-size:20px;"  id="dropdownMenuButton_x" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu  animated--fade-in" aria-labelledby="dropdownMenuButton_x">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#document_form_{{$document->id}}" class="dropdown-item">Edit Document</a>
                                        <a href="{{route('case_docuemnts.case_docuemnt.case_docuemnt_activate',$document->id)}}" data-msg="Are you sure, you want to {{$document->active ? 'deactivate' : 'activate'  }} this Document?" class="dropdown-item delete_button text-{{$document->active ? 'warning' : 'success'}}">{{$document->active ? 'Deactivate':'Activate' }} Document</a>
                                        <a href="{{route('case_docuemnts.case_docuemnt.case_docuemnt_delete',$document->id)}}" data-msg="Are you sure, you want to delete this Document?" class="dropdown-item delete_button text-danger">Delete Document</a>
                                    
                                       
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cm_case_task_title" style="font-size: 18px;">
                        <b>{{$document->title}}</b>
                    </div>
                    
                    <div class="cm_case_description fw-semibold">{{$document->description}}</div>
                    
                    <div class="cm_case_task_attachments mt-1">
                        <ul class="list-style-none p-0">
                        @foreach($document->documents as $doc)
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
                </div>
            @include('head_office.case_manager.notes.create_documents',['document'=>$document])
            </div>
            @endif
        @endforeach
        </div>
    </div>
    <div id="normal_document" class="tab-pane fade normal_document" >
        <div class=" relative">
            @foreach($documents as $key=>$document)
            @if(!$document->is_default_document)
            <div class="cm_case_task_normal">
                <div class="mb-2 shadow-custom cm_case_task_wrapper rounded-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            @isset($document->uploadedByUser)
                            <p class="m-0 fw-semibold" style="font-size: 12px;">Uploaded by {{$document->uploadedByUser->first_name}} {{$document->uploadedByUser->surname}} ({{$document->created_at->format('D')}}) {{$document->created_at->format('d/m/Y g:iA')}}</p>
                            @isset($document->updatedByUser)
                            <p class="m-0 fw-semibold" style="font-size: 12px;">Last Updated {{$document->updatedByUser->first_name}} {{$document->updatedByUser->surname}} ({{$document->updated_at->format('D')}}) {{$document->updated_at->format('d/m/Y g:iA')}}</p>
                            @endisset
                            @endisset
                        </div>
                        <div class="d-flex gap-5 align-items-start">
                            <div class="live-wrapper {{$document->active ? '' : 'not-active'}}">
                                <span class="live-circle"></span>
                                {{$document->active ? 'Live' : 'Not Live'}}
                            </div>
                            <div class="cm_case_task_actions dropdown no-arrow">
                                <a href="#" class="btn btn-outline-cirlc float-right" style="transform: rotateZ(90deg);font-size:20px;"  id="dropdownMenuButton_x" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu  animated--fade-in" aria-labelledby="dropdownMenuButton_x">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#document_form_{{$document->id}}" class="dropdown-item">Edit Document</a>
                                        <a href="{{route('case_docuemnts.case_docuemnt.case_docuemnt_activate',$document->id)}}" data-msg="Are you sure, you want to {{$document->active ? 'deactivate' : 'activate'  }} this Document?" class="dropdown-item delete_button text-{{$document->active ? 'warning' : 'success'}}">{{$document->active ? 'Deactivate':'Activate' }} Document</a>
                                        <a href="{{route('case_docuemnts.case_docuemnt.case_docuemnt_delete',$document->id)}}" data-msg="Are you sure, you want to delete this Document?" class="dropdown-item delete_button text-danger">Delete Document</a>
                                    
                                       
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cm_case_task_title" style="font-size: 18px;">
                        <b>{{$document->title}}</b>
                    </div>
                    
                    <div class="cm_case_description fw-semibold">{{$document->description}}</div>
                    
                    <div class="cm_case_task_attachments mt-1">
                        <ul class="list-style-none p-0">
                        @foreach($document->documents as $doc)
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
                </div>
                @include('head_office.case_manager.notes.create_documents',['document'=>$document])
            </div>
            @endif
        @endforeach
        </div>
    </div>
</div> --}}
@endif



@include('head_office.case_manager.notes.create_documents2',['document'=>null])

