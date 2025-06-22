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

        @if(count($requests) == 0)
        <h5 class="text-info text-center">No Record Available</h5>
        @else
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
                    @foreach($requests as $request)
                    <tr>
                        <td>{{$request->created_at->format(config('app.dateFormat'))}}</td>
                        <td>{{$request->head_office->name()}}</td>
                        <td>
                            @if($request->rca_type == 'five_whys')
                            <a href="{{route('be_spoke_forms.be_spoke_form.record.preview', $request->id)}}">Five
                                Whys</a>
                            @else
                            <a href="{{route('be_spoke_forms.be_spoke_form.record.preview', $request->id)}}">Fish
                                Bone</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="col-12 fish-bone-model">

            <div class="header-area">
                <div class="p1">Cause</div>
                <div class="p2">Effect</div>
            </div>

            <div class="model-area p1">
                <div class="centeral-line fish-bone-border-color"></div>

                <!-- ider foreach -->
                <div class="side-line fish-bone-border-color"><a href="javascript:void(0)" data-content="I am second"></a></div>
                <!-- ider khatam --> wait mai roti khaa lewan.

                <div class="side-line fish-bone-border-color"><a href="javascript:void(0)" data-content="Hi m third and so on"></a></div>
                <div class="side-line fish-bone-border-color"><a href="javascript:void(0)"></a></div>
                <div class="side-line fish-bone-border-color"><a href="javascript:void(0)"></a></div>
                <div class="side-line fish-bone-border-color"><a href="javascript:void(0)"></a></div>
                <div class="side-line fish-bone-border-color"><a href="javascript:void(0)"></a></div>
                <div class="side-line fish-bone-border-color"><a href="javascript:void(0)"></a></div>
                <div class="side-line fish-bone-border-color"><a href="javascript:void(0)"></a></div>
                <div class="side-line fish-bone-border-color"><a href="javascript:void(0)"></a></div>

            </div>
            
            <div class="reason-area p2">
                <textarea spellcheck="true"  class="text-box form-control" id="reason-text-area" placeholder="Enter a text here...."></textarea>
            </div>
            
        </div>
        <div style="width: 300px; margin:auto">
            <button class="btn btn-info">Start</button>
            <button class="btn btn-info">Edit</button>
            <button class="btn btn-info">Worked Example</button>
        </div>
    </div>
    <div class="modal fade" id="level_action_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    @endif

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
<script>
    $('.model-area a').on('click', function(e) {
        $('#reason-text-area').text($(this).attr('data-content'));
    });
    </script>
@endsection
@endsection