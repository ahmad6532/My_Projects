<style>
.top-heading-center-tabs a {
    position: relative;
    color: black;
    text-decoration: none;
    padding-bottom: 5px;
}

.top-heading-center-tabs a:hover,
.top-heading-center-tabs a.active {
    color: black;
}

.top-heading-center-tabs a::after {
    content: "";
    display: block;
    width: 100%;
    height: 2px;
    position: absolute;
    left: 0; 
    bottom: 0;
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.top-heading-center-tabs a:hover::after,
.top-heading-center-tabs a.active::after {
    transform: scaleX(1);
}

.top-heading-center-tabs a svg path {
    stroke: black;
    transition: stroke 0.3s ease; 
}

.top-heading-center-tabs a:hover svg path,
.top-heading-center-tabs a.active svg path {
    stroke: black;
}

</style>
@php
    $location = Auth::guard('location')->user() ?? $location;
    $forms = $location->group_forms();
    $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
    $userMain = Auth::guard('web')->user() ?? Auth::guard('user')->user();
    $positions = [
        1 => 'Accuracy Checking Technician (ACT)',
        2 => 'Pharmacy Apprentice',
        3 => 'Counter Assistant',
        4 => 'Dispenser',
        5 => 'Driver',
        6 => 'Pharmacist',
        7 => 'Pre-registration Pharmacist',
        8 => 'Pharmacy Technician',
        9 => 'Pharmacy Assistant',
    ];
    $positionName = 'Unknown Position';
    $name = '';
    if (isset($user)) {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $name =
        isset($user) && $user->name
            ? implode(
                '',
                array_map(function ($word) {
                    return strtoupper($word[0]);
                }, explode(' ', $user->name)),
            )
            : '';
        $positionId = $user->position_id ?? null; 
        $positionName = $positions[$positionId] ?? 'Unknown Position';
    }
@endphp
<header class="justify-content-between d-flex" style="background: var(--highlight-nav-color)">
    <div class="d-flex align-items-center">
        <div class="logo">
            <a href="{{ route('location.dashboard') }}"><img
                    src="{{ isset($location->organization_setting_assignment->organization_setting) && $location->organization_setting_assignment->organization_setting->setting_logo() ? $location->organization_setting_assignment->organization_setting->setting_logo() : asset('images/svg/logo_blue.png') }}"></a>
        </div>
        <div class="heading-line">Welcome, {{ $location->trading_name }}</div>
    </div>

    @if (isset($userMain))
        <div class="top-heading-center-tabs "
            style="position:absolute;top:90px;left:50%;transform:translateX(-50%);z-index: 2;">

            <a data-toggle="tooltip" title="Coming soon" {{-- href="{{route('location.dashboard')}}" --}}
                class="@if (request()->route()->getName() == 'location.dashboard') active @endif"><svg width="16" height="16"
                    style='margin-right:0.2rem;' viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M21 21L16.65 16.65M11 6C13.7614 6 16 8.23858 16 11M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"
                        stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Dashboard<span></span></a>
            <a href="{{ route('be_spoke_forms.be_spoke_form.records') }}"
                class="@if (request()->route()->getName() == 'be_spoke_forms.be_spoke_form.records') active @endif"><svg width="18 " height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 7.8C5 6.11984 5 5.27976 5.32698 4.63803C5.6146 4.07354 6.07354 3.6146 6.63803 3.32698C7.27976 3 8.11984 3 9.8 3H14.2C15.8802 3 16.7202 3 17.362 3.32698C17.9265 3.6146 18.3854 4.07354 18.673 4.63803C19 5.27976 19 6.11984 19 7.8V21L12 17L5 21V7.8Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                
                
                Reported<span></span></a>

            {{-- <a href="{{route('location.view_patient_safety_alerts')}}" class="@if (request()->route()->getName() == 'location.view_patient_safety_alerts') active @endif">Alerts<span></span></a> --}}

            {{-- <a href="{{route('location.view_near_miss')}}" class="@if (request()->route()->getName() == 'location.view_near_miss') active @endif">Near Misses<span></span></a> --}}
            <a href="{{ route('location.view_drafts') }}"
                class="@if (request()->route()->getName() == 'location.view_drafts') active @endif d-flex"><svg width="16" height="16"
                    style="margin-right: 0.2rem;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M21 18L19.9999 19.094C19.4695 19.6741 18.7502 20 18.0002 20C17.2501 20 16.5308 19.6741 16.0004 19.094C15.4693 18.5151 14.75 18.1901 14.0002 18.1901C13.2504 18.1901 12.5312 18.5151 12 19.094M3.00003 20H4.67457C5.16376 20 5.40835 20 5.63852 19.9447C5.84259 19.8957 6.03768 19.8149 6.21663 19.7053C6.41846 19.5816 6.59141 19.4086 6.93732 19.0627L19.5001 6.49998C20.3285 5.67156 20.3285 4.32841 19.5001 3.49998C18.6716 2.67156 17.3285 2.67156 16.5001 3.49998L3.93729 16.0627C3.59139 16.4086 3.41843 16.5816 3.29475 16.7834C3.18509 16.9624 3.10428 17.1574 3.05529 17.3615C3.00003 17.5917 3.00003 17.8363 3.00003 18.3255V20Z"
                        stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                My Drafts<div class="mx-1 bg-info badge">{{ count($location->drafts) }}</div><span></span></a>


                {{-- <a href=" "
                class=""><svg width="16" height="16"
                    style="margin-right: 0.2rem;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M21 18L19.9999 19.094C19.4695 19.6741 18.7502 20 18.0002 20C17.2501 20 16.5308 19.6741 16.0004 19.094C15.4693 18.5151 14.75 18.1901 14.0002 18.1901C13.2504 18.1901 12.5312 18.5151 12 19.094M3.00003 20H4.67457C5.16376 20 5.40835 20 5.63852 19.9447C5.84259 19.8957 6.03768 19.8149 6.21663 19.7053C6.41846 19.5816 6.59141 19.4086 6.93732 19.0627L19.5001 6.49998C20.3285 5.67156 20.3285 4.32841 19.5001 3.49998C18.6716 2.67156 17.3285 2.67156 16.5001 3.49998L3.93729 16.0627C3.59139 16.4086 3.41843 16.5816 3.29475 16.7834C3.18509 16.9624 3.10428 17.1574 3.05529 17.3615C3.00003 17.5917 3.00003 17.8363 3.00003 18.3255V20Z"
                        stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Feedback<div class="mx-1 bg-info badge">{{ count($location->drafts) }}</div><span></span></a> --}}
            
        </div>
    @endif

    <div class="right-side-items">
       <!--
        <div class="notification-icon">
            <a href="#" title="Notifications">
                <i class="fa-regular fa-bell"></i>
            </a>
        </div>
        <div class="help-icon">
            <a href="#" title="Help">
                <i class="fa-regular fa-question"></i>
            </a>
        </div> 
    -->

        @if (isset($name) && $name != '')
            <div class="user-icon">
                <a href="#" class="e-drop-down" data-target="#user-pr-menu">
                    <div class="user-icon-circle" title="User Profile">
                        {{ $name }}
                        <span class="online" title="Status: Online"></span>
                    </div>
                </a>

            </div>
        @endif
        <div id="user-pr-menu" class="custom-menu user-profile-menu-tr hidden">
            <div class="row">
                <div class="col-12">
                    {{-- <div class="logo-menu">
                        <a href="/"><img height="25px" src="{{asset('v2/images/well.svg')}}"></a>
                    </div> --}}
                    <div class="user-icon mx-auto" style="width: 50px;height:50px;border-radius:50%;">
                        <a href="#" class="e-drop-down" data-target="#user-pr-menu">
                            <div style="font-size: 18px;" class="user-icon-circle" title="User Profile">
                                {{ $name }}
                            </div>
                        </a>
                    </div>

                </div>
                @if (isset($user))
                    <div class="col-12">
                        <div class="mx-auto" style="width: fit-content; text-align: center; cursor: pointer; position: relative;">
                            <!-- Username -->
                            <div style="font-size: 16px; font-weight: bold; color: #333;">
                                {{ $user->name }}
                            </div>
                            <!-- User Role in Box -->
                            <div style="font-size: 12px; color: #555; background: #f0f0f0; padding: 4px 10px; border-radius: 15px; display: inline-block; margin-top: 5px; border: 1px solid #ddd;">
                                {{ $positionName }}
                            </div>
                            <div class="user-details" style="display: none; position: absolute; top: 60px; left: 50%; transform: translateX(-50%); background: #fff; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); padding: 10px; z-index: 10; font-size: 12px; width: 200px;">
                                {{ $user->registration_no }} <br>
                            </div>
                        </div>
                    </div>
                @endif
                
            </div>
            <div class="col-4">
                <div class="py-1 blue-heading">Mode</div>
                <div class="py-1">
                    <div class="ps-3" data-toggle="tooltip" data-placement='Top' title="Coming Soon"><a
                            href="#"><img class="menuIcons"
                                src="{{ asset('v2/images/icons/menu-lightmode.svg') }}">
                            Light</a></div>
                </div>
            </div>
            <div class="col-12">
                <hr class="menu-hr">
                <div class="py-1 text-end"><a href="{{ route('user.view_profile') }}"><img class="menuIcons"
                            src="{{ asset('v2/images/icons/menu-userarea.svg') }}"> Go to User Account</a></div>
                <div class="py-1 text-end sign-out-label"><a href="{{ route('user.logout') }}"><img class="menuIcons"
                            src="{{ asset('v2/images/icons/menu-signout.svg') }}"> Sign Out</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            setRandomBackgroundColor('user-icon');

            document.documentElement.style.setProperty('--location-section-heading-color',
                @json(
                    $location &&
                    isset($location->organization_setting_assignment) &&
                    isset($location->organization_setting_assignment->organization_setting)
                        ? $location->organization_setting_assignment->organization_setting->location_section_heading_color
                        : '#5ac1b6'));

            document.documentElement.style.setProperty('--location-form-setting-color',
                @json(
                    $location &&
                    isset($location->organization_setting_assignment) &&
                    isset($location->organization_setting_assignment->organization_setting)
                        ? $location->organization_setting_assignment->organization_setting->location_form_setting_color
                        : '#4dd6f0'));

            document.documentElement.style.setProperty('--portal-primary-btn-color', @json(
                $location &&
                isset($location->organization_setting_assignment) &&
                isset($location->organization_setting_assignment->organization_setting)
                    ? $location->organization_setting_assignment->organization_setting->location_button_color
                    : '#5ac1b8'));

            document.documentElement.style.setProperty('--portal-primary-btn-text-color',
                @json(
                    $location &&
                    isset($location->organization_setting_assignment) &&
                    isset($location->organization_setting_assignment->organization_setting)
                        ? $location->organization_setting_assignment->organization_setting->location_button_text_color
                        : '#fff'));

            document.documentElement.style.setProperty('--highlight-nav-color', @json(
                $location &&
                isset($location->organization_setting_assignment) &&
                isset($location->organization_setting_assignment->organization_setting)
                    ? $location->organization_setting_assignment->organization_setting->bg_color_code
                    : '#72c4ba'));
        });

        function setRandomBackgroundColor(elementId) {
            var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);

            // Ensure the color string is 6 characters long
            while (randomColor.length < 7) {
                randomColor += '0';
            }

            // Calculate brightness to decide text color
            var r = parseInt(randomColor.substring(1, 3), 16);
            var g = parseInt(randomColor.substring(3, 5), 16);
            var b = parseInt(randomColor.substring(5, 7), 16);
            var brightness = (r * 299 + g * 587 + b * 114) / 1000;

            var textColor = brightness > 125 ? 'black' : 'white';

            console.log(randomColor);
            $('.' + elementId).css('background-color', randomColor);
            $('.user-icon-circle').css('color', textColor);
        }
        document.querySelectorAll('.mx-auto').forEach((parent) => {
    const details = parent.querySelector('.user-details');

    parent.addEventListener('mouseover', () => {
        details.style.display = 'block';
    });

    parent.addEventListener('mouseout', () => {
        details.style.display = 'none';
    });
});

    </script>
</header>
