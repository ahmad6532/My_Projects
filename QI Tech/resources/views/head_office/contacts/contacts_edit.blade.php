@extends('layouts.head_office_app')
@section('title', 'Head office Settings')

@section('sub-header')

    <div class="container mx-auto">
        <a href="{{ route('head_office.contacts.edit', $new_contact->id) }}" class="link text-info">Details</a>
        <a href="{{route('head_office.contact_view_timeline',$new_contact->id)}}" class="link text-info ms-4">Timeline</a>
        <a href="{{route('head_office.contact_intelligence',$new_contact->id)}}" class="link text-info ms-4">Intelligence</a>
        <a href="{{route('head_office.contact_matchs',$new_contact->id)}}" class="link text-info ms-4">Matches</a>
    </div>
@endsection
@section('content')
    @if (isset($new_contact))
        <div class="d-flex justify-content-between w-100">
            <div class="container-fluid">
                <form id="associationDataForm" action="{{ route('head_office.contacts.create', $new_contact->id) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex align-items-center gap-2" style="width:200px;">
                        <label for="file" class="user-icon-circle">
                            <img style="width: 50px;height:50px;border-radius:50%; border:0.5px solid gray; object-fit:cover; object-position:top"
                                id="output"
                                src="{{ isset($new_contact->avatar) && file_exists(public_path('v2/' . $new_contact->avatar)) ? asset('v2/' . $new_contact->avatar) : asset('images/svg/logo_blue.png') }}">
                        </label>
                        <input type="hidden" name="image" id="base64_image" />
                        <input id="file" type="file" class="d-none" accept=".png" onchange="loadFile(event)" />
                        <input type="name" id="name" name="name" placeholder="Name"
                            class="form-control shadow-none" style="height:30px" required value="{{ $new_contact->name }}">
                    </div>
                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Personal</p>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="dropdown-toggle" type="button" id="personalDropDown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="dropdown-menu animated--fade-in" aria-labelledby="personalDropDown">
                            <a class="dropdown-item" onclick="add_personal('date', 'Date of birth', 'date_of_birth' )">Date
                                of
                                birth</a>
                            <a class="dropdown-item" onclick="add_personal('text', 'NHS No', 'nhs_no' )">NHS No</a>
                            <a class="dropdown-item" onclick="add_personal('text', 'Ethnicity', 'ethnicity' )">Ethnicity</a>
                            <a class="dropdown-item" onclick="add_personal('text', 'Sexual Orientation', 'sexual_orientation' )">Sexual orientation</a>
                            <a class="dropdown-item"
                                onclick="add_personal('radio', 'Marital status', 'marital_status' )">Marital
                                status</a>
                            <a class="dropdown-item" onclick="add_personal('radio', 'Gender', 'gender' )">Gender</a>
                            <a class="dropdown-item" onclick="add_personal('text', 'Pronoun', 'pronoun' )">Pronoun</a>
                            <a class="dropdown-item" onclick="add_personal('text', 'Religion', 'religion' )">Religion</a>
                            <a class="dropdown-item" onclick="add_personal('text', 'Passport no', 'passport_no' )">Passport
                                no</a>
                            <a class="dropdown-item"
                                onclick="add_personal('text', 'Driver\'s license no', 'driver_license_no' )">Driver's
                                license
                                no</a>
                            <a class="dropdown-item"
                                onclick="add_personal('text', 'Profession', 'profession' )">Profession</a>
                            <a style="display: none" id="registration_no_input" class="dropdown-item"
                                onclick="add_personal('text', 'Registration No.', 'registration_no' )">Registration No.</a>
                            <a class="dropdown-item" onclick="add_personal('textarea', 'Other', 'other' )">Other</a>
                        </div>
                    </div>
                    <div class="personal-container" style="width:90%">
                        @if (isset($new_contact->date_of_birth))
                            <div class="" id="date_of_birth">
                                <label style="margin: 0;font-size: 12px;" for="date_of_birth">Date of birth</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="date" id="date_of_birth_input" name="date_of_birth"
                                        placeholder="Date of birth" class="form-control" style="height:30px" required
                                        value="{{ Carbon\Carbon::parse($new_contact->date_of_birth)->format('Y-m-d') }}">
                                    <svg onclick="remove_item('date_of_birth')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->nhs_no))
                            <div class="" id="nhs_no">
                                <label style="margin: 0;font-size: 12px;" for="nhs_no">NHS No</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="nhs_no_input" name="nhs_no" placeholder="NHS No"
                                        class="form-control" style="height:30px" required
                                        value="{{ $new_contact->nhs_no }}">
                                    <svg onclick="remove_item('nhs_no')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif

                        @if (isset($new_contact->ethnicity))
                            <div class="" id="ethnicity">
                                <label style="margin: 0;font-size: 12px;" for="ethnicity">Ethnicity</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="ethnicity_input" name="ethnicity" placeholder="Ethnicity"
                                        class="form-control" style="height:30px" required
                                        value="{{ $new_contact->ethnicity }}">
                                    <svg onclick="remove_item('ethnicity')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->sexual_orientation))
                            <div class="" id="sexual_orientation">
                                <label style="margin: 0;font-size: 12px;" for="sexual_orientation">Sexual orientation</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="sexual_orientation_input" name="sexual_orientation" placeholder="Sexual Orientation"
                                        class="form-control" style="height:30px" required
                                        value="{{ $new_contact->sexual_orientation }}">
                                    <svg onclick="remove_item('sexual_orientation')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->marital_status))
                            <div class="" id="marital_status">
                                <label style="margin: 0;font-size: 12px;" for="marital_status">Marital status</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="marital_status"
                                            id="single" value="single" required
                                            @if ($new_contact->marital_status == 'single') checked @endif>
                                        <label class="form-check-label" for="single">Single</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="marital_status"
                                            id="married" value="married" required
                                            @if ($new_contact->marital_status == 'married') checked @endif>
                                        <label class="form-check-label" for="married">Married</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="marital_status"
                                            id="separated" value="separated" required
                                            @if ($new_contact->marital_status == 'separated') checked @endif>
                                        <label class="form-check-label" for="separated">Separated</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="marital_status"
                                            id="divorced" value="divorced" required
                                            @if ($new_contact->marital_status == 'divorced') checked @endif>
                                        <label class="form-check-label" for="divorced">Divorced</label>
                                    </div>
                                    <svg onclick="remove_item('marital_status')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->gender))
                            <div class="" id="gender">
                                <label style="margin: 0;font-size: 12px;" for="gender">Gender</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male"
                                            value="male" required @if ($new_contact->gender == 'male') checked @endif>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female"
                                            value="female" required @if ($new_contact->gender == 'female') checked @endif>
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="other2"
                                            value="other" required @if ($new_contact->gender == 'other') checked @endif>
                                        <label class="form-check-label" for="other2">Other</label>
                                    </div>
                                    <svg onclick="remove_item('gender')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif


                        @if (isset($new_contact->pronoun))
                            <div class="" id="pronoun">
                                <label style="margin: 0;font-size: 12px;" for="pronoun">Pronoun</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="pronoun_input" name="pronoun" placeholder="Pronoun"
                                        class="form-control" style="height:30px" required
                                        value="{{ $new_contact->pronoun }}">
                                    <svg onclick="remove_item('pronoun')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->religion))
                            <div class="" id="religion">
                                <label style="margin: 0;font-size: 12px;" for="religion">Religion</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="religion_input" name="Religion" placeholder="religion"
                                        class="form-control" style="height:30px" required
                                        value="{{ $new_contact->religion }}">
                                    <svg onclick="remove_item('religion')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->passport_no))
                            <div class="" id="passport_no">
                                <label style="margin: 0;font-size: 12px;" for="passport_no">Passport no</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="passport_no_input" name="passport_no"
                                        placeholder="Passport no" class="form-control" style="height:30px" required
                                        value="{{ $new_contact->passport_no }}">
                                    <svg onclick="remove_item('passport_no')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->driver_license_no))
                            <div class="" id="driver_license_no">
                                <label style="margin: 0;font-size: 12px;" for="driver_license_no">Driver's license
                                    no</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="driver_license_no_input" name="driver_license_no"
                                        placeholder="Driver's license no" class="form-control" style="height:30px"
                                        required value="{{ $new_contact->driver_license_no }}">
                                    <svg onclick="remove_item('driver_license_no')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->profession))
                            <div class="" id="profession">
                                <label style="margin: 0;font-size: 12px;" for="profession">Profession</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="profession_input" name="profession"
                                        placeholder="Profession" class="form-control" style="height:30px" required
                                        value="{{ $new_contact->profession }}">
                                    <svg onclick="remove_item('profession')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->registration_no))
                            <div class="" id="registration_no">
                                <label style="margin: 0;font-size: 12px;" for="registration_no">Registration No.</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="registration_no_input" name="registration_no"
                                        placeholder="Registration No." class="form-control" style="height:30px" required
                                        value="{{ $new_contact->registration_no }}">
                                    <svg onclick="remove_item('registration_no')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->other))
                            <div class="" id="other">
                                <label style="margin: 0;font-size: 12px;" for="other">Other</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <textarea spellcheck="true"  id="other_input" name="other" placeholder="Other" class="form-control" style="height:60px" required></textarea>
                                    <svg onclick="remove_item('other')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                    </div>



                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Contact Info</p>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="dropdown-toggle" type="button"
                            id="contactInfoDropDown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="dropdown-menu animated--fade-in" aria-labelledby="contactInfoDropDown">
                            <a class="dropdown-item"
                                onclick="add_contact_info('email_select', 'Work email', 'work_emails' )">Work
                                email</a>
                            <a class="dropdown-item"
                                onclick="add_contact_info('email_select', 'Personal email', 'personal_emails' )">Personal
                                email</a>
                            <a class="dropdown-item"
                                onclick="add_contact_info('phone_select', 'Work mobile no.', 'work_mobiles' )">Work mobile
                                no.</a>
                            <a class="dropdown-item"
                                onclick="add_contact_info('phone_select', 'Personal mobile no.', 'personal_mobiles' )">Personal
                                mobile no.</a>
                            <a class="dropdown-item"
                                onclick="add_contact_info('phone_select', 'Home telephone no.', 'home_telephones' )">Home
                                telephone
                                no.</a>
                            <a class="dropdown-item"
                                onclick="add_contact_info('phone_select', 'Work telephone no.', 'work_telephones' )">Work
                                telephone
                                no.</a>
                            <a class="dropdown-item"
                                onclick="add_contact_info('text', 'Facebook', 'facebook' )">Facebook</a>
                            <a class="dropdown-item"
                                onclick="add_contact_info('text', 'Instagram', 'instagram' )">Instagram</a>
                            <a class="dropdown-item" onclick="add_contact_info('text', 'Twitter', 'twitter' )">Twitter</a>
                            <a class="dropdown-item" onclick="add_contact_info('text', 'Other ', 'other_link' )">Other
                            </a>
                        </div>
                    </div>
                    <div class="contact-info-container" style="width:90%">
                        @if (isset($new_contact->work_emails) &&
                                json_decode($new_contact->work_emails, true) != null &&
                                count(json_decode($new_contact->work_emails, true)) > 0)
                            <div class="" id="work_emails">
                                <label style="margin: 0;font-size: 12px;" for="work_emails">Work email</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <select name="work_emails[]" multiple
                                        class="form-contorl email_select "style="width: 100%">
                                        @foreach (json_decode($new_contact->work_emails, true) as $work_email)
                                            <option value="{{ $work_email }}" selected>{{ $work_email }}</option>
                                        @endforeach
                                    </select>
                                    <svg onclick="remove_item('work_emails')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->personal_emails) &&
                                json_decode($new_contact->personal_emails, true) != null &&
                                count(json_decode($new_contact->personal_emails, true)) > 0)
                            <div class="" id="personal_emails">
                                <label style="margin: 0;font-size: 12px;" for="personal_emails">Personal email</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <select name="personal_emails[]" multiple
                                        class="form-contorl email_select "style="width: 100%">
                                        @foreach (json_decode($new_contact->personal_emails, true) as $personal_email)
                                            <option value="{{ $personal_email }}" selected>{{ $personal_email }}</option>
                                        @endforeach
                                    </select>
                                    <svg onclick="remove_item('personal_emails')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->work_mobiles) &&
                                json_decode($new_contact->work_mobiles, true) != null &&
                                count(json_decode($new_contact->work_mobiles, true)) > 0)
                            <div class="" id="work_mobiles">
                                <label style="margin: 0;font-size: 12px;" for="work_mobiles">Work mobile no.</label>
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        @foreach (json_decode($new_contact->work_mobiles, true) as $index => $work_mobile)
                                            <div id="work_mobiles_phone_input_{{ $index }}" class="my-1">
                                                <input class="telephone" required type="text"
                                                    placeholder="Add a phone number" name="work_mobiles[]"
                                                    value="{{ $work_mobile }}" />
                                                <svg onclick="remove_item('work_mobiles_phone_input_{{ $index }}')"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                        stroke="red" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div>
                                        <svg onclick="remove_item('work_mobiles')" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                stroke="red" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <svg onclick="insert_new_phone('phone_select', 'Work mobile no.', 'work_mobiles' )"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endif








                        @if (isset($new_contact->personal_mobiles) &&
                                json_decode($new_contact->personal_mobiles, true) != null &&
                                count(json_decode($new_contact->personal_mobiles, true)) > 0)
                            <div class="" id="personal_mobiles">
                                <label style="margin: 0;font-size: 12px;" for="personal_mobiles">Personal mobile
                                    no.</label>
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        @foreach (json_decode($new_contact->personal_mobiles, true) as $index => $personal_mobile)
                                            <div id="personal_mobiles_phone_input_{{ $index }}" class="my-1">
                                                <input class="telephone" required type="text"
                                                    placeholder="Add a phone number" name="personal_mobiles[]"
                                                    value="{{ $personal_mobile }}" />
                                                <svg onclick="remove_item('personal_mobiles_phone_input_{{ $index }}')"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                        stroke="red" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div>
                                        <svg onclick="remove_item('personal_mobiles')" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                stroke="red" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <svg onclick="insert_new_phone('phone_select', 'Personal mobile no.', 'personal_mobiles' )"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endif










                        @if (isset($new_contact->home_telephones) &&
                                json_decode($new_contact->home_telephones, true) != null &&
                                count(json_decode($new_contact->home_telephones, true)) > 0)
                            <div class="" id="home_telephones">
                                <label style="margin: 0;font-size: 12px;" for="home_telephones">Home telephone no.</label>
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        @foreach (json_decode($new_contact->home_telephones, true) as $index => $home_telephone)
                                            <div id="home_telephones_phone_input_{{ $index }}" class="my-1">
                                                <input class="telephone" required type="text"
                                                    placeholder="Add a phone number" name="home_telephones[]"
                                                    value="{{ $home_telephone }}" />
                                                <svg onclick="remove_item('home_telephones_phone_input_{{ $index }}')"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                        stroke="red" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div>
                                        <svg onclick="remove_item('home_telephones')" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                stroke="red" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <svg onclick="insert_new_phone('phone_select', 'Home telephone no.', 'home_telephones' )"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->work_telephones) &&
                                json_decode($new_contact->work_telephones, true) != null &&
                                count(json_decode($new_contact->work_telephones, true)) > 0)
                            <div class="" id="work_telephones">
                                <label style="margin: 0;font-size: 12px;" for="work_telephones">Work telephone
                                    no..</label>
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        @foreach (json_decode($new_contact->work_telephones, true) as $index => $work_telephone)
                                            <div id="work_telephones_phone_input_{{ $index }}" class="my-1">
                                                <input class="telephone" required type="text"
                                                    placeholder="Add a phone number" name="work_telephones[]"
                                                    value="{{ $work_telephone }}" />
                                                <svg onclick="remove_item('work_telephones_phone_input_{{ $index }}')"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                        stroke="red" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div>
                                        <svg onclick="remove_item('work_telephones')" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                stroke="red" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <svg onclick="insert_new_phone('phone_select', 'Work telephone no.', 'work_telephones' )"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->facebook))
                            <div class="" id="facebook">
                                <label style="margin: 0;font-size: 12px;" for="facebook">Facebook</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="facebook_input" name="facebook" placeholder="Facebook"
                                        class="form-control" style="height:30px" required
                                        value="{{ $new_contact->facebook }}">
                                    <svg onclick="remove_item('facebook')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->instagram))
                            <div class="" id="instagram">
                                <label style="margin: 0;font-size: 12px;" for="instagram">Instagram</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="instagram_input" name="instagram" placeholder="Instagram"
                                        class="form-control" style="height:30px" required
                                        value="{{ $new_contact->instagram }}">
                                    <svg onclick="remove_item('instagram')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif

                        @if (isset($new_contact->twitter))
                            <div class="" id="twitter">
                                <label style="margin: 0;font-size: 12px;" for="twitter">Twitter</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="twitter_input" name="twitter" placeholder="Twitter"
                                        class="form-control" style="height:30px" required
                                        value="{{ $new_contact->twitter }}">
                                    <svg onclick="remove_item('twitter')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->other_link))
                            <div class="" id="other_link">
                                <label style="margin: 0;font-size: 12px;" for="other_link">Other</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <input type="text" id="other_link_input" name="other_link" placeholder="Other"
                                        class="form-control" style="height:30px" required
                                        value="{{ $new_contact->other_link }}">
                                    <svg onclick="remove_item('other_link')" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        @endif

                    </div>


                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Address</p>
                    </div>

                    <select name="addresses[]" multiple class="form-control address_select "
                            style="width: 90%">
                            @if (!empty($new_contact_addresses) && count($new_contact_addresses) > 0)
                                @foreach ($new_contact_addresses as $address)
                                    <option value="{{ $address->id }}" @if (in_array($address->id, $new_contact->contacts_to_addresses->pluck('address_id')->toArray()))
                                        selected
                                    @endif>
                                        {{ $address->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                    


                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Associations</p>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" type="button" id="addAssociation">
                            <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="association-container" style="width:90%">

                        @if (isset($new_contacts_relations) && count($new_contacts_relations) > 0)
                            @foreach ($new_contacts_relations as $index => $new_contacts_relation)
                                <div class="d-flex align-items-center gap-2 justify-content-between my-2"
                                    id="{{ $index }}">
                                    <div class="w-100">
                                        <label style="margin: 0;font-size: 12px;">Target Contact</label>
                                        <select class="form-select" aria-label="Select Contact">
                                            @if (!empty($new_contacts))
                                                @foreach ($new_contacts as $contact)
                                                    @if ($new_contact->id != $contact->id)
                                                        <option @if ($new_contacts_relation->source_contact_id == $contact->id) selected @endif
                                                            value="{{ $contact->id }}">{{ $contact->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="">
                                        <label style="margin: 0;font-size: 12px;"
                                            for="relation-{{ $index }}">Relation</label>
                                        <input value="{{ $new_contacts_relation->relation }}" type="text"
                                            id="relation-{{ $index }}" class="form-control" required>
                                    </div>
                                    <div class="">
                                        <label style="margin: 0;font-size: 12px;"
                                            for="reverse_relation-{{ $index }}">Reverse
                                            Relation</label>
                                        <input value="{{ $new_contacts_relation->reverse_relation }}" type="text"
                                            id="reverse_relation-{{ $index }}" class="form-control" required>
                                    </div>

                                    <svg style="width:10%" onclick="remove_item('{{ $index }}')" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                            stroke="red" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            @endforeach
                        @endif

                    </div>
                    <input type="hidden" name="new_contacts_relations" id="new_contacts_relations">
                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Group</p>
                    </div>
                    

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
                    <div class="d-flex justify-content-between mt-4" style="width: 90%">
                        <div>
                            <p class="fw-bold" style="color: var(--portal-section-heading-color)">Tags</p>
                            <p class="fw-bold" style="color: var(--portal-section-heading-color)">Groups</p>
                        </div>
                        @livewire('contact-tags', ['contact_id' => $new_contact->id])
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
                                        | {{ isset($head_office_user->position) ? $head_office_user->position : '' }}
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
                                @if ($new_contact->id != $contact->id)
                                    <option selected value="{{ $contact->id }}">{{ $contact->name }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <button type="submit" class="primary-btn my-4">Save</button>
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

        const add_personal = (type, name, id) => {
            if ($('#' + id).length) {
                return;
            }
            let inputField;

            if (type === "date" || type === "text") {
                inputField =
                    `<input type="${type}" id="${id}_input" name="${id}" placeholder="${name}" class="form-control" style="height:30px" required>`;
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
                    <input class="form-check-input" type="radio" name="${id}" id="other" value="other" required>
                    <label class="form-check-label" for="other">Other</label>
                </div>
                `
            } else if (type === 'textarea') {
                console.log("intextarea")
                inputField =
                    `<textarea spellcheck="true"  id="${id}_input" name="${id}" placeholder="${name}" class="form-control" style="height:60px" required></textarea>`
            }
            console.log(type, "type")

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

            checkProffession();
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
                         /*
                         $('.selected-assignes').append(`
                    <div class="d-flex gap-2">
                        <button class="badge badge-success badge-user border-0">${userName}</button>
                        <button class="badge badge-success badge-user border-0">${userPosition}</button>
                    </div>
                     `);
                     */
                    });
                }
                updateSelectedAssignes();
                $('#assigns-select').on('change', function() {
                    updateSelectedAssignes();
                });
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
        });

        $(".telephone").intlTelInput({
            fixDropdownWidth: true,
            showSelectedDialCode: true,
            strictMode: true,
            preventInvalidNumbers: true,
            initialCountry: 'gb'
        })
    </script>
    <script src="{{ asset('tribute/tribute.min.js') }}"></script>
    <script src="{{ asset('admin_assets/speech-to-text.js') }}"></script>
@endsection
@section('scripts')
    <script>
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
