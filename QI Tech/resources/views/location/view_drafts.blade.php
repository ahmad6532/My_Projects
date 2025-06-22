@extends('layouts.location_app')
@section('title', 'Near Misses')
@section('content')
<div id="content" >
    <div class="headingWithSearch flex-column " >
        <style>
            .dt-search input{
                padding-left:25px !important;
            }
            .tooltip .tooltip-inner{
  max-width: fit-content !important;
  width: fit-content !important;
}
        </style>
        <div class="heading-center" style="padding-top: 20px ">
            Your Drafts <i class="fa fa-info-circle" title="Your drafts are only visible to you!" data-toggle="tooltip" data-bs-placement="right"></i>
        </div>
        <style>
            #session-dataTable_filter:after, .dt-search:after {
            left:10px !important;
        }
        </style>
        <div>
            @if($drafts->isEmpty())
                <p class="text-left">You do not have any drafts saved.</p>
            @else
                <table id="blockUser-table" class="table table-responsive table-bordered mx-auto dataTable w-100 new-table">
                    <thead class="text-center">
                        <tr>
                            {{-- <th class="text-center">User Name</th> --}}
                            <th style="text-align: center">Form</th>
                            <th class="text-center">Saved</th>
                            <th style="text-align: center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="all_locations text-center">
                        @foreach($drafts as $draft)
                            <tr>
                                {{-- <td class="fw-semibold email">{{$draft->user->name}}</td> --}}
                                <td class="position" style="">
                                    {{$draft->form->name}} 
                                    <p data-toggle="tooltip" data-placement="top" title="{{$draft->form->allow_drafts_off_site ? 'This form can be completed off site by signing into your User Account' : 'You cannot complete this draft offsite.'}}" class="badge bg-{{$draft->form->allow_drafts_off_site ? 'info' : ''}} m-0">
                                        {{$draft->form->allow_drafts_off_site ? 'Off-site' : ''}}
                                    </p>
                                </td>
                                <td class="fw-semibold email">{{$draft->created_at->format('D d/m/Y g:ia')}} <br> {{$draft->created_at->diffForHumans()}}</td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center align-items-center mx-auto" style="width: fit-content;">
                                        <a href="#" data-link="{{route('location.delete_drafts', ['id' => $draft->id, '_token' => csrf_token()])}}" type="button" class="btn p-0 px-2 delete-btn" title="Delete Draft">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </a>
                                        <a href="{{url('/bespoke_form_v3/#!/submit-draft/' . $draft->id)}}" type="button" class="btn p-0 px-2" title="Continue Draft">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
    </div>
    
</div>
@endsection

@section('scripts')

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script> --}}
<script>
 const dataTable =  $('#blockUser-table').DataTable({
    paging: false,
    info: false,
    language: {
        search: ""
    },
});

$('.delete-btn').on('click',function(event){
    event.preventDefault();
    let link = $(this).data('link');
    alertify.defaults.glossary.title = 'Alert!';
    alertify.confirm('Delete Draft', 'Are you sure you want to delete this draft!', 
        function() { 
            alertify.success('Confirmed'); 
            window.location.href = link;
        },
        function() { 
            alertify.error('Cancelled'); 
        })
        .set({labels:{ok:'Confirm', cancel:'Cancel'}});
})
</script>
@endsection