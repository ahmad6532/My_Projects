@extends('layouts.head_office_app')
@section('title', 'Head office Settings')
@section('content')
    <div class="container-fluid">
        <form id="associationDataForm" action="{{ route('head_office.contacts.create') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="d-flex align-items-center gap-2" style="width:200px;">
                <label for="file" class="user-icon-circle">
                    <img style="width: 50px;height:50px;border-radius:50%; border:0.5px solid gray; object-fit:cover; object-position:top"
                        id="output">
                </label>
                <input type="hidden" name="image" id="base64_image" />
                <input id="file" type="file" class="d-none" accept=".png" onchange="loadFile(event)" />
                <input type="name" id="name" name="name" placeholder="Name" class="form-control shadow-none"
                    style="height:30px" required>
            </div>
            <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:40%">
                <p class="fw-bold" style="color: var(--portal-section-heading-color)">Personal</p>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="dropdown-toggle" type="button" id="personalDropDown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="dropdown-menu animated--fade-in" aria-labelledby="personalDropDown">
                    <a class="dropdown-item" onclick="add_personal('date', 'Date of birth', 'date_of_birth' )">Date of
                        birth</a>
                    <a class="dropdown-item" onclick="add_personal('text', 'NHS No', 'nhs_no' )">NHS No</a>
                    <a class="dropdown-item" onclick="add_personal('text', 'Ethnicity', 'ethnicity' )">Ethnicity</a>
                    <a class="dropdown-item" onclick="add_personal('text', 'Sexual Orientation', 'sexual_orientation' )">Sexual orientation</a>
                    <a class="dropdown-item" onclick="add_personal('radio', 'Marital status', 'marital_status' )">Marital
                        status</a>
                    <a class="dropdown-item" onclick="add_personal('radio', 'Gender', 'gender' )">Gender</a>
                    <a class="dropdown-item" onclick="add_personal('text', 'Pronoun', 'pronoun' )">Pronoun</a>
                    <a class="dropdown-item" onclick="add_personal('text', 'Religion', 'religion' )">Religion</a>
                    <a class="dropdown-item" onclick="add_personal('text', 'Passport no', 'passport_no' )">Passport no</a>
                    <a class="dropdown-item"
                        onclick="add_personal('text', 'Driver\'s license no', 'driver_license_no' )">Driver's license no</a>
                    <a class="dropdown-item" onclick="add_personal('text', 'Profession', 'profession' )">Profession</a>
                    
                    <a class="dropdown-item" style="display: none" id="registration_no_input"
                        onclick="add_personal('text', 'Registration No.', 'registration_no' )">Registration No.</a>
                    <a class="dropdown-item" onclick="add_personal('textarea', 'Other', 'other' )">Other</a>
                </div>
            </div>
            <div class="personal-container" style="width:40%"></div>




            <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:40%">
                <p class="fw-bold" style="color: var(--portal-section-heading-color)">Contact Info</p>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="dropdown-toggle" type="button" id="contactInfoDropDown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="dropdown-menu animated--fade-in" aria-labelledby="contactInfoDropDown">
                    <a class="dropdown-item" onclick="add_contact_info('email_select', 'Work email', 'work_emails' )">Work
                        email</a>
                    <a class="dropdown-item"
                        onclick="add_contact_info('email_select', 'Personal email', 'personal_emails' )">Personal email</a>
                    <a class="dropdown-item"
                        onclick="add_contact_info('phone_select', 'Work mobile no.', 'work_mobiles' )">Work mobile no.</a>
                    <a class="dropdown-item"
                        onclick="add_contact_info('phone_select', 'Personal mobile no.', 'personal_mobiles' )">Personal
                        mobile no.</a>
                    <a class="dropdown-item"
                        onclick="add_contact_info('phone_select', 'Home telephone no.', 'home_telephones' )">Home telephone
                        no.</a>
                    <a class="dropdown-item"
                        onclick="add_contact_info('phone_select', 'Work telephone no.', 'work_telephones' )">Work telephone
                        no.</a>
                    <a class="dropdown-item" onclick="add_contact_info('text', 'Facebook', 'facebook' )">Facebook</a>
                    <a class="dropdown-item" onclick="add_contact_info('text', 'Instagram', 'instagram' )">Instagram</a>
                    <a class="dropdown-item" onclick="add_contact_info('text', 'Twitter', 'twitter' )">Twitter</a>
                    <a class="dropdown-item" onclick="add_contact_info('text', 'Other ', 'other_link' )">Other </a>
                </div>
            </div>

            <div class="contact-info-container" style="width:40%"></div>


            <div class="my-2" style="width:40%">
                <p class="fw-bold" style="color: var(--portal-section-heading-color)">Address</p>
                <select id="tags-select" name="addresses[]" multiple class="form-control address_select "
                    style="width: 100%">
                    @if (!empty($new_contact_addresses) && count($new_contact_addresses) > 0)
                        @foreach ($new_contact_addresses as $address)
                            <option value="{{ $address->id }}">
                                {{ $address->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>



            <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:40%">
                <p class="fw-bold" style="color: var(--portal-section-heading-color)">Associations</p>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" type="button" id="addAssociation">
                    <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="association-container" style="width:40%"></div>
            <input type="hidden" name="new_contacts_relations" id="new_contacts_relations">
            

            <style>
                .cat-input-bucket {
                    top: 50%;
                }

                .cat-input-parent {
                    display: flex;
                    position: relative;
                    padding: unset;
                    width: 100%;
                    width: 450px;
                    justify-content: flex-start;
                    align-items: flex-start;
                    gap: 10px;
                }
            </style>
            <div class="d-flex justify-content-between mt-4" style="width: 40%">
                <div>
                    <p class="fw-bold" style="color: var(--portal-section-heading-color)">Tags</p>
                    <p class="fw-bold" style="color: var(--portal-section-heading-color)">Groups</p>
                </div>
                @livewire('contact-tags', ['contact_id' => null])
            </div>
            <div class="my-2" style="width: 40%">
                <p class="fw-bold" style="color: var(--portal-section-heading-color)">Assign To</p>
                <select id="assigns-select" name="assigns[]" multiple class="form-control tag_select "
                    style="width: 100%">
                    @if (!empty($head_office_users) && count($head_office_users) > 0)
                        @foreach ($head_office_users as $head_office_user)
                            <option value="{{ $head_office_user->id }}"
                                data-user-name="{{ (isset($head_office_user->user->first_name) ? $head_office_user->user->first_name : '') .
                                    ' ' .
                                    (isset($head_office_user->user->surname) ? $head_office_user->user->surname : '') }}"
                                data-user-position="{{ isset($head_office_user->user->position) ? $head_office_user->user->position->name : '' }}">
                                {{ (isset($head_office_user->user->first_name) ? $head_office_user->user->first_name : '') .
                                    ' ' .
                                    (isset($head_office_user->user->surname) ? $head_office_user->user->surname : '') }} |
                                {{ isset($head_office_user->position) ? $head_office_user->position : '' }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="selected-assignes d-flex flex-column gap-1" style="width: 40%">

            </div>

            <div id="assicaters-list" class="d-none">
                @if (!empty($new_contacts))
                    @foreach ($new_contacts as $contact)
                        <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                    @endforeach
                @endif
            </div>
            <button type="submit" class="primary-btn my-4">Submit</button>
        </form>
    </div>
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

        const add_personal = (type, name, id) => {
    if ($('#' + id).length) {
        return;
    }
    let inputField;

    if (type === "date" || type === "text") {
        const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        inputField =
            `<input type="${type}" id="${id}_input" name="${id}" placeholder="${name}" class="form-control" style="height:30px" required ${type === 'date' ? 'max="' + today + '"' : ''}>`;
    } else if (type === "textarea") {
        inputField =
            `<textarea spellcheck="true"  id="${id}_input" name="${id}" placeholder="${name}" class="form-control" style="height:60px" required></textarea>`;
    } else if (type == 'radio' && id == 'marital_status') {
        inputField = `
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="${id}" id="single" value="single" required>
            <label class="form-check-label" for="single">Single</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="${id}" id="married" value="married" required>
            <label class="form-check-label" for="married">Married</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="${id}" id="separated" value="separated" required>
            <label class="form-check-label" for="separated">Separated</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="${id}" id="divorced" value="divorced" required>
            <label class="form-check-label" for="divorced">Divorced</label>
        </div>
        `
    } else if (type === 'radio' && id == 'gender') {
        inputField = `
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="${id}" id="male" value="male" required>
            <label class="form-check-label" for="male">Male</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="${id}" id="female" value="female" required>
            <label class="form-check-label" for="female">Female</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="${id}" id="other2" value="other" required>
            <label class="form-check-label" for="other2">Other</label>
        </div>
        `
    } else if (type === 'textarea') {
                inputField =
                    `<textarea spellcheck="true"  id="${id}_input" name="${id}" placeholder="${name}" class="form-control" style="height:60px" required></textarea>`
    }

    $('.personal-container').append(`
        <div class="" id="${id}">
            <label style="margin: 0;font-size: 12px;" for="${id}">${name}</label>
            <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                ${inputField}
                <svg onclick="remove_item('${id}')" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    `);
    checkProffession()
};

        const add_contact_info = (type, name, id) => {
            if ($('#' + id).length) {
                return;
            }
            let inputField;

            if (type === "text") {
                inputField =
                    `<input type="${type}" id="${id}_input" name="${id}" placeholder="${name}" class="form-control" style="height:30px" required>`;
            } else if (type === "email_select") {
                inputField =
                    `<select name="${id}[]" multiple class="form-contorl email_select "style="width: 100%"></select>`
            } else if (type === "phone_select") {
                const newIndex = $(`[id^="${id}_phone_input_"]`).length + 1
                inputField =
                    `
                    <div id="${id}_phone_input_${newIndex}" class="my-1">
                    <input class="telephone" required type="text" placeholder="Add a phone number" name="${id}[]" />
                    <svg onclick="remove_item('${id}_phone_input_${newIndex}')" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg></div>
                    `
            }
            let addSvg = type === 'phone_select' ?
                `<svg onclick="insert_new_phone('${type}', '${name}', '${id}' )" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">` :
                ''
            $('.contact-info-container').append(`
                <div class="" id="${id}">
                    <label style="margin: 0;font-size: 12px;" for="${id}">${name}</label>
                    <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                        ${inputField}
                       <div> <svg onclick="remove_item('${id}')" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        ${addSvg}
                    <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg></div>
                    </div>
                </div>
            `);


            $(".telephone").intlTelInput({
                fixDropdownWidth: true,
                showSelectedDialCode: true,
                strictMode: true,
                preventInvalidNumbers: true,
                initialCountry: 'gb'
            })
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
        };

        const add_address = (type, name, id) => {
            let safeId = CSS.escape(id);

            if ($('#' + safeId).length) {
                return;
            }
            let inputField =
                `<input type="${type}" id="${id}_input" name="${id}" placeholder="${name}" class="form-control" style="height:30px" required>`;

            $('.address-container').append(`
                <div class="" id="${id}" class="my-1">
                    <label style="margin: 0;font-size: 12px;" for="${id}">${name}</label>
                    <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                        ${inputField}
                        <svg onclick="remove_item('${id}')" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            `);
        };
        $('#addAssociation').on('click', function() {
            const length = $('.association-container').children().length;
            const id = 'association_' + length + 1;
            const options = $('#assicaters-list').html();
            const html = `<div class="d-flex align-items-center gap-2 justify-content-between my-2" id="${id}">
                    <div class="w-100">
                        <label style="margin: 0;font-size: 12px;">Target Contact</label>
                    <select class="form-select" aria-label="Select Contact">
                        ${options}
                      </select>
                    </div>
                      <div class="">
                        <label style="margin: 0;font-size: 12px;" for="relation-${id}">Relation</label>
                      <input type="text" id="relation-${id}" class="form-control" required>
                      </div>
                      <div class="">
                        <label style="margin: 0;font-size: 12px;" for="reverse_relation-${id}">Reverse Relation</label>
                      <input type="text" id="reverse_relation-${id}" class="form-control"  required>
                      </div>

                       <svg style="width:10%" onclick="remove_item('${id}')" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                </div>`

            $('.association-container').append(html);
        });
        const remove_item = (id) => {
            let safeId = CSS.escape(id);
            $('#' + safeId).remove();
            checkProffession()
        }
        function insert_new_phone(type, name, id) {
            let newIndex = $(`[id^="${id}_phone_input_"]`).length + 1
            $(`#${id}`).append(` <div id="${id}_phone_input_${newIndex}">
                    <input class="telephone" required type="text" placeholder="Add a phone number" name="${id}[]" />
                    <svg onclick="remove_item('${id}_phone_input_${newIndex}')" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg></div>`)

            $(".telephone").intlTelInput({
                fixDropdownWidth: true,
                showSelectedDialCode: true,
                strictMode: true,
                preventInvalidNumbers: true,
                initialCountry: 'gb'
            })
        }
        function insert_new_phone(type, name, id) {
            $(`#${id}`).append(` <div id="${id}_phone_input">
                    <input class="telephone" required type="text" placeholder="Add a phone number" name="${id}[]" />
                    <svg onclick="remove_item('${id}_phone_input')" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg></div>`)

            $(".telephone").intlTelInput({
                fixDropdownWidth: true,
                showSelectedDialCode: true,
                strictMode: true,
                preventInvalidNumbers: true,
                initialCountry: 'gb'
            })
        }

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
            });

            function updateSelectedAssignes() {
                let selectedOptions = $('#assigns-select').find('option:selected');
                $('.selected-assignes').empty();
                        
                selectedOptions.each(function() {
                    let userName = $(this).data('user-name');
                    let userPosition = $(this).data('user-position');
                    $('.selected-assignes').append(`
                        <div class="d-flex gap-2">
                            <!-- <button class="badge badge-success badge-user border-0">${userName}</button> -->
                            <!-- <button class="badge badge-success badge-user border-0">${userPosition}</button> -->
                        </div>
                    `);
                });
            }
            updateSelectedAssignes();
            $('#assigns-select').on('change', function() {
                updateSelectedAssignes();
            });
        });

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validatePhone(phone) {
            var re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;;
            return re.test(phone);
        }



        document.getElementById('associationDataForm').addEventListener('submit', function(event) {
            const associationData = [];

            document.querySelectorAll('.association-container > div').forEach(function(div) {
                const divId = div.id;
                const selectedOptionId = div.querySelector('select').value;
                const relation = div.querySelector(`#relation-${divId}`).value;
                const inverseRelation = div.querySelector(`#reverse_relation-${divId}`).value;

                const existingEntry = associationData.find(entry => entry.div_id === divId);

                if (existingEntry) {
                    existingEntry.target_contact_id = selectedOptionId;
                    existingEntry.relation = relation;
                    existingEntry.reverse_relation = inverseRelation;
                } else {
                    associationData.push({
                        div_id: divId,
                        target_contact_id: selectedOptionId,
                        relation: relation,
                        reverse_relation: inverseRelation
                    });
                }
            });

            document.getElementById('new_contacts_relations').value = JSON.stringify(associationData);
            document.querySelectorAll('.telephone').forEach(function(telephone) {
                const code = telephone.intlTelInput("getSelectedCountryData").dialCode
                let phone = "+" + code + telephone.value();
                telephone.value(phone);
            });

        });

        $(".telephone").intlTelInput({
            fixDropdownWidth: true,
            showSelectedDialCode: true,
            strictMode: true,
            preventInvalidNumbers: true,
            initialCountry: 'gb'
        })
    </script>
@endsection
@section('scripts')
    <script>
         $(document).ready(function() {
            checkProffession();
        })

        function checkProffession(){
            const profession = $('#profession_input');
            if(profession.length > 0){
                $('#registration_no_input').fadeIn();
            }else{
                $('#registration_no_input').fadeOut();
            }
        }

        $(document).ready(function() {
            $('.select_2').select2();
            $('.address_select').select2();

            function filterTags() {
                let selectedGroup = $('#group-select').val();
                $('#tags-select option').each(function() {
                    let tagType = $(this).data('tag-type');
                    let tagGroups = $(this).data('tag-groups');
                    if (!selectedGroup && tagType === 'group_specific') {
                        $(this).prop('disabled', true);
                        $(this).prop('selected', false);
                    } else if (tagType === 'general') {
                        $(this).prop('disabled', false);
                    } else if (tagType === 'group_specific') {
                        selectedGroup = selectedGroup?.toString();
                        tagGroups = tagGroups.map(String);
                        if (tagGroups.includes(selectedGroup)) {
                            $(this).prop('disabled', false);
                        } else {
                            $(this).prop('disabled', true);
                            $(this).prop('selected', false);
                        }
                    }
                });

            }

            filterTags();
            $('#group-select').on('change', function() {
                filterTags();
            });
        });
    </script>
@endsection
