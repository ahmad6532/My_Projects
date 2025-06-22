@extends('layouts.head_office_app')
@section('title', 'Head office Settings')
@section('content')

<style>
    .loc-card-wrapper{
        width: 280px;
        min-height: 50px;
        background: rgb(17, 4, 134);
        border-radius: 8px;
        box-shadow: rgb(0,0,0,0.2) 0px 0px 10px;
        display: flex;
        color: white;
        position: relative;
        flex-direction: row;
        background: linear-gradient(to right, #2cafa4, #6bc7bf);
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        overflow: hidden;
        gap: 0.5rem;
    }
    .loc-card-wrapper:hover{
        opacity: 0.9;
        box-shadow: rgb(0,0,0,0.4) 2px 2px 5px;
        transition: 0.2s ease;
    }
    .card-text {
    font-size: 20px;
    margin-top: 0px;
    font-weight: 600;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }
    .border-end{
        border-right: 2px solid #dee2e6 !important;
        width: fit-content;
        padding-right: 1rem; 
        border-top-right-radius: 0px !important;
    }
    #location-search::placeholder{
        color:#bebdbd !important;

    }
    #dataTable_filter:after, .dt-search:after{
        left: 10px !important;
    }

    #session-dataTable thead{
        display: none;
    }
    .dt-scroll-head{
        height: 38px;
    }

    div.dt-container div.dt-layout-cell.dt-end{
        display: flex;
        justify-content: flex-end;
    }

    div.dt-container div.dt-layout-cell.dt-end .dt-search label{
        display: none;
    }

    div.dt-container div.dt-layout-cell.dt-end .dt-search::after{
        left: 8px;
    }
    div.dt-container .dt-search input{
        padding-left: 24px;
    }
    div.dt-container div.dt-layout-cell.dt-start select.dt-input{
        margin-right: 5px;
    }
    div.dt-container.dt-empty-footer .dt-scroll-body{
            border-bottom: none;
            padding-bottom: 10px;
        }
        .new-table tbody tr td:last-child{
            padding-right: 0px !important;
        }
</style>

<div id="content" style="margin: 0;padding:0;">
    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="content-page-heading custom-theme-heading">
            Near Miss
        </div>
        
    </div>
    {{-- <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-info">Contacts</h1>
    </div> --}}
    @include('layouts.error')
    <!-- Content Row -->
    <div class="container-fluid mt-4">
        <style>
            .input-group .form-control:focus + .input-group-append .input-group-text {
                border-color: #86b7fe;
    }
        </style>
        <div class="row column-gap-2 justify-content-between" style="min-height: 70vh;">
            <div class="col-3 p-2 rounded " >
                <div class="mt-2 d-flex flex-column gap-2">
                    <h6 class="fw-bold h3 mb-0 custom-theme-heading">Locations</h6>
                    <div class="d-flex align-items-center gap-2">
                        <!-- <div class="input-group">
                            <input type="text" class="form-control custom-input" style="border-right: 0;color: red" id="location-search" placeholder="Search Location">
                            <div class="input-group-append">
                                <span class="input-group-text custom-input bg-white" style="border-left: 0; border-start-start-radius: 0;border-end-start-radius: 0;"><i class="fa fa-search"></i></span>
                            </div>
                        </div> -->
                        <div class="input-group rounded">
                            <span class="input-group-text border-0 bg-transparent search-addon" id="search-addon">
                                <i class="fas fa-search" style="color: #969697;"></i>
                            </span>
                            <input type="search" class="form-control rounded shadow-none search-input" placeholder="Search" aria-label="Search" aria-describedby="search-addon" id="location-search" />
                        </div>
                        {{-- <div class="d-flex bg-white justify-content-center align-items-center p-1" style="border: 2px solid #D9D9D9; border-radius: 5px;" >
                            <div class="">
                                <i class="fa fa-search" style="color:rgba(0, 0, 0, 0.2)"; ></i>
                            </div>
                            <input type="text" class="form-control custom-input" style="border:0; " id="location-search"  placeholder="Search">
                        </div> --}}
                    </div>
                    <div id="location-cards" class="mt-2 d-flex flex-column gap-2 custom-scroll pe-1" style="height: 90vh;overflow-y:auto    ">
                        @foreach ($ho_locations as $loc)
                        <a class="loc-card-wrapper w-100 " href="{{route('head_office.board',['id'=>$loc->location_id])}}">
                            <div class=" gap-2 card-text d-flex align-items-center px-3 w-100 ">
                                <i class="fa-regular fa-compass"></i>
                                <p class="m-0  align-items-center gap-1">{{$loc->location->trading_name}}  <span class="fw-semibold d-flex" style="font-size: 14px;">- {{$loc->location->username}}</span> <i style="font-size: 14px; position: absolute; right: 15px;top:15px;" data-toggle='tooltip' data-placement='top' title="{{$loc->location->name()}}" class="mx-2 fa-regular fa-circle-question"></i>  </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function(){ 
                    $("#location-search").on("keyup", function() {
                        var value = $(this).val().toLowerCase();
                        $("#location-cards .loc-card-wrapper").filter(function() {
                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        });
                    });
                });
            </script>

            <div class="col-8 " >
                <div style="padding:10px;position: relative;" >
                    @if (isset($loc_id) && isset($ho_locations->where('location_id',$loc_id)->first()->location) && count($ho_locations->where('location_id',$loc_id)->first()->location->NearMiss()) == 0)
                    <h4 class="h4  text-secondary"> No Near Miss Yet 🙂!</h4>
                    @else
                    <form action="{{route('headOffice.board.near_miss_export')}}" method="POST" class="d-flex align-items-center gap-2" style="position: absolute;right:12px;top:-24px;">
                    @csrf
                        <input type="hidden" name="id" value="{{$loc_id}}">
                        <div class=" " >
                            
                            <label for="start_date" class="control-label" style="color: #999;font-size: 12px;">Start Date</label>
                                    <input
                                       type="datetime-local" style="color: #999;"  class="form-control py-1 px-2 border-2 shadow-none form-control-sm" name="startDate" id="start_date"
                                    />
                             
                           
                        </div>
                        <div class="">
                            <label for="end_date" style="color: #999;font-size: 12px;;">End Date</label>
                            <input type="datetime-local" style="color: #999;" class="form-control py-1 px-2 border-2 shadow-none form-control-sm" name="endDate" id="end_date">
                        </div>
                        <button type="submit" class="primary-btn mt-4 btn-sm">Export</button>
                    </form>
                    <table class="table table-bordered  w-100 new-table" id="session-dataTable">
                        <thead >
                            <th style="white-space: nowrap;">ID</th>
                            <th style="white-space: nowrap;">Date</th>
                            <th >Error by</th>
                            <th style="white-space: nowrap;">Error detected by</th>
                            {{-- <th style="white-space: nowrap;">Status</th> --}}
                            <th style="white-space: nowrap;">Point of detection</th>
                            <th style="white-space: nowrap;">Type</th>

                            

                        </thead>
                        <tbody>
                            @isset($loc_id)
                                
                           
                            @foreach ($ho_locations->where('location_id',$loc_id)->first()->location->NearMiss() as $near_miss )
                                <tr >
                                    <td style="white-space: nowrap;">{{$near_miss->id}}</td>
                                    <td style="white-space: nowrap;">{{$near_miss->created_at->format('d M Y h:i A')}}</td>
                                    <td >{{$near_miss->error_by}}</td>
                                    <td >{{$near_miss->error_detected_by}}</td>
                                    {{-- <td style="white-space: nowrap;">{{$near_miss->status}}</td> --}}
                                    <td style="white-space: nowrap;">{{$near_miss->point_of_detection}}</td>
                                    <td>
                                        <ul>
                                        @foreach(App\Models\NearMiss::$errorTypePrescription as $field => $label)
                                                @if($near_miss->$field == 1 )
                                                    <li>{{ $label }}</li>
                                                @endif
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$errorTypeLabelling as $field => $label)
                                                @if($near_miss->$field == 1 )
                                                    <li>{{ $label }}</li>
                                                @endif
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$errorTypePicking as $field => $label)
                                                @if($near_miss->$field == 1 )
                                                    <li>{{ $label }}</li>
                                                @endif
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$errorTypePlacingIntoBasket as $field => $label)
                                                @if($near_miss->$field == 1 )
                                                    <li>{{ $label }}</li>
                                                @endif
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$errorTypeBagging as $field => $label)
                                                @if($near_miss->$field == 1 )
                                                    <li>{{ $label }}</li>
                                                @endif
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$errorTypePreparingDosetteTray as $field => $label)
                                                @if($near_miss->$field == 1 )
                                                    <li>{{ $label }}</li>
                                                @endif
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$errorTypeHandingOut as $field => $label)
                                                @if($near_miss->$field == 1 )
                                                    <li>{{ $label }}</li>
                                                @endif
                                        @endforeach

                                        @php
                                            $contribute_label = false;
                                            $DrugsBasedOnErrorType_label = false;
                                        @endphp
                                        @foreach(App\Models\NearMiss::$contributingFactors as $parent_field => $parent_label_field)
                                            @foreach ($parent_label_field as $field => $label)
                                            @if($near_miss->contributing_factor_other == 1 && $field == 'contributing_factor_other')
                                            <li>{{ $label }}</li>
                                            <li style="list-style: circle">{{ $near_miss->contributing_factor_other_field }}</li>
                                            @elseif($near_miss->$field == 1 )
                                                    @if (!$contribute_label)
                                                    <p class="m-0" style="font-size: 12px;color:#2BAFA5;margin-top:5px !important;">Contributing Factors</p>
                                                    @php
                                                        $contribute_label = true;
                                                    @endphp
                                                    @endif
                                                    <li>{{ $label  }}</li>
                                                @endif
                                            @endforeach
                                        @endforeach

                                        
                                        @foreach(App\Models\NearMiss::$DrugsBasedOnErrorType as $main_label => $main_array)
                                            @foreach($main_array as $field => $label)
                                            @if ($field === 'prescription_expired_drug_name' && !empty($near_miss->prescription_expired_drug_name) )
                                                @if (!$DrugsBasedOnErrorType_label)
                                                    <p class="m-0" style="font-size: 12px;color:#2BAFA5;margin-top:5px !important;">What was error?</p>
                                                    @php
                                                        $DrugsBasedOnErrorType_label = true;
                                                    @endphp
                                                @endif
                                            <li>{{ $label }}</li>
                                            <li style="list-style: circle;">{{ $near_miss->$field }}</li>
                                            @elseif(isset($near_miss->$field) && !empty($near_miss->$field) )
                                                    @if (!$DrugsBasedOnErrorType_label)
                                                    <p class="m-0" style="font-size: 12px;color:#2BAFA5;margin-top:5px !important;">What was error?</p>
                                                    @php
                                                        $DrugsBasedOnErrorType_label = true;
                                                    @endphp
                                                    @endif
                                                    <li>{{ $label }}: ({{ $near_miss->$field }}) <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$PrescriptionReasonsOfNearMiss as $main_label => $main_array)
                                            @foreach($main_array as $field => $label)
                                                @if(isset($near_miss->$field) && !empty($near_miss->$field) )
                                                    @if(strpos($field,'other_field'))
                                                    <li style="list-style: circle;">{{ $near_miss->$field }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @else
                                                    <li>{{ $label }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$LabellingReasonsOfNearMiss as $main_label => $main_array)
                                            @foreach($main_array as $field => $label)
                                                @if(isset($near_miss->$field) && !empty($near_miss->$field) )
                                                    @if(strpos($field,'other_field'))
                                                    <li style="list-style: circle;">{{ $near_miss->$field }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @else
                                                    <li>{{ $label }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$PickingReasonsOfNearMiss as $main_label => $main_array)
                                            @foreach($main_array as $field => $label)
                                                @if(isset($near_miss->$field) && !empty($near_miss->$field) )
                                                    @if(strpos($field,'other_field'))
                                                    <li style="list-style: circle;">{{ $near_miss->$field }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @else
                                                    <li>{{ $label }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$PlacingIntoBasketReasonsOfNearMiss as $main_label => $main_array)
                                            @foreach($main_array as $field => $label)
                                                @if(isset($near_miss->$field) && !empty($near_miss->$field) )
                                                    @if(strpos($field,'other_field'))
                                                    <li style="list-style: circle;">{{ $near_miss->$field }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @else
                                                    <li>{{ $label }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$BaggingReasonsOfNearMiss as $main_label => $main_array)
                                            @foreach($main_array as $field => $label)
                                                @if(isset($near_miss->$field) && !empty($near_miss->$field) )
                                                    @if(strpos($field,'other_field'))
                                                    <li style="list-style: circle;">{{ $near_miss->$field }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @else
                                                    <li>{{ $label }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$PreparingDosetteTrayReasonsOfNearMiss as $main_label => $main_array)
                                            @foreach($main_array as $field => $label)
                                                @if(isset($near_miss->$field) && !empty($near_miss->$field) )
                                                    @if(strpos($field,'other_field'))
                                                    <li style="list-style: circle;">{{ $near_miss->$field }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @else
                                                    <li>{{ $label }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @foreach(App\Models\NearMiss::$HandingReasonsOfNearMiss as $main_label => $main_array)
                                            @foreach($main_array as $field => $label)
                                                @if(isset($near_miss->$field) && !empty($near_miss->$field) )
                                                    @if(strpos($field,'other_field'))
                                                    <li style="list-style: circle;">{{ $near_miss->$field }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @else
                                                    <li>{{ $label }} <span style="font-size: 12px;color:#2BAFA5;">{{ $main_label }}</span></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach

                                        </ul>
                                    </td>
                                    
                                    

                                </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('head_office.edit_contact',['contact' => null])
@include('head_office.edit_address',['address' => null])

@section('scripts')
<script>
     $(document).ready(function (){
        loadActiveTab();

            let table = new DataTable('#session-dataTable', {
                fixedHeader: {
        header: true
    },
    scrollCollapse: true,
    scrollY: '82vh',
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
    function loadActiveTab(tab = null){
        if(tab == null){
            tab = window.location.hash;
        } 
        console.log(tab);
        $('.nav-tabs button[data-bs-target="' + tab + '"]').tab('show');
    }
    $(".delete" ).on( "click", function(e) {
            e.preventDefault();
            let href= $(this).attr('href');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", "Are you sure to delete this? ",
            function(){
                window.location.href= href;
            },function(i){
            });
        });
        
    function initPlaces() {
    //var autocomplete = new google.maps.places.Autocomplete(document.getElementByClassName(''));
    var input = document.getElementsByClassName('free-type-address');
    for (let i = 0; i < input.length; i++) {
        var autocomplete = new google.maps.places.Autocomplete(input[i]);
        autocomplete.addListener('place_changed', function () {
        $(input[i]).trigger('change');
        });
    }
}



    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzxvYQxd1yHydcBFIRNOLQjcbQtThH6rI&libraries=places&callback=initPlaces"></script>




<script src="{{asset('js/alertify.min.js')}}"></script>
@include('head_office.be_spoke_forms.script')

<script src="{{asset('admin_assets/js/form-template.js')}}"></script>

@endsection
@endsection