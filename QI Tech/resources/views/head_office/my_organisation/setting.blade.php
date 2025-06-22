@extends('layouts.head_office_app')
@section('title', 'Assign Setting to Organisation')
@section('sidebar')
@include('layouts.company.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="content-page-heading">
        Update Setting For Location {{$location->location->trading_name}} 
    </div>
    @include('layouts.error')
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Color</th>
                    <th>Logo</th>
                    <th>Background Logo</th>
                    <th>Font</th>
                    <th>Bespoke Forms</th>
                </tr>
            </thead>
            <form method="post" action="{{ route('head_office.organisation.assign_setting_save',$id) }}">
                @csrf
            <tbody>
                @if(count($head_office_organisation_settings) <= 0) <tr>
                    <td colspan="7" class="font-italic">You have no saves settings.</td>
                    </tr>
                    @else
                    @foreach($head_office_organisation_settings as $head_office_organisation_setting)
                    <tr>
                            <td><input type="radio" name="organisation_setting_id" value="{{$head_office_organisation_setting->id}}"></td>

                        <td>{{$head_office_organisation_setting->name}}</td>
                        <td>
                            <span class="bg-color-tile" style="background-color: {{$head_office_organisation_setting->bg_color_code}}"></span>
                            </td>
                        <td>
                            {!! $head_office_organisation_setting->organisation_setting_logo() !!}
                        </td>
                        <td>
                            {!! $head_office_organisation_setting->organisation_setting_bg_logo() !!}
                        </td>
                        <td>
                            {!! $head_office_organisation_setting->font !!}
                        </td>
                        <td>
                            {{count($head_office_organisation_setting->organisationSettingBespokeForms)}}
                        </td>
                        
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7">
                            <button class="btn btn-sm btn-info">Update</button>
                        </td>
                    </tr>
                    @endif

            </tbody>
        </form>
        </table>
    <div> {!! $head_office_organisation_settings->render('pagination::bootstrap-5') !!}</div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')
<script src="{{asset('js/alertify.min.js')}}"></script>
@endsection