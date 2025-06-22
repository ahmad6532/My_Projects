@extends('layouts.location_app')
@section('title', 'Root cause analysis request')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page">Root cause analysis requests</li>
    </ol>
</nav>

@include('layouts.error')
@section('styles')
<link href="{{asset('admin_assets/css/fish-bone-model.css')}}" rel="stylesheet"/>
@endsection
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <div class="float-left">
                <h4 class="text-info font-weight-bold">Requests</h4>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Requested By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$record->created_at->format(config('app.dateFormat'))}}</td>
                        <td>{{$record->location->head_office_location->head_office->name()}}</td>
                        <td>
                            @if(isset($is_five_whys))
                                
                            <a href="{{route('be_spoke_forms.be_spoke_form.record.preview', $record->id)}}">Five
                                Whys</a>
                            @endif
                            @if(isset($is_fish_bone))
                            <a href="{{route('location.root_cause_analysis.fish_bone', $record->id)}}">Fish
                                Bone</a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

</div>
@section('scripts')
<script>
    jQuery(document).on('click','.organisation-tag, .btn-level ',function(e){
        // Prevent child clicks hidding the modal
        if (e.target !== this){
            //return;
        }
        jQuery('.action-bar').not(jQuery(this).find('.action-bar')).removeClass('show');
        jQuery(this).find('.action-bar').first().toggleClass('show');

    });
</script>
@endsection
@endsection