@extends('layouts.head_office_app')
@section('title', 'Head Office Dashboard')
@section('content')
    <style>
        .input_cus{
            border: 1px solid transparent;
            transition: 0.2s ease;
        }
        .input_cus2{
            border: 1px dotted black;
            transition: 0.2s ease;
        }
        .input_cus:hover{
            border: 1px dotted black;
            transition: 0.2s ease;
        }
        .table-cus thead{
            border-bottom: 2px solid #b0b1b2;
        }
        .table-cus th{
            font-size: 12px;
            color: #a5a5a4;
            font-weight: 500;
        }
        .table-cus td{
            color: #9a9a99;
        }
        .btn-dark-cus{
            background: #ab6be1;
            border: 0;
            border-radius: 3rem;
        }
        .select2-dropdown--below{
            z-index: 99999999;
        }

        .select2-container{
            width: 100% !important;
        }

        div.dt-container div.dt-layout-row{
            display: block;
        }
        
    </style>
    <div id="content" class="d-flex">
       
        <div style="height:100%;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('head_office.be_spoke_form.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('head_office.be_spoke_form.index') }}">Forms</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Near Miss</li>
                </ol>
            </nav>
            <div style="min-width: 250px;border-right: 2px solid #dddcdf;background:white;z-index:1;position:relative;height:100%;padding-right:2rem !important;"
                class="px-3 me-2">
                <ul class="case-nav nav nav-tabs d-flex flex-column " id="myTab" role="tablist">
                    <li class="nav-item " role="presentation">
                        <button class="nav-link active d-flex align-items-center justify-content-between w-100"
                            id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button"
                            role="tab" aria-controls="home-tab-pane" aria-selected="true">Form Settings <i
                                class="fa-solid fa-chevron-right" style="font-size: 13px;"></i></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center justify-content-between w-100" id="profile-tab"
                            data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab"
                            aria-controls="profile-tab-pane" aria-selected="false">Customize Form <i
                                class="fa-solid fa-chevron-right" style="font-size: 13px;"></i></button>
                    </li>
                </ul>
            </div>

        </div>

    <script>
        document.getElementById('update_form').addEventListener('click', function() {
            // Triggers the submit button for the form
            document.querySelector('form').submit();
            // Display success message (You can modify this as per your needs)
            alert("Update Successful!");
        });
    </script>
    <script>
    document.getElementById('save-btn').addEventListener('click', function() {
        document.querySelector('button[type="submit"]').click(); // Triggers the Update button
    });
    </script>

    <div class="tab-content" id="myTabContent" style="width: 100%">
        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <div class="mx-4 " >
                
                            <div>
                                
                                
                                <form method="POST" action="{{ route('near_miss_manager.update', $near_miss->id) }}">
                                    @csrf
                                    <div class="form-name-warp" style="text-align: center; width: 100%;">
                                        <div class="form-group form-name-input-edit resizing-input" style="display: inline-block; width: 100%;">
                                            <input type="text" style="width: 100%; min-width: 200px;" name="name" class="input_cus" value="{{$near_miss->name}}" readonly>
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2.5 21.4998L8.04927 19.3655C8.40421 19.229 8.58168 19.1607 8.74772 19.0716C8.8952 18.9924 9.0358 18.901 9.16804 18.7984C9.31692 18.6829 9.45137 18.5484 9.72028 18.2795L21 6.99982C22.1046 5.89525 22.1046 4.10438 21 2.99981C19.8955 1.89525 18.1046 1.89524 17 2.99981L5.72028 14.2795C5.45138 14.5484 5.31692 14.6829 5.20139 14.8318C5.09877 14.964 5.0074 15.1046 4.92823 15.2521C4.83911 15.4181 4.77085 15.5956 4.63433 15.9506L2.5 21.4998ZM2.5 21.4998L4.55812 16.1488C4.7054 15.7659 4.77903 15.5744 4.90534 15.4867C5.01572 15.4101 5.1523 15.3811 5.2843 15.4063C5.43533 15.4351 5.58038 15.5802 5.87048 15.8703L8.12957 18.1294C8.41967 18.4195 8.56472 18.5645 8.59356 18.7155C8.61877 18.8475 8.58979 18.9841 8.51314 19.0945C8.42545 19.2208 8.23399 19.2944 7.85107 19.4417L2.5 21.4998Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: right;;">
                                        <a href="{{ route('head_office.be_spoke_form.index', ['tab' => 'AllFormBespoke']) }}" style="
                                            background-color: transparent; 
                                            color: black; 
                                            border: none; 
                                            padding: 8px 16px; 
                                            cursor: pointer; 
                                            text-decoration: none; 
                                            display: inline-flex; 
                                            align-items: center; 
                                            transition: all 0.3s;"
                                            onmouseover="this.style.cursor='pointer';"
                                            onmouseout="this.style.cursor='pointer';">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 5px;">
                                                <path d="M3 12L21 12" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M12 3L3 12L12 21" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            Back
                                        </a>
                                    
                                        <button class="btn m-0 primary-btn" id="update_form" style="margin-left: auto;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7 3V6.4C7 6.96005 7 7.24008 7.10899 7.45399C7.20487 7.64215 7.35785 7.79513 7.54601 7.89101C7.75992 8 8.03995 8 8.6 8H15.4C15.9601 8 16.2401 8 16.454 7.89101C16.6422 7.79513 16.7951 7.64215 16.891 7.45399C17 7.24008 17 6.96005 17 6.4V4M17 21V14.6C17 14.0399 17 13.7599 16.891 13.546C16.7951 13.3578 16.6422 13.2049 16.454 13.109C16.2401 13 15.9601 13 15.4 13H8.6C8.03995 13 7.75992 13 7.54601 13.109C7.35785 13.2049 7.20487 13.3578 7.10899 13.546C7 13.7599 7 14.0399 7 14.6V21M21 9.32548V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V7.8C3 6.11984 3 5.27976 3.32698 4.63803C3.6146 4.07354 4.07354 3.6146 4.63803 3.32698C5.27976 3 6.11984 3 7.8 3H14.6745C15.1637 3 15.4083 3 15.6385 3.05526C15.8425 3.10425 16.0376 3.18506 16.2166 3.29472C16.4184 3.4184 16.5914 3.59135 16.9373 3.93726L20.0627 7.06274C20.4086 7.40865 20.5816 7.5816 20.7053 7.78343C20.8149 7.96237 20.8957 8.15746 20.9447 8.36154C21 8.59171 21 8.8363 21 9.32548Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            Save
                                        </button>
                                    </div>
                                    <div style="display: flex; justify-content: center; width: 100%;">
                                        
                                        <div style="width: 50%;">
                                    <div style="text-align: left;">
                                        <p class="m-0" style="color: #7bb86b; font-size: 14px; font-weight: bold;">General</p>
                                    </div>  
                
                                </div>
                
                                    </div>
                
                
                
                
                                    <div style="display: flex; justify-content: center; width: 100%;">
                                        
                                        <div style="width: 50%;">
                                            <div class="mt-2">
                                                <div class="form-fle">
                                                    <label style="font-weight: 500; margin-bottom: 0.2rem;" for="purpose">
                                                        Purpose:
                                                    </label>
                                                    <textarea spellcheck="true" style="border: 2px solid #D9D9D9;" 
                                                            class="form-control shadow-none" id="purpose" name="purpose"
                                                            placeholder="purpose of near miss">@if (isset($near_miss)){{ $near_miss->purpose }}@endif</textarea>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="form-fle">
                                                    <label style="font-weight: 500; margin-bottom: 0.2rem;" for="description">
                                                        Description:
                                                    </label>
                                                    <textarea spellcheck="true" style="border: 2px solid #D9D9D9;" 
                                                            class="form-control shadow-none" id="description" name="description"
                                                            placeholder="Enter Form description here">@if (isset($near_miss)){{ $near_miss->description }}@endif</textarea>
                                                </div>
                                            </div>
                                        </div>          
                                    </div>
                                    <div style="display: flex; justify-content: center; width: 100%;">
                                        <div style="width: 50%;">
                                            <div class="">
                                                <label for="color">Color</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="custom-color-wrap" style="height: 44px;">
                                                        <input class="form-control custom-color" type="color"
                                                            @if (isset($near_miss) && $near_miss->color) 
                                                            value="{{ old('color', $near_miss->color ?? '#000000') }}" @endif id="color" name="color">
                                                        <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M24 40.8332C26.123 42.7334 28.9266 43.8888 32 43.8888C38.6274 43.8888 44 38.5162 44 31.8888C44 26.588 40.5629 22.0899 35.7957 20.5016" 
                                                                stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M27.1711 27.4989C27.7058 28.8585 27.9995 30.3394 27.9995 31.8888C27.9995 38.5162 22.6269 43.8888 15.9995 43.8888C9.37209 43.8888 3.99951 38.5162 3.99951 31.8888C3.99951 26.5739 7.45492 22.0659 12.2418 20.489" 
                                                                stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M24 27.8891C30.6274 27.8891 36 22.5165 36 15.8891C36 9.26165 30.6274 3.88907 24 3.88907C17.3726 3.88907 12 9.26165 12 15.8891C12 22.5165 17.3726 27.8891 24 27.8891Z" 
                                                                stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </div>
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" 
                                                        data-placement="top" title="Any report displayed in the location’s timeline or any case generated in the case manager will show this colour" style="margin-bottom: 1px;">
                                                        <path d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" 
                                                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1 mt-1" style="padding-top: 15px;">
                                                <p class="m-0" style="font-size: 14px; color: #08fc61;">Type</p>
                                            </div>
                                            <div class="d-flex align-items-center gap-1" style="margin-top: 0;">
                                                <div class="d-flex flex-column align-items-center">
                                                    <label for="" class="fw-semibold" style="margin-top: 0;">Category:</label>
                                                </div>
                                                @livewire('category-manager', ['form_id' => $near_miss->id, 'near_miss_table' => true])
                                            </div>
                                            
                                            
                                            
                                            
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; justify-content: center; width: 100%;">
                                        <div style="width: 50%;">
                                            <div class="mt-2" style="width: fit-content;">
                                                <div class="form-flex">
                                                    <label for="quick_report">Show in Quick Report:</label>
                                                    <input hidden type="checkbox" id="quick_report" name="quick_report"
                                                        {{ isset($near_miss) && $near_miss->is_quick_report == true ? 'checked' : '' }}>
                                                    <div class="btn-wrap">
                                                        <button type="button" id="quick_report-yes-btn" class="btn btn-outline-secondary rounded-button">Yes</button>
                                                        <button type="button" id="quick_report-no-btn" class="btn btn-outline-secondary">No</button>
                                                    </div>
                                                </div>
                                                <div class="form-flex" id="qr-wrapper" style="display: {{ isset($near_miss) && $near_miss->is_quick_report == true ? 'flex' : 'none' }};">
                                                    <label for="qr">Generate QR Code:</label>
                                                    <input hidden type="checkbox" id="qr" name="qr"
                                                        {{ isset($near_miss) && $near_miss->is_qr_code == true ? 'checked' : '' }}>
                                                    <div class="btn-wrap">
                                                        <button type="button" id="qr-yes-btn" class="btn btn-outline-secondary">Yes</button>
                                                        <button type="button" id="qr-no-btn" class="btn btn-outline-secondary">No</button>
                                                    </div>
                                                </div>
                                                <div class="form-flex" style="position: relative">
                                                    <label for="allow_editing">Allow editing after submission:</label>
                                                    <input hidden type="checkbox" id="allow_editing" name="allow_editing"
                                                        {{ isset($near_miss) && $near_miss->allow_editing_state != 'disable' ? 'checked' : '' }}>
                                                    <div class="btn-wrap">
                                                        <button type="button" id="allow_editing-yes-btn" class="btn btn-outline-secondary">Yes</button>
                                                        <button type="button" id="allow_editing-no-btn" class="btn btn-outline-secondary">No</button>
                                                    </div>
                                                    
                                                </div>
                                                <div class="form-flex">
                                                    <div class="allow-editing-wrapper"
                                                        style="display:{{ isset($near_miss) && $near_miss->allow_editing_state != 'disable' ? 'flex' : 'none' }};
                                                            flex-direction: column; position: relative;">
                                                        
                                                        <div style="display: block; width: 100%; text-align: left; margin-bottom: 8px;">
                                                            <div style="display: flex; align-items: center;">
                                                                <input class="form-check-input" type="checkbox" name="allow_editing_time_always" id="allow_editing_time_always"
                                                                    {{ isset($near_miss) && $near_miss->allow_editing_state == 'always' ? 'checked' : '' }}
                                                                    {{ isset($near_miss) && $near_miss->allow_editing_time == null ? 'checked' : '' }}
                                                                    style="margin-right: 5px;">
                                                                <label for="allow_editing_time_always" style="font-size: 12px; color: #999;">Always</label>
                                                            </div>
                                                        </div>
                                                
                                                        <div style="display: flex; align-items: center;">
                                                            <label for="allow_editing_select" style="font-size: 14px; color: #333; margin-right: 8px;">Limit by:</label>
                                                            <input @if (isset($near_miss) && $near_miss->allow_editing_state == 'always') disabled @endif 
                                                                class="custom-input custom-number" type="number" min="0" max="1000" oninput="fitSize(event)" 
                                                                id="allow_editing_time" name="allow_editing_time" 
                                                                value="{{ isset($near_miss) && $near_miss->allow_editing_time ? $near_miss->allow_editing_time : 0 }}" 
                                                                style="margin-right: 8px;">
                                                            
                                                            <div class="select-wrapper" style="position: relative;">
                                                                <select @if (isset($near_miss) && $near_miss->allow_editing_state == 'always') disabled @endif 
                                                                    id="allow_editing_select" class="form-control" style="width: 5rem;">
                                                                    <option value="1" {{ isset($near_miss) && $near_miss->allow_editing_state == 'minutes' ? 'selected' : '' }}>minutes</option>
                                                                    <option value="2" {{ isset($near_miss) && $near_miss->allow_editing_state == 'hour' ? 'selected' : '' }}>Hour</option>
                                                                    <option value="3" {{ isset($near_miss) && $near_miss->allow_editing_state == 'day' ? 'selected' : '' }}>Day</option>
                                                                    <option value="4" {{ isset($near_miss) && $near_miss->allow_editing_state == 'week' ? 'selected' : '' }}>Week</option>
                                                                </select>
                                                                <i class="fa-solid fa-chevron-down" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%);"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                
                                                
                
                                                
                                            
                                            </div>
                                        </div>
                                    </div>
                                    
                                <div class="d-flex justify-content-between">
                                    <div></div>
                                    <button class="primary-btn" type="submit">Update</button>
                                </div>
                            </form>
                            </div>
                
                    </div>

        </div>

        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="home">
            <div class="mt-4">
                <div class="d-flex w-100 justify-content-between">
                    <p class="m-0" style="color: #7bb86b;font-size:14px;font-weight:bold;"></p>
                    <button class="primary-btn " style="margin-bottom: 20px;" data-bs-toggle="modal" data-bs-target="#near_miss_modal"> Add New</button>
                </div>
                <table class="table new-table" id="dataTable_all"  style="width:70% !important;">
                <thead>
                    <tr style="background-color: #f5f5f5; text-align: left;">
                        <th>Customized Form</th>
                        <th>Status</th>
                        <th>Assign to</th>
                        <th>Purpose</th>
                        <th style="width: 10%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($near_miss->settings))
                        @foreach ($near_miss->settings as $set)
                            {{-- @if (isset($set->location)) --}}
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="padding: 12px; font-size: 14px; background-color: #f9f9f9;">{{$set->name}}</td>
                                    <td>
                                        @if ($set->is_active)
                                        <p class="text-success d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:2px;"></i>Live</p>
                                        @else
                                        <p class="text-danger d-flex align-items-center gap-1 m-0"><i class="fa-solid fa-circle" style="font-size: 6px;margin-top:2px;"></i>Inactive</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if($set->location_id != null)
                                            {{$set->location->trading_name}}
                                        @else
                                        <button data-bs-toggle="modal" data-bs-target="#assignLocationModal" data-setting-id="{{$set->id}}" class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center" onclick="setSettingId({{ $set->id }})">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        @endif
                                    </td>
                                    <td >{{$set->purpose}}</td>
                                    <td>
                                        <div class="actions-wrap">
                                            <a class="Edit"
                                            href="{{ route('head_office.update_near_miss.edit',['id'=>$set->id]) }}">
                                            <img src="{{ asset('v2/images/icons/edit-03.svg') }}" alt="">
                                            </a>

                                            <a class="delete_form"
                                                data-msg="to @if ($set->is_active) Deactivate @else Activate @endif this form?"
                                                href="{{ route('head_office.update_near_miss.status', ['id' => $set->id,'_token' => csrf_token()]) }}">
                                                @if ($set->is_active)
                                                    <img title="Deactivate"
                                                        src="{{ asset('v2/images/icons/arrow-circle-broken-up-left.svg') }}"
                                                        alt="">
                                                @else
                                                    <img title="Activate"
                                                        src="{{ asset('v2/images/icons/arrow-circle-broken-up-right.svg') }}"
                                                        alt="">
                                                @endif
                                            </a>
                                            <a class="text-info delete_form" title="Delete"
                                                    data-msg="to delete this form?"
                                                    href="{{ route('head_office.update_near_miss.delete', ['id'=>$set->id,'_token'=>csrf_token()]) }}">
                                                    <img src="{{ asset('v2/images/icons/trash.svg') }}" alt="">
                                                </a>

                                        </div>
                                    </td>
                                </tr>
                            {{-- @endif --}}
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" style="padding: 12px; text-align: center; font-size: 14px; color: #9a9a99;">
                                No settings yet!
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            </div>
        </div>

    </div>

    </div>

    </div>
    <div class="modal fade" id="assignLocationModal" tabindex="-1" aria-labelledby="assignLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('head_office.near_miss.assign_location') }}" method="POST">
                @csrf
                <input type="hidden" name="setting_id" id="setting-id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignLocationModalLabel">Assign Location</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="location-select" class="fw-bold">Select Location:</label>
                        <select name="location" id="location-select" class="form-control" required>
                            <option value="" disabled selected>Select a location</option>
                            @foreach($headOffice->locations as $loc)
                                <option value="{{ $loc->location->id }}">{{ $loc->location->trading_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setSettingId(id) {
            document.getElementById('setting-id').value = id;
        }
    </script>


    <input type="hidden" value="{{ route('head_office.update_near_miss.name') }}" id="update_name_route">
    <div class="modal fade" id="near_miss_modal"  aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Setting</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('head_office.near_miss.add_setting')}}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input  type="hidden" name="near_miss" value="{{$near_miss->id}}">
                    <div>
                        <div>
                            <label for="name" class="fw-bold">Name:</label>
                            <input type="text" name="name" id="name" class="form-control" required maxlength="255"placeholder="Enter name"
                            >
                        </div>
                        <div class="mt-1">
                            <label for="" class="fw-bold">Assign to:</label>
                            <select name="location" id="location-select" class="select_2">
                                <option value="" disabled selected>Select a location</option>
                                @foreach ($headOffice->locations as $loc)
                                    <option value="{{$loc->location->id}}">{{$loc->location->trading_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <p class="mb-0 mt-2 fw-bold">Purpose:</p>
                    <textarea name="purpose" spellcheck="true" class="form-control mt-2" ></textarea>

                    <div class="form-flex mt-2">
                        <label for="show-sub-loc">Make Active:</label>
                        <input hidden class="" type="checkbox" id="show-sub-loc" name="is_active">
                        <div class="btn-wrap">
                            <button type="button" id="show-sub-loc-yes-btn"class="btn btn-outline-secondary">Yes</button>
                            <button type="button" id="show-sub-loc-no-btn" class="btn btn-outline-secondary">No</button>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="border-radius: 3rem;" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-dark btn-dark-cus">Add New</button>
            </div>
        </form>
        </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/alertify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            const table = new DataTable('#dataTable_all', {
                
                paging: false,
                info: false,
                language: {
                    search: ""
                }
            });
        })
        $('#allow_editing-yes-btn').click(function() {
                $('#allow_editing').prop('checked', true);
                $('.allow-editing-wrapper').fadeIn().css('display', 'flex');
            });

            $('#allow_editing-no-btn').click(function() {
                $('#allow_editing').prop('checked', false);
                $('.allow-editing-wrapper').fadeOut('fast');
            });

            $('#allow_editing_time_always').click(function() {
                if ($('#allow_editing_time_always').prop('checked')) {
                    $('#allow_editing_time').attr('readonly', true).attr('disabled', true);
                    $('#allow_editing_select').attr('readonly', true).attr('disabled', true);
                } else {
                    $('#allow_editing_time').removeAttr('readonly').removeAttr('disabled');
                    $('#allow_editing_select').removeAttr('readonly').removeAttr('disabled');
                }
            })
            $('#quick_report-yes-btn').click(function() {
                $('#quick_report').prop('checked', true);
                $('#qr-wrapper').fadeIn();
            });

            $('#quick_report-no-btn').click(function() {
                $('#quick_report').prop('checked', false);
                $('#qr-wrapper').fadeOut();
            });

            $('#quick_report-yes-btn').click(function() {
                $('#quick_report').prop('checked', true);
                $('#qr-wrapper').fadeIn();
            });

            $('#quick_report-no-btn').click(function() {
                $('#quick_report').prop('checked', false);
                $('#qr-wrapper').fadeOut();
            });
            $('#qr-yes-btn').click(function() {
                $('#qr').prop('checked', true);
            });

            $('#qr-no-btn').click(function() {
                $('#qr').prop('checked', false);
            });
        $(document).ready(function() {
            $('.select_2').select2();
        });
        function updateName(id, element) {
            var value = $(element).val();
            var _token = "{{ csrf_token() }}";
            var data = {
                id: id,
                value: value,
                type: 1,
                _token: _token
            }
            var route = $('#update_name_route').val();
            $.post(route, data)
                .then(function(response) {
                    if (response) {
                        alertify.notify('Name Updated!', 'success', 5, function(){  console.log('dismissed'); });
                        
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        $('#show-sub-loc-yes-btn').click(function() {
                $('#show-sub-loc').prop('checked', true);
            });

            $('#show-sub-loc-no-btn').click(function() {
                $('#show-sub-loc').prop('checked', false);
            });
    </script>
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
