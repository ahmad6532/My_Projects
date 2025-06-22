@extends('layouts.head_office_app')
@section('title', 'Head office Settings')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-info">Settings</h1>
        </div>
    @include('layouts.error')
    <!-- Content Row -->
    
        <div class="row">
            <div class="col-md-12">
                <!-- Card Content - Collapse -->
                <div class="card" id="collapseCard">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="head_office_details_tab" data-toggle="tab"
                                        data-target="#head_office_details" type="button" role="tab"
                                        aria-controls="head_office_details" aria-selected="true">Head Office Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password_tab" data-toggle="tab" data-target="#password"
                                        type="button" role="tab" aria-controls="password" aria-selected="false">Password
                                    and Security
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="patient_safety_alert_tab" data-toggle="tab" data-target="#patient_safety_alert_settings"
                                        type="button" role="tab" aria-controls="patient_safety_alert_tab" aria-selected="false">Patient Safety Alerts
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="subscription_tab" data-toggle="tab"
                                        data-target="#subscription" type="button" role="tab"
                                        aria-controls="subscription" aria-selected="false">Subscription & Invoices
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="personalize_my_account_tab" data-toggle="tab"
                                        data-target="#personalize_my_account" type="button" role="tab"
                                        aria-controls="personalize_my_account" aria-selected="false">Personalize My
                                    Account
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="finance_department_detail_tab" data-toggle="tab"
                                        data-target="#finance_department_detail" type="button" role="tab"
                                        aria-controls="finance_department_detail" aria-selected="false">Finance Department Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="root_cause_analysis_tab" data-toggle="tab"
                                        data-target="#root_cause_analysis" type="button" role="tab"
                                        aria-controls="root_cause_analysis" aria-selected="false">Root Cause Analysis
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="head_office_details" role="tabpanel" aria-labelledby="head_office_details-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center text-info h3 font-weight-bold">Update Head Office
                                            Details</h3>
                                        @include('head_office.settings.edit_head_office_details')
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="text-center text-info h3 font-weight-bold">Update
                                                    Password</h3>
                                                @include('head_office.settings.password_security.update_password')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                @include('head_office.settings.password_security.verified_devices')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="patient_safety_alert_settings" role="tabpanel"
                                 aria-labelledby="patient_safety_alert_settings-tab">
                                 <br>
                                <h3 class="text-info h3 font-weight-bold mt-1">Holding Area Settings</h3>
                                <form method="post" action="{{route('head_office.psa.holding_area_on_off')}}">
                                    @csrf
                                    <div class="form-group1">
                                        <label>
                                            <input type="checkbox" name="holding_area_on" class="holding_area_on" value="1"  @if(isset($headOffice) && $headOffice->holding_area_on ) checked @endif >
                                            Hold CAS alerts for review before sending to pharmacies
                                        </label>
                                    </div>
                                    <div class="form-group what_to_do_wrapper" style="display:none">
                                        What to do with alerts in holding area?
                                        <select name="what_to_do" class="w-50 form-control">
                                            <option></option>
                                            <option value="approve_all">Approve All</option>
                                        </select>
                                    </div>
                                     <div class="form-group">
                                        <input type="submit" name="submit" value="Save" class="btn btn-info">
                                     </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="subscription" role="tabpanel"
                                 aria-labelledby="subscription-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center text-info h3 font-weight-bold">Subscription &
                                            Invoices</h3>
                                        @include('head_office.settings.subscription_view')
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="personalize_my_account" role="tabpanel"
                                 aria-labelledby="personalize_my_account-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center text-info h3 font-weight-bold">Personalize My
                                            Account</h3>
                                        @include('head_office.settings.personalize_account.color_branding')
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="finance_department_detail" role="tabpanel"
                                 aria-labelledby="finance_department_detail-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center text-info h3 font-weight-bold">Finance Department Details</h3>
                                        @include('head_office.settings.finance_department_detail')
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="root_cause_analysis" role="tabpanel"
                                 aria-labelledby="root_cause_analysis-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center text-info h3 font-weight-bold">Root Cause Analysis</h3>
                                        @include('head_office.settings.root_cause_analysis')
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
@section('scripts')
    <script>
    $(document).ready(function (){
        loadActiveTab();
    });
    function loadActiveTab(tab = null){
        if(tab == null){
            tab = window.location.hash;
        } 
        console.log(tab);
        $('.nav-tabs button[data-target="' + tab + '"]').tab('show');
    }
    $(document).ready(function(){
        if(!$("#is_fish_bone").is(':checked'))
        {
            $("#is_fish_bone_compulsory").prop('checked',false);
            $("#is_fish_bone_compulsory").prop('disabled', 'disabled');
            $('.default_questinos').hide();
        }
        else
        {
            $("#is_fish_bone_compulsory").removeAttr("disabled");
            $('.default_questinos').show();
        }
        if(!$("#is_five_whys").is(':checked'))
        {
            $("#is_five_whys_compulsory").prop('checked',false);
            $("#is_five_whys_compulsory").prop('disabled', 'disabled');
        }
        else
        {
            $("#is_five_whys_compulsory").removeAttr("disabled");
        }
    })

    $(document).on('change','#is_fish_bone',function(){
        console.log($("#is_fish_bone").is(':checked'));
        if(!$("#is_fish_bone").is(':checked'))
        {
            $("#is_fish_bone_compulsory").prop('checked',false);
            $("#is_fish_bone_compulsory").prop('disabled', 'disabled');
            $('.default_questinos').hide();
        }
        else
        {
            $("#is_fish_bone_compulsory").removeAttr("disabled");
            $('.default_questinos').show();
        }
    })

    $(document).on('change','#is_five_whys',function(){
        console.log($("#is_five_whys").is(':checked'));
        if(!$("#is_five_whys").is(':checked'))
        {
            $("#is_five_whys_compulsory").prop('checked',false);
            $("#is_five_whys_compulsory").prop('disabled', 'disabled');
        }
        else
        {
            $("#is_five_whys_compulsory").removeAttr("disabled");
        }
    })
</script>
@endsection