<style>
    .accordion-button:not(.collapsed){
        background-color:rgb(44 175 164 / 26%) ;
    }
</style>

<div style="position:relative;margin-right:1rem;">
    <button class="sidebar-btn"><img id="side-img" src="{{asset('images/chevron-left-double.svg')}}" alt="icon"></button>
<nav id="sidebar" class="sidebar">
            @if (in_array(request()->route()->getName(), [
                'case_manager.edit_report',
                'case_manager.request_information',
                'case_manager.index',
                'case_manager.view',
                'case_manager.view_report',
                'case_manager.view_root_cause_analysis',
                'case_manager.view_sharing',
                'case_manager.intelligence.mrege_contact',
                'head_office.case.requested_informations',
            ]))
            @php
                $casesCollection = $cases;
                $groupedCases = $casesCollection->groupBy(function($case) {
                    return isset($case->link_case_with_form->form->name) ? $case->link_case_with_form->form->name : 'Unknown';
                })->map(function($group) {
                    return $group->count();
                });

                $statusCount = $casesCollection->groupBy('status')->map(function($group) {
                    return $group->count();
                });

                $stagesCount = $casesCollection->flatMap(function($case){
                    return $case->stages;
                })->groupBy('name')->map(function($group){
                    return $group->count();
                });
                $rootCauseAnalysisCount = $casesCollection->filter(function($case) {
                    return $case->root_cause_analysis->contains(function($analysis) {
                        return $analysis->status == 0;
                    });
                })->count();
                $requestInfoCount = $casesCollection->filter(function($case) {
                    return $case->case_request_informations->contains(function($info) {
                        return $info->status == 0;
                    });
                })->count();
                $shareCaseCount = $casesCollection->filter(function($case) {
                    return $case->share_cases->isNotEmpty();
                })->count();
                $shareCaseExtensionCount = $casesCollection->filter(function($case) {
                    return $case->getShareCaseExtensionsAttribute() == 1;
                })->count();
            @endphp
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                            Stage
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                        <ul>
                            @foreach ($stagesCount as $stageName => $stageCount )
                                <li>
                                    <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="{{ $stageName }}">
                                    <a class="text-decoration-none text-secondary" title="{{$stageName}}">{{$stageName}}</a>
                                    <span class="badge bg-secondary float-end rounded-pill {{ str_ireplace(' ', '_', strtolower($stageName)) }}" style="width:25px">{{$stageCount}}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                            Type
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                        <ul>
                            @foreach ($groupedCases as $name => $count)
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="{{ $name }}">
                                <a class="text-decoration-none text-secondary" title="{{ $name }}">{{ $name }}</a>
                                <span class="badge bg-secondary float-end rounded-pill {{ str_ireplace(' ', '_', strtolower($name)) }}" style="width:25px">{{ $count }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="accordion-item nhs-lfpse-status" style="display:none;">
                    <h2 class="accordion-header">
                        <button class="accordion-button shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="false" aria-controls="panelsStayOpen-collapseSix">
                            NHS LFPSE Status
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse show">
                        <ul>
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="&nbsp;submitted">
                                <a class="text-decoration-none text-secondary" title="submitted">Submitted</a>
                                <span class="badge bg-secondary float-end rounded-pill &nbsp;submitted" style="width:25px">0</span>
                            </li>
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="not submitted">
                                <a class="text-decoration-none text-secondary" title="Not Submitted">Not Submitted</a>
                                <span class="badge bg-secondary float-end rounded-pill not_submitted" style="width:25px">0</span>
                            </li>
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="submitted with warning">
                                <a class="text-decoration-none text-secondary" title="Submitted With Warning">Submitted With Warning</a>
                                <span class="badge bg-secondary float-end rounded-pill submitted_with_warning" style="width:25px">0</span>
                            </li>
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="submitted with error">
                                <a class="text-decoration-none text-secondary" title="Submitted With Error">Submitted With Error</a>
                                <span class="badge bg-secondary float-end rounded-pill submitted_with_error" style="width:25px">0</span>
                            </li>
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
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="updated">
                                <a class="text-decoration-none text-secondary" title="updated">Updated</a>
                                <span class="badge bg-secondary float-end rounded-pill updated" style="width:25px">{{isset($statusCount['updated']) ? $statusCount['updated'] : 0}}</span>
                            </li>
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="final approval">
                                <a class="text-decoration-none text-secondary" title="Final Approval">Final Approval</a>
                                <span class="badge bg-secondary float-end rounded-pill final_approval" style="width:25px">{{isset($statusCount['waiting']) ? $statusCount['waiting'] : 0}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                            Awaiting
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse">
                        <ul>
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="root_cause_analysis">
                                <a class="text-decoration-none text-secondary" title="Root Cause Analysis">Root Cause Analysis</a>
                                <span class="badge bg-secondary float-end rounded-pill root_cause_analysis" style="width:25px">{{$rootCauseAnalysisCount}}</span>
                            </li>
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="requst_info_awaiting">
                                <a class="text-decoration-none text-secondary" title="Request Information">Request Information</a>
                                <span class="badge bg-secondary float-end rounded-pill requst_info_awaiting" style="width:25px">{{$requestInfoCount}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                            Sharing
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse">
                        <ul>
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="shared">
                                <a class="text-decoration-none text-secondary" title="Shared">Shared</a>
                                <span class="badge bg-secondary float-end rounded-pill shared" style="width:25px">{{$shareCaseCount}}</span>
                            </li>
                            <li>
                                <input type="checkbox" class="stage ajaxCheck case-filter-checkbox" value="share_extended">
                                <a class="text-decoration-none text-secondary" title="Extension Requested">Extension Requested</a>
                                <span class="badge bg-secondary float-end rounded-pill share_extended" style="width:25px">{{$shareCaseExtensionCount}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif
</nav>
</div>
<script>
    $('.sidebar-btn').on('click',function(){
        var sidebar = $('#sidebar');
        const imgTag = $('#side-img');
    if (sidebar.width() > 0) {
        const imgPath = imgTag.attr('src').replace('chevron-left-double','chevron-right-double');
        imgTag.attr('src',imgPath)
        sidebar.animate({ width: 0,opacity:0 }, 300);
    } else {
        const imgPath = imgTag.attr('src').replace('chevron-right-double','chevron-left-double');
        imgTag.attr('src',imgPath)
        sidebar.animate({ width: '250px',opacity:1 }, 300);
    }
    })


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
    $('.case-filter-checkbox').on('change', function () {

        let showNhsLfpseStatus = $('.case-filter-checkbox:checked').filter(function() {
            return $(this).val().toLowerCase() === 'nhs lfpse';
        }).length > 0;

        $('.nhs-lfpse-status').toggle(showNhsLfpseStatus);
    });

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
