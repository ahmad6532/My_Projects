@extends('layouts.head_office_app')
@section('title', 'Head office Settings')


@section('sidebar')
@php
    $statusCount = $cases->groupBy('status')->map(function($group) {
                    return $group->count();
                });
    $incident_types = $cases->groupBy('incident_type')->map(function($group) {
        return $group->count();
    });
@endphp
<div id="sidebar" class="sidebar">
    <div class=" headingWithSearch ms-0">
        <div class="input-group rounded " style="width: 320px;">
            <span class="input-group-text border-0 bg-transparent search-addon" id="search-addon">
                <i class="fas fa-search" style="color: #969697;z-index: 2"></i>
            </span>
            <input spellcheck="true" type="search" class="form-control rounded shadow-none search-input" placeholder="Search" aria-label="Search" aria-describedby="search-addon" id="caseSearch" />
        </div>
    </div>
    <div class="accordion" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                    Incident Type
                </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                <ul>
                    @foreach ($incident_types as $incident_type => $quantity )
                        <li>
                            <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="{{$incident_type}}">
                            <a class="text-decoration-none text-secondary" title="{{$incident_type}}">{{$incident_type}}</a>
                            <span class="badge bg-secondary float-end rounded-pill open" style="width:25px">{{isset($incident_types[$incident_type]) ? $incident_types[$incident_type] : 0}}</span>
                        </li>
                    @endforeach
                    
                </ul>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                    Status
                </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                <ul>
                    <li>
                        <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="open">
                        <a class="text-decoration-none text-secondary" title="Open">Open</a>
                        <span class="badge bg-secondary float-end rounded-pill open" style="width:25px">{{isset($statusCount['open']) ? $statusCount['open'] : 0}}</span>
                    </li>
                    <li>
                        <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="closed">
                        <a class="text-decoration-none text-secondary" title="Closed">Closed</a>
                        <span class="badge bg-secondary float-end rounded-pill closed" style="width:25px">{{isset($statusCount['closed']) ? $statusCount['closed'] : 0}}</span>
                    </li>
                    <li>
                        <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="final approval">
                        <a class="text-decoration-none text-secondary" title="Final Approval">Final Approval</a>
                        <span class="badge bg-secondary float-end rounded-pill final_approval" style="width:25px">{{isset($statusCount['waiting']) ? $statusCount['waiting'] : 0}}</span>
                    </li>
                </ul>
            </div>
        </div>
        
    </div>

    <form method="GET" action="{{route('head_office.contact_view_timeline',$new_contact->id)}}">
    
        <!-- Date Range Filter -->
        <label class="text-secondary" style="font-size: 12px;" for="filter-start-date">Start Date:</label>
        <input class="form-control form-control-sm shadow-none" type="date" id="filter-start-date" name="start_date" value="{{ request('start_date') }}">
    
        <label class="text-secondary" style="font-size: 12px;" for="filter-end-date">End Date:</label>
        <input class="form-control form-control-sm shadow-none" type="date" id="filter-end-date" name="end_date" value="{{ request('end_date') }}">
    
        <!-- Submit Button -->
        <button type="submit" class="outline-btn mt-2">Filter Cases</button>
    </form>
    

</div>
@endsection

@section('sub-header')
    <div class="container mx-auto">
        <a href="{{ route('head_office.contacts.view', $new_contact->id) }}" class="link text-info">Details</a>
        <a href="{{route('head_office.contact_view_timeline',$new_contact->id)}}" class="link text-info ms-4">Timeline</a>
        {{-- <a href="{{route('head_office.contact_intelligence',$new_contact->id)}}" class="link text-info ms-4">Intelligence</a> --}}
        <a href="javascript:void(0);" style="position: relative; text-decoration: none; color: #17a2b8; margin-left: 1rem;" onmouseover="this.querySelector('span').style.visibility='visible'" onmouseout="this.querySelector('span').style.visibility='hidden'"> Intelligence
            <span style="visibility: hidden; position: absolute; bottom: 150%; left: 50%; transform: translateX(-50%); background-color: #000; color: #fff; padding: 5px 8px; border-radius: 5px; font-size: 12px; white-space: nowrap; z-index: 1000;">
                Coming Soon
                <span style="position: absolute; top: 100%; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 5px solid #000;">
                </span>
            </span>
            </a>

        <a href="{{route('head_office.contact_matchs',$new_contact->id)}}" class="link text-info ms-4">Matches</a>
    </div>
@endsection
@section('content')


    <div id="content" style="margin: 0;padding:0;">
        <style>
            .grid-wrap{
                display: grid;
                grid-template-columns: 30% 1fr;
            }
        </style>
        @include('layouts.error')

        <div class="container-lg mx-auto">
            <div class="timeline timeline_nearmiss border-add nearmiss-timeline" style="margin-left:13rem;">
                <div class="border-top-form"></div>
                @include('head_office.contacts.contact_timeline_card')


                <div class="line line-date  print-display-none" style="display:none">
                    <div class="timeline-label"><i class="spinning_icon fa-spin fa fa-spinner"></i></div>
                </div>
                <div class="line line-date last-line">
                    <div class="timeline-label">Start</div>
                </div>
                @if ($user_to_case_handlers->first() == null)
                    <div class="account_created center" style="margin: unset;">
                        <h4 class="timeline_category_title">No activity</h4>
                    </div>
                    @else
                    <div class="account_created center" style="margin: unset;">
                        <h4 class="timeline_category_title">Created At</h4>
                        <p>{{ date('D jS F Y', strtotime($user_to_case_handlers->first()->created_at)) }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>


@section('scripts')
    <script>
        $(document).ready(function() {
            loadActiveTab();

        });
        window.addEventListener('DOMContentLoaded', (event) => {
            $(document).ready(function() {
                $('.select_2_custom').select2({
                    tags: true,
                });
            });
        });

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log(tab);
            $('.nav-tabs button[data-bs-target="' + tab + '"]').tab('show');
        }

    </script>




    <script src="{{ asset('js/alertify.min.js') }}"></script>

    <script>
        function filterCases() {
        const query = $('#caseSearch').val().toLowerCase();
        const selectedCriteria = $('.case-filter-checkbox:checked').map(function() {
            return $(this).val().toLowerCase();
        }).get();

        const allCriteria = $('.case-filter-checkbox').map(function() {
            return $(this).val().toLowerCase();
        }).get();

        const allCriteriaSet = new Set(allCriteria);
        let hasVisibleItems = false;

        // Track counts of criteria
        const criteriaCounts = {};
        const criteriaCountsTotal = {};

        $('.case-item').each(function() {
            const text = $(this).text().toLowerCase();
            const matchesQuery = text.includes(query);
            const matchesCriteria = selectedCriteria.every(criteria => text.includes(criteria));

            const shouldDisplay = matchesQuery && matchesCriteria;
            $(this).toggle(shouldDisplay);

            if (shouldDisplay) {
                hasVisibleItems = true;

                // Count criteria occurrences in the visible case item
                allCriteria.forEach(criteria => {
                    if (text.includes(criteria)) {
                        if (criteriaCounts[criteria]) {
                            criteriaCounts[criteria]++;
                        } else {
                            criteriaCounts[criteria] = 1;
                        }
                    }
                });
            }
        });

        // Find criteria that are in allCriteria but not in visible cases
        const visibleCriteriaSet = new Set(Object.keys(criteriaCounts));
        const uniqueToAllCriteria = allCriteria.filter(criteria => !visibleCriteriaSet.has(criteria));

        // Initialize counts for unique criteria
        uniqueToAllCriteria.forEach(criteria => {
            criteriaCountsTotal[criteria] = 0;
        });

        // Combine counts
        const combinedCounts = { ...criteriaCountsTotal, ...criteriaCounts };

        // Update sidebar
        Object.entries(combinedCounts).forEach(([criteria, count]) => {
            console.log(criteria)
            const badgeClass = replaceSpacesWithUnderscores(criteria).toLowerCase();
            $('.sidebar ul li .badge.' + badgeClass).text(count);
        });

        // Update no results message
        $('#noResultsMessage').toggle(!hasVisibleItems);
    }

    // Utility function to replace spaces with underscores
    function replaceSpacesWithUnderscores(str) {
        if (typeof str !== 'string') {
            throw new TypeError('Expected a string');
        }
        return str.replace(/ /g, '_');
    }

    // Utility function to count occurrences
    function countOccurrences(array) {
        return array.reduce((counts, item) => {
            counts[item] = (counts[item] || 0) + 1;
            return counts;
        }, {});
    }

    $('.case-filter-checkbox').on('change', filterCases);

    $('#caseSearch').on('input', filterCases);

    // Initially hide the no results message
    $('#noResultsMessage').hide();

    $(function() {
        let maxLength = 18;
        $('.sidebar .accordion-item a').each(function () {
            let text = $(this).text();
            if (text.length > maxLength) {
                let truncatedText = text.substring(0, maxLength) + '...';
                $(this).text(truncatedText);
            }
        });
    });

    </script>

@endsection
@endsection
