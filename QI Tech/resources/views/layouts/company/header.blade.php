@php
    $headOffice = Auth::guard('web')->user()->selected_head_office;
    $user = Auth::guard('web')->user();
    $head_office_user = $user->getHeadOfficeUser($headOffice->id);
    $matching_contacts = $headOffice->matching_contacts();  
    $customTheme = false;
    if ($headOffice->link_token == Session::get('token') && Session::get('token') != null) {
        $customTheme = true;
        $themeData = $headOffice;
    }
    $unApproved_count_holding_area = App\Models\Headoffices\ReceivedNationalAlert::where('status', 'unapproved')
    ->where('head_office_id', $headOffice->id)
    ->count();
    
@endphp

@if (isset($matching_contacts) && $matching_contacts->count() > 0 && session('show_toast') == true)
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11" id="main-live-toast">
        <div id="liveToast" class="toast d-block opacity-100 bg-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <svg class="me-1" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.00002 21.8174C4.6026 22 5.41649 22 6.8 22H17.2C18.5835 22 19.3974 22 20 21.8174M4.00002 21.8174C3.87082 21.7783 3.75133 21.7308 3.63803 21.673C3.07354 21.3854 2.6146 20.9265 2.32698 20.362C2 19.7202 2 18.8802 2 17.2V6.8C2 5.11984 2 4.27976 2.32698 3.63803C2.6146 3.07354 3.07354 2.6146 3.63803 2.32698C4.27976 2 5.11984 2 6.8 2H17.2C18.8802 2 19.7202 2 20.362 2.32698C20.9265 2.6146 21.3854 3.07354 21.673 3.63803C22 4.27976 22 5.11984 22 6.8V17.2C22 18.8802 22 19.7202 21.673 20.362C21.3854 20.9265 20.9265 21.3854 20.362 21.673C20.2487 21.7308 20.1292 21.7783 20 21.8174M4.00002 21.8174C4.00035 21.0081 4.00521 20.5799 4.07686 20.2196C4.39249 18.6329 5.63288 17.3925 7.21964 17.0769C7.60603 17 8.07069 17 9 17H15C15.9293 17 16.394 17 16.7804 17.0769C18.3671 17.3925 19.6075 18.6329 19.9231 20.2196C19.9948 20.5799 19.9996 21.0081 20 21.8174M16 9.5C16 11.7091 14.2091 13.5 12 13.5C9.79086 13.5 8 11.7091 8 9.5C8 7.29086 9.79086 5.5 12 5.5C14.2091 5.5 16 7.29086 16 9.5Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            <strong class="me-auto">Matching Contacts</strong>
            {{-- <small>{{$matching_contacts->first()->created_at->diffForHumans()}}</small> --}}
            <small>
                {{ optional($matching_contacts->first())->created_at ? $matching_contacts->first()->created_at->diffForHumans() : '' }}
            </small>
            
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="toast" aria-label="Close" onclick="closeToast()"></button>
        </div>
        <div class="toast-body opacity-100">
            @foreach ($matching_contacts as $match_contact)
                @php
                    $contact_1 = $match_contact->get_contact_1;
                    $contact_2 = $match_contact->get_contact_2;
                @endphp
                <div class="d-flex align-items-center gap- justify-content-between mb-2">
                    <div class="d-flex  align-items-center gap-4 justify-content-between">
                        <div  class="d-flex flex-column gap-2 align-items-center ">
                            <img class="border" style="width: 40px;height:40px;border-radius:50%; object-fit:cover; object-position:top"
                            src="{{ isset($contact_1->avatar) && file_exists(public_path('v2/' . $contact_1->avatar)) ? asset('v2/' . $contact_1->avatar) : asset('images/svg/logo_blue.png') }}">
        
                            <p class="m-0">{{ $contact_1->name }}</p>
                        </div>
    
                        
                        <i class="fa-solid fa-people-arrows text-info" style="margin-bottom: 1rem;font-size: 1.4rem;"></i>


                        <div class="d-flex flex-column gap-2 align-items-center ">
                            <img class="border" style="width: 40px;height:40px;border-radius:50%; object-fit:cover; object-position:top"
                            src="{{ isset($contact_2->avatar) && file_exists(public_path('v2/' . $contact_2->avatar)) ? asset('v2/' . $contact_2->avatar) : asset('images/svg/logo_blue.png') }}">
                            <p class="m-0">{{ $contact_2->name }}</p>
                        </div>
                    </div>
                    <div class="badge bg-info " style="font-size: 12px">{{$match_contact->match}}%</div>
                </div>
            @endforeach

            <a href="{{ route('head_office.contacts.index') }}" class="primary-btn btn-sm mt-4" style="width: fit-content;">Resolve</a>
        </div>
        </div>
    </div>
    
@endif

<style>
    .coustom33 ul li a{
        width: 100%;
        align-items: center;
        display: grid !important;
        grid-template-columns: auto 1fr;
        gap: 10px;
    }
    .user_status{
        display: grid;
        place-items: center;
        padding: 1px;
        width: 14px !important;
        height: 14px !important;
    }
    .user_status.grey{
        background: rgb(182, 181, 182) !important;
    }
    .user_status svg{
  width: 90% !important;
  height: 90% !important;
}
.user_status svg path{
  /* fill: #fff !important; */
  stroke: #fff !important;
}
</style>
    
    <header class="custom-header-color coustom33" style="background:{{ $headOffice->primary_color ? $headOffice->primary_color : 'inherit' }}">

    @if ($customTheme)
        <style>
            .top-heading-center-tabs > a > span , .top-heading-center-tabs > a.active > span{
                    background: var(--icon-nav-color) !important;


                }

            .custom-theme-heading{
                color: {{$headOffice->portal_section_heading_color}};
            }
            .primary-btn,.btn-info{
                background: {{$headOffice->portal_primary_btn_color}} !important;
                border: 1px solid {{$headOffice->portal_primary_btn_color}} !important;
                color: {{$headOffice->portal_primary_btn_text_color}} !important;
            }
            </style>
    @endif

    <div class="22" style="display: flex; flex-direction: row">

    <div id="menu-container" style="position: relative; display: flex; align-items: center; padding-top:10px;justify-content: center; height: 60px; width: 40px;">
    <img src="{{asset('admin_assets/img/menu-01.svg')}}" alt="menu icon"
         style="cursor: pointer; width: 28px; height: 28px;" onclick="toggleMenu()">

         <ul id="menu-list" style="list-style-type: none; margin: 0; padding: 0; display: none; position: absolute; top: 65px; left: 0; background-color: #ffffff; border-radius: 8px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); z-index: 100; width: 200px; overflow: hidden;">

<li style="padding: 20px 0 5px 40px; display: flex; align-items: center;">
    <a href="" style="text-decoration: none; color: #000000; display: flex; align-items: center; gap: 10px; cursor: pointer;" title="Coming soon">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M21 21L16.65 16.65M11 6C13.7614 6 16 8.23858 16 11M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <p class="m-0">Dashboard</p>
</a>

</li>
<li style="padding: 10px 0 5px 40px; display: flex; align-items: center; " title="Coming soon">
    <a href="#" class="nli" style="text-decoration: none; color: #000000; display: flex; align-items: center; gap: 10px; cursor: pointer;">
        <svg width="18" height="18" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 9H21M9 9L9 21M7.8 3H16.2C17.8802 3 18.7202 3 19.362 3.32698C19.9265 3.6146 20.3854 4.07354 20.673 4.63803C21 5.27976 21 6.11984 21 7.8V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V7.8C3 6.11984 3 5.27976 3.32698 4.63803C3.6146 4.07354 4.07354 3.6146 4.63803 3.32698C5.27976 3 6.11984 3 7.8 3Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Boards
    </a>
</li>
<li style="padding: 10px 0 5px 40px; display: flex;gap: 10px; align-items: center;">
    <a href="{{ route('case_manager.index') }}" class="@if (in_array(request()->route()->getName(), [
                    'case_manager.edit_report',
                    'case_manager.request_information',
                    'case_manager.index',
                    'case_manager.view',
                    'case_manager.view_report',
                    'case_manager.view_root_cause_analysis',
                    'case_manager.view_sharing',
                    'case_manager.intelligence.mrege_contact',
                    'head_office.case.requested_informations',
                ])) active @endif" style="text-decoration: none; color: black; display: flex; align-items: center; gap: 10px; cursor: pointer;">
        <svg width="18" height="18" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16 7C16 6.07003 16 5.60504 15.8978 5.22354C15.6204 4.18827 14.8117 3.37962 13.7765 3.10222C13.395 3 12.93 3 12 3C11.07 3 10.605 3 10.2235 3.10222C9.18827 3.37962 8.37962 4.18827 8.10222 5.22354C8 5.60504 8 6.07003 8 7M12.8 17.5H17.7C17.98 17.5 18.12 17.5 18.227 17.4455C18.3211 17.3976 18.3976 17.3211 18.4455 17.227C18.5 17.12 18.5 16.98 18.5 16.7V14.3C18.5 14.02 18.5 13.88 18.4455 13.773C18.3976 13.6789 18.3211 13.6024 18.227 13.5545C18.12 13.5 17.98 13.5 17.7 13.5H12.8C12.52 13.5 12.38 13.5 12.273 13.5545C12.1789 13.6024 12.1024 13.6789 12.0545 13.773C12 13.88 12 14.02 12 14.3V16.7C12 16.98 12 17.12 12.0545 17.227C12.1024 17.3211 12.1789 17.3976 12.273 17.4455C12.38 17.5 12.52 17.5 12.8 17.5ZM6.8 21H17.2C18.8802 21 19.7202 21 20.362 20.673C20.9265 20.3854 21.3854 19.9265 21.673 19.362C22 18.7202 22 17.8802 22 16.2V11.8C22 10.1198 22 9.27976 21.673 8.63803C21.3854 8.07354 20.9265 7.6146 20.362 7.32698C19.7202 7 18.8802 7 17.2 7H6.8C5.11984 7 4.27976 7 3.63803 7.32698C3.07354 7.6146 2.6146 8.07354 2.32698 8.63803C2 9.27976 2 10.1198 2 11.8V16.2C2 17.8802 2 18.7202 2.32698 19.362C2.6146 19.9265 3.07354 20.3854 3.63803 20.673C4.27976 21 5.11984 21 6.8 21Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <p class="m-0">Case Manager</p>
    </a>
</li>
<li style="padding: 10px 0 5px 40px; display: flex; align-items: center;gap: 10px; color:#000000;">
    <a href="{{ route('head_office.contacts.index') }}"
                    class="chup-kr" style="color: #000000"><svg class="me-1" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.00002 21.8174C4.6026 22 5.41649 22 6.8 22H17.2C18.5835 22 19.3974 22 20 21.8174M4.00002 21.8174C3.87082 21.7783 3.75133 21.7308 3.63803 21.673C3.07354 21.3854 2.6146 20.9265 2.32698 20.362C2 19.7202 2 18.8802 2 17.2V6.8C2 5.11984 2 4.27976 2.32698 3.63803C2.6146 3.07354 3.07354 2.6146 3.63803 2.32698C4.27976 2 5.11984 2 6.8 2H17.2C18.8802 2 19.7202 2 20.362 2.32698C20.9265 2.6146 21.3854 3.07354 21.673 3.63803C22 4.27976 22 5.11984 22 6.8V17.2C22 18.8802 22 19.7202 21.673 20.362C21.3854 20.9265 20.9265 21.3854 20.362 21.673C20.2487 21.7308 20.1292 21.7783 20 21.8174M4.00002 21.8174C4.00035 21.0081 4.00521 20.5799 4.07686 20.2196C4.39249 18.6329 5.63288 17.3925 7.21964 17.0769C7.60603 17 8.07069 17 9 17H15C15.9293 17 16.394 17 16.7804 17.0769C18.3671 17.3925 19.6075 18.6329 19.9231 20.2196C19.9948 20.5799 19.9996 21.0081 20 21.8174M16 9.5C16 11.7091 14.2091 13.5 12 13.5C9.79086 13.5 8 11.7091 8 9.5C8 7.29086 9.79086 5.5 12 5.5C14.2091 5.5 16 7.29086 16 9.5Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Contacts</a>
</li>
<li style="padding: 10px 0 20px 40px; display: flex; align-items: center; color:#000000;">
    <a href="{{ route('head_office.locations_page') }}"
    style="color: #000000"><svg width="18" height="18" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.5 7H10.25M7.5 11H10.25M7.5 15H10.25M13.75 7H16.5M13.75 11H16.5M13.75 15H16.5M20 21V6.2C20 5.0799 20 4.51984 19.782 4.09202C19.5903 3.71569 19.2843 3.40973 18.908 3.21799C18.4802 3 17.9201 3 16.8 3H7.2C6.07989 3 5.51984 3 5.09202 3.21799C4.71569 3.40973 4.40973 3.71569 4.21799 4.09202C4 4.51984 4 5.0799 4 6.2V21M22 21H2" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

                        <p class="m-0">Locations</p></a>
</li>
</ul>



</div>



<script>
    function toggleMenu() {
        var menu = document.getElementById("menu-list");
        if (menu.style.display === "block") {
            menu.style.display = "none";
        } else {
            menu.style.display = "block";
        }
    }

    // Optional: Close the menu if clicked outside of it
    document.addEventListener("click", function(event) {
        var menu = document.getElementById("menu-list");
        var menuContainer = document.getElementById("menu-container");
        if (!menuContainer.contains(event.target)) {
            menu.style.display = "none";
        }
    });
</script>



        <div class="logo">
        <a href="{{ route('case_manager.index') }}"><img
                src="{{ $headOffice->logo ?  $headOffice->logo : asset('/images/svg/logo_blue.png') }} "
                alt="logo-img"></a>
        </div>
        <div class="heading-line" style="color:{{ $headOffice->portal_text_color ? $headOffice->portal_text_color : 'inherit' }};">
        @if (in_array(request()->route()->getName(), [
                    'case_manager.index',
                    'case_manager.view',
                    'case_manager.view_report',
                    'case_manager.view_root_cause_analysis',
                    'case_manager.view_sharing',
                    'case_manager.intelligence.mrege_contact',
                    'head_office.case.requested_informations',
                ]))
                Case Manager
            @else
                {{ $headOffice->portal_text ? $headOffice->portal_text : 'Work Management' }}
            @endif
        </div>    
    </div>
    
    <div class="right-side-items">
        <!--
             <div class="notification-icon">
            <a href="#" title="Notifications" onclick="preventHash(event)">
                <i class="fa-regular fa-bell custom-nav-colors"></i>
            </a>
            </div>
            <div class="help-icon" onclick="preventHash(event)">
            <a href="#" title="Help">
                <i class="fa-regular fa-question custom-nav-colors"></i>  
            </a>
            </div>
        -->
        <div class="user-icon">
            <a id="user-profile-ico" href="#" class="e-drop-down" data-target="#user-pr-menu"
                onclick="preventHash(event)">
                <div class="user-icon-circle" title="{{  $user->name }}">
                    {{-- @if (isset($user->logo))
                        <img src="{{ $user->logo }}" alt="png_img"
                            style="width: 30px;height:30px;border-radius:50%;">
                    @else
                        <div class="user-img-placeholder" id="user-img-place" style="width:40px;height:40px;">
                            {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $user->name))) }}
                        </div>
                    @endif --}}
                    <div class="user-img-placeholder" id="user-img-place" style="width:30px;height:30px; overflow:hidden; border-radius:50%;">
                        <img src="{{ $user->logo }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                    </div>

                        @if ($head_office_user->is_active == true)
                            <div class="user_status {{ ($head_office_user->work_status == 1 || $head_office_user->work_status == 3) || $head_office_user->do_not_disturb == 1 ? 'grey' : '' }}" style="width: 12px;height: 12px;border-radius: 50%;background-color: #10b70b;position: absolute;right: -5px;top: 20px">
                                @if ($head_office_user->work_status == 0)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.5 7H14.5M9.5 11H14.5M9.5 15H14.5M18 21V6.2C18 5.0799 18 4.51984 17.782 4.09202C17.5903 3.71569 17.2843 3.40973 16.908 3.21799C16.4802 3 15.9201 3 14.8 3H9.2C8.0799 3 7.51984 3 7.09202 3.21799C6.71569 3.40973 6.40973 3.71569 6.21799 4.09202C6 4.51984 6 5.0799 6 6.2V21M20 21H4" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                @elseif($head_office_user->work_status == 1)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.25 21.9595L12 12.0002M17 3.33995C12.6868 0.849735 7.28964 1.93783 4.246 5.68314C3.94893 6.0487 3.80039 6.23148 3.75718 6.49336C3.7228 6.70172 3.77373 6.97785 3.88018 7.16024C4.01398 7.38947 4.25111 7.52638 4.72539 7.8002L19.2746 16.2002C19.7489 16.474 19.986 16.6109 20.2514 16.6122C20.4626 16.6132 20.7272 16.5192 20.8905 16.3853C21.0957 16.2169 21.1797 15.9969 21.3477 15.5568C23.0695 11.0483 21.3132 5.83017 17 3.33995ZM17 3.33995C15.0868 2.23538 11.2973 5.21728 8.5359 10.0002M17 3.33995C18.9132 4.44452 18.2255 9.21728 15.4641 14.0002M22 22.0002H2" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                @elseif ($head_office_user->work_status == 2)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 21V13.6C9 13.0399 9 12.7599 9.109 12.546C9.20487 12.3578 9.35785 12.2049 9.54601 12.109C9.75993 12 10.04 12 10.6 12H13.4C13.9601 12 14.2401 12 14.454 12.109C14.6422 12.2049 14.7951 12.3578 14.891 12.546C15 12.7599 15 13.0399 15 13.6V21M2 9.5L11.04 2.72C11.3843 2.46181 11.5564 2.33271 11.7454 2.28294C11.9123 2.23902 12.0877 2.23902 12.2546 2.28295C12.4436 2.33271 12.6157 2.46181 12.96 2.72L22 9.5M4 8V17.8C4 18.9201 4 19.4802 4.21799 19.908C4.40974 20.2843 4.7157 20.5903 5.09202 20.782C5.51985 21 6.0799 21 7.2 21H16.8C17.9201 21 18.4802 21 18.908 20.782C19.2843 20.5903 19.5903 20.2843 19.782 19.908C20 19.4802 20 18.9201 20 17.8V8L13.92 3.44C13.2315 2.92361 12.8872 2.66542 12.5091 2.56589C12.1754 2.47804 11.8246 2.47804 11.4909 2.56589C11.1128 2.66542 10.7685 2.92361 10.08 3.44L4 8Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                @elseif ($head_office_user->work_status == 3)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 4.6C15 4.03995 15 3.75992 14.891 3.54601C14.7951 3.35785 14.6422 3.20487 14.454 3.10899C14.2401 3 13.9601 3 13.4 3H10.6C10.0399 3 9.75992 3 9.54601 3.10899C9.35785 3.20487 9.20487 3.35785 9.10899 3.54601C9 3.75992 9 4.03995 9 4.6V7.4C9 7.96005 9 8.24008 8.89101 8.45399C8.79513 8.64215 8.64215 8.79513 8.45399 8.89101C8.24008 9 7.96005 9 7.4 9H4.6C4.03995 9 3.75992 9 3.54601 9.10899C3.35785 9.20487 3.20487 9.35785 3.10899 9.54601C3 9.75992 3 10.0399 3 10.6V13.4C3 13.9601 3 14.2401 3.10899 14.454C3.20487 14.6422 3.35785 14.7951 3.54601 14.891C3.75992 15 4.03995 15 4.6 15H7.4C7.96005 15 8.24008 15 8.45399 15.109C8.64215 15.2049 8.79513 15.3578 8.89101 15.546C9 15.7599 9 16.0399 9 16.6V19.4C9 19.9601 9 20.2401 9.10899 20.454C9.20487 20.6422 9.35785 20.7951 9.54601 20.891C9.75992 21 10.0399 21 10.6 21H13.4C13.9601 21 14.2401 21 14.454 20.891C14.6422 20.7951 14.7951 20.6422 14.891 20.454C15 20.2401 15 19.9601 15 19.4V16.6C15 16.0399 15 15.7599 15.109 15.546C15.2049 15.3578 15.3578 15.2049 15.546 15.109C15.7599 15 16.0399 15 16.6 15H19.4C19.9601 15 20.2401 15 20.454 14.891C20.6422 14.7951 20.7951 14.6422 20.891 14.454C21 14.2401 21 13.9601 21 13.4V10.6C21 10.0399 21 9.75992 20.891 9.54601C20.7951 9.35785 20.6422 9.20487 20.454 9.10899C20.2401 9 19.9601 9 19.4 9L16.6 9C16.0399 9 15.7599 9 15.546 8.89101C15.3578 8.79513 15.2049 8.64215 15.109 8.45399C15 8.24008 15 7.96005 15 7.4V4.6Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>


                                @elseif ($head_office_user->work_status == 4)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.5 19C4.01472 19 2 16.9853 2 14.5C2 12.1564 3.79151 10.2313 6.07974 10.0194C6.54781 7.17213 9.02024 5 12 5C14.9798 5 17.4522 7.17213 17.9203 10.0194C20.2085 10.2313 22 12.1564 22 14.5C22 16.9853 19.9853 19 17.5 19C13.1102 19 10.3433 19 6.5 19Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                @elseif ($head_office_user->work_status == 5)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 21C20 19.6044 20 18.9067 19.8278 18.3389C19.44 17.0605 18.4395 16.06 17.1611 15.6722C16.5933 15.5 15.8956 15.5 14.5 15.5H9.5C8.10444 15.5 7.40665 15.5 6.83886 15.6722C5.56045 16.06 4.56004 17.0605 4.17224 18.3389C4 18.9067 4 19.6044 4 21M16.5 7.5C16.5 9.98528 14.4853 12 12 12C9.51472 12 7.5 9.98528 7.5 7.5C7.5 5.01472 9.51472 3 12 3C14.4853 3 16.5 5.01472 16.5 7.5Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                @endif
                            </div>
                            
                        @endif

                        @if ($head_office_user->do_not_disturb == true)
                        <div class="user_status do_not " style="width: 12px;height: 12px;border-radius: 50%;background-color: rgb(227,74,82);position: absolute;left: -5px;top: 20px">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                        </div>
                        @endif
                </div>
            </a>

            <div class="user-info-wrapper">
                @if (isset($user->logo))
                    <img src="{{ $user->logo }}" alt="png_img">
                @else
                    <div class="user-img-placeholder" id="user-img-place">
                        {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $user->name))) }}
                    </div>
                @endif
                <div class="user-info-details">
                    <h5 class="user-info-name">{{ $user->name }}</h5>
                    <h6>{{ $head_office_user->position }}</h6>
                    <select class=" form-select form-select-sm status" aria-label=".form-select-sm example">
                        <option value="1">Online</option>
                        <option value="2">Away</option>
                        <option value="3">Do Not Disturb</option>
                    </select>
                    <a href="{{ route('head_office.view_profile', ['tab' => 'aboutMeProfile']) }}">View Info</a>
                </div>
            </div>
        </div>

        <div id="user-pr-menu" class="custom-menu user-profile-menu-tr hidden" style="z-index: 200">
            <div class="row">
                <div class="col-12">
                    <div class="logo-menu" style="display: flex; align-items: center; height: 100%;">
                        <a href="{{ route('case_manager.index') }}">
                         <img height="60px"
                            src="{{ $headOffice->logo ? $headOffice->logo : asset('/images/svg/logo_blue.png') }}"
                            alt="logo">
                        </a>
                    </div>
                </div>

                <div class="col-8">
                    <div class="py-1 blue-heading">Account</div>
                    <div class="ps-3 py-1"><a
                            href="{{ route('head_office.view_profile', ['tab' => 'aboutMeProfile']) }}"><span
                                class="pe-1 gray-heading"> <img class="menuIcons"
                                    src="{{ asset('v2/images/icons/menu-myprofile.svg') }}"></span>
                            My Profile</a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="py-1 blue-heading">Status</div>
                    <div class="ps-0 py-1 d-flex align-items-center gap-1"><span class="status-circle status-live {{$head_office_user->is_active ? '' : 'bg-danger'}}" ></span> {{$head_office_user->is_active ? 'Active' : 'Offline'}}</div>
                </div>
                <div class="col-8">
                    <div class="py-1 blue-heading">Settings</div>
                    <!-- <div class="ps-3 py-2"> -->
                    <div class="ps-3 py-1"><a
                            href="{{ route('head_office.be_spoke_form.index', ['tab' => 'AllFormBespoke']) }}"><img
                                class="menuIcons" src="{{ asset('v2/images/icons/menu-forms.svg') }}">
                            Forms</a></div>
                    <div class="ps-3 py-1"><a
                            href="{{ route('head_office.company_info', ['tab' => 'InfoCompany']) }}"><img
                                class="menuIcons" src="{{ asset('v2/images/icons/menu-organization.svg') }}">
                            Company</a></div>
                    <div class="ps-3 py-1"><a
                            href="{{ route('head_office.head_office_users', ['tab' => 'MemeberTeam']) }}"><img
                                class="menuIcons" src="{{ asset('v2/images/icons/menu-teams.svg') }}"> Team</a></div>
                    <div class="ps-3 py-1"><a
                            href="{{ route('head_office.approved_location.users', ['tab' => 'ApprovedUser']) }}"><img
                                class="menuIcons" src="{{ asset('v2/images/icons/menu-locationusers.svg') }}"> Location
                            User</a></div>
                    <div class="ps-3 py-1"><a
                            href="{{ route('head_office.gdpr.index') }}"><img
                                class="menuIcons" src="{{ asset('v2/images/icons/icon-tags.svg') }}"> Data Retention</a></div>
                    <div class="ps-3 py-1" data-toggle="tooltip" data-placement="left" title="Coming Soon"><a onclick="event.preventDefault();" href="{{ route('head_office.psa', ['tab' => 'AlertsTem']) }}"><img
                                class="menuIcons" src="{{ asset('v2/images/icons/alerts.svg') }}"> Alerts</a></div>
                    
                                <div class="ps-3 py-1">
    <a href="{{ route('head_office.contacts_merge') }}">
        <svg class="me-1" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.00002 21.8174C4.6026 22 5.41649 22 6.8 22H17.2C18.5835 22 19.3974 22 20 21.8174M4.00002 21.8174C3.87082 21.7783 3.75133 21.7308 3.63803 21.673C3.07354 21.3854 2.6146 20.9265 2.32698 20.362C2 19.7202 2 18.8802 2 17.2V6.8C2 5.11984 2 4.27976 2.32698 3.63803C2.6146 3.07354 3.07354 2.6146 3.63803 2.32698C4.27976 2 5.11984 2 6.8 2H17.2C18.8802 2 19.7202 2 20.362 2.32698C20.9265 2.6146 21.3854 3.07354 21.673 3.63803C22 4.27976 22 5.11984 22 6.8V17.2C22 18.8802 22 19.7202 21.673 20.362C21.3854 20.9265 20.9265 21.3854 20.362 21.673C20.2487 21.7308 20.1292 21.7783 20 21.8174M4.00002 21.8174C4.00035 21.0081 4.00521 20.5799 4.07686 20.2196C4.39249 18.6329 5.63288 17.3925 7.21964 17.0769C7.60603 17 8.07069 17 9 17H15C15.9293 17 16.394 17 16.7804 17.0769C18.3671 17.3925 19.6075 18.6329 19.9231 20.2196C19.9948 20.5799 19.9996 21.0081 20 21.8174M16 9.5C16 11.7091 14.2091 13.5 12 13.5C9.79086 13.5 8 11.7091 8 9.5C8 7.29086 9.79086 5.5 12 5.5C14.2091 5.5 16 7.29086 16 9.5Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
        Contacts
    </a>
</div>

                    
                                <!-- </div> -->
                </div>
                <div class="col-4">
                    <div class="py-1 blue-heading">Mode</div>
                    <div class="py-1">
                        <div class="ps-3"><a href="#" onclick="preventHash(event)"><img class="menuIcons"
                                    src="{{ asset('v2/images/icons/menu-lightmode.svg') }}">
                                Light</a></div>
                    </div>
                </div>
                <div class="col-12">
                    <hr class="menu-hr">
                    <div class="py-1 text-end"><a href="{{ route('head_office.user.profile',['from_company'=> true]) }}"><img class="menuIcons"
                                src="{{ asset('v2/images/icons/menu-userarea.svg') }}"> Go to User Account</a></div>
                    <div class="py-1 text-end sign-out-label"><a href="{{ route('user.logout') }}"><img
                                class="menuIcons" src="{{ asset('v2/images/icons/menu-signout.svg') }}"> Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</header>








    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    // Function to generate a unique color from a string (user ID or email)
    function stringToColor(str) {
        var hash = 0;
        for (var i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        var color = '#';
        for (var j = 0; j < 3; j++) {
            var value = (hash >> (j * 8)) & 0xFF;
            color += ('00' + value.toString(16)).substr(-2);
        }
        return color;
    }

    // Function to set a unique color for the user image
    function setUserBackgroundColor(elementId, userId) {
        var uniqueColor = stringToColor(userId); // Generate unique color from user ID or email
        console.log('Generated Color: ' + uniqueColor);
        $('#' + elementId).css('background-color', uniqueColor);
    }

    // Example usage
    var userId = "{{ $user->id }}"; // Replace this with user's unique ID or email from your backend
    setUserBackgroundColor('user-img-place', userId); // Set the unique color for the user's image

    var userInfoWrapper = $('.user-info-wrapper');
    var isMenuOpen = false;
    $('#user-profile-ico').on('click', function(event) {
        $('.user-info-wrapper').stop(true,true).fadeOut();
    });
</script>





<script>
    /* $('#user-profile-ico').on('mouseenter', function(event) {
        if($('#user-pr-menu').hasClass('hidden')){
        if ($(event.target).is('#user-profile-ico, #user-profile-ico *')) {
            $('.user-info-wrapper').stop(true,true).fadeIn().css({
                'display': 'flex'
            });
        }
    }
    });

    $('#user-profile-ico, #user-profile-ico *').on('mouseleave', function(event) {
        if (!$(event.relatedTarget).closest('#user-profile-ico').length && !$(event.relatedTarget).hasClass(
                'user-info-wrapper')) {
            $('.user-info-wrapper').stop(true,true).fadeOut();
        }
    }); */

    $('.user-info-wrapper').on('mouseenter', function(element) {
        $(this).stop(true,true).fadeIn('fast').css({
            'display': 'flex',
            'transition': '0'
        })


    });

    $('.user-info-wrapper').on('mouseleave', function() {
        $('.user-info-wrapper').stop(true,true).fadeOut();
    });

    $(document).ready(function(){
        $('#overview').tooltip({container: 'body',placement:'bottom'})

    })



    function preventHash(event) {
        event.preventDefault();

    }
    document.documentElement.style.setProperty('--highlight-nav-color', @json($headOffice ? $headOffice->highlight_color : '#014c6b'));
    document.documentElement.style.setProperty('--icon-nav-color', @json($headOffice ? $headOffice->icon_color : '#444'));
    console.log(@json($headOffice->icon_color ? $headOffice->icon_color : '#fff'),'testing');
    document.documentElement.style.setProperty('--location-primary-color', @json($headOffice ? $headOffice->icon_color : '#444'));
    document.documentElement.style.setProperty('--highlight-company-subnav-color', @json($headOffice ? $headOffice->icon_color : '#444'));
    document.documentElement.style.setProperty('--portal-primary-btn-color', @json($headOffice ? $headOffice->portal_primary_btn_color : '#444'));
    document.documentElement.style.setProperty('--portal-primary-btn-text-color', @json($headOffice ? $headOffice->portal_primary_btn_text_color : '#444'));
    document.documentElement.style.setProperty('--portal-section-heading-color', @json($headOffice ? $headOffice->portal_section_heading_color : '#444'));
    console.log(@json($headOffice->icon_color ? $headOffice->icon_color : '#fff'),'testing');
    document.documentElement.style.setProperty('--primary-nav-color', @json($headOffice ? $headOffice->primary_color : '#fff'));
</script>


<script>
    function closeToast() {
        var toastCard = document.getElementById('main-live-toast');
        if (toastCard) {
            toastCard.remove();

            // Update the session to indicate the toast has been dismissed
            fetch("{{route('head_office.dismissToast')}}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });
        }
    }
</script>
