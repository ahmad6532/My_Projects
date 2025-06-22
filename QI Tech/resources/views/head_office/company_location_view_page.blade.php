@extends('layouts.head_office_app')
@section('title', 'Head office Settings')

<style>
    .grid-wrap{
        display: grid;
        grid-template-columns: 30% 1fr;
    }
    .grid-wrap span{
        display: none;
    }
    #content {
        min-height: unset !important;
        overflow: unset !important;
    }

    .extras div input::placeholder{
        color: #2e2d2d63 !important;
    }

    .tooltip {
        position: relative !important;
        bottom:  -16px !important;
    }
    .category-Wrapper button, .category-Wrapper a {
        padding: .375rem .75rem !important;
        border: none;
        border-radius: 4px;
    }

    .category-Wrapper .cross-cat-btn{
        border: none;
    }

    .all_locations .custom-button{
        font-size: 12px !important;
        padding: 2px 4px;
        scale: 0.8;
    }
    .all_locations .custom-button > i{
        display: none !important;
    }
    .all_locations .custom-button:hover .fa-xmark{
        display: block !important;
    }
</style>

@section('sub-header')

    <div class="container mx-auto">
        <a href="{{route('head_office.location_page_view',$ho_location->id)}}" class="link text-info">Details</a>
        <a href="{{route('head_office.location_page_view_timeline',$ho_location->id)}}" class="link text-info ms-4">Timeline</a>
    </div>
@endsection
@section('content')


    <div id="content" style="margin: 0;padding:0;margin-bottom:8rem;">
        @php
            $location = $ho_location->location;
        @endphp
        
        @include('layouts.error')

        <div class="container-lg mx-auto">
            <div class="row">
                <div class="col-7">
                    <form action="{{route('head_office.location.update')}}" method="POST" enctype="multipart/form-data" id="form">
                        @csrf
                        <input type="hidden" name="ho_location_id" value="{{$ho_location->id}}">
                        {{-- Avatar --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <label for="file" class="user-icon-circle ">
                                    <img style="width: 100px;height:100px;border-radius:50%; border:0.5px solid gray; object-fit:cover; object-position:top"
                                        id="output"
                                        src="{{ ($ho_location->org_settings() !== null && $ho_location->org_settings()->setting_logo() !== null) ? $ho_location->org_settings()->setting_logo() : asset('images/svg/logo_blue.png') }}">
                                        @if ($ho_location->org_settings() == null)
                                            <p class="m-0 text-secondary" style="font-size: 12px;">No theme applied, Logo changes will not be reflected</p>
                                            
                                        @endif
                                </label>
                                <input id="file" type="file" name="logo_file" class="d-none" accept=".png, .jpg , .jpeg" onchange="loadFile(event)" />
                                <h6 class="h4 m-0">{{$location->trading_name}} {{isset($location->location_code) ? '('. $location->location_code . ')' : ''}}</h6>
                            </div>
                            <button class="primary-btn" type="submit">Update</button>
                        </div>
                        {{-- Location Info --}}
                        @php
    $ho_name = DB::table('head_offices')
        ->where('id', $ho_location->head_office_id)
        ->value('company_name');
            $ho_name = Str::replace(' ', '_', $ho_name);
            $loc_username = $location->username;
            $loc_username = rtrim($loc_username, '.');
            if (!Str::contains($loc_username, '.' . $ho_name)) {
                $loc_username .= '.' . $ho_name;
        }
@endphp

                        <div class="mt-0 " style="margin-left: 7.5rem;">
                            <div>
                                <p class="m-0 fw-semibold text-info mb-2" >General</p>
                                <div class="grid-wrap resizing-input location-input">
                                    <p class="m-0">Username:</p>
                                    <input type="text" name="username" value="{{ $loc_username }}" placeholder="Username" minlength="5">
                                    <span></span>
                                </div>
                                <div class="grid-wrap resizing-input location-input">
                                    <p class="m-0">Company Name:</p>
                                    <p class="m-0">{{$location->registered_company_name}}</p>
                                </div>
                                <div class="grid-wrap resizing-input location-input">
                                    <p class="m-0">Registration no:</p>
                                    <input type="text" name="registration_no" value="{{$location->registration_no}}" placeholder="Registration no" min="5"> 
                                    <span></span>
                                </div>
                                <div class="grid-wrap resizing-input location-input">
                                    <p class="m-0">Location ID:</p>
                                    <input type="text" name="location_code" value="{{$location->location_code}}" placeholder="Location ID" min="5"> 
                                    <span></span>
                                </div>
                                <div class="grid-wrap resizing-input location-input">
                                    <p class="m-0">ODS/Contractor code:</p>
                                    <input style="background-color:rgba(255, 0, 0, 0); " disabled type="text" name="location_ods_code" value="{{$location->ods_name}}" placeholder="Location ODS" min="5"> 
                                    <span></span>
                                </div>
                            </div>
                            <div>
                                <p class="m-0 fw-semibold text-info mb-2 mt-3" >Details</p>
                                <div class="grid-wrap resizing-input location-input d-flex align-items-center">
                                    <p class="m-0">Address:</p>
                                    <p class="m-0 ms-2">{{$location->full_address}}</p>
                                    <button type="button" class="btn btn-link p-0 ms-2" data-bs-toggle="modal" data-bs-target="#editAddressModal" style="text-decoration: none;">
                                        <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                        width="20px" height="20px" viewBox="0 0 494.936 494.936"
                                        xml:space="preserve">
                                   <g>
                                       <g>
                                           <path d="M389.844,182.85c-6.743,0-12.21,5.467-12.21,12.21v222.968c0,23.562-19.174,42.735-42.736,42.735H67.157
                                               c-23.562,0-42.736-19.174-42.736-42.735V150.285c0-23.562,19.174-42.735,42.736-42.735h267.741c6.743,0,12.21-5.467,12.21-12.21
                                               s-5.467-12.21-12.21-12.21H67.157C30.126,83.13,0,113.255,0,150.285v267.743c0,37.029,30.126,67.155,67.157,67.155h267.741
                                               c37.03,0,67.156-30.126,67.156-67.155V195.061C402.054,188.318,396.587,182.85,389.844,182.85z"/>
                                           <path d="M483.876,20.791c-14.72-14.72-38.669-14.714-53.377,0L221.352,229.944c-0.28,0.28-3.434,3.559-4.251,5.396l-28.963,65.069
                                               c-2.057,4.619-1.056,10.027,2.521,13.6c2.337,2.336,5.461,3.576,8.639,3.576c1.675,0,3.362-0.346,4.96-1.057l65.07-28.963
                                               c1.83-0.815,5.114-3.97,5.396-4.25L483.876,74.169c7.131-7.131,11.06-16.61,11.06-26.692
                                               C494.936,37.396,491.007,27.915,483.876,20.791z M466.61,56.897L257.457,266.05c-0.035,0.036-0.055,0.078-0.089,0.107
                                               l-33.989,15.131L238.51,247.3c0.03-0.036,0.071-0.055,0.107-0.09L447.765,38.058c5.038-5.039,13.819-5.033,18.846,0.005
                                               c2.518,2.51,3.905,5.855,3.905,9.414C470.516,51.036,469.127,54.38,466.61,56.897z"/>
                                       </g>
                                   </g>
                                   </svg>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="grid-wrap  w-100 ">
                                        {{-- <p class="m-0 position-relative">Telephone: <span style="display: flex !important;font-size:10px; width:fit-content; position:absolute; left:-125px; top:5px;" class="badge bg-info">This will be used to 2FA</span>
                                        </p> --}}
                                        <p class="m-0">Telephone: <i class=" rounded-circle bg-black text-white" style="font-style: normal; font-size: 11px;padding:4px;" data-toggle="tooltip" data-bs-placement="top" title="This is used for Two-Factor Authentication">2FA</i></p>
                                        <input class="telephone form-control form-control-sm" autocomplete="off" type="tel" name="telephone_no" value="{{$location->telephone_no}}" placeholder="Telephone no"> 
                                        <span></span>
                                    </div>
                                    
                                    <svg style="cursor: pointer;" onclick="insert_new_phone('phone_container', 'phones')"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        
                                </svg>
                                
                                </div>
                                <div id="phone_container" class="d-flex flex-column gap-1 ">
                                    @if (isset($phones) && !empty($phones))
                                        @foreach ($phones as $index => $phone)
                                            <div id="phone_input_{{ $index }}" class='d-flex flex-column gap-1'>
                                                <input class="telephone form-control form-control-sm shadow-none" required type="tel" placeholder="Add a phone number" name="phones[]" value="{{ $phone }}" />
                                                <input class="form-control form-control-sm shadow-none" type="text" name="phone_note[]" placeholder="Add a note (e.g. Manager's mobile)" value="{{ isset($phone_notes[$index]) ? $phone_notes[$index] : '' }}" />
                                                <div class='d-flex align-items-center gap-2'>
                                                    <input type="checkbox" name="primary_phone" value="{{ $index }}" onclick="setPrimary(event,'primary_phone', {{ $index }})" tooltip='Set this phone as primary phone'>
                                                    <span>Make Primary</span>
                                                    <svg fill="none" onclick="remove_item('phone_input_{{ $index }}')" width="24" height="24" viewBox="0 0 24 24" style="cursor: pointer;">
                                                        <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                    @endif
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-4 mb-2">
                                    <div class="grid-wrap  w-100 ">
                                        {{-- <p class="m-0 position-relative">Email:
                                        </p> --}}
                                        <p class="m-0">Email: <i class=" rounded-circle bg-black text-white" style="font-style: normal; font-size: 11px;padding:4px;" data-toggle="tooltip" data-bs-placement="top" title="This is used for Two-Factor Authentication">2FA</i></p>
                                        <input style = "z-index: 0;" autocomplete="off" class="email-input form-control form-control-sm " type="email" name="email" value="{{$location->email}}" placeholder="Email" > 
                                        <span></span>
                                    </div>
                                    <svg style="cursor: pointer;" onclick="insert_new_email('email_container', 'emails')"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5V19M5 12H19" stroke="var(--portal-section-heading-color)"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                </div>
                                <div id="email_container" class="d-flex flex-column gap-2 extras">
                                    @if (isset($emails) && count($emails) != 0)
                                        @foreach ($emails as $index => $email)
                                        <div id="email_input_{{ $index }}" class="position-relative email-container">
                                            <input class="email email-input form-control form-control-sm shadow-none" required type="email" placeholder="Add an email address" name="emails[]" value="{{ $email }}" />
                                        
                                            <input class="email-note form-control form-control-sm shadow-none position-absolute speech-bubble" type="text" name="email_note[]" placeholder="Add a note (e.g. Work email)" value="{{ isset($email_notes[$index]) ? $email_notes[$index] : '' }}" />
                                        
                                            <div class="d-flex align-items-center gap-2 mt-2 email-actions">
                                                <input tooltip="Set this email as primary email" type="checkbox" name="primary_email" value="{{ $index }}" onclick="setPrimary(event, 'primary_email', {{ $index }})" 
                                                />
                                                <span>Make Primary</span>
                                                <svg fill="none" onclick="remove_item('email_input_{{ $index }}')" width="24" height="24" viewBox="0 0 24 24" class="delete-icon"
                                                >
                                                    <path 
                                                        d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" 
                                                        stroke="red" 
                                                        stroke-width="2" 
                                                        stroke-linecap="round" 
                                                        stroke-linejoin="round" 
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                        @endforeach
                                        
                                    @endif
                                </div>
<style>
.email-container {
    position: relative;
    margin-bottom: 20px;
}

.email-input {
    z-index: 1;
}

.speech-bubble {
    display: none;
    position: absolute;
    top: -40px;
    left: 0;
    background: #f9f9f9;
    border: 1px solid #ccc;
    padding: 5px 10px;
    border-radius: 5px;
    z-index: 2;
    width: 250px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.speech-bubble::after {
    content: '';
    position: absolute;
    bottom: -10px; 
    left: 20px;
    border-width: 10px;
    border-style: solid;
    border-color: #f9f9f9 transparent transparent transparent;
}

.email-container:hover .speech-bubble {
    display: block;
}

.email-actions {
    opacity: 0;
    pointer-events: none;
    margin-top: 5px;
    transition: opacity 0.3s;
}

.email-container:hover .email-actions {
    opacity: 1;
    pointer-events: auto;
}

.delete-icon {
    cursor: pointer;
    color: red;
}


</style>

<script>
    function showNoteInput(index) {
    document.querySelector(`#email_input_${index} .email-note-input`).style.display = 'block';
}

function hideNoteInput(index) {
    document.querySelector(`#email_input_${index} .email-note-input`).style.display = 'none';
}
 </script>
                                
                                
                            </div>
                            <div>
                                <p class="m-0 fw-semibold text-info mb-2 mt-3" >Type</p>
                                <div class="grid-wrap resizing-input location-input">
                                    <p class="m-0">Type:</p>
                                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
                                        @foreach ($location_types as $location_type)
                                            <button type="button" data-id="{{$location_type->id}}" class="location-type-btn {{$location->location_type_id == $location_type->id ? 'active' : ''}}">{{$location_type->name}}</button>
                                        @endforeach
                                        <input type="hidden" name="location_type_id" value="{{$location->location_type_id}}">
                                    </div>
                                </div>
                                <div class="grid-wrap resizing-input location-input mt-4">
                                    <p class="m-0">Sub Type:</p>
                                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
                                        @foreach ($pharm_types as $pharm_type)
                                        <button type="button" data-id="{{$pharm_type->id}}" class="location-type-btn {{isset($location->location_pharmacy_type_id) && $location->location_pharmacy_type_id == $pharm_type->id ? 'active' : ''}}">{{$pharm_type->name}}</button>
                                        @endforeach
                                        <input type="hidden" name="location_pharmacy_type_id" value="{{$location->location_pharmacy_type_id}}">
                                    </div>
                                </div>
                                <div class="grid-wrap resizing-input location-input mt-4">
                                    <p class="m-0">Regulatory Body:</p>
                                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
                                        @foreach ($bodies as $body)
                                        <button type="button" data-id="{{$body->id}}" class="location-type-btn {{ $location->location_regulatory_body_id == $body->id ? 'active' : ''}}">{{$body->name}}</button>
                                        @endforeach
                                        <input type="hidden" name="location_regulatory_body_id" value="{{$location->location_regulatory_body_id}}">
                                    </div>
                                </div>
                            </div>
                            {{-- Assigned Groups --}}
                            <div>
                                <p class="m-0 fw-semibold text-info mb-2 mt-3" >Group</p>
                                <div class="d-flex align-items-center gap-1 loc-groups-wrapper">
    
                                    @if (count($ho_location->groups))
                                        <div @if (count($ho_location->groups) > 1) style="display:flex;align-items:center;flex-wrap:wrap;width:144px;" @endif
                                            class="">
                                            @foreach ($ho_location->groups as $assignment)
                                                <p class="btn group-btn">{{ $assignment->group->group }} <a title="Remove Group"
                                                        href="{{ route('head_office.organisation.delete_group', ['id'=>$assignment->id,'_token'=>csrf_token()]) }}"
                                                        data-msg="Are you sure you want to remove this assignment?"
                                                        class="text-danger delete_button float-right"><i class="fa fa-xmark"></i></a></p>
                                            @endforeach
                                        </div>
                                    @endif
                                    <p style="margin-top: 10px;"><button type="button" data-bs-toggle="modal" data-bs-target='#group_assing_modal'
                                            class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center mx-auto"><i
                                                class="fa fa-plus"></i></button></p>
                            </div>
                            </div>
                            {{-- Assigned Tags --}}
                            <div>
                                <p class="m-0 fw-semibold text-info mb-2 mt-3" >Tags</p>
                                <div class="">
                                    @livewire('location-tags-manager', ['loc_id' => $location->id])
                                </div>
                            </div>
                            {{-- Assigned To --}}
                            <div>
                                <p class="m-0 fw-semibold text-info mb-2 mt-3" >Assigned To</p>
                                <div class="">
                                    {{-- <p class="m-0">{{$location->assigned_tag->name}}</p> --}}
                                    <p>Location has not been assigned to any user</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('update.address') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ho_location_id" value="{{ $location->id }}">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editAddressModalLabel">Edit Address</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="address_line1" class="form-label">Address Line 1</label>
                                        <input type="text" class="form-control" id="address_line1" name="address_line1" value="{{ $location->address_line1 }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="address_line2" class="form-label">Address Line 2</label>
                                        <input type="text" class="form-control" id="address_line2" name="address_line2" value="{{ $location->address_line2 }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="address_line3" class="form-label">Address Line 3</label>
                                        <input type="text" class="form-control" id="address_line3" name="address_line3" value="{{ $location->address_line3 }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="town" class="form-label">Town</label>
                                        <input type="text" class="form-control" id="town" name="town" value="{{ $location->town }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="county" class="form-label">County</label>
                                        <input type="text" class="form-control" id="county" name="county" value="{{ $location->county }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <select class="form-control" id="country" name="country">
                                            <option value="Northern Ireland" {{ $location->country == 'Northern Ireland' ? 'selected' : '' }}>Northern Ireland</option>
                                            <option value="Republic of Ireland" {{ $location->country == 'Republic of Ireland' ? 'selected' : '' }}>Republic of Ireland</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="postcode" class="form-label">Postcode</label>
                                        <input type="text" class="form-control" id="postcode" name="postcode" value="{{ $location->postcode }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>

                <div class="col-5">
                    @foreach ($comments as $key => $comment)
                        @include('head_office.contacts.view_comments', compact('comment'))
                    @endforeach
                    <div class="cm_new_comment ">
                        <!-- <p>Add New Comment</p> -->
                        @include('head_office.location_comments', [
                            'comment' => null,
                            'parent' => null,
                            'remove_backdrop' => true,
                        ])

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="group_assing_modal" tabindex="-1" aria-labelledby="group_assing_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="group_assing_modalLabel">Assign to</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <p><strong>Location: </strong> {{ $location->name() }}</p>
                    <form method="post"
                        action="{{ route('head_office.organisation.assign_groups_save', $ho_location->id) }}">
                        @csrf
                        <input type="hidden" name="location_id" value="{{ $ho_location->id }}">
                        <p>Please select a group/tier</p>
                        @include('head_office.my_organisation.tree-list', ['groups' => $allGroups])
                        <input type="submit" name="save" value="Save" class="btn btn-info">
                    </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>


@section('scripts')
    <script>
        $(document).ready(function() {
            loadActiveTab();

            $(".telephone").intlTelInput({
            fixDropdownWidth: true,
            showSelectedDialCode: true,
            strictMode: true,
            preventInvalidNumbers: true,
            initialCountry: 'gb',
            utilsScript:"{{asset('admin_assets/js/utils.js')}}"
        })
        });

        $('#form').on('submit', function() {
    $(".telephone").each(function() {
        const phoneInput = $(this);
        const fullNumber = $(this).intlTelInput("getNumber"); // Get the full phone number (with country code)

        if (fullNumber) {
            phoneInput.val(fullNumber); // Set the full number in the input field
        }
    });
});


        window.addEventListener('DOMContentLoaded', (event) => {
            $(document).ready(function() {
                $('.select_2_custom').select2({
                    tags: true,
                });
            });
        });

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log(tab);
            $('.nav-tabs button[data-bs-target="' + tab + '"]').tab('show');
        }

        let loadFile = function(event) {
    let image = document.getElementById("output");
    let file = event.target.files[0]; // Ensure file is selected

    if (file) {
        // Update the image source to display the selected image
        image.src = URL.createObjectURL(file);
        
        image.onload = () => {
            URL.revokeObjectURL(image.src);
        };
    } else {
        image.src = "{{ ($ho_location->org_settings() !== null && $ho_location->org_settings()->setting_logo() !== null) ? $ho_location->org_settings()->setting_logo() : asset('images/svg/logo_blue.png') }}";
    }
};


        

        $('.location-type-btn').on('click', function(e) {
            e.preventDefault();
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            $(this).siblings().closest('input').val($(this).data('id'));
        })

        function insert_new_phone(container_id, name) {
            let newIndex = $(`[id^="${container_id}_phone_input_"]`).length + 1;

            // Add new phone field with a Primary checkbox and note input
            $(`#${container_id}`).append(`
            <div id="${container_id}_phone_input_${newIndex}" class='d-flex flex-column gap-1'>
                <input class="telephone form-control form-control-sm shadow-none" required type="text" placeholder="Add a phone number" name="${name}[]" />
                
                <input class="form-control form-control-sm shadow-none" type="text" name="phone_note[]" placeholder="Add a note (e.g. Manager's mobile)" />
                <div class="d-flex align-items-center">
                    <svg fill="none" onclick="remove_item('${container_id}_phone_input_${newIndex}')" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                </div>
            </div>
        `);
        // <input tootip='Set this email as primary email' type="checkbox" name="primary_email" value="${newIndex}" onclick="setPrimary('primary_email', ${newIndex})"> Make Primary
        $(".telephone").intlTelInput({
            fixDropdownWidth: true,
            showSelectedDialCode: true,
            strictMode: true,
            preventInvalidNumbers: true,
            initialCountry: 'gb',
            utilsScript:"{{asset('admin_assets/js/utils.js')}}"
        });

        }

function insert_new_email(container_id, name) {
    let newIndex = $(`[id^="${container_id}_email_input_"]`).length + 1;

    // Add new email field with a Primary checkbox and note input
    $(`#${container_id}`).append(`
        <div id="${container_id}_email_input_${newIndex}" class='d-flex flex-column gap-1'>
            <input class="email email-input form-control form-control-sm shadow-none" required  placeholder="Add an email address" name="${name}[]" />
            <input class="form-control form-control-sm shadow-none" type="text" name="email_note[]" placeholder="Add a note (e.g. Work email)" />
            <div style="display: flex; align-items: center;"> 
                
                <svg fill="none" onclick="remove_item('${container_id}_email_input_${newIndex}')" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <button class="primary-btn">Save email</button>
            </div>
        </div>
    `);
}

function setPrimary(event, type, index) {
    // Prevent the event from propagating
    event.stopPropagation();

    // Get the clicked checkbox as a jQuery object
    const checkbox = $(`input[name="${type}"][value="${index}"]`);

    if (checkbox.length === 0) {
        console.error('Checkbox not found.');
        return;
    }

    if (checkbox.prop('checked')) {
        // Uncheck all other checkboxes except the clicked one
        $(`input[name="${type}"]`).not(checkbox).prop('checked', false);
    }
    
    // Let the checkbox toggle naturally
}




function remove_item(id) {
    // Remove the selected phone or email field
    $(`#${id}`).remove();
}


function validateEmails(container_id) {
    let isValid = true;
    
    $(`.email-input`).each(function() {
        let email = $(this).val();
        // Simple email validation regex
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        if (!emailPattern.test(email)) {
            isValid = false;
            $(this).addClass('is-invalid');  // Add a class to highlight invalid email
        } else {
            $(this).removeClass('is-invalid');  // Remove invalid class if valid
        }
    });

    return isValid;
}

function validatePhones(container_id) {
    let isValid = true;

    // Loop through each telephone input to validate and get full number
    $(`.telephone`).each(function() {
        // Get the full international number using the intlTelInput jQuery method
        const fullNumber = $(this).intlTelInput("getNumber");

        // Check if the number is valid
        if (!$(this).intlTelInput("isValidNumber")) {
            isValid = false;
            $(this).addClass('is-invalid');  // Add error highlighting
        } else {
            $(this).removeClass('is-invalid');  // Remove error highlighting
            console.log('Valid number:', fullNumber);  // Output the valid number
        }
    });

    return isValid;
}


// Usage example in form submission
$('#form').on('submit', function(e) {
    let emailContainerId = 'email_container';
    let phoneContainerId = 'phone_container';

    let isPhoneValid = validatePhones(phoneContainerId);
    let isEmailValid = validateEmails(emailContainerId);
    // If either validation fails, prevent form submission
    if (!isPhoneValid || !isEmailValid) {
        e.preventDefault();  // Stop form submission
        if (!isPhoneValid) {
            alertify.error('Please correct invalid phone number(s).');
        }
        if (!isEmailValid) {
            alertify.error('Invalid email address.');
        }
    }
    
});




    </script>




    <script src="{{ asset('js/alertify.min.js') }}"></script>
    <script src="{{asset('admin_assets/speech-to-text.js')}}"></script>

@endsection
@endsection
