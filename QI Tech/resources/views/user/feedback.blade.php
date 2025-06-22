@extends('layouts.users_app')
@section('styles')
@endsection

<style>
    .page-title {
        /*margin-top: -44px;*/
        /*margin-bottom: 34px;*/
        font-weight: 400;
        font-size: 2rem;
        /* padding-bottom: 10px; */
        /* margin:20px; */
    }
    .form-control::placeholder{
        color: #a0a0a0 !important;
    }
    .filters-wrapper{
        left: 90% !important;
    }
</style>
@php
    use App\Models\User;
@endphp

@section('content')
<div class="headingWithSearch flex-column " >
    
        
    <div class="d-flex align-items-center gap-5 mb-2">
        <h3>Feedback</h3>

        <div class="d-flex align-items-center gap-1 position-relative">
            <input type="text" id="searchInput-feedback" class="form-control form-control-sm shadow-none" placeholder="Search...">
            <button id="filters-show" class="btn btn-sm btn-light shadow-none"><i class="fa-solid fa-chevron-down"></i></button>

            <div class="filters-wrapper">
                <p>Companies</p>
                @foreach ($user->head_office_admins as $com )
                    <div class="d-flex align-items-center text-secondary gap-1">
                        <input type="checkbox" class="company-filter" value="{{$com->id}}"> {{$com->headOffice->company_name . ' (' . count($user->getCaseFeedbacks()->where('head_office_id',$com->headOffice->id)) . ')' }} 
                    </div>
                @endforeach
                <div class="mt-3 text-secondary">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" id="startDate" class="form-control form-control-sm">
                </div>
                <div class="mt-2 text-secondary">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" id="endDate" class="form-control form-control-sm">
                </div>
            </div>
        </div>
    </div>

    <style>

.headingWithSearch{
    width: 100%
}
    </style>
    <div class="px-4">
        @if (isset($feedbacks) && count($feedbacks) != 0)
            @foreach ($feedbacks as $feedback )
                @php
                    $case_ids = isset($feedback->case_ids) ? json_decode($feedback->case_ids,true) : null;
                    $feedback->mark_read = $feedback->marked_unseen == true ? false : true;
                    $feedback->save();
                @endphp
                <div class="p-4 d-flex mb-3 feedback-wrapper" data-company_id="{{$feedback->head_office_id}}"  style="border: 1px solid #dee2e6;border-radius:0.9rem;gap:5rem!important;">
                    <img style="width: 85px;height:85px;object-fit:contain;" src="{{$feedback->headOffice->getLogoAttribute()}}" alt="headoffice logo">
                    <div>
                        <p class="text-info m-0" style="font-size: 14px; margin-bottom:0.5rem;">Date</p>
                        <h6 class="m-0 " style="font-weight: 400;">{{$feedback->created_at->format('d/m/Y')}}</h6>
                    </div>
                    <div>
                        <p class="text-info m-0" style="font-size: 14px; margin-bottom:0.5rem;">Reported</p>
                        @isset($case_ids)
                            @foreach ($case_ids as $case_id )
                                @if (isset($case_id))
                                    @php
                                        $case = $headOfficeCases->where('id',$case_id)->first();
                                    @endphp
                                    <div class="d-flex flex-column mb-3" >
                                        <p class="m-0 fw-semibold" style="font-weight: 400;line-height:1.2;">{{$case->created_at->format('d/m/Y')}}</p>
                                        <p class="m-0 fw-semibold" style="font-weight: 400;line-height:1.2;">{{$case->location->trading_name}}</p>
                                        <p class="m-0 fw-semibold" style="font-weight: 400;line-height:1.2;">{{$case->location->location_code}}</p>
                                        <p class="m-0 fw-semibold" style="font-weight: 400;line-height:1.2;">{{$case->link_case_with_form->form->name}}</p>
                                    </div>
                                @endif
                            @endforeach
                        @endisset
                    </div>
                    @if($feedback->is_feedback_user)
                    <div>
                        <p class="text-info m-0" style="font-size: 14px; margin-bottom:0.5rem;">Feedback by {{User::find($feedback->feedback_by_user_id)->name}}</p>
                        <p class="m-0 fw-semibold" style="font-weight: 400;line-height:1.2;">{!! isset($feedback->feedback_user) ? $feedback->feedback_user : (User::find($feedback->feedback_by_user_id)->name.' '."did'nt provided any feedback") !!}</p>
                    </div>
                    @endif

                    <div class="mt-4" style="margin-left: auto;">
                        <a href="{{route('user.feedback_seen',[$feedback->id])}}" class="btn  btn-light d-flex align-items-center gap-2" data-toggle="tooltip" data-placement="top" title="Mark as {{ $feedback->mark_read ? 'Unseen' : 'Seen' }}">
                            <i class="fa fa-{{ $feedback->mark_read ? 'check' : 'xmark' }}" style="color: {{ $feedback->mark_read ? '#4fb53d' : '#c73232' }}"></i>
                            {{ $feedback->mark_read ? 'Seen' : 'Unseen' }}
                        </a>
                    </div>
                </div>
            @endforeach
        @else
        <!-- <p class="h5" style="font-size: 18px; font-weight: bold; color: black; text-align: left;">You have not received any feedback</p> -->

        <p style="font-size: 18px; font-weight: normal; color: black; text-align: left;">You have not received any feedback</p>
  
        @endif
    </div>
</div>



@endsection






@section('sidebar')
@include('layouts.user.sidebar-header')
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
    // Show/Hide Filters
    $('#filters-show').on('click', function () {
        $('.filters-wrapper').toggle();
    });

    // Function to Apply Filters
    function applyFilters() {
        const searchText = $('#searchInput-feedback').val().toLowerCase();
        const selectedCompanies = $('.company-filter:checked').map(function () {
            return $(this).val();
        }).get();
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        $('.feedback-wrapper').each(function () {
            const feedbackText = $(this).text().toLowerCase();
            const companyId = $(this).data('company_id');
            const feedbackDate = $(this).find('h6').text().trim(); // Assuming format 'dd/mm/yyyy'

            let show = true;

            // Filter by Search Text
            if (searchText && !feedbackText.includes(searchText)) {
                show = false;
            }

            // Filter by Company
            if (selectedCompanies.length > 0 && !selectedCompanies.includes(companyId.toString())) {
                show = false;
            }

            // Filter by Date
            if (startDate || endDate) {
                const feedbackDateObj = new Date(feedbackDate.split('/').reverse().join('-'));
                const startDateObj = startDate ? new Date(startDate) : null;
                const endDateObj = endDate ? new Date(endDate) : null;

                if ((startDateObj && feedbackDateObj < startDateObj) ||
                    (endDateObj && feedbackDateObj > endDateObj)) {
                    show = false;
                }
            }

            // Show/Hide Feedback Wrapper
            if (show) {
                $(this).removeClass('d-none');
            } else {
                $(this).addClass('d-none');
            }
        });
    }

    // Event Listeners
    $('#searchInput-feedback').on('keyup', applyFilters);
    $('.company-filter').on('change', applyFilters);
    $('#startDate, #endDate').on('change', applyFilters);
});


</script>
@endsection