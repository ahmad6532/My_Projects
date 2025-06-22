<?php
$action = route('head_office.head_office_profile_save');
if (isset($access_rights)) {
    $action = route('head_office.head_office_access_right_save');
}
$logged_ho_u = Auth::guard('web')->user()->getHeadOfficeUser();
$logged_profile = $logged_ho_u->user_profile_assign->profile;
?>

<form action="{{ $action }}" method="POST">

    @if (!isset($access_rights))
        @if ((isset($profile) && $profile->super_access) || $logged_profile->super_access == true)
            {{-- <div class=" text-danger " role="alert">This is system default profile. Editing is disabled.</div> --}}
        @endif
    @endif

    @if (isset($access_rights) && isset($headOfficeUsers))
        <input type="hidden" name="head_office_user_id" value="{{ $headOfficeUsers->id }}">
    @endif

    @if (isset($profile) && $profile)
        <input type="hidden" name="id" value="{{ $profile->id }}">
    @endif

    <div class="d-flex flex-column p-3 justify-content-center align-items-center gap-2 ">
        <div class="text-right align-self-end">
            @if ((isset($profile) && $profile->super_access) || $logged_profile->super_access == true)
                @csrf
                <input type="submit"
                    @if (isset($access_rights)) value="Save Access Rights" @else value="Save Profile & Permissions" @endif
                    class="primary-btn clear clear-both" @if (!$logged_profile->super_access) style="display:none" @endif>
            @endif
        </div>
        @if (!isset($access_rights))
            <div class="profile-page-contents hide-placeholder-parent w-50" style="max-width: unset;">
                <label class="inputGroup fw-semibold">Profile Name:

                    <input style="width:auto;" type="text" name="profile_name"
                        @if (isset($profile)) value="{{ $profile->profile_name }}" @endif required
                        @if ((isset($profile) && $profile->super_access) || $logged_profile->super_access == true) @else disabled @endif>

                </label>
            </div>
            <div class="profile-page-contents hide-placeholder-parent w-50" style="max-width: fit-content;">
                <label class="inputGroup fw-semibold">Super Access:

                    <input type="checkbox" name="super_access"
                        @if (isset($profile)) {{ $profile->super_access == true ? 'checked' : '' }} @endif required
                        @if ((isset($profile) && $profile->super_access) || $logged_profile->super_access == true) @else disabled @endif>

                </label>
            </div>

        @endif
        <div class="d-flex align-items-center justify-content-between p-4 w-50 rounded" style="background: #2bafa524;">
            <label class="h5 m-0 " style="cursor: pointer;" for="cbx-12">Manage Forms</label>
            <div class="checkbox-wrapper-12">
                <div class="cbx">
                    <input type="checkbox" @if (isset($profile) && $profile->super_access) data-superUser='true' @endif
                        @if (!($logged_profile->super_access == true)) disabled @endif id="cbx-12" name="is_manage_forms"
                        @if (isset($profile->head_office_access_rights) && $profile->head_office_access_rights->is_manage_forms == true) checked
                        @elseif(isset($profile) && $profile->is_manage_forms == true) 
                        checked @endif>
                    <label class="m-0" for="cbx-12"></label>
                    <svg fill="none" viewBox="0 0 15 14" height="14" width="15">
                        <path d="M2 8.36364L6.23077 12L13 2"></path>
                    </svg>
                </div>

                <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="goo-12">
                            <feGaussianBlur result="blur" stdDeviation="4" in="SourceGraphic"></feGaussianBlur>
                            <feColorMatrix result="goo-12" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -7"
                                mode="matrix" in="blur"></feColorMatrix>
                            <feBlend in2="goo-12" in="SourceGraphic"></feBlend>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between p-4 w-50 rounded" style="background: #2bafa524;">
            <label class="h5 m-0 " style="cursor: pointer;" for="cbx-13">Manage Company Account</label>
            <div class="checkbox-wrapper-12">
                <div class="cbx">
                    <input type="checkbox" @if (isset($profile) && $profile->super_access) data-superUser='true' @endif
                        @if (!($logged_profile->super_access == true)) disabled @endif id="cbx-13"
                        name="is_manage_company_account"
                        @if (isset($profile->head_office_access_rights) &&
                                $profile->head_office_access_rights->is_manage_company_account == true) checked
                        @elseif(isset($profile) && $profile->is_manage_company_account == true) 
                        checked @endif>

                    <label class="m-0" for="cbx-13"></label>
                    <svg fill="none" viewBox="0 0 15 14" height="14" width="15">
                        <path d="M2 8.36364L6.23077 12L13 2"></path>
                    </svg>
                </div>

                <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="goo-12">
                            <feGaussianBlur result="blur" stdDeviation="4" in="SourceGraphic"></feGaussianBlur>
                            <feColorMatrix result="goo-12" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -7"
                                mode="matrix" in="blur"></feColorMatrix>
                            <feBlend in2="goo-12" in="SourceGraphic"></feBlend>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between p-4 w-50 rounded" style="background: #2bafa524;">
            <label class="h5 m-0 " style="cursor: pointer;" for="cbx-14">Manage Team</label>
            <div class="checkbox-wrapper-12">
                <div class="cbx">
                    <input type="checkbox" @if (isset($profile) && $profile->super_access) data-superUser='true' @endif
                        @if (!($logged_profile->super_access == true)) disabled @endif id="cbx-14" name="is_manage_team"
                        @if (isset($profile->head_office_access_rights) && $profile->head_office_access_rights->is_manage_team == true) checked
                        @elseif(isset($profile) && $profile->is_manage_team == true) 
                        checked @endif>

                    <label class="m-0" for="cbx-14"></label>
                    <svg fill="none" viewBox="0 0 15 14" height="14" width="15">
                        <path d="M2 8.36364L6.23077 12L13 2"></path>
                    </svg>
                </div>

                <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="goo-12">
                            <feGaussianBlur result="blur" stdDeviation="4" in="SourceGraphic"></feGaussianBlur>
                            <feColorMatrix result="goo-12" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -7"
                                mode="matrix" in="blur"></feColorMatrix>
                            <feBlend in2="goo-12" in="SourceGraphic"></feBlend>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between p-4 w-50 rounded"
            style="background: #2bafa524;">
            <label class="h5 m-0 " style="cursor: pointer;" for="cbx-15">Manage Location Users</label>
            <div class="checkbox-wrapper-12">
                <div class="cbx">
                    <input type="checkbox" @if (isset($profile) && $profile->super_access) data-superUser='true' @endif
                        @if (!($logged_profile->super_access == true)) disabled @endif id="cbx-15"
                        name="is_manage_location_users"
                        @if (isset($profile->head_office_access_rights) && $profile->head_office_access_rights->is_manage_location_users == true) checked
                        @elseif(isset($profile) && $profile->is_manage_location_users == true) 
                        checked @endif>

                    <label class="m-0" for="cbx-15"></label>
                    <svg fill="none" viewBox="0 0 15 14" height="14" width="15">
                        <path d="M2 8.36364L6.23077 12L13 2"></path>
                    </svg>
                </div>

                <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="goo-12">
                            <feGaussianBlur result="blur" stdDeviation="4" in="SourceGraphic"></feGaussianBlur>
                            <feColorMatrix result="goo-12" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -7"
                                mode="matrix" in="blur"></feColorMatrix>
                            <feBlend in2="goo-12" in="SourceGraphic"></feBlend>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between p-4 w-50 rounded"
            style="background: #2bafa524;">
            <label class="h5 m-0 " style="cursor: pointer;" for="cbx-16">Manage Alert Settings</label>
            <div class="checkbox-wrapper-12">
                <div class="cbx">
                    <input type="checkbox" @if (isset($profile) && $profile->super_access) data-superUser='true' @endif
                        @if (!($logged_profile->super_access == true)) disabled @endif id="cbx-16"
                        name="is_manage_alert_settings"
                        @if (isset($profile->head_office_access_rights) && $profile->head_office_access_rights->is_manage_alert_settings == true) checked
                        @elseif(isset($profile) && $profile->is_manage_alert_settings == true) 
                        checked @endif>

                    <label class="m-0" for="cbx-16"></label>
                    <svg fill="none" viewBox="0 0 15 14" height="14" width="15">
                        <path d="M2 8.36364L6.23077 12L13 2"></path>
                    </svg>
                </div>

                <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="goo-12">
                            <feGaussianBlur result="blur" stdDeviation="4" in="SourceGraphic"></feGaussianBlur>
                            <feColorMatrix result="goo-12" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -7"
                                mode="matrix" in="blur"></feColorMatrix>
                            <feBlend in2="goo-12" in="SourceGraphic"></feBlend>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between p-4 w-50 rounded"
            style="background: #2bafa524;">
            <label class="h5 m-0 " style="cursor: pointer;" for="cbx-17">Access company activity log</label>
            <div class="checkbox-wrapper-12">
                <div class="cbx">
                    <input type="checkbox" @if (isset($profile) && $profile->super_access) data-superUser='true' @endif
                        @if (!($logged_profile->super_access == true)) disabled @endif id="cbx-17"
                        name="is_access_company_activity_log"
                        @if (isset($profile->head_office_access_rights) &&
                                $profile->head_office_access_rights->is_access_company_activity_log == true) checked
                        @elseif(isset($profile) && $profile->is_access_company_activity_log == true) 
                        checked @endif>

                    <label class="m-0" for="cbx-17"></label>
                    <svg fill="none" viewBox="0 0 15 14" height="14" width="15">
                        <path d="M2 8.36364L6.23077 12L13 2"></path>
                    </svg>
                </div>

                <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="goo-12">
                            <feGaussianBlur result="blur" stdDeviation="4" in="SourceGraphic"></feGaussianBlur>
                            <feColorMatrix result="goo-12" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -7"
                                mode="matrix" in="blur"></feColorMatrix>
                            <feBlend in2="goo-12" in="SourceGraphic"></feBlend>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between p-4 w-50 rounded"
            style="background: #2bafa524;">
            <label class="h5 m-0 " style="cursor: pointer;" for="cbx-18">Access Contacts</label>
            <div>
                <select class="form-select custom-select access_contacts_option" style="width: 150px;"
                    id="access_contacts_option" name="access_contacts_option">
                    <option value="all_contacts" selected>All Contacts</option>
                    <option value="selected_contacts" disabled>Selected Contacts | Coming Soon</option>
                </select>
            </div>
            <div class="checkbox-wrapper-12">
                <div class="cbx">
                    <input type="checkbox" @if (isset($profile) && $profile->super_access) data-superUser='true' @endif
                        @if (!($logged_profile->super_access == true)) disabled @endif id="cbx-18" name="is_access_contacts"
                        @if (isset($profile->head_office_access_rights) && $profile->head_office_access_rights->is_access_contacts == true) checked
                        @elseif(isset($profile) && $profile->is_access_contacts == true) 
                        checked @endif>

                    <label class="m-0" for="cbx-18"></label>
                    <svg fill="none" viewBox="0 0 15 14" height="14" width="15">
                        <path d="M2 8.36364L6.23077 12L13 2"></path>
                    </svg>
                </div>

                <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="goo-12">
                            <feGaussianBlur result="blur" stdDeviation="4" in="SourceGraphic"></feGaussianBlur>
                            <feColorMatrix result="goo-12" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -7"
                                mode="matrix" in="blur"></feColorMatrix>
                            <feBlend in2="goo-12" in="SourceGraphic"></feBlend>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
        <style>
            .select2-container {
                display: flex;
            }

            .select2-container--default .select2-selection--multiple {
                min-width: 200px;
                height: 100%;
            }
        </style>
        <div class="d-flex align-items-center justify-content-between p-4 w-50 rounded"
            style="background: #2bafa524;">
            <label class="h5 m-0 " style="cursor: pointer;" for="cbx-18">Access Locations</label>
            <div>
                @php
                    $locs = isset($profile->head_office_access_rights->locations) ? json_decode($profile->head_office_access_rights->locations, true) : [];
                @endphp
                <select class=" select2" style="width: 200px;" multiple name="access_locations_options[]">
                    @foreach ($headOffice->head_office_location_groups as $groupName => $group)
                        <optgroup label="{{ $group->group->group }}">
                            @foreach ($group->location() as $location_gr)
                                <option @if (isset($profile) && in_array($location_gr->id, $locs ?? [])) selected @endif
                                    value="{{ $location_gr->id }}">{{ $location_gr->location->trading_name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>

            </div>
            <div class="checkbox-wrapper-12">
                <div class="cbx">
                    <input type="checkbox" @if (isset($profile) && $profile->super_access) data-superUser='true' @endif
                        @if (!($logged_profile->super_access == true)) disabled @endif id="cbx-18" name="is_access_locations"
                        @if (isset($profile->head_office_access_rights) && $profile->head_office_access_rights->is_access_locations == true) checked
                        @elseif(isset($profile) && $profile->is_access_locations == true) 
                        checked @endif>

                    <label class="m-0" for="cbx-18"></label>
                    <svg fill="none" viewBox="0 0 15 14" height="14" width="15">
                        <path d="M2 8.36364L6.23077 12L13 2"></path>
                    </svg>
                </div>

                <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="goo-12">
                            <feGaussianBlur result="blur" stdDeviation="4" in="SourceGraphic"></feGaussianBlur>
                            <feColorMatrix result="goo-12" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -7"
                                mode="matrix" in="blur"></feColorMatrix>
                            <feBlend in2="goo-12" in="SourceGraphic"></feBlend>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
    </div>

</form>
