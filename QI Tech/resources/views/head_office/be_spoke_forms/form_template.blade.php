@extends('layouts.head_office_app')
@section('title', 'Bespoke Form Template')
@section('sub-header')
    {{-- <ul>
            <li>
                <a class="{{ request()->route()->getName() == 'head_office.be_spoke_form.index' ? 'active' : '' }}"
                    href="{{ route('head_office.be_spoke_form.index') }}">Bespoke Forms <span></span>
                </a>
            </li>
            <li>
                <a class="active" href="{{ route('case_manager.overview') }}">
                    @if (isset($form))
                        {{ substr($form->name, 0, 30) }}
                    @else
                        New
                        Bespoke Form
                    @endif
                    <span></span>
                </a>
            </li>
        </ul> --}}

@endsection
@section('content')
<style>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.tooltip{
    display: flex !important;
}



.custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: move;
}

.close-btn {
    cursor: pointer;
    font-size: 24px;
}

.modal-buttons {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    margin-left: 10px;
}
.dragging {
    opacity: 0.9;
}

</style>
    <style>
        #dataTable_filter:after,
        .dt-search:after {
            left: 10px !important;
        }

        .draggable {
            border: none;
        }

        .form-page-contents {
            max-width: unset;
        }

        #user-avatar-table_wrapper .dt-search>label {
            display: none;
        }
        

        .ui-widget.ui-widget-content{
        height: 8px !important;
        background: #2bafa57a !important;
    }
    .ui-slider span.ui-slider-handle{
        border-radius: 50%;
        width: 22px !important;
        height: 22px !important;
        background: #2BAFA5 !important;
        top: -8px !important;
        box-shadow: 0 0 5px #162c1521;
    }
    .ui-slider-range{
        background: #2BAFA5 !important;
        height: 6px !important;
    }
    .green-bg{
        background: #2bafa524;
        border-radius: 8px;
    }
    .cn .active{
        background-color: #2bafa524;
        padding-left: 10px;
        border-radius: 6px;
    }
    .allow-update{
        display: none;
        right: -134px;
    align-items: center;
    gap: 0.3rem;
    /* position: absolute; */
    /* top: -3px; */
    }
    div.dt-container div.dt-layout-row{
        display: block;
    }
    </style>
    <div id="content" class="d-flex custom-scroll" style="max-height: 82vh !important;min-height:82vh !important;">
        <div style="height:100%;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('head_office.be_spoke_form.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('head_office.be_spoke_form.index') }}">Forms</a></li>
                    @if (isset($form))
                        <li class="breadcrumb-item active" aria-current="page">{{ $form->name }}</li>
                    @endif
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
                            aria-controls="profile-tab-pane" aria-selected="false">Case Settings <i
                                class="fa-solid fa-chevron-right" style="font-size: 13px;"></i></button>
                    </li>
                    @if (isset($form) && $form->is_draft == true)
                        
                        <a class="btn bg-danger text-white mt-2 me-2" href="{{ route('head_office.be_spoke_forms.be_spoke_form.delete', ['id' => $form->id, '_token' => csrf_token()]) }}">Cancel Duplication</a>
                    @endif

                </ul>
            </div>

        </div>
        <div style="width: 100%;">
            @include('layouts.error')

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <div class="forms-wrapper justify-content-center">


                        <a id="actionButton" href="{{ route('head_office.be_spoke_form.index') }}" style="position: absolute; right: 25px; top: 9px; color: rgb(120, 196, 188); text-decoration: none; font-size: 16px; display: flex; align-items: center;">
                            <i id="buttonIcon" class="fa fa-arrow-left" style="margin-right: 5px;"></i> <span id="buttonText">Back</span>
                        </a>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const actionButton = document.getElementById('actionButton');
                                const buttonIcon = document.getElementById('buttonIcon');
                                const buttonText = document.getElementById('buttonText');
                                const form = document.querySelector('form');
                                let formFields = form.querySelectorAll('input, select, textarea');
                                let originalValues = {};
                        
                                formFields.forEach(field => {
                                    originalValues[field.name] = field.value;
                                });
                        
                                formFields.forEach(field => {
                                    field.addEventListener('input', function() {
                                        if (field.value !== originalValues[field.name]) {
                                            changeToCancel();
                                        }
                                    });
                        
                                    field.addEventListener('change', function() {
                                        if (field.value !== originalValues[field.name]) {
                                            changeToCancel();
                                        }
                                    });
                                });
                        
                                function changeToCancel() {
                                    buttonText.textContent = 'Cancel';
                                    buttonIcon.className = 'fa fa-arrow-left';
                                    actionButton.style.color = 'black';
                        
                                    actionButton.onclick = null;
                                    window.onbeforeunload = null;
                                }
                            });
                        </script>
                        <style>
                            a:hover {
                                color: black !important;
                            }
                        </style>
                        
                        <form name="fields_form" id="main-form" method="post"
                            action="{{ route('head_office.be_spoke_forms_templates.form_template_save', $id) }}">
                            @csrf
                            <input type="hidden" name="id" id="form_id"
                                value="@if (isset($form)) {{ $form->id }} @endif">

                            <div class="form-name-warp">
                                <div class="form-group form-name-input-edit resizing-input">
                                    {{-- <label for="form_name">{{ isset($form) ? 'Edit' : 'Add' }} form name</label> --}}
                                    <input  class="form-control" type="text"
                                        value="@if (isset($form)) {{ $form->name }} @endif" id="form_name"
                                        name="form_name" placeholder="Add Form Name" required>
                                    <span style="display: none;"></span>
                                    @if (isset($form))
                                        <div class="position-relative">
                                            <svg id="tooltip-btn" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <div class="d-flex flex-column gap-2 tooltip-wrap">
                                                @if(isset($form->created_by->user))
                                                <div class="d-flex align-items-center gap-1" style="font-size: 14px">
                                                    <a href="#" onclick="preventHash(event)">
                                                        <div class="user-icon-circle" title="User Profile">
                                                            @if (isset($form->created_by->user->logo))
                                                                <img src="{{ $form->created_by->user->logo }}"
                                                                    alt="png_img"
                                                                    style="width: 30px;height:30px;border-radius:50%;">
                                                            @else
                                                                <div class="user-img-placeholder" id="user-img-place"
                                                                    style="width:30px;height:30px;">
                                                                    {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $form->created_by->user->name))) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                    Created by <b>{{ $form->created_by->user->name }} </b>
                                                    on <b>{{ $form->created_at->format('d/m/Y') }}</b> at
                                                    <b>{{ $form->created_at->format('h:i A') }}</b>
                                                </div>
                                                @elseif (!isset($form->created_by) && $form->submitable_to_nhs_lfpse == true )
                                                    <div class="d-flex align-items-center gap-1" style="font-size: 14px">
                                                        <a href="#" onclick="preventHash(event)">
                                                            <div class="user-icon-circle" title="User Profile">
                                                                @if (isset($head_office->logo))
                                                                    <img src="{{ $head_office->logo }}"
                                                                        alt="png_img"
                                                                        style="width: 30px;height:30px;border-radius:50%;object-fit:cover;">
                                                                @endif
                                                            </div>
                                                        </a>
                                                        Created by <b>{{ $head_office->company_name }} </b>
                                                        on <b>{{ $form->created_at->format('d/m/Y') }}</b> at
                                                        <b>{{ $form->created_at->format('h:i A') }}</b>
                                                    </div>
                                                @endif
                                                @if (isset($form->modified_by))
                                                    <div class="d-flex align-items-center gap-1" style="font-size: 14px">
                                                        <a href="#" onclick="preventHash(event)">
                                                            <div class="user-icon-circle" title="User Profile">
                                                                @if (isset($form->modified_by->user->logo))
                                                                    <img src="{{ $form->modified_by->user->logo }}"
                                                                        alt="png_img"
                                                                        style="width: 30px;height:30px;border-radius:50%;">
                                                                @else
                                                                    <div class="user-img-placeholder" id="user-img-place"
                                                                        style="width:30px;height:30px;">
                                                                        {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $form->modified_by->user->name))) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </a>
                                                        Last modified by <b>{{ $form->modified_by->user->name }} </b>
                                                        on <b>{{ $form->updated_at->format('d/m/Y') }}</b> at
                                                        <b>{{ $form->updated_at->format('h:i A') }}</b>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    @endif
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.5 21.4998L8.04927 19.3655C8.40421 19.229 8.58168 19.1607 8.74772 19.0716C8.8952 18.9924 9.0358 18.901 9.16804 18.7984C9.31692 18.6829 9.45137 18.5484 9.72028 18.2795L21 6.99982C22.1046 5.89525 22.1046 4.10438 21 2.99981C19.8955 1.89525 18.1046 1.89524 17 2.99981L5.72028 14.2795C5.45138 14.5484 5.31692 14.6829 5.20139 14.8318C5.09877 14.964 5.0074 15.1046 4.92823 15.2521C4.83911 15.4181 4.77085 15.5956 4.63433 15.9506L2.5 21.4998ZM2.5 21.4998L4.55812 16.1488C4.7054 15.7659 4.77903 15.5744 4.90534 15.4867C5.01572 15.4101 5.1523 15.3811 5.2843 15.4063C5.43533 15.4351 5.58038 15.5802 5.87048 15.8703L8.12957 18.1294C8.41967 18.4195 8.56472 18.5645 8.59356 18.7155C8.61877 18.8475 8.58979 18.9841 8.51314 19.0945C8.42545 19.2208 8.23399 19.2944 7.85107 19.4417L2.5 21.4998Z"
                                            stroke="black" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                </div>
                            </div>
                            <div class="form-inputs-wrapper">
                                <p class="setting-p p-0 m-0" style="transform:translateY(4px)">General</p>
                                {{-- <div class="">
                                    <div class="form-group form-name-input-wrapper purpose" >
                                        <label for="form_name"><i class="fa-regular fa-circle-question" data-toggle="tooltip" data-placement="top" title="This explains the purpose of this form to the responder"></i> Purpose</label>
                                        <textarea spellcheck="true"  spellcheck="true"  title="This explains the purpose of this form to the responder" class="form-control" rows="1" name="form_purpose" placeholder="Enter Form name here" required>{{ isset($form) ? $form->purpose : '' }}</textarea>
                                    </div>
                                </div> --}}
                                <div class="description-box">
                                    <div class="form-fle">
                                        <label style="font-weight: 500; margin-bottom: 0.2rem;" for="is_external_link">Description:</label>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" 
                                             data-toggle="tooltip" data-placement="top" title="This explains the purpose of this form to the responder"
                                             style="margin-bottom:1px;">
                                            <path d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                                  stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    
                                        <textarea spellcheck="true" style="text-align:left; border:2px solid #D9D9D9;" class="form-control shadow-none spellcheck" id="note" name="note" placeholder="Enter Form note here">@if (isset($form)) {{ old('note', $form->note) }} @endif</textarea>
                                    </div>
                                </div>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const externalBtn = document.getElementById('external-btn');
                                        const internalBtn = document.getElementById('internal-btn');
                                        const descriptionBox = document.querySelector('.description-box');
                                    
                                        externalBtn.addEventListener('click', function() {
                                            descriptionBox.style.display = 'none';
                                        });
                                    
                                        internalBtn.addEventListener('click', function() {
                                            descriptionBox.style.display = 'block';
                                        });
                                    });
                                </script>
                                
                                
                                
                                <div class="">
                                    <label for="color_code">Expiry</label>
                                    <div class="form-flex">
                                        <input class="form-control custom-input" id="expiry_time" type="datetime-local"
                                            style="width: 15rem;padding:20px !important;" name="expiry"
                                            {{ isset($form) && $form->expiry_state != 'never_expire' ? '' : 'disabled' }}
                                            value='{{ isset($form) ? $form->expiry_time : null }}'>
                                        <div class="d-flex" style="gap:0.3rem;">
                                            <input class="form-check-input" type="checkbox" name="never_expire_check"
                                                id="never_expire_check"
                                                {{ isset($form) && $form->expiry_state == 'expiry_time' ? '' : 'checked' }}>
                                            {{-- <label for="never_expire_check" style="margin: 0;font-size:12px;color:#999;">Never
                                                Expire</label> --}}
                                        </div>
                                        <label for="color_code" class="fw-normal">No expiry</label>

                                    </div>
                                </div>
                                <div class="">
                                    <label for="color_code">Color</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class=" custom-color-wrap " style="height: 44px;">
                                            <input class="form-control custom-color" type="color"
                                                @if (isset($form) && $form->color_code) value="{{ $form->color_code }}" @endif
                                                id="color_code" name="color_code">
                                            <svg width="20" height="20" viewBox="0 0 48 48" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M24 40.8332C26.123 42.7334 28.9266 43.8888 32 43.8888C38.6274 43.8888 44 38.5162 44 31.8888C44 26.588 40.5629 22.0899 35.7957 20.5016"
                                                    stroke="#000000" stroke-width="4" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M27.1711 27.4989C27.7058 28.8585 27.9995 30.3394 27.9995 31.8888C27.9995 38.5162 22.6269 43.8888 15.9995 43.8888C9.37209 43.8888 3.99951 38.5162 3.99951 31.8888C3.99951 26.5739 7.45492 22.0659 12.2418 20.489"
                                                    stroke="#000000" stroke-width="4" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M24 27.8891C30.6274 27.8891 36 22.5165 36 15.8891C36 9.26165 30.6274 3.88907 24 3.88907C17.3726 3.88907 12 9.26165 12 15.8891C12 22.5165 17.3726 27.8891 24 27.8891Z"
                                                    stroke="#000000" stroke-width="4" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top"
                                            title="Any report displayed in the location’s timeline or any case generated in the case manager will show this colour"
                                            style="margin-bottom:1px;">
                                            <path
                                                d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                                stroke="black" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>


                                <p class="setting-p" style="margin-top: 3rem;">Type</p>
                                <div class="">
                                    <div class="form-flex" style="height: 44px;">
                                         <label for="form_name">Category:</label>
                                         @isset($form)
                                            @livewire('category-manager', ['form_id' => $form->id])
                                        @else
                                            @livewire('category-manager', ['form_id' => null])
                                        @endisset
                                        <input type="hidden" id='be_spoke_form_category_id'
                                            name="be_spoke_form_category_id"
                                            value="{{ isset($form) ? $form->be_spoke_form_category_id : null }}">

                                        {{-- <select name="be_spoke_form_category_id" class="form-control">
                                        @foreach ($head_office->headOfficeCategories as $head_office_category)
                                        <option value="{{$head_office_category->id}}" @if ($head_office_category->id == ($form->be_spoke_form_category_id ?? 0)) selected @endif>{{$head_office_category->name}}</option>
                                        @endforeach
                                    </select> --}}
                                    </div>
                                </div>
                                <div class="d-flex gap-2" style="width: 500px;">
                                    <div class="form-flex">
                                        <label for="is_external_link">Type:</label>
                                        <input hidden class="" type="checkbox"
                                            @if (isset($form)) {{ $form->is_external_link ? '' : 'checked' }} @endif
                                            id="is_external_link" name="is_external_link"
                                            {{ !isset($form) ? 'checked' : '' }}>
                                        <div class="btn-wrap" style="margin-left:34px;">
                                            <button type="button" style="height: 44px;" id="internal-btn"
                                                class="btn btn-outline-secondary">Internal</button>
                                            <button type="button" style="height: 44px;" id="external-btn"
                                                class="btn btn-outline-secondary">External</button>
                                        </div>
                                    </div>
                                    @if(!empty($url) && !empty($form->external_link))
                                        <div class="external-wrapper"
                                            style="display: {{ isset($form) && $form->is_external_link ? 'block' : 'none' }}">
                                            <input spellcheck="true" style="width: 220px;" name="form-control custom-input " id="myInput"
                                                type="text" value="{{route('be_spoke_forms.be_spoke_form.external_link',$form->external_link)}}"
                                                data-old="{{ $form->external_link }}"
                                                data-company='{{ str_replace(' ', '_', $head_office->company_name) }}'>
                                                <div class="tooltip">
                                                <button type="button" class="clip-btn" onclick="myFunction()"
                                                    onmouseout="outFunc()">
                                                    <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
                                                    <img src="{{ asset('images/copy-01.svg') }}" width="20"
                                                        alt="svg">
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>


                                <style>

<style>
    .btn-wrap button {
        position: relative;
        padding: 10px;
        height: 44px;
        cursor: not-allowed;
    }

    .btn-wrap .tooltip {
        visibility: hidden;
        background-color: black;
        color: #fff;
        text-align: center;
        padding: 5px;
        border-radius: 4px;

        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-bottom: 10px;
        white-space: nowrap;
    }

    .btn-wrap button:hover .tooltip {
        visibility: visible;
    }
</style>

                                <div class="">
                                    <div class="form-flex">
                                        <label for="add_to_case_manage">Generate:</label>
                                        <input hidden class="" id="add_to_case_manager" type="checkbox"
                                            @if (isset($form)) {{ $form->add_to_case_manager ? 'checked' : '' }} @endif
                                            name="add_to_case_manager">
                                            <div class="btn-wrap" style="margin-left:34px;">
                                                <button type="button" style="height: 44px;" id="internal-btn"
                                                    class="btn btn-outline-secondary" title="Coming Soon" style="" >Board</button>
                                                <button type="button" style="height: 44px;" id="" class="btn" disabled>Case</button>
                                            </div>
                                            
                                            </div>
                                            <style>
                                                .btn-wrap {
    position: relative; /* Positioning context for the tooltip */
}

.btn-wrap button[disabled]:hover + .tooltip {
    display: block; /* Show tooltip on hover over the disabled button */
}

.tooltip {
    display: none; /* Hidden by default */
}

                                            </style>
                                    </div>
                                </div>



                                @if (isset($form))


                                    @php
                                        $record = $form;
                                        $data_objects = [];
                                        if ($record->form_json) {
                                            $questionsJson = json_decode($record->form_json, true);
                                            if (isset($questionsJson['pages']) && count($questionsJson['pages']) > 0) {
                                                foreach ($questionsJson['pages'] as $pageIndex => $page) {
                                                    if ($page['items'] && count($page['items']) > 0) {
                                                        foreach ($page['items'] as $itemIndex => $item) {
                                                            if (
                                                                isset(
                                                                    $item['label'],
                                                                    $item['input'],
                                                                    $item['input']['type'],
                                                                    $item['input']['is_display_case'],
                                                                )
                                                            ) {
                                                                $type = $item['input']['type'];
                                                                $is_display_case = $item['input']['is_display_case'];
                                                                $is_display_summary =
                                                                    isset($item['input']['is_display_summary']) ? $item['input']['is_display_summary'] : false;

                                                                $data_objects[] = [
                                                                    'label' => $item['label'],
                                                                    'is_display_case' => $is_display_case,
                                                                    'is_display_summary' => $is_display_summary,
                                                                    'pageIndex' => $pageIndex,
                                                                    'itemIndex' => $itemIndex,
                                                                    'pageName' => $page['name'],
                                                                ];
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    @php
                                        $is_display_case_data_objects = array_filter($data_objects, function ($data) {
                                            return $data['is_display_case'];
                                        });
                                        $is_display_summary_data_objects = array_filter($data_objects, function (
                                            $data,
                                        ) {
                                            return $data['is_display_summary'];
                                        });
                                    @endphp





                                    <div id="location-timeline-container"
                                        style="display: {{ isset($form) && $form->is_external_link ? 'none' : 'block' }};"
                                        class="w-100">
                                        <p data-toggle="tooltip" data-placement="top"
                                            title=" List of fields that will appear in the summary of the timeline in location account. "
                                            class=" setting-p">Location
                                            Timeline</p>


                                        @if (count($is_display_summary_data_objects) > 0)
                                            <table class="table new-table dataTable" style="width: 100% !important">
                                                <thead>
                                                    <tr>
                                                        <th>Page</th>
                                                        <th>Question</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($is_display_summary_data_objects as $data)
                                                        <tr>
                                                            <td>{{ $data['pageName'] }}</td>
                                                            <td>{{ $data['label'] }}</td>
                                                            <td><a
                                                                    href="{{ route('head_office.be_spoke_form.rule_edit', ['form_id' => $form->id, 'page_id' => $data['pageIndex'], 'item_id' => $data['itemIndex'], 'id' => 0]) }}"><i
                                                                        class="fa-regular fa-pen-to-square text-info"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="table-responsive">
                                                <p>No fields assigned</p>

                                            </div>
                                        @endif
                                    </div>
                                @endif






                                <p class="setting-p" style="margin-top: 3rem;">Limit by</p>
                                <div class="">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="checkbox" name="active_limit_by_amount" id="active_limit_by_amount"
                                            class="form-check-input"
                                            {{ isset($form) && $form->active_limit_by_amount ? 'checked' : '' }}>
                                        <p class="m-0 my-2 text-black" style="font-weight: 500;">No of submissions</p>
                                    </div>
                                    <div id="amount_wrap"
                                        style="display: {{ isset($form) && $form->active_limit_by_amount ? '' : 'none' }}">
                                        <div class="form-fle px-3">
                                            <input type="checkbox" class="form-check-input" name="amount_total_max_res"
                                                id="amount_total_max_res" class="form-check-input"
                                                {{ isset($form) && $form->amount_total_max_res ? 'checked' : '' }}>
                                            <label for="color_code" style="font-weight: 500;">Total</label>
                                            <input class="form-control custom-input number-input"
                                                style="width: fit-content;height: 44px;" min="0" type="number"
                                                name="limits" value="{{ isset($form) ? $form->limits : '0' }}">
                                        </div>

                                        <div id="external-spec"
                                            style="display: {{ isset($form) && $form->is_external_link ? 'none' : 'block' }}">

                                            {{-- <p class="m-0 mx-1 text-black" style="font-weight: 700;">Limit by User</p> --}}
                                            <div class="form-fle px-3">
                                                <input type="checkbox" name="limit_to_one_user" id="limit_to_one_user"
                                                    class="form-check-input"
                                                    {{ isset($form) && $form->limit_to_one_user ? 'checked' : '' }}>
                                                <label for="color_code" style="font-weight: 500;word-wrap: no-wrap;">Per
                                                    User </label>
                                                <input class="form-control custom-input  number-input" type="number"
                                                    min="0" max="1000" name="limit_by_per_user_value"
                                                    value="{{ isset($form) ? $form->limit_by_per_user_value : '0' }}"
                                                    style="height: 44px;">
                                                {{-- <label for="color_code" style="font-weight: 500;word-wrap: no-wrap;">response per user</label> --}}
                                            </div>
                                            {{-- <p class="m-0 mx-1 text-black" style="font-weight: 700;">Limit by Location</p> --}}
                                            <div class="form-fle px-3">
                                                <input type="checkbox" class="form-check-input"
                                                    name="limit_to_one_location" id="limit_to_one_location"
                                                    {{ isset($form) && $form->limit_to_one_location ? 'checked' : '' }}>
                                                <label for="color_code" style="font-weight: 500;">Per location </label>
                                                <input class="form-control custom-input number-input" type="number"
                                                    oninput="fitSize(event)" min="0" max="1000"
                                                    name="limit_by_per_location_value"
                                                    value="{{ isset($form) ? $form->limit_by_per_location_value : '0' }}"
                                                    style="height: 44px;">
                                                {{-- <label for="color_code" style="font-weight: 500;">response per location</label> --}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="checkbox" class="form-check-input" name="active_limit_by_period"
                                            id="active_limit_by_period"
                                            {{ isset($form) && $form->active_limit_by_period ? 'checked' : '' }}>
                                        <p class="m-0 text-black" style="font-weight: 500;">By period</p>
                                    </div>
                                    <div id="period_wrap"
                                        style="display: {{ isset($form) && $form->active_limit_by_period ? '' : 'none' }}">
                                        <div class="form-fle px-3">
                                            <input type="checkbox" class="form-check-input"
                                                name="limit_by_period_max_check" id="limit_by_period_max_check"
                                                {{ isset($form) && $form->limit_by_period_max_state != 'off' ? 'checked' : '' }}>
                                            <label for="limit_by_period_max_check" style="font-weight: 500;">Max</label>
                                            <div class="form-flex">
                                                <input class="form-control custom-input number-input" type="number"
                                                    oninput="fitSize(event)" min="0" max="1000"
                                                    name="limit_by_period_max_value"
                                                    value="{{ isset($form) ? $form->limit_by_period_max_value : '0' }}"
                                                    style="height: 44px;">
                                                <label for="color_code" style="font-weight: 500;">per</label>
                                                <div class="select-wrapper">
                                                    <select class="form-control" style="width: 5rem;height: 44px"
                                                        name="limit_by_period_max_select">
                                                        <option value="1"
                                                            {{ isset($form) && $form->limit_by_period_max_state == 'day' ? 'selected' : '' }}>
                                                            Day</option>
                                                        <option value="2"
                                                            {{ isset($form) && $form->limit_by_period_max_state == 'week' ? 'selected' : '' }}>
                                                            Week</option>
                                                        <option value="3"
                                                            {{ isset($form) && $form->limit_by_period_max_state == 'month' ? 'selected' : '' }}>
                                                            Month</option>
                                                    </select>
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-fle px-3 mt-1">
                                            <input type="checkbox" class="form-check-input"
                                                name="limit_by_period_min_check" id="limit_by_period_min_check"
                                                {{ isset($form) && $form->limit_by_period_min_state != 'off' ? 'checked' : '' }}>
                                            <label for="limit_by_period_min_check" style="font-weight: 500;">Min</label>
                                            <div class="form-flex">
                                                <input class="form-control custom-input number-input" type="number"
                                                    oninput="fitSize(event)" min="0" max="1000"
                                                    name="limit_by_period_min_value"
                                                    value="{{ isset($form) ? $form->limit_by_period_min_value : '0' }}"
                                                    style="height: 44px;">
                                                <label for="color_code" style="font-weight: 500;">per</label>
                                                <div class="select-wrapper">
                                                    <select class="form-control" style="width: 5rem;height: 44px"
                                                        name="limit_by_period_min_select">
                                                        <option value="1"
                                                            {{ isset($form) && $form->limit_by_period_min_state == 'day' ? 'selected' : '' }}>
                                                            Day</option>
                                                        <option value="2"
                                                            {{ isset($form) && $form->limit_by_period_min_state == 'week' ? 'selected' : '' }}>
                                                            Week</option>
                                                        <option value="3"
                                                            {{ isset($form) && $form->limit_by_period_min_state == 'month' ? 'selected' : '' }}>
                                                            Month</option>
                                                    </select>
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div id="loc-settings-wrapper"
                                    style="display: {{ isset($form) && $form->is_external_link ? 'none' : 'block' }}">


                                    <p class="setting-p" style="margin-top: 1.5rem;">Location Account</p>
                                    <div>
                                        <div class="">
                                            <div class="form-flex">
                                                <label for="quick_report">Show in Quick Report:</label>
                                                <input hidden class="" type="checkbox" id="quick_report"
                                                    name="quick_report"
                                                    {{ isset($form) && $form->is_quick_report == true ? 'checked' : '' }}>
                                                <div class="btn-wrap">
                                                    <button type="button" id="quick_report-yes-btn"
                                                        class="btn btn-outline-secondary rounded-button">Yes</button>
                                                    <button type="button" id="quick_report-no-btn"
                                                        class="btn btn-outline-secondary">No</button>
                                                </div>
                                            </div>
                                            <div class="form-flex" id="qr-wrapper"
                                                style="display: {{ isset($form) && $form->is_quick_report == true ? 'flex' : 'none' }};">
                                                <label for="qr">Generate QR Code:</label>
                                                <input hidden class="" type="checkbox" id="qr"
                                                    name="qr"
                                                    {{ isset($form) && $form->is_qr_code == true ? 'checked' : '' }}>
                                                <div class="btn-wrap">
                                                    <button type="button" id="qr-yes-btn"
                                                        class="btn btn-outline-secondary">Yes</button>
                                                    <button type="button" id="qr-no-btn"
                                                        class="btn btn-outline-secondary">No</button>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="">
                                            <div class="form-flex" style="position: relative">
                                                <label for="allow_editing">Allow editing after submission:</label>
                                                <input hidden class="" type="checkbox" id="allow_editing" name="allow_editing"
                                                    {{ isset($form) && $form->allow_editing_state != 'disable' ? 'checked' : '' }}>
                                                <div class="btn-wrap">
                                                    <button type="button" id="allow_editing-yes-btn" class="btn btn-outline-secondary" onclick="showEditingOptions(true)">Yes</button>
                                                    <button type="button" id="allow_editing-no-btn" class="btn btn-outline-secondary" onclick="showEditingOptions(false)">No</button>
                                                </div>
                                            </div>
                                        
                                            <div class="form-flex allow-editing-wrapper" id="allow-editing-options" style="display: none; flex-direction: column; align-items: baseline; justify-content: left;">
                                                <div style="align-items: left; margin-bottom: 10px;">
                                                    <input class="form-check-input" type="checkbox" name="allow_editing_time_always" id="until_case_closed"
                                                        onclick="toggleLimitByFields(this)" 
                                                        {{ isset($form) && $form->allow_editing_state == 'always' ? 'checked' : '' }}
                                                        {{ isset($form) && $form->allow_editing_time == null ? 'checked' : '' }}>
                                                    <label for="until_case_closed" style="margin-left: 5px; font-size: 12px; color: #999;">
                                                        Until case closed
                                                    </label>
                                                </div>
                                                <div id="limitFields" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                                                    <label for="allow_editing_time" style="font-size: 12px; color: #999;">Limit By</label>
                                        
                                                    <input class="custom-input custom-number" type="number" min="0" max="1000" id="allow_editing_time"
                                                        name="allow_editing_time" value="{{ isset($form) && $form->allow_editing_time ? $form->allow_editing_time : 0 }}"
                                                        >
                                        
                                                    <!-- Select Field for time unit -->
                                                    <div class="select-wrapper" style="flex-direction: row; align-items: center;">
                                                        <select class="form-control" style="width: 90px;" name="allow_editing_select" id="allow_editing_select" disabled>
                                                            <option value="1" {{ isset($form) && $form->allow_editing_state == 'minutes' ? 'selected' : '' }}>minutes</option>
                                                            <option value="2" {{ isset($form) && $form->allow_editing_state == 'hour' ? 'selected' : '' }}>Hour</option>
                                                            <option value="3" {{ isset($form) && $form->allow_editing_state == 'day' ? 'selected' : '' }}>Day</option>
                                                            <option value="4" {{ isset($form) && $form->allow_editing_state == 'week' ? 'selected' : '' }}>Week</option>
                                                        </select>
                                                        <i class="fa-solid fa-chevron-down"></i>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="">
                                            <div class="form-flex position-relative">
                                                <label for="provide_update">Allow responder to provide updates:</label>
                                                <input hidden class="" type="checkbox" id="provide_update" name="provide_update" 
                                                    {{ isset($form) && $form->allow_responder_update == 1 ? 'checked' : '' }}>
                                                <div class="btn-wrap">
                                                    <button type="button" id="update-yes-btn" class="btn btn-outline-secondary" onclick="showUpdateFields(true)">Yes</button>
                                                    <button type="button" id="update-no-btn" class="btn btn-outline-secondary" onclick="showUpdateFields(false)">No</button>
                                                </div>
                                            </div>
                                        
                                            <div class="form-flex allow-update-wrapper" id="allow-update-options" style="display: none; flex-direction: column; align-items: baseline; justify-content: left;">
                                        
                                                <div style="align-items: left; ">
                                                    <input class="form-check-input" type="checkbox" name="allow_update_time_always" id="allow_update_time_always"
                                                        onclick="toggleLimitByFields(this)"
                                                        @if (isset($form))
                                                            @if ($form->allow_update_state == 'always')
                                                                checked
                                                            @else
                                                            @endif

                                                            @if ($form->allow_update_time == null && $form->allow_update_state == 'always')
                                                                checked
                                                            @else
                                                            @endif
                                                        @endif
                                                        >
                                                    <label for="allow_update_time_always" style="margin-left: 5px; font-size: 12px; color: #999;">
                                                        Until case closed
                                                    </label>
                                                </div>
                                                <div style="align-items: left; margin-bottom: 10px;">
                                                    <input class="form-check-input" type="checkbox" name="allow_update_time_open" id="allow_update_time_open"
                                                        onclick="toggleLimitByFields(this)"
                                                        {{ isset($form) && $form->allow_update_state == 'open' ? 'checked' : '' }}>
                                                    <label for="allow_update_time_open" style="margin-left: 5px; font-size: 12px; color: #999;">
                                                        Open-ended updates <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" style="margin-bottom:1px;" aria-label="This will allow users to submit updates even after a case
has been closed. The closed case will then be reassigned to a case handler" data-bs-original-title='This will allow users to submit updates even after a case
has been closed. The closed case will then be reassigned to a case handler'>
                                                            <path d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </label>
                                                </div>
                                        
                                                <div id="limitByFields" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                                        
                                                    <label for="allow_update_time" style="font-size: 12px; color: #999;">Limit time</label>
                                        
                                                    <input class="custom-input custom-number" type="number" min="0" max="1000" id="allow_update_time"
                                                        name="allow_update_time" value="{{ isset($form) && $form->allow_update_time ? $form->allow_update_time : 0 }}" disabled>
                                        
                                                    <div class="select-wrapper" style="flex-direction: row; align-items: center;">
                                                        <select class="form-control" style="width: 90px;" name="allow_update_select" id="allow_update_select" disabled>
                                                            <option value="1" {{ isset($form) && $form->allow_update_state == 'minutes' ? 'selected' : '' }}>minutes</option>
                                                            <option value="2" {{ isset($form) && $form->allow_update_state == 'hour' ? 'selected' : '' }}>Hour</option>
                                                            <option value="3" {{ isset($form) && $form->allow_update_state == 'day' ? 'selected' : '' }}>Day</option>
                                                            <option value="4" {{ isset($form) && $form->allow_update_state == 'week' ? 'selected' : '' }}>Week</option>
                                                        </select>
                                                        <i class="fa-solid fa-chevron-down"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        
                                        <div class="">
                                            <div class="form-flex">
                                                <label for="allow-draft">Allow drafts to be completed off-site:</label>
                                                <input hidden class="" type="checkbox" id="allow-draft"
                                                    name="allow_draft"
                                                    {{ isset($form) && $form->allow_drafts_off_site == 1 ? 'checked' : '' }}>
                                                <div class="btn-wrap">
                                                    <button type="button" id="draft-yes-btn"
                                                        class="btn btn-outline-secondary">Yes</button>
                                                    <button type="button" id="draft-no-btn"
                                                        class="btn btn-outline-secondary">No</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="">
                                            <div class="form-flex">
                                                <label for="show-sub-loc">Show submission in location account:</label>
                                                <input hidden class="" type="checkbox" id="show-sub-loc" name="submission_loc"
                                                    {{ isset($form) && $form->show_submission_loc == true ? 'checked' : '' }}>
                                                <div class="btn-wrap">
                                                    <button type="button" id="show-sub-loc-yes-btn"class="btn btn-outline-secondary">Yes</button>
                                                    <button type="button" id="show-sub-loc-no-btn" class="btn btn-outline-secondary">No</button>
                                                </div>
                                            </div>
                                            <div id="submission-text-area" style="display: none; margin-top: 20px;">

                                                <label>
                                                    <input type="checkbox" name="show_to_responder" id="show-to-responder-checkbox" 
                                                           @if(isset($form) && $form->show_to_responder == 1) checked @endif>
                                                    Display message to responder
                                                </label>
                                                <span style="display: inline-block; width: 20px; height: 20px; border-radius: 50%; background-color: #007bff; color: #fff; text-align: center; line-height: 20px; font-size: 14px; font-weight: bold; cursor: pointer; position: relative;" onmouseover="this.querySelector('.tooltip').style.visibility='visible';" onmouseout="this.querySelector('.tooltip').style.visibility='hidden';">
                                                    ? 
                                                    <span class="tooltip" style=" visibility: hidden; width: 220px; background-color: #333; color: #fff; text-align: center; padding: 5px; border-radius: 5px; position: absolute; bottom: 30px; /* Move tooltip above the icon */ left: 50%; transform: translateX(-50%); font-size: 12px; line-height: 1.5; white-space: pre-wrap; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); z-index: 1;"> This message will be displayed at the top of the form</span>
                                                </span>


                                                <textarea id="modal-text" class="tinymce" name="submission_text" rows="4" cols="50">@if (isset($form)) {{ old('submission_text', $form->submission_text )}} @else This submitted form will not be saved in the location account @endif</textarea>
                                               
                                                {{-- <div>
                                                    <button type="button" id="bold-btn">Bold</button>
                                                    <button type="button" id="italic-btn">Italic</button>
                                                    <input type="color" id="color-picker" value="#000000">
                                                </div> --}}
                                            </div>
                                        </div>
                                        
                                        <div id="confirmationModal" class="modal-overlay" style="display: none;">
                                            <div class="modal-content">

                                                <h6> This will <strong>show</strong> submission in the location account timeline.<br>Are you sure you want to proceed? </h6>
                                                <div class="modal-buttons">
                                                    <button id="confirmYes" class="btn btn-success" style=" background-color: var(--portal-primary-btn-color) !important; color: var(--portal-primary-btn-text-color) !important; border-color: #858796;">Yes</button>
                                                    <button id="cancelModal" class="btn btn-danger" style=" background-color: var(--portal-primary-btn-color) !important; color: var(--portal-primary-btn-text-color) !important; border-color: #858796;">No</button>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="">
                                            <div class="form-flex">
                                                <label for="color_code">Schedule:</label>
                                                <input class="form-check-input" hidden type="checkbox"
                                                    name="schedule_check" id="schedule_check"
                                                    {{ isset($form) && $form->schedule_state != 'optional' ? 'checked' : '' }}>
                                                <div class="btn-wrap">
                                                    <button type="button" id="show-sec-yes-btn"
                                                        class="btn btn-outline-secondary">Yes</button>
                                                    <button type="button" id="show-sec-no-btn"
                                                        class="btn btn-outline-secondary">No</button>
                                                </div>
                                                @if (isset($form) && $form->schedule_state == 'optional')
                                                    {{-- <p class=" text-center fw-bold m-0" style="font-size:12px;color:#2BAFA5;">Please
                                                    activate the schedule
                                                </p> --}}
                                                @endif
                                                {{-- <input class="form-control custom-input" type="datetime-local" style="width: 15rem;"
                                            name="schedule" id="schedule_input"
                                            {{ isset($form) && $form->schedule_state == 'optional' ? 'readonly' : '' }}
                                            value='{{ isset($form) ? $form->schedule_time : null }}'> --}}
                                            </div>
                                        
                                            <div id="schedule_section"
                                                style="display: {{ isset($form) ? 'block' : 'none' }}">
                                                <div class="d-flex align-items-center px-2 input-ss"
                                                    style="gap:0.3rem;display: flex !important;">
                                                    {{-- <label for="schedule_check"
                                                        style="margin: 0;font-size:14px;color:#999;font-weight:900;">Prompt location</label> --}}
                                                    {{-- <div class="form-check d-flex align-items-center gap-2">
                                                        <input class="form-check-input" type="radio"
                                                            name="schedule_radio" id="inlineRadio1" value="1"
                                                            {{-- {{ isset($form) && $form->schedule_state == 'day' ? 'checked' : '' }} --}}
                                                        {{-- <label class="form-check-label text-muted"style="font-size: 14px;" for="inlineRadio1">By Day</label>
                                                    </div> --}}
                                                    {{-- <div class="form-check d-flex align-items-center gap-2">
                                                        <input class="form-check-input" type="radio"
                                                            name="schedule_radio" id="inlineRadio2" value="2"
                                                            {{ isset($form) && $form->schedule_state == 'date' ? 'checked' : '' }}
                                                        {{-- <label class="form-check-label text-muted"style="font-size: 14px;" for="inlineRadio2">By Date</label>
                                                    </div> --}}
                                                </div>
                                                @if (isset($form))
                                                    <div class="my-2" id="day-id"
                                                        style="display: {{ $form->schedule_state == 'day' ? 'block' : 'block' }};">
                                                        @livewire('day-scheduler', ['form_id' => $form->id])
                                                    </div>
                                                    <div id="calender-id"
                                                        style="display: {{ $form->schedule_state == 'date' ? 'block' : 'block' }};">
                                                        <table id="myTable" class="row-border loc-datatable w-100 my-2">
                                                            <thead>
                                                                <tr>
                                                                    <th><input type="checkbox" name="select_all"
                                                                            value="1" id="dataTable-select-all"></th>
                                                                    <th>Date</th>
                                                                    <th>Times</th>
                                                                    <th>Repeat</th>
                                                                    <th></th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                        @foreach ($form->calenderEvent as $event)
                                                        <tr data-event-id="{{ $event->id }}" data-original-times="{{ json_encode($event->times) }}" data-original-repeat="{{ $event->repeat_state }}">
                                                            <td></td>
                                                            <td class="text-center">
                                                                {{ \Carbon\Carbon::parse($event->start_date)->isoFormat('Do MMM YYYY') }}
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="event-times">{{ json_encode($event->times) }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="event-repeat">{{ $event->repeat_state == 'off' ? 'No' : $event->repeat_state }}</span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('form_template.calender.event_delete', ['id' => $event->id,'_token'=>csrf_token()]) }}" class="text-info">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="Livewire.emit('editEvent', {{ $event->id }})" class="text-info">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                        
                                                            </tbody>
                                                        </table>
                                                        @livewire('calender', ['form_id' => $form->id])
                                                    </div>
                                                @else
                                                    <p class=" text-center mt-3 fw-bold rounded py-2"
                                                        style="color:#999;border:2px dashed #999;">
                                                        Please save the form to edit schedule
                                                    </p>
                                                @endif
                                            </div>
                                        
                                        </div>
                                    </div>
                                    {{-- <p class="text-center fw-bold"
                                        style="display: {{ isset($form) && !$form->is_external_link ? 'none' : 'block' }};color:#999;">
                                        Please select internal type to edit these settings</p> --}}
                                </div>
                                {{-- <div class="">
                                    <div class="form-fle">
                                        <label style="font-weight: bolder;margin-bottom:0.2rem;" for="is_external_link" data-toggle="tooltip" data-placement="top" title="Coming Soon">Rules:</label>
                                        <textarea spellcheck="true"  spellcheck="true"  style="border:2px dotted #999;" class="form-control" id="logic" name="" placeholder="Enter Form note here" data-toggle="tooltip" data-placement="right" title="Coming Soon"></textarea>
                                    </div>
                                </div> --}}

                                <div class="col-">
                                    <button style="display:none" type="submit" name="submit" class="mt-3 primary-btn" id="submit-btn">Save
                                        Form</button>
                                </div>
                            </div>

                        </form>


                        <script>

// function editEvent(eventId, currentTimes, currentRepeatState) {
//     const tableRow = document.querySelector(`tr[data-event-id="${eventId}"]`);

//     if (tableRow) {
//         const timesCell = tableRow.querySelector('.event-times');
//         const repeatCell = tableRow.querySelector('.event-repeat');

//         // Create the times select dropdown
//         const timesOptions = JSON.parse(currentTimes).map(time => `<option>${time}</option>`).join('');
//         const timesSelect = `<select class="form-control" style="width: 5rem;">${timesOptions}</select>`;

//         // Create the repeat state input
//         const repeatInput = `<input type="text" class="form-control" value="${currentRepeatState}" />`;

//         // Replace cell content with input fields
//         timesCell.innerHTML = timesSelect;
//         repeatCell.innerHTML = repeatInput;

//         // Add save and cancel buttons
//         const saveButton = `<button class="btn btn-success" onclick="saveEdit(${eventId}, this)">Save</button>`;
//         const cancelButton = `<button class="btn btn-danger" onclick="cancelEdit(${eventId})">Cancel</button>`;
//         const actionCell = tableRow.querySelector('td:last-child');
//         actionCell.innerHTML = saveButton + ' ' + cancelButton;
//     }
// }

function saveEdit(eventId, button) {
    const tableRow = button.closest('tr');
    const timesSelect = tableRow.querySelector('td:nth-child(3) select');
    const repeatInput = tableRow.querySelector('td:nth-child(4) input');

    const updatedTimes = timesSelect.value;
    const updatedRepeatState = repeatInput.value;

    // AJAX call to save the event
    fetch(`/route/to/save/event/${eventId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Ensure you include this for CSRF protection
        },
        body: JSON.stringify({
            times: updatedTimes,
            repeat_state: updatedRepeatState
        })
    })
    .then(response => response.json())
    .then(data => {
        // Update the table row with the new values
        timesSelect.outerHTML = `<span class="event-times">${updatedTimes}</span>`;
        repeatInput.outerHTML = `<span class="event-repeat">${updatedRepeatState}</span>`;

        // Restore the edit icon
        const editIcon = `<a href="javascript:void(0);" onclick="editEvent(${eventId}, '${updatedTimes}', '${updatedRepeatState}')" class="text-info">
                            <i class="fa-solid fa-pen-to-square"></i>
                          </a>`;
        tableRow.querySelector('td:last-child').innerHTML = editIcon;
    })
    .catch(error => {
        console.error('Error saving event:', error);
        // Optionally handle error feedback here
    });
}

function cancelEdit(eventId) {
    const tableRow = document.querySelector(`tr[data-event-id="${eventId}"]`);
    if (tableRow) {
        const originalTimes = tableRow.dataset.originalTimes;
        const originalRepeat = tableRow.dataset.originalRepeat;

        // Reset to original display
        tableRow.querySelector('.event-times').innerHTML = `<span class="event-times">${originalTimes}</span>`;
        tableRow.querySelector('.event-repeat').innerHTML = `<span class="event-repeat">${originalRepeat}</span>`;

        // Restore the edit icon
        const editIcon = `<a href="javascript:void(0);" onclick="editEvent(${eventId}, '${originalTimes}', '${originalRepeat}')" class="text-info">
                            <i class="fa-solid fa-pen-to-square"></i>
                          </a>`;
        tableRow.querySelector('td:last-child').innerHTML = editIcon;
    }
}

                        </script>
                        <div class="form-links-wrapper">
                            <a data-toggle="tooltip" data-placement="top" title="Coming Soon" class="light-btn"
                                style="cursor:not-allowed;"><svg width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21 21H4.6C4.03995 21 3.75992 21 3.54601 20.891C3.35785 20.7951 3.20487 20.6422 3.10899 20.454C3 20.2401 3 19.9601 3 19.4V3M20 8L16.0811 12.1827C15.9326 12.3412 15.8584 12.4204 15.7688 12.4614C15.6897 12.4976 15.6026 12.5125 15.516 12.5047C15.4179 12.4958 15.3215 12.4458 15.1287 12.3457L11.8713 10.6543C11.6785 10.5542 11.5821 10.5042 11.484 10.4953C11.3974 10.4875 11.3103 10.5024 11.2312 10.5386C11.1416 10.5796 11.0674 10.6588 10.9189 10.8173L7 15"
                                        stroke="#6bc1b7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Stats</a>
                            @if (isset($form))
                                @if (isset($form->form_json) && !empty(json_decode($form->form_json, true)))
                                    <a class="light-btn" href="/bespoke_form_v3/#!/preview/{{ $form->id }}"><svg
                                            width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.42012 12.7132C2.28394 12.4975 2.21584 12.3897 2.17772 12.2234C2.14909 12.0985 2.14909 11.9015 2.17772 11.7766C2.21584 11.6103 2.28394 11.5025 2.42012 11.2868C3.54553 9.50484 6.8954 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7766C21.8517 11.9015 21.8517 12.0985 21.8231 12.2234C21.785 12.3897 21.7169 12.4975 21.5807 12.7132C20.4553 14.4952 17.1054 19 12.0004 19C6.8954 19 3.54553 14.4952 2.42012 12.7132Z"
                                                stroke="black" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M12.0004 15C13.6573 15 15.0004 13.6569 15.0004 12C15.0004 10.3431 13.6573 9 12.0004 9C10.3435 9 9.0004 10.3431 9.0004 12C9.0004 13.6569 10.3435 15 12.0004 15Z"
                                                stroke="black" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    Preview</a>
                                @endif

                                {{-- <a href="{{route('head_office.be_spoke_form.form_test',$form->id)}}" class="outline-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 21.4998L8.04927 19.3655C8.40421 19.229 8.58168 19.1607 8.74772 19.0716C8.8952 18.9924 9.0358 18.901 9.16804 18.7984C9.31692 18.6829 9.45137 18.5484 9.72028 18.2795L21 6.99982C22.1046 5.89525 22.1046 4.10438 21 2.99981C19.8955 1.89525 18.1046 1.89524 17 2.99981L5.72028 14.2795C5.45138 14.5484 5.31692 14.6829 5.20139 14.8318C5.09877 14.964 5.0074 15.1046 4.92823 15.2521C4.83911 15.4181 4.77085 15.5956 4.63433 15.9506L2.5 21.4998ZM2.5 21.4998L4.55812 16.1488C4.7054 15.7659 4.77903 15.5744 4.90534 15.4867C5.01572 15.4101 5.1523 15.3811 5.2843 15.4063C5.43533 15.4351 5.58038 15.5802 5.87048 15.8703L8.12957 18.1294C8.41967 18.4195 8.56472 18.5645 8.59356 18.7155C8.61877 18.8475 8.58979 18.9841 8.51314 19.0945C8.42545 19.2208 8.23399 19.2944 7.85107 19.4417L2.5 21.4998Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                
                                Edit form</a> --}}
                                {{-- @if ($form->submitable_to_nhs_lfpse == 0) --}}
                                    <a href="/bespoke_form_v3/#!/form/{{ $form->id }}?ho={{$head_office->id}}" class="outline-btn"><svg
                                            width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.5 21.4998L8.04927 19.3655C8.40421 19.229 8.58168 19.1607 8.74772 19.0716C8.8952 18.9924 9.0358 18.901 9.16804 18.7984C9.31692 18.6829 9.45137 18.5484 9.72028 18.2795L21 6.99982C22.1046 5.89525 22.1046 4.10438 21 2.99981C19.8955 1.89525 18.1046 1.89524 17 2.99981L5.72028 14.2795C5.45138 14.5484 5.31692 14.6829 5.20139 14.8318C5.09877 14.964 5.0074 15.1046 4.92823 15.2521C4.83911 15.4181 4.77085 15.5956 4.63433 15.9506L2.5 21.4998ZM2.5 21.4998L4.55812 16.1488C4.7054 15.7659 4.77903 15.5744 4.90534 15.4867C5.01572 15.4101 5.1523 15.3811 5.2843 15.4063C5.43533 15.4351 5.58038 15.5802 5.87048 15.8703L8.12957 18.1294C8.41967 18.4195 8.56472 18.5645 8.59356 18.7155C8.61877 18.8475 8.58979 18.9841 8.51314 19.0945C8.42545 19.2208 8.23399 19.2944 7.85107 19.4417L2.5 21.4998Z"
                                                stroke="black" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>

                                        Edit form</a>
                                {{-- @endif --}}
                            @else
                                <button class="btn m-0  outline-btn" id="create_form">Create form</button>
                            @endif

                            <button class="btn m-0  primary-btn" id="save_form"><svg width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7 3V6.4C7 6.96005 7 7.24008 7.10899 7.45399C7.20487 7.64215 7.35785 7.79513 7.54601 7.89101C7.75992 8 8.03995 8 8.6 8H15.4C15.9601 8 16.2401 8 16.454 7.89101C16.6422 7.79513 16.7951 7.64215 16.891 7.45399C17 7.24008 17 6.96005 17 6.4V4M17 21V14.6C17 14.0399 17 13.7599 16.891 13.546C16.7951 13.3578 16.6422 13.2049 16.454 13.109C16.2401 13 15.9601 13 15.4 13H8.6C8.03995 13 7.75992 13 7.54601 13.109C7.35785 13.2049 7.20487 13.3578 7.10899 13.546C7 13.7599 7 14.0399 7 14.6V21M21 9.32548V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V7.8C3 6.11984 3 5.27976 3.32698 4.63803C3.6146 4.07354 4.07354 3.6146 4.63803 3.32698C5.27976 3 6.11984 3 7.8 3H14.6745C15.1637 3 15.4083 3 15.6385 3.05526C15.8425 3.10425 16.0376 3.18506 16.2166 3.29472C16.4184 3.4184 16.5914 3.59135 16.9373 3.93726L20.0627 7.06274C20.4086 7.40865 20.5816 7.5816 20.7053 7.78343C20.8149 7.96237 20.8957 8.15746 20.9447 8.36154C21 8.59171 21 8.8363 21 9.32548Z"
                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Save</button>

                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                    tabindex="0">
                    <!-- Custom Designs starts from here -->
                        {{-- <h4><strong>Please add stages to your form</strong></h4>
                        <form name="fields_form" method="post"
                            action="{{ route('head_office.be_spoke_forms_templates.form_stages_save') }}">
                            @csrf
                            <input type="hidden" name="form_id"
                                value="@if (isset($form)) {{ $form->id }} @endif">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Stage Name</th>
                                            <th>Arrange Question in Groups</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($form->stages as $stage)
                                            <tr>
                                                <td>
                                                    <input class="form-control" type="text" id="stage_name[{{ $stage->id }}]"
                                                        value="{{ $stage->stage_name }}" name="stages[{{ $stage->id }}]"
                                                        placeholder="Enter Form name here" required>
                                                </td>
                                                <td>
                                                    @foreach ($stage->groups as $group)
                                                        <p>
                                                            <span class="group_name">{{ substr($group->group_name, 0, 50) }}</span>
                                                            <a class="text-primary"
                                                                href="{{ route('head_office.be_spoke_forms_templates.form_stage_questions', [$stage->id, $group->id]) }}"><i
                                                                    class="fas fa-address-book"></i> Create Questions</a>
                                                        </p>
                                                    @endforeach
                                                </td>
                                                <td calss="row_icons">
                                                    <a class="btn btn-info toggle_ajax_model" data-bs-toggle="modal"
                                                        data-bs-target="#stage_groups_model" href="#"
                                                        data-href="{{ route('head_office.be_spoke_forms_templates.stage_groups', $stage->id) }}"><i
                                                            class="fas fa-address-book"></i> Stage Groups</a>
                                                    <a class="btn btn-danger delete_stage"
                                                        href="{{ route('head_office.be_spoke_forms_templates.form_stage_delete', $stage->id) }}"><i
                                                            class="fas fa-times"></i> Delete Stage</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>
                                                <label for="stage_name">New Stage Name</label>
                                                <input class="form-control" type="text" id="stage_name" name="stage_name"
                                                    placeholder="Enter Form name here">
                                            </td>
                                            <td calss="row_icons">
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>


                                </table>
                            </div>
                            <div>
                                <button type="submit" name="submit" class="nav-link p-2 btn btn-info inline"><i
                                        class="fas fa-save"></i>
                                    Save Stages</button>
                            </div>

                        </form> --}}
                        <br>
                        <div class="if_case_manager_checked">
                            <div class="car" id="collapseCard">
                                <div class="card-body">
                                    <h4 style="font-weight: 400;font-size:30px;color:#48494e;" class="text-center pb-4">
                                        Case Settings</h4>
                                    <ul class="nav nav-tabs justify-content-center " id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation" data-toggle="tooltip"
                                            data-placement="Top" title="Coming Soon">
                                            <button class="nav-link" data-bs-target="#shared_case_approved_email"
                                                type="button" role="tab" aria-controls="shared_case_approved_email"
                                                aria-selected="true">Case Deadline
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="stages_tab"
                                                onclick="changeTabUrl('stages_tab')" data-bs-toggle="tab"
                                                data-bs-target="#stages" type="button" role="tab"
                                                aria-controls="stages" aria-selected="false">
                                                Stages
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="docuemnt_and_template_tab"
                                                onclick="changeTabUrl('docuemnt_and_template_tab')" data-bs-toggle="tab"
                                                data-bs-target="#docuemnt_and_template" type="button" role="tab"
                                                aria-controls="docuemnt_and_template" aria-selected="false">Document &
                                                Template
                                            </button>
                                        </li>


                                        {{-- <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="default_task_tab" data-bs-toggle="tab"
                                                data-bs-target="#default_task" type="button" role="tab"
                                                aria-controls="default_task" aria-selected="false">Default Task
                                            </button>
                                        </li> --}}


                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="links_tab" onclick="changeTabUrl('links_tab')"
                                                data-bs-toggle="tab" data-bs-target="#links-tab" type="button"
                                                role="tab" aria-controls="docuemnt_and_template"
                                                aria-selected="false">Links
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="when_case_closed_tab"
                                                onclick="changeTabUrl('when_case_closed_tab')" data-bs-toggle="tab"
                                                data-bs-target="#when_case_closed" type="button" role="tab"
                                                aria-controls="when_case_closed" aria-selected="false">Automatic Closure
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button onclick="changeTabUrl('review_close_tab')" class="nav-link"
                                                id="review_close_tab" data-bs-toggle="tab" data-bs-target="#review_close"
                                                type="button" role="tab" aria-controls="review_close"
                                                aria-selected="false">Final Approval
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="shared_case_approved_email_tab"
                                                onclick="changeTabUrl('shared_case_approved_email_tab')"
                                                data-bs-toggle="tab" data-bs-target="#shared_case_approved_email"
                                                type="button" role="tab" aria-controls="shared_case_approved_email"
                                                aria-selected="true">Share Case
                                            </button>
                                        </li>
                                        {{-- <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="cards_tab" onclick="changeTabUrl('cards_tab')" data-bs-toggle="tab" data-bs-target="#cards"
                                                type="button" role="tab" aria-controls="cards"
                                                aria-selected="false">Involvements
                                            </button>
                                        </li> --}}
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="case_invest"
                                                onclick="changeTabUrl('case_invest')" data-bs-toggle="tab"
                                                data-bs-target="#case_invest_tab" type="button" role="tab"
                                                aria-controls="cards" aria-selected="false">Case Investigators
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="presentation-t" data-bs-toggle="tab"
                                                onclick="changeTabUrl('presentation-t')"
                                                data-bs-target="#location_timline" type="button" role="tab"
                                                aria-controls="cards" aria-selected="false">Case Summary
                                            </button>
                                        </li>
                                    </ul>
                                    <hr style="border:1px solid #D9D9D9 !important; opacity:1; width:140%;margin-left:-5%;z-index:-1;"
                                        class="mt-0">
                                    <div class="tab-content mt-5" id="myTabContent">
                                        <div class="tab-pane fade active show" id="stages">
                                            <div style="display: flex; justify-content: center; align-items: center;">
                                                
                                                <div style="position: absolute;right: 40px; cursor: pointer;"
                                                    class="search add_new_stage primary-btn" title="Add new Stage">
                                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M4 18V17.8C4 16.1198 4 15.2798 4.32698 14.638C4.6146 14.0735 5.07354 13.6146 5.63803 13.327C6.27976 13 7.11984 13 8.8 13H15.2C16.8802 13 17.7202 13 18.362 13.327C18.9265 13.6146 19.3854 14.0735 19.673 14.638C20 15.2798 20 16.1198 20 17.8V18M4 18C2.89543 18 2 18.8954 2 20C2 21.1046 2.89543 22 4 22C5.10457 22 6 21.1046 6 20C6 18.8954 5.10457 18 4 18ZM20 18C18.8954 18 18 18.8954 18 20C18 21.1046 18.8954 22 20 22C21.1046 22 22 21.1046 22 20C22 18.8954 21.1046 18 20 18ZM12 18C10.8954 18 10 18.8954 10 20C10 21.1046 10.8954 22 12 22C13.1046 22 14 21.1046 14 20C14 18.8954 13.1046 18 12 18ZM12 18V8M6 8H18C18.9319 8 19.3978 8 19.7654 7.84776C20.2554 7.64477 20.6448 7.25542 20.8478 6.76537C21 6.39782 21 5.93188 21 5C21 4.06812 21 3.60218 20.8478 3.23463C20.6448 2.74458 20.2554 2.35523 19.7654 2.15224C19.3978 2 18.9319 2 18 2H6C5.06812 2 4.60218 2 4.23463 2.15224C3.74458 2.35523 3.35523 2.74458 3.15224 3.23463C3 3.60218 3 4.06812 3 5C3 5.93188 3 6.39782 3.15224 6.76537C3.35523 7.25542 3.74458 7.64477 4.23463 7.84776C4.60218 8 5.06812 8 6 8Z"
                                                            stroke="black" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    New stage
                                                </div>
                                            </div>

                                            <div class="form-page-contents ">
                                                <div class="timeline timeline_nearmiss">
                                                    @if (isset($form))
                                                    @foreach ($form->default_stages()->orderBy('label')->get() as $counter => $stage)
                                                        <div class="stage_dragable stage_dropable"
                                                            data-label="{{ $stage->label }}"
                                                            data-wrapperid="{{ $stage->id }}">
                                                            <div class="line line-date stage-{{ $stage->id }}"
                                                                data-stageid="{{ $stage->id }}">
                                                                <div data-toggle="tooltip" data-bs-placement="left"
                                                                    title="Drag and drop to rearrange order"
                                                                    class="timeline-label stage-time-line"
                                                                    style="display: flex;cursor:move;">
                                                                    <div class="  stage-btn-open"
                                                                        data-id="{{ $stage->id }}"
                                                                        onclick="$('.btn-delete-stage').attr('data-id',{{ $stage->id }})">
                                                                        <div id="stage-label-name-{{ $stage->id }}">
                                                                            {{ $stage->name }}</div>
                                                                    </div>
                                                                    <div class="user-avatar-wrapper">
                                                                        @php
                                                                            $data = json_decode(
                                                                                $stage->stage_rules,
                                                                                true,
                                                                            );
                                                                            $startedData =
                                                                                isset($data['started']) &&
                                                                                !empty($data['started'])
                                                                                    ? $data['started']
                                                                                    : null;
                                                                            $completedData =
                                                                                isset($data['completed']) &&
                                                                                !empty($data['completed'])
                                                                                    ? $data['completed']
                                                                                    : null;
                                                                            $matchingRecords = [];

                                                                            if (isset($startedData)) {
                                                                                foreach ($startedData as $singleRule) {
                                                                                    if (
                                                                                        $singleRule['condition_type'] ==
                                                                                        1
                                                                                    ) {
                                                                                        if (isset($completedData)) {
                                                                                            $filteredArray = array_filter(
                                                                                                $completedData,
                                                                                                function ($item) {
                                                                                                    return isset(
                                                                                                        $item[
                                                                                                            'condition_type'
                                                                                                        ],
                                                                                                    ) &&
                                                                                                        $item[
                                                                                                            'condition_type'
                                                                                                        ] == 1;
                                                                                                },
                                                                                            );
                                                                                        }
                                                                                        $unique_profiles = [];

                                                                                        if (
                                                                                            isset($filteredArray) &&
                                                                                            !empty($filteredArray)
                                                                                        ) {
                                                                                            foreach (
                                                                                                $filteredArray
                                                                                                as $item
                                                                                            ) {
                                                                                                $unique_profiles = array_merge(
                                                                                                    $unique_profiles,
                                                                                                    array_diff(
                                                                                                        $singleRule[
                                                                                                            'user_profiles'
                                                                                                        ],
                                                                                                        $item[
                                                                                                            'user_profiles'
                                                                                                        ],
                                                                                                    ),
                                                                                                );
                                                                                            }

                                                                                            $unique_profiles = array_unique(
                                                                                                $unique_profiles,
                                                                                            );
                                                                                        } else {
                                                                                            $unique_profiles =
                                                                                                $singleRule[
                                                                                                    'user_profiles'
                                                                                                ];
                                                                                        }
                                                                                        // Retrieve matching records for condition type 1
                                                                                        $matchedProfiles = $form->form_owner
                                                                                            ->head_office_user_profiles()
                                                                                            ->whereIn(
                                                                                                'id',
                                                                                                $unique_profiles,
                                                                                            )
                                                                                            ->get();
                                                                                        foreach (
                                                                                            $matchedProfiles
                                                                                            as $profile
                                                                                        ) {
                                                                                            foreach (
                                                                                                $profile->user_profile_assign
                                                                                                as $assign
                                                                                            ) {
                                                                                                if (isset($assign->head_office_user->user)) {
                                                                                                $matchingRecords[] = [
                                                                                                    'condition_type' => $singleRule['condition_type'],
                                                                                                    'data' => $assign->head_office_user->user,
                                                                                                ];
                                                                                            }
                                                                                            }
                                                                                        }
                                                                                    } elseif (
                                                                                        $singleRule['condition_type'] ==
                                                                                        2
                                                                                    ) {
                                                                                        if (isset($completedData)) {
                                                                                            $filteredArrayUsers = array_filter(
                                                                                                $completedData,
                                                                                                function ($item) {
                                                                                                    return isset(
                                                                                                        $item[
                                                                                                            'condition_type'
                                                                                                        ],
                                                                                                    ) &&
                                                                                                        $item[
                                                                                                            'condition_type'
                                                                                                        ] == 2;
                                                                                                },
                                                                                            );
                                                                                        }

                                                                                        $unique_users = [];
                                                                                        if (
                                                                                            isset(
                                                                                                $filteredArrayUsers,
                                                                                            ) &&
                                                                                            !empty($filteredArrayUsers)
                                                                                        ) {
                                                                                            foreach (
                                                                                                $filteredArrayUsers
                                                                                                as $item
                                                                                            ) {
                                                                                                $unique_users = array_merge(
                                                                                                    $unique_users,
                                                                                                    array_diff(
                                                                                                        $singleRule[
                                                                                                            'users'
                                                                                                        ],
                                                                                                        $item['users'],
                                                                                                    ),
                                                                                                );
                                                                                            }

                                                                                            $unique_users = array_unique(
                                                                                                $unique_users,
                                                                                            );
                                                                                        } else {
                                                                                            $unique_users =
                                                                                                $singleRule['users'];
                                                                                        }
                                                                                        // Retrieve matching users for condition type 2
                                                                                        $matchedUsers = $form
                                                                                            ->usersToDisplay()
                                                                                            ->whereIn(
                                                                                                'id',
                                                                                                $unique_users,
                                                                                            );

                                                                                        foreach (
                                                                                            $matchedUsers
                                                                                            as $user
                                                                                        ) {
                                                                                            $matchingRecords[] = [
                                                                                                'condition_type' =>
                                                                                                    $singleRule[
                                                                                                        'condition_type'
                                                                                                    ],
                                                                                                'data' => $user,
                                                                                            ];
                                                                                        }
                                                                                    } elseif (
                                                                                        $singleRule['condition_type'] ==
                                                                                            3 &&
                                                                                        $singleRule[
                                                                                            'email_user_type'
                                                                                        ] == 2
                                                                                    ) {
                                                                                        // Retrieve matching records for condition type 3 and email_user_type 2
                                                                                        $matchedProfiles = $form->form_owner
                                                                                            ->head_office_user_profiles()
                                                                                            ->whereIn(
                                                                                                'id',
                                                                                                $singleRule[
                                                                                                    'user_profiles'
                                                                                                ],
                                                                                            )
                                                                                            ->get();

                                                                                        foreach (
                                                                                            $matchedProfiles
                                                                                            as $profile
                                                                                        ) {
                                                                                            foreach (
                                                                                                $profile->user_profile_assign
                                                                                                as $assign
                                                                                            ) {
                                                                                                foreach (
                                                                                                    $assign->head_office_user->get()
                                                                                                    as $user
                                                                                                ) {
                                                                                                    $matchingRecords[] = [
                                                                                                        'condition_type' =>
                                                                                                            $singleRule[
                                                                                                                'condition_type'
                                                                                                            ],
                                                                                                        'data' =>
                                                                                                            $user->user,
                                                                                                    ];
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } elseif (
                                                                                        $singleRule['condition_type'] ==
                                                                                            3 &&
                                                                                        $singleRule[
                                                                                            'email_user_type'
                                                                                        ] == 1
                                                                                    ) {
                                                                                        // Retrieve matching users for condition type 3 and email_user_type 1
                                                                                        $matchedUsers = $form
                                                                                            ->usersToDisplay()
                                                                                            ->whereIn(
                                                                                                'id',
                                                                                                $singleRule['users'],
                                                                                            );

                                                                                        foreach (
                                                                                            $matchedUsers
                                                                                            as $user
                                                                                        ) {
                                                                                            $matchingRecords[] = [
                                                                                                'condition_type' =>
                                                                                                    $singleRule[
                                                                                                        'condition_type'
                                                                                                    ],
                                                                                                'data' => $user,
                                                                                            ];
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }

                                                                        @endphp
                                                                        @if (!empty($matchingRecords))
                                                                            @php $count = 0; @endphp
                                                                            @foreach ($matchingRecords as $record)
                                                                                @if ($count < 2)
                                                                                    @php
                                                                                        $ho_u_new = $head_office->users
                                                                                            ->where(
                                                                                                'user_id',
                                                                                                $record['data']->id,
                                                                                            )
                                                                                            ->first()->user;
                                                                                    @endphp
                                                                                    <div style="cursor:pointer;"
                                                                                        class="user-icon-circle new-card-wrap"
                                                                                        title="{{ $record['data']->name }}">
                                                                                        @if (isset($record['data']->logo))
                                                                                            <img src="{{ $record['data']->logo }}"
                                                                                                alt="png_img"
                                                                                                style="width: 30px; height: 30px; border-radius: 50%;">
                                                                                        @else
                                                                                            <div class="user-img-placeholder"
                                                                                                id="user-img-place"
                                                                                                style="width: 30px; height: 30px;">
                                                                                                {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $record['data']->name))) }}
                                                                                            </div>
                                                                                        @endif
                                                                                        @if (isset($user))
                                                                                            @include(
                                                                                                'head_office.user_card_component',
                                                                                                [
                                                                                                    'user' => $ho_u_new,
                                                                                                ]
                                                                                            )
                                                                                        @endif
                                                                                    </div>
                                                                                    @php $count++; @endphp
                                                                                @endif
                                                                            @endforeach

                                                                            @if (count($matchingRecords) > 2)
                                                                                <button class="avatar-counter-btn"
                                                                                    onclick="displayUsersInfo({{ $stage->id }})">+{{ count($matchingRecords) - 2 }}</button>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-outline-info new-btn"
                                                                    data-bs-toggle="collapse"
                                                                    style="transform: translate(50px, 13px); text-wrap:nowrap;"
                                                                    data-bs-target="#stage_{{ $stage->id }}">New
                                                                    Task</button>
                                                                <div class="action-bar card card-qi" style="left:187px">
                                                                    <span class="edit_stage">
                                                                        <img src="{{ asset('v2/images/icons/edit-03.svg') }}"
                                                                            alt="">
                                                                    </span>
                                                                    <span class="stage_delete">
                                                                        <img src="{{ asset('v2/images/icons/trash.svg') }}"
                                                                            alt="">
                                                                    </span>
                                                                    <span data-bs-toggle="collapse"
                                                                        data-bs-target="#stage_{{ $stage->id }}">
                                                                        <img src="{{ asset('v2/images/icons/plus.svg') }}"
                                                                            alt="">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @if (!$stage->default_tasks()->count())
                                                                <div class="line stage-line dragable_line nearmiss_hidden right-record nearmiss_record nearmiss_status_active draggable droppable"
                                                                    data-default_case_stage_id="{{ $stage->id }}">
                                                                </div>
                                                                {{-- <div class="line stage-line nearmiss_hidden right-record nearmiss_record nearmiss_stage_{{$stage->id}} nearmiss_status_active">
                                                        <div class="content-timeline">
                                                            <div class="actions" style="display:none"> --}}
                                                                {{-- <a href="javascript:void(0)" title="Edit" class="" >
                                                                    <img src="{{asset('v2/images/icons/edit-03.svg')}}" alt="">
                                                                </a> --}}
                                                                {{-- </div>
                                                            No Task found
                                                        </div>
                                                    </div> --}}
                                                            @else
                                                                @foreach ($stage->default_tasks()->orderBy('label')->get() as $key => $task)
                                                                    <div class="custom-mess-wrap">
                                                                        <button class="btn btn-outline-info custom-mess"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#stage_{{ $stage->id }}">New
                                                                            Task</button>
                                                                    </div>
                                                                    <div style="width:50% !important;"
                                                                        class="line dragable_line stage-line nearmiss_hidden right-record nearmiss_record nearmiss_stage_{{ $task->id }} nearmiss_status_active draggable droppable"
                                                                        data-default_case_stage_id="{{ $task->default_case_stage_id }}"
                                                                        data-label="{{ $task->label }}"
                                                                        data-task="{{ $key }}">
                                                                        <div class="content-timeline"
                                                                            data-task_id="{{ $task->id }}">
                                                                            <div class="actions" style="display:none">
                                                                                <a href="javascript:void(0)"
                                                                                    title="Edit" class=""
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#task_{{ $task->id }}">
                                                                                    <img src="{{ asset('v2/images/icons/edit-03.svg') }}"
                                                                                        alt="">
                                                                                </a>
                                                                            </div>
                                                                            @php $tasksUsers = isset($task->type_ids) ? json_decode($task->type_ids) : null;  @endphp
                                                                            @if (isset($tasksUsers) && !empty($tasksUsers))
                                                                                <div class="actions2 d-flex">
                                                                                    @php $count = 0; @endphp
                                                                                    @foreach ($tasksUsers as $task_user)
                                                                                        @php $task_selected_user = App\Models\User::find($task_user)  @endphp
                                                                                        @if ($count < 2)
                                                                                            <div class="user-icon-circle"
                                                                                                title="{{ $task_selected_user->name }}">
                                                                                                @if (isset($task_selected_user->logo))
                                                                                                    <img src="{{ $task_selected_user->logo }}"
                                                                                                        alt="png_img"
                                                                                                        style="width: 30px; height: 30px; border-radius: 50%;">
                                                                                                @else
                                                                                                    <div class="user-img-placeholder"
                                                                                                        id="user-img-place"
                                                                                                        style="width: 30px; height: 30px;">
                                                                                                        {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $task_selected_user->name))) }}
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                            @php $count++; @endphp
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @if (count($tasksUsers) > 2)
                                                                                        <button
                                                                                            class="avatar-counter-btn">+{{ count($tasksUsers) - 2 }}</button>
                                                                                    @endif
                                                                                </div>
                                                                            @endif
                                                                            <h2 class="timeline_category_title">
                                                                                <span
                                                                                    class="timeline_what_was_error_title well_title">{{ $task->title }}</span>
                                                                            </h2>
                                                                            <p class="mb-0 text-body-tertiary" >
                                                                                <span class="detail-title"> Description: </span>
                                                                                <div style="text-body-tertiary">
                                                                                    {!! $task->description !!}
                                                                                </div>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    @if ($loop->last)
                                                                        <div class="custom-mess-wrap">
                                                                            <button
                                                                                class="btn btn-outline-info custom-mess"
                                                                                data-bs-toggle="collapse"
                                                                                data-bs-target="#stage_{{ $stage->id }}">New
                                                                                Task</button>
                                                                        </div>
                                                                    @endif
                                                                    @include(
                                                                        'head_office.be_spoke_forms.default_case_stage_task',
                                                                        ['stage' => $stage, 'task' => $task]
                                                                    )
                                                                @endforeach
                                                            @endif

                                                            @include(
                                                                'head_office.be_spoke_forms.default_case_stage_task',
                                                                ['stage' => $stage, 'task' => null]
                                                            )
                                                        </div>
                                                    @endforeach

                                                    @endif
                                                    <div class=" right-sidebar-settings custom-scroll"
                                                        id="stage-side-wrapper" style="display:none;">
                                                        <div onclick="$('#stage-side-wrapper').fadeOut()"
                                                            class="position-fixed"
                                                            style="top: 0;left:0;width:100%;height:100%;background:rgba(0, 0, 0, 0.1);">
                                                        </div>
                                                        <div class="card" style="min-height: 99%;z-index:110;"
                                                            onclick="function(event) { event.stopPropagation(); }">
                                                            <div class="card-body">

                                                                <h3 class="text-center text-success">Stage</h3>
                                                                <div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <p class="mb-0">Info</p>

                                                                    </div>
                                                                    <div class="mb-3 d-flex align-items-center gap-2">
                                                                        <label for="StageName"
                                                                            class="form-label fw-bold mb-0">Name</label>
                                                                        <input  type="text" class="form-control"
                                                                            id="StageName" data-id=""
                                                                            placeholder="Please enter stage name"
                                                                            value="">
                                                                    </div>
                                                                    <p class="">Logic</p>
                                                                    @livewire('case-logic')
                                                                </div>
                                                                <div class="text-end">
                                                                    <button type="submit" class="btn text-black fw-bold" onclick="$('#stage-side-wrapper').fadeOut()">Save</button>
                                                                    <button type="button" class="btn fw-bold btn-delete-stage" id="btn-delete-stage" data-id="{{isset($stage) ? $stage->id : ''}}">Delete</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="shared_case_approved_email" role="tabpanel"
                                            aria-labelledby="shared_case_approved_email-tab">
                                            <div class="row">
                                                {{--  <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3 class="text-center text-info h3 font-weight-bold">Shared Case Approved Email</h3>
                                                        <form action="{{route('share_emails.share_email.store',$form->id)}}" method="POST">
                                                            <input type="hidden" name="_token" value="Uz9Kj0nCjX1BelgaUcJTdVforFjLF79lJW31VUe9">
                                                            <div class="form-group">
                                                                <label for="email"></label>
                                                                <input type="email" id="email" name="shared_case_approved_email" placeholder="Shared Case Approved Email" class="form-control" required="">
                                                            </div>
                                                            <div class="form-group">
                                                                <button class="btn btn-info" type="submit" name="submit">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div> --}}
                                                <div class="col-md-12">
                                                    <div class="car ms-3">
                                                        <div class="card-bod">
                                                            <h3 class="h3 font-weight-bold mb-3" style="color: #48494E;">

                                                                <span style="float: right">


                                                                    <a href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#share_case" class="primary-btn"
                                                                        style="font-size: 16px;">
                                                                        <svg width="20" height="20"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M12 8V16M8 12H16M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                                                                stroke="black" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>

                                                                        Add New
                                                                    </a>
                                                                </span>
                                                            </h3>

                                                            <div>
                                                                <input type="checkbox"
                                                                    @if (isset($form) && $form->is_allow_non_approved_emails) checked @endif
                                                                    name="is_allow_non_approved_emails"
                                                                    id="is_allow_non_approved_emails">
                                                                <label for="">
                                                                    Allow user to share case with non-approved emails
                                                                </label>
                                                            </div>
                                                            <table border="0" id="scheduleTable"
                                                                class="table table-responsive table_full_width new-table"
                                                                style="width: 100% !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Emails</th>
                                                                        <th>Description</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($form))
                                                                    @foreach ($form->shared_case_approved_emails as $shared_case_approved_email)
                                                                        <tr>
                                                                            <td>
                                                                                {{ $shared_case_approved_email->email }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $shared_case_approved_email->description }}
                                                                            </td>
                                                                            <td>
                                                                                <div class="actions-wrap">

                                                                                    <a style="color: rgba(0, 0, 0, 0.6)"
                                                                                        class=""
                                                                                        data-msg="Are you sure, you want to delete this email?"
                                                                                        href="{!! route('share_emails.share_email.delete', ['id'=>$shared_case_approved_email->id,'_token'=>csrf_token()]) !!}"><i
                                                                                            class="fas fa-trash"></i></a>

                                                                                    <a style="color: rgba(0, 0, 0, 0.6)"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#share_case_{{ $shared_case_approved_email->id }}"
                                                                                        href="#"><i
                                                                                            class="fas fa-wrench"></i></a>
                                                                                </div>


                                                                            </td>
                                                                        </tr>
                                                                        @include(
                                                                            'head_office.be_spoke_forms.share_case_approved_email',
                                                                            [
                                                                                'shared_case_approved_email' => $shared_case_approved_email,
                                                                            ]
                                                                        )
                                                                    @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="default_task" role="tabpanel"
                                            aria-labelledby="default_task-tab">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="card">
                                                        <div class="card-body">

                                                            <h3 class="text-info h3 font-weight-bold">Default Tasks <a
                                                                    style="float: right !important;" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#default_task_form"
                                                                    class="btn btn-info">Add New Task</a></h3>

                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="dataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Task Title</th>
                                                                            <th>Task Description</th>
                                                                            <th>Assign Type</th>
                                                                            <th>Assign To</th>

                                                                            <th>Start Date</th>

                                                                            <th>Deadline Duration</th>
                                                                            <th>Deadline Users</th>


                                                                            <th>Overdue Type</th>
                                                                            <th>Overdue Users</th>


                                                                            <th>Files</th>
                                                                            <th>Action</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (isset($form))
                                                                        @foreach ($form->defaultTasks as $task)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $task->title }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $task->description }}
                                                                                </td>

                                                                                <td>
                                                                                    @if (!$task->type)
                                                                                        Users
                                                                                    @elseif($task->type == 1)
                                                                                        Profiles
                                                                                    @else
                                                                                        Leave Unassigned
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    {{ $task->profiles ? $task->profiles : 'Leave Unassigned' }}
                                                                                </td>

                                                                                <td>
                                                                                    {{ $task->dead_line_start_from }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $task->dead_line_duration }}
                                                                                    {{ $task->dead_line_unit }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $task->dead_line }}
                                                                                </td>

                                                                                <td>
                                                                                    {{ $task->task_over_due_duration }}
                                                                                    {{ $task->task_over_due_unit }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $task->over_due }}
                                                                                </td>

                                                                                <td>
                                                                                    @if (count($task->documents))
                                                                                        <div
                                                                                            class="cm_comment_attachments mt-1">
                                                                                            <ul
                                                                                                class="list-style-none p-0">
                                                                                                @foreach ($task->documents as $doc)
                                                                                                    <li class="relative ">
                                                                                                        <a class="relative @if ($doc->type == 'image') cm_image_link @endif "
                                                                                                            href="{{ route('headoffice.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}"
                                                                                                            target="_blank"><i
                                                                                                                class="fa fa-link"></i>
                                                                                                            {{ $doc->document->original_file_name() }}
                                                                                                            @if ($doc->type == 'image')
                                                                                                                <div
                                                                                                                    class="cm_image_hover">
                                                                                                                    <div
                                                                                                                        class="card shadow">
                                                                                                                        <div
                                                                                                                            class="card-body">
                                                                                                                            <img class="image-responsive"
                                                                                                                                width="300"
                                                                                                                                src="{{ route('headoffice.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}">
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            @endif
                                                                                                        </a>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                    @endif
                                                                                </td>
                                                                                <td calss="row_icons">
                                                                                    <div class="btn-group">
                                                                                        <a class="btn btn-danger delete_task"
                                                                                            href="{{ route('head_office.default_task.default_task_delete', $task->id) }}"><i
                                                                                                class="fas fa-times"></i></a>
                                                                                        <a href="#"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#default_task_form_{{ $task->id }}"
                                                                                            class="btn btn-warning"><i
                                                                                                class="fa fa-wrench"></i></a>
                                                                                    </div>
                                                                                    @include(
                                                                                        'head_office.be_spoke_forms.default_task',
                                                                                        ['task' => $task]
                                                                                    )
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="docuemnt_and_template" role="tabpanel"
                                            aria-labelledby="docuemnt_and_template-tab">
                                            <div class="car">
                                                <div class="card-body">

                                                    <a style="float: right !important;" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#default_document_form"
                                                        class="primary-btn"><svg width="20" height="20"
                                                            viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M20 7V6.8C20 5.11984 20 4.27976 19.673 3.63803C19.3854 3.07354 18.9265 2.6146 18.362 2.32698C17.7202 2 16.8802 2 15.2 2H8.8C7.11984 2 6.27976 2 5.63803 2.32698C5.07354 2.6146 4.6146 3.07354 4.32698 3.63803C4 4.27976 4 5.11984 4 6.8V17.2C4 18.8802 4 19.7202 4.32698 20.362C4.6146 20.9265 5.07354 21.3854 5.63803 21.673C6.27976 22 7.11984 22 8.8 22H12.5M12.5 11H8M11.5 15H8M16 7H8M18 18V12.5C18 11.6716 18.6716 11 19.5 11C20.3284 11 21 11.6716 21 12.5V18C21 19.6569 19.6569 21 18 21C16.3431 21 15 19.6569 15 18V14"
                                                                stroke="black" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        New Document</a>

                                                    <div class="table-responsive">

                                                        @if (isset($form) && !count($form->defaultDocuments))
                                                        <p>There are no uploaded documents</p> 
                                                        
                                                    @else

                                                          <table class="table new-table ms-2" style="margin-top: 4rem; width:100%;"
                                                            id="dataTable_doc" width="100% !important">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Status</th>
                                                                    <th>Description</th>
                                                                    <th>Files</th>
                                                                    <th>Info</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>

                                                           
                                                            <tbody>
                                                                    @if (isset($form))
                                                                    @foreach ($form->defaultDocuments as $key => $document)
                                                                        <tr>

                                                                            <td style="text-wrap:nowrap;">
                                                                                {{ $document->title }}</td>
                                                                            <td>
                                                                                <div
                                                                                    class="live-wrapper {{ $document->active ? '' : 'not-active' }}">
                                                                                    <span class="live-circle"></span>
                                                                                    {{ $document->active == true ? 'Live' : 'Not Live' }}
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                {{ $document->description }}
                                                                            </td>
                                                                            <td>
                                                                                <div class="cm_case_task_attachments mt-1">
                                                                                    <ul class="list-style-none p-0">
                                                                                        @foreach ($document->documents as $doc)
                                                                                            <li class="relative ">
                                                                                                <a class="relative @if ($doc->type == 'image') cm_image_link @endif "
                                                                                                    href="{{ route('headoffice.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}"
                                                                                                    target="_blank"
                                                                                                    style="color: #748cf9 !important;"><i
                                                                                                        class="fa fa-link"></i>
                                                                                                    {{ $doc->document->original_file_name() }}
                                                                                                    @if ($doc->type == 'image')
                                                                                                        <div
                                                                                                            class="cm_image_hover">
                                                                                                            <div
                                                                                                                class="card shadow">
                                                                                                                <div
                                                                                                                    class="card-body">
                                                                                                                    <img class="image-responsive"
                                                                                                                        width="300"
                                                                                                                        src="{{ route('headoffice.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </a>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="d-flex flex-column gap-2 ">
                                                                                    <div class="d-flex align-items-center gap-1"
                                                                                        style="font-size: 14px">
                                                                                        <a href="#"
                                                                                            onclick="preventHash(event)">
                                                                                            <div class="user-icon-circle"
                                                                                                title="User Profile">
                                                                                                @if (isset($document->uploadedByUser->logo))
                                                                                                    <img src="{{ $document->uploadedByUser->logo }}"
                                                                                                        alt="png_img"
                                                                                                        style="width: 30px;height:30px;border-radius:50%;">
                                                                                                @else
                                                                                                    <div class="user-img-placeholder"
                                                                                                        id="user-img-place"
                                                                                                        style="width:30px;height:30px;">
                                                                                                        {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $document->uploadedByUser->name))) }}
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        </a>
                                                                                        Created by
                                                                                        <b>{{ $document->uploadedByUser->name }}
                                                                                        </b>
                                                                                        on
                                                                                        <b>{{ $document->created_at->format('d/m/Y') }}</b>
                                                                                        at
                                                                                        <b>{{ $document->created_at->format('h:i A') }}</b>
                                                                                    </div>
                                                                                    @if (isset($document->updatedByUser))
                                                                                        <div class="d-flex align-items-center gap-1"
                                                                                            style="font-size: 14px">
                                                                                            <a href="#"
                                                                                                onclick="preventHash(event)">
                                                                                                <div class="user-icon-circle"
                                                                                                    title="User Profile">
                                                                                                    @if (isset($document->updatedByUser->logo))
                                                                                                        <img src="{{ $document->updatedByUser->logo }}"
                                                                                                            alt="png_img"
                                                                                                            style="width: 30px;height:30px;border-radius:50%;">
                                                                                                    @else
                                                                                                        <div class="user-img-placeholder"
                                                                                                            id="user-img-place"
                                                                                                            style="width:30px;height:30px;">
                                                                                                            {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $document->updatedByUser->name))) }}
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </a>
                                                                                            Last modified by
                                                                                            <b>{{ $document->updatedByUser->name }}
                                                                                            </b>
                                                                                            on
                                                                                            <b>{{ $document->updated_at->format('d/m/Y') }}</b>
                                                                                            at
                                                                                            <b>{{ $document->updated_at->format('h:i A') }}</b>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="actions-wrap">
                                                                                    <a class="Edit" href="#"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#document_form_{{ $document->id }}">
                                                                                        <svg width="20"
                                                                                            height="20"
                                                                                            style="opacity: 0.6;"
                                                                                            viewBox="0 0 24 24"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M18 10L14 6M2.49997 21.5L5.88434 21.124C6.29783 21.078 6.50457 21.055 6.69782 20.9925C6.86926 20.937 7.03242 20.8586 7.18286 20.7594C7.35242 20.6475 7.49951 20.5005 7.7937 20.2063L21 7C22.1046 5.89543 22.1046 4.10457 21 3C19.8954 1.89543 18.1046 1.89543 17 3L3.7937 16.2063C3.49952 16.5005 3.35242 16.6475 3.24061 16.8171C3.1414 16.9676 3.06298 17.1307 3.00748 17.3022C2.94493 17.4954 2.92195 17.7021 2.87601 18.1156L2.49997 21.5Z"
                                                                                                stroke="black"
                                                                                                stroke-width="2"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                        </svg>


                                                                                    </a>

                                                                                    <a class="delete_form"
                                                                                        href="{{ route('case_docuemnts.case_docuemnt.case_docuemnt_activate', ['id'=>$document->id,'_token'=>csrf_token()]) }}"
                                                                                        data-msg="Are you sure, you want to {{ $document->active ? 'deactivate' : 'activate' }} this Document?">
                                                                                        @if ($document->active)
                                                                                            <svg width="20"
                                                                                                height="20"
                                                                                                title="Deactivate"
                                                                                                style="opacity: 0.6"
                                                                                                viewBox="0 0 24 24"
                                                                                                fill="none"
                                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                                <path
                                                                                                    d="M4.93 4.93L19.07 19.07M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                                                                                    stroke="black"
                                                                                                    stroke-width="2"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round" />
                                                                                            </svg>
                                                                                        @else
                                                                                            <svg width="20"
                                                                                                height="20"
                                                                                                title="Activate"
                                                                                                style="opacity: 0.6"
                                                                                                viewBox="0 0 24 24"
                                                                                                fill="none"
                                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                                <path
                                                                                                    d="M22 11.0857V12.0057C21.9988 14.1621 21.3005 16.2604 20.0093 17.9875C18.7182 19.7147 16.9033 20.9782 14.8354 21.5896C12.7674 22.201 10.5573 22.1276 8.53447 21.3803C6.51168 20.633 4.78465 19.2518 3.61096 17.4428C2.43727 15.6338 1.87979 13.4938 2.02168 11.342C2.16356 9.19029 2.99721 7.14205 4.39828 5.5028C5.79935 3.86354 7.69279 2.72111 9.79619 2.24587C11.8996 1.77063 14.1003 1.98806 16.07 2.86572M22 4L12 14.01L9 11.01"
                                                                                                    stroke="black"
                                                                                                    stroke-width="2"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round" />
                                                                                            </svg>
                                                                                        @endif
                                                                                    </a>

                                                                                    <a class="Edit"
                                                                                        href="{{ route('default_documents.default_document.delete', ['id'=>$document->id,'_token'=>csrf_token()]) }}"
                                                                                        data-msg="Are you sure, you want to delete this Document?">
                                                                                        <svg width="20"
                                                                                            height="20"
                                                                                            style="opacity: 0.6"
                                                                                            viewBox="0 0 24 24"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                                                                stroke="black"
                                                                                                stroke-width="2"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                        </svg>
                                                                                    </a>

                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        @include(
                                                                            'head_office.case_manager.notes.create_documents',
                                                                            ['document' => $document]
                                                                        )
                                                                    @endforeach
                                                                    @endif
                                                                    @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="links-tab" role="tabpanel"
                                            aria-labelledby="docuemnt_and_template-tab">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="car">
                                                        <div class="card-bod">

                                                            <a style="float: right !important;" href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#default_links_form"
                                                                class="btn btn-info primary-btn"><svg width="19"
                                                                    height="19" viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M12.7076 18.3639L11.2933 19.7781C9.34072 21.7308 6.1749 21.7308 4.22228 19.7781C2.26966 17.8255 2.26966 14.6597 4.22228 12.7071L5.63649 11.2929M18.3644 12.7071L19.7786 11.2929C21.7312 9.34024 21.7312 6.17441 19.7786 4.22179C17.826 2.26917 14.6602 2.26917 12.7076 4.22179L11.2933 5.636M8.50045 15.4999L15.5005 8.49994"
                                                                        stroke="black" stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                                New Link</a>

                                                            <div class="table-responsive mt-3">
                                                                <table class="table new-table ms-2"
                                                                    style="margin-top: 4rem;width:50% !important;"
                                                                    id="dataTable_doc1">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Status</th>
                                                                            <th>Description</th>
                                                                            <th>Info</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (isset($form) && !count($form->defaultLinks))
                                                                            <tr>
                                                                                <td>
                                                                                    There are no saved links
                                                                                </td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        @else
                                                                        @if ($form)
                                                                            
                                                                        
                                                                            @foreach ($form->defaultLinks as $key => $document)
                                                                                <tr>

                                                                                    <td style="max-width:250px;">
                                                                                        <p class="m-0" style="word-wrap:break-word;">
                                                                                            {{ $document->link }}        
                                                                                        </p>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="live-wrapper {{ $document->is_active ? '' : 'not-active' }}">
                                                                                            <span
                                                                                                class="live-circle"></span>
                                                                                            {{ $document->is_active == true ? 'Live' : 'Not Live' }}
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ $document->link_description }}
                                                                                    </td>
                                                                                    <td>

                                                                                        <div
                                                                                            class="d-flex flex-column gap-2 ">
                                                                                            @if(isset($document->uploadedByUser))
                                                                                            <div class="d-flex align-items-center gap-1"
                                                                                                style="font-size: 14px">
                                                                                                <a href="#"
                                                                                                    onclick="preventHash(event)">
                                                                                                    <div class="user-icon-circle"
                                                                                                        title="User Profile">
                                                                                                        @if (isset($document->uploadedByUser->logo))
                                                                                                            <img src="{{ $document->uploadedByUser->logo }}"
                                                                                                                alt="png_img"
                                                                                                                style="width: 30px;height:30px;border-radius:50%;">
                                                                                                        @else
                                                                                                            <div class="user-img-placeholder"
                                                                                                                id="user-img-place"
                                                                                                                style="width:30px;height:30px;">
                                                                                                                {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $document->uploadedByUser->name))) }}
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </a>
                                                                                                Created by
                                                                                                <b>{{ $document->uploadedByUser->name }}
                                                                                                </b>
                                                                                                on
                                                                                                <b>{{ $document->created_at->format('d/m/Y') }}</b>
                                                                                                at
                                                                                                <b>{{ $document->created_at->format('h:i A') }}</b>
                                                                                            </div>
                                                                                            @endif
                                                                                            @if (isset($document->updatedByUser))
                                                                                                <div class="d-flex align-items-center gap-1"
                                                                                                    style="font-size: 14px">
                                                                                                    <a href="#"
                                                                                                        onclick="preventHash(event)">
                                                                                                        <div class="user-icon-circle"
                                                                                                            title="User Profile">
                                                                                                            @if (isset($document->updatedByUser->logo))
                                                                                                                <img src="{{ $document->updatedByUser->logo }}"
                                                                                                                    alt="png_img"
                                                                                                                    style="width: 30px;height:30px;border-radius:50%;">
                                                                                                            @else
                                                                                                                <div class="user-img-placeholder"
                                                                                                                    id="user-img-place"
                                                                                                                    style="width:30px;height:30px;">
                                                                                                                    {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $document->updatedByUser->name))) }}
                                                                                                                </div>
                                                                                                            @endif
                                                                                                        </div>
                                                                                                    </a>
                                                                                                    Last modified by
                                                                                                    <b>{{ $document->updatedByUser->name }}
                                                                                                    </b>
                                                                                                    on
                                                                                                    <b>{{ $document->updated_at->format('d/m/Y') }}</b>
                                                                                                    at
                                                                                                    <b>{{ $document->updated_at->format('h:i A') }}</b>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="actions-wrap">
                                                                                            <a class="Edit"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#default_links_form_{{ $document->id }}">
                                                                                                <svg width="20"
                                                                                                    height="20"
                                                                                                    style="opacity: 0.6;"
                                                                                                    viewBox="0 0 24 24"
                                                                                                    fill="none"
                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                    <path
                                                                                                        d="M18 10L14 6M2.49997 21.5L5.88434 21.124C6.29783 21.078 6.50457 21.055 6.69782 20.9925C6.86926 20.937 7.03242 20.8586 7.18286 20.7594C7.35242 20.6475 7.49951 20.5005 7.7937 20.2063L21 7C22.1046 5.89543 22.1046 4.10457 21 3C19.8954 1.89543 18.1046 1.89543 17 3L3.7937 16.2063C3.49952 16.5005 3.35242 16.6475 3.24061 16.8171C3.1414 16.9676 3.06298 17.1307 3.00748 17.3022C2.94493 17.4954 2.92195 17.7021 2.87601 18.1156L2.49997 21.5Z"
                                                                                                        stroke="black"
                                                                                                        stroke-width="2"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round" />
                                                                                                </svg>


                                                                                            </a>

                                                                                            <a class="delete_form"
                                                                                                href="{{ route('default_links.default_link_activate', ['id' => $document->id,'_token'=>csrf_token()]) }}"
                                                                                                data-msg="Are you sure, you want to {{ $document->is_active ? 'deactivate' : 'activate' }} this link?">
                                                                                                @if ($document->is_active)
                                                                                                    <svg width="20"
                                                                                                        height="20"
                                                                                                        title="Deactivate"
                                                                                                        style="opacity: 0.6"
                                                                                                        viewBox="0 0 24 24"
                                                                                                        fill="none"
                                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                                        <path
                                                                                                            d="M4.93 4.93L19.07 19.07M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                                                                                            stroke="black"
                                                                                                            stroke-width="2"
                                                                                                            stroke-linecap="round"
                                                                                                            stroke-linejoin="round" />
                                                                                                    </svg>
                                                                                                @else
                                                                                                    <svg width="20"
                                                                                                        height="20"
                                                                                                        title="Activate"
                                                                                                        style="opacity: 0.6"
                                                                                                        viewBox="0 0 24 24"
                                                                                                        fill="none"
                                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                                        <path
                                                                                                            d="M22 11.0857V12.0057C21.9988 14.1621 21.3005 16.2604 20.0093 17.9875C18.7182 19.7147 16.9033 20.9782 14.8354 21.5896C12.7674 22.201 10.5573 22.1276 8.53447 21.3803C6.51168 20.633 4.78465 19.2518 3.61096 17.4428C2.43727 15.6338 1.87979 13.4938 2.02168 11.342C2.16356 9.19029 2.99721 7.14205 4.39828 5.5028C5.79935 3.86354 7.69279 2.72111 9.79619 2.24587C11.8996 1.77063 14.1003 1.98806 16.07 2.86572M22 4L12 14.01L9 11.01"
                                                                                                            stroke="black"
                                                                                                            stroke-width="2"
                                                                                                            stroke-linecap="round"
                                                                                                            stroke-linejoin="round" />
                                                                                                    </svg>
                                                                                                @endif
                                                                                            </a>

                                                                                            <a class="Edit"
                                                                                                href="{{ route('default_links.default_link_delete', ['id' => $document->id,'_token'=> csrf_token()]) }}"
                                                                                                data-msg="Are you sure, you want to delete this link?">
                                                                                                <svg width="20"
                                                                                                    height="20"
                                                                                                    style="opacity: 0.6"
                                                                                                    viewBox="0 0 24 24"
                                                                                                    fill="none"
                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                    <path
                                                                                                        d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                                                                                        stroke="black"
                                                                                                        stroke-width="2"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round" />
                                                                                                </svg>
                                                                                            </a>

                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                @include(
                                                                                    'head_office.be_spoke_forms.default_links',
                                                                                    ['document' => $document]
                                                                                )
                                                                            @endforeach
                                                                        @endif
                                                                        @endif
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="when_case_closed" role="tabpanel"
                                            aria-labelledby="when_case_closed-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                        <div class="car ms-3">
                                                            <div class="card-bod">
                                                                {{-- <h3 class="text-center h3 font-weight-bold"
                                                                    style="color: #48494E;">When case closed
                                                                </h3> --}}
                                                                @isset($form)
                                                                <form
                                                                    action="{{ route('when_case_closed', $form->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="">
                                                                        <h5 class='fw-bold'>Case Priority Value</h5>
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <input type="checkbox" value="1"
                                                                                @if ($form->is_case_close_priority) checked @endif
                                                                                id="is_case_close_priority"
                                                                                name="is_case_close_priority">
                                                                            <p class="m-0 fw-semibold">Close Case based on
                                                                                case priority value</p>
                                                                        </div>
                                                                        <div class="d-flex align-items-center gap-1">
                                                                            <label
                                                                                class="d-flex align-items-center fw-semibold w-100"
                                                                                for="is_case_close_priority">If
                                                                                <select
                                                                                    style="padding: 0 !important;padding-left: 0.6rem !important;height:calc(0.7em + 0.75rem + 2px);width:140px;"
                                                                                    class='form-select form-select-sm mx-1'
                                                                                    name="case_close_priority_rule"
                                                                                    id="">
                                                                                    <option value="greater"
                                                                                        {{ isset($form->case_close_priority_rule) && $form->case_close_priority_rule == 'greater' ? 'selected' : '' }}>
                                                                                        Greater then</option>
                                                                                    <option value="less"
                                                                                        {{ isset($form->case_close_priority_rule) && $form->case_close_priority_rule == 'less' ? 'selected' : '' }}>
                                                                                        Less then</option>
                                                                                    <option value="equal"
                                                                                        {{ isset($form->case_close_priority_rule) && $form->case_close_priority_rule == 'equal' ? 'selected' : '' }}>
                                                                                        Equal to</option>
                                                                                </select>
                                                                                <input type="number"
                                                                                    oninput="fitSize(event)"
                                                                                    name="case_close_priority_value"
                                                                                    class="form-control custom-number mr-1"
                                                                                    style="margin-right: 0.5rem;"
                                                                                    min="0"
                                                                                    value="{{ isset($form->case_close_priority_value) ? $form->case_close_priority_value : 0 }}">
                                                                                then automatically close this case
                                                                            </label>
                                                                        </div>

                                                                        <div class="my-3 w-75">
                                                                            <label for="close_case_comment"
                                                                                class="form-label fw-semibold">Add Comment
                                                                                to Case Log when case closed:</label>
                                                                            <textarea spellcheck="true"  spellcheck="true"  class="form-control" id="close_case_comment" name="case_close_priority_comment spellcheck" rows="3">{{ isset($form->case_close_priority_comment) ? $form->case_close_priority_comment : '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    {{-- <div class="">
                                                                        <input type="checkbox" value="1"
                                                                            @if ($form->requires_final_approval) checked @endif
                                                                            id="requires_final_approval"
                                                                            name="requires_final_approval">
                                                                        <label class="fw-semibold"
                                                                            for="requires_final_approval">Require final
                                                                            approval</label>
                                                                    </div> --}}

                                                                    <div class="mt-3">
                                                                        <h5 class="fw-bold">Form-Based Rules</h5>
                                                                        <table class="table new-table"
                                                                            style="width: 100% !important;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Page</th>
                                                                                    <th>Question</th>
                                                                                    <th>Rule</th>
                                                                                    <th>Condition</th>
                                                                                    <th>Actions</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @if (!empty($form_json))
                                                                                    @foreach ($form_json['pages'] as $page_index => $page)
                                                                                        @foreach ($page['items'] as $item_index => $item)
                                                                                        @if(isset($item['input']))
                                                                                            @foreach ($item['input']['conditions'] as $condition_index => $condition)
                                                                                                @if ($condition['action_type'] == 'auto_close_case' || $condition['action_type'] == 'donot_auto_close_case')
                                                                                                    <tr>
                                                                                                        <td>{{ $page['name'] }}
                                                                                                        </td>
                                                                                                        <td>{{ $item['label'] }}
                                                                                                        </td>
                                                                                                        <td>{{ $condition['action_type'] == 'auto_close_case' ? 'Auto Close Case Immediately Regardless of Priority Value' : 'Do Not Auto Close Case Regardless of Priority Value' }}
                                                                                                        </td>
                                                                                                        <td>{{ !empty($condition['if_value']) ? $condition['if_value'] : '' }}
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <div
                                                                                                                class="text-center">
                                                                                                                <a class="mx-2"
                                                                                                                    href="{{ route('head_office.be_spoke_form.rule_remove', ['form_id' => $form->id, 'page_id' => $page_index, 'item_id' => $item_index, 'id' => $condition_index,'_token'=>csrf_token()]) }}"><i
                                                                                                                        class="fa-regular fa-trash-can text-danger"></i></a>
                                                                                                                <a
                                                                                                                    href="{{ route('head_office.be_spoke_form.rule_edit', ['form_id' => $form->id, 'page_id' => $page_index, 'item_id' => $item_index, 'id' => $condition_index]) }}"><i
                                                                                                                        class="fa-regular fa-pen-to-square text-info"></i></a>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif
                                                                                        @endforeach
                                                                                    @endforeach
                                                                                @endif
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <button class="primary-btn" type="submit"
                                                                            name="submit"><svg width="19"
                                                                                height="19" viewBox="0 0 24 24"
                                                                                fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M16 12L12 8M12 8L8 12M12 8V17.2C12 18.5907 12 19.2861 12.5505 20.0646C12.9163 20.5819 13.9694 21.2203 14.5972 21.3054C15.5421 21.4334 15.9009 21.2462 16.6186 20.8719C19.8167 19.2036 22 15.8568 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 15.7014 4.01099 18.9331 7 20.6622"
                                                                                    stroke="black" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                            </svg>
                                                                            Update</button>
                                                                    </div>
                                                                </form>
                                                                @endisset
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="review_close" role="tabpanel"
                                            aria-labelledby="review_close">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                        <div class="car ms-3">
                                                            <div class="card-bod">
                                                                @isset($form)
                                                                <form
                                                                    action="{{ route('case_must_review', $form->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="">
                                                                        <input type="checkbox" value="1"
                                                                            @if ($form->requires_final_approval) checked @endif
                                                                            id="case_must_review"
                                                                            name="case_must_review">
                                                                        <label for="case_must_review">This case requires
                                                                            final approval before it is permanently
                                                                            closed</label>
                                                                    </div>
                                                                    <p class="m-0 mt-1 inputSection">Final Approvers</p>
                                                                    @php
                                                                        $case_approvers = $form->get_case_approvers();
                                                                    @endphp

                                                                    @if (isset($case_approvers) && count($case_approvers) > 0)
                                                                        <div class="d-flex align-items-center">
                                                                            @foreach ($case_approvers as $app)
                                                                                @if (isset($app['user']))
                                                                                    <div data-toggle="tooltip"
                                                                                        data-placement="top"
                                                                                        class="user-icon-circle"
                                                                                        title="{{ $app['user']->first_name }} {{ $app['user']->surname }}"
                                                                                        style="margin-left: {{ !$loop->first && count($case_approvers) > 1 ? '-10px' : '' }}">
                                                                                    </div>

                                                                                    <div data-toggle="tooltip"
                                                                                        data-placement="top"
                                                                                        class="user-icon-circle"
                                                                                        title="{{ $app['user']->first_name }} {{ $app['user']->surname }}"
                                                                                        style="margin-left: {{ !$loop->first && count($case_approvers) > 1 ? '-2px' : '' }}">
                                                                                        <div class="user-img-placeholder border"
                                                                                            id="user-img-place"
                                                                                            style="width: 30px; height: 30px; font-size: 13px;">
                                                                                            {{ isset($app['user']->first_name, $app['user']->surname)
                                                                                                ? strtoupper($app['user']->first_name[0]) . strtoupper($app['user']->surname[0])
                                                                                                : '' }}
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <p class="text-muted">No final approvers have been assigned</p>
                                                                    @endif


                                                                    {{-- <div class="d-flex align-items-center">
                                                                        @foreach ($form->form_settings as $sets)
                                                                            @php $location_ids = isset($sets->location_id) ? json_decode($sets->location_id,true) : null; @endphp
                                                                            @if (isset($location_ids))
                                                                                @foreach ($location_ids as $view)
                                                                                    @php
                                                                                        $ho_loc = $head_office
                                                                                            ->locations()
                                                                                            ->find($view);
                                                                                    @endphp
                                                                                    @isset($ho_loc)
                                                                                        <div data-toggle="tooltip"
                                                                                            data-placement="top"
                                                                                            class="user-icon-circle"
                                                                                            title="{{ $ho_loc->location->trading_name }}"
                                                                                            style="margin-left: {{ !$loop->first && count($location_ids) > 1 ? '-10px' : '' }}">
                                                                                            @if ($ho_loc->location->logo)
                                                                                                <img src="{{ $ho_loc->location->logo }}"
                                                                                                    alt="png_img"
                                                                                                    style="width: 30px; height: 30px; border-radius: 50%;">
                                                                                            @else
                                                                                                <div class="user-img-placeholder border"
                                                                                                    id="user-img-place"
                                                                                                    style="width: 30px; height: 30px;font-size:13px;">
                                                                                                    {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $ho_loc->location->trading_name))) .implode('',array_map(function ($word) {return strtoupper($word[1]);}, explode(' ', $ho_loc->location->trading_name))) }}
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    @endisset
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach

                                                                    </div> --}}
                                                                    <?php $hasConditions = false; ?>

                                                                    {{-- @if (!empty($form_json))
                                                                        @foreach ($form_json['pages'] as $page_index => $page)
                                                                            @foreach ($page['items'] as $item_index => $item)
                                                                                @foreach ($item['input']['conditions'] as $condition_index => $condition)
                                                                                    @if ($condition['action_type'] == 'auto_close_case' || $condition['action_type'] == 'donot_auto_close_case')
                                                                                        <?php $hasConditions = true; ?>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endforeach
                                                                        @endforeach
                                                                    @endif --}}

                                                                    <p class="inputSection m-0">Logic</p>
                                                                    @if (!empty($form_json) && isset($form_json['pages']))
                                                                        <div class="mt-1"> <!-- Changed from mt-3 to mt-1 -->
                                                                            <h5 class="fw-bold">Form-Based Rules</h5>
                                                                            <table class="table new-table"
                                                                                style="width: 100% !important;">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Page</th>
                                                                                        <th>Question</th>
                                                                                        <th>Rule</th>
                                                                                        <th>Condition</th>
                                                                                        <th>Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                    @foreach ($form_json['pages'] as $page_index => $page)
                                                                                        @foreach ($page['items'] as $item_index => $item)
                                                                                        @if (isset($item['input']))
                                                                                            @foreach ($item['input']['conditions'] as $condition_index => $condition)
                                                                                                @if ($condition['action_type'] == 'auto_close_case' || $condition['action_type'] == 'donot_auto_close_case')
                                                                                                    <tr>
                                                                                                        <td>{{ $page['name'] }}
                                                                                                        </td>
                                                                                                        <td>{{ $item['label'] }}
                                                                                                        </td>
                                                                                                        <td>{{ $condition['action_type'] == 'auto_close_case' ? 'Auto Close Case Immediately Regardless of Priority Value' : 'Do Not Auto Close Case Regardless of Priority Value' }}
                                                                                                        </td>
                                                                                                        <td>{{ !empty($condition['if_value']) ? $condition['if_value'] : '' }}
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <div
                                                                                                                class="text-center">
                                                                                                                <a class="mx-2"
                                                                                                                    href="{{ route('head_office.be_spoke_form.rule_remove', ['form_id' => $form->id, 'page_id' => $page_index, 'item_id' => $item_index, 'id' => $condition_index,'_token'=>csrf_token()]) }}"><i
                                                                                                                        class="fa-regular fa-trash-can text-danger"></i></a>
                                                                                                                <a
                                                                                                                    href="{{ route('head_office.be_spoke_form.rule_edit', ['form_id' => $form->id, 'page_id' => $page_index, 'item_id' => $item_index, 'id' => $condition_index]) }}"><i
                                                                                                                        class="fa-regular fa-pen-to-square text-info"></i></a>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endif
                                                                                            @endforeach
                                                                                            
                                                                                        @endif
                                                                                        @endforeach
                                                                                    @endforeach

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    @else
                                                                        <p>There are no form based rules</p>
                                                                    @endif
                                                                            
                                                                        <style>
                                                                            .custom-margin {
                                                                                margin-top: 0.5rem; /* Adjust as needed */
                                                                            }
                                                                        </style>
                                                                    <div class="form-group">
                                                                        <button class="primary-btn" type="submit"
                                                                            name="submit"><svg width="19"
                                                                                height="19" viewBox="0 0 24 24"
                                                                                fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M16 12L12 8M12 8L8 12M12 8V17.2C12 18.5907 12 19.2861 12.5505 20.0646C12.9163 20.5819 13.9694 21.2203 14.5972 21.3054C15.5421 21.4334 15.9009 21.2462 16.6186 20.8719C19.8167 19.2036 22 15.8568 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 15.7014 4.01099 18.9331 7 20.6622"
                                                                                    stroke="black" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                            </svg>
                                                                            Update</button>
                                                                    </div>
                                                                </form>
                                                                @endisset
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="cards" role="tabpanel"
                                            aria-labelledby="cards-tab">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="card">
                                                        <div class="card-body">

                                                            <h3 class="text-info h3 font-weight-bold">Involvements <a
                                                                    style="float: right !important;" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#default_card_form"
                                                                    class="btn btn-info">Add New Involvement</a></h3>

                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="dataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            {{-- <th>Type</th> --}}
                                                                            <th>Name</th>
                                                                            <th>Connected with</th>
                                                                            <th>Action</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @isset($form)
                                                                        @foreach ($form->formCards as $card)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $loop->iteration }}
                                                                                </td>
                                                                                {{-- <td>
                                                                            {{$card->default_card->type}}
                                                                        </td> --}}
                                                                                <td>
                                                                                    {{ $card->name }}
                                                                                </td>
                                                                                <td>
                                                                                    @foreach ($card->group() as $connected_card)
                                                                                        @if ($connected_card->from_card && $connected_card->form_card_id != $card->id)
                                                                                            {{ $connected_card->from_card->name }},
                                                                                        @endif
                                                                                    @endforeach
                                                                                </td>


                                                                                <td calss="row_icons">
                                                                                    <div class="btn-group">
                                                                                        <a class="btn btn-danger delete_button"
                                                                                            data-msg="Are you sure, you want to delete this Card?"
                                                                                            href="{{ route('head_office.be_spoke_form.form_card_delete', ['id'=>$card->id,'_token'=>csrf_token()]) }}"><i
                                                                                                class="fas fa-times"></i></a>
                                                                                        <a href="#"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#default_card_form_{{ $card->id }}"
                                                                                            class="btn btn-warning"><i
                                                                                                class="fa fa-wrench"></i></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>

                                                                            @include(
                                                                                'head_office.be_spoke_forms.form_card',
                                                                                [
                                                                                    'card' => $card,
                                                                                    'form_cards' =>
                                                                                        $form->formCards,
                                                                                ]
                                                                            )
                                                                        @endforeach
                                                                        @endisset
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="case_invest_tab" role="tabpanel"
                                            aria-labelledby="case_invest_tab">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="car ms-3">
                                                        <div class="card-bod">

                                                            <h3 class="h3 font-weight-bold mb-3"
                                                                style="color: #48494E;">Case Investigators <a
                                                                    style="float: right !important; font-size:16px;"
                                                                    data-bs-toggle="modal" data-bs-target="#case_invest_modal"
                                                                    href="#" class="primary-btn"><svg
                                                                        width="20" height="20"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M16 3.46776C17.4817 4.20411 18.5 5.73314 18.5 7.5C18.5 9.26686 17.4817 10.7959 16 11.5322M18 16.7664C19.5115 17.4503 20.8725 18.565 22 20M2 20C3.94649 17.5226 6.58918 16 9.5 16C12.4108 16 15.0535 17.5226 17 20M14 7.5C14 9.98528 11.9853 12 9.5 12C7.01472 12 5 9.98528 5 7.5C5 5.01472 7.01472 3 9.5 3C11.9853 3 14 5.01472 14 7.5Z"
                                                                            stroke="black" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                    Add New</a></h3>

                                                            <div class="table-responsive" style="min-height: 50vh;">
                                                                <table class="table new-table" style="width: 100% !important;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Locations</th>
                                                                            <th>Case Priority Values</th>
                                                                            @if (isset($ho_user->user_profile_assign->profile->is_manage_team) &&
                                                                                    $ho_user->user_profile_assign->profile->is_manage_team)
                                                                                <th></th>
                                                                            @endif

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @isset($form)
                                                                        @foreach ($form->form_settings->where('be_spoke_form_id', $form->id)->where('is_active', 1) as $setting)
                                                                            <tr>
                                                                                <td class='position-relative'>
                                                                                    <div class="d-flex align-items-center gap-2">
                                                                                        <div class="user-avatar-wrapper" style="position: relative;left: unset;">
                                                                                           
                                                                                                        <div style="cursor:pointer;"
                                                                                                            class="user-icon-circle new-card-wrap"
                                                                                                            title="{{ $setting->head_office_user->user->name }}">
                                                                                                            @if (isset($setting->head_office_user->user->logo))
                                                                                                                <img src="{{ $setting->head_office_user->user->logo }}"
                                                                                                                    alt="png_img"
                                                                                                                    style="width: 30px; height: 30px; border-radius: 50%;">
                                                                                                            @else
                                                                                                                <div class="user-img-placeholder"
                                                                                                                    id="user-img-place"
                                                                                                                    style="width: 30px; height: 30px;">
                                                                                                                    {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $setting->head_office_user->user->name))) }}
                                                                                                                </div>
                                                                                                            @endif
                                                                                                            @if (isset($setting->head_office_user->user))
                                                                                                                @include(
                                                                                                                    'head_office.user_card_component',
                                                                                                                    [
                                                                                                                        'user' => $setting->head_office_user->user,
                                                                                                                    ]
                                                                                                                )
                                                                                                            @endif
                                                                                                        </div>
                    
                                                                                                
                                                                                        </div>
                                                                                        <p class="m-0">
                                                                                            {{ $setting->head_office_user->user->name }}

                                                                                        </p>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    @php
                                                                // Convert the locations into arrays for easy comparison
                                                                $allLocations = $head_office->locations->pluck('id')->toArray();
                                                                $settingLocations = isset($setting->location_id) ? json_decode($setting->location_id,true) : null; 
                                                                $allExist = !array_diff($allLocations, $settingLocations);
                                                            @endphp
                                                            @if ($allExist)
                                                                <p>All</p>
                                                            @elseif(isset($settingLocations[0]))
                                                                    
                                                                    <div class='d-flex align-items-center gap-1'>

                                                                        <p class="m-0">{{$head_office->locations->where('id',$settingLocations[0])->first()->location->trading_name}}...</p>
                                                                        <span class="badge badge-info bg-info">{{count($settingLocations)}}</span>
                                                                    </div>
                                                            @endif

                                                                                    {{-- <span
                                                                                        class="badge text-bg-primary">Case
                                                                                        Handler</span>
                                                                                    @php
                                                                                        $jsoData = isset(
                                                                                            $setting->user_can_view,
                                                                                        )
                                                                                            ? json_decode(
                                                                                                $setting->user_can_view,
                                                                                                true,
                                                                                            )
                                                                                            : null;

                                                                                    @endphp
                                                                                    @if ($jsoData !== null && ((isset($jsoData[3]) && in_array(3, $jsoData[3])) || isset($jsoData[1])))
                                                                                        <span
                                                                                            class="badge text-bg-secondary">Can
                                                                                            view case</span>
                                                                                    @endif
                                                                                    @if ($form->head_office_user_form_review_setting($setting->head_office_user->id, $form->id))
                                                                                        <span
                                                                                            class="badge text-bg-warning">Can
                                                                                            review case</span>
                                                                                    @endif
                                                                                    @if (count($setting->head_office_user->stage_task_assigns()) != 0)
                                                                                        <span
                                                                                            class="badge text-bg-success">stage
                                                                                            task assigned</span>
                                                                                    @endif --}}
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="m-0 fw-normal">
                                                                                        {{$setting->min_prority}} - {{$setting->max_prority}}

                                                                                    </h5>
                                                                                </td>

                                                                                @if (isset($ho_user->user_profile_assign->profile->is_manage_team) &&
                                                                                        $ho_user->user_profile_assign->profile->is_manage_team)
                                                                                    <td >
                                                                                        <div class="d-flex gap-1 justify-content-center align-items-center mx-auto"
                                                                                            style="width: fit-content;">
                                                                                            <button data-json="{{ json_encode($setting) }}"  type="button" data-bs-toggle="modal" data-bs-target="#case_invest_modal" class="btn p-0 px-2 shadow-none case_ivestigator-button"
                                                                                                title="edit this condition">
                                                                                                <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                                                                                            </button>
                                                                                            <a href="{{route('head_office.delete_case_investigitor',$setting->id)}}" type="button" class="btn p-0 px-2 shadow-none"
                                                                                                title="Remove this action">
                                                                                                <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                @endif
                                                                            </tr>

                                                                            {{-- @include(
                                                                                'head_office.be_spoke_forms.form_card',
                                                                                [
                                                                                    'card' => $card,
                                                                                    'form_cards' => $form->formCards,
                                                                                ]
                                                                            ) --}}
                                                                        @endforeach
                                                                        @endisset
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="location_timline" role="tabpanel"
                                            aria-labelledby="location_timline">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="car ms-3">
                                                        <div class="card-bod">




                                                            <h3 class=" h3 font-weight-bold my-4 "
                                                                style="width: fit-content; color:#48494E;">Case Summary
                                                            </h3>
                                                            <form action="{{ isset($form) ? route('share_emails.share_email.store', $form->id) : '#' }}" method="post">
                                                                @if (isset($is_display_case_data_objects) && is_iterable($is_display_case_data_objects))
                                                                <table class="new-table" style="width: 100% !important">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Page</th>
                                                                            <th>Question</th>
                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        @foreach ($is_display_case_data_objects as $data)
                                                                            <tr>
                                                                                <td>{{ $data['pageName'] }}</td>
                                                                                <td>{{ $data['label'] }}</td>
                                                                                <td><a
                                                                                        href="{{ route('head_office.be_spoke_form.rule_edit', ['form_id' => $form->id, 'page_id' => $data['pageIndex'], 'item_id' => $data['itemIndex'], 'id' => 0]) }}"><i
                                                                                            class="fa-regular fa-pen-to-square text-info"></i></a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                @else
                                                                <div class="table-responsive">
                                                                    <p>No fields have been assigned to display in the case summary in the Case Manager</p>

                                                                </div>
                                                                @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @include('head_office.be_spoke_forms.default_task', ['task' => null])
                        @include('head_office.be_spoke_forms.default_document', ['document' => null])
                        @include('head_office.be_spoke_forms.default_links', ['document' => null])
                        @include('head_office.be_spoke_forms.share_case_approved_email', [
                            'shared_case_approved_email' => null,
                        ])
                        @if (isset($form) && isset($form->formCards))
                        @include('head_office.be_spoke_forms.form_card', [
                            'card' => null,
                            'form_cards' => $form->formCards,
                        ])
                        @endif
                    
                        
                    
                    <!-- End custom design -->
                </div>
            </div>


        </div>

    </div>
    <input type="hidden" value="{{ asset('v2/images/icons/edit-03.svg') }}" id="edit_image">
    <input type="hidden" value="{{ asset('v2/images/icons/trash.svg') }}" id="delete_image">
    <input type="hidden" value="{{ asset('v2/images/icons/check.svg') }}" id="save_image">
    <input type="hidden" value="{{ asset('v2/images/icons/close.svg') }}" id="close_image">
    <input type="hidden" value="{{ csrf_token() }}" id="token">
    <input type="hidden" value="{{ route('head_offie.form.default_stage_save') }}" id="stage_save_route">
    <input type="hidden" value="{{ route('head_offie.form.default_stage_delete') }}" id="stage_delete_route">

    <input type="hidden" value="{{ route('head_offie.form.swap_stage_route') }}" id="swap_stage_route">
    <input type="hidden" value="{{ route('head_offie.form.swap_task_route') }}" id="swap_task_route">
    {{-- <input type="hidden" value="{{asset('v2/images/icons/minus')}}" id="minus_image"> --}}
    <input type="hidden" value="{{ asset('v2/images/icons/plus.svg') }}" id="plus_image">
    <!-- Modal -->
    <div class="modal modal-md fade" id="user-avatar-model" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-2">
                        <table id="user-avatar-table" class="table new-table-no-event" style="width:100% !important">
                            <thead class="text-center">
                                <tr>
                                    <th class="text-center">User</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Rule</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal modal-md fade" id="stage_groups_model" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_task_record" tabindex="-1" aria-labelledby="saveModalLabel"
        aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="post" action="{{ route('head_office.be_spoke_form.stage.default_task_update') }}">
                    <div class="modal-header">
                        <h1 class="modal-title fs-3" id="saveModalLabel" style="text-align: center;width: 100%;">Edit
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="parent_div mt-3">
                            <style>
                                select {
                                    cursor: pointer;
                                    background: #F2F6F7;
                                    border-radius: 5px;
                                    padding: 5px;
                                    border: 1px solid #D9D9D9 !important;
                                }

                                .select2-container--open {
                                    z-index: 9999999 !important;
                                }
                            </style>
                            <p class="fw-semibold text-info rec-title" style="font-size: 14px;"> Task Deadline</p>
                            <div class="d-flex align-items-center">
                                <input type="text" hidden id="task_rec_id" name="task_rec_id">
                                <label class="inputGroup mb-0 d-flex align-items-center rec-subtitle"
                                    style="width: fit-content;">Set Task Deadline&nbsp;&nbsp;
                                </label>
                                <input style="width: fit-content;flex:unset;" type="checkbox" name="is_dead_line"
                                    value="1" class="  is_dead_line">
                                <div class="deadline-wrap" style="display: none">
                                    {{-- <input class="form-control custom-input " style="max-width: 50px;" type="number" oninput="fitSize(event)"
                                            min="0" max="1000"  
                                            value="0"> --}}

                                    <input type="number" name="dead_line_duration" value="1" min="1"
                                        style="width: 50px;background:rgba(242, 246, 247, 255);"
                                        class="mx-2 rounded dead_line_duration border-0">
                                    <select class=" w-auto  dead_line_units" name="dead_line_unit"
                                        id="dead_line_unit">
                                        <option value="days">Days
                                        </option>
                                        <option value="weeks">Weeks
                                        </option>
                                        <option value="months">Months
                                        </option>
                                        <option value="years">Years
                                        </option>
                                    </select>
                                    </label>

                                </div>
                            </div>


                            <div class="hide_all" style="display: none">


                                <div class="row dead_line_option mt-2">
                                    <div class="dead_line_start_from_wrap mb-2">
                                        <label class="inputGroup w-50">From
                                            <select name="dead_line_start_from" id=""
                                                class=" w-100 dead_line_start_from">
                                                <option value="">Select option</option>
                                                <option value="incident_date">Incident Date</option>
                                                <option value="reported_date">Reported Date</option>
                                                <option value="task_started">Task start</option>
                                                <option value="task_complete">Task complete</option>
                                                <option value="stage_started">Stage start</option>
                                                <option value="stage_complete">Stage complete</option>
                                            </select>
                                        </label>
                                    </div>
                                    <div class="incident_date_select_field_wrap" style="display: none;">
                                        <select name="incident_date_selected"
                                            class=" w-auto select2 incident_date_select_field">
                                            @if (!empty($incident_date_items))
                                                <option value="0" disabled>Select Field</option>
                                                @foreach ($incident_date_items as $incident_date_item)
                                                    <option value="{{ $incident_date_item['id'] }}">
                                                        {{ $incident_date_item['label'] }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No Field Marked as Incident Date</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="task_started_select_field_wrap" style="display: none;">
                                        <select name="task_started_selected" class=" w-auto select2 task_select_field">
                                            @if (isset($stage) && !empty($stage->default_tasks()->get()))
                                                <option value="0">Select a task</option>
                                                @foreach ($stage->default_tasks()->get() as $task_opt)
                                                    <option value="{{ $task_opt->id }}">
                                                        {{ $task_opt->title }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Task yet.</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="task_completed_select_field_wrap" style="display: none;">
                                        <select name="task_completed_selected"
                                            class=" w-auto select2 task_select_field">
                                            @if (isset($stage) && !empty($stage->default_tasks()->get()))
                                                <option value="0">Select a task</option>
                                                @foreach ($stage->default_tasks()->get() as $task_opt)
                                                    <option value="{{ $task_opt->id }}">
                                                        {{ $task_opt->title }}</option>
                                                @endforeach
                                            @else
                                                <option value="0" disabled>No Task yet.</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="stage_started_select_field_wrap" style="display: none;">
                                        <select name="stage_started_selected" class=" w-auto select2 task_select_field">
                                            @if (isset($stage) && !empty($form->default_stages()->get()))
                                                <option value="0" disabled selected>Select a stage</option>
                                                @foreach ($form->default_stages()->get() as $stage_opt)
                                                    <option value="{{ $stage_opt->id }}">
                                                        {{ $stage_opt->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="0">No Stage yet.</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="stage_completed_select_field_wrap" style="display: none;">
                                        <select name="stage_completed_selected"
                                            class=" w-auto select2 task_select_field">
                                            @if (isset($stage) && !empty($form->default_stages()->get()))
                                                <option value="0">Select a stage</option>
                                                @foreach ($form->default_stages()->get() as $task_opt)
                                                    <option value="{{ $task_opt->id }}">
                                                        {{ $task_opt->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No Task yet.</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="">
                                        <label class="inputGroup my-1">Then
                                            <select name="dead_line_option" class=" w-auto  dead_line_option_select">

                                                <option value="option">Select Option</option>
                                                <option value="do_nothing">Do nothing
                                                </option>
                                                <option value="move_task_to_another_user_random">Move task to another user
                                                    (random)
                                                </option>
                                                <option value="move_user">Move task to
                                                    specific user</option>
                                                <option value="move_profile">Move task
                                                    to another user with a user profile of...</option>
                                                <option value="mail_user">Email specific
                                                    user</option>
                                                <option value="mail_profile">Email
                                                    person with user profile…</option>
                                                <option value="mail_custom">Custom
                                                    Profile</option>
                                            </select>
                                        </label>
                                    </div>



                                    <div class=" dead_line_users" style="display: none;">
                                        @php
                                            $user = Auth::guard('web')->user()->selected_head_office;
                                        @endphp

                                        <label class="inputGroup w-100">
                                            Select Users
                                            <select class=" w-auto  dead_line_user_option select_2"
                                                name="dead_line_user[]" multiple="multiple">
                                                @foreach ($user->users as $u)
                                                    <option value="{{ $u->user->id }}">
                                                        {{ $u->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>


                                    <div class=" dead_line_profiles" style="display: none;">

                                        <label class="inputGroup w-100">
                                            Select Profiles
                                            <select class=" w-auto  select_2" name="dead_line_profile[]"
                                                multiple="multiple">
                                                @foreach ($user->head_office_user_profiles as $user)
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->profile_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                    <div class=" dead_line_email_profile" style="display: none;">

                                        <label class="inputGroup w-100">
                                            Please specify
                                            <select class=" w-auto  dead_line_user_option select_2"
                                                name="dead_line_user_email_profile">
                                                <option value="1">Email all users with this profile</option>
                                                <option value="2">Email single user with this profile (random)
                                                </option>
                                            </select>
                                        </label>
                                    </div>
                                    <div class=" dead_line_email_tag" style="display: none;">
                                        <p class="m-0 fw-semibold">Please specify emails</p>
                                        <select name="custom_dead_line_emails[]" multiple="multiple"
                                            class="w-100 select_2 test-mail" style="width: 100%">
                                            <option value="0" disabled>Type a valid Email</option>
                                        </select>
                                        <label class="inputGroup w-100">Custom Email
                                            <textarea spellcheck="true"  spellcheck="true"  name="email_template" class="form-control border mt-2 task-rich w-100 spellcheck" id="task_email_temp"
                                                cols="30" rows="10"></textarea>
                                        </label>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <script>
                            window.addEventListener('DOMContentLoaded', (event) => {
                                $(document).ready(function() {
                                    $('.select_2').select2();

                                    $('.select_2_custom').select2({
                                        tags: true,
                                        createTag: function(params) {
                                            var term = $.trim(params.term);

                                            if (validateEmail(term)) {
                                                return {
                                                    id: term,
                                                    text: term,
                                                    newTag: true // add additional parameters
                                                };
                                            }

                                            return null;
                                        },
                                        insertTag: function(data, tag) {
                                            // Insert the tag only if it is valid
                                            if (tag.newTag) {
                                                data.push(tag);
                                            }
                                        }
                                    });
                                });

                                function validateEmail(email) {
                                    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                    return re.test(email);
                                }
                            });

                            $('.dead_line_start_from').on('change', function(e) {
                                const $this = $(this);
                                const fieldWrapClasses = {
                                    'incident_date': '.incident_date_select_field_wrap',
                                    'task_started': '.task_started_select_field_wrap',
                                    'task_complete': '.task_completed_select_field_wrap',
                                    'stage_started': '.stage_started_select_field_wrap',
                                    'stage_complete': '.stage_completed_select_field_wrap'
                                };

                                $.each(fieldWrapClasses, function(value, className) {
                                    const $fieldWrap = $this.parent().parent().siblings().closest(className);
                                    if (e.target.value === value) {
                                        $fieldWrap.slideDown();
                                    } else {
                                        $fieldWrap.slideUp();
                                    }
                                });
                            });
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary save-text-btn">Save
                            changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" name="" id="is_allow_non_approved_emails_route"
        value="{{ route('head_office.case.is_allow_non_approved_emails_route') }}">


        <div class="modal fade" id="case_invest_modal" tabindex="-1" aria-labelledby="saveModalLabel"
    aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="{{ route('head_office.user_incident_setting_add') }}" id="case_invest_form">
                <div class="modal-header">
                    <h1 class="modal-title fs-3" id="saveModalLabel" style="text-align: center;width: 100%;">Edit Case Investigator
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="parent_div mt-3">
                        <style>
                            select {
                                cursor: pointer;
                                background: #F2F6F7;
                                border-radius: 5px;
                                padding: 5px;
                                border: 1px solid #D9D9D9 !important;
                            }

                            .select2-container--open {
                                z-index: 9999999 !important;
                            }
                        </style>

                        <input type="text" hidden name="form_id" value="{{ isset($form->id) ? $form->id : '' }}">
                        <div>
                            <label for="">Select Users</label>
                            <Select class="select2" multiple="multiple" required name="case_investor_users[]">
                                @foreach ($head_office->users()->get() as $case_user)
                                    <option value="{{ $case_user->id }}">{{ $case_user->user->name }}</option>
                                @endforeach
                            </Select>
                        </div>
                        <div class="my-2">
                            <div class="d-flex align-items-center gap-2">
                                <label for="">Select Locations</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" id="all_loc_btn" />
                                    <label for=""><small>All</small></label>
                                </div>
                            </div>
                            <Select class="select2 location-select" multiple="multiple" required name="case_investor_locations[]">
                                @foreach ($head_office->locations()->get() as $case_loc)
                                    <option value="{{ $case_loc->id }}">{{ $case_loc->location->trading_name }}  {{ isset($case_loc->location->location_code) ? '(' . $case_loc->location->location_code . ')' : '' }}</option>
                                @endforeach
                            </Select>
                        </div>
                        <div class="col">
                            <label>Case Priority Levels</label> 
                            <div class="d-flex align-items-center gap-3 slider-container">
                                <div class="form-group m-0" style="width: 50px; height:50px;">
                                    {{-- <label>Min</label> --}}
                                    <input type="text" style="height: 100%;" name="min_prority"  class="form-control min-input p-1 custom-input text-center">
                                </div>
                                <div class="position-relativ " style="min-width: 300px; ">
                                    <div class="slider-range"></div>
                                </div>
                                <div class="form-group m-0" style="width: 50px;height:50px;">
                                    {{-- <label>Max</label> --}}
                                    <input type="text" style="height: 100%;" name="max_prority"  class="form-control max-input p-1 custom-input text-center">
                                </div>

                            </div>
                        </div>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary save-text-btn" id="save-btn-case-invest">Add New</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('styles')
    <style>
        .action-bar {
            top: -60px !important;
            left: 189px;
            min-height: 37px;
            min-width: 135px !important;
        }

        .rounded-button {
            border-radius: 14px;
        }
    </style>



    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/css/style.css') }}">
@endsection

@section('scripts')
    @if (isset($form))
        <script>
            $(document).ready(function() {
                $("#is_allow_non_approved_emails").on('change', function() {
                    var route = $("#is_allow_non_approved_emails_route").val();
                    var is_allow_non_approved_emails = $("#is_allow_non_approved_emails").is(':checked') ? 1 :
                        0;
                    var data = {
                        '_token': "{{ csrf_token() }}",
                        'is_allow_non_approved_emails': is_allow_non_approved_emails,
                        'form_id': {{ $form->id }}
                    }
                    $.post(route, data)
                        .then(function(response) {
                            console.log(is_allow_non_approved_emails);
                        })
                        .catch(function(response) {
                            console.log(response);
                        })
                })
            });
        </script>
    @endif
    <script>
        $('.slider-range').each(function() {
        // Find the associated inputs
        var sliderContainer = $(this).closest('.slider-container');
        var minInput = sliderContainer.find('.min-input');
        var maxInput = sliderContainer.find('.max-input');

        // Get the values from the inputs, if they exist
        var min = minInput.val().trim() !== '' ? parseInt(minInput.val(), 10) : 75;
        var max = maxInput.val().trim() !== '' ? parseInt(maxInput.val(), 10) : 300;

        // Initialize the slider with the values
        $(this).slider({
            range: true,
            min: 0,
            max: 500,
            values: [min, max],
            slide: function(event, ui) {
                minInput.val(ui.values[0]);
                maxInput.val(ui.values[1]);
            }
        });

        // Set the inputs to the slider values if they were empty
        if (minInput.val() === '') {
            minInput.val(min);
        }
        if (maxInput.val() === '') {
            maxInput.val(max);
        }
    });

    $('.min-input').on('change', function() {
        var min = parseInt($(this).val(), 10);
        var max = parseInt($(this).closest('.slider-container').find('.max-input').val(), 10);
        const slider = $(this).closest('.slider-container').find('.slider-range');

        if (min >= max) {
            min = max - 1;
            $(this).val(min);
        }

        $(slider).slider("values", 0, min);
    });

    $('.max-input').on('change', function() {
        var max = parseInt($(this).val(), 10);
        var min = parseInt($(this).closest('.slider-container').find('.min-input').val(), 10);
        const slider = $(this).closest('.slider-container').find('.slider-range');

        if (max <= min) {
            max = min + 1;
            $(this).val(max);
        }

        $(slider).slider("values", 1, max);
    });
        let isCopying = false;
        $('#myInput').on('click', function(event) {
            event.stopPropagation();
            $(this).val($(this).data().old);
            $(this).removeAttr('readonly').css('background', 'transparent');
        });
        $('#myInput').on('blur', function() {
    if (isCopying) {
        isCopying = false;
        return;
    }

    // Define the protocol part of the URL
    const protocol = window.location.protocol + '//';

    // Set the input field to readonly and change its background color
    $(this).attr('readonly', true).css('background', '#E9ECEF');

    // Get the new and old links, and the company data
    var new_link = $(this).val().trim();
    var old_link = $(this).data('old');
    var company = new URL(window.location.href).hostname;
    // var company = $(this).data('company');

    // Check if the new link is empty
    if (new_link === '') {
        $(this).val(protocol + company + '/external/' + old_link);
        return;
    }

    // Check if the new link is the same as the old link
    if (new_link === old_link) {
        $(this).val(protocol + company + '/external/' + old_link);
        return;
    }

    // Validate the new link for alphanumeric characters only
    if (!/^[a-zA-Z0-9]+$/.test(new_link)) {
        alertify.notify('Link cannot contain symbols', 'error', 5);
        $(this).val(protocol + company + '/external/' + old_link);
        return;
    }

    // Check if the new link is longer than 4 characters
    if (new_link.length <= 4) {
        alertify.notify('Link must be longer than 4 characters', 'error', 5);
        $(this).val(protocol + company + '/external/' + old_link);
        return;
    }

    // Set the new URL and update the old link in the data attribute
    $(this).val(protocol + company + '/external/' + new_link);
    $(this).data('old', new_link);
});


        function myFunction() {
            isCopying = true;
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copied: " + copyText.value.slice(0, 36) + '...';
            // isCopying = false;
        }

        function outFunc() {
            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copy to clipboard";
        }

        function setRandomBgAll(className) {
            $('.' + className).each(function() {
                var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
                $(this).css('background-color', randomColor);
            });
        }
        $(document).ready(function() {
            setRandomBgAll('user-img-placeholder')
            // changeTabUrl('MemberTeam');
        })

        $('#create_form').on('click', function() {
            $('#submit-btn').click()
        })

        $('#save_form').on('click', function() {
            $('#submit-btn').click()
        })
        $(document).ready(function() {

            loadActiveTab();
            if (window.location.search.split('=')[1] != undefined) {
                changeTabUrl(window.location.search.split('=')[1])
                $('#profile-tab').click()
            }

        });

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log(tab);
            //$(window.location)[0].replace(url);
            // window.location.replace(url);
            $('.nav-tabs button[data-target="' + tab + '"]').tab('show');
        }





        $('#active_limit_by_amount').on('click', function() {
            if ($(this).prop('checked')) {
                $('#amount_wrap').slideToggle();
            } else {
                $('#amount_wrap').slideToggle();
            }
        })
        $('#active_limit_by_period').on('click', function() {
            if ($(this).prop('checked')) {
                $('#period_wrap').slideToggle();
            } else {
                $('#period_wrap').slideToggle();
            }
        })


        function setRandomBackgroundColor(elementId) {
            var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
            console.log(randomColor)
            $('.' + elementId).css('background-color', randomColor);
        }

        setRandomBackgroundColor('user-img-placeholder');

        function setRandomBgAll(className) {
            $('.' + className).each(function() {
                var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
                $(this).css('background-color', randomColor);
            });
        }
        setRandomBgAll('randCo')
        $('.stage-btn-open').on('click', function() {
            console.log($(this).data('id'))
            Livewire.emit('updateStageId', $(this).data('id'));
        })
        $('.btn-delete-stage').on('click', function() {
            Livewire.emit('reloadComponent');
            $('#stage-side-wrapper').fadeOut()
        })
        $(document).ready(function() {
            let table = new DataTable('#myTable', {
                paging: false,
                info: false,
                language: {
                    search: ""
                },
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
            });
            new DataTable('.new-table', {
                paging: false,
                info: false,
                language: {
                    search: ""
                },
                'columnDefs': [{
                    "select": 'multi',
                    'targets': 0,
                    'searchable': false,

                    'orderable': false,
                    'className': '',
                }],
            });
            $('#internal-btn').click(function() {
                $('#is_external_link').prop('checked', true);
                $('#loc-settings-wrapper').fadeIn()
                $('.external-wrapper').fadeOut()
                $('#external-spec').fadeIn()
                $('#loc-settings-wrapper + p').fadeOut()
                $("#location-timeline-container").fadeIn();
            });

            $('#external-btn').click(function() {
                $('#is_external_link').prop('checked', false);
                $('#loc-settings-wrapper').fadeOut()
                $('.external-wrapper').fadeIn()
                $('#external-spec').fadeOut()
                $('#loc-settings-wrapper + p').fadeIn()
                $("#location-timeline-container").fadeOut();
            });

            $('#case-btn').click(function() {
                $('#add_to_case_manager').prop('checked', true);
            });

            $('#board-btn').click(function() {
                $('#add_to_case_manager').prop('checked', false);
            });

            $('#update-yes-btn').click(function() {
                $('#provide_update').prop('checked', true);
            });

            $('#update-no-btn').click(function() {
                $('#provide_update').prop('checked', false);
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

            $('#draft-yes-btn').click(function() {
                $('#allow-draft').prop('checked', true);
            });

            $('#draft-no-btn').click(function() {
                $('#allow-draft').prop('checked', false);
            });

            $('#show-sub-loc-yes-btn').click(function() {
                $('#show-sub-loc').prop('checked', true);
            });

            $('#show-sub-loc-no-btn').click(function() {
                $('#show-sub-loc').prop('checked', false);
            });

            $('#show-sec-yes-btn').click(function() {
                $('#schedule_section').fadeIn();
                $('.input-ss').fadeIn();
                $('#schedule_check').prop('checked', true);
            });

            $('#show-sec-no-btn').click(function() {
                $('#schedule_section').fadeOut('fast');
                $('#schedule_check').prop('checked', false);
            });

            $('#allow_editing-yes-btn').click(function() {
                $('#allow_editing').prop('checked', true);
                $('.allow-editing-wrapper').fadeIn().css('display', 'flex');
            });

            $('#allow_editing-no-btn').click(function() {
                $('#allow_editing').prop('checked', false);
                $('.allow-editing-wrapper').fadeOut('fast');
            });
            $('#update-yes-btn').click(function() {
                $('#provide_update').prop('checked', true);
                $('.allow-update').fadeIn().css('display', 'flex');
            });

            $('#update-no-btn').click(function() {
                $('#provide_update').prop('checked', false);
                $('.allow-update').fadeOut('fast');
            });

            $('#allow_editing_time_always').click(function() {
                if ($('#allow_editing_time_always').prop('checked')) {
                    $('#allow_editing_time').attr('readonly', true);
                } else {
                    $('#allow_editing_time').removeAttr('readonly');
                }
            })

            $('#never_expire_check').click(function() {
                if ($('#never_expire_check').prop('checked')) {
                    $('#expiry_time').attr('disabled', true);
                } else {
                    $('#expiry_time').removeAttr('disabled');
                }
            })
            $('#schedule_check').click(function() {
                if ($('#schedule_check').prop('checked')) {
                    $('#schedule_input').attr('readonly', true);
                } else {
                    $('#schedule_input').removeAttr('readonly');
                }
            })

            $('input[name="schedule_radio"]').change(function() {
                var selectedValue = $('input[name="schedule_radio"]:checked').val();
                $('#day-id, #calender-id').fadeToggle();
            });


            $(document).ready(function() {
                let formChanged = false;

                $('#main-form').on('change input', function() {
                    formChanged = true;
                });

                $(window).on('beforeunload', function(e) {
                    if (formChanged) {
                        var confirmationMessage =
                            'You have unsaved changes. Do you want to save the form?';
                        (e || window.event).returnValue = confirmationMessage;
                        return confirmationMessage;
                    }
                });

                $(window).on('unload', function(e) {
                    if (formChanged) {
                        var shouldSave = confirm(
                            'You have unsaved changes. Do you want to save the form?');
                        if (shouldSave) {
                            $('#main-form').submit();
                        }
                    }
                });

                $('#main-form').on('submit', function() {
                    formChanged = false;
                });
            });



        });

        $('.select_user_type').on('change', function() {
            if ($(this).val() == 1) {
                $('#pre-stage-wrap').fadeIn();
            } else {
                $('#pre-stage-wrap').fadeOut('fast');
            }
        })
        $('.select-user-2').on('change', function() {
            if ($(this).val() == 3) {
                $('#pre-stage-wrap-2').fadeIn();
            } else {
                $('#pre-stage-wrap-2').fadeOut('fast');
            }
        })

        function fitSize(e) {
            if (e.target.value == '') {
                e.target.value = 0;
            }
            if (e.target.value > 999) {
                e.target.style.width = '6ch';
            } else if (e.target.value > 99) {
                e.target.style.width = '4ch';
            } else {
                e.target.style.width = '3ch';
            }

        }
        var tableAvatar;

        function displayUsersInfo(stageId) {
            $('#user-avatar-model').modal('show');
            fetch(`head_office/bespokeforms/stage_users/${stageId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {

                    console.log(data['success']);
                    if (tableAvatar !== null && tableAvatar !== undefined) {
                        tableAvatar.destroy();
                    }


                    table = $('#user-avatar-table');

                    // Clear existing tbody content
                    table.find('tbody').remove();

                    // Create new tbody and append to table
                    var tbody = $('<tbody></tbody>');
                    table.append(tbody);


                    // Append the user icon circle to the table cell


                    // Loop through the data and create rows
                    data['success'].forEach(function(item, index) {
                        var logoSrc = item.logo ? item.logo : '';

                        // Create the user icon circle div
                        var userIconCircle = `
                        <div class="user-icon-circle" title="User Profile" style="margin: auto;width: fit-content;">
                            ${logoSrc !== '' ? `<img src="${logoSrc}" alt="png_img" style="width: 30px; height: 30px; border-radius: 50%;">` :
                                            `<div class="user-img-placeholder randCol${index}" style="width: 30px; height: 30px;font-weight:500;">${item.data.first_name.charAt(0).toUpperCase()}${item.data.surname.charAt(0).toUpperCase()}</div>`}
                        </div>
                    `;
                        var row = $('<tr></tr>');
                        row.append(`<td>${userIconCircle}</td>`);
                        row.append('<td>' + item.data.first_name + '</td>');
                        row.append('<td>' + item.data.surname + '</td>');
                        row.append('<td>' + item.data.email + '</td>');
                        var action;
                        if (item.condition_type == 1) {
                            action = 'Add user profile to this case';
                        } else if (item.condition_type == 2) {
                            action = 'Add specific user to this case';
                        } else {
                            action = 'Send Email';
                            if (item.pro) {
                                action = 'Send Email (User Profiles)';
                            }
                        }
                        row.append('<td>' + action + '</td>');

                        // Append row to tbody

                        tbody.append(row);
                        setRandomBackgroundColor(`randCol${index}`);
                    });

                    // Initialize new DataTable
                    tableAvatar = new DataTable('#user-avatar-table', {
                        paging: false,
                        info: false,
                    });
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }

        $(document).ready(function() {
            function initializeModal($modal, taskData) {
                console.log(taskData)
                $modal.find('input[name="is_dead_line"]').prop('checked', true);
                $modal.find('input[name="dead_line_duration"]').val(taskData.duration);
                $modal.find('#task_rec_id').val(taskData.id);
                $modal.find('input[name="is_task_over_due"]').prop('checked', taskData.is_task_over_due);
                if (taskData.task_type == 'deadline') {
                    $modal.find('.rec-subtitle').text('Set Task Deadline');
                    $modal.find('.rec-title').text('Task Deadline');
                    $modal.find('.dead_line_start_from_wrap').show();
                    $modal.find('select[name="dead_line_start_from"]').val(taskData.start_from).trigger('change')
                } else {
                    $modal.find('.rec-subtitle').text('If task overdue ');
                    $modal.find('.rec-subtitle').text('If overdue ');
                    $modal.find('select[name="dead_line_start_from"]').val(taskData.start_from).trigger('change');
                    $modal.find('.dead_line_start_from_wrap').hide();
                }

                $modal.find('select[name="dead_line_option"]').val(taskData.action_option).trigger('change');
                $modal.find('select[name="dead_line_unit"]').val(taskData.unit).trigger('change');

                $modal.find('select[name="dead_line_option"]').trigger('change');
                var userIds = JSON.parse(taskData.user_ids); // Array of user IDs
                var profileIds = JSON.parse(taskData.profile_ids); // Array of user IDs
                var emails = JSON.parse(taskData.emails); // Array of user IDs
                $modal.find('select[name="dead_line_user[]"]').val(userIds).trigger('change');
                $modal.find('select[name="dead_line_profile[]"]').val(profileIds).trigger('change');

                if (emails != undefined || emails != null) {
                    $modal.find('.test-mail').select2({
                        tags: true,
                        tokenSeparators: [',', ' '], // Allows comma and space to separate tags
                        createTag: function(params) {
                            var term = $.trim(params.term);
                            if (term === '') {
                                return null;
                            }
                            return {
                                id: term, // Set both id and text to the input value
                                text: term,
                                newOption: true
                            };
                        }
                    });


                    $modal.find('.test-mail').val(null).trigger('change');

                    $modal.find('.test-mail').select2('data', null); // Clear the data first
                    emails.forEach(function(email) {
                        var newOption = new Option(email, email, true, true);
                        $modal.find('.test-mail').append(newOption).trigger('change');
                    });
                }
                $modal.find('select[name="dead_line_user_email_profile"]').val(taskData.email_profile_type).trigger(
                    'change');
                const editor = tinymce.get($modal.find('.task-rich').attr('id'));
                if (taskData.email_template !== undefined && taskData.email_template !== null) {
                    editor.insertContent(taskData.email_template);
                }


                $modal.find('input[name="is_dead_line"]').trigger('change');
            }

            // Attach click event to the button to initialize the modal
            $('.task-button').on('click', function() {
                var taskData = $(this).data('task');
                var $modal = $('#edit_task_record'); // Modal container

                // Initialize the modal with the jQuery function and populate with data
                initializeModal($modal, taskData);
            });
        });

        function selectAllLocations() {
        var allValues = [];

        $('.location-select option').each(function() {
            allValues.push($(this).val());
        });

        $('.location-select').val(allValues).trigger('change');
    }
    function clearAllSelections() {
        $('.location-select').val([]).trigger('change');
    }

    $('#all_loc_btn').on('change', function() {
        if ($(this).is(':checked')) {
            selectAllLocations();
        } else {
            clearAllSelections();
        }
    })

    $('.case_ivestigator-button').on('click', function() {
        var data = $(this).data('json');

        // Parse the data if it's a JSON string
        var jsonData = data;
        // Populate Select2 users
        var locations = JSON.parse(jsonData.location_id) || [];
        var minPriority = jsonData.min_prority || '';
        var maxPriority = jsonData.max_prority || '';


        $('.select2[name="case_investor_users[]"]').val(jsonData.head_office_user_id).trigger('change');
        $('.select2[name="case_investor_locations[]"]').val(locations).trigger('change');
        $('input[name="min_prority"]').val(minPriority);
        $('input[name="max_prority"]').val(maxPriority);
        $('#save-btn-case-invest').text('Update!')
    });

    // Reset form values on modal close
    $('#case_invest_modal').on('hidden.bs.modal', function () {
        console.log($('#case_invest_form'))
        $('#case_invest_form')[0].reset(); // Reset the form
        $('input[name="min_prority"]').val(75);
        $('input[name="max_prority"]').val(300);
        $('#save-btn-case-invest').text('Add New')
        // Reset Select2 elements
        $('.select2').val(null).trigger('change');
    });
    // function for Block editing when selecting Always Checkbox
    document.addEventListener("DOMContentLoaded", function() {
    // Function to handle enabling/disabling for editing
    function toggleEditingAlwaysCheckbox() {
        var alwaysEditingCheckbox = document.getElementById("allow_editing_time_always");
        var editingNumber = document.getElementById("allow_editing_time");
        var editingSelect = document.querySelector("select[name='allow_editing_select']");

        alwaysEditingCheckbox.addEventListener("change", function() {
            if (this.checked) {
                editingNumber.disabled = true;
                editingSelect.disabled = true;
            } else {
                editingNumber.disabled = false;
                editingSelect.disabled = false;
            }
        });

        if (alwaysEditingCheckbox.checked) {
            editingNumber.disabled = true;
            editingSelect.disabled = true;
        }
    }

    $('#main-form').on('submit', function(e) {
        const form_id = $('#be_spoke_form_category_id').val();
        if (form_id == null || form_id == '') {
            e.preventDefault();
            alertify.error('Please select a Category.');
        }
    })



    // Function to handle enabling/disabling for updates
    function toggleUpdateAlwaysCheckbox() {
        var alwaysOpen = document.getElementById('allow_update_time_open');
        var alwaysUpdateCheckbox = document.getElementById("allow_update_time_always");
        var updateNumber = document.getElementById("allow_update_time");
        var updateSelect = document.querySelector("select[name='allow_update_select']");

        alwaysUpdateCheckbox.addEventListener("change", function() {
            if (this.checked || $('#allow_update_time_open').checked) {
                updateNumber.disabled = true;
                updateSelect.disabled = true;
            }else if(this.checked == false && $('#allow_update_time_open').checked == false){
                updateNumber.disabled = false;
                updateSelect.disabled = false;
            }
             else {
                updateNumber.disabled = false;
                updateSelect.disabled = false;
            }
        });

        if (alwaysUpdateCheckbox.checked) {
            updateNumber.disabled = true;
            updateSelect.disabled = true;
        }
    }

    // Initialize both toggle functions
    toggleEditingAlwaysCheckbox();
    toggleUpdateAlwaysCheckbox();
});

    </script>
    <script src="{{ asset('js/alertify.min.js') }}"></script>
    <script src="{{ asset('v2/js/stages.js') }}"></script>
    @include('head_office.be_spoke_forms.script')


    <script src="{{ asset('admin_assets/js/form-template.js') }}"></script>
    <script>
        Livewire.on('categoryUpdated', (category_id) => {
            $('#be_spoke_form_category_id').val(category_id);
        })
    </script>
    {{-- script to show Submission in Loc Acc modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const yesBtn = document.getElementById('show-sub-loc-yes-btn');
        const noBtn = document.getElementById('show-sub-loc-no-btn');
        const modal = document.getElementById('confirmationModal');
        const confirmYes = document.getElementById('confirmYes');
        const cancelModal = document.getElementById('cancelModal');
        const checkbox = document.getElementById('show-sub-loc');

        let confirmed = false;

        yesBtn.addEventListener('click', function (event) {
            confirmed = false;
            modal.style.display = 'flex';
        });

        confirmYes.addEventListener('click', function (event) {
            event.preventDefault();
            confirmed = true;
            checkbox.checked = true;
            modal.style.display = 'none';
            // alert('Submission will now show in location account timeline.');
        });

        cancelModal.addEventListener('click', function () {
            checkbox.checked = false; 
            modal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                checkbox.checked = false; 
                modal.style.display = 'none';
            }
        });
    });

    </script>
<script>
    $('#allow_update_time_always').on('change', function() {
        if(this.checked) {
            $('#allow_update_time_open').prop('checked', false);
        }
        if(!this.checked && !$('#allow_update_time_open').is(':checked')) {
            $("select[name='allow_update_select'],#allow_update_time").prop('disabled', false);
        }
    })
    $('#allow_update_time_open').on('change', function() {
        if(this.checked) {
            $('#allow_update_time_always').prop('checked', false);
        }

        if(!this.checked && !$('#allow_update_time_always').is(':checked')) {
            $("select[name='allow_update_select'],#allow_update_time").prop('disabled', false);
        }
    })

    document.addEventListener('DOMContentLoaded', function() {
        function showUpdateFields(yesSelected) {
            const updateOptionsDiv = document.getElementById('allow-update-options');
            const updateCheckbox = document.getElementById('allow_update_time_always');
            const updateTimeInput = document.getElementById('allow_update_time');
            const updateSelect = document.getElementById('allow_update_select');
            
            if (yesSelected) {
                updateOptionsDiv.style.display = 'flex'; 
                updateTimeInput.disabled = false;
                updateSelect.disabled = false;
            } else {
                updateOptionsDiv.style.display = 'none';
                updateTimeInput.disabled = true;
                updateSelect.disabled = true;
            }
        }

        const allowUpdateCheckbox = document.getElementById('provide_update');
        const allowUpdateDiv = document.getElementById('allow-update-options');
        
        if (allowUpdateCheckbox.checked || {{ isset($form) && $form->allow_responder_update == 1 ? 'true' : 'false' }}) {
            showUpdateFields(true); 
        } else {
            showUpdateFields(false);
        }
        const yesButton = document.getElementById('update-yes-btn');
        const noButton = document.getElementById('update-no-btn');

        yesButton.addEventListener('click', function() {
            showUpdateFields(true);
        });

        noButton.addEventListener('click', function() {
            showUpdateFields(false);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function showEditingOptions(yesSelected) {
            const editingOptionsDiv = document.getElementById('allow-editing-options');
            const editingTimeInput = document.getElementById('allow_editing_time');
            const editingSelect = document.getElementById('allow_editing_select');
            
            if (yesSelected) {
                editingOptionsDiv.style.display = 'flex';
            } else {
                editingOptionsDiv.style.display = 'none';
                editingTimeInput.disabled = true;
                editingSelect.disabled = true;
            }
        }

        function showUpdateFields(yesSelected) {
            const updateOptionsDiv = document.getElementById('allow-update-options');
            const updateTimeInput = document.getElementById('allow_update_time');
            const updateSelect = document.getElementById('allow_update_select');
            
            if (yesSelected) {
                updateOptionsDiv.style.display = 'flex'; 
            } else {
                updateOptionsDiv.style.display = 'none';
                updateTimeInput.disabled = true;
                updateSelect.disabled = true;
            }
        }

        function toggleLimitByFields(checkbox, timeInput, selectInput) {
            if (checkbox.checked) {
                timeInput.disabled = true;
                selectInput.disabled = true;
            } else {
                timeInput.disabled = false;
                selectInput.disabled = false;
            }
        }

        const untilCaseClosedCheckbox = document.getElementById('until_case_closed');
        const allowEditingTimeInput = document.getElementById('allow_editing_time');
        const allowEditingSelect = document.getElementById('allow_editing_select');
        untilCaseClosedCheckbox.addEventListener('click', function() {
            toggleLimitByFields(untilCaseClosedCheckbox, allowEditingTimeInput, allowEditingSelect);
        });

        const alwaysUpdateCheckbox = document.getElementById('allow_update_time_always');
        const allowUpdateTimeInput = document.getElementById('allow_update_time');
        const allowUpdateSelect = document.getElementById('allow_update_select');
        alwaysUpdateCheckbox.addEventListener('click', function() {
            toggleLimitByFields(alwaysUpdateCheckbox, allowUpdateTimeInput, allowUpdateSelect);
        });

        const allowEditingCheckbox = document.getElementById('allow_editing');
        const allowUpdateCheckbox = document.getElementById('provide_update');

        showEditingOptions(allowEditingCheckbox.checked);
        showUpdateFields(allowUpdateCheckbox.checked);

        toggleLimitByFields(untilCaseClosedCheckbox, allowEditingTimeInput, allowEditingSelect);
        toggleLimitByFields(alwaysUpdateCheckbox, allowUpdateTimeInput, allowUpdateSelect);
    });
    
    // script for This will show Responder
    document.addEventListener("DOMContentLoaded", function () {
    const noBtn = document.getElementById("show-sub-loc-no-btn");
    const yesBtn = document.getElementById("show-sub-loc-yes-btn");
    const textBoxContainer = document.getElementById("custom-textbox-container");

    const showSubmissionLoc = document.getElementById("show-sub-loc");
    if (!showSubmissionLoc.checked) {
        textBoxContainer.style.display = "block";
    }

    noBtn.addEventListener("click", function () {
        textBoxContainer.style.display = "block";
    });

    yesBtn.addEventListener("click", function () {
        textBoxContainer.style.display = "none";
    });
});




document.addEventListener("DOMContentLoaded", function () {
    const editorContainer = document.getElementById("editor-container");
    const quill = new Quill(editorContainer, {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }]
            ]
        }
    });

    const form = document.querySelector('form'); 
    form.addEventListener('submit', function () {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'custom_text';
        hiddenInput.value = quill.root.innerHTML;
        form.appendChild(hiddenInput);
    });
});





document.addEventListener('DOMContentLoaded', function() {
    const yesButton = document.getElementById('show-sub-loc-yes-btn');
    const noButton = document.getElementById('show-sub-loc-no-btn');
    const submissionTextArea = document.getElementById('submission-text-area');
    const modalText = document.getElementById('modal-text');
    const boldButton = document.getElementById('bold-btn');
    const italicButton = document.getElementById('italic-btn');
    const colorPicker = document.getElementById('color-picker');
    const checkbox = document.getElementById('show-to-responder-checkbox');
    function showTextArea() {
        submissionTextArea.style.display = 'block';
    }
    function hideTextArea() {
        submissionTextArea.style.display = 'none';
    }

    noButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent any default action
        document.getElementById('show-sub-loc').checked = false;
        showTextArea();
    });
    yesButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent any default action
        document.getElementById('show-sub-loc').checked = true;
        hideTextArea();
    });
    if (!document.getElementById('show-sub-loc').checked) {
        showTextArea();
    }
});

window.addEventListener('DOMContentLoaded', () => {
    tinymce.init({
        selector: '.tinymce',
        font_formats:"Littera Text",
        content_style: "body { font-family: 'Littera Text', sans-serif; }",
        menubar: false,
        skin: false,
        height: 200,
        content_css: false,
        forced_root_block: false,
        promotion: false,
        branding: false,
        plugins: 'textcolor',
        toolbar: 'undo redo | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
        setup: function(editor) {
            editor.on('init change', function() {
                editor.save();
            });
            
        }
    });
    
});
</script>
<script>
        $('.task-del-btn').on('click', function (e) {
            e.preventDefault(); // Prevent default link behavior
            const task_id = $(this).data('task_id');
            alertify.confirm(
                "Warning", // Dialog title
                "All the questions will be deleted. Are you sure?", // Dialog message
                function () {
                    // User clicked "Yes"
                    console.log("Questions deleted.");
                    if(task_id){
                        window.location.href = '/head_office/bespokeforms/del_form_task_json/'+ task_id;
                    }
                    alertify.success("Questions have been deleted."); // Show success message
                    // Add your delete logic here, e.g., AJAX request
                },
                function () {
                    // User clicked "No"
                    console.log("Deletion canceled.");
                    alertify.error("Deletion canceled."); // Show error message
                }
            );
        });
</script>

@endsection
