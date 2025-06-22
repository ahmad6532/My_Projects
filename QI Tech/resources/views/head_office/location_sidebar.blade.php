<style>
    .accordion-button:not(.collapsed) {
        background-color: rgb(44 175 164 / 26%);
    }
</style>

<div style="position:relative;margin-right:1rem;" class="filters">
    <button class="sidebar-btn"><img id="side-img" src="{{ asset('images/chevron-left-double.svg') }}"
            alt="icon"></button>
    <nav id="sidebar" class="sidebar">
        @if (isset($forms))
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button shadow-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapseOne">
                            Assigned To
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                        <ul>
                            @foreach ($forms as $form)
                                <li>
                                    <input type="checkbox" class="asssigned-to case-filter-checkbox"
                                        value="{{ $form['form_name'] }}">
                                    <a class="text-decoration-none text-secondary"
                                        title="{{ $form['form_name'] }}">{{ $form['form_name'] }}</a>
                                    <span
                                        class="badge bg-secondary float-end rounded-pill {{ str_ireplace(' ', '_', strtolower($form['form_name'])) }} "
                                        style="width:25px">{{ $form['record_count'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('head_office.location_page_view_timeline', ['id' => $ho_location->id]) }}" method="get"
            class="d-flex flex-column align-items-start gap-1">
            <div class=" ">

                <label for="start_date" class="control-label" style="color: #999;font-size: 12px;">Start Date</label>
                <input type="datetime-local" style="color: #999;"
                    class="form-control py-1 px-2 border-2 shadow-none form-control-sm" name="startDate"
                    id="start_date" />


            </div>
            <div class="">
                <label for="end_date" style="color: #999;font-size: 12px;;">End Date</label>
                <input type="datetime-local" style="color: #999;"
                    class="form-control py-1 px-2 border-2 shadow-none form-control-sm" name="endDate" id="end_date">
            </div>
            <button type="submit" class="primary-btn mt-1 btn-sm">Search</button>
        </form>
    </nav>
</div>




<script>
    $(document).ready(function() {
        function filter_records() {
            const query = $('#caseSearch')?.val()?.toLowerCase();
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

            $('.right-record').each(function() {
                const text = $(this).text().toLowerCase();
                // const matchesQuery = text.includes(query);
                const matchesCriteria = selectedCriteria.length === 0 || selectedCriteria.some(criteria => text.includes(criteria));
                console.log(matchesCriteria)

                const shouldDisplay = matchesCriteria;
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
            const combinedCounts = {
                ...criteriaCountsTotal,
                ...criteriaCounts
            };

            // Update sidebar
            Object.entries(combinedCounts).forEach(([criteria, count]) => {
                console.log(criteria)
                const badgeClass = replaceSpacesWithUnderscores(criteria).toLowerCase();
                $('.sidebar ul li .badge.' + badgeClass).text(count);
            });

            console.log(combinedCounts)
        }

        function replaceSpacesWithUnderscores(str) {
            if (typeof str !== 'string') {
                throw new TypeError('Expected a string');
            }
            return str.replace(/ /g, '_');
        }


        $('.search').on('input', filter_records);
        $('.case-filter-checkbox').on('change', filter_records);
    });
</script>
