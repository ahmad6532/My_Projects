{{-- @extends('layouts.head_office_app')
@section('title', 'Be Spoke Form Record')
@section('top-nav-title', 'Be Spoke Form Preview')

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('be_spoke_forms.be_spoke_form.index')}}">Bespoke Forms</a></li>
    <li class="breadcrumb-item" aria-current="page">Records</li>
</ol>
</nav>

    @include('layouts.error')

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <div class="float-left">
                    <h4 class="text-info font-weight-bold">{{$form->name}} Records</h4>
                </div>
            </div>

            @if(count($form->records) == 0)
                <h5 class="text-info text-center">No Record Available</h5>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($form->records as $record)
                            <tr>
                                <td>{{$record->createdDate()}}</td>
                                <td>{{$record->location->name()}}</td>
                                <td> 
                                    <a href="{{route('be_spoke_forms.be_spoke_form.record.preview', $record->id)}}">Preview</a>
                                    <!-- <a href="{{route('be_spoke_forms.be_spoke_form.record.preview', $record->id)}}">Delete</a> -->
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
        </div>
        @endif

    </div>
@endsection --}}



@extends('layouts.head_office_app')
@section('title', 'Be Spoke Form Records')

@section('content')
<div id="content">
    <div class="headingWithSearch">
        
        <div class="heading-center">
            {{$form->name}} Records
        </div>
    </div>
    <form method="get" class="form search-form print-display-none" style="margin-top: -78px;">
        <div class="input-group form-group mb-3 search-wrapper">
            <div class="form-group-search">
                <input type="text" class="form-control search-nearmiss" name="search" @if(request()->query('search'))
                value="{{request()->query('search')}}" @endif>
            </div>
            <div class="form-group-search">
                <input type="text" name="start_date" class="datepicker form-control" @if(request()->query('start_date'))
                value="{{request()->query('start_date')}}" @else value="{{date('d/m/Y', strtotime('-1 week'))}}" @endif>
            </div>
            <div class="form-group-search">
                <input type="text" name="end_date" class="datepicker form-control" @if(request()->query('end_date'))
                value="{{request()->query('end_date')}}" @else value="{{date('d/m/Y')}}" @endif>
            </div>
            @if(request()->query('format'))
            <input type="hidden" name="format" value="{{request()->query('format')}}">
            @endif
            <button type="submit" class="btn btn-info search_button"><i class="fa fa-search"></i></button>
        </div>
        

    </form>
    
    {{-- <div class="btn-group btn-group-sm float-right" role="group" style="margin-top: -51px">
        @if(request()->query('format') == 'table')
        <a href="{{route('be_spoke_forms.be_spoke_form.records',['hide'=>'deleted'])}}" class="btn btn-info"
            title=" Hide Deleted">
            <i class="fas fa-eye-slash"></i>
        </a>
        @else
        <a href="#" class="btn btn-info btn-toggle-delete" title="Show Deleted" data-show-title="Show Deleted"
            data-hide-title="Hide Deleted">
            <i class="fas fa-eye-slash"></i>
        </a>
        @endif
        <a href="{{route('be_spoke_forms.be_spoke_form.records')}}" class="btn btn-info" title="QR Code" target="_blank">
            <i class="fa fa-qrcode"></i>
        </a>
        <a href="{{route('be_spoke_forms.be_spoke_form.records',['format'=>'timeline'])}}" class="btn btn-info"
            title=" View as Timeline">
            <i class="fa fa-th" aria-hidden="true"></i>
        </a>
        <a href="{{route('be_spoke_forms.be_spoke_form.records',['format'=>'table'])}}" class="btn btn-info"
            title=" View as List">
            <span class="fas fa-list" aria-hidden="true"></span>
        </a>
    </div> --}}
@include('layouts.error')

        @if(!$records)
        <h5 class="text-info text-center">No Record Available</h5>
        @else
     
        <div class="timeline timeline_nearmiss">

            @include('head_office.be_spoke_forms.record_data',['counter' => 0])
            

            <div class="line line-date line-reloading print-display-none" style="display:none">
                <div class="timeline-label"><i class="spinning_icon fa-spin fa fa-spinner"></i></div>
            </div>
            <div class="line line-date last-line">
                <div class="timeline-label">Start</div>
            </div>
            <div class="account_created center">
                <h4 class="timeline_category_title">Account Created</h4>
                <p>{{date('D jS F Y',strtotime(Auth::guard('web')->user()->created_at))}}</p>
            </div>
        </div>
@endif

</div>

<style>
    .line.right-record .date {
        left: -114px !important;
    }

    .line.left-record .date {
        right: -114px !important;
    }
</style>
@section('scripts')

    <script>
         jQuery(document).on('change','.commentMultipleFiles',function(e){
            e = e.originalEvent;
            var files = e.target.files;
            var form = $(this).closest('form');
            uploadDocumentCaseManager(files,form);
        });
        function uploadDocumentCaseManager(files, form = false){
        var url = document.getElementById('route_document').value;
        for (let i = 0; i < files.length; i++) {
            let number = Math.floor(Math.random() * 1000000);
            var formData = new FormData();
            formData.append("file",  files.item(i));
            formData.append("_token", $('input[name=_token]').val());
            formData.append("type", 'case_manager');
            var progress = $([
                "<li class='item_"+number+"'><span class='fa fa-file'></span>&nbsp; "+ files.item(i).name+"<a href='#' title='Delete File' class='remove_btn'> <span class='fa fa-times'></span></a>",
                "    <div class='progress'>",
                "        <div class='progress-bar progress-bar-striped active' role='progressbar'",
                "            aria-valuenow='0' aria-valuemin='0' aria-valuemax='" + files.item(i).size + "'>",
                "            <span class='sr-only'>0%</span>",
                "        </div>",
                "    </div>",
                "</li>",
            ].join(""));

            var progress2 = $([
                "<li class='item_"+number+"'><span class='fa fa-file'></span>&nbsp; "+ files.item(i).name+"<a href='#' title='Delete File' class='remove_btn'> <span class='fa fa-times'></span></a>",
                "</li>",
            ].join(""));

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                cache: false,
                contentType: false,
                beforeSend: function () {
                    var percentage = 0;
                    if(form){
                        $(form).find(".uploaded_files").append(progress);
                        $(form).find(".uploaded_files2").append(progress2);
                    }else{
                        $(".uploaded_files").append(progress);
                        $(".uploaded_files2").append(progress2);
                    }
                    
                    $('.item_'+number+' .progress .progress-bar').css("width", percentage+'%', function() {
                        return $(this).attr("aria-valuenow", percentage) + "%";
                    });
                },
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            $('.item_'+number+' .progress .progress-bar').css("width", percentComplete+'%', function() {
                                return $(this).attr("aria-valuenow", percentComplete) + "%";
                            });
                        }
                }, false);xhr.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                            $('.item_'+number+' .progress .progress-bar').css("width", percentComplete+'%', function() {
                                return $(this).attr("aria-valuenow", percentComplete) + "%";
                            });
                    }
                }, false);
                return xhr;
                },
            }).done(function(data) {
                    $('.item_'+number+' .progress').remove();
                try{
                    var input = "<input type='hidden' name='documents[]' class='file document' id='"+data.id+"' value='"+data.id+"'>";
                    //$('.item_'+number).append(input);
                    $('.item_'+number+":last").append(input);
                }catch(e){
                    console.log(e);
                }
                
            });
        }
    }
    jQuery(document).on('click','.remove_btn',function(e){
        e.preventDefault();
        var route = $("#route_document_removedHashed").val();
        var val = $('.' + $(this).parent().attr('class'));
        var data = {
            'hashed' : $('.' + $(this).parent().attr('class')).find("input[name='documents[]']").val(),
            '_token' : "{{ csrf_token () }}"
        }
        $.post(route,data)
        .then(function(response)
        {
            val.remove();
        })
        .catch(function(error)
        {
            console.log(error);
        })
        

    });
    </script>

@endsection
@endsection