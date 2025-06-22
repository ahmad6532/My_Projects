@extends('layouts.users_app')
@section('title', 'user request')
@section('sidebar')
@include('layouts.user.sidebar-header')
@endsection
@section('content')
<div class="profile-center-area">
    <div style="display: flex; justify-content: center; align-items: center; margin-top:-42px">
        <div class="content-page-heading">
            Information Request {{$request->case->id}}
        </div>
        <div style="position: absolute;left: 40px;" class="search">
            <input type="search" placeholder="Search" />
            <i style="margin-left: -25px; color: #777;" class="fa fa-search icon"></i>
        </div>

    </div>
    <div class="container-fluid mt-5">
        <h4>Hello</h4>
        <p>We need your account of events regarding an incident that was reported</p>
                @foreach($request->case->link_case_with_form->form->stages as $key=>$stage)
            
                    <div class="card stages stage_{{$stage->id}} stage_data_{{$key+1}}" >
                        
                        <div class="card-body">
                            <h5>{{$stage->stage_name}}</h5>
                            @foreach($stage->groups as $group)
                            <div class=" group group_{{$group->id}}">
                                <div class="organisation-structure-add-content">
                                    <label class="inputSection form-group-name">{{$group->group_name}}</label>
                                    <div class="row">
                                        @foreach($group->questions as $question)
                                        @php $value = $request->case->link_case_with_form->data->where('question_id',$question->id)->first() ; @endphp
                                        
                                        @if($value && $value->radact)
                                        <div class="col-md-6">
                                            <div class="question_{{$question->id}}">
                                                <label class="inputGroup" for="question_{{$question->id}}">
                                                    {{$question->question_title}}
                                                    <input type="text" readonly  value="{{$value->question_value}}" title="" />
                                                </label>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- @foreach($case->link_case_with_form->data as $data)
                @if($data->question)
                <p>
                    <span class="detail-title"> {{$data->question->question_name}}: </span>
                    {{$data->question_value}}
                </p>
                <br>
                @endif
                @endforeach --}}
                @if ($request->note)
                <div class="organisation-structure-add-content">
                    <label for="" class="inputGroup">Note:
                        <input type="text" readonly value="{{$request->note}}">
                    </label>
                </div>
                @endif
                <div class="table-responsive">
                    @if ($request->status == 0)
                    <form method="post" class="organisation-structure-add-content" action="{{route('user.statement.single_statement_update',[$request->id,0])}}">
                        @csrf
                        @foreach ($request->questions as $question)
                            <label class="inputGroup" for="answer_{{$question->id}}">{{$question->question}}
                            <textarea spellcheck="true"  class="form-control" required name="answer_{{$question->id}}"></textarea>
                            </label>
                        @endforeach
                        <div class="uploaded_files mt-2 mb-2">
                            <input type="file" name="file" multiple value=""
                                class="form-control commentMultipleFiles">
                        </div>
                        <label class="inputGroup" for="note" >Note
                            <textarea spellcheck="true"  class="form-control" name="note"></textarea>
                        </label>
                        <br>
                        <div class="from-group">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#submit_by_phone"
                                class="btn btn-warning">Submit By Phone</a>
                        </div>
                    </form>
                    @else
                    @foreach ($request->questions as $question)
                        <b>{{$question->question}}</b>
                        <p>{{$question->answer}}</p>
                    @endforeach
                    <ul>
                        @foreach($request->documents as $doc)
                            <li>
                                <input type='hidden' name='documents[]' class='file document'
                                    value='{{$doc->document->document->unique_id}}'>
                                <span class="fa fa-file"></span>&nbsp;{{$doc->document->document->original_file_name()}}
                                <a href="{{route('user.view.attachment', $doc->document->document->unique_id).$doc->document->document->extension()}}"
                                    target="_blank" title='Preview' class="preview_btn"> <span class="fa fa-eye"></span></a>
                                <a href="{{route('user.view.remove_item', $doc->document->document->unique_id)}}" title='Delete File' class="remove_btn"> <span class="fa fa-times"></span></a>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
    </div>
    

</div>
<div class="modal fade file_upload_model" id="submit_by_phone" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    <p class="text-success"><i class="fa fa-phone"></i></p>Give Account Over the Phone
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post"
                action="{{route('user.statement.single_statement_update',[$request->id,1])}}">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="note" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Phone Number</label>
                        <input type="text" name="confirm_note" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

    @section('scripts')
    <script>

        function removeFile()
        function uploadDocumentCaseManager(files, form = false){
    var url = '/user/document/upload/hashed';
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
                var input = "<input type='hidden' name='documents[]' class='file document' value='"+data.id+"'>";
                //$('.item_'+number).append(input);
                $('.item_'+number+":last").append(input);
                var a = $('.item_'+number+":last").find('.remove_btn');
                //$(a).prop('data-route',data.route)
            }catch(e){
                console.log(e);
            }
            
        });
    }
}
jQuery(document).on('change','.commentMultipleFiles',function(e){
        e = e.originalEvent;
        var files = e.target.files;
        var form = $(this).closest('form');
        uploadDocumentCaseManager(files,form);
    });
    </script>
    @endsection
    @endsection