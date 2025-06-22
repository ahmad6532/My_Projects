@extends('layouts.head_office_app')
@section('title', 'Head Office Dashboard')
@section('sidebar')
    @include('layouts.company.sidebar')
@endsection
@section('content')
<style>
    #dataTable_filter:after, .dt-search:after{
        left: 10px !important;
    }
    div.dt-container .dt-layout-table {
            display: block !important;
        }
        #dataTable_all{
            margin: inherit !important;
            
    }

    #content{
        overflow-x: unset !important;
    }
</style>

    <div id="content">
        @if (request()->query('success'))
            <div class="alert to_hide_10 alert-success w-50" style="margin:0 auto">
                {{ request()->query('success') }}
                <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
            </div>
        @endif
        @if (request()->query('error'))
            <div class="alert to_hide_10 alert-danger w-50" style="margin:0 auto">
                {{ request()->query('error') }}
                <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
            </div>
        @endif
        <div class="content-page-heading">
            My Forms
            @if ($profile->is_manage_forms == true)
                <div class="btn-group btn-group-sm float-right" role="group">
                    <a href="{{ route('head_office.be_spoke_forms_templates.form_template') }}" class="btn btn-info"
                        title="Create New Be Spoke Form">
                        <span class="fas fa-plus" aria-hidden="true"></span>
                    </a>
                </div>
            @endif
        </div>
        @if (!$beSpokeForms)
            <h4 class="text-info text-center">No Forms Available.</h4>
        @else
            <nav class='page-menu bordered'>
                <ul class="nav nav-tab main_header">

                    <li>
                        <a data-bs-toggle="tab" id="AllFormBespoke" onclick="changeTabUrl('AllFormBespoke')"
                            data-bs-target="#all_forms" class="info active" href="javascript:void(0)" {{--
                    href="{{route('head_office.company_info')}}" --}}>
                            Current
                            <span></span>
                        </a>
                    </li>

                    @if ($profile->is_manage_forms == true)
                        <li>
                            <a data-bs-toggle="tab" id="ArchivedBespoke" onclick="changeTabUrl('ArchivedBespoke')"
                                data-bs-target="#archived" class="archived" href="javascript:void(0)" {{-- href="{{route('head_office.my_organisation')}}" --}}>
                                Archived
                                <span></span>
                            </a>
                        </li>
                        <li id="os">
                            <a data-bs-toggle="tab" id="DeletedBespoke" onclick="changeTabUrl('DeletedBespoke')"
                                data-bs-target="#deleted" href="javascript:void(0)" {{--
                        href="{{route('head_office.my_organisation')}}" --}}>
                                Deleted
                                <span></span>
                            </a>
                        </li>
                        
                    @endif
                </ul>
            </nav>
            <hr class="hrBeneathMenu">
            <div class="tab-content" id="myTabContent">
                <div id="all_forms" class="all_forms relative tab-pane active show">
                        <table class="table new-table" id="dataTable_all" width="85%">
                            <thead>
                                <tr>
                                    <th style="text-align: left;">
                                        <input class="form-check-input "  type="checkbox" name="never_expire_check" id="dataTable-select-all">
                                    </th>
                                    <th style="text-align: left;">Name</th>
                                    <th style="text-align: left;">Category</th>
                                    <!-- <th style="text-align: left;">Type</th> -->
                                    <th style="text-align: left;">Status</th>
                                    <th style="text-align: left;">Info</th>
                                    <th style="text-align: left;">Purpose</th>
                                    <th style="text-align: left;"></th>
                                    <th style="text-align: left;"></th>
                                    <th style="text-align: left;">Hidden</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr id="alwaysFirstRow">
                                    <td style="align-items: left;">
                                        {{-- <input class="form-check-input" type="checkbox" name="never_expire_check"
                                        id="never_expire_check"
                                        > --}}
                                    </td>
                                    <td  style="text-align: left;">{{$near_miss->name}}</td>
                                    <td>
                                        @livewire('category-manager', ['form_id' => $near_miss->id, 'near_miss_table' => true])
                                    </td>
                                    <!-- <td><input type="button" class="btn-page-content page-content-btn"value="Internal"></td> -->
                                    <td  style="text-align: left;">
                                        @if ($near_miss->isActive)
                                        <p class="text-success d-flex align-items-center gap-1 m-0">{{count($head_office->near_miss_settings->where('is_active',true))}} Live</p>
                                        <p class="text-danger d-flex align-items-center gap-1 m-0">{{count($head_office->near_miss_settings->where('is_active',false))}} Inactive</p>
                                        @else
                                            <!-- <input type="button" style="width: fit-content;" class="btn-page-content page-content-btn-danger"
                                                value="Inactive"> -->
                                                <p class="text-danger d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:5px;"></i> Inactive</p>
                                        @endif
                                    </td>
                                    <td></td>
                                      <td style="text-align: left;">
                                          <div style="text-align: left;">
                                              <span style="font-size: 14px; color: #333; display: inline !important; visibility: visible !important;">
                                                  @if (isset($near_miss) && !empty($near_miss->purpose))
                                                      {{ $near_miss->purpose }}
                                                  @else
                                                      No Purpose Provided
                                                  @endif
                                              </span>
                                          </div>
                                      </td>  
                                    
                                    <td>   
                                        
                                    </td>
                                    <td style="text-align: left;">
                                        <div class="actions-wrap">
                                            <a class="Edit"
                                                href="{{ route('head_office.near_miss_manager', $near_miss->id) }}">
                                                <img src="{{ asset('v2/images/icons/edit-03.svg') }}" alt="">
                                            </a>
                                        @if (!$near_miss->is_deleted)
                                                <a class="delete_form"
                                                    data-msg="to @if ($near_miss->isActive) deactivate @else activate @endif this form?"
                                                    href="{{ route('head_office.be_spoke_forms.near_miss.active', ['id'=>$near_miss->id,'_token'=>csrf_token()]) }}">
                                                    @if ($near_miss->isActive)
                                                        <img title="Deactivate"
                                                            src="{{ asset('v2/images/icons/arrow-circle-broken-up-left.svg') }}"
                                                            alt="">
                                                    @else
                                                        <img title="Activate"
                                                            src="{{ asset('v2/images/icons/arrow-circle-broken-up-right.svg') }}"
                                                            alt="">
                                                    @endif
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                @foreach ($beSpokeForms as $beSpokeForm)
                                @if ($beSpokeForm->is_deleted || $beSpokeForm->is_archived || $beSpokeForm->soft_deleted)
                                @else
                                    <tr>
                                        <td data-form-id="{{$beSpokeForm->id}}">
                                            <input class="form-check-input" type="checkbox" name="never_expire_check"
                                            id="never_expire_check"
                                            data-form-id="{{$beSpokeForm->id}}"
                                            >
                                        </td>
                                        <td>{{ $beSpokeForm->name }}</td>
                                        <td>@isset($beSpokeForm)
                                            @livewire('category-manager', ['form_id' => $beSpokeForm->id])
                                        @else
                                            @livewire('category-manager', ['form_id' => null])
                                        @endisset</td>
                                        
                                        <td>
                                            @if ($beSpokeForm->is_active && !$beSpokeForm->is_deleted)
                                                <!-- <input type="button" class="btn-page-content page-content-btn" value="Active"> -->
                                                <p class="text-success d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:2px;"></i>Live</p>
    
                                            @elseif($beSpokeForm->is_deleted)
                                                <input type="button" class="btn-page-content page-content-btn-danger"
                                                    value="Deleted">
                                            @else
                                                <!-- <input type="button" style="width: fit-content;" class="btn-page-content page-content-btn-danger"
                                                    value="Inactive"> -->
                                                <p class="text-danger d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:5px;"></i> Inactive</p>
    
                                            @endif
                                        </td>
                                        <td style="text-align: left; vertical-align: top; padding: 10px;">
                                            
                                                @if ($beSpokeForm->is_external_link)
                                                    <div class="external-wrapper" style="text-align: left;">
                                                        <input style="font-size: 16px;" data-column='external-{{ $beSpokeForm->id }}' type="text" readonly
                                                            value="{{ route('be_spoke_forms.be_spoke_form.external_link', $beSpokeForm->external_link) }}">
                                                        <button style="color: #D5D5D5;" title="click to copy text" 
                                                            onclick="copyFunction('external-{{ $beSpokeForm->id }}')">
                                                            <i class="fa-regular fa-copy"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            
                                                @if ($beSpokeForm->expiry_state != 'never_expire')
                                                    <div style="text-align: left;">Expires in {{ \Carbon\Carbon::parse($beSpokeForm->expiry_time)->diffForHumans() }}</div>
                                                @endif
                                            
                                                @if (!$beSpokeForm->is_external_link)
                                                    <div class="my-2" style="text-align: left; font-size: 16px;"> 
                                                        Internal Form: locations {{ count($beSpokeForm->group_assigned_locations()) }}
                                                    </div>
                                            
                                                    @if (count($beSpokeForm->groups()))
                                                        <div @if (count($beSpokeForm->groups()) > 1) style="height: 50px; overflow-y: auto;" @endif 
                                                            class="custom-scroll d-flex flex-column justify-content-start align-items-start">
                                            
                                                            @foreach ($beSpokeForm->groups() as $group)
                                                                @php
                                                                    $origin = [];
                                                                    $currentGroup = $group;
                                            
                                                                    while ($currentGroup && $currentGroup->parent) {
                                                                        $origin[] = $currentGroup->parent->group;
                                                                        $currentGroup = $currentGroup->parent;
                                                                    }
                                            
                                                                    $origin = array_reverse($origin);
                                                                    $origin[] = $group->group;
                                                                    $originMessage = count($origin) > 1 ?  implode(' → ', $origin) : $group->group;
                                                                @endphp
                                            
                                                                <p class="btn group-btn" style="text-align: left; margin-inline: unset;" 
                                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $originMessage }}">
                                                                    {{ $group->group }}
                                                                    <a title="Remove Group"
                                                                        href="{{ route('head_office.removeLocations', ['id' => $group->id, 'form_id' => $beSpokeForm->id, '_token' => csrf_token()]) }}"
                                                                        data-msg="Are you sure you want to remove this assignment?"
                                                                        class="text-danger delete_button float-right">
                                                                        <i class="fa fa-xmark"></i>
                                                                    </a>
                                                                </p>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                            
                                                    <p style="margin-top: 10px; text-align: left;">
                                                        <button data-bs-toggle="modal" data-bs-target='#group_assing_modal'
                                                                class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </p>
                                                @endif
                                            
                                                <div class="d-none">
                                                    <div class="inside-content">
                                                        <div class="card-body" style="text-align: left;">
                                                            <form method="post" action="{{ route('head_office.assign_locations', $beSpokeForm->id) }}">
                                                                @csrf
                                                                <p>Please select a group/tier</p>
                                                                @include('head_office.my_organisation.tree-list', ['groups' => $allGroups])
                                                                <input type="hidden" name="form_id" value="{{ $beSpokeForm->id }}">
                                                                <input type="submit" value="Save" class="btn btn-info w-100">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            

                                        <td style="text-align: left;">
    <div class="form-name-input-edit resizing-input" style="text-align: left;">
        <input onfocusout="updatePurpose(this)" data-form_id="{{$beSpokeForm->id}}" style="border-radius: 4px; font-size: 14px; width: 100%;" type="text" value="{{$beSpokeForm->purpose}}" placeholder="Enter purpose here">
        <span style="display: none;"></span>
    </div>
</td>

                                        <td>
                                           @if(isset($beSpokeForm->created_by->user))
                                           <div class="d-flex align-items-center gap-1 flex-wrap" style="font-size: 14px;">
                                            <a href="#" onclick="preventHash(event)">
                                                <div class="user-icon-circle" title="User Profile">
                                                    @if (isset($beSpokeForm->created_by->user->logo))
                                                        <img src="{{ $beSpokeForm->created_by->user->logo }}" alt="png_img"
                                                            style="width: 30px; height: 30px; border-radius: 50%;">
                                                    @else
                                                        <div class="user-img-placeholder" id="user-img-place" style="width: 30px; height: 30px;">
                                                            {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $beSpokeForm->created_by->user->name))) }}
                                                        </div>
                                                    @endif
                                                </div>  
                                            </a>
                                            <div class="my-2" style="text-align: left; font-size: 16px; ">
                                                Created by
                                            </div>
                                            <span class="d-inline-block;" style="text-align: left; font-size: 16px;">{{$beSpokeForm->created_by->user->name}}</span>
                                            <span class="d-inline-block" style="text-align: left; font-size: 16px;">on {{$beSpokeForm->created_at->format('d/m/Y')}}</span>
                                            <span class="d-inline-block" style="text-align: left; font-size: 16px;">at {{$beSpokeForm->created_at->format('h:i A')}}</span>
                                        </div>
                                           
                                               @if (isset($beSpokeForm->modified_by))
                                               <div class="d-flex align-items-center gap-1 flex-wrap" style="font-size: 14px;">
                                                <a href="#" onclick="preventHash(event)">
                                                    <div class="user-icon-circle" title="User Profile">
                                                        @if (isset($beSpokeForm->modified_by->user->logo))
                                                            <img src="{{ $beSpokeForm->modified_by->user->logo }}" alt="png_img"
                                                                style="width: 30px;height:30px;border-radius:50%;">
                                                        @else
                                                            <div class="user-img-placeholder" id="user-img-place" style="width:30px;height:30px;">
                                                                {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $beSpokeForm->modified_by->user->name))) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </a>
                                                <div class="d-flex align-items-center flex-nowrap gap-1" style="text-align: left; font-size: 16px;">
                                                    Last modified by 
                                                    {{-- <span class="fw-semibold" style="white-space: nowrap;">{{$beSpokeForm->modified_by->user->name}}</span> --}}
                                                </div>
                                                <span class="d-inline-block" style="text-align: left; font-size: 16px;">{{$beSpokeForm->modified_by->user->name}}</span>

                                                <span class="d-inline-block" style="text-align: left; font-size: 16px;">on {{$beSpokeForm->updated_at->format('d/m/Y')}}</span>
                                                <span class="d-inline-block" style="text-align: left; font-size: 16px;">at {{$beSpokeForm->updated_at->format('h:i A')}}</span>
                                            </div>
                                              
                                               @endif
                                           </div>
                                           @endif
                                        </td>
                                        <td>
                                            @if ($profile->is_manage_forms)
                                            
                                                <div class="actions-wrap">
                                                    <a class="Edit"
                                                    href="{{ route('head_office.be_spoke_forms_templates.form_template', $beSpokeForm->id) }}">
                                                    <img src="{{ asset('v2/images/icons/edit-03.svg') }}" alt="">
                                                    </a>

                                                    <a title="Duplicate" class="Edit"
                                                    href="{{ route('head_office.be_spoke_forms_templates.form_template_duplicate', $beSpokeForm->id) }}">
                                                    <svg style="opacity: 0.5;" width="19" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.5 2.0028C9.82495 2.01194 9.4197 2.05103 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8.05103 3.4197 8.01194 3.82495 8.0028 4.5M19.5 2.0028C20.1751 2.01194 20.5803 2.05103 20.908 2.21799C21.2843 2.40973 21.5903 2.71569 21.782 3.09202C21.949 3.4197 21.9881 3.82494 21.9972 4.49999M21.9972 13.5C21.9881 14.175 21.949 14.5803 21.782 14.908C21.5903 15.2843 21.2843 15.5903 20.908 15.782C20.5803 15.949 20.1751 15.9881 19.5 15.9972M22 7.99999V9.99999M14.0001 2H16M5.2 22H12.8C13.9201 22 14.4802 22 14.908 21.782C15.2843 21.5903 15.5903 21.2843 15.782 20.908C16 20.4802 16 19.9201 16 18.8V11.2C16 10.0799 16 9.51984 15.782 9.09202C15.5903 8.71569 15.2843 8.40973 14.908 8.21799C14.4802 8 13.9201 8 12.8 8H5.2C4.0799 8 3.51984 8 3.09202 8.21799C2.71569 8.40973 2.40973 8.71569 2.21799 9.09202C2 9.51984 2 10.0799 2 11.2V18.8C2 19.9201 2 20.4802 2.21799 20.908C2.40973 21.2843 2.71569 21.5903 3.09202 21.782C3.51984 22 4.07989 22 5.2 22Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        
                                                    </a>
                                                
                                                @if ($beSpokeForm->submitable_to_nhs_lfpse == false)
                                                @if (!$beSpokeForm->is_deleted)
                                                @if (count($beSpokeForm->records) === 0)
                                                <a class="text-info delete_form" title="Delete"
                                                    data-msg="to delete this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.delete', ['id' => $beSpokeForm->id, '_token' => csrf_token()]) }}">
                                                    <img src="{{ asset('v2/images/icons/trash.svg') }}" alt="">
                                                </a>
                                                @else
                                                <a class="text-info" title="Form has cases associated" href="javascript:void(0);" onclick="showAlert()">
                                                    <img src="{{ asset('v2/images/icons/trash.svg') }}" alt="">
                                                </a>
                                                    @endif
                                                @else
                                                <a class="text-info delete_form" title="Restore"
                                                    data-msg="to restore this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.delete', ['id'=>$beSpokeForm->id,'_token'=> csrf_token()]) }}">
                                                    <img src="{{ asset('v2/images/icons/flip-backward.svg') }}" alt="">
                                                </a>
                                                @endif
                                            @endif

                                            <script>
                                                function showAlert() {
                                                    alert("This form has cases associated with it. You cannot delete this form.");
                                                }
                                            </script>
                                            
                                                {{-- <a title="Records" class="text-info"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.records', $beSpokeForm->id) }}"><img
                                                        src="{{ asset('v2/images/icons/file-06.svg') }}" alt=""></a> --}}
                                                @if ($beSpokeForm->is_external_link)
                                                    <a class="text-info" target="_blank" title="External Link"
                                                        href="{{route('be_spoke_forms.be_spoke_form.external_link',$beSpokeForm->external_link)}}">
                                                        <img src="{{ asset('v2/images/icons/chevron-right-double.svg') }}"
                                                            alt="">
                                                    </a>
                                                @endif
                                                @if (!$beSpokeForm->is_deleted)
                                                    <a class="delete_form"
                                                        data-msg="to @if ($beSpokeForm->is_active) Deactivate @else Activate @endif this form?"
                                                        href="{{ route('head_office.be_spoke_forms.be_spoke_form.active', ['id' => $beSpokeForm->id,'_token' => csrf_token()]) }}">
                                                        @if ($beSpokeForm->is_active)
                                                            <img title="Deactivate"
                                                                src="{{ asset('v2/images/icons/arrow-circle-broken-up-left.svg') }}"
                                                                alt="">
                                                        @else
                                                            <img title="Activate"
                                                                src="{{ asset('v2/images/icons/arrow-circle-broken-up-right.svg') }}"
                                                                alt="">
                                                        @endif  
                                                    </a>
                                                @endif
        
                                                @if (!$beSpokeForm->is_deleted)
                                                    <a class="text-info delete_form"
                                                        data-msg="to @if (!$beSpokeForm->is_archived) Archive @else Unarchive @endif this form?"
                                                        href="{{ route('head_office.be_spoke_forms.be_spoke_form.archived', ['id'=>$beSpokeForm->id,'_token'=>csrf_token()]) }}">
                                                        @if (!$beSpokeForm->is_archived)
                                                            <img title="archive" src="{{asset('images/folder-lock.svg')}}" alt="" style="opacity: 0.5;">
                                                        @else
                                                        <img title="Unarchive" src="{{asset('images/folder.svg')}}" alt="" style="opacity: 0.5;">
                                                        @endif
                                                    </a>
                                                @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                </div>
                @if ($profile->is_manage_forms == true)
                
                    <div class="tab-pane archived" id="archived">

                        @if (!count($beSpokeForms->where('is_archived', 1)))
                                    
                        <p style="text-align: center; padding-top:40px; font-size: 20px;">No Archived forms available</p>                                        
                        @else
                        <table class="table new-table" id="dataTable_archive" width="85%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    {{-- <th>Type</th> --}}
                                    <th>Status</th>
                                    <th>Assigned </th>
                                    <th>purpose</th>
                                    <th>Info</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                              
                                    @foreach ($beSpokeForms->where('is_archived', 1) as $beSpokeForm)
                                    <tr>
                                        {{-- <td>
                                            <input class="form-check-input" type="checkbox" name="never_expire_check"
                                            id="never_expire_check"
                                            >
                                        </td> --}}
                                        <td>{{ $beSpokeForm->name }}</td>
                                        <td>{{ $beSpokeForm->category->name }}</td>
                                        <!-- <td>
                                            @if ($beSpokeForm->is_external_link)
                                                <div class="external-wrapper">
                                                    <input data-column='external-{{ $beSpokeForm->id }}' type="text" readonly
                                                        value="{{ request()->isSecure() ? 'https://' : 'http://'}}{{$head_office->company_name.'.qi-tech.co.uk/external/'.$beSpokeForm->external_link}}">
                                                    <button title="click to copy text"
                                                        onclick="copyFunction('external-{{ $beSpokeForm->id }}')"><i
                                                            class="fa-regular fa-copy"></i></button>
                                                </div>
                                            @else
                                                <input type="button" class="btn-page-content page-content-btn"value="Internal">
                                            @endif
                                        </td> -->
                                        <td>
                                            @if ($beSpokeForm->is_active && !$beSpokeForm->is_deleted)
                                                <!-- <input type="button" class="btn-page-content page-content-btn" value="Active"> -->
                                                <p class="text-success d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:2px;"></i>Live</p>

                                            @elseif($beSpokeForm->is_deleted)
                                                <input type="button" class="btn-page-content page-content-btn-danger"
                                                    value="Deleted">
                                            @else
                                                <!-- <input type="button" style="width: fit-content;" class="btn-page-content page-content-btn-danger"
                                                    value="Inactive"> -->
                                                <p class="text-danger d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:5px;"></i> Inactive</p>

                                            @endif
                                        </td>
                                        <td>
                                            @if (count($beSpokeForm->groups()))
                                                <div @if(count($beSpokeForm->groups()) > 1)style="height: 50px;overflow-y: auto;" @endif class="custom-scroll d-flex flex-column ">
                                                    @foreach ($beSpokeForm->groups() as $key => $assignment)
                                                        <p class="btn group-btn">{{ $assignment->group }} <a title="Remove Group"
                                                                href="{{ route('head_office.removeLocations', ['id'=>$key,'form_id'=>$beSpokeForm->id,'_token'=>csrf_token()]) }}"
                                                                data-msg="Are you sure you want to remove this assignment?"
                                                                class="text-danger delete_button float-right"><i class="fa fa-xmark"></i></a></p>
                                                    @endforeach
                                                </div>
                                            @endif
                                                <p style="margin-top: 10px;"><button data-bs-toggle="modal" data-bs-target='#group_assing_modal'
                                                        class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center mx-auto" ><i class="fa fa-plus"></i></button></p>
                                                <div class="d-none">
                                                    <div class=" inside-content">
                                                        <div class="card-body">
                                                            <form method="post" action="{{route('head_office.assign_locations',$beSpokeForm->id)}}">
                                                                @csrf
                                                                <p>Please select a group/tier</p>
                                                                @include('head_office.my_organisation.tree-list',['groups' => $allGroups])
                                                                <input type="hidden" name="form_id" value="{{$beSpokeForm->id}}">
                                                                <input type="submit" value='Save' class="btn btn-info w-100">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            {{-- <button class="btn btn-outline-secondary custom-outline-btn" onclick="showLocations(this)" data-id='{{$beSpokeForm->id}}'>
                                                {{ count($beSpokeForm->assignedBespokeFroms) }}
                                            </button>
                                            @if (count($beSpokeForm->assignedBespokeFroms) > 0)
                                            <div hidden>
                                                <div class="table-wrapper" >
                                                    <table  class="dataTable-case table table-responsive table-bordered mx-auto rounded" style="width:80%">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th class="text-center">Locations</th>
                                                                <th>Trading Name</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="all_locations text-center">
                                                            @foreach ($beSpokeForm->assignedBespokeFroms as $assignForm)
                                                                <tr>
                                                                    <td class="fw-semibolf">{{ $assignForm->locations()->username }}</td>
                                                                    <td>{{ $assignForm->locations()->trading_name }}</td>
                                                                    <td>
                                                                        <div class="d-flex gap-1 justify-content-center align-items-center mx-auto"
                                                                            style="width: fit-content;">
                                                                            <a href="{{route('head_office.removeLocations',$assignForm->id)}}" type="button" class="btn p-0 px-2" title="Remove Location">
                                                                                <i class="fa-solid fa-trash text-danger"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @endif --}}
                                        </td>
                                        <td>
                                            <!-- <input style="border-radius: 4px;border:1px solid black;" type="text" value="{{$beSpokeForm->purpose}}" readonly> -->
                                            <p>{{$beSpokeForm->purpose}}</p>
                                        </td>
                                        <td>
                                        @if (isset($beSpokeForm->created_by->user))
                                            
                                        
                                            <div class="d-flex flex-column gap-2 ">
                                                <div class="d-flex align-items-center gap-1" style="font-size: 14px;white-space: nowrap">
                                                    <a href="#" 
                                                        onclick="preventHash(event)">
                                                        <div class="user-icon-circle" title="User Profile">
                                                            @if (isset($beSpokeForm->created_by->user->logo))
                                                                <img src="{{ $beSpokeForm->created_by->user->logo }}" alt="png_img"
                                                                    style="width: 30px;height:30px;border-radius:50%;">
                                                            @else
                                                                <div class="user-img-placeholder" id="user-img-place" style="width:30px;height:30px;">
                                                                    {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $beSpokeForm->created_by->user->name))) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                    Created by <p>{{$beSpokeForm->created_by->user->name}} </p>on <p>{{$beSpokeForm->created_at->format('d/m/Y')}}</p> at <p>{{$beSpokeForm->created_at->format('h:i A')}}</p>
                                                </div>   
                                                @if (isset($beSpokeForm->modified_by))
                                                <div class="d-flex align-items-center gap-1" style="font-size: 14px">
                                                    <a href="#" 
                                                        onclick="preventHash(event)">
                                                        <div class="user-icon-circle" title="User Profile">
                                                            @if (isset($beSpokeForm->modified_by->user->logo))
                                                                <img src="{{ $beSpokeForm->modified_by->user->logo }}" alt="png_img"
                                                                    style="width: 30px;height:30px;border-radius:50%;">
                                                            @else
                                                                <div class="user-img-placeholder" id="user-img-place" style="width:30px;height:30px;">
                                                                    {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $beSpokeForm->modified_by->user->name))) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                    Last modified by <p>{{$beSpokeForm->modified_by->user->name}} </p>
                                                    on <p>{{$beSpokeForm->updated_at->format('d/m/Y')}}</p> at <p>{{$beSpokeForm->updated_at->format('h:i A')}}</p>
                                                </div>   
                                                @endif
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="actions-wrap">
                                                <a class="Edit"
                                                href="{{ route('head_office.be_spoke_forms_templates.form_template', $beSpokeForm->id) }}">
                                                <img src="{{ asset('v2/images/icons/edit-03.svg') }}" alt="">
                                            </a>
                                            {{-- <a title="Preview" target="_blank" class="text-info"
                                                href="{{ route('head_office.be_spoke_forms_templates.form_view', $beSpokeForm->id) }}">
                                                <img src="{{ asset('v2/images/icons/eye.svg') }}" alt="">
                                            </a> --}}
                                            @if (!count($beSpokeForm->records) && !$beSpokeForm->is_deleted)
                                                <a class="text-info delete_form" title="Delete"
                                                    data-msg="to delete this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.delete', ['id'=>$beSpokeForm->id,'_token'=>csrf_token()]) }}">
                                                    <img src="{{ asset('v2/images/icons/trash.svg') }}" alt="">
                                                </a>
                                            @else
                                                <a class="text-info delete_form" title="Restore"
                                                    data-msg="to restore this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.delete', ['id'=>$beSpokeForm->id,'_token'=>csrf_token()]) }}">
                                                    <img src="{{ asset('v2/images/icons/flip-backward.svg') }}" alt="">
                                                </a>
                                            @endif
                                            {{-- <a title="Records" class="text-info"
                                                href="{{ route('head_office.be_spoke_forms.be_spoke_form.records', $beSpokeForm->id) }}"><img
                                                    src="{{ asset('v2/images/icons/file-06.svg') }}" alt=""></a> --}}
                                            @if ($beSpokeForm->is_external_link)
                                                <a class="text-info" target="_blank" title="External Link"
                                                    href="{{route('be_spoke_forms.be_spoke_form.external_link',$beSpokeForm->external_link)}}">
                                                    <img src="{{ asset('v2/images/icons/chevron-right-double.svg') }}"
                                                        alt="">
                                                </a>
                                            @endif
                                            @if (!$beSpokeForm->is_deleted)
                                                <a class="delete_form"
                                                    data-msg="to @if ($beSpokeForm->is_active) Deactivate @else Activate @endif this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.active', ['id' => $beSpokeForm->id,'_token' => csrf_token()]) }}">
                                                    @if ($beSpokeForm->is_active)
                                                        <img title="Deactivate"
                                                            src="{{ asset('v2/images/icons/arrow-circle-broken-up-left.svg') }}"
                                                            alt="">
                                                    @else
                                                        <img title="Activate"
                                                            src="{{ asset('v2/images/icons/arrow-circle-broken-up-right.svg') }}"
                                                            alt="">
                                                    @endif
                                                </a>
                                            @endif

                                            @if (!$beSpokeForm->is_deleted)
                                                <a class="text-info delete_form"
                                                    data-msg="to @if (!$beSpokeForm->is_archived) Archive @else Unarchive @endif this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.archived', ['id'=>$beSpokeForm->id,'_token'=>csrf_token()]) }}">
                                                    @if (!$beSpokeForm->is_archived)
                                                        <img title="archive" src="{{asset('images/folder-lock.svg')}}" alt="" style="opacity: 0.5;">
                                                    @else
                                                    <img title="Unarchive" src="{{asset('images/folder.svg')}}" alt="" style="opacity: 0.5;">
                                                    @endif
                                                </a>
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane deleted" id="deleted">
                        @if (count($beSpokeForms->where('is_deleted', 1)) > 0)
                        <table class="table new-table" id="dataTable_del" width="85%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    {{-- <th>Type</th> --}}
                                    <th>Status</th>

                                    <th>Assigned</th>
                                    <th>Purpose</th>
                                    <th>Info</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    @foreach ($beSpokeForms->where('is_deleted', 1) as $beSpokeForm)
                                    <tr>
                                        
                                        <td>{{ $beSpokeForm->name }}</td>
                                        <td>{{ $beSpokeForm->category->name }}</td>
                                        <!-- <td>
                                            @if ($beSpokeForm->is_external_link)
                                                <div class="external-wrapper">
                                                    <input data-column='external-{{ $beSpokeForm->id }}' type="text" readonly
                                                        value="{{ request()->isSecure() ? 'https://' : 'http://'}}{{$head_office->company_name.'.qi-tech.co.uk/external/'.$beSpokeForm->external_link}}">
                                                    <button title="click to copy text"
                                                        onclick="copyFunction('external-{{ $beSpokeForm->id }}')"><i
                                                            class="fa-regular fa-copy"></i></button>
                                                </div>
                                            @else
                                                <input type="button" class="btn-page-content page-content-btn"value="Internal">
                                            @endif
                                        </td> -->
                                        <td>
                                            @if ($beSpokeForm->is_active && !$beSpokeForm->is_deleted)
                                                <!-- <input type="button" class="btn-page-content page-content-btn" value="Active"> -->
                                                <p class="text-success d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:2px;"></i>Live</p>

                                            @elseif($beSpokeForm->is_deleted)
                                            <p class="text-danger d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:2px;"></i>Deleted</p>
                                            @else
                                                <!-- <input type="button" style="width: fit-content;" class="btn-page-content page-content-btn-danger"
                                                    value="Inactive"> -->
                                                <p class="text-danger d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:5px;"></i> Inactive</p>

                                            @endif
                                        </td>
                                        <td>
                                            @if (count($beSpokeForm->groups()))
                                                <div @if(count($beSpokeForm->groups()) > 1)style="height: 50px;overflow-y: auto;" @endif class="custom-scroll d-flex flex-column ">
                                                    @foreach ($beSpokeForm->groups() as $key => $assignment)
                                                        <p class="btn group-btn">{{ $assignment->group }} <a title="Remove Group"
                                                                href="{{ route('head_office.removeLocations', ['id'=>$key,'form_id'=>$beSpokeForm->id,'_token'=>csrf_token()]) }}"
                                                                data-msg="Are you sure you want to remove this assignment?"
                                                                class="text-danger delete_button float-right"><i class="fa fa-xmark"></i></a></p>
                                                    @endforeach
                                                </div>
                                            @endif
                                                <p style="margin-top: 10px;"><button data-bs-toggle="modal" data-bs-target='#group_assing_modal'
                                                        class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center mx-auto" ><i class="fa fa-plus"></i></button></p>
                                                <div class="d-none">
                                                    <div class=" inside-content">
                                                        <div class="card-body">
                                                            <form method="post" action="{{route('head_office.assign_locations',$beSpokeForm->id)}}">
                                                                @csrf
                                                                <p>Please select a group/tier</p>
                                                                @include('head_office.my_organisation.tree-list',['groups' => $allGroups])
                                                                <input type="hidden" name="form_id" value="{{$beSpokeForm->id}}">
                                                                <input type="submit" value='Save' class="btn btn-info w-100">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            {{-- <button class="btn btn-outline-secondary custom-outline-btn" onclick="showLocations(this)" data-id='{{$beSpokeForm->id}}'>
                                                {{ count($beSpokeForm->assignedBespokeFroms) }}
                                            </button>
                                            @if (count($beSpokeForm->assignedBespokeFroms) > 0)
                                            <div hidden>
                                                <div class="table-wrapper" >
                                                    <table  class="dataTable-case table table-responsive table-bordered mx-auto rounded" style="width:80%">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th class="text-center">Locations</th>
                                                                <th>Trading Name</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="all_locations text-center">
                                                            @foreach ($beSpokeForm->assignedBespokeFroms as $assignForm)
                                                                <tr>
                                                                    <td class="fw-semibolf">{{ $assignForm->locations()->username }}</td>
                                                                    <td>{{ $assignForm->locations()->trading_name }}</td>
                                                                    <td>
                                                                        <div class="d-flex gap-1 justify-content-center align-items-center mx-auto"
                                                                            style="width: fit-content;">
                                                                            <a href="{{route('head_office.removeLocations',$assignForm->id)}}" type="button" class="btn p-0 px-2" title="Remove Location">
                                                                                <i class="fa-solid fa-trash text-danger"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @endif --}}
                                        </td>
                                        <td>
                                            <!-- <input style="border-radius: 4px;border:1px solid black;" type="text" value="{{$beSpokeForm->purpose}}" readonly> -->
                                            <p>{{$beSpokeForm->purpose}}</p>
                                        </td>
                                        <td>
                                        @if (isset($beSpokeForm->created_by->user))
                                            
                                        
                                            <div class="d-flex flex-column gap-2 ">
                                                <div class="d-flex align-items-center gap-1" style="font-size: 14px">
                                                    <a href="#" 
                                                        onclick="preventHash(event)">
                                                        <div class="user-icon-circle" title="User Profile">
                                                            @if (isset($beSpokeForm->created_by->user->logo))
                                                                <img src="{{ $beSpokeForm->created_by->user->logo }}" alt="png_img"
                                                                    style="width: 30px;height:30px;border-radius:50%;">
                                                            @else
                                                                <div class="user-img-placeholder" id="user-img-place" style="width:30px;height:30px;">
                                                                    {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $beSpokeForm->created_by->user->name))) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                    Created by <p>{{$beSpokeForm->created_by->user->name}} </p>
                                                    on <p>{{$beSpokeForm->created_at->format('d/m/Y')}}</p> at <b>{{$beSpokeForm->created_at->format('h:i A')}}</b>
                                                </div>   
                                                @if (isset($beSpokeForm->modified_by))
                                                <div class="d-flex align-items-center gap-1" style="font-size: 14px">
                                                    <a href="#" 
                                                        onclick="preventHash(event)">
                                                        <div class="user-icon-circle" title="User Profile">
                                                            @if (isset($beSpokeForm->modified_by->user->logo))
                                                                <img src="{{ $beSpokeForm->modified_by->user->logo }}" alt="png_img"
                                                                    style="width: 30px;height:30px;border-radius:50%;">
                                                            @else
                                                                <div class="user-img-placeholder" id="user-img-place" style="width:30px;height:30px;">
                                                                    {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $beSpokeForm->modified_by->user->name))) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                    Last modified by <p>{{$beSpokeForm->modified_by->user->name}} </p>
                                                    on <p>{{$beSpokeForm->updated_at->format('d/m/Y')}}</p> at <p>{{$beSpokeForm->updated_at->format('h:i A')}}</p>
                                                </div>   
                                                @endif
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="actions-wrap">
                                                <a class="Edit"
                                                href="{{ route('head_office.be_spoke_forms_templates.form_template', $beSpokeForm->id) }}">
                                                <img src="{{ asset('v2/images/icons/edit-03.svg') }}" alt="">
                                            </a>
                                            {{-- <a title="Preview" target="_blank" class="text-info"
                                                href="{{ route('head_office.be_spoke_forms_templates.form_view', $beSpokeForm->id) }}">
                                                <img src="{{ asset('v2/images/icons/eye.svg') }}" alt="">
                                            </a> --}}
                                            @if (!count($beSpokeForm->records) && !$beSpokeForm->is_deleted)
                                                <a class="text-info delete_form" title="Delete"
                                                    data-msg="to delete this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.delete', ['id'=>$beSpokeForm->id,'_token'=>csrf_token()]) }}">
                                                    <img src="{{ asset('v2/images/icons/trash.svg') }}" alt="">
                                                </a>
                                            @else
                                            <a class="text-info delete_form" title="Restore"
                                            data-msg="to restore this form?"
                                            href="{{ route('head_office.be_spoke_forms.be_spoke_form.restore', ['id'=>$beSpokeForm->id,'_token'=>csrf_token()]) }}">
                                            <img src="{{ asset('v2/images/icons/flip-backward.svg') }}" alt="">
                                        </a>

                                                <a class="text-warning" title="Permanent Delete"
                                                   data-msg="Are you sure you want to soft delete this form?"
                                                   href="{{ route('head_office.be_spoke_forms.be_spoke_form.soft_delete', ['id' => $beSpokeForm->id]) }}">                                                       <?xml version="1.0" encoding="UTF-8"?>
                                                    <svg width="24px" height="24px" viewBox="0 0 28 28" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <path d="M19.5,16 C22.5375661,16 25,18.4624339 25,21.5 C25,24.5375661 22.5375661,27 19.5,27 C16.4624339,27 14,24.5375661 14,21.5 C14,18.4624339 16.4624339,16 19.5,16 Z M14,2.25 C16.0042592,2.25 17.6412737,3.82236105 17.744802,5.80084143 L17.75,6 L23.25,6 C23.6642136,6 24,6.33578644 24,6.75 C24,7.12969577 23.7178461,7.44349096 23.3517706,7.49315338 L23.25,7.5 L22.191,7.5 L21.5567191,15.3321126 C21.0850145,15.1748937 20.5892627,15.0702731 20.0764221,15.0252089 L20.6867279,7.5 L7.31327208,7.5 L8.52396146,22.4318357 C8.6186884,23.6001347 9.59446882,24.5 10.7666018,24.5 L13.7327703,24.5010736 C14.0200482,25.0520148 14.3839565,25.5566422 14.8103588,26.0008195 L10.7666018,26 C8.81304683,26 7.18674613,24.5002245 7.02886788,22.5530595 L5.808,7.5 L4.75,7.5 C4.37030423,7.5 4.05650904,7.21784612 4.00684662,6.85177056 L4,6.75 C4,6.37030423 4.28215388,6.05650904 4.64822944,6.00684662 L4.75,6 L10.25,6 C10.25,3.92893219 11.9289322,2.25 14,2.25 Z M17.0237993,19.0241379 C16.8285371,19.2194 16.8285371,19.5359825 17.0237993,19.7312446 L18.793,21.501 L17.0263884,23.2674911 C16.8311263,23.4627532 16.8311263,23.7793357 17.0263884,23.9745978 C17.2216505,24.16986 17.538233,24.16986 17.7334952,23.9745978 L19.5,22.208 L21.2693951,23.9768405 C21.4646573,24.1721026 21.7812398,24.1721026 21.9765019,23.9768405 C22.1717641,23.7815783 22.1717641,23.4649959 21.9765019,23.2697337 L20.208,21.501 L21.9792686,19.7312918 C22.1745308,19.5360297 22.1745308,19.2194472 21.9792686,19.024185 C21.7840065,18.8289229 21.467424,18.8289229 21.2721619,19.024185 L19.501,20.794 L17.7309061,19.0241379 C17.5356439,18.8288757 17.2190614,18.8288757 17.0237993,19.0241379 Z M14,3.75 C12.809136,3.75 11.8343551,4.67516159 11.7551908,5.84595119 L11.75,6 L16.25,6 L16.2448092,5.84595119 C16.1656449,4.67516159 15.190864,3.75 14,3.75 Z" id="🎨-Color">

            </path></svg>
                                                </a>

                                                {{-- button for permanent delete --}}
                                                {{-- <a class="text-danger permanent_delete_form" title="Permanent Delete"
                                                    data-msg="Are you sure you want to permanently delete this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.permanentDelete', $beSpokeForm->id) }}">
                                                    <img src="{{ asset('v2/images/icons/flip-backward.svg') }}" alt="">
                                                </a> --}}

                                                {{-- <a class="text-danger permanent_delete_form" title="Permanent Delete"
                                                    data-msg="Are you sure you want to permanently delete this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.permanentDelete', $beSpokeForm->id) }}">
                                                    <i class="fa-solid fa-trash-list"></i>
                                                </a> --}}
                                            @endif
                                            {{-- <a title="Records" class="text-info"
                                                href="{{ route('head_office.be_spoke_forms.be_spoke_form.records', $beSpokeForm->id) }}"><img
                                                    src="{{ asset('v2/images/icons/file-06.svg') }}" alt=""></a> --}}
                                            @if ($beSpokeForm->is_external_link)
                                                <a class="text-info" target="_blank" title="External Link"
                                                    href="{{route('be_spoke_forms.be_spoke_form.external_link',$beSpokeForm->external_link)}}">
                                                    <img src="{{ asset('v2/images/icons/chevron-right-double.svg') }}"
                                                        alt="">
                                                </a>
                                            @endif
                                            @if (!$beSpokeForm->is_deleted)
                                                <a class="delete_form"
                                                    data-msg="to @if ($beSpokeForm->is_active) Deactivate @else Activate @endif this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.active', ['id' => $beSpokeForm->id,'_token' => csrf_token()]) }}">
                                                    @if ($beSpokeForm->is_active)
                                                        <img title="Deactivate"
                                                            src="{{ asset('v2/images/icons/arrow-circle-broken-up-left.svg') }}"
                                                            alt="">
                                                    @else
                                                        <img title="Activate"
                                                            src="{{ asset('v2/images/icons/arrow-circle-broken-up-right.svg') }}"
                                                            alt="">
                                                    @endif
                                                </a>
                                            @endif

                                            @if (!$beSpokeForm->is_deleted)
                                                <a class="text-info delete_form"
                                                    data-msg="to @if (!$beSpokeForm->is_archived) Archive @else Unarchive @endif this form?"
                                                    href="{{ route('head_office.be_spoke_forms.be_spoke_form.archived', ['id'=>$beSpokeForm->id,'_token'=>csrf_token()]) }}">
                                                    @if (!$beSpokeForm->is_archived)
                                                        <img title="archive" src="{{asset('images/folder-lock.svg')}}" alt="" style="opacity: 0.5;">
                                                    @else
                                                    <img title="Unarchive" src="{{asset('images/folder.svg')}}" alt="" style="opacity: 0.5;">
                                                    @endif
                                                </a>
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>

                        @else
                            <p style="text-align: center; padding-top:40px; font-size: 20px;">No deleted forms</p>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- <div class="modal fade modal-lg" id="LocModal" tabindex="-1"  aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Assigned Locations</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="dynamic-table-container">
                        </div>
                    </div>
                    <style>
                        .select2-container--open {
                            z-index: 9999999 !important;
                        }
                    </style>
                    <div class="modal-footer justify-content-between">
                        <form action="{{route('head_office.assign_locations')}}" method="POST" class="d-flex w-75 align-items-center gap-3">
                            @csrf
                            <div class="w-50" style="z-index: 20;">
                                <input type="text" hidden name="form_id" id="custom_form_id">
                                <select name="assigned_locations[]" class="form-select select2" id='multi_loc_select' multiple required  style="min-width: 200px;">
                                    @isset($beSpokeForm->form_owner)
                                        @foreach($beSpokeForm->form_owner->locations as $loc)
                                            <option value="{{$loc->location->id}}">{{$loc->location->trading_name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">Add Locations</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div> --}}

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

@endsection

<div id="draggable" class="bottom-nav position-fixed display_cases_nav" style="z-index: 10;" aria-describedby="drag" >
    <div class="left-side">
        <div class="info-wrapper">
            <div class="selected-show">
                <h5 id="count">0</h5>
            </div>
            <div class="info-heading" style="max-width: 180px;overflow:hidden;">
                <p>Items Selected</p>
                <div class="dots-wrapper">
                    <span class="dot"></span>
                </div>
            </div>
        </div>

        <div style="max-width: 570px;overflow-x:scroll;height:100%;" class="custom-drag-scroll">
        <div class="btn-wrapper" >
            <form action="{{route('head_office.be_spoke_forms_templates.form_template_duplicate_bulk')}}" method="POST" class="mb-0">
                @csrf
                <input type="" hidden name="form_ids" class="form_ids">
                <button id='export-case-btn' class="bar-btn" title="Export selected cases" style="width: 112px;">
                    <svg  width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.5 2.0028C9.82495 2.01194 9.4197 2.05103 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8.05103 3.4197 8.01194 3.82495 8.0028 4.5M19.5 2.0028C20.1751 2.01194 20.5803 2.05103 20.908 2.21799C21.2843 2.40973 21.5903 2.71569 21.782 3.09202C21.949 3.4197 21.9881 3.82494 21.9972 4.49999M21.9972 13.5C21.9881 14.175 21.949 14.5803 21.782 14.908C21.5903 15.2843 21.2843 15.5903 20.908 15.782C20.5803 15.949 20.1751 15.9881 19.5 15.9972M22 7.99999V9.99999M14.0001 2H16M5.2 22H12.8C13.9201 22 14.4802 22 14.908 21.782C15.2843 21.5903 15.5903 21.2843 15.782 20.908C16 20.4802 16 19.9201 16 18.8V11.2C16 10.0799 16 9.51984 15.782 9.09202C15.5903 8.71569 15.2843 8.40973 14.908 8.21799C14.4802 8 13.9201 8 12.8 8H5.2C4.0799 8 3.51984 8 3.09202 8.21799C2.71569 8.40973 2.40973 8.71569 2.21799 9.09202C2 9.51984 2 10.0799 2 11.2V18.8C2 19.9201 2 20.4802 2.21799 20.908C2.40973 21.2843 2.71569 21.5903 3.09202 21.782C3.51984 22 4.07989 22 5.2 22Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    <p>Duplicate</p>
                </button>
            </form>
        </div>
    </div>
    
    </div>
    <button class="drag-btn">
        <img src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
        <img style="margin-top:-15px;" src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
    </button>
</div>

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
@endsection

@section('scripts')
{{-- <script src="https://cdn.datatables.net/plug-ins/2.0.8/sorting/absolute.js"></script> --}}
    <script src="{{ asset('js/alertify.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Side bar Close =============
                var sidebar = $('#sidebar');
                const imgTag = $('#side-img');
                const imgPath = imgTag.attr('src').replace('chevron-left-double','chevron-right-double');
                imgTag.attr('src',imgPath)
                sidebar.animate({ width: 0,opacity:0 }, 300);


            loadActiveTab();
            changeTabUrl('AllFormBespoke')
            // var hidden = $('dataTable_all').absoluteOrder( [
            //     { value: 'XXX', position: 'top' }
            // ] );
            const table = new DataTable('#dataTable_all', {
                orderFixed: {'pre': [7, 'asc']},
                columnDefs: [{
                targets: -1,
                visible: false,
                // type: hidden
                }],
                paging: false,
                info: false,
                language: {
                    search: ""
                }
            });

            $('#dataTable-select-all').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            let sessionIds = []; // Array to store data-session-id values
            table.on('change', 'input', function() {

                let rowData = table.column(0).nodes().filter(function(value, index) {
                    let inputElement = $(value).find('input');
                    return inputElement.prop('checked');
                });

                sessionIds = [];
                $.each(rowData, function(index, obj) {
                    let sessionId = $(obj).data('form-id');
                    if (sessionId) {
                        sessionIds.push(sessionId);
                    }
                });

                $('.form_ids').val(sessionIds);
                console.log(sessionIds)


                if (rowData.length > 0) {
                    $('#draggable').addClass('anim').removeClass('reverse-anim');
                } else {
                    $('#draggable').addClass('reverse-anim').removeClass('anim');
                }


                const dotsWrapper = $('.dots-wrapper');
                dotsWrapper.empty();

                for (let i = 0; i < rowData.length; i++) {
                    dotsWrapper.append('<span class="dot" style="width:8px;height:8px;"></span>')
                }
                $('#count').text(rowData.length);
            });


            new DataTable('#dataTable_del,#dataTable_archive', {
                paging: false,
                info: false,
                language: {
                    search: ""
                }
            });

        });

        function copyFunction(inputId) {
            var copyText = document.querySelector(`[data-column="${inputId}"]`);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
        }

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log(tab);
            //$(window.location)[0].replace(url);
            //window.location.replace(url);
            $('.nav-tabs button[data-target="' + tab + '"]').tab('show');
        }

        // function showLocations(element) {
        //     let table;
        //     let num = $(element).text();
        //     let id = $(element).data("id")
        //         $('#custom_form_id').val(id);
        //     if (parseInt(num) !== 0) {
        //         let tableHtml = $(element).siblings().find('.table-wrapper').prop('outerHTML');
        //         $('#dynamic-table-container').html(tableHtml);
        //         const newTable = $(tableHtml).find('.dataTable-case');
                
        //         new DataTable('#dynamic-table-container .dataTable-case', {
        //         paging: false,
        //         info: false,
        //         language: {
        //             search: ""
        //         }
        //         });
        //     }else{
        //         $('#dynamic-table-container').html('<p>No Location Assigned!</p>');
        //     }
        //     $('#LocModal').modal('show')
        // }

        $('#multi_loc_select').select2()
        $('.btn-group-assign').on('click',function(){
        const mainContent = $(this).parent().siblings().find('.inside-content');
        $('.group-content-wrapper').empty().append(mainContent.clone());
    })
        
    function updatePurpose(element) {
            var email = $(element).val();
            var _token = '{{csrf_token()}}';
            var form_id =  $(element).data('form_id');
            console.log(form_id)
            var route = '{{route('head_office.update_form_details')}}';
            var data = {
                column: 'purpose',
                value: email,
                _token: _token,
                form_id: form_id
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }
    </script>
    
    @include('location.be_spoke_forms.script')
    @if(Session::has('success'))
        <script>
            alertify.success("{{ Session::get('success') }}");
        </script>
    @elseif(Session::has('error'))
    <script>
        alertify.error("{{ Session::get('error') }}");
    </script>
    @endif
@endsection
