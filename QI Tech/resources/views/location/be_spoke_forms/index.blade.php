@extends('layouts.location_app')
@section('title', 'Be Spoke Forms dashboard')
@section('content')
    <div id="content">
        <div >
            <div class="headingWithSearch">
        
                <div class="heading-center">
                    Bespoke Forms Templates
                </div>
            </div>
        @if(request()->query('success'))
            <div class="alert to_hide_10 alert-success w-50" style="margin:0 auto">
                {{request()->query('success')}} 
                <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
            </div>
        @endif
        @if(request()->query('error'))
            <div class="alert to_hide_10 alert-danger w-50" style="margin:0 auto">
                {{request()->query('error')}} 
                <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
            </div>
        @endif

            {{-- <div class="mb-3">
                <div class="btn-group btn-group-sm float-right" role="group">
                    @if(Auth::guard('location')->user()->userCanUpdateSettings())
                    <a href="{{route('be_spoke_forms_templates.form_template')}}" class="btn btn-info" title="Create New Be Spoke Form">
                        <span class="fas fa-plus" aria-hidden="true"></span>
                    </a>
                    @endif
                </div>

            </div> --}}

            @if(count($beSpokeForms) == 0)
                <h4 class="text-info text-center">No Forms Available.</h4>
            @else
                <div class="table-responsive">
                    <table class="table table-striped" id="dataTable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            
                            <th>Category</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(!Auth::guard('location')->user()->userCanUpdateSettings())
                            @foreach($beSpokeForms as $beSpokeForm)
                            <tr>
                                <td>{{$beSpokeForm->form->name}}</td>
                                <td>{{$beSpokeForm->form->category->name}}</td>
                                <td>{{$beSpokeForm->form->type}}</td>
                                <td>
                                    @if($beSpokeForm->form->is_active)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                                </td>
                                <td>
                                    <a class="text-info" href="{{route('be_spoke_forms_templates.form_template', $beSpokeForm->form->id)}}">Edit</a> | 
                                    <a class="text-info" href="{{route('be_spoke_forms.be_spoke_form.preview', $beSpokeForm->form->id)}}">Preview</a> |
                                    @if(!count($beSpokeForm->form->records))<a class="text-info delete_form" href="{{route('be_spoke_forms.be_spoke_form.delete', ['id'=>$beSpokeForm->form->id,'_token'=>csrf_token()])}}">Delete</a> |@endif 
                                    <a class="text-info" href="{{route('be_spoke_forms.be_spoke_form.active', ['id'=>$beSpokeForm->form->id,'_token'=>csrf_token()])}}">{{$beSpokeForm->form->active}}</a> |
                                    <a class="text-info" href="{{route('be_spoke_forms.be_spoke_form.records', $beSpokeForm->form->id)}}">Records</a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            {{-- @else --}}
                            {{-- @foreach($beSpokeForms as $beSpokeForm)
                            <tr>
                                <td>{{$beSpokeForm->name}}</td>
                                <td>{{$beSpokeForm->category->name}}</td>
                                <td>{{$beSpokeForm->type}}</td>
                                <td>
                                    @if($beSpokeForm->is_active)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                                </td>
                                <td>
                                    <a class="text-info" href="{{route('be_spoke_forms_templates.form_template', $beSpokeForm->id)}}">Edit</a> | 
                                    <a class="text-info" href="{{route('be_spoke_forms.be_spoke_form.preview', $beSpokeForm->id)}}">Preview</a> |
                                    @if(!count($beSpokeForm->records))<a class="text-info delete_form" href="{{route('be_spoke_forms.be_spoke_form.delete', $beSpokeForm->id)}}">Delete</a> |@endif 
                                    <a class="text-info" href="{{route('be_spoke_forms.be_spoke_form.active', $beSpokeForm->id)}}">{{$beSpokeForm->active}}</a> |
                                    <a class="text-info" href="{{route('be_spoke_forms.be_spoke_form.records', $beSpokeForm->id)}}">Records</a>
                                </td>
                            </tr>
                            @endforeach --}}
                            {{-- @endif --}}
                        </tbody>
                    </table>

                </div>
        </div>

        {{--        <div class="card-footer"> --}}
        {{--            {!! $headOffices->render() !!} --}}
        {{--        </div> --}}

        @endif

    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('js/alertify.min.js')}}"></script>
@include('location.be_spoke_forms.script')
@endsection