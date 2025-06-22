@extends('layouts.head_office_app')
@section('title', 'Head office Settings')
@section('sub-header')

    <div class="container mx-auto">
        <a href="{{ route('head_office.contacts.view', $new_contact->id) }}" class="link text-info">Details</a>
        <a href="{{route('head_office.contact_view_timeline',$new_contact->id)}}" class="link text-info ms-4">Timeline</a>
        <a href="{{route('head_office.contact_intelligence',$new_contact->id)}}" class="link text-info ms-4">Intelligence</a>
        <a href="{{route('head_office.contact_matchs',$new_contact->id)}}" class="link text-info ms-4">Matches</a>
    </div>
@endsection
@section('content')
    @if (isset($new_contact))
   
        <div class="d-flex justify-content-between w-100">
            <div class="container-fluid">
                <div>
                    <a href="{{ route('head_office.contacts.edit', $new_contact->id) }}" class="primary-btn text-center" style="width: 115px">Edit Contact</a>
                    <div class="d-flex align-items-center gap-2" style="width:200px;">

                        <img style="width: 50px;height:50px;border-radius:50%; object-fit:cover; object-position:top"
                            id="output"
                            src="{{ isset($new_contact->avatar) && file_exists(public_path('v2/' . $new_contact->avatar)) ? asset('v2/' . $new_contact->avatar) : asset('images/svg/logo_blue.png') }}">

                        <p class="m-0 p-0 fw-bold">{{ $new_contact->name }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Personal</p>
                    </div>
                    <div class="personal-container" style="width:90%">
                        @if (isset($new_contact->date_of_birth))
                            <div class="" id="date_of_birth">
                                <label style="margin: 0;font-size: 12px;" for="date_of_birth">Date of birth</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <p>{{ Carbon\Carbon::parse($new_contact->date_of_birth)->format('Y-m-d') }}</p>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->nhs_no))
                            <div class="" id="nhs_no">
                                <label style="margin: 0;font-size: 12px;" for="nhs_no">NHS No</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <p>{{ $new_contact->nhs_no }}</p>
                                </div>
                            </div>
                        @endif

                        @if (isset($new_contact->ethnicity))
                            <div class="" id="ethnicity">
                                <label style="margin: 0;font-size: 12px;" for="ethnicity">Ethnicity</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <p>{{ $new_contact->ethnicity }}</p>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->sexual_orientation))
                            <div class="" id="sexual_orientation">
                                <label style="margin: 0;font-size: 12px;" for="sexual_orientation">Sexual orientation</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <p>{{ $new_contact->sexual_orientation }}</p>
                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->marital_status))
                            <div class="" id="marital_status">
                                <label style="margin: 0;font-size: 12px;" for="marital_status">Marital status</label>
                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                    <p>
                                        @if (isset($new_contact->marital_status) && $new_contact->marital_status != '')
                                            @if ($new_contact->marital_status == 'single')
                                                Single
                                            @elseif ($new_contact->marital_status == 'married')
                                                Married
                                            @elseif ($new_contact->marital_status == 'separated')
                                                Separated
                                            @elseif ($new_contact->marital_status == 'divorced')
                                                Divorced
                                            @endif

                                        @endif
                                    </p>

                                </div>
                            </div>
                        @endif
                        @if (isset($new_contact->gender))
                            <div class="" id="gender">
                                <label style="margin: 0;font-size: 12px;" for="marital_status">Gender</label>
                                <p>
                                    @if (isset($new_contact->gender) && $new_contact->gender != '')
                                        @if ($new_contact->gender == 'male')
                                            Male
                                        @elseif ($new_contact->gender == 'female')
                                            Female
                                        @elseif ($new_contact->gender == 'other')
                                            Other
                                        @endif
                                    @endif
                                </p>
                            </div>
                        @endif


                        @if (isset($new_contact->pronoun))
                            <div class="" id="pronoun">
                                <label style="margin: 0;font-size: 12px;" for="pronoun">Pronoun</label>
                                <p>{{ $new_contact->pronoun }}</p>
                            </div>
                        @endif
                        @if (isset($new_contact->religion))
                            <div class="" id="religion">
                                <label style="margin: 0;font-size: 12px;" for="religion">Religion</label>
                                <p>{{ $new_contact->religion }}</p>
                            </div>
                        @endif
                        @if (isset($new_contact->passport_no))
                            <div class="" id="passport_no">
                                <label style="margin: 0;font-size: 12px;" for="passport_no">Passport no</label>
                                <p>{{ $new_contact->passport_no }}</p>
                            </div>
                        @endif
                        @if (isset($new_contact->driver_license_no))
                            <div class="" id="driver_license_no">
                                <label style="margin: 0;font-size: 12px;" for="driver_license_no">Driver's license
                                    no</label>
                                <p>{{ $new_contact->driver_license_no }}</p>
                            </div>
                        @endif
                        @if (isset($new_contact->profession))
                            <div class="" id="profession">
                                <label style="margin: 0;font-size: 12px;" for="profession">Profession</label>
                                <p>{{ $new_contact->profession }}</p>
                            </div>
                        @endif
                        @if (isset($new_contact->registration_no))
                            <div class="" id="registration_no">
                                <label style="margin: 0;font-size: 12px;" for="registration_no">Registration No.</label>
                                <p>{{ $new_contact->registration_no }}</p>
                            </div>
                        @endif
                        @if (isset($new_contact->other))
                            <div class="" id="other">
                                <label style="margin: 0;font-size: 12px;" for="other">Other</label>
                                <p>{{ $new_contact->other }}</p>
                            </div>
                        @endif
                    </div>



                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Contact Info</p>


                    </div>
                    <div class="contact-info-container" style="width:90%">
                        @if (isset($new_contact->work_emails) &&
                                json_decode($new_contact->work_emails, true) != null &&
                                count(json_decode($new_contact->work_emails, true)) > 0)
                            <div class="" id="work_emails">
                                <label style="margin: 0;font-size: 12px;" for="work_emails">Work email</label>


                                @foreach (json_decode($new_contact->work_emails, true) as $work_email)
                                    <p class="m-0 p-0">{{ $work_email }}</p>
                                @endforeach


                            </div>
                        @endif
                        @if (isset($new_contact->personal_emails) &&
                                json_decode($new_contact->personal_emails, true) != null &&
                                count(json_decode($new_contact->personal_emails, true)) > 0)
                            <div class="" id="personal_emails">
                                <label style="margin: 0;font-size: 12px;" for="personal_emails">Personal email</label>

                                @foreach (json_decode($new_contact->personal_emails, true) as $personal_email)
                                    <p class="m-0 p-0">{{ $personal_email }}</p>
                                @endforeach

                            </div>
                        @endif
                        @if (isset($new_contact->work_mobiles) &&
                                json_decode($new_contact->work_mobiles, true) != null &&
                                count(json_decode($new_contact->work_mobiles, true)) > 0)
                            <div class="" id="work_mobiles">
                                <label style="margin: 0;font-size: 12px;" for="work_mobiles">Work mobile no.</label>

                                @foreach (json_decode($new_contact->work_mobiles, true) as $work_mobile)
                                    <div class="my-1">
                                        <input class="telephone" value="{{ $work_mobile }}" readonly required
                                            type="text" placeholder="Add a phone number" />
                                    </div>
                                @endforeach

                            </div>
                        @endif
                        @if (isset($new_contact->personal_mobiles) &&
                                json_decode($new_contact->personal_mobiles, true) != null &&
                                count(json_decode($new_contact->personal_mobiles, true)) > 0)
                            <div class="" id="personal_mobiles">
                                <label style="margin: 0;font-size: 12px;" for="personal_mobiles">Personal mobile
                                    no.</label>

                                @foreach (json_decode($new_contact->personal_mobiles, true) as $personal_mobile)
                                    <div class="my-1">
                                        <input class="telephone" value="{{ $personal_mobile }}" readonly required
                                            type="text" placeholder="Add a phone number" />
                                    </div>
                                @endforeach

                            </div>
                        @endif
                        @if (isset($new_contact->home_telephones) &&
                                json_decode($new_contact->home_telephones, true) != null &&
                                count(json_decode($new_contact->home_telephones, true)) > 0)
                            <div class="" id="home_telephones">
                                <label style="margin: 0;font-size: 12px;" for="home_telephones">Home mobile no.</label>

                                @foreach (json_decode($new_contact->home_telephones, true) as $home_telephone)
                                    <div class="my-1">
                                        <input class="telephone" value="{{ $home_telephone }}" readonly required
                                            type="text" placeholder="Add a phone number" />
                                    </div>
                                @endforeach

                            </div>
                        @endif
                        @if (isset($new_contact->work_telephones) &&
                                json_decode($new_contact->work_telephones, true) != null &&
                                count(json_decode($new_contact->work_telephones, true)) > 0)
                            <div class="" id="work_telephones">
                                <label style="margin: 0;font-size: 12px;" for="work_telephones">Work mobile no.</label>

                                @foreach (json_decode($new_contact->work_telephones, true) as $work_telephone)
                                    <div class="my-1">
                                        <input class="telephone" value="{{ $work_telephone }}" readonly required
                                            type="text" placeholder="Add a phone number" />
                                    </div>
                                @endforeach

                            </div>
                        @endif
                        @if (isset($new_contact->facebook))
                            <div class="" id="facebook">
                                <label style="margin: 0;font-size: 12px;" for="facebook">Facebook</label>
                                <a class="d-block" href="{{ $new_contact->facebook }}" target="_blank">
                                    {{ $new_contact->facebook }}
                                </a>
                            </div>
                        @endif
                        @if (isset($new_contact->instagram))
                            <div class="" id="instagram">
                                <label style="margin: 0;font-size: 12px;" for="instagram">Instagram</label>
                                <a class="d-block" href="{{ $new_contact->instagram }}" target="_blank">
                                    {{ $new_contact->instagram }}
                                </a>
                            </div>
                        @endif

                        @if (isset($new_contact->twitter))
                            <div class="" id="twitter">
                                <label style="margin: 0;font-size: 12px;" for="twitter">Twitter</label>
                                <a class="d-block" href="{{ $new_contact->twitter }}" target="_blank">
                                    {{ $new_contact->twitter }}
                                </a>
                            </div>
                        @endif
                        @if (isset($new_contact->other_link))
                            <div class="" id="other_link">
                                <label style="margin: 0;font-size: 12px;" for="other_link">Other</label>
                                <p class="m-0 p-0">{{ $new_contact->other_link }}</p>
                            </div>
                        @endif

                    </div>


                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Address</p>
                    </div>

                    <div class="address-container" style="width:90%">
                        @if (isset($contact_to_addresses) && count($contact_to_addresses) > 0)
                            <table class="row-border new-table dataTable no-footer" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Adress</th>
                                        <th>Tag</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contact_to_addresses as $contact_to_address)
                                    <tr>
                                            <td>{{ $contact_to_address->new_contact_address->name }}</td>
                                            <td>{{ $contact_to_address->new_contact_address->address }}</td>
                                            <td>
                                                @if (isset($contact_to_address->new_contact_address->address_tag))
                                                    @if ($contact_to_address->new_contact_address->address_tag == 'current_address')
                                                        Current address
                                                    @elseif ($contact_to_address->new_contact_address->address_tag == 'past_address')
                                                        Past address
                                                    @elseif ($contact_to_address->new_contact_address->address_tag == 'work_address')
                                                        Work address
                                                    @elseif ($contact_to_address->new_contact_address->address_tag == 'home_address')
                                                        Home address
                                                    @endif
                                                @endif
                                            </td>
                                    </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>


                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Associations</p>
                    </div>
                    <div class="association-container" style="width:90%">

                        @if (isset($new_contacts_relations) && count($new_contacts_relations) > 0)





                            <table class="row-border new-table dataTable no-footer" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Contact</th>
                                        <th>Relation</th>
                                        <th>Reverse Relation</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    @foreach ($new_contacts_relations as $index => $new_contacts_relation)
                                    <tr>
                                            <td>
                                                {{ $new_contacts_relation->target_contact->name }}
                                            </td>
                                            <td>
                                                {{ $new_contacts_relation->relation }}
                                            </td>
                                            <td>
                                                {{ $new_contacts_relation->reverse_relation }}
                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>

                        @endif

                    </div>
                    <input type="hidden" name="new_contacts_relations" id="new_contacts_relations">
                    <div class="d-flex align-items-center gap-2 justify-content-between my-2" style="width:90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Group</p>
                    </div>




                    @if (isset($contact_to_groups) && count($contact_to_groups) > 0)
                        @foreach ($contact_to_groups as $contact_to_group)
                            <button type="button"
                                class="btn btn-outline-secondary custom-button rounded fw-normal d-flex align-items-center gap-2 case-tags"
                                style="background: black;color:white }}">
                                {{ $contact_to_group->contact_group->group_name }}
                            </button>
                        @endforeach
                    @endif







                    <div class="my-2" style="width: 90%">















                        @php
                            $svgs = [
                                '
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 8V12M12 16H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 8.00008V12.0001M12 16.0001H12.01M3 7.94153V16.0586C3 16.4013 3 16.5726 3.05048 16.7254C3.09515 16.8606 3.16816 16.9847 3.26463 17.0893C3.37369 17.2077 3.52345 17.2909 3.82297 17.4573L11.223 21.5684C11.5066 21.726 11.6484 21.8047 11.7985 21.8356C11.9315 21.863 12.0685 21.863 12.2015 21.8356C12.3516 21.8047 12.4934 21.726 12.777 21.5684L20.177 17.4573C20.4766 17.2909 20.6263 17.2077 20.7354 17.0893C20.8318 16.9847 20.9049 16.8606 20.9495 16.7254C21 16.5726 21 16.4013 21 16.0586V7.94153C21 7.59889 21 7.42756 20.9495 7.27477C20.9049 7.13959 20.8318 7.01551 20.7354 6.91082C20.6263 6.79248 20.4766 6.70928 20.177 6.54288L12.777 2.43177C12.4934 2.27421 12.3516 2.19543 12.2015 2.16454C12.0685 2.13721 11.9315 2.13721 11.7985 2.16454C11.6484 2.19543 11.5066 2.27421 11.223 2.43177L3.82297 6.54288C3.52345 6.70928 3.37369 6.79248 3.26463 6.91082C3.16816 7.01551 3.09515 7.13959 3.05048 7.27477C3 7.42756 3 7.59889 3 7.94153Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 8V12M12 16H12.01M2 8.52274V15.4773C2 15.7218 2 15.8441 2.02763 15.9592C2.05213 16.0613 2.09253 16.1588 2.14736 16.2483C2.2092 16.3492 2.29568 16.4357 2.46863 16.6086L7.39137 21.5314C7.56432 21.7043 7.6508 21.7908 7.75172 21.8526C7.84119 21.9075 7.93873 21.9479 8.04077 21.9724C8.15586 22 8.27815 22 8.52274 22H15.4773C15.7218 22 15.8441 22 15.9592 21.9724C16.0613 21.9479 16.1588 21.9075 16.2483 21.8526C16.3492 21.7908 16.4357 21.7043 16.6086 21.5314L21.5314 16.6086C21.7043 16.4357 21.7908 16.3492 21.8526 16.2483C21.9075 16.1588 21.9479 16.0613 21.9724 15.9592C22 15.8441 22 15.7218 22 15.4773V8.52274C22 8.27815 22 8.15586 21.9724 8.04077C21.9479 7.93873 21.9075 7.84119 21.8526 7.75172C21.7908 7.6508 21.7043 7.56432 21.5314 7.39137L16.6086 2.46863C16.4357 2.29568 16.3492 2.2092 16.2483 2.14736C16.1588 2.09253 16.0613 2.05213 15.9592 2.02763C15.8441 2 15.7218 2 15.4773 2H8.52274C8.27815 2 8.15586 2 8.04077 2.02763C7.93873 2.05213 7.84119 2.09253 7.75172 2.14736C7.6508 2.2092 7.56432 2.29568 7.39137 2.46863L2.46863 7.39137C2.29568 7.56432 2.2092 7.6508 2.14736 7.75172C2.09253 7.84119 2.05213 7.93873 2.02763 8.04077C2 8.15586 2 8.27815 2 8.52274Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 8V12M12 16H12.01M7.8 21H16.2C17.8802 21 18.7202 21 19.362 20.673C19.9265 20.3854 20.3854 19.9265 20.673 19.362C21 18.7202 21 17.8802 21 16.2V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M11.9998 8.99999V13M11.9998 17H12.0098M10.6151 3.89171L2.39019 18.0983C1.93398 18.8863 1.70588 19.2803 1.73959 19.6037C1.769 19.8857 1.91677 20.142 2.14613 20.3088C2.40908 20.5 2.86435 20.5 3.77487 20.5H20.2246C21.1352 20.5 21.5904 20.5 21.8534 20.3088C22.0827 20.142 22.2305 19.8857 22.2599 19.6037C22.2936 19.2803 22.0655 18.8863 21.6093 18.0983L13.3844 3.89171C12.9299 3.10654 12.7026 2.71396 12.4061 2.58211C12.1474 2.4671 11.8521 2.4671 11.5935 2.58211C11.2969 2.71396 11.0696 3.10655 10.6151 3.89171Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M22 7.99992V11.9999M10.25 5.49991H6.8C5.11984 5.49991 4.27976 5.49991 3.63803 5.82689C3.07354 6.11451 2.6146 6.57345 2.32698 7.13794C2 7.77968 2 8.61976 2 10.2999L2 11.4999C2 12.4318 2 12.8977 2.15224 13.2653C2.35523 13.7553 2.74458 14.1447 3.23463 14.3477C3.60218 14.4999 4.06812 14.4999 5 14.4999V18.7499C5 18.9821 5 19.0982 5.00963 19.1959C5.10316 20.1455 5.85441 20.8968 6.80397 20.9903C6.90175 20.9999 7.01783 20.9999 7.25 20.9999C7.48217 20.9999 7.59826 20.9999 7.69604 20.9903C8.64559 20.8968 9.39685 20.1455 9.49037 19.1959C9.5 19.0982 9.5 18.9821 9.5 18.7499V14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15.0002 19C15.0002 20.6569 13.6571 22 12.0002 22C10.3434 22 9.00025 20.6569 9.00025 19M13.7968 6.23856C14.2322 5.78864 14.5002 5.17562 14.5002 4.5C14.5002 3.11929 13.381 2 12.0002 2C10.6195 2 9.50025 3.11929 9.50025 4.5C9.50025 5.17562 9.76825 5.78864 10.2037 6.23856M2.54707 8.32296C2.53272 6.87161 3.3152 5.51631 4.57928 4.80306M21.4534 8.32296C21.4678 6.87161 20.6853 5.51631 19.4212 4.80306M18.0002 11.2C18.0002 9.82087 17.3681 8.49823 16.2429 7.52304C15.1177 6.54786 13.5915 6 12.0002 6C10.4089 6 8.88283 6.54786 7.75761 7.52304C6.63239 8.49823 6.00025 9.82087 6.00025 11.2C6.00025 13.4818 5.43438 15.1506 4.72831 16.3447C3.92359 17.7056 3.52122 18.3861 3.53711 18.5486C3.55529 18.7346 3.58876 18.7933 3.73959 18.9036C3.87142 19 4.53376 19 5.85844 19H18.1421C19.4667 19 20.1291 19 20.2609 18.9036C20.4117 18.7933 20.4452 18.7346 20.4634 18.5486C20.4793 18.3861 20.0769 17.7056 19.2722 16.3447C18.5661 15.1506 18.0002 13.4818 18.0002 11.2Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M11 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21H15.2C16.8802 21 17.7202 21 18.362 20.673C18.9265 20.3854 19.3854 19.9265 19.673 19.362C20 18.7202 20 17.8802 20 16.2V13M13 17H7M15 13H7M20.1213 3.87868C21.2929 5.05025 21.2929 6.94975 20.1213 8.12132C18.9497 9.29289 17.0503 9.29289 15.8787 8.12132C14.7071 6.94975 14.7071 5.05025 15.8787 3.87868C17.0503 2.70711 18.9497 2.70711 20.1213 3.87868Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
                            ];

                        @endphp

                        @php
                            $getSvg = function ($index, $color = 'white', $width = 24, $height = 24) use ($svgs) {
                                if (!isset($svgs[$index])) {
                                    return null;
                                }

                                $svg = $svgs[$index];
                                $svg = preg_replace('/(?<!stroke-)width="\d+"/', 'width="' . $width . '"', $svg);
                                $svg = preg_replace('/height="\d+"/', 'height="' . $height . '"', $svg);
                                $svg = preg_replace('/stroke="[^"]+"/', 'stroke="' . $color . '"', $svg);

                                return $svg;
                            };
                        @endphp


                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Tags</p>
                        @if (isset($tag_to_contacts) && count($tag_to_contacts) > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($tag_to_contacts as $tag_to_contact)
                                    <button
                                        class="btn btn-outline-secondary custom-button rounded fw-normal d-flex align-items-center gap-2 case-tags"
                                        style="background: {{ $tag_to_contact->contact_tag->color }};color:{{ $tag_to_contact->contact_tag->text_color }}">
                                        {!! $getSvg($tag_to_contact->contact_tag->icon, $tag_to_contact->contact_tag->icon_color) !!} {{ $tag_to_contact->contact_tag->name }}
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="my-2" style="width: 90%">
                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Assign To</p>
                        @if (isset($user_to_contacts) && count($user_to_contacts) > 0)
                            <div class="d-flex align-items-center" bis_skin_checked="1">
                                @foreach ($user_to_contacts as $user_to_contact)
                                    <div id="user-profile-ico">
                                        <div class="user-icon-circle" title="User Profile">
                                            <div class="user-img-placeholder" id="user-img-place"
                                                style="width:30px;height:30px; margin-right:-6px">
                                                {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $user_to_contact->head_office_user->user->name))) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @endif


                        <div class="d-flex align-items-center" bis_skin_checked="1">




                        </div>



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

                </div>
            </div>

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


    @endif
    <script src="{{ asset('admin_assets/js/intlTelInput-jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin_assets/js/select2.min.js') }}"></script>
    <script>
        function getRandomMediumDarkColor() {
            // Generate random values for RGB components in the medium-dark range
            let r = Math.floor(Math.random() * 128);
            let g = Math.floor(Math.random() * 128);
            let b = Math.floor(Math.random() * 128);
            return `rgb(${r}, ${g}, ${b})`;
        }


        $(document).ready(function() {
            $('.user-img-placeholder').each(function() {
                let randomColor = getRandomMediumDarkColor();
                $(this).css('background-color', randomColor);
            });
        });

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
                    `<select name="${id}[]" multiple class="form-contorl email_select select_2"style="width: 100%"></select>`
            } else if (type === "phone_select") {
                inputField =
                    `<select name="${id}[]" multiple class="form-contorl phone_select select_2"style="width: 100%"></select>`
            }

            $('.contact-info-container').append(`
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

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validatePhone(phone) {
            var re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;;
            return re.test(phone);
        }

        $(".telephone").intlTelInput({
            fixDropdownWidth: true,
            showSelectedDialCode: true,
            strictMode: true,
            preventInvalidNumbers: true,
            initialCountry: 'gb'
        })



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
    </script>
    <script src="{{ asset('tribute/tribute.min.js') }}"></script>
    <script src="{{ asset('admin_assets/speech-to-text.js') }}"></script>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select_2').select2();

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
