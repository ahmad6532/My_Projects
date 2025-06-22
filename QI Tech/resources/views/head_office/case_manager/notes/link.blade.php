<div>
    <div class="  p-0" style="position:absolute;right:10px;top:60px;">
        @if($case->status == 'open' && $case->isArchived == false)
        <a href="#" data-bs-toggle="modal" data-bs-target="#default_links_form"  class="btn btn-outline-cirlce bg-white"><i class="fa fa-plus"></i></a>
        @endif
    </div>
    <p style="color: #51a9a3;" class="fw-bold mb-1">Case links</p>
    <div  class="mb-2">
        @foreach ($case->link_case_with_form->form->defaultLinks as $defaultLink)
        @if ($defaultLink->is_active)
        <a href="{{ strpos($defaultLink->link, 'http') === 0 ? $defaultLink->link : 'http://' . $defaultLink->link }}" target="_blank">
            <div class="shadow p-2 pl-4 shadow-custom border cus-wrap mb-1" style="padding-left:1rem !important;border-radius:4px;background:linear-gradient(90deg, rgb(129, 157, 166) 0.8%, rgba(255,255,255,1) 0.8%);">
                <p class="fw-bold mb-1 text-black" style="font-size: 18px;">{{$defaultLink->title}}</p>
                <p class="fw-bold text-body-tertiary mb-0" style="font-size: 14px;">{{$defaultLink->link_description}}</p>
            </div>
        </a>
        @endif
            
        @endforeach
        
    </div>
</div>
@if(count($case->system_links) == 0)
<p style="color: #51a9a3;" class="fw-bold mb-1 mt-4">In case log</p> 
<p class="font-italic">There are no links in case log!</p> 
@else


<div class="mb-2 cm_case_task_wrapper">

    {{-- <nav class="nav nav-tabs nav-h-bordered">
        <a href="#" class="active" data-bs-toggle="tab" data-bs-target="#defualt_links"><span
                class="item_with_border">Default Links</span> <span
                class="badge badge-danger">{{$tasks->where('is_default_task',1)->count()}}</span></a>
        <a href="#" data-bs-toggle="tab" data-bs-target="#uploaded_links"><span class="item_with_border">Uploaded
                Links</span><span class="badge badge-danger">{{$tasks->where('is_default_task',0)->count()}}</span></a>
    </nav> --}}
    <p style="color: #51a9a3;" class="fw-bold mb-1 mt-5">In Case log</p>
<div class="tab-content" id="myTabContent">
    <style>
        .cus-wrap a{
            font-weight: bold;
            color: black;
            font-size: 16px;
        }
    </style>
        @foreach($case->system_links as $key=>$link)
        <div id="hidden-table-{{$key}}" style="display: none;">
            <table class="table">
                <thead>
                    <th>Name</th>
                    <th>Accessed</th>
                </thead>
                <tbody>
                    @foreach ($link->link_access_log as $logs)
                        <tr>
                            <td>{{$logs->user->name}}</td>
                            <td>{{$logs->created_at->format('d M Y (D) h:i a')}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{$link->link.'/'.'random_link'.'/'.$link->random}}" target="_blank">
            <div class="shadow p-2 pl-4 shadow-custom border cus-wrap mt-2" style="min-height:50px;padding-left:1rem !important;border-radius:4px;background:linear-gradient(90deg, rgb(129, 157, 166) 0.8%, rgba(255,255,255,1) 0.8%);">
                <button onclick="showCustomAlert(event,{{$key}})" class="btn text-info" style="position: absolute;right:20%;top:0;z-index:999;"><i class="fa-solid fa-clock-rotate-left"></i></button>
                <p class="fw-bold mb-1 text-black" style="font-size: 18px;">{{empty($link->title) ? '(no title)' : $link->title}}</p>
                <p class="fw-bold text-body-tertiary mb-0" style="font-size: 14px;">{{$link->description}}</p>
            </div>
        </a>
        
    
        @endforeach
</div>

<script>
    function showCustomAlert(event,key) {
        event.preventDefault();
        var tableDiv = document.getElementById('hidden-table-' + key);
        var tableHtml = tableDiv.innerHTML;
        var wrappedTableHtml = '<div style="margin: 20px;">' + tableHtml + '</div>';

        // Custom alert with Alertify.js
        alertify.alert('Last Accessed', wrappedTableHtml)
            .set('resizable', true)
            .resizeTo('auto', 'auto');
    }
</script>
    
</div>  


@endif

@include('head_office.be_spoke_forms.default_links', ['document' => null,'form'=>$case->link_case_with_form->form])