@extends('layouts.head_office_app')
@section('title', 'My Organisation')
@section('top-nav-title', 'Colour branding')
@section('content')
<div id="content">
    <div class="content-page-heading">
        @if(!$setting)Create Organisation Setting @else Update
        '{{$setting->name}}' Setting @endif
    </div>
    @include('layouts.error')
    <form
        action="{{ $setting ? route('organisation_settings.organisation_setting.update',$setting->id) : route('organisation_settings.organisation_setting.store') }}"
        _target="blank" method="{{$setting ? 'POST' : 'POST'}}" enctype="multipart/form-data">
        @csrf
        {!! $setting ? '<input name="_method" type="hidden" value="PUT">' : '' !!}

        <div class="user-page-contents hide-placeholder-parent">

            <label for="name" class="inputGroup">Name :
                <input type="text" id="name" name="name" value="{{old('name', optional($setting)->name)}}" placeholder="Enter Name"
                    class="" title="enter setting name">
            </label>

            <label for="bg_color_code" class="inputGroup">Color Scheme :
                <input type="color" id="bg_color_code" class=""
                    value="{{ old('bg_color_code',optional($setting)->bg_color_code) }}" name="bg_color_code"
                    title="Color Scheme" required>
            </label>

            <label for="logo" class="inputGroup">Upload Logo (Leave Blank to keep unchanged) :
                <input type="file" id="logo" value="{{old('logo_file')}}" name="logo_file" class=""
                    title="logo">
            </label>

            <label for="font" class="inputGroup">Select Font :
                <select name="font" id="font" class="" name="font">
                    <option @if($setting && $setting->font == 'Arial') selecled @endif>Arial</option>
                    <option @if($setting && $setting->font == 'sans-serif') selecled @endif>sans-serif
                    </option>
                    <option @if($setting && $setting->font == 'Times New Roman') selecled @endif>Times New
                        Roman</option>
                    <option @if($setting && $setting->font == 'fantasy') selecled @endif>fantasy</option>
                    <option @if($setting && $setting->font == 'Montserrat') selecled @endif>Montserrat
                    </option>

                </select>
            </label>
            <label for="background" class="inputGroup">Upload User Login page background (Leave Blank to remove) :
                <input type="file" id="background" name="bg_file" value="{{old('bg_file')}}" class=""
                    title="background">

            </label>
            <div class="table-responsive">
                <table class="table table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <td></td>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $beSpokeForm)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{$beSpokeForm->id}}" multiple="multiple"
                                    @php echo in_array($beSpokeForm->id,$ids) ? 'checked' : '' @endphp></td>
                            <td>{{$beSpokeForm->name}}</td>
                            <td>{{$beSpokeForm->type}}</td>
                            <td>
                                @if($beSpokeForm->is_active)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <button class="btn btn-info" type="submit" name="submit">Save</button>
        </div>
    </form>
</div>
@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')

<script src="{{asset('js/alertify.min.js')}}"></script>


@endsection


@endsection