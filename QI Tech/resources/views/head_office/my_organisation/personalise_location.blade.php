@extends('layouts.head_office_app')
@section('title', $location->trading_name.' Colour branding')
@section('top-nav-title', 'Colour branding')
@section('sidebar')
@include('layouts.company.sidebar')
@endsection
@section('content')
<div id="content">
    <style>
        .form-group > .form-control:focus, .form-group > .dataTable-input:focus, .form-group > .form-control:not(:placeholder-shown), .form-group > .dataTable-input:not(:placeholder-shown) {
    padding: 6px;
}
    </style>
    <div class="container-fluid">

        <div class="row justify-content-center ">
            <div class="col-md-12 mb-1">
                <div class="card vh-75 ">
                    <div class="card-body">
                        <h3 class="text-info h3 font-weight-bold">Colour Branding</h3>
                        <form action="{{ route('head_office.color_branding',$location->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex flex-column">
                                <label for="color_scheme">Color Scheme</label>
                                <input type="color" id="color_scheme" class="" value="{{ $location->bg_color_code }}" name="bg_color_code" title="Color Scheme" required>
                            </div>
                            <div class="form-group">
                                <label for="logo">Upload Logo (Leave Blank to keep unchanged)</label>
                                <input type="file" id="logo" name="logo_file" class="form-control" title="logo">
                            </div>
                            <div class="form-group">
                                <label for="font">Select Font</label>
                                <select name="font" id="font" class="form-control" name="font">
                                    <option>Arial</option>
                                    <option>sans-serif</option>
                                    <option>Times New Roman</option>
                                    <option>fantasy</option>
                                    <option>Montserrat</option>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="background">Upload User Login page background (Leave Blank to remove)</label>
                                <input type="file" id="background" name="bg_file" class="form-control" title="background">
                            </div>

                            <div class="form-group">
                                <button class="primary-btn d-inline" type="submit" name="submit">Update</button>
                                <button class="primary-btn d-inline" type="submit" name="preview_btn" value="preview">Preview</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection