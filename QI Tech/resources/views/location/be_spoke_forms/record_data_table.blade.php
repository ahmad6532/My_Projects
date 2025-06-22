@php
$nearMiss = [];
$tableRecords = [];
    foreach($records as $date => $dateRecords){
        foreach ($dateRecords as $key => $record) {
            if($record->getTable() == 'near_misses'){
                $nearMiss[] = $record;
            }else{
                $tableRecords[] = $record;
            }
        }
    }
        
@endphp

<style>
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

</style>

@if(isset($nearMiss) && count($nearMiss) != 0)
    

<div class="mt-5" >
    <h3 class="text-info" style="text-align: center;" >Near Miss</h3>
    <div >
        <table class="table  w-100 new-table" id="session-dataTable">
            <thead >
                <th style="white-space: nowrap;">ID</th>
                <th style="white-space: nowrap;">Date</th>
                <th >Error by</th>
                <th >Error detected by</th>
                {{-- <th style="white-space: nowrap;">Status</th> --}}
                <th style="white-space: nowrap;">Point of detection</th>
                <th style="white-space: nowrap;">Type</th>

                

            </thead>
            <tbody>
                    
               
                @foreach ($nearMiss as $near_miss)
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
            </tbody>
        </table>
    </div>
</div>
@endif

@php
    // $records = $location->records()->where('hide', 0)->orderByDesc('created_at')->get();
    $groupedRecords = collect($tableRecords)->groupBy('form_id');
@endphp
@foreach ($groupedRecords as $date => $records)
<div class="mt-5" >
    <h3 class="text-info">{{ optional($records[0]->form)->name ?? 'No form data available' }}</h3>
    <div style="overflow-X:auto; ">
        <table class="table new-table w-100" id="session-dataTable" >
            <thead >
                <th style="white-space: nowrap;">ID</th>
                <th style="white-space: nowrap;">Icon</th>
                <th style="white-space: nowrap;">Date</th>
                @foreach ($records[0]->data as $data)
                @if ($data->question)
                <th style="white-space: nowrap;">{{$data->question->question_title}}</th>
                    @endif
                @endforeach
            </thead>
            <tbody>
                @foreach ($records as $key => $record)
                <tr>
                    <td>{{$record->id}}</td>
                    <td><img class="timeline_icon icon" src="{{ asset('images/prescription_timeline.png') }}" width="32"></td>
                    <td style="white-space: nowrap;">{{$record->created_at->format('d M Y h:i A')}}</td>
                    @foreach ($record->data as $data)
                        @if ($data->question)
                        <th style="white-space: nowrap;">{{$data->question_value}}</th>
                        @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


    


<?php $counter++; ?>
@endforeach

<input type="hidden" value="{{ route('location.document.uploadHashed') }}" id="route_document">
<input type="hidden" value="{{ route('location.document.removedHashed') }}" id="route_document_removedHashed">

<style>
.dataTables_info {
    float: left !important;
    margin-right: 50px !important; /* Adjust this to reduce space */
}

.dataTables_length {
    float: left !important;
    margin-left: 5px !important; /* Adjust this to control the gap */
}

.dataTables_paginate {
    float: right !important;
    margin-right: 15px !important;
}
.dt-info{
    margin-right:20px; 
}


</style>
    
<script>
   $(document).ready(function() {
    $('.new-table').DataTable( {
        fixedHeader: {
            header: true
        },
        scrollCollapse: true,
        scrollY: 600,
        pageLength: 2,
        searching: false,
        lengthChange: true,
        lengthMenu: [[2, 5, 10, 25, 50, -1], [2, 5, 10, 25, 50, "All"]],
        pagingType: 'full_numbers',
        dom: "rt" + // Only the table content
             "<'row'<'col-sm-6 d-flex align-items-center'i<'ml-3'l>><'col-sm-6 text-end'p>>"
    })
})
</script>