@extends('layouts.head_office_app')
@section('title', 'Head office Settings')


@section('content')
    @if (isset($address))
        <div class="d-flex justify-content-between w-100">
            <div class="container-fluid">
                <form id="associationDataForm" action="{{ route('head_office.contacts.create_address', $address->id) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex align-items-center gap-2" style="width:200px;">
                        <label for="file" class="user-icon-circle">
                            <img style="width: 50px;height:50px;border-radius:50%; border:0.5px solid gray; object-fit:cover; object-position:top"
                            id="output"
                            src="{{ isset($address->avatar) && file_exists(public_path('v2/' . $address->avatar)) ? asset('v2/' . $address->avatar) : asset('images/svg/logo_blue.png') }}">
            </label>
                        <input type="hidden" name="image" id="base64_image" />
                        <input id="file" type="file" class="d-none" accept=".png"
                            onchange="loadFile(event)" />
                            <input type="hidden" name="avatar" value="{{ $address->avatar }}">
                        <div class="resizing-input">
                            <input type="name" id="name" name="name" placeholder="Name"
                                class="form-control shadow-none" style="height:30px;" required value="{{ $address->name }}">
                            <span style="display: none;"></span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label style="margin: 0;font-size: 12px;" for="email">Address</label>
                        <input type="text" name="address" placeholder="Address" class="form-control"
                            style="height:50px" required value="{{ $address->address }}">

                    </div>

                    <select class="form-select mt-4" aria-label="Tag the Address" name="address_tag">
                        <option value="current_address" selected>Current Address</option>
                        <option value="past_address">Past Address</option>
                        <option value="work_address">Work Address</option>
                        <option value="home_address">Home Address</option>
                    </select>
                    



                    


                    
                    

                    
                    <div class="d-flex justify-content-between mt-4" style="width: 90%">
                        <div>
                            <p class="fw-bold" style="color: var(--portal-section-heading-color)">Tags</p>
                        </div>
                        @livewire('address-tags-manager', ['address_id' => $address->id])
                    </div>

                    <div class="my-2" style="width: 90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Assign To</p>
                        <select id="assigns-select" name="assigns[]" multiple class="form-control tag_select "
                            style="width: 100%">
                            @if (!empty($head_office_users) && count($head_office_users) > 0)
                                @foreach ($head_office_users as $head_office_user)
                                    <option value="{{ $head_office_user->id }}"
                                        @if (isset($user_to_contacts) &&
                                                in_array($head_office_user->id, $user_to_contacts->pluck('head_office_user_id')->toArray())) selected @endif
                                        data-user-name="{{ (isset($head_office_user->user->first_name) ? $head_office_user->user->first_name : '') .
                                            ' ' .
                                            (isset($head_office_user->user->surname) ? $head_office_user->user->surname : '') }}"
                                        data-user-position="{{ isset($head_office_user->user->position) ? $head_office_user->user->position->name : '' }}">
                                        {{ (isset($head_office_user->user->first_name) ? $head_office_user->user->first_name : '') .
                                            ' ' .
                                            (isset($head_office_user->user->surname) ? $head_office_user->user->surname : '') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="selected-assignes d-flex flex-column gap-1" style="width: 90%">

                    </div>

                    <div id="assicaters-list" class="d-none">
                        @if (!empty($new_contacts))
                            @foreach ($new_contacts as $contact)
                                @if ($address->id != $contact->id)
                                    <option selected value="{{ $contact->id }}">{{ $contact->name }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <button type="submit" class="primary-btn my-4">Submit</button>
                </form>
            </div>
            <div class="w-100">
                @php
                    $comments = $new_contact_comments;
                @endphp
                @foreach ($comments as $key => $comment)
                    @include('head_office.contacts.view_comments', compact('comment'))
                @endforeach
                <div class="cm_new_comment ">
                    <!-- <p>Add New Comment</p> -->
                    @include('head_office.contacts.contact_comments', [
                        'comment' => null,
                        'parent' => null,
                        'remove_backdrop' => true,
                        'address' => $address
                    ])

                </div>
            </div>
        </div>


    @endif
    <script src="{{ asset('admin_assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/intlTelInput-jquery.min.js') }}" type="text/javascript"></script>
    <script>
        let loadFile = function(event) {
            let image = document.getElementById("output");
            image.src = URL.createObjectURL(event.target.files[0]);
            let file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = (evt) => {
                let result = evt.target.result;
                document.getElementById('base64_image').value = result;
            };
            reader.readAsDataURL(file);
        };

        window.addEventListener('DOMContentLoaded', (event) => {
            $(document).ready(function() {
                $('.email_select').select2({
                    tags: true,
                    createTag: function(params) {
                        var term = $.trim(params.term);

                        if (validateEmail(term)) {
                            return {
                                id: term,
                                text: term,
                                newTag: true
                            };
                        }

                        return null;
                    },
                    insertTag: function(data, tag) {
                        if (tag.newTag) {
                            data.push(tag);
                        }
                    }
                });
                $('.phone_select').select2({
                    tags: true,
                    createTag: function(params) {
                        var term = $.trim(params.term);

                        if (validatePhone(term)) {
                            return {
                                id: term,
                                text: term,
                                newTag: true
                            };
                        }

                        return null;
                    },
                    insertTag: function(data, tag) {
                        if (tag.newTag) {
                            data.push(tag);
                        }
                    }
                });
                $('.tag_select').select2({
                    tags: true,
                    createTag: function(params) {
                        var term = $.trim(params.term);


                        return {
                            id: term,
                            text: term,
                            newTag: true
                        };


                        return null;
                    },
                    insertTag: function(data, tag) {
                        if (tag.newTag) {
                            data.push(tag);
                        }
                    }
                });

                function updateSelectedAssignes() {
                    let selectedOptions = $('#assigns-select').find('option:selected');
                    $('.selected-assignes').empty();

                    selectedOptions.each(function() {
                        let userName = $(this).data('user-name');
                        let userPosition = $(this).data('user-position');
                        $('.selected-assignes').append(`
                <div class="d-flex gap-2">
                    <button class="badge badge-success badge-user border-0">${userName}</button>
                    <button class="badge badge-success badge-user border-0">${userPosition}</button>
                </div>
            `);
                    });
                }
                updateSelectedAssignes();
                $('#assigns-select').on('change', function() {
                    updateSelectedAssignes();
                });
            });
        });
        
        </script>
    <script src="{{ asset('tribute/tribute.min.js') }}"></script>
    <script src="{{ asset('admin_assets/speech-to-text.js') }}"></script>
    @endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.tag_select').select2();
        
    })

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
@endsection
