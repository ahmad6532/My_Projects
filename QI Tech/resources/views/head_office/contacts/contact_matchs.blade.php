@extends('layouts.head_office_app')
@section('title', 'Head office Settings')


<style>
    div.dt-container .dt-layout-table {
        display: block !important;
    }

    div.dt-container div.dt-layout-cell.dt-end .dt-search label {
        display: none;
    }

    div.dt-container div.dt-layout-cell.dt-end .dt-search label {
        display: none;
    }

    div.dt-container div.dt-layout-cell.dt-end .dt-search::after {
        left: 8px;
    }

    div.dt-container .dt-search input {
        padding-left: 24px;
    }

    div.dt-container div.dt-layout-cell.dt-start select.dt-input {
        margin-right: 5px;
    }

    #locations-dataTable_wrapper {
        width: 80%;
        margin: 0 auto;
    }

    div.dt-container.dt-empty-footer .dt-scroll-body {
        border-bottom: none;
    }

    div.dt-container.dt-empty-footer .dt-scroll-body {
        border-bottom: none;
        padding-bottom: 10px;
    }

    .new-table tbody tr td:last-child {
        padding-right: 0px !important;
    }

    /* hidding edit button 😑 */
    .btn-wrapper-loc button:first-child {
        display: none;
    }

    p {
        margin-bottom: 0 !important;
    }

    #content .bg-success {
        background: rgb(0, 205, 69) !important;
    }

    #content .text-success {
        color: rgb(0, 205, 69) !important;
    }
</style>

@section('sub-header')
    <div class="container mx-auto">
        <a href="{{ route('head_office.contacts.view', $new_contact->id) }}" class="link text-info">Details</a>
        <a href="{{ route('head_office.contact_view_timeline', $new_contact->id) }}" class="link text-info ms-4">Timeline</a>
        <a href="{{ route('head_office.contact_intelligence', $new_contact->id) }}"
            class="link text-info ms-4">Intelligence</a>
        <a href="{{ route('head_office.contact_matchs', $new_contact->id) }}" class="link text-info ms-4">Matches</a>
    </div>
@endsection
@section('content')


    <div id="content" style="margin: 0;padding:0;">

        @include('layouts.error')

        <div class="container-lg mx-auto">
            @if (isset($matching_contacts) && count($matching_contacts) > 0)
                <table class="new-table mx-auto" style="width: 100% !important;" id="locations-dataTable">
                    <thead>
                        <th>Matched with</th>
                        <th style="text-align: left;">% Match</th>
                        <th style="text-align: center;">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($matching_contacts as $match_contact)
                            @php
                                $other_contact = $match_contact->get_other_matched_contact($new_contact->id);
                                $contact_to_addresses = $other_contact->contacts_to_addresses;
                                $other_contacts_relations = $other_contact->new_contacts_relations;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="width:200px;">

                                        <img style="width: 50px;height:50px;border-radius:50%; object-fit:cover; object-position:top"
                                            id="output"
                                            src="{{ isset($other_contact->avatar) && file_exists(public_path('v2/' . $other_contact->avatar)) ? asset('v2/' . $other_contact->avatar) : asset('images/svg/logo_blue.png') }}">

                                        <a target="_blank"
                                            href="{{ route('head_office.contacts.view', $other_contact->id) }}"
                                            class="m-0 p-0 fw-bold link-dark">{{ $other_contact->name }}</a>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 justify-content-between my-2"
                                        style="width:90%">
                                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Personal</p>
                                    </div>
                                    <div class="personal-container" style="width:90%">
                                        @if (isset($other_contact->date_of_birth))
                                            <div class="" id="date_of_birth">
                                                <label style="margin: 0;font-size: 12px;" for="date_of_birth">Date of
                                                    birth</label>
                                                <div class="d-flex align-items-center gap-2 align-items-center w-100">
                                                    <p class="m-0">
                                                        {{ Carbon\Carbon::parse($other_contact->date_of_birth)->format('Y-m-d') }}
                                                    </p>
                                                    <i class="fa-solid fa-check text-success"></i>
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->nhs_no))
                                            <div class="" id="nhs_no">
                                                <label style="margin: 0;font-size: 12px;" for="nhs_no">NHS No</label>
                                                <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                                    <p>{{ $other_contact->nhs_no }}</p>
                                                    @if ($other_contact->nhs_no === $new_contact->nhs_no)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->ethnicity))
                                            <div class="" id="ethnicity">
                                                <label style="margin: 0;font-size: 12px;" for="ethnicity">Ethnicity</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>{{ $other_contact->ethnicity }}</p>
                                                    @if ($other_contact->ethnicity === $new_contact->ethnicity)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->sexual_orientation))
                                            <div class="" id="sexual_orientation">
                                                <label style="margin: 0;font-size: 12px;" for="sexual_orientation">Sexual
                                                    Orientation</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>{{ $other_contact->sexual_orientation }}</p>
                                                    @if ($other_contact->sexual_orientation === $new_contact->sexual_orientation)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->marital_status))
                                            <div class="" id="marital_status">
                                                <label style="margin: 0;font-size: 12px;" for="marital_status">Marital
                                                    Status</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>
                                                        @if ($other_contact->marital_status == 'single')
                                                            Single
                                                        @elseif ($other_contact->marital_status == 'married')
                                                            Married
                                                        @elseif ($other_contact->marital_status == 'separated')
                                                            Separated
                                                        @elseif ($other_contact->marital_status == 'divorced')
                                                            Divorced
                                                        @endif
                                                    </p>
                                                    @if ($other_contact->marital_status === $new_contact->marital_status)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->gender))
                                            <div class="" id="gender">
                                                <label style="margin: 0;font-size: 12px;" for="gender">Gender</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>
                                                        @if ($other_contact->gender == 'male')
                                                            Male
                                                        @elseif ($other_contact->gender == 'female')
                                                            Female
                                                        @elseif ($other_contact->gender == 'other')
                                                            Other
                                                        @endif
                                                    </p>
                                                    @if ($other_contact->gender === $new_contact->gender)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->pronoun))
                                            <div class="" id="pronoun">
                                                <label style="margin: 0;font-size: 12px;" for="pronoun">Pronoun</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>{{ $other_contact->pronoun }}</p>
                                                    @if ($other_contact->pronoun === $new_contact->pronoun)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->religion))
                                            <div class="" id="religion">
                                                <label style="margin: 0;font-size: 12px;" for="religion">Religion</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>{{ $other_contact->religion }}</p>
                                                    @if ($other_contact->religion === $new_contact->religion)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->passport_no))
                                            <div class="" id="passport_no">
                                                <label style="margin: 0;font-size: 12px;" for="passport_no">Passport
                                                    No</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>{{ $other_contact->passport_no }}</p>
                                                    @if ($other_contact->passport_no === $new_contact->passport_no)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->driver_license_no))
                                            <div class="" id="driver_license_no">
                                                <label style="margin: 0;font-size: 12px;" for="driver_license_no">Driver's
                                                    License No</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>{{ $other_contact->driver_license_no }}</p>
                                                    @if ($other_contact->driver_license_no === $new_contact->driver_license_no)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->profession))
                                            <div class="" id="profession">
                                                <label style="margin: 0;font-size: 12px;"
                                                    for="profession">Profession</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>{{ $other_contact->profession }}</p>
                                                    @if ($other_contact->profession === $new_contact->profession)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->registration_no))
                                            <div class="" id="registration_no">
                                                <label style="margin: 0;font-size: 12px;"
                                                    for="registration_no">Registration No.</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>{{ $other_contact->registration_no }}</p>
                                                    @if ($other_contact->registration_no === $new_contact->registration_no)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->other))
                                            <div class="" id="other">
                                                <label style="margin: 0;font-size: 12px;" for="other">Other</label>
                                                <div class="d-flex align-items-center gap-2  w-100">
                                                    <p>{{ $other_contact->other }}</p>
                                                    @if ($other_contact->other === $new_contact->other)
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                    </div>



                                    <div class="d-flex align-items-center gap-2 justify-content-between my-2"
                                        style="width:90%">
                                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Contact Info
                                        </p>


                                    </div>
                                    <div class="contact-info-container" style="width:90%">
                                        @if (isset($other_contact->work_emails) &&
                                                json_decode($other_contact->work_emails, true) != null &&
                                                count(json_decode($other_contact->work_emails, true)) > 0)
                                            <div id="work_emails">
                                                <label style="margin: 0;font-size: 12px;" for="work_emails">Work
                                                    email</label>
                                                @foreach (json_decode($other_contact->work_emails, true) as $work_email)
                                                    <p class="m-0 p-0">{{ $work_email }}</p>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (isset($other_contact->personal_emails) &&
                                                json_decode($other_contact->personal_emails, true) != null &&
                                                count(json_decode($other_contact->personal_emails, true)) > 0)
                                            <div id="personal_emails">
                                                <label style="margin: 0;font-size: 12px;" for="personal_emails">Personal
                                                    email</label>
                                                @foreach (json_decode($other_contact->personal_emails, true) as $personal_email)
                                                    <p class="m-0 p-0">{{ $personal_email }}</p>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (isset($other_contact->work_mobiles) &&
                                                json_decode($other_contact->work_mobiles, true) != null &&
                                                count(json_decode($other_contact->work_mobiles, true)) > 0)
                                            <div id="work_mobiles">
                                                <label style="margin: 0;font-size: 12px;" for="work_mobiles">Work mobile
                                                    no.</label>
                                                @foreach (json_decode($other_contact->work_mobiles, true) as $work_mobile)
                                                    <div class="my-1">
                                                        <input class="telephone" value="{{ $work_mobile }}" readonly
                                                            required type="text" placeholder="Add a phone number" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (isset($other_contact->personal_mobiles) &&
                                                json_decode($other_contact->personal_mobiles, true) != null &&
                                                count(json_decode($other_contact->personal_mobiles, true)) > 0)
                                            <div id="personal_mobiles">
                                                <label style="margin: 0;font-size: 12px;" for="personal_mobiles">Personal
                                                    mobile no.</label>
                                                @foreach (json_decode($other_contact->personal_mobiles, true) as $personal_mobile)
                                                    <div class="my-1">
                                                        <input class="telephone" value="{{ $personal_mobile }}" readonly
                                                            required type="text" placeholder="Add a phone number" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (isset($other_contact->home_telephones) &&
                                                json_decode($other_contact->home_telephones, true) != null &&
                                                count(json_decode($other_contact->home_telephones, true)) > 0)
                                            <div id="home_telephones">
                                                <label style="margin: 0;font-size: 12px;" for="home_telephones">Home
                                                    mobile no.</label>
                                                @foreach (json_decode($other_contact->home_telephones, true) as $home_telephone)
                                                    <div class="my-1">
                                                        <input class="telephone" value="{{ $home_telephone }}" readonly
                                                            required type="text" placeholder="Add a phone number" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (isset($other_contact->work_telephones) &&
                                                json_decode($other_contact->work_telephones, true) != null &&
                                                count(json_decode($other_contact->work_telephones, true)) > 0)
                                            <div id="work_telephones">
                                                <label style="margin: 0;font-size: 12px;" for="work_telephones">Work
                                                    mobile no.</label>
                                                @foreach (json_decode($other_contact->work_telephones, true) as $work_telephone)
                                                    <div class="my-1">
                                                        <input class="telephone" value="{{ $work_telephone }}" readonly
                                                            required type="text" placeholder="Add a phone number" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (isset($other_contact->facebook))
                                            <div id="facebook">
                                                <label style="margin: 0;font-size: 12px;" for="facebook">Facebook</label>
                                                <a class="d-block" href="{{ $other_contact->facebook }}"
                                                    target="_blank">{{ $other_contact->facebook }}</a>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->instagram))
                                            <div id="instagram">
                                                <label style="margin: 0;font-size: 12px;"
                                                    for="instagram">Instagram</label>
                                                <a class="d-block" href="{{ $other_contact->instagram }}"
                                                    target="_blank">{{ $other_contact->instagram }}</a>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->twitter))
                                            <div id="twitter">
                                                <label style="margin: 0;font-size: 12px;" for="twitter">Twitter</label>
                                                <a class="d-block" href="{{ $other_contact->twitter }}"
                                                    target="_blank">{{ $other_contact->twitter }}</a>
                                            </div>
                                        @endif

                                        @if (isset($other_contact->other_link))
                                            <div id="other_link">
                                                <label style="margin: 0;font-size: 12px;" for="other_link">Other</label>
                                                <p class="m-0 p-0">{{ $other_contact->other_link }}</p>
                                            </div>
                                        @endif
                                    </div>



                                    <div class="d-flex align-items-center gap-2 justify-content-between my-2"
                                        style="width:90%">
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
                                                            <td>{{ $contact_to_address->new_contact_address->address }}
                                                            </td>
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


                                    <div class="d-flex align-items-center gap-2 justify-content-between my-2"
                                        style="width:90%">
                                        <p class="fw-bold" style="color: var(--portal-section-heading-color)">Associations
                                        </p>
                                    </div>
                                    <div class="association-container" style="width:90%">

                                        @if (isset($other_contacts_relations) && count($other_contacts_relations) > 0)
                                            <table class="row-border new-table dataTable no-footer" id="dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>Contact</th>
                                                        <th>Relation</th>
                                                        <th>Reverse Relation</th>
                                                    </tr>
                                                </thead>
                                                <tbody>


                                                    @foreach ($other_contacts_relations as $index => $other_contacts_relation)
                                                        <tr>
                                                            <td>
                                                                {{ $other_contacts_relation->target_contact->name }}
                                                            </td>
                                                            <td>
                                                                {{ $other_contacts_relation->relation }}
                                                            </td>
                                                            <td>
                                                                {{ $other_contacts_relation->reverse_relation }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif

                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div data-toggle="tooltip" data-bs-placement="auto"
                                            title="{{ $match_contact->match }}%" class="progress mt-1"
                                            role="progressbar" aria-label="Case progress" aria-valuenow="75"
                                            aria-valuemin="0" aria-valuemax="100"
                                            style="height: 10px;width: 85%;margin-left:2px;cursor: pointer;">
                                            <div class="progress-bar 
                                @if ($match_contact->match <= 20) bg-danger
                                @elseif($match_contact->match <= 40)
                                bg-primary
                                @elseif($match_contact->match <= 60)
                                bg-info
                                @elseif($match_contact->match <= 70)
                                bg-success
                                @elseif($match_contact->match <= 100)
                                bg-success @endif
                                "
                                                style="width: {{ $match_contact->match }}%"></div>
                                        </div>
                                        {{ $match_contact->match }}%
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <a class="link-secondary link-underline-opacity-0" href="">Merge</a> | <a
                                        class="link-secondary link-underline-opacity-0" href="">Different
                                        Person</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="mx-4">No matching contacts yet!</p>
            @endif
        </div>
    </div>


@section('scripts')





    <script src="{{ asset('js/alertify.min.js') }}"></script>


    <script>
        $(document).ready(function() {

            let table = new DataTable('#locations-dataTable', {
                width: '100%',
                fixedHeader: {
                    header: true
                },
                scrollCollapse: true,
                scrollY: '70vh',
                "initComplete": function(settings, json) {
                    $('body').find('.dt-scroll-body').addClass("custom-scroll");
                }
            });
        });
    </script>





@endsection
@endsection
