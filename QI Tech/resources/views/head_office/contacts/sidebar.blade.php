<style>
    .accordion-button:not(.collapsed) {
        background-color: rgb(44 175 164 / 26%);
    }
</style>

<div style="position:relative;margin-right:1rem;" data-selected-tab={{ $tab }} class="filters">
    <button class="sidebar-btn"><img id="side-img" src="{{ asset('images/chevron-left-double.svg') }}"
            alt="icon"></button>
    <nav id="sidebar" class="sidebar">
        @if ($tab != 'my_contacts')
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
                            @foreach ($head_office_users as $index => $user)
                                <li>
                                    <input type="checkbox" class="asssigned-to case-filter-checkbox"
                                        value="{{ $user->id }}">
                                    <a class="text-decoration-none text-secondary"
                                        title="{{ (isset($user->user->first_name) ? $user->user->first_name : '') .
                                            ' ' .
                                            (isset($user->user->surname) ? $user->user->surname : '') }}">{{ (isset($user->user->first_name) ? $user->user->first_name : '') .
                                            ' ' .
                                            (isset($user->user->surname) ? $user->user->surname : '') }}</a>
                                    <span class="badge bg-secondary float-end rounded-pill "
                                        style="width:25px">{{ count($user->user_to_contacts) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="accordion" id="groupsAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button shadow-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#groupsAccordion-collapseOne" aria-expanded="true"
                        aria-controls="groupsAccordion-collapseOne">
                        Groups
                    </button>
                </h2>
                <div id="groupsAccordion-collapseOne" class="accordion-collapse collapse show">
                    <ul>
                        @foreach ($contact_groups as $index => $group)
                            <li>
                                <input type="checkbox" class="groups" value="{{ $group->id }}">
                                <a class="text-decoration-none text-secondary"
                                    title="{{ isset($group->group_name) ? $group->group_name : '' }}">{{ isset($group->group_name) ? $group->group_name : '' }}</a>
                                <span class="badge bg-secondary float-end rounded-pill "
                                    style="width:25px">{{ count($group->contact_to_groups) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>


        <div class="accordion" id="tagsAccordian">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button shadow-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#tagsAccordian-collapseOne" aria-expanded="true"
                        aria-controls="tagsAccordian-collapseOne">
                        Tags
                    </button>
                </h2>
                <div id="tagsAccordian-collapseOne" class="accordion-collapse collapse show">
                    <ul>
                        @foreach ($contact_tags as $index => $tag)
                            <li>
                                <input type="checkbox" class="tags" value="{{ $tag->id }}">
                                <a class="text-decoration-none text-secondary"
                                    title="{{ isset($tag->name) ? $tag->name : '' }}">{{ isset($tag->name) ? $tag->name : '' }}</a>
                                <span class="badge bg-secondary float-end rounded-pill "
                                    style="width:25px">{{ count($tag->tag_to_contacts) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </nav>
</div>




<script>
    $(document).ready(function() {
        function filterCards(selectedTab) {
            // Gather filter values for the selected tab
            let searchText = $(`.search[data-selected-tab='${selectedTab}']`).val()?.toLowerCase() || '';
            let selectedGroupIds = $(`.filters[data-selected-tab='${selectedTab}'] .groups:checked`).map(
                function() {
                    return $(this).val().toString();
                }).get();
            let selectedTagIds = $(`.filters[data-selected-tab='${selectedTab}'] .tags:checked`).map(
                function() {
                    return $(this).val().toString();
                }).get();
            let selectedAssignedToIds = $(`.filters[data-selected-tab='${selectedTab}'] .asssigned-to:checked`)
                .map(function() {
                    return $(this).val().toString();
                }).get();

            $(`.contact-card[data-selected-tab='${selectedTab}']`).each(function() {
                let card = $(this);

                let matchesName = true;
                if (searchText !== '') {
                    let name = card.data('name')?.toLowerCase() || '';
                    matchesName = name.includes(searchText);
                }
              

                let tagIds = card.data('tag-ids')?.map(String) || [];
                let matchesTags = true;
                if (selectedTagIds.length > 0) {
                    matchesTags = selectedTagIds.some(tag => tagIds.includes(tag));
                }

                let groupIds = card.data('group-ids')?.map(String) || [];
                let matchesGroups = true;
                if (selectedGroupIds.length > 0) {
                    matchesGroups = selectedGroupIds.some(group => groupIds.includes(group));
                }

                let assignedToIds = card.data('assigned-to-ids')?.map(String) || [];
                let matchesAssignedTo = true;
                if (selectedAssignedToIds.length > 0) {
                    matchesAssignedTo = selectedAssignedToIds.some(id => assignedToIds.includes(id));
                }

                if (matchesName && matchesGroups && matchesTags && matchesAssignedTo) {
                    card.show();
                } else {
                    card.hide();
                }
            });
        }

        function applyFiltersToCurrentTab() {
            let activeTab = $('.tab-pane.active').attr('id');
            filterCards(activeTab);
        }
        $('.search').on('input', applyFiltersToCurrentTab);
        $('.groups, .tags, .asssigned-to').on('change', applyFiltersToCurrentTab);
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', applyFiltersToCurrentTab);
        applyFiltersToCurrentTab();
    });
</script>
