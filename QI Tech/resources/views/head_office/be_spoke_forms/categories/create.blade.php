@extends('layouts.head_office_app')
@section('title', 'Bespoke Form Category Template')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('be_spoke_form_categories.be_spoke_form_category.index')}}">Bespoke Form Categories</a></li>
        <li class="breadcrumb-item active" aria-current="page">@if(isset($category)){{substr($category->name,0,30)}} @else New
            Bespoke Form Category @endif</li>
    </ol>
</nav>
<div class="card">
    @include('layouts.error')
    <div class="card-header float-left">
        <h4 class="text-info font-weight-bold">Bespoke Form Category @if(isset($category)) - {{$category->name}} @endif</h4>
    </div>
    <div class="card-body">
        <form name="fields_form" method="post"
            action="{{route('be_spoke_form_categories.be_spoke_form_category.store',$category ? $category->id : '')}}">
            @csrf

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input class="form-control" type="text" value="@if(isset($category)){{$category->name}}@endif"
                            id="name" name="name" placeholder="Enter Form name here" required>
                    </div>
                    <button type="submit" name="submit" class="mt-3 btn btn-info">Save Form Name</button>
                </div>
                
                </div>
            </div>
            <div>
            </div>
        </form>

    </div>
    <!-- End custom design -->
</div>
<div class="card-footer text-center">
</div>

<!-- Modal -->
<div class="modal modal-md fade" id="stage_groups_model" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-2">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>



@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_assets/css/style.css')}}">
@endsection

@section('scripts')
<script src="{{asset('js/alertify.min.js')}}"></script>
@include('head_office.be_spoke_forms.script')

<script src="{{asset('admin_assets/js/form-template.js')}}"></script>
@endsection