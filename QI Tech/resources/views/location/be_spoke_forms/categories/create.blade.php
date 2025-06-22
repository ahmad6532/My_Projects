@extends('layouts.location_app')
@section('title', 'Bespoke Form Category')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('location.be_spoke_form_category.index')}}">Bespoke Form Categories</a></li>
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
            action="{{route('location.be_spoke_form_category.store',$category ? $category->id : '')}}">
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