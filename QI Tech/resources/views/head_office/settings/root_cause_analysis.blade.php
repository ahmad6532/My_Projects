<form action="{{ route('head_office.root_cause_analysis.save') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="company_name">Fish Bone?</label>
        <input type="checkbox" id="is_fish_bone" class="form-control" value="1" @if($headOffice->is_fish_bone) checked @endif name="is_fish_bone" placeholder="Is fish bone" >
    </div>
    <div class="form-group">
        <label for="is_fish_bone_compulsory">Fish Bone Compulsory?</label>
        <input type="checkbox" id="is_fish_bone_compulsory" name="is_fish_bone_compulsory" value="1" @if($headOffice->is_fish_bone_compulsory) checked @endif class="form-control" placeholder="Is fish bone compulsory?" >
    </div>
    <div class="form-group">
        <label for="is_five_whys">Five Ways?</label>
        <input type="checkbox" id="is_five_whys" name="is_five_whys" value="1" class="form-control" @if($headOffice->is_five_whys) checked @endif placeholder="is five whys?" >
    </div>
    <div class="form-group">
        <label for="is_five_whys_compulsory">Five Ways Compulsory?</label>
        <input type="checkbox" id="is_five_whys_compulsory" name="is_five_whys_compulsory" value="1" @if($headOffice->is_five_whys_compulsory) checked @endif class="form-control" placeholder="is five whys compulsory?" >
    </div>
    <div class="form-group">
        <button class="btn btn-info" type="submit" name="submit">Update</button>
    </div>
</form>


<div class="row justify-content-center default_questinos">
    <div class="col-md-12 mb-1">
        <div class="card vh-75 ">
            <div class="card-body">


                {{-- <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="fish_bone_tab" data-toggle="tab"
                                data-target="#fish_bone" type="button" role="tab"
                                aria-controls="fish_bone" aria-selected="true">Fish Bone
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="five_whys_tab" data-toggle="tab" data-target="#five_whys"
                                type="button" role="tab" aria-controls="five_whys" aria-selected="false">Five Why's
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="fish_bone" role="tabpanel" aria-labelledby="fish_bone-tab">
                        <div class="row">
                            <div class="col-md-12"> --}}
                                <h3 class="text-info h3 font-weight-bold">Fish Bone<a style="float: right !important;" href="#"
                                    data-toggle="modal" data-target="#default_fish_bone_questions"
                                    class="btn btn-info">Add Fish BOne</a></h3>
                                <table border="0" id="scheduleTable" class="table table-responsive table_full_width">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Questions</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    @foreach ($headOffice->fish_bone_questions as $question)
                                        
                                    
                                        <tr>
                                            <td>
                                                {{$loop->iteration}}
                                            </td>
                                            <td>
                                                {{$question->question}}
                                            </td>
                                            <td>
                                                <div class="btn-group right">
                                                <a style="float: right !important;" href="#"
                                    data-toggle="modal" data-target="#default_fish_bone_questions_{{$question->id}}"
                                    class="btn btn-info">Edit question</a>
                                                <a style="float: right !important;" href="{{route('head_office.setting.default_fish_bone_question_delete',['id'=>$question->id,'_token'=>csrf_token()])}}" class="btn btn-danger delete_email" data-msg="Are you sure, you want to delete this question?" >Delete</a>
                                            </div>
                                            </td>
                                        </tr>
                                        
                                        @include('head_office.settings.default_fish_bone_questions',['question' => $question])
                                        @endforeach
                                    <tbody>
                                    </tbody>
                                </table>
{{--                                 
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="five_whys" role="tabpanel" aria-labelledby="five_whys-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-info h3 font-weight-bold">Five Why's <a style="float: right !important;" href="#"
                                    data-toggle="modal" data-target="#default_five_whys_questions"
                                    class="btn btn-info">Add Five Why's</a></h3>
                                <table border="0" id="scheduleTable" class="table table-responsive table_full_width">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Questions</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    @foreach ($headOffice->five_whys_questions as $question)
                                        
                                    
                                        <tr>
                                            <td>
                                                {{$loop->iteration}}
                                            </td>
                                            <td>
                                                {{$question->question}}
                                            </td>
                                            <td>
                                                <div class="btn-group right">
                                                <a style="float: right !important;" href="#" data-toggle="modal" data-target="#default_five_whys_questions_{{$question->id}}" class="btn btn-info">Edit question</a>
                                                <a style="float: right !important;" href="{{route('head_office.setting.default_five_whys_question_delete',$question->id)}}" class="btn btn-danger delete_email" data-msg="Are you sure, you want to delete this question?" >Delete</a>
                                            </div>
                                            </td>
                                        </tr>
                                        
                                        @include('head_office.settings.default_five_whys_questions',['question' => $question])
                                        @endforeach
                                    <tbody>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div> --}}






                
            </div>
        </div>
    </div>
</div>

@include('head_office.settings.default_fish_bone_questions',['question' => null])

@include('head_office.settings.default_five_whys_questions',['question' => null])


