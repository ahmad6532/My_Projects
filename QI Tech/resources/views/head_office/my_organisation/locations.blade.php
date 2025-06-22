<style>
    #dataTable_filter {
        margin-top: 0;
    }

    #count2 {
        font-weight: 500;
        font-size: 2.5rem;
        margin: 0;
    }

    .info-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        height: 100%;
    }

    .info-wrapper p {
        margin: 0;
        padding: 0;
    }

    .info-wrapper .info-heading p {
        font-size: 1.7rem;
        font-weight: 500;
    }

    .info-wrapper .info-heading .dots-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-heading {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-wrapper .info-heading .dots-wrapper .dot {
        display: flex;
        aspect-ratio: 1/1;
        width: 8px;
        background: rgba(52, 191, 175, 0.879);
        border-radius: 50%;
    }
    .view-btn{
        margin: 0 auto;
        cursor: pointer;
        opacity: 0.7;
        transition: 0.2s ease;
    }
    .view-btn:hover{
        opacity: 1;
        transition: 0.2s ease;
    }
    #code{
        text-align: center;
        font-weight: 500;
        font-size: 15px;
        border:none;
        border-radius: 5px;
        background: #F2F6F7;
        padding-block: 0.5rem;
    }
    .loc{
        transition: 0.2s ease;
    }
    .loc:hover{
        transition: 0.2s ease;
        background: #2bafa414 !important;
    }
    .dataTables_scrollHeadInner{
        width: 100% !important;
    }
    .dataTables_scrollHeadInner table{
        width: 100% !important;
    }
    .dataTables_scrollBody thead{
        display: none !important;
    }
    .dataTables_scrollBody{
        padding-bottom: 5px;
    }
    #dataTable_length{
        color: #999;
    margin-left: 14px;
    margin-top: 6px;
    }
    .dataTables_wrapper .bottom {
    display: flex;
    gap: 1rem;
    align-items: center;
}
    #dataTable_paginate{
        display: flex;
        gap: 5px;
        align-items: center;
        margin-top: 10px;
    }

    .paginate_button {
    box-sizing: border-box;
    display: inline-block;
    min-width: 1.5em;
    padding: 0.5em 1em;
    margin-left: 2px;
    text-align: center;
    text-decoration: none !important;
    cursor: pointer;
    color: inherit !important;
    border: 1px solid transparent;
    border-radius: 2px;
    background: transparent;
    color: inherit !important;
    border: 1px solid rgba(0, 0, 0, 0.3);
    background: linear-gradient(to bottom, rgba(230, 230, 230, 0.05) 0%, rgba(0, 0, 0, 0.05) 100%);
    }
    .paginate_button:hover{
        background: linear-gradient(to bottom, rgba(230, 230, 230, 0.05) 0%, rgba(0, 0, 0, 0.05) 100%);
        border: 1px solid rgba(0, 0, 0, 0.3);
    }
    .main-wrapper-loc{
        width: fit-content !important;
    }
    table.dataTable > thead tr th{
        text-align: left;
    }
    table.dataTable > tbody tr td{
        vertical-align: top;
    }

   
    table.dataTable > thead tr th:first-child{
        max-width: 40px !important;
        width: 40px !important;
        
    }
    table.dataTable > tbody tr td:first-child{
        max-width: 40px !important;
        width: 40px !important;
        
    }
    table.dataTable > tbody tr td:nth-child(4),
    table.dataTable > thead tr th:nth-child(4){
        width: 18%;
    }
    table.dataTable > tbody tr td:nth-child(7),
    table.dataTable > thead tr th:nth-child(7){
        width: 15%;
    }

    .loc-groups-wrapper .group-btn{
        font-size: 10px;
        align-items: center;
        padding-block: 4px;
        background: #eaeaea;
    }
    .loc-groups-wrapper .group-btn:hover{
        color: black;
    }
    .loc-groups-wrapper .btn-group-assign{
        padding: 0;
        font-size: 10px;
        width: 24px;
        height: 24px;
    }

    .loc-groups-wrapper .group-btn a{
        display: none;
    }
    .loc-groups-wrapper .group-btn:hover a{
        display: block;
        color: rgba(52, 191, 175, 0.879);
    }

    /* hidding edit button 😑 */
    .btn-wrapper-loc button:first-child{
        display: none;
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

    #searchInput-locations::placeholder{
        color: #999 !important;
    }
.filters-wrapper input[type="checkbox"] {
    min-width: fit-content !important;
}
</style>

<div style="position:relative;">    
    
    @if (isset($locations) && count($locations) > 0)
    <div class="headingWithSearch d-flex align-items-center gap-4 mt-2">
        {{-- <div class="heading">All Locations</div> --}}
        <Select class="form-select custom-input  " style="width: 250px;" id="locaction-status">
            <option value="1">All Locations</option>
            <option value="2">Archived</option>
            <option value="3">Live</option>
            <option value="4">Deleted</option>
        </Select>

        <div class="d-flex align-items-center gap-1 position-relative">
            <input type="text" id="searchInput-locations" class="form-control  shadow-none" placeholder="Search...">
            <button id="filters-show" class="btn btn-sm btn-light shadow-none"><i class="fa-solid fa-chevron-down"></i></button>

            <div class="filters-wrapper" style="left: 90%;">
                <!-- Location Types -->
                <p class="filter-title">
                    Location Types 
                    <i class="btn fa-solid fa-chevron-down toggle-chevron"></i>
                </p>
                <div class="filter-content">
                    <div class="d-flex align-items-center text-secondary gap-1">
                        <input id="comunity-check" type="checkbox" class="location-filter" value="Community Pharmacy"> Community Pharmacy
                    </div>
                    <div style="display: none;margin-left: 10px;" id="community-sub-types">
                        <p>Sub types</p>
                        <div class="d-flex align-items-center text-secondary gap-1">
                            <input  type="checkbox" class="location-sub-filter" value="Retail Pharmacy"> Retail Pharmacy
                        </div>
                        <div class="d-flex align-items-center text-secondary gap-1">
                            <input  type="checkbox" class="location-sub-filter" value="Distance Selling Pharmacy"> Distance Selling Pharmacy
                        </div>
                        <div class="d-flex align-items-center text-secondary gap-1">
                            <input  type="checkbox" class="location-sub-filter" value="Private Pharmacy"> Private Pharmacy
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-secondary gap-1">
                        <input type="checkbox" class="location-filter" value="Dispensing"> Dispensing Doctor's Practice
                    </div>
                    <div class="d-flex align-items-center text-secondary gap-1">
                        <input type="checkbox" class="location-filter" value="Hospital Pharmacy"> Hospital Pharmacy
                    </div>
                    <div class="d-flex align-items-center text-secondary gap-1">
                        <input type="checkbox" class="location-filter" value="Private Pharmacy"> Private Pharmacy
                    </div>
                </div>
            
                <!-- Country -->
                <p class="filter-title">
                    Country
                    <i class="btn fa-solid fa-chevron-down toggle-chevron"></i>
                </p>
                <div class="filter-content">
                    @foreach ([ "England", "Scotland", "Wales", "Channel Islands", "Northern Ireland", "Republic of Ireland" ] as $country)
                        <div class="d-flex align-items-center text-secondary gap-1">
                            <input type="checkbox" class="country-filter" value="{{$country}}"> {{$country}}
                        </div>
                    @endforeach
                </div>
            
                <!-- Tags -->
                <p class="filter-title">
                    Tags
                    <i class="btn fa-solid fa-chevron-down toggle-chevron"></i>
                </p>
                <div class="filter-content">
                    @foreach ($headOffice->location_tags as $loc_tag)
                        <div class="d-flex align-items-center text-secondary gap-1">
                            <input type="checkbox" class="tags-filter" value="{{$loc_tag->name}}"> {{$loc_tag->name}}
                        </div>
                    @endforeach
                </div>
            
                <!-- Group -->
                <p class="filter-title">
                    Group
                    <i class="btn fa-solid fa-chevron-down toggle-chevron"></i>
                </p>
                <div class="filter-content">
                    <input type="checkbox" class="group-filter" value="Unassigned"> Unassigned
                    @foreach ($headOffice->head_office_organisation_groups as $loc_group)
                        <div class="d-flex align-items-center text-secondary gap-1">
                            <input type="checkbox" class="group-filter" value="{{$loc_group->group}}"> {{$loc_group->group}}
                        </div>
                    @endforeach
                </div>
            
                <!-- Status -->
                <p class="filter-title">
                    Status
                    <i class="btn fa-solid fa-chevron-down toggle-chevron"></i>
                </p>
                <div class="filter-content">
                    @foreach ([ "Live", "Inactive", "Archived" ,'Deleted'] as $stat)
                        <div class="d-flex align-items-center text-secondary gap-1">
                            <input type="checkbox" class="stat-filter" value="{{$stat}}"> {{$stat}}
                        </div>
                    @endforeach
                </div>
            </div>
            
        </div>
    </div>
    @include('layouts.error')
    <table id="dataTable" class="row-border loc-datatable new-table" style="width:100%;table-layout:fixed;" >
        <thead>
            <div class="table-heading">
                <tr >
                    <th ><input type="checkbox" name="select_all" value="1" id="dataTable-select-all"></th>
                    <th style="text-align:left  !importan;">Username</th>
                    <th>Status</th>
                    <th>Info</th>
                    <th>Group</th>
                    <th>Tag</th>
                    <th>Settings</th>
                    <th>Theme</th>
                    <th></th>
                </tr>
            </div>
            
        </thead>
        <tbody class="all_locations">

            @include('head_office.my_organisation.loc', ['locations' => $locations])
            <!-- Pagination Links -->
            <div class="pagination-links">
                {{-- {!! $locations->links('pagination::bootstrap-4') !!} --}}
            </div>
        </tbody>
    </table>
    @else
    <p class="">You have not created any locations</p>
    @endif
    <input type="hidden" id="routeLoc" value="{{ route('head_office.update_head_office_location_details') }}">
    <input type="hidden" id="tokenLoc" value="{{ csrf_token() }}">
    <div id="draggable" class="bottom-nav" aria-describedby="drag">
        <div class="left-side">
            <div class="info-wrapper">
                <div class="selected-show">
                    <h5 id="count2">0</h5>
                </div>
                <div class="info-heading" style="max-width: 180px;overflow:hidden;">
                    <p>Items Selected</p>
                    <div class="dots-wrapper">
                        <span class="dot"></span>
                    </div>
                </div>
            </div>

            <div style="max-width: 570px;overflow-x:scroll;height:100%;" class="custom-drag-scroll">
                <div class="btn-wrapper" style="width: 1200px;justify-content:flex-start;">
                    <button id='disable-btn' class="bar-btn" data-toggle="tooltip" data-placement="top" title="Disable 2FA for all loctions">
                        <img src="{{ asset('images/shield-off.svg') }}" alt="icon">
                        <p>Disable</p>
                    </button>
                    <button id="enable-btn" class="bar-btn" data-toggle="tooltip" data-placement="top" title="Enable 2FA for all loctions">
                        <img src="{{ asset('images/shield-tick.svg') }}" alt="icon">
                        <p>Enable</p>
                    </button>
                    <button  id="incident-btn" class="bar-btn" data-toggle="tooltip" data-placement="top" title="Import Incidents">
                        <img src="{{ asset('images/info-octagon.svg') }}" alt="icon">
                        <p>Incidents</p>
                    </button>
                    <button  id="archive-btn-bar" class="bar-btn" data-toggle="tooltip" data-placement="top" title="Archive Locations">
                        <img src="{{ asset('images/folder-lock.svg') }}" alt="icon">
                        <p>Archive</p>
                    </button>
                    <button id="unarchive-btn-bar" class="bar-btn" data-toggle="tooltip" data-placement="top" title="Remove Locations from archive">
                        <img src="{{ asset('images/folder.svg') }}" alt="icon">
                        <p>Unarchive</p>
                    </button>
                    {{-- <button id="rename-btn-bar" class="bar-btn" data-toggle="tooltip" data-placement="top" title="Rename Locations">
                        <img src="{{ asset('images/Rename.svg') }}" alt="icon">
                        <p>Rename</p>
                    </button> --}}
                    <button  id="delete-btn-bar" class="bar-btn" data-toggle="tooltip" data-placement="top" title="Delete Locations">
                        <img src="{{ asset('images/trash-01.svg') }}" alt="icon">
                        <p>Delete</p>
                    </button>
                    <button  id="restore-btn-bar" class="bar-btn" data-toggle="tooltip" data-placement="top" title="Restore Locations">
                        <img src="{{ asset('images/refresh-cw-03.svg') }}" alt="icon">
                        <p>Restore</p>
                    </button>
                    <div class="d-flex flex-column justify-content-center align-items-center bar-btn" style="width: 148px;" data-toggle='tooltip' data-placement="top" title="Select Theme" >
                        <img src="{{ asset('images/roller-brush.svg') }}" alt="icon" width="30">
                        <select id="theme-select-bar" class="form-select form-select-sm custom-input" style="transform: scale(0.7);">
                            <option value="0" selected disabled>Please Select</option>
                            @foreach ($headOffice->organisationSettings as $setting)
                            <option value="{{$setting->id}}">
                                {{ $setting->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center bar-btn" style="width: 148px;" data-toggle='tooltip' data-placement="top" title="Change Access" >
                        <img src="{{ asset('images/lock-keyhole-circle.svg') }}" alt="icon" width="30">
                        <select id="access-select-bar" class="form-select form-select-sm custom-input" style="transform: scale(0.7);">
                            <option value="-2" selected disabled>Please Select</option>
                            <option value="0">
                                Anyone
                            </option>
                            <option value="1">
                                Selected Users
                            </option>
                        </select>
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center bar-btn" style="width: 148px;" data-toggle='tooltip' data-placement="top" title="Select Tag" >
                        <img src="{{ asset('images/tag-01.svg') }}" alt="icon" width="30">
                        <select id="tag-select-bar" class="form-select form-select-sm custom-input" style="transform: scale(0.7);">
                            <option value="0" selected disabled>Please Select</option>
                            @foreach ($headOffice->location_tags as $tag )
                            <option value="{{$tag->id}}" style="background:{{$tag->color}}">{{$tag->name}}</option>
                            @endforeach
                        </select>
                    </div>
    
                </div>

            </div>
        </div>
        <button class="drag-btn drag-btn-2">
            <img src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
            <img style="margin-top:-15px;" src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
        </button>
    </div>
    {{-- group assign modal --}}
    <div class="modal fade" id="group_assing_modal" tabindex="-1" aria-labelledby="group_assing_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="group_assing_modalLabel">Assign to</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="group-content-wrapper">

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
</div>

{{-- passwords modal --}}
<div class="modal fade" id="password_modal" tabindex="-1" aria-labelledby="password_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content password-loc-card">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="password_modalLabel">Password Reset Settings</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="d-flex gap-4 flex-column">
                
                <a href="#" class="primary-btn  p-3 justify-content-center " id="password_reset_link"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M22 11V8.2C22 7.0799 22 6.51984 21.782 6.09202C21.5903 5.71569 21.2843 5.40973 20.908 5.21799C20.4802 5 19.9201 5 18.8 5H5.2C4.0799 5 3.51984 5 3.09202 5.21799C2.71569 5.40973 2.40973 5.71569 2.21799 6.09202C2 6.51984 2 7.0799 2 8.2V11.8C2 12.9201 2 13.4802 2.21799 13.908C2.40973 14.2843 2.71569 14.5903 3.09202 14.782C3.51984 15 4.0799 15 5.2 15H11M12 10H12.005M17 10H17.005M7 10H7.005M19.25 17V15.25C19.25 14.2835 18.4665 13.5 17.5 13.5C16.5335 13.5 15.75 14.2835 15.75 15.25V17M12.25 10C12.25 10.1381 12.1381 10.25 12 10.25C11.8619 10.25 11.75 10.1381 11.75 10C11.75 9.86193 11.8619 9.75 12 9.75C12.1381 9.75 12.25 9.86193 12.25 10ZM17.25 10C17.25 10.1381 17.1381 10.25 17 10.25C16.8619 10.25 16.75 10.1381 16.75 10C16.75 9.86193 16.8619 9.75 17 9.75C17.1381 9.75 17.25 9.86193 17.25 10ZM7.25 10C7.25 10.1381 7.13807 10.25 7 10.25C6.86193 10.25 6.75 10.1381 6.75 10C6.75 9.86193 6.86193 9.75 7 9.75C7.13807 9.75 7.25 9.86193 7.25 10ZM15.6 21H19.4C19.9601 21 20.2401 21 20.454 20.891C20.6422 20.7951 20.7951 20.6422 20.891 20.454C21 20.2401 21 19.9601 21 19.4V18.6C21 18.0399 21 17.7599 20.891 17.546C20.7951 17.3578 20.6422 17.2049 20.454 17.109C20.2401 17 19.9601 17 19.4 17H15.6C15.0399 17 14.7599 17 14.546 17.109C14.3578 17.2049 14.2049 17.3578 14.109 17.546C14 17.7599 14 18.0399 14 18.6V19.4C14 19.9601 14 20.2401 14.109 20.454C14.2049 20.6422 14.3578 20.7951 14.546 20.891C14.7599 21 15.0399 21 15.6 21Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  Send Password Reset Link</a>
                <button class="primary-btn p-3 justify-content-center " id="direct-pass"> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 12H12.005M17 12H17.005M7 12H7.005M5.2 7H18.8C19.9201 7 20.4802 7 20.908 7.21799C21.2843 7.40973 21.5903 7.71569 21.782 8.09202C22 8.51984 22 9.0799 22 10.2V13.8C22 14.9201 22 15.4802 21.782 15.908C21.5903 16.2843 21.2843 16.5903 20.908 16.782C20.4802 17 19.9201 17 18.8 17H5.2C4.0799 17 3.51984 17 3.09202 16.782C2.71569 16.5903 2.40973 16.2843 2.21799 15.908C2 15.4802 2 14.9201 2 13.8V10.2C2 9.0799 2 8.51984 2.21799 8.09202C2.40973 7.71569 2.71569 7.40973 3.09202 7.21799C3.51984 7 4.0799 7 5.2 7ZM12.25 12C12.25 12.1381 12.1381 12.25 12 12.25C11.8619 12.25 11.75 12.1381 11.75 12C11.75 11.8619 11.8619 11.75 12 11.75C12.1381 11.75 12.25 11.8619 12.25 12ZM17.25 12C17.25 12.1381 17.1381 12.25 17 12.25C16.8619 12.25 16.75 12.1381 16.75 12C16.75 11.8619 16.8619 11.75 17 11.75C17.1381 11.75 17.25 11.8619 17.25 12ZM7.25 12C7.25 12.1381 7.13807 12.25 7 12.25C6.86193 12.25 6.75 12.1381 6.75 12C6.75 11.8619 6.86193 11.75 7 11.75C7.13807 11.75 7.25 11.8619 7.25 12Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  Change Password Direclty</button>
                  <form action="{{route('location.password_direct_update.update')}}" method="POST" id="location_pass_form" style="display: none;">
                    @csrf
                    <input type="hidden" name="loc_id" id="loc_pass_id">
                    <p class="mb-1 mt-2" style="font-size: 12px;font-weight: 500;">Enter New Password</p>
                    <input class="form-control shadow-none" type="text" name="password" id="password" minlength="8" placeholder="Enter new password ">
                    <button class="primary-btn mt-2 justify-content-center">Update Password</button>
                  </form>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Rename Modal --}}
<div id="renameModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="renameModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="renameModalLabel">Rename Locations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="renameForm">
                    <div id="renameFields">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitRename">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

  {{-- Verification Codes --}}
  <div id="verificationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationModalLabel">Verification Codes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div id="verificationCodeWrap">
                    </div>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
@isset($locations)
    <script>
        jQuery(document).ready(function($) {

            // Convert the first page item from span to anchor if it is active
            let baseUrl = window.location.href.split('?')[0];
            let secondPageItem = $('.pagination .page-item:nth-child(2)');
            if (secondPageItem.hasClass('active')) {
                secondPageItem.html('<a class="page-link" href="' + baseUrl + '?page=1">1</a>');
            }

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();

                let pageNumber;
                let baseUrl = window.location.href.split('?')[0];

                // Check if the clicked item is "Previous" or "Next" arrow
                if ($(this).hasClass('page-link') && ($(this).text().trim() === '‹' || $(this).text().trim() === '›')) {

                    let currentPage = $('.pagination .page-item.active a').text().trim();
                    pageNumber = parseInt(currentPage, 10);

                    if ($(this).text().trim() === '‹') {
                        pageNumber--; // Go to the previous page
                    } else if ($(this).text().trim() === '›') {
                        pageNumber++; // Go to the next page
                    }
                } else {
                    // If it's a number, use that as the page number
                    pageNumber = parseInt($(this).text().trim(), 10);
                }


                // Get the total number of pages
                let totalPages = $('.pagination .page-item').length - 2; // Excluding the previous and next buttons

                // Update the "Next" arrow's href
                let nextPageItem = $('.pagination .page-item:last-child');
                if (pageNumber >= totalPages) {
                    nextPageItem.addClass('disabled').html('<span class="page-link">›</span>');
                } else {
                    nextPageItem.removeClass('disabled active').html('<a class="page-link" href="' + baseUrl + '?page=' + (pageNumber + 1) + '">›</a>');
                }

                // Update the "Previous" arrow's href
                let prevPageItem = $('.pagination .page-item:first-child');
                if (pageNumber <= 1) {
                    prevPageItem.addClass('disabled').html('<span class="page-link">‹</span>');
                } else {
                    prevPageItem.removeClass('disabled active').html('<a class="page-link" href="' + baseUrl + '?page=' + (pageNumber - 1) + '">‹</a>');
                }


                let url = $(this).attr('href');
                $('.loader-container').css('display', 'grid');
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        ajax: true,
                        // locations: true
                    },
                    success: function(data) {
                        $('.all_locations').html(data);
                        updateActiveLink(url);
                        $('.loader-container').css('display', 'none');
                    }
                });
            });

            function updateActiveLink(url) {
                $('.pagination a').each(function() {
                    if ($(this).attr('href') === url) {
                        $(this).closest('li').addClass('active');
                    } else {
                        $(this).closest('li').removeClass('active');
                    }
                });
            }

            let table = $('#dataTable').DataTable({
                "autoWidth": false,
                paging: true,
                info: false,
                language: {
                    search: ""
                },
                dom: '<"top"i>rt<"bottom"pl><"clear">',
                'columnDefs': [{
                    "select": 'multi',
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': '',
                    'render': function(data, type, full, meta) {
                        return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(
                            data).html() + '">';
                    }
                }],
                
                fixedHeader: {
                    header: true
                },
                scrollCollapse: true,
                scrollY: '70vh',
                "initComplete": function(settings, json) {
                    $('body').find('.dt-scroll-body').addClass("custom-scroll");
                }
            });

            $('#dataTable-select-all').on('click', function() {
                // Get all rows with search applied
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            let checkedRowsData = new Set();
            let checkedRowId = '';
            table.on('change', 'input', function() {

                let rowData = table.column(0).nodes().filter(function(value, index) {
                    let inputElement = $(value).find('input');
                    return inputElement.prop('checked');
                });


                let rowUnique = table.column(0).nodes().filter(function(value, index) {
                    let inputElement = $(value).find('input');
                    if (inputElement.prop('checked')) {
                        let rowData2 = table.row($(value).closest('tr')).data()[1];
                        let rowData2Username = $(rowData2).find('input').val();
                        checkedRowId = $(rowData2).find('input').data('column').split('-')[1];
                        checkedRowsData.add(rowData2Username);
                    }
                });

                if (rowData.length > 0) {
                    $('#draggable').addClass('anim').removeClass('reverse-anim');
                }
                else {
                    $('#draggable').addClass('reverse-anim').removeClass('anim');
                }


                const dotsWrapper = $('.dots-wrapper');
                dotsWrapper.empty();

                for (let i = 0; i < rowData.length; i++) {
                    dotsWrapper.append('<span class="dot" style="width:8px;height:8px;"></span>')
                }

                $('#count2').text(rowData.length);
                if(rowData.length > 1){
                    $('#incident-btn').fadeOut('fast')
                }else{
                    $('#incident-btn').fadeIn();
                }
            });
            // ======================================
            $('#theme-select-bar').on('change',function(){
            var _token = "{{csrf_token()}}";
            var route = "{{ route('head_office.organisation_settings_update.multi') }}";
            var data = {
                setting_id: this.value,
                loc_ids: Array.from(checkedRowsData),
                _token: _token
            };
            $.post(route, data)
            .then(function(response) {
                    if (response) {
                        window.location.reload()
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
            });
            $('#access-select-bar').on('change',function(){
            var _token = "{{csrf_token()}}";
            var route = "{{ route('location.access.update.multi') }}";
            var data = {
                select_id: this.value,
                loc_ids: Array.from(checkedRowsData),
                _token: _token
            };
            $.post(route, data)
            .then(function(response) {
                    if (response) {
                        window.location.reload()
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
            });
            $('#tag-select-bar').on('change',function(){
            var _token = "{{csrf_token()}}";
            var route = "{{ route('location.tags.update.multi') }}";
            var data = {
                tag_id: this.value,
                loc_ids: Array.from(checkedRowsData),
                _token: _token
            };
            $.post(route, data)
            .then(function(response) {
                    if (response) {
                        window.location.reload()
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
            });
            // $('#archive-btn').on('change',function(){
            // var _token = "{{csrf_token()}}";
            // var route = "{{ route('location.archive.update.multi') }}";
            // var data = {
            //     archive_id: this.value,
            //     loc_ids: Array.from(checkedRowsData),
            //     _token: _token
            // };
            // $.post(route, data)
            // .then(function(response) {
            //         if (response) {
            //             window.location.reload()
            //         }
            //     })
            //     .catch(function(error) {
            //         console.log(error);
            //     })
            // });

            function setupActionButton(route,buttonId) {
                    var _token = "{{csrf_token()}}";
                    var data = {
                        action_id: buttonId, // Renamed for generality
                        loc_ids: Array.from(checkedRowsData),
                        _token: _token
                    };
                    $.post(route, data)
                    .then(function(response) {
                        if (response) {
                            window.location.reload();
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }

            $('#archive-btn-bar').on('click',function(){
                setupActionButton("{{route('location.archive.update.multi')}}",this.id)
            })
            $('#unarchive-btn-bar').on('click',function(){
                setupActionButton("{{route('location.unarchive.update.multi')}}",this.id)
            })
            $('#delete-btn-bar').on('click',function(){
                setupActionButton("{{route('location.delete.update.multi')}}",this.id + '?_token={{ csrf_token() }}')
            })
            $('#rename-btn-bar').on('click', function () {
                setupActionButton("{{ route('location.rename.update.multi') }}", this.id);
            });
            $('#restore-btn-bar').on('click',function(){
                setupActionButton("{{route('location.restore.update.multi')}}",this.id)
            })

            $('#enable-btn').on('click', function() {
                otpSubmit('enable', Array.from(checkedRowsData));
            });
            $('#disable-btn').on('click', function() {
                otpSubmit('disable', Array.from(checkedRowsData));
            });
            $('#incident-btn').on('click',function(){
                let currentUrl = window.location.href;

                let newUrl = currentUrl.replace(/\/head_office\/company\/info.*/, `/head_office/import_location/incidents/${checkedRowId}`);
                window.location.href = newUrl;
            })

            async function otpSubmit(action, usernames) {
                try {

                    const response = await fetch("/otp/security/all", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            usernames: usernames,
                            action: action
                        }),
                    });

                    // Handle the response here (e.g., check for success)
                    // const data = await response.json();
                    if (response.ok) {
                        alertify.alert('2-Step Factor Authentication status updated for all users!').set({
                            title: "Status Updated"
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                        // const body = await response.text();
                        // console.log(body)
                        // $('.all_locations').html(body);
                    }
                } catch (error) {
                    alertify.alert('Error occured while updating 2-Step Factor Authentication!').set({
                            title: "Status Updated"
                        });
                }
            }
        });

        function viewCode(element){
            $(element).hide();
            $(element).siblings().removeAttr('hidden');
        }

        $('.password-reset-btn').on('click',function(){
            if($(this).data('show_link') == 0){
                $('#password_reset_link').fadeOut('fast');
            }else{

                $('#password_reset_link').attr('href', $(this).data('link')).fadeIn('fast');
                $('#loc_pass_id').val($(this).data('id'));
            }
        })
        $('#direct-pass').on('click',function(){
            $('#location_pass_form').slideToggle();
        })
    </script>
@endisset

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var templateWrap = document.getElementById('template-wrap');
    var csvFile = document.getElementById('csv_file');

    templateWrap.addEventListener('click', function() {
        csvFile.click();
    });

    csvFile.addEventListener('change', function() {
        // Handle file selection here
        $('#template_form').submit()
    });
});

$('.btn-group-assign').on('click',function(){
    const mainContent = $(this).parent().siblings().find('.inside-content');
    $('.group-content-wrapper').empty().append(mainContent.clone());
})
$(document).ready(function(){
})
function showCode(element) {
    var $siblings = $(element).siblings().eq(0);

    $siblings.css('display', 'flex');

    $(element).add($siblings).on('mouseleave', function (event) {
        event.stopPropagation();
        $siblings.css('display', 'none');
    });
    $(element).add($siblings).on('mouseenter', function (event) {
        $siblings.css('display', 'flex');
    });
}


$(document).ready(function(){



     draggable = document.getElementById('draggable');
    dragBtn = document.querySelector('.drag-btn-2');

    var posX = 0,
        posY = 0,
        mouseX = 0,
        mouseY = 0;

    dragBtn.addEventListener('mousedown', mouseDown, false);
    window.addEventListener('mouseup', mouseUp, false);

    function mouseDown(e) {
        e.preventDefault();
        posX = e.clientX - draggable.offsetLeft;
        posY = e.clientY - draggable.offsetTop;
        window.addEventListener('mousemove', moveElement, false);
    }

    function mouseUp() {
        window.removeEventListener('mousemove', moveElement, false);
    }

    function moveElement(e) {
        console.log(e)
        mouseX = e.clientX - posX;
        mouseY = e.clientY - posY;

        const maxX = 1000 ;
        const maxY = window.innerHeight - draggable.offsetHeight;
        console.log(maxX)

        mouseX = Math.min(Math.max(mouseX, 0), maxX);
        mouseY = Math.min(Math.max(mouseY, 0), maxY);
        draggable.style.left = mouseX + 'px';
        draggable.style.top = mouseY + 'px';
    }
})
$('.locationAccessDropdown').on('click', function(event) {
        // Stop event propagation to parent elements
        event.stopPropagation();
    });
    $('.locationAccessDropdown').on('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        window.location.href = selectedOption.value;
    });
    $('.passwordResetDropdown').on('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        window.location.href = selectedOption.value;
    });

    $('.otpSecurityDropdown').on('click', function(event) {
        // Stop event propagation to parent elements
        event.stopPropagation();
    });

    $('.otpSecurityDropdown').on('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        window.location.href = selectedOption.value;
    });

// $('.loc').on('click', function() {
//     var customScroll = $(this).find('.custom-scroll');
//     var currentHeight = customScroll.height();
    
//     if (currentHeight == 50) {
//         customScroll.css('flex-direction', 'column');
//         customScroll.animate({ height: '100%' }, 300); // Toggle to auto height
//     } else {
//         customScroll.css('flex-direction', 'row');
//         customScroll.animate({ height: '50px' }, 300); // Toggle to 50px height
//     }
// });



$(document).ready(function () {
    // Show/Hide Filters
    $('#filters-show').on('click', function () {
        $('.filters-wrapper').toggle();
    });

    // Function to Apply Filters
    function applyFilters() {
    const searchText = $('#searchInput-locations').val().toLowerCase();
    const selectedCompanies = $('.location-filter:checked').map(function () {
        return $(this).val();
    }).get();
    const selectedSubTypes = $('.location-sub-filter:checked').map(function () {
        return $(this).val();
    }).get();
    const selectedCountry = $('.country-filter:checked').map(function () {
        return $(this).val();
    }).get();
    const selectedTags = $('.tags-filter:checked').map(function () {
        return $(this).val();
    }).get();
    const selectedGroups = $('.group-filter:checked').map(function () {
        return $(this).val();
    }).get();
    const selectedStat = $('.stat-filter:checked').map(function () {
        return $(this).val();
    }).get();

    $('.loc').each(function () {
        const feedbackText = $(this).text().toLowerCase();
        const companyId = $(this).data('location_type');
        const subTypeId = $(this).data('location_sub_type');
        const countryId = $(this).data('country');
        const tags = $(this).data('tags');
        const groups = $(this).data('groups');
        const stat = $(this).data('status'); // Assuming status data is available

        let show = true;

        // Filter by Search Text
        if (searchText && !feedbackText.includes(searchText)) {
            show = false;
        }

        // Filter by Company
        if (selectedCompanies.length > 0 && !selectedCompanies.includes(companyId.toString())) {
            show = false;
        }

        // Filter by SubType
        if (selectedSubTypes.length > 0 && !selectedSubTypes.includes(subTypeId.toString())) {
            show = false;
        }

        // Filter by Country
        if (selectedCountry.length > 0 && !selectedCountry.includes(countryId.toString())) {
            show = false;
        }

        // Filter by Tags
        if (selectedTags.length > 0 && !selectedTags.some(tag => tags.includes(tag))) {
            show = false;
        }

        // Filter by Groups
        if (selectedGroups.length > 0 && !selectedGroups.includes(groups)) {
            show = false;
        }

        // Filter by Status (assuming you have a status data attribute)
        if (selectedStat.length > 0 && !selectedStat.includes(stat.trim())) {
            console.log(selectedStat, stat)
            show = false;
        }

        // Show/Hide the row based on the filters
        if (show) {
            $(this).removeClass('d-none');
        } else {
            $(this).addClass('d-none');
        }
    });
}

// Event Listeners for Filters
$('#searchInput-locations').on('keyup', applyFilters);
$('.location-filter').on('change', applyFilters);
$('.location-sub-filter').on('change', applyFilters);
$('.country-filter').on('change', applyFilters);
$('.tags-filter').on('change', applyFilters);
$('.group-filter').on('change', applyFilters);
$('.stat-filter').on('change', applyFilters);


    $('#comunity-check').on('change', function () {
        if ($(this).is(':checked')) {
            $('#community-sub-types').slideDown();
        } else {
            $('#community-sub-types').slideUp();
        }
    });
});


$(document).ready(function () {
    // Initially hide all filter contents
    $(".filter-content").hide();

    // Handle chevron click to toggle visibility
    $(".toggle-chevron").off("click").click(function () {
    var $chevron = $(this);
    var $content = $chevron.closest("p").next(".filter-content");

    // Slide up all other open filter contents
    $(".filter-content").not($content).slideUp();
    $(".toggle-chevron").not($chevron).removeClass("fa-chevron-up").addClass("fa-chevron-down");

    // Toggle the current filter content
    $content.stop(true, true).slideToggle(function () {
        if ($content.is(":visible")) {
            $chevron.removeClass("fa-chevron-down").addClass("fa-chevron-up");
        } else {
            $chevron.removeClass("fa-chevron-up").addClass("fa-chevron-down");
        }
    });
});

});


$('.verification-code-btn').on('click', function () {
        const table = $(this).siblings().find('.new-table');
        const codeWrap = $('#verificationCodeWrap');
        codeWrap.empty();

            codeWrap.append(table.clone());

        $('#verificationModal').modal('show');
    });


// script for rename location

$(document).ready(function () {
    $('#rename-btn-bar').on('click', function () {
        const selectedLocations = getSelectedCheckboxValues(); // Dynamically fetch selected checkboxes
        if (selectedLocations.length === 0) {
            alert('Please select at least one location to rename.');
            return;
        }

        const renameFields = $('#renameFields');
        renameFields.empty();

        selectedLocations.forEach((oldName) => {
            renameFields.append(`
                <div class="form-group">
                    <label for="rename_${oldName}">Current Name: ${oldName}</label>
                    <input type="text" class="form-control" id="rename_${oldName}" name="loc_renames[${oldName}]" placeholder="Enter new name">
                </div>
            `);
        });

        $('#renameModal').modal('show');
    });

    $('#submitRename').on('click', function () {
        const formData = $('#renameForm').serialize();

        $.ajax({
            url: "{{ route('location.rename.update.multi') }}", // Replace with your route for renaming
            type: "POST",
            data: formData,
            success: function (response) {
                alert(response.success);
                $('#renameModal').modal('hide');
                location.reload(); // Reload to reflect changes
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });

    function getSelectedCheckboxValues() {
        const selected = [];
        $('input[name="id[]"]:checked').each(function () {
            selected.push($(this).val()); // Get the value of the checkbox
        });
        return selected;
    }
});
</script>
<script src="{{ asset('/js/alertify.min.js') }}"></script>
