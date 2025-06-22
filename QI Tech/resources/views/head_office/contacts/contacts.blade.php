@extends('layouts.head_office_app')
@section('title', 'Head office Settings')
<style>
    .category-Wrapper button, .category-Wrapper a {
        padding: .375rem .75rem !important;
    }
    .bg-backdrop {
    width: 100vw !important;
    height: 100vh;
    position: fixed;
    top: -46vh !important;
    left: -31vw !important;
    background: #00000008;
    z-index: 1;
    transform: scale(1.5);
}
.cat-input-parent{
    gap: 0.5rem !important; 
}
</style>
@section('content')
    <div class="container-fluid">
        <div class="card card-qi border ps-3">
            <div class="card-body">
                <nav class="nav nav-tabs main_header nav-h-bordered d-flex align-items-center justify-content-center gap-2">
                    <a style="cursor: pointer;" onclick="changeTabUrl('all_contacts')" class="active cursor-pointer" data-bs-toggle="tab"
                        data-bs-target="#all_contacts"><span class="item_with_border">All Contacts</span></a>
                    <a style="cursor: pointer;" onclick="changeTabUrl('archived_contacts')" class="cursor-pointer" data-bs-toggle="tab"
                        data-bs-target="#archived_contacts"><span class="item_with_border">Archived</span></a>
                    <a style="cursor: pointer;" onclick="changeTabUrl('deleted_contacts')" class="cursor-pointer" data-bs-toggle="tab"
                        data-bs-target="#deleted_contacts"><span class="item_with_border">Deleted</span></a>
                    <a style="cursor: pointer;" onclick="changeTabUrl('addresses')" data-bs-toggle="tab" data-bs-target="#addresses"><span
                            class="item_with_border">Addresses</span> </a>
                    <a style="cursor: pointer;" onclick="changeTabUrl('my_contacts')" data-bs-toggle="tab" data-bs-target="#my_contacts"><span
                            class="item_with_border">My
                            Contacts</span></a>
                    <a style="cursor: pointer;" onclick="changeTabUrl('favourites')" data-bs-toggle="tab" data-bs-target="#favourites"><span
                            class="item_with_border">Favorites</span></a>
                    {{-- <a onclick="changeTabUrl('groups')" data-bs-toggle="tab" data-bs-target="#groups"><span
                            class="item_with_border">Groups</span> </a>
                    <a onclick="changeTabUrl('tags')" data-bs-toggle="tab" data-bs-target="#tags"><span
                            class="item_with_border">Tags</span>
                    </a> --}}



                </nav>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active " id="all_contacts">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('head_office.contacts.create_contact') }}" class="primary-btn my-2">
                                Add New Contact
                            </a>
                        </div>
                        <div class="d-flex gap-4 justify-content-start">
                            @include('head_office.contacts.sidebar', ['tab' => 'all_contacts'])
                            @include('head_office.contacts.bulk_editor', ['tab' => 'all_contacts'])
                            <div class="w-100">
                                @include('head_office.contacts.search', ['tab' => 'all_contacts'])
                                <div class="d-flex gap-2 flex-wrap">
                                    @if (!empty($contacts))
                                        @foreach ($contacts as $contact)
                                            @if ($contact->is_archive == false && $contact->is_deleted == false)    
                                                @include('head_office.contacts.contact', [
                                                    'tab' => 'all_contacts',
                                                ])
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade " id="archived_contacts">
                        <div class="d-flex gap-4 justify-content-start">
                            @include('head_office.contacts.sidebar', ['tab' => 'archived_contacts'])
                            @include('head_office.contacts.bulk_editor', ['tab' => 'archived_contacts'])
                            <div class="w-100">
                                @include('head_office.contacts.search', ['tab' => 'archived_contacts'])
                                <div class="d-flex gap-2 flex-wrap">
                                    @if (!empty($contacts))
                                        @foreach ($contacts as $contact)
                                            @if ($contact->is_archive == true)    
                                                @include('head_office.contacts.contact', [
                                                    'tab' => 'all_contacts',
                                                ])
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade " id="deleted_contacts">
                        <div class="d-flex gap-4 justify-content-start">
                            @include('head_office.contacts.sidebar', ['tab' => 'deleted_contacts'])
                            @include('head_office.contacts.bulk_editor', ['tab' => 'deleted_contacts'])
                            <div class="w-100">
                                @include('head_office.contacts.search', ['tab' => 'deleted_contacts'])
                                <div class="d-flex gap-2 flex-wrap">
                                    @if (!empty($contacts))
                                        @foreach ($contacts as $contact)
                                            @if ($contact->is_deleted == true && $contact->is_archive == false)    
                                                @include('head_office.contacts.contact', [
                                                    'tab' => 'all_contacts',
                                                ])
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="addresses">
                        @include('head_office.contacts.contacts_addresses')
                    </div>
                    <div class="tab-pane fade" id="my_contacts">
                        <div class="d-flex gap-4 justify-content-start">
                            @include('head_office.contacts.sidebar', ['tab' => 'my_contacts'])
                            @include('head_office.contacts.bulk_editor', ['tab' => 'my_contacts'])
                            <div class="w-100">
                                @include('head_office.contacts.search', ['tab' => 'my_contacts'])
                                <div class="d-flex gap-2 flex-wrap">
                                    @if (!empty($user_to_contacts))
                                        @foreach ($user_to_contacts as $user_to_contact)
                                            @php
                                                $contact = $user_to_contact->new_contact;
                                            @endphp
                                            @if (!empty($contact))
                                                @include('head_office.contacts.contact', [
                                                    'tab' => 'my_contacts',
                                                ])
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="favourites">
                        <div class="d-flex gap-4 justify-content-start">
                            @include('head_office.contacts.sidebar', ['tab' => 'favourites'])
                            @include('head_office.contacts.bulk_editor', ['tab' => 'favourites'])
                            <div class="w-100">
                                @include('head_office.contacts.search', ['tab' => 'favourites'])
                                <div class="d-flex gap-2 flex-wrap">
                                    @if (!empty($contacts))
                                        @foreach ($contacts as $contact)
                                            @if (in_array($contact->id, $user_favourite_contacts->pluck('contact_id')->toArray()))
                                                @include('head_office.contacts.contact', [
                                                    'tab' => 'favourites',
                                                ])
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- <div class="tab-pane fade" id="groups">
                        @include('head_office.contacts.contacts_groups')
                    </div>
                    <div class="tab-pane fade" id="tags">
                        @include('head_office.contacts.contacts_tags')
                    </div> --}}
                </div>

            </div>
        </div>

        <div class="loader-container" id="loader" style="top: 0;z-index:9999999;display:none;">
            <div class="loader"></div>
        </div>

        <script>
            $('#submit-now').on('click', function() {
                $('#loader').show();
            })

            $('#unlink-btn').on('click', function() {
                $('#other_case_id').val($(this).data('id'));
            })
        </script>
        <style>
            .select2-container--open {
                z-index: 9999999 !important;
            }
        </style>
    </div>


@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{ asset('js/alertify.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/select2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
    $(document).ready(function() {
        loadActiveTab();
        if (window.location.search.split('=')[1] != undefined) {
            changeTabUrl(window.location.search.split('=')[1])
        }


        function changeTabUrl(tabId, subTabId = null) {
            const currentURL = new URL(window.location.href);
            currentURL.searchParams.set('tab', tabId);
            window.history.pushState({
                tabId: tabId
            }, null, currentURL.href);

            $('#' + tabId).tab('show');
        }

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            $('.main_header > li > a[data-bs-target="' + tab + '"]').tab('show');
        }
    });



    $(document).ready(function() {
        function getCheckedCheckboxesInActiveTab() {
            let activeTabId = $('.tab-pane.active').attr('id');
            let checkedCheckboxes = $(`.tab-pane#${activeTabId} .contact-card input[type="checkbox"]:checked`);
            let checkedValues = checkedCheckboxes.map(function() {
                return $(this).val();
            }).get();

            return checkedValues;
        }
        $('.tab-pane .contact-card input[type="checkbox"]').on('change', function() {
            let checkedValues = getCheckedCheckboxesInActiveTab();
            let activeTabId = $('.tab-pane.active').attr('id');
            $(`.${activeTabId}`).val(JSON.stringify(checkedValues));
            let draggable = $(`.bottom-nav[data-selected-tab="${activeTabId}"]`)
            if (checkedValues && checkedValues?.length > 0) {
                draggable.css("display", "block");
                draggable.css("opacity", "1");
            } else {
                draggable.css("display", "none");
                draggable.css("opacity", "0");
            }
            draggable.find('h5').text(checkedValues?.length);
            console.log(draggable);
            
            dragBtn = draggable.find('.drag-btn').get(0);

            var posX = 0,
            posY = 0,
            mouseX = 0,
            mouseY = 0;
            if (dragBtn !== undefined && dragBtn !== null) {
            dragBtn.addEventListener("mousedown", mouseDown, false);
            }
            window.addEventListener("mouseup", mouseUp, false);

            function mouseDown(e) {
            e.preventDefault();
            posX = e.clientX - draggable[0].offsetLeft;
            posY = e.clientY - draggable[0].offsetTop;
            window.addEventListener("mousemove", moveElement, false);
            }

            function mouseUp() {
            window.removeEventListener("mousemove", moveElement, false);
            }

            function moveElement(e) {
            mouseX = e.clientX - posX;
            mouseY = e.clientY - posY;

            const maxX = 1000;
            const maxY = window.innerHeight - draggable[0].offsetHeight;

            mouseX = Math.min(Math.max(mouseX, 0), maxX);
            mouseY = Math.min(Math.max(mouseY, 0), maxY);
            draggable[0].style.left = mouseX + "px";
            draggable[0].style.top = mouseY + "px";
            }

        });
    });
</script>


@if (Session::has('success'))
    <script>
        $(document).ready(function() {
            alertify.success("{{ Session::get('success') }}");
        })
    </script>
@elseif(Session::has('error'))
    <script>
        $(document).ready(function() {
            alertify.error("{{ Session::get('error') }}");
        })
    </script>
@endif
