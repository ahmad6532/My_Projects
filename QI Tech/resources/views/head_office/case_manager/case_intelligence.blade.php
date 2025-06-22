@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())

@section('sub-header')
@include('head_office.case_manager.notes.sub-header')
@endsection

@section('content')
<div id="content">
@include('layouts.error')
    <div class="card card-qi content_widthout_sidebar">
        <div class="card-body">
            <div class="top-nav row">

                <div class="col-sm-12">
                    <form method="get" style="float: right">
                        <select name="cases" class="form-control mb-1 inline" style="width:auto;"
                            onchange="this.form.submit();">
                            <option value="my_cases_only">Periods</option>
                            <option value="all_cases">All
                                Cases</option>
                        </select>
                        <select name="incidents" class="form-control mb-1 inline" style="width:auto;"
                            onchange="this.form.submit();">
                            <option value="all">All Incident Types
                            </option>
                        </select>
                        <a href="http://localhost:8000/head_office/case/manager/case/record" class="btn btn-info">Add
                            New</a>
                    </form>
                </div>
            </div>
            <div class="cm_content pt-2">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="nav nav-tabs" style="display:block;" id="myTab" role="tablist">
                                
                                @foreach ($case_contacts as $key=>$case_contact)
                                @php $contact = $case_contact->contact; @endphp
                                <div class="pointer" id="tab_{{$key}}_tab" data-bs-toggle="tab"
                                    data-bs-target="#tab_{{$key}}" role="tab" aria-controls="tab_{{$key}}"
                                    aria-selected="true">
                                    <div class="case_1 relative">
                                        <div class="card border-left-secondary shadow w-100">
                                            <div class="card-body">
                                                <div class="row align-items-center ">
                                                    <div class="col-sm-4 ">
                                                        <div class=" font-weight-bold text-black " title="Name">
                                                            {{$contact->first_name}} {{$contact->last_name}}
                                                        </div>
                                                    </div>
                                                    <div class="col ">
                                                        <p>
                                                            {{$contact->contact_cases()->where('case_id',$case->id)->first()->type}}
                                                            
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="col " style="float: right;">
                                                        <a href="#" class="float-right"><i
                                                                class="fa fa-angle-right fa-2x text-gray-300"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <div class="pointer active" id="location_cases_tab" data-bs-toggle="tab" data-bs-target="#location_cases" role="tab"
                                    aria-controls="location_cases" aria-selected="true">
                                    <div class="case_1 relative">
                                        <div class="card border-left-secondary shadow w-100">
                                            @if(strpos($case->location->registered_company_name, 'External') === false)
                                            <div class="card-body">
                                                <div class="row align-items-center ">
                                                    <div class="col-sm-4 ">

                                                        <div class=" font-weight-bold text-black " title="Name">
                                                            {{$case->location->registered_company_name}}</div>
                                                        </div>
                                                    <div class="col ">
                                                        <p>
                                                            Location
                                                        </p>
                                                    </div>
                                                    <div class="col">
                                                         
                                                            <a target="_blank" style="color: #34BFAF;" href="{{route('head_office.color_branding_get',['id'=>$case->location->id])}}">
                                                                Remotely Access
                                                            </a>
                                                        
                                                    </div>

                                                    <div class="col " style="float: right;">
                                                        <a href="#" class="float-right"><i
                                                                class="fa fa-angle-right fa-2x text-gray-300"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="pointer" id="linked_cases_tab" data-bs-toggle="tab" data-bs-target="#linked_cases" role="tab"
                                    aria-controls="linked_cases" aria-selected="true">
                                    <div class="case_1 relative">
                                        <div class="card border-left-secondary shadow w-100">
                                            <div class="card-body">
                                                <div class="row align-items-center ">
                                                    <div class="col-sm-4 ">

                                                        <div class=" font-weight-bold text-black " title="Name">
                                                            Linked Cases?</div>
                                                    </div>
                                                    <div class="col-sm-4 ">
                                                        <p>
                                                            There are {{count($case->allLinkedCases())}} possibly linked cases
                                                        </p>
                                                    </div>

                                                    <div class="col-sm-4 " style="float: right;">
                                                        <a href="#" class="float-right"><i
                                                                class="fa fa-angle-right fa-2x text-gray-300"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="pointer" id="linked_cases_tab" data-bs-toggle="tab" data-bs-target="#linked_contacts" role="tab"
                                    aria-controls="linked_cases" aria-selected="true">
                                    <div class="case_1 relative">
                                        <div class="card border-left-secondary shadow w-100">
                                            <div class="card-body">
                                                <div class="row align-items-center ">
                                                    <div class="col-sm-4 ">
                                                        @php
                                                                $totalMatches = $case->linked_contacts->sum(function ($contact) {
                                                                    return $contact->contact->get_all_matching_contacts()->count();
                                                                });
                                                                $all_linked_contacts = $all_linked_contacts = $case->linked_contacts->map(function ($con) {
                                                                    return $con->contact; // Extract the contact collection for each linked contact
                                                                })->unique('id');
                                                            @endphp

                                                        <div class=" font-weight-bold text-black " title="Name">
                                                            @if(isset($all_linked_contacts) && count($all_linked_contacts) > 0)
                                                                {{implode(', ', $all_linked_contacts->pluck('name')->toArray())}}
                                                            @else
                                                            Linked Contacts
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 ">
                                                        <p>
                                                            
                                                            {{ $totalMatches }} possible matches
                                                        </p>
                                                    </div>

                                                    <div class="col-sm-4 " style="float: right;">
                                                        <a href="#" class="float-right"><i
                                                                class="fa fa-angle-right fa-2x text-gray-300"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content" id="myTabContent">
                                        @foreach ($case_contacts as $k => $case_contact)
                                        @php $contact = $case_contact->contact; @endphp
                                        <div class="tab-pane fade show" id="tab_{{$k}}" role="tabpanel" aria-labelledby="tab_{{$k}}-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3 class="text-center text-info h3 font-weight-bold">@if($contact->conatact_patient)Patient Cases @elseif($contact->conatact_prescriber)Health Care Professional Cases @endif</h3>
                                        
                                                    <div class="case-intelligence-container">
                                                        @php $data = count($contact->contact_cases) @endphp
                                                        <div class="center-line" style="width: {{$data * 58}}px">
                                                            @foreach($contact->contact_cases as $c)
                                                            @if($c->case->linked_location_incident->incident->form)
                                                                <div class="item-line" @if($c->case->linked_location_incident->incident->form->color_code) style="background-color: {{$c->case->linked_location_incident->incident->form->color_code}}" @endif>
                                                                    <p><a href="{{route('case_manager.view_intelligence',$c->case->id)}}"
                                                                        title="{{$c->case->status}}" target="_blank">{{$c->case->linked_location_incident->incident->form->name}}</a> </p>
                                                                    <div>{{$c->case->created_at->format(config('app.dateFormat'))}}</div>
                                                                </div>
                                                            @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        @endforeach
                                    <div class="tab-pane fade show active" id="location_cases" role="tabpanel" aria-labelledby="location_cases-tab">
                                            <h3 class="text-center text-info h3 font-weight-bold">Location Cases
                                            </h3>
                                            <div class="case-intelligence-container">
                                                @if(count($case->location->cases) > 0)
                                                @php $data = count($case->location->cases) @endphp
                                                <div class="center-line" style="width: {{$data * 58}}px">
                                                    @foreach($case->location->cases()->orderBy('id', 'DESC')->get() as $new_case)
                                                    <div class="item-line" @if($new_case->linked_location_incident->incident->form && $new_case->linked_location_incident->incident->form->color_code) style="background-color: {{$new_case->linked_location_incident->incident->form->color_code}}" @endif>
                                                        <p><a href="{{route('case_manager.view_intelligence',$new_case->id)}}"
                                                                target="_blank" title="{{$new_case->status}}">{{$new_case->linked_location_incident->incident->form && $new_case->linked_location_incident->incident->form->name}}</a> </p>
                                                        <div>{{$case->created_at->format(config('app.dateFormat'))}}</div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane fade show " id="linked_cases" role="tabpanel"
                                            aria-labelledby="linked_cases-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3 class="text-center text-info h3 font-weight-bold">Linked Cases</h3>
                                                    <div class="case-intelligence-container">
                                                        @if (count($case->allLinkedCases()) != 0)
                                                        <table class="table new-table">
                                                            <thead>
                                                                <th>Case Id</th>
                                                                <th>Type</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($case->allLinkedCases() as $link_case )
                                                                @php
                                                                    $link_case_other = $link_case->otherCase($case->id)->first();
                                                                @endphp
                                                                    <tr>
                                                                        <td>#{{$link_case_other->id}}</td>
                                                                        <td>{{$link_case_other->incident_type}}</td>
                                                                        <td><div class="cm_case_status font-weight-bold d-flex align-items-center fw-semibold @if($link_case_other->isArchive == true) text-info @elseif($link_case_other->status == 'waiting' && $link_case_other->requires_final_approval == true) text-warning @elseif($link_case_other->status == 'open') text-success @elseif($link_case_other->status == 'closed') text-danger @endif" ><i class="fa-solid fa-circle mx-2" style="font-size: 6px;margin-top:3px;"></i>@if($link_case_other->isArchived) Archived @elseif($link_case_other->status == 'waiting' && $link_case_other->requires_final_approval == true) Final Approval @else {{$link_case_other->status()}} @endif</div></td>
                                                                        @if ($case->isArchive == true)
                                                                            <td></td>
                                                                        @else
                                                                        <td><a href="#" class="btn shadow-none" title="Unlink" data-bs-toggle="modal" data-bs-target="#unlink_case_modal" data-toggle="tooltip" data-bs-placement="right" data-id='{{$link_case_other->id}}' id="unlink-btn"><i class="fa-solid fa-link-slash"></i></a></td>    
                                                                        @endif
                                                                    </tr>
                                                                    
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                    <p>There are no linked cases</p>
                                                    @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="tab-pane fade show " id="linked_contacts" role="tabpanel"
                                            aria-labelledby="linked_cases-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3 class="text-center text-info h3 font-weight-bold">Linked Contacts</h3>
                                                    <div class="case-intelligence-container">

                                                        <div class="container-lg mx-auto">
                                                            @if (isset($case->linked_contacts[0]->contact) && count($case->linked_contacts[0]->contact->get_all_matching_contacts()) > 0)
                                                                <table class="new-table mx-auto" style="width: 100% !important;" id="locations-dataTable">
                                                                    <thead>
                                                                        <th>Matched with</th>
                                                                        <th style="text-align: left;">% Match</th>
                                                                        <th style="text-align: center;">Action</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($case->linked_contacts as $new_contact )
                                                                            @foreach ($new_contact->contact->get_all_matching_contacts() as $match_contact)
                                                                                @php
                                                                                    $other_contact = $match_contact->get_other_matched_contact($new_contact->contact_id);
                                                                                @endphp
                                                                                @if (isset($other_contact))
                                                                                    @php
                                                                                        $contact_to_addresses = $other_contact->contacts_to_addresses;
                                                                                        $other_contacts_relations = $other_contact->new_contacts_relations ;
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
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @else
                                                                <p class="mx-4">No matching contacts yet!</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
            </div>


        </div>
    </div>
</div>


@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('admin_assets/css/intelligence.css')}}" />
@endsection

@section('scripts')

<script src="{{asset('tribute/tribute.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('admin_assets/speech-to-text.js')}}"></script>
@endsection