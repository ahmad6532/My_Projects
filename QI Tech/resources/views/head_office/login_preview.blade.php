

<style>
    .customFormSelect {
        display: block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        -webkit-transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        -o-transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;

        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        font-size: .875rem;
    }
    .logo-login-per{
        max-width: 100px;
        max-height: 50px;
        object-fit: contain;
    }
    .background_image{
        max-width: 450px;
        max-height: 450px;
        
    }
    .back-button-login{
        transition: 0.2s ease-in-out;
    }
    .back-button-login:hover{
        opacity: 0.5;
        transition: 0.2s ease-in-out;
    }

    .login_preview{
        transform: scale(0.5);
        position: absolute;
        top: -2%;
        right: -32%;
        width: 100%;
        max-height: 445px;
    }
    .section2{
        transform: scale(0.32);
        position: absolute;
        top: 15%;
        right: -32%;
        width: 100%;
        min-height: 845px;
        padding: 10px;
        border-radius: 2rem;
        box-shadow: 0 0 4.2em 20px rgba(0, 0, 0, .12);
    }
    .login_preview .border-change{
        border: 1px solid #ced4da;
    }
</style>
@if (isset($head_office->login_highlight_color))
<style>
.login_preview .border-change:focus{
        border-color: {{$head_office->login_highlight_color}} !important;
    }
.login_preview .card-btn{
    background: {{$head_office->sign_button_color}} !important;
}
.login_preview .login-heading-blue{
    color: {{$head_office->sign_button_color}} !important;
}
{}

</style>

@endif

<section class="login_preview">
    {{-- <link rel="stylesheet" href="{{asset('css/colors.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('css/login_preview.css')}}">

    <div class="app-content" ng-controller="LoginController">
        <div ng-include="'views/back.html'"></div>

        <div class="login-wrapper container-fluid">
            <div class="container-fluid shadow-box-wrapper " style="overflow: hidden;"
                ng-class="{'person-bg' : login_type === 1} ">
                <div class="d-flex align-items-center justify-content-between w-100 login-nav" style="padding-right: 0.6rem;">
                    <a href="/"><img id="login_preview_logo" src="{{$head_office->logo}}" class="logo-login ml-4"></a>
                    <a ng-style="{'color': themeData.primary_color }" class="back-button-login mr-4" href="#">
                        <img class="back" src="{{asset('images/arrow-narrow-left.svg')}}"> Back
                    </a>
                </div>
                <div class="row " style="height: 100%; ">
                    <!-- <div class="col-lg-6 col-sm-12 left-pane">
                    <img src="images/svg/QI Tech white.svg" class="logo" style="visibility: collapse;">
                    <h2 class="left-heading"></h2>

                    <div class="stages">
                        <div class="stages-inner">
                            <div class="stage" ng-repeat="s in sub_headings[login_type] track by $index">
                                <span class="cirlce completed"></span>
                            </div>
                        </div>
                    </div>

                </div> -->
                    <style>
                        .card-tile-heading2 {
                            color: #D79D20;
                        }
                        .normal-form-input{
                            border-color: #ced4da !important;
                        }
                    </style>

                    <div class="col-lg-6  ">
                        <div class="mx-auto text-center  mt-5">
                            <h3 class="card-tile-description">Sign in to your</h3>
                            <h2 data-btn-color class="login-heading login-heading-blue" id="company-head">Company Account</h2>
                        </div>

                        {{-- <div class="img-wrapper" ng-show="login_type === 1">
                            <img class="img-fluid1" id="cloud1" src="../../images/login-cloud1.png" alt="cloud-img">
                            <!-- <img class="img-fluid2" src="../../images/login-person-img.png" alt="person-img"> -->
                            <img class="img-fluid3" id="cloud2" src="../../images/login-cloud2.png" alt="cloud-img">
                        </div> --}}


                        <div class="img-wrapper" ng-if="themeData" >
                            <img class="background_image" id="person_backImg" src="{{ asset('storage/images/' . $head_office->background_image) }}" alt="person-img">
                        </div>


                    </div>
                    <div class="col-lg-6 col-sm-12 " ng-class="{'mt-5': themeData}">
                        <div class="">
                            <div class="d-flex tabs-bar gap-3 align-items-center">


                                <div class="" ng-show="!themeData">
                                    <a href="javascript:void(0)" ng-click="login_type = 2" class="login-nav-btn"
                                        ng-class="active_tab_class(2)">
                                        <img class="login-tab-icon" src="{{asset('images/svg/login_svgs/company-btn-img.png')}}">
                                        <span class="" ng-style="{'color': themeData.primary_color}">Company</span>
                                    </a>
                                </div>
                                <div class="pl-5" ng-show="!themeData">
                                    <a href="javascript:void(0)" class="login-nav-btn" ng-click="login_type = 1"
                                        ng-class="active_tab_class(1)">
                                        <img class="login-tab-icon" src="{{asset('images/svg/login_svgs/user_btn_img.png')}}">
                                        <span class="">User</span>
                                    </a>
                                </div>
                            </div>


                            <div class="mt-5 ">
                                <h2 class="login-subheading" id="login_preview-sub">{{empty($head_office->title_text) ? 'Please add title' : $head_office->title_text }}</h2>
                            </div>
                            <form id="login-form" action="/postlogin" class="close-form" ng-submit="login($event)"
                                method="post">
                                <input type="hidden" name="_token" id="csrf_token">
                                <input type="hidden" name="type" id="ltype">
                                <div class="form-field">
                                    <label class="form-label">Email</label>
                                    <input data-focused-border-color theme-data="themeData" class="border-change form-control" type="text" name="email" id="email" ng-model="email"
                                        required>
                                </div>
                                <div class="form-field">
                                    <label class="form-label"> Password</label>
                                    <input data-focused-border-color theme-data="themeData" class="border-change form-control" type="password" name="password" id="password"
                                        ng-model="password" required>
                                    <div class="right">
                                        <a href="#" hover-color theme-data="themeData" class="form-label form-label-link">Forgot password?</a>
                                    </div>
                                </div>
                                <div class="form-field">
                                    <input data-btn-color type="button" value="Sign in" class="btn card-btn login-preview-btn">
                                </div>
                                <div class="small-text" ng-if="!themeData ">
                                    Don't have an account? <a href="#" class="small-text"
                                        ng-click="UI.loading = true">Sign up</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</section>

<section class="section2" style="background:#ECF3F6;">
    <header class="custom-header-color">
        <?php
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        
        $customTheme = false;
        if ($headOffice->link_token == Session::get('token') && Session::get('token') != null) {
            $customTheme = true;
            $themeData = $headOffice;
        }
        $unApproved_count_holding_area = App\Models\Headoffices\ReceivedNationalAlert::where('status', 'unapproved')
            ->where('head_office_id', $headOffice->id)
            ->count();
        ?>
        <div class="logo">
            <a href="#"><img
                    src="{{ $headOffice->logo }} "
                    alt="logo-img" id="nd-logo"></a>
        </div>
        <div class="heading-line" id="nd-text">
            
                {{ isset($headOffice->portal_text) ? $headOffice->portal_text : 'Work Management' }}
        </div>
    
        <div class="top-heading-center-tabs">
           
                @if (
                    !in_array(request()->route()->getName(), [
                        'case_manager.request_information',
                        'case_manager.index',
                        'case_manager.view',
                        'case_manager.view_report',
                        'case_manager.view_root_cause_analysis',
                        'case_manager.view_sharing',
                        'case_manager.intelligence.mrege_contact',
                        'head_office.case.requested_informations',
                    ]))
                    <a id="overview" data-toggle="tooltip" data-placement="bottom" title="Coming Soon" href="{{ route('head_office.dashboard') }}" class="@if (request()->route()->getName() == 'head_office.dashboard') active @endif"><i
                            class="fa-solid fa-table-cells" style="margin-right: 0.2rem;"></i>Overview <span></span></a>
                    <a href="{{ route('head_office.board') }}" class="@if (request()->route()->getName() == 'head_office.board') active @endif"><img
                            class="headerIcons" src="{{ asset('v2/images/icons/Board.svg') }}" />Boards<span></span></a>
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
                        ])) active @endif"><img
                            class="headerIcons" src="{{ asset('v2/images/icons/Case-Manager.svg') }}" />Case
                        Manager<span></span></a>
                @endif
                @if (
                    !in_array(request()->route()->getName(), [
                        'case_manager.edit_report',
                        'case_manager.request_information',
                        'case_manager.index',
                        'case_manager.view',
                        'case_manager.view_report',
                        'case_manager.view_root_cause_analysis',
                        'case_manager.view_sharing',
                        'case_manager.intelligence.mrege_contact',
                        'head_office.case.requested_informations',
                    ]))
                    <a href="{{ route('head_office.contact') }}"
                        class="@if (request()->route()->getName() == 'head_office.contact') active @endif"><img class="headerIcons"
                            src="{{ asset('v2/images/icons/Contacts.svg') }}" />Contacts<span></span></a>
                @endif
    
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
            </div> -->
            <div class="user-icon">
                <a id="user-profile-io" href="#" 
                    onclick="preventHash(event)">
                    <div class="user-icon-circle" title="User Profile">
                        @if (isset($user->logo))
                            <img src="{{ $user->logo }}" alt="png_img"
                                style="width: 30px;height:30px;border-radius:50%;">
                        @else
                            <div class="user-img-placeholder" id="user-img-plae" style="width:30px;height:30px;">
                                {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $user->name))) }}
                            </div>
                        @endif
                    </div>
                </a>
    
            </div>
        </div>
    
    </header>
    <script>
    
        $(document).ready(function(){
            $('#overview').tooltip({container: 'body',placement:'bottom'})
    
        })
    
    
    
        function preventHash(event) {
            event.preventDefault();
    
        }
        document.documentElement.style.setProperty('--highlight-nav-color2', @json($headOffice->highlight_color ? $headOffice->highlight_color : '#014c6b'));
        document.documentElement.style.setProperty('--icon-nav-color2', @json($headOffice->icon_color ? $headOffice->icon_color : '#444'));
        document.documentElement.style.setProperty('--primary-nav-color2', @json($headOffice->primary_color ? $headOffice->primary_color : '#fff'));
    </script>
    
    
    
    
    
</section>


