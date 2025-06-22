@extends('layouts.head_office_app')
@section('title', 'Head office Settings')
@section('content')
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
        div.dt-container.dt-empty-footer .dt-scroll-body{
            border-bottom: none;
        }
        div.dt-container.dt-empty-footer .dt-scroll-body{
            border-bottom: none;
            padding-bottom: 10px;
        }
        .new-table tbody tr td:last-child{
            padding-right: 0px !important;
        }
        /* hidding edit button 😑 */
    .btn-wrapper-loc button:first-child{
        display: none;
    }

    .custom-button{
        font-size: 12px;
        scale: 0.8;
        padding: 5px;
    }
    </style>


    <div id="content" style="margin: 0;padding:0;">
        <div style="display: flex; justify-content: center; align-items: center;">
            <div class="content-page-heading custom-theme-heading" style="text-align: left">
                Locations
            </div>
        </div>

        @include('layouts.error')
        @if(isset($locations) && count($locations) > 0)
        <table class="new-table mx-auto" style="width: 100% !important;" id="locations-dataTable">
            <thead>
                <th>Username</th>
                <th>Info</th>
                <th>Tags</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($locations as $loc)
                    <tr>
                        <td>
                            <div style="width: fit-content !important;" class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                                onmouseleave='leaveHoverFunction(this)'>
                                <input onblur="blurFunction(this)" data-column='username-{{ $loc->location_id }}' type="text"
                                    value="{{ $loc->location->username }}" readonly>
                                <span style="display:none;"></span>

                                <div class="btn-wrapper-loc">
                                    <button title="click to edit" onclick="editFunction('username-{{ $loc->location_id }}')"><i
                                            class="fa-regular fa-pen-to-square"></i></button>
                                    <button title="click to copy text" onclick="copyFunction('username-{{ $loc->location_id }}')"><i
                                            class="fa-regular fa-copy"></i></button>
                                </div>
                            </div>
                            @if (isset($loc->location->location_type))
                                <div class="border border-2 p-1 mt-2 rounded" style="width: fit-content;font-size:10px;">
                                    {{$loc->location->location_type->name}}
                                </div>
                            @endif
                        </td>
                        <td >
                            <div class="m-0 d-flex flex-column gap-1" style="line-height:1.1;max-width:400px">
                                <div class="main-wrapper-loc  resizing-input" onmouseenter="hoverFunction(this)"
                                    onmouseleave='leaveHoverFunction(this)'>
                                    <input onblur="blurFunction(this)" data-column="location_code-{{ $loc->location_id }}" type="text"
                                        value="{{ $loc->location->trading_name }}" readonly>
                                    <span style="display:none;"></span>
                                    <div class="btn-wrapper-loc">
                                        <button title="click to edit" onclick="editFunction('location_code-{{ $loc->location_id }}')"><i
                                                class="fa-regular fa-pen-to-square"></i></button>
                                        <button title="click to copy text"
                                            onclick="copyFunction('location_code-{{ $loc->location_id }}')"><i
                                                class="fa-regular fa-copy"></i></button>
                                    </div>

                                </div>
                                <div>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 14.2864C3.14864 15.1031 2 16.2412 2 17.5C2 19.9853 6.47715 22 12 22C17.5228 22 22 19.9853 22 17.5C22 16.2412 20.8514 15.1031 19 14.2864M18 8C18 12.0637 13.5 14 12 17C10.5 14 6 12.0637 6 8C6 4.68629 8.68629 2 12 2C15.3137 2 18 4.68629 18 8ZM13 8C13 8.55228 12.5523 9 12 9C11.4477 9 11 8.55228 11 8C11 7.44772 11.4477 7 12 7C12.5523 7 13 7.44772 13 8Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>                                        
                                    {{ $loc->location->full_address }}
                                </div>
                                <div>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 7L10.1649 12.7154C10.8261 13.1783 11.1567 13.4097 11.5163 13.4993C11.8339 13.5785 12.1661 13.5785 12.4837 13.4993C12.8433 13.4097 13.1739 13.1783 13.8351 12.7154L22 7M6.8 20H17.2C18.8802 20 19.7202 20 20.362 19.673C20.9265 19.3854 21.3854 18.9265 21.673 18.362C22 17.7202 22 16.8802 22 15.2V8.8C22 7.11984 22 6.27976 21.673 5.63803C21.3854 5.07354 20.9265 4.6146 20.362 4.32698C19.7202 4 18.8802 4 17.2 4H6.8C5.11984 4 4.27976 4 3.63803 4.32698C3.07354 4.6146 2.6146 5.07354 2.32698 5.63803C2 6.27976 2 7.11984 2 8.8V15.2C2 16.8802 2 17.7202 2.32698 18.362C2.6146 18.9265 3.07354 19.3854 3.63803 19.673C4.27976 20 5.11984 20 6.8 20Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>                                   
                                    {{ $loc->location->email }} <span class="badge bg-info">primary</span>
                                </div>
                                <div>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.38028 8.85335C9.07627 10.303 10.0251 11.6616 11.2266 12.8632C12.4282 14.0648 13.7869 15.0136 15.2365 15.7096C15.3612 15.7694 15.4235 15.7994 15.5024 15.8224C15.7828 15.9041 16.127 15.8454 16.3644 15.6754C16.4313 15.6275 16.4884 15.5704 16.6027 15.4561C16.9523 15.1064 17.1271 14.9316 17.3029 14.8174C17.9658 14.3864 18.8204 14.3864 19.4833 14.8174C19.6591 14.9316 19.8339 15.1064 20.1835 15.4561L20.3783 15.6509C20.9098 16.1824 21.1755 16.4481 21.3198 16.7335C21.6069 17.301 21.6069 17.9713 21.3198 18.5389C21.1755 18.8242 20.9098 19.09 20.3783 19.6214L20.2207 19.779C19.6911 20.3087 19.4263 20.5735 19.0662 20.7757C18.6667 21.0001 18.0462 21.1615 17.588 21.1601C17.1751 21.1589 16.8928 21.0788 16.3284 20.9186C13.295 20.0576 10.4326 18.4332 8.04466 16.0452C5.65668 13.6572 4.03221 10.7948 3.17124 7.76144C3.01103 7.19699 2.93092 6.91477 2.9297 6.50182C2.92833 6.0436 3.08969 5.42311 3.31411 5.0236C3.51636 4.66357 3.78117 4.39876 4.3108 3.86913L4.46843 3.7115C4.99987 3.18006 5.2656 2.91433 5.55098 2.76999C6.11854 2.48292 6.7888 2.48292 7.35636 2.76999C7.64174 2.91433 7.90747 3.18006 8.43891 3.7115L8.63378 3.90637C8.98338 4.25597 9.15819 4.43078 9.27247 4.60655C9.70347 5.26945 9.70347 6.12403 9.27247 6.78692C9.15819 6.96269 8.98338 7.1375 8.63378 7.4871C8.51947 7.60142 8.46231 7.65857 8.41447 7.72538C8.24446 7.96281 8.18576 8.30707 8.26748 8.58743C8.29048 8.66632 8.32041 8.72866 8.38028 8.85335Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>                                        
                                    {{ $loc->location->telephone_no }} <span class="badge bg-info">primary</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @livewire('location-tags-manager', ['loc_id' => $loc->location->id])
                            {{-- @if (count($loc->groups))
                                <div @if (count($loc->groups) > 1) style="height: 50px;overflow-y: auto;display:flex;align-items:center;flex-wrap:wrap;width:144px;" @endif
                                    class="custom-scroll">
                                    @foreach ($loc->groups as $assignment)
                                        <p class="btn group-btn">{{ $assignment->group->group }} <a title="Remove Group"
                                                href="{{ route('head_office.organisation.delete_group', ['id'=>$assignment->id,'_token'=>csrf_token()]) }}"
                                                data-msg="Are you sure you want to remove this assignment?"
                                                class="text-danger delete_button float-right"><i
                                                    class="fa fa-xmark"></i></a></p>
                                    @endforeach
                                </div>
                            @endif
                            <p style="margin-top: 10px;"><button data-bs-toggle="modal" data-bs-target='#group_assing_modal'
                                    class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center mx-auto"><i
                                        class="fa fa-plus"></i></button></p>
                            <div class="d-none">
                                <div class=" inside-content">
                                    <div class="card-body">
                                        <p><strong>Location: </strong> {{ $loc->location->name() }}</p>
                                        <form method="post"
                                            action="{{ route('head_office.organisation.assign_groups_save', $loc->id) }}">
                                            @csrf
                                            <input type="hidden" name="location_id" value="{{ $loc->id }}">
                                            <p>Please select a group/tier</p>
                                            @include('head_office.my_organisation.tree-list', [
                                                'groups' => $allGroups,
                                            ])
                                            <input type="submit" name="save" value="Save" class="btn btn-info">
                                        </form>
                                    </div>
                                </div>
                            </div> --}}
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-2 " style="width: 80%;">
                                <a href="{{route('head_office.color_branding_get',['id'=>$loc->location_id,'_token'=>csrf_token()])}}" class="primary-btn" ><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.7914 12.6074C21.0355 12.3981 21.1575 12.2935 21.2023 12.169C21.2415 12.0598 21.2415 11.9402 21.2023 11.831C21.1575 11.7065 21.0355 11.6018 20.7914 11.3926L12.3206 4.13196C11.9004 3.77176 11.6903 3.59166 11.5124 3.58725C11.3578 3.58342 11.2101 3.65134 11.1124 3.77122C11 3.90915 11 4.18589 11 4.73936V9.03462C8.86532 9.40807 6.91159 10.4897 5.45971 12.1139C3.87682 13.8845 3.00123 16.1759 3 18.551V19.1629C4.04934 17.8989 5.35951 16.8765 6.84076 16.1659C8.1467 15.5394 9.55842 15.1683 11 15.0705V19.2606C11 19.8141 11 20.0908 11.1124 20.2288C11.2101 20.3486 11.3578 20.4166 11.5124 20.4127C11.6903 20.4083 11.9004 20.2282 12.3206 19.868L20.7914 12.6074Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Remotely Access</a>
                                <a href="{{ route('head_office.location_page_view', ['id' => $loc->id,'_token'=>csrf_token()]) }}" class="outline-btn" ><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 16V21M12 16L18 21M12 16L6 21M21 3V11.2C21 12.8802 21 13.7202 20.673 14.362C20.3854 14.9265 19.9265 15.3854 19.362 15.673C18.7202 16 17.8802 16 16.2 16H7.8C6.11984 16 5.27976 16 4.63803 15.673C4.07354 15.3854 3.6146 14.9265 3.32698 14.362C3 13.7202 3 12.8802 3 11.2V3M8 9V12M12 7V12M16 11V12M22 3H2" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                     View</a>

                                @if ($ho_u->get_permissions() !== null )
                                    @if ($ho_u->get_permissions()->super_access)
                                        <a href="#" data-super="1" data-show_link="{{$loc->location->is_admin_password}}" data-id="{{$loc->id}}" data-link='{{ route('head_office.location.password_reset_link', ['id' => $loc->location_id, '_token' => csrf_token()]) }}'
                                            class="primary-btn password-reset-btn" data-bs-toggle='modal' data-bs-target='#password_modal' data-bs-placement='left'
                                            title="send password reset email to location account">Password Reset</a>
                                    @elseif (in_array($loc->id, isset($ho_u->assigned_locations) ? json_decode($ho_u->assigned_locations,true) : null))
                                    <a href="#" data-super="0" data-show_link="{{$loc->location->is_admin_password}}" data-id="{{$loc->id}}" data-link='{{ route('head_office.location.password_reset_link', ['id' => $loc->location_id, '_token' => csrf_token()]) }}'
                                        class="primary-btn password-reset-btn" data-bs-toggle='modal' data-bs-target='#password_modal' data-bs-placement='left'
                                        title="send password reset email to location account">Password Reset</a>
                                    @endif
                                 @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="mx-4">No Locations Assigned yet!</p>
        @endif

    </div>
    <div class="modal fade" id="group_assing_modal" tabindex="-1" aria-labelledby="group_assing_modalLabel"
        aria-hidden="true">
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
                <p style="display: none;" id="admin-msg">Only Admin can Change the passsword</p>
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


@section('scripts')
    <script>
        $(document).ready(function() {
            loadActiveTab();

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

        $(document).ready(function() {
            $('.btn-group-assign').on('click', function() {
                const mainContent = $(this).parent().siblings().find('.inside-content');
                $('.group-content-wrapper').empty().append(mainContent.clone());
            })
        })

        function copyFunction(inputId) {
        var copyText = document.querySelector(`[data-column="${inputId}"]`);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
    }
    function hoverFunction(element) {
        $(element).find('.btn-wrapper-loc').stop(true, true).css('visibility', 'visible');
    }

    function leaveHoverFunction(element) {
        $(element).find('.btn-wrapper-loc').stop(true, true).css('visibility', 'hidden');
    }

    $('.password-reset-btn').on('click',function(){
        console.log($(this).data('show_link'));
            if($(this).data('show_link') == 0){
                $('#password_reset_link').fadeOut('fast');
            }else{

                $('#password_reset_link').attr('href', $(this).data('link')).fadeIn('fast');
                $('#loc_pass_id').val($(this).data('id'));
                console.log($(this).data('id'))
            }

            if($(this).data('super') == 1){
                $('#direct-pass').fadeIn('fast');
                $('#loc_pass_id').val($(this).data('id'));
            }else{
                $('#direct-pass').fadeOut('fast');
            }

            if( $(this).data('show_link') == 0 && $(this).data('super') == 0){
                $('#admin-msg').fadeIn();
            }else{
                $('#admin-msg').fadeOut();
            }
        })
        $('#direct-pass').on('click',function(){
            $('#location_pass_form').slideToggle();
        })
    </script>




    <script src="{{ asset('js/alertify.min.js') }}"></script>

@endsection
@endsection
