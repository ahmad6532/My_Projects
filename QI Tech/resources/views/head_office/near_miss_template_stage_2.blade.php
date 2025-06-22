<div class="m-t-10 highlight" data-list="point" id="point-stage-wrapper">
    <h5 class="text-info" style="width: fit-content;">Point of detection?</h5>
    <input type="hidden" name="point_of_detection" class="point_of_detection_input" value="Bagging" @if($nearmiss) value="{{$nearmiss->point_of_detection}}" @endif >

    <button @if(!isset($data)) @elseif($data['what']['point_of_detection']['labelling'] == false) style="display: none;" @endif  
        type="button" class="labelling btn point_of_detection_btn @if($nearmiss && $nearmiss->point_of_detection == 'Labelling') btn-info @else btn-default @endif" 
        data-value="Labelling">
        {{ $data['what']['point_of_detection']['labelling_text'] ?? 'Labelling' }}
    </button>
    <button @if(!isset($data)) @elseif($data['what']['point_of_detection']['bagging'] == false) style="display: none;" @endif  
        type="button" class="bagging btn point_of_detection_btn @if($nearmiss && $nearmiss->point_of_detection == 'Bagging') btn-info @else btn-default @endif" 
        data-value="Bagging">
        {{ $data['what']['point_of_detection']['bagging_text'] ?? 'Bagging' }}
    </button>
    <button @if(!isset($data)) @elseif($data['what']['point_of_detection']['filling_away'] == false) style="display: none;" @endif  
        type="button" class="filling_away btn point_of_detection_btn @if($nearmiss && $nearmiss->point_of_detection == 'Filing Away') btn-info @else btn-default @endif" 
        data-value="Filing Away">
        {{ $data['what']['point_of_detection']['filling_away_text'] ?? 'Filling Away' }}
    </button>
    <button @if(!isset($data)) @elseif($data['what']['point_of_detection']['delivering'] == false) style="display: none;" @endif  
        type="button" class="delivering btn point_of_detection_btn @if($nearmiss && $nearmiss->point_of_detection == 'Delivering') btn-info @else btn-default @endif" 
        data-value="Delivering">
        {{ $data['what']['point_of_detection']['delivering_text'] ?? 'Delivering' }}
    </button>
    <button @if(!isset($data)) @elseif($data['what']['point_of_detection']['picking'] == false) style="display: none;" @endif  
        type="button" class="picking btn point_of_detection_btn @if($nearmiss && $nearmiss->point_of_detection == 'Picking') btn-info @else btn-default @endif" 
        data-value="Picking">
        {{ $data['what']['point_of_detection']['picking_text'] ?? 'Picking' }}
    </button>
    <button @if(!isset($data)) @elseif($data['what']['point_of_detection']['final_check'] == false) style="display: none;" @endif  
        type="button" class="final_check btn point_of_detection_btn @if($nearmiss && $nearmiss->point_of_detection == 'Final Check') btn-info @else btn-default @endif" 
        data-value="Final Check">
        {{ $data['what']['point_of_detection']['final_check_text'] ?? 'Final Check' }}
    </button>
    <button @if(!isset($data)) @elseif($data['what']['point_of_detection']['handing_out'] == false) style="display: none;" @endif  
        type="button" class="handing_out btn point_of_detection_btn @if($nearmiss && $nearmiss->point_of_detection == 'Handing Out') btn-info @else btn-default @endif" 
        data-value="Handing Out">
        {{ $data['what']['point_of_detection']['handing_out_text'] ?? 'Handing Out' }}
    </button>
</div>
<style>
    .what_was_error li{
        width: fit-content;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        top: 8px;
    }
</style>
<br>
<h5 class="text-info display_when_point_detection" >What was the error?</h5>
<div class="row m-t-10 display_when_point_detection"  >

    <div class="col-md-3 col-sm-3 col-xs-3 border-right-2">
        <ul class="what_was_error highlight" data-list="was_error">
            <li class="error_prescription"  @if(!isset($data)) @elseif(!$data['what']['what_was_error']['error_prescription']) style="display: none;" @endif>
                <?php 
                $prescription_icon = ($nearmiss && $nearmiss->what_was_error == 'Prescription')?'prescription-active.png':'prescription.png';
                if(!$nearmiss){
                    $prescription_icon = 'prescription-active.png';
                }
                $labelling_icon = ($nearmiss && $nearmiss->what_was_error == 'Labelling')?'labelling-active.png':'labelling.png';
                $picking_icon = ($nearmiss && $nearmiss->what_was_error == 'Picking')?'picking-active.png':'picking.png';
                $placing_into_basket_icon = ($nearmiss && $nearmiss->what_was_error == 'Placing into Basket')?'placing_in_basket-active.png':'placing_in_basket.png';
                $bagging_icon = ($nearmiss && $nearmiss->what_was_error == 'Bagging')?'bagging-active.png':'bagging.png';
                $desette_tray_icon = ($nearmiss && $nearmiss->what_was_error == 'Preparing Dosette Tray')?'desette_tray-active.png':'desette_tray.png';
                $handing_out_icon = ($nearmiss && $nearmiss->what_was_error == 'Handing Out')?'handing_out-active.png':'handing_out.png';
                ?>
                <a data-value="Prescription"  class="what_was_error_link link_prescription @if($nearmiss && $nearmiss->what_was_error == 'Prescription') active @elseif(!$nearmiss) active @endif" href="#">
                <img data-active-src="{{asset('images/prescription-active.png')}}" data-src="{{asset('images/prescription.png')}}" src="{{asset('images/'.$prescription_icon)}}" width="36">
                <span id="prescription_text">
                    {{ $data['what']['what_was_error']['error_prescription_name'] ?? 'Prescription' }}
                </span>
                {{-- <span @if(!$nearmiss || $nearmiss->what_was_error != 'Prescription') style="display:none" @endif  class="error_count error_count_prescription">@if($nearmiss && $nearmiss->what_was_error == 'Prescription') {{$nearmiss->totalErrors()}} @else 0 @endif</span> --}}
                </a>
                
            </li>
            <li class="error_labelling"  @if(!isset($data)) @elseif(!$data['what']['what_was_error']['error_labelling']) style="display: none;" @endif>
                <a href="#"  data-value="Labelling" class="what_was_error_link link_labelling @if($nearmiss && $nearmiss->what_was_error == 'Labelling') active  @endif">
                <img data-active-src="{{asset('images/labelling-active.png')}}" src="{{asset('images/'.$labelling_icon)}}" data-src="{{asset('images/labelling.png')}}" width="36">
                <span>
                    {{ $data['what']['what_was_error']['error_labelling_name'] ?? 'Labelling' }}
                </span>
                {{-- <span @if(!$nearmiss || $nearmiss->what_was_error != 'Labelling') style="display:none" @endif class="error_count error_count_labelling">@if($nearmiss && $nearmiss->what_was_error == 'Labelling') {{$nearmiss->totalErrors()}} @else 0 @endif</span> --}}
            </a>
               
            </li>
            <li class="error_picking" @if(!isset($data)) @elseif(!$data['what']['what_was_error']['error_picking']) style="display: none;" @endif>
                <a href="#" data-value="Picking" class="what_was_error_link link_picking @if($nearmiss && $nearmiss->what_was_error == 'Picking') active  @endif ">
                    <img data-active-src="{{asset('images/picking-active.png')}}" src="{{asset('images/'.$picking_icon)}}"  data-src="{{asset('images/picking.png')}}" width="36">
                    <span id="picking_text">
                        {{ $data['what']['what_was_error']['error_picking_name'] ?? 'Picking' }}
                    </span>
                    {{-- <span @if(!$nearmiss || $nearmiss->what_was_error != 'Picking') style="display:none" @endif class="error_count error_count_picking">@if($nearmiss && $nearmiss->what_was_error == 'Picking') {{$nearmiss->totalErrors()}} @else 0 @endif</span> --}}
            </a>
               
            </li>
            <li class="error_placing_into_basket" @if(!isset($data)) @elseif(!$data['what']['what_was_error']['error_placing_into_basket']) style="display: none;" @endif>
                <a href="#" data-value="Placing into Basket" class="what_was_error_link link_placing_into_basket @if($nearmiss && $nearmiss->what_was_error == 'Placing into Basket') active  @endif">
                <img data-active-src="{{asset('images/placing_in_basket-active.png')}}" src="{{asset('images/'.$placing_into_basket_icon)}}" data-src="{{asset('images/placing_in_basket.png')}}" width="36">
                <span id="placing_basket_text">
                    {{ $data['what']['what_was_error']['error_placing_into_basket_name'] ?? 'Placing into Basket' }}
                </span>
                
                {{-- <span @if(!$nearmiss || $nearmiss->what_was_error != 'Placing into Basket') style="display:none" @endif class="error_count error_count_picking_placing_in_basket">@if($nearmiss && $nearmiss->what_was_error == 'Placing into Basket') {{$nearmiss->totalErrors()}} @else 0 @endif</span> --}}
            </a>
               
            </li>
            <li class="error_bagging" @if(!isset($data)) @elseif(!$data['what']['what_was_error']['error_bagging']) style="display: none;" @endif>
                <a href="#" data-value="Bagging" class="what_was_error_link link_bagging @if($nearmiss && $nearmiss->what_was_error == 'Bagging') active  @endif">
                <img data-active-src="{{asset('images/bagging-active.png')}}" src="{{asset('images/'.$bagging_icon)}}"  data-src="{{asset('images/bagging.png')}}" width="36">
                <span id="bagging_text">
                    {{ $data['what']['what_was_error']['error_bagging_name'] ?? 'Bagging' }}
                </span>
                {{-- <span @if(!$nearmiss || $nearmiss->what_was_error != 'Bagging') style="display:none" @endif class="error_count error_count_bagging">@if($nearmiss && $nearmiss->what_was_error == 'Bagging') {{$nearmiss->totalErrors()}} @else 0 @endif</span> --}}
            </a>
               
            </li>
            <li class="error_preparing_dosette_tray" @if(!isset($data)) @elseif(!$data['what']['what_was_error']['error_preparing_dosette_tray']) style="display: none;" @endif>
                <a href="#" data-value="Preparing Dosette Tray" class="what_was_error_link link_preparing_dosette_tray @if($nearmiss && $nearmiss->what_was_error == 'Preparing Dosette Tray') active  @endif">
                <img data-active-src="{{asset('images/desette_tray-active.png')}}" src="{{asset('images/'.$desette_tray_icon)}}"  data-src="{{asset('images/desette_tray.png')}}" width="36">
                <span id="dosette_tray_text">
                    {{ $data['what']['what_was_error']['error_preparing_dosette_tray_name'] ?? 'Preparing Dosette Tray' }}
                </span>
                
                {{-- <span @if(!$nearmiss || $nearmiss->what_was_error != 'Preparing Dosette Tray') style="display:none" @endif class="error_count error_count_desette_tray">@if($nearmiss && $nearmiss->what_was_error == 'Preparing Dosette Tray') {{$nearmiss->totalErrors()}} @else 0 @endif</span> --}}
            </a>
            </li>
            <li class="error_handing_out" @if(!isset($data)) @elseif(!$data['what']['what_was_error']['error_handing_out']) style="display: none;" @endif>
                <a href="#" data-value="Handing Out" class="what_was_error_link link_handing_out @if($nearmiss && $nearmiss->what_was_error == 'Handing Out') active  @endif">
                <img data-active-src="{{asset('images/handing_out-active.png')}}" src="{{asset('images/'.$handing_out_icon)}}" data-src="{{asset('images/handing_out.png')}}" width="36">
                <span id="handing_out_text">
                    {{ $data['what']['what_was_error']['error_handing_out_name'] ?? 'Handing Out' }}
                </span>
                {{-- <span @if(!$nearmiss || $nearmiss->what_was_error != 'Handing Out') style="display:none" @endif class="error_count error_count_handing_out">@if($nearmiss  && $nearmiss->what_was_error == 'Handing Out') {{$nearmiss->totalErrors()}} @else 0 @endif</span> --}}
            </a>
               
            </li>
        </ul>
    </div>
    <style>
        .min-height-test{
            min-height: 230px;
        }
    </style>
    <div class="col-md-4 col-sm-4 col-xs-4 border-right-2 p-left-50">
        <!-- Prescription checkboxes -->
        <div data-list="error_prescription_chks" class="prescription_2nd_column highlight min-height-test" @if($nearmiss && $nearmiss->what_was_error != 'Prescription') style="display:none" @endif>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['prescription']['missing_signature']['hidden']) style="display: none;" @endif class="missing_signature checkbox @if($nearmiss && $nearmiss->prescription_missing_signature) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->prescription_missing_signature) checked @endif name="prescription_missing_signature" class="prescription_missing_signature prescription_checkbox error_checkbox" value="1">
                <span> {{ $data['extra_fields']['prescription']['missing_signature']['label'] ?? 'Missing signature' }} </span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['prescription']['prescription_expired']['hidden']) style="display: none;" @endif class="checkbox prescription_expired_field @if($nearmiss && $nearmiss->prescription_expired) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->prescription_expired) checked @endif name="prescription_expired" class="prescription_expired prescription_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['prescription']['prescription_expired']['label'] ?? 'Prescription expired' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['prescription']['old_treatment']['hidden']) style="display: none;" @endif class="checkbox old_treatment @if($nearmiss && $nearmiss->prescription_old_treatment) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->prescription_old_treatment) checked @endif name="prescription_old_treatment" class="prescription_old_treatment prescription_checkbox error_checkbox" value="1">
                <span>
                    {{$data && isset($data['extra_fields']['prescription']['old_treatment']['label']) ? $data['extra_fields']['prescription']['old_treatment']['label'] : 'Old treatment'}}
                </span>
                
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['prescription']['fraudulent_tampered_prescription']['hidden']) style="display: none;" @endif class="checkbox fraudulent_tampered_prescription @if($nearmiss && $nearmiss->prescription_tampered) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->prescription_tampered) checked @endif name="prescription_tampered" class="prescription_tampered prescription_checkbox error_checkbox" value="1">
                <span>
                    {{$data && isset($data['extra_fields']['prescription']['fraudulent_tampered_prescription']['label']) ? $data['extra_fields']['prescription']['fraudulent_tampered_prescription']['label'] : 'Fraudulent/tampered prescription'}}
                </span>
            </label>
        </div>

        <!-- Labelling checkboxes -->
        <div data-list="error_labelling_chks" class="highlight min-height-test labelling_2nd_column" @if(!$nearmiss || $nearmiss->what_was_error != 'Labelling') style="display:none" @endif>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['hidden']) style="display: none;" @endif class="wrong_brand checkbox @if($nearmiss && $nearmiss->labelling_wrong_brand) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->labelling_wrong_brand) checked @endif name="labelling_wrong_brand" class="labelling_wrong_brand labelling_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['labelling']['wrong_brand']['wrong_brand_text'] ?? 'Wrong brand' }}</span>
            </label>        
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_direction']['hidden']) style="display: none;" @endif class="wrong_direction checkbox @if($nearmiss && $nearmiss->labelling_wrong_direction) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->labelling_wrong_direction) checked @endif name="labelling_wrong_direction" class="labelling_wrong_direction labelling_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['labelling']['wrong_direction']['wrong_direction_label'] ?? 'Wrong direction' }}</span>
            </label>    
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['hidden']) style="display: none;" @endif class="wrong_item checkbox @if($nearmiss && $nearmiss->labelling_wrong_item) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->labelling_wrong_item) checked @endif  name="labelling_wrong_item" class="labelling_wrong_item labelling_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['labelling']['wrong_item']['wrong_item_text'] ?? 'Wrong item' }}</span>
            </label>    
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['hidden']) style="display: none;" @endif class="wrong_formulation checkbox @if($nearmiss && $nearmiss->labelling_wrong_formulation) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->labelling_wrong_formulation) checked @endif  name="labelling_wrong_formulation" class="labelling_wrong_formulation labelling_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['labelling']['wrong_formulation']['wrong_formulation_text'] ?? "Wrong formulation" }} </span>
            </label>    
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_patient']['hidden']) style="display: none;" @endif class="wrong_patient checkbox  @if($nearmiss && $nearmiss->labelling_wrong_patient) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->labelling_wrong_patient) checked @endif  name="labelling_wrong_patient" class="labelling_wrong_patient labelling_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['labelling']['wrong_patient']['wrong_patient_label'] ?? 'Wrong patient' }}</span>
            </label>  
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_quantity']['hidden']) style="display: none;" @endif class="wrong_quantity checkbox @if($nearmiss && $nearmiss->labelling_wrong_quantity) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->labelling_wrong_quantity) checked @endif  name="labelling_wrong_quantity" class="labelling_wrong_quantity labelling_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['labelling']['wrong_quantity']['wrong_quantity_text'] ?? 'Wrong quantity' }}</span>
            </label> 
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['hidden']) style="display: none;" @endif class="wrong_strength checkbox @if($nearmiss && $nearmiss->labelling_wrong_strength) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->labelling_wrong_strength) checked @endif  name="labelling_wrong_strength" class="labelling_wrong_strength labelling_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['labelling']['wrong_strength']['wrong_strength_text'] ?? 'Wrong strength' }}</span>
            </label>
        </div>

        <!-- Picking checkboxes -->
        <div data-list="error_picking_chks" class="highlight min-height-test picking_2nd_column" @if(!$nearmiss || $nearmiss->what_was_error != 'Picking') style="display:none" @endif>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['out_of_date_item']['hidden']) style="display: none;" @endif class="out_of_date_item checkbox @if($nearmiss && $nearmiss->picking_out_of_date_item) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->picking_out_of_date_item) checked @endif name="picking_out_of_date_item"  class="picking_out_of_date_item picking_checkbox error_checkbox"  value="1">
                <span>{{ $data['extra_fields']['picking']['out_of_date_item']['label'] ?? 'Out-of-date item' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['hidden']) style="display: none;" @endif class="picking_wrong_brand_field checkbox @if($nearmiss && $nearmiss->picking_wrong_brand) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->picking_wrong_brand) checked @endif name="picking_wrong_brand" class="picking_wrong_brand picking_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['picking']['wrong_brand']['label'] ?? 'Wrong brand' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['hidden']) style="display: none;" @endif class="picking_wrong_item_field checkbox @if($nearmiss && $nearmiss->picking_wrong_item) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->picking_wrong_item) checked @endif name="picking_wrong_item" class="picking_wrong_item picking_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['picking']['wrong_item']['label'] ?? 'Wrong item' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['hidden']) style="display: none;" @endif class="picking_wrong_formulation_field checkbox @if($nearmiss && $nearmiss->picking_wrong_quantity) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->picking_wrong_quantity) checked @endif name="picking_wrong_quantity" class="picking_wrong_quantity picking_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['picking']['wrong_formulation']['label'] ?? 'Wrong formulation' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_quantity']['hidden']) style="display: none;" @endif class="picking_wrong_quantity_field checkbox @if($nearmiss && $nearmiss->picking_wrong_strength) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->picking_wrong_strength) checked @endif name="picking_wrong_strength" class="picking_wrong_strength picking_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['picking']['wrong_quantity']['label'] ?? 'Wrong quantity' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['hidden']) style="display: none;" @endif class="picking_wrong_strength_field checkbox @if($nearmiss && $nearmiss->picking_wrong_formulation) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->picking_wrong_formulation) checked @endif name="picking_wrong_formulation" class="picking_wrong_formulation picking_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['picking']['wrong_strength']['label'] ?? 'Wrong strengths' }}</span>
            </label>
        </div>

        <!-- Placing Into Basket checkboxes -->
        <div data-list="error_placing_into_basket_chks" class="highlight min-height-test placing_basket_2nd_column" @if(!$nearmiss || $nearmiss->what_was_error != 'Placing into Basket') style="display:none" @endif>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['placing_to_basket']["another_patient_label_basket"]['hidden']) style="display: none;" @endif class="another_patient_label_basket checkbox @if($nearmiss && $nearmiss->placing_basket_another_patient_label_basket) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->placing_basket_another_patient_label_basket) checked @endif name="placing_basket_another_patient_label_basket" class="placing_basket_another_patient_label_basket placing_basket_checkbox error_checkbox" value="1">
                
                {{-- It is Showing Accurate Thieng --}}
                <span>
                    {{$data['extra_fields']['placing_to_basket']["another_patient_label_basket"]['label'] ?? ($request->another_patient_label_basket_label ?? 'Another patient\'s labels in/on the basket')}}
                </span>
                
                
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['placing_to_basket']['wrong_basket']['hidden']) style="display: none;" @endif class="wrong_basket checkbox @if($nearmiss && $nearmiss->placing_basket_wrong_basket) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->placing_basket_wrong_basket) checked @endif name="placing_basket_wrong_basket" class="placing_basket_wrong_basket placing_basket_checkbox error_checkbox" value="1">
                
                {{-- This Span have To Show What in This Label "'label' => $request->wrong_basket_label ?? 'Placed into the wrong basket'," --}}
                <span>
                    {{$data['extra_fields']['placing_to_basket']["wrong_basket"]['label'] ?? ($request->wrong_basket_label ?? 'Placed into the wrong basket')}}
                </span>
                
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['placing_to_basket']['missing_item']['hidden']) style="display: none;" @endif class="missing_item checkbox @if($nearmiss && $nearmiss->placing_basket_missing_item) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->placing_basket_missing_item) checked @endif name="placing_basket_missing_item" class="placing_basket_missing_item placing_basket_checkbox error_checkbox" value="1">
                {{-- This Span have To Show What in This Label "'label' => $request->missing_item_label ?? 'Missing item'," --}}
                <span>
                    {{$data['extra_fields']['placing_to_basket']["missing_item"]['label'] ?? ($request->missing_item_label ?? 'Missing item')}}
                </span>
                
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['placing_to_basket']['label_wrong_item']['hidden']) style="display: none;" @endif class="label_wrong_item checkbox @if($nearmiss && $nearmiss->placing_basket_label_wrong_item) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->placing_basket_label_wrong_item) checked @endif  name="placing_basket_label_wrong_item" class="placing_basket_label_wrong_item placing_basket_checkbox error_checkbox" value="1">
                {{-- This Span have To Show What in This Label "'label' => $request->label_wrong_item_label ?? 'Label attached to the wrong item'," --}}
                <span>
                    {{$data['extra_fields']['placing_to_basket']["label_wrong_item"]['label'] ?? ($request->label_wrong_item_label ?? 'Label attached to the wrong item')}}
                </span>
                
                
            </label>
        </div>

        
        <!-- Bagging checkboxes -->
        <div data-list="error_bagging_chks" class="highlight min-height-test bagging_2nd_column" @if(!$nearmiss || $nearmiss->what_was_error != 'Bagging') style="display:none" @endif>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['bagging']['wrong_bag_label']['hidden']) style="display: none;" @endif class="wrong_bag_label checkbox @if($nearmiss && $nearmiss->bagging_wrong_bag_label) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->bagging_wrong_bag_label) checked @endif name="bagging_wrong_bag_label" class="bagging_wrong_bag_label bagging_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['bagging']['wrong_bag_label']['label'] ?? 'Wrong bag label' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['bagging']['another_patient_med_in_bag']['hidden']) style="display: none;" @endif class="another_patient_med_in_bag checkbox @if($nearmiss && $nearmiss->bagging_another_patient_med_in_bag) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->bagging_another_patient_med_in_bag) checked @endif name="bagging_another_patient_med_in_bag" class="bagging_another_patient_med_in_bag bagging_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['bagging']['another_patient_med_in_bag']['label'] ?? 'Another patient\'s medication in bag' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['bagging']['missed_items']['hidden']) style="display: none;" @endif class="missed_items checkbox @if($nearmiss && $nearmiss->bagging_missed_items) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->bagging_missed_items) checked @endif name="bagging_missed_items" class="bagging_missed_items bagging_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['bagging']['missed_items']['label'] ?? 'Missed out items' }}</span>
            </label>
        </div>
        
        <!-- Preparing Dosette Tray checkboxes -->
        <div data-list="error_dosette_tray_chks" class="highlight min-height-test preparing_dosette_tray_2nd_column" @if(!$nearmiss || $nearmiss->what_was_error != 'Preparing Dosette Tray') style="display:none" @endif>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['preparing_dosette_tray']['wrong_day_or_time_of_day']) style="display: none;" @endif class="wrong_day_or_time_of_day checkbox @if($nearmiss && $nearmiss->preparing_dosette_tray_wrong_day_time) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->preparing_dosette_tray_wrong_day_time) checked @endif name="preparing_dosette_tray_wrong_day_time" class="preparing_dosette_tray_wrong_day_time preparing_dosette_tray_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['preparing_dosette_tray']['wrong_day_or_time_of_day']['label'] ?? 'Wrong day/time of day' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['preparing_dosette_tray']['error_on_patient_mar_chart']) style="display: none;" @endif class="error_on_patient_mar_chart checkbox @if($nearmiss && $nearmiss->preparing_dosette_tray_error_patient_mar_chart) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->preparing_dosette_tray_error_patient_mar_chart) checked @endif name="preparing_dosette_tray_error_patient_mar_chart" class="preparing_dosette_tray_error_patient_mar_chart preparing_dosette_tray_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['preparing_dosette_tray']['error_on_patient_mar_chart']['label'] ?? 'Error on patient MAR Chart' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['preparing_dosette_tray']['extra_quantity_in_tray']) style="display: none;" @endif class="extra_quantity_in_tray checkbox @if($nearmiss && $nearmiss->preparing_dosette_tray_extra_quantity_on_tray) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->preparing_dosette_tray_extra_quantity_on_tray) checked @endif name="preparing_dosette_tray_extra_quantity_on_tray" class="preparing_dosette_tray_extra_quantity_on_tray preparing_dosette_tray_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['preparing_dosette_tray']['extra_quantity_in_tray']['label'] ?? 'Extra quantity in tray' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['preparing_dosette_tray']['error_in_description_of_the_medication']) style="display: none;" @endif class="error_in_description_of_the_medication checkbox @if($nearmiss && $nearmiss->preparing_dosette_tray_error_in_description) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->preparing_dosette_tray_error_in_description) checked @endif name="preparing_dosette_tray_error_in_description" class="preparing_dosette_tray_error_in_description preparing_dosette_tray_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['preparing_dosette_tray']['error_in_description_of_the_medication']['label'] ?? 'Error in description of the medication' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['preparing_dosette_tray']['wrong_bag_label']) style="display: none;" @endif class="tray_wrong_bag_label checkbox @if($nearmiss && $nearmiss->preparing_dosette_tray_wrong_bag_label) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->preparing_dosette_tray_wrong_bag_label) checked @endif name="preparing_dosette_tray_wrong_bag_label" class="preparing_dosette_tray_wrong_bag_label preparing_dosette_tray_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['preparing_dosette_tray']['wrong_bag_label']['label'] ?? 'Wrong bag label' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['preparing_dosette_tray']['external_item_missing']) style="display: none;" @endif class="external_item_missing checkbox @if($nearmiss && $nearmiss->preparing_dosette_tray_external_item_missing) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->preparing_dosette_tray_external_item_missing) checked @endif name="preparing_dosette_tray_external_item_missing" class="preparing_dosette_tray_external_item_missing preparing_dosette_tray_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['preparing_dosette_tray']['external_item_missing']['label'] ?? 'External item missing' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['preparing_dosette_tray']['tray_item_missing']) style="display: none;" @endif class="tray_item_missing checkbox @if($nearmiss && $nearmiss->preparing_dosette_tray_tray_item_missing) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->preparing_dosette_tray_tray_item_missing) checked @endif name="preparing_dosette_tray_tray_item_missing" class="preparing_dosette_tray_tray_item_missing preparing_dosette_tray_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['preparing_dosette_tray']['tray_item_missing']['label'] ?? 'Tray item missing' }}</span>
            </label>
            <label @if(!isset($data)) @elseif(!$data['extra_fields']['preparing_dosette_tray']['preparing_dosette_tray_error_on_blister_pack']['hidden']) style="display: none;" @endif class="preparing_dosette_tray_error_on_blister_pack checkbox @if($nearmiss && $nearmiss->preparing_dosette_tray_error_on_blister_pack) active @endif">
                <input type="checkbox" @if($nearmiss && $nearmiss->preparing_dosette_tray_error_on_blister_pack) checked @endif name="preparing_dosette_tray_error_on_blister_pack" class="preparing_dosette_tray_error_on_blister_pack preparing_dosette_tray_checkbox error_checkbox" value="1">
                <span>{{ $data['extra_fields']['preparing_dosette_tray']['preparing_dosette_tray_error_on_blister_pack']['label'] ?? 'Error on blister pack guide sheet' }}</span>
            </label>
        </div>
        

         <!-- Handing Out checkboxes -->
         <div data-list="error_handing_out_chks" class="highlight min-height-test handing_out_2nd_column" @if(!$nearmiss || $nearmiss->what_was_error != 'Handing Out') style="display:none" @endif>
            <label @if(!isset($data)) 
            @elseif(!isset($data['extra_fields']['handing_out']['handed_to_wrong_patient']) || !$data['extra_fields']['handing_out']['handed_to_wrong_patient']['hidden']) 
                style="display: none;" 
            @endif 
            class="handed_to_wrong_patient checkbox @if($nearmiss && $nearmiss->handing_out_to_wrong_patient) active @endif">
                    <input type="checkbox" @if($nearmiss && $nearmiss->handing_out_to_wrong_patient) checked @endif name="handing_out_to_wrong_patient" class="handing_out_to_wrong_patient handing_out_checkbox error_checkbox" value="1">
                <span>
                    {{ $data['extra_fields']['handing_out']['handed_to_wrong_patient_label'] ?? ($request->handed_to_wrong_patient_label ?? 'Handed to wrong patient') }}
                </span>
            </label>
        </div>
    </div>
    <div class="col-md-5 col-sm-5 col-xs-5 third_column d-none">
        
        <div class="prescription_3rd_column card card-qi" @if(!$nearmiss || $nearmiss->what_was_error != 'Prescription') style="display:none" @endif>
            <div class="card-body">
                <h5 class="text-info">
                    <input type="checkbox" @if($nearmiss && $nearmiss->what_was_error == 'Prescription') checked @endif class="error_value_prescription error_value_checkboxes"> <span class="card-title">Prescription</span></h5>
                
                <div class="prescription_missing_signature_data prescription_data" @if(!$nearmiss || !$nearmiss->prescription_missing_signature) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="prescription_missing_signature_clone">
                        <label>&nbsp;Missing signature</label>
                    </div> 
                </div>

                <div class="prescription_expired_data  prescription_data" @if(!$nearmiss || !$nearmiss->prescription_expired) style="display:none" @endif>
                
                    <div class="checkbox">   
                        <input type="checkbox" @if($nearmiss && $nearmiss->prescription_expired) checked @endif  class="prescription_expired_clone">
                        <label>&nbsp;Prescription expired</label>
                    </div>
                    <div class="form-group">
                        <label>Does this involve a controlled drug?</label>
                        <select name="prescription_expired_involve_drug" class="form-control prescription_expired_involve_drug">
                            <option value="No" @if($nearmiss && $nearmiss->prescription_expired_involve_drug != 1) selected @endif>No</option>
                            <option value="Yes" @if($nearmiss && $nearmiss->prescription_expired_involve_drug == 1) selected @endif>Yes</option>
                        </select>
                    </div>
                    <div class="checkbox">
                        <div class="form-group prescription_expired_drug_name" @if(!$nearmiss || $nearmiss->prescription_expired_involve_drug != 1) style="display:none" @endif>
                            <label>Drug Name</label>
                            <input type="text" name="prescription_expired_drug_name" @if($nearmiss) value="{{$nearmiss->prescription_expired_drug_name}}" @endif class="form-control drug-field" >
                        </div>
                    </div>
                </div>

                <div class="prescription_old_treatment_data prescription_data" @if(!$nearmiss || !$nearmiss->prescription_old_treatment) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="prescription_old_treatment_clone">
                        <label>&nbsp;Old treatment</label>
                    </div> 
                </div>

                <div class="prescription_tampered_data prescription_data" @if(!$nearmiss || !$nearmiss->prescription_tampered) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="prescription_tampered_clone">
                        <label>&nbsp;Fraudulent/tampered prescription</label>
                    </div> 
                </div>

            </div>
        </div>


        <div class="labelling_3rd_column card card-qi" @if(!$nearmiss || $nearmiss->what_was_error != 'Labelling') style="display:none" @endif>
            <div class="card-body">
                <h5 class="text-info"> 
                    <input type="checkbox" @if($nearmiss && $nearmiss->what_was_error == 'Labelling') checked @endif class="error_value_labelling error_value_checkboxes"> <span class="card-title">Labelling</span></h5>
                <br>
                <div data-list="label_wrong_brand_fields" class="highlight min-height-test labelling_wrong_brand_data labelling_data" @if(!$nearmiss || !$nearmiss->labelling_wrong_brand) style="display:none" @endif>
                    <div class="checkbox active" style="width: fit-content">
                        <input type="checkbox" class="labelling_wrong_brand_clone" checked>
                        <label>&nbsp;Wrong brand</label>
                    </div>
                    <div class="form-group labelling_wrong_brand_prescribed_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label> <span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['prescribed_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_brand_prescribed_mandatory text-danger">*</span> Prescribed Item</label>
                        <input type="text" name="labelling_wrong_brand_drug_prescribed" @if($nearmiss) value="{{$nearmiss->labelling_wrong_brand_drug_prescribed}}" @endif class="form-control drug-field" >
                    </div>
                    <div class="form-group labelling_wrong_brand_labelled_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_brand_labelled_mandatory text-danger">*</span>Labelled Item</label>
                        <input type="text" name="labelling_wrong_brand_drug_labelled" @if($nearmiss) value="{{$nearmiss->labelling_wrong_brand_drug_labelled}}" @endif class="form-control drug-field" >
                    </div>
                </div>

                <div class="labelling_wrong_direction_data labelling_data" @if(!$nearmiss || !$nearmiss->labelling_wrong_direction) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="labelling_wrong_direction_clone">
                        <label>&nbsp;Wrong direction</label>
                    </div> 
                </div>

            <div data-list="label_wrong_item_fields" class="highlight min-height-test labelling_wrong_item_data labelling_data" @if(!$nearmiss || !$nearmiss->labelling_wrong_item) style="display:none" @endif>
                <br>
                <div class="checkbox active" style="width: fit-content;">
                    <input type="checkbox" class="labelling_wrong_item_clone" checked>
                    <label>&nbsp;Wrong item</label>
                </div>
                    <div class="form-group labelling_wrong_item_prescribed_field"  @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_item_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                        <input type="text" name="labelling_wrong_item_drug_prescribed" @if($nearmiss) value="{{$nearmiss->labelling_wrong_item_drug_prescribed}}" @endif class="form-control drug-field" >
                    </div>
                    <div class="form-group labelling_wrong_item_labelled_field"  @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_item_labelled_mandatory text-danger">*</span>Labelled Item</label>
                        <input type="text" name="labelling_wrong_item_drug_labelled" @if($nearmiss) value="{{$nearmiss->labelling_wrong_item_drug_labelled}}" @endif class="form-control drug-field" >
                    </div>
            </div>


            <div data-list="label_wrong_formulation_fields" class="highlight min-height-test labelling_wrong_formulation_data labelling_data" @if(!$nearmiss || !$nearmiss->labelling_wrong_formulation) style="display:none" @endif>
            <br>
            <div class="checkbox active " style="width: fit-content;">
                <input type="checkbox" class="labelling_wrong_formulation_clone" checked>
                <label>&nbsp;Wrong formulation</label>
            </div>
                    <div class="form-group labelling_wrong_formulation_prescribed_field" @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_formulation_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                        <input type="text" name="labelling_wrong_formulation_drug_prescribed" @if($nearmiss) value="{{$nearmiss->labelling_wrong_formulation_drug_prescribed}}" @endif class="form-control drug-field" >
                    </div>
                    <div class="form-group labelling_wrong_formulation_labelled_field"  @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_formulation_labelled_mandatory text-danger">*</span>Labelled Item</label>
                        <input type="text" name="labelling_wrong_formulation_drug_labelled" @if($nearmiss) value="{{$nearmiss->labelling_wrong_formulation_drug_labelled}}" @endif class="form-control drug-field" >
                    </div>
            </div>

                <div class="labelling_wrong_patient_data labelling_data" @if(!$nearmiss || !$nearmiss->labelling_wrong_patient) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="labelling_wrong_patient_clone">
                        <label>&nbsp;Wrong patient</label>
                    </div> 
                </div>

                <div class="labelling_wrong_quantity_data labelling_data" @if(!$nearmiss || !$nearmiss->labelling_wrong_quantity) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="labelling_wrong_quantity_clone">
                        <label>&nbsp;Wrong quantity</label>
                    </div> 
                </div>

            <div data-list="label_wrong_strength_fields" class="highlight min-height-test labelling_wrong_strength_data labelling_data" @if(!$nearmiss || !$nearmiss->labelling_wrong_strength) style="display:none" @endif>
                <br>
                <div class="checkbox active " style="width: fit-content;">
                    <input type="checkbox" class="labelling_wrong_strength_clone" checked>
                    <label>&nbsp;Wrong strength</label>
                </div>
                    <div class="form-group labelling_wrong_strength_prescribed_field" @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_strength_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                        <input type="text" name="labelling_wrong_strength_drug_prescribed" @if($nearmiss) value="{{$nearmiss->labelling_wrong_strength_drug_prescribed}}" @endif class="form-control drug-field" >
                    </div>
                    <div class="form-group labelling_wrong_strength_labelled_field"  @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_strength_labelled_mandatory text-danger">*</span>Labelled Item</label>
                        <input type="text" name="labelling_wrong_strength_drug_labelled" @if($nearmiss) value="{{$nearmiss->labelling_wrong_strength_drug_labelled}}" @endif class="form-control drug-field" >
                    </div>
            </div>
            </div>
        </div>

        <div class="picking_3rd_column card card-qi" @if(!$nearmiss || $nearmiss->what_was_error != 'Picking') style="display:none" @endif>
            <div class="card-body">
                <h5 class="text-info"> 
                    <input type="checkbox"  @if($nearmiss && $nearmiss->what_was_error == 'Picking') checked @endif class="error_value_picking error_value_checkboxes"> <span class="card-title">Picking</span></h5> 
            
                <div class="picking_outdate_item_data picking_data" @if(!$nearmiss || !$nearmiss->picking_out_of_date_item) style="display:none" @endif>
                    <br>
                    <div class="checkbox active">
                        <input type="checkbox" class="picking_outdate_item_clone" checked>
                        <label>&nbsp;Out-of-date item</label>
                    </div>
                    <div class="form-group">
                        <label>Prescribed Item</label>
                        <input type="text" name="picking_out_of_date_item_drug_name" @if($nearmiss) value="{{$nearmiss->picking_out_of_date_item_drug_name}}" @endif  class="form-control drug-field" >
                    </div>
                </div>

            <div data-list="picking_wrong_brand_fields" class="highlight min-height-test picking_wrong_brand_data picking_data" @if(!$nearmiss || !$nearmiss->picking_wrong_brand) style="display:none" @endif>
                <br>
                <div class="checkbox active" style="width:fit-content;" >
                    <input type="checkbox" class="picking_wrong_brand_clone" checked>
                    <label>&nbsp;Wrong brand</label>
                </div>
                <div class="form-group picking_wrong_brand_prescribed_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_brand_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" name="picking_wrong_brand_drug_prescribed" @if($nearmiss) value="{{$nearmiss->picking_wrong_brand_drug_prescribed}}" @endif  class="form-control drug-field" >
                </div>
                <div class="form-group picking_wrong_brand_labelled_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_brand_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" name="picking_wrong_brand_drug_labelled" @if($nearmiss) value="{{$nearmiss->picking_wrong_brand_drug_labelled}}" @endif  class="form-control drug-field" >
                </div>
            </div>
        
            <div data-list="picking_wrong_item__fields" class="highlight min-height-test picking_wrong_item_data picking_data" @if(!$nearmiss || !$nearmiss->picking_wrong_item) style="display:none" @endif>
                <br>
                <div class="checkbox active" style="width:fit-content;">
                    <input type="checkbox" class="picking_wrong_item_clone" checked>
                    <label>&nbsp;Wrong item</label>
                </div>
                <div class="form-group picking_wrong_item_prescribed_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['prescribed_item']['mandatory']) style="display: none;" @endif class="picking_wrong_item_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" name="picking_wrong_item_drug_prescribed" @if($nearmiss) value="{{$nearmiss->picking_wrong_item_drug_prescribed}}" @endif class="form-control drug-field" >
                </div>
                <div class="form-group picking_wrong_item_labelled_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_item_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" name="picking_wrong_item_drug_labelled" @if($nearmiss) value="{{$nearmiss->picking_wrong_item_drug_labelled}}" @endif class="form-control drug-field" >
                </div>
            </div>

            <div class="picking_wrong_quantity_data labelling_data" @if(!$nearmiss || !$nearmiss->picking_wrong_quantity) style="display:none" @endif>
                <div class="checkbox">   
                    <input type="checkbox" checked class="picking_wrong_quantity_clone">
                    <label>&nbsp;Wrong quantity</label>
                </div> 
            </div>

            <div data-list="picking_wrong_strength_fields" class="highlight min-height-test picking_wrong_strength_data picking_data" @if(!$nearmiss || !$nearmiss->picking_wrong_strength) style="display:none" @endif>
                <br>
                <div class="checkbox active" style="width:fit-content;">
                    <input type="checkbox" class="picking_wrong_strength_clone" checked>
                    <label>&nbsp;Wrong strength</label>
                </div>
                <div class="form-group picking_wrong_strength_prescribed_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_strength_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" name="picking_wrong_strength_drug_prescribed" @if($nearmiss) value="{{$nearmiss->picking_wrong_strength_drug_prescribed}}" @endif class="form-control drug-field" >
                </div>
                <div class="form-group picking_wrong_strength_labelled_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_strength_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" name="picking_wrong_strength_drug_labelled" @if($nearmiss) value="{{$nearmiss->picking_wrong_strength_drug_labelled}}" @endif class="form-control drug-field" >
                </div>
        </div>

        <div data-list="picking_wrong_formulation_fields" class="highlight min-height-test picking_wrong_formulation_data picking_data" @if(!$nearmiss || !$nearmiss->picking_wrong_formulation) style="display:none" @endif>
            <br>
            <div class="checkbox active" style="width:fit-content;">
                <input type="checkbox" class="picking_wrong_formulation_clone" checked>
                <label>&nbsp;Wrong formulation</label>
            </div>
            <div class="form-group picking_wrong_formulation_prescribed_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_formulation_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                <input type="text" name="picking_wrong_formulation_drug_prescribed" @if($nearmiss) value="{{$nearmiss->picking_wrong_formulation_drug_prescribed}}" @endif class="form-control drug-field" >
            </div>
            <div class="form-group picking_wrong_formulation_labelled_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_formulation_labelled_mandatory text-danger">*</span>Labelled Item</label>
                <input type="text" name="picking_wrong_formulation_drug_labelled" @if($nearmiss) value="{{$nearmiss->picking_wrong_formulation_drug_labelled}}" @endif class="form-control drug-field" >
            </div>
        </div>
        </div> 
        </div>
        <div class="placing_into_basket_3rd_column card card-qi"  @if(!$nearmiss || $nearmiss->what_was_error != 'Placing into Basket') style="display:none" @endif>
            <div class="card-body">
                <h5 class="text-info"> 
                    <input type="checkbox" @if($nearmiss && $nearmiss->what_was_error == 'Placing into Basket') checked @endif class="error_value_placing_into_basket error_value_checkboxes"> <span class="card-title">Placing into Basket</span></h5>
                
                <div class="placing_basket_another_patient_label_basket_data placing_basket_data" @if(!$nearmiss || !$nearmiss->placing_basket_another_patient_label_basket) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="placing_basket_another_patient_label_basket_clone">
                        <label>&nbsp;Another patient's labels in/on the basket</label>
                    </div> 
                </div>

                <div class="placing_basket_wrong_basket_data placing_basket_data" @if(!$nearmiss || !$nearmiss->placing_basket_wrong_basket) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="placing_basket_wrong_basket_clone">
                        <label>&nbsp;Placed into the wrong basket</label>
                    </div> 
                </div>

                <div class="placing_basket_missing_item_data placing_basket_data" @if(!$nearmiss || !$nearmiss->placing_basket_missing_item) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="placing_basket_missing_item_clone">
                        <label>&nbsp;Missing item</label>
                    </div> 
                </div>

                <div class="placing_basket_label_wrong_item_data placing_basket_data" @if(!$nearmiss || !$nearmiss->placing_basket_label_wrong_item) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="placing_basket_label_wrong_item_clone">
                        <label>&nbsp;Label attached to the wrong item</label>
                    </div> 
                </div>
            
            </div>
        </div>
        
        <div class="bagging_3rd_column card card-qi"  @if(!$nearmiss || $nearmiss->what_was_error != 'Bagging') style="display:none" @endif>
            <div class="card-body">
                <h5 class="text-info">
                <input type="checkbox" @if($nearmiss && $nearmiss->what_was_error == 'Bagging') checked @endif class="error_value_bagging error_value_checkboxes"> <span class="card-title">Bagging</span></h5>

                <div class="bagging_wrong_bag_label_data bagging_data" @if(!$nearmiss || !$nearmiss->bagging_wrong_bag_label) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="bagging_wrong_bag_label_clone">
                        <label>&nbsp;Wrong bag label</label>
                    </div> 
                </div>

                <div class="bagging_another_patient_med_in_bag_data bagging_data" @if(!$nearmiss || !$nearmiss->bagging_another_patient_med_in_bag) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="bagging_another_patient_med_in_bag_clone">
                        <label>&nbsp;Another patient's medication in bag</label>
                    </div> 
                </div>

                <div class="bagging_missed_items_data bagging_data" @if(!$nearmiss || !$nearmiss->bagging_missed_items) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="bagging_missed_items_clone">
                        <label>&nbsp;Missed out items</label>
                    </div> 
                </div>
            
            </div>
        </div>
        <div class="preparing_dosette_tray_3rd_column card card-qi"  @if(!$nearmiss || $nearmiss->what_was_error != 'Preparing Dosette Tray') style="display:none" @endif>
            <div class="card-body">
                <h5 class="text-info">
                    <input type="checkbox" @if($nearmiss && $nearmiss->what_was_error == 'Preparing Dosette Tray') checked @endif class="error_value_preparing_dosette_tray error_value_checkboxes"> <span class="card-title">Preparing Dosette Tray</span>
                </h5>

                <div class="preparing_dosette_tray_wrong_day_time_data preparing_dosette_tray_data" @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_wrong_day_time) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="preparing_dosette_tray_wrong_day_time_clone">
                        <label>&nbsp;Wrong day/time of day</label>
                    </div> 
                </div>
            
                <div class="preparing_dosette_tray_error_patient_mar_chart_data preparing_dosette_tray_data" @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_error_patient_mar_chart) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="preparing_dosette_tray_error_patient_mar_chart_clone">
                        <label>&nbsp;Error on patient MAR Chart</label>
                    </div> 
                </div>

                <div class="preparing_dosette_tray_extra_quantity_on_tray_data preparing_dosette_tray_data" @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_extra_quantity_on_tray) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="preparing_dosette_tray_extra_quantity_on_tray_clone">
                        <label>&nbsp;Extra quantity in tray</label>
                    </div> 
                </div>

                <div class="preparing_dosette_tray_error_in_description_data preparing_dosette_tray_data" @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_error_in_description) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="preparing_dosette_tray_error_in_description_clone">
                        <label>&nbsp;Error in description of the medication</label>
                    </div> 
                </div>

                <div class="preparing_dosette_tray_wrong_bag_label_data preparing_dosette_tray_data" @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_wrong_bag_label) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="preparing_dosette_tray_wrong_bag_label_clone">
                        <label>&nbsp;Wrong bag label</label>
                    </div> 
                </div>

                <div class="preparing_dosette_tray_external_item_missing_data preparing_dosette_tray_data" @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_external_item_missing) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="preparing_dosette_tray_external_item_missing_clone">
                        <label>&nbsp;External item missing</label>
                    </div> 
                </div>

                <div class="preparing_dosette_tray_tray_item_missing_data preparing_dosette_tray_data" @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_tray_item_missing) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="preparing_dosette_tray_tray_item_missing_clone">
                        <label>&nbsp;Tray item missing</label>
                    </div> 
                </div>

                <div class="preparing_dosette_tray_error_on_blister_pack_data preparing_dosette_tray_data" @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_error_on_blister_pack) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="preparing_dosette_tray_error_on_blister_pack_clone">
                        <label>&nbsp;Error on blister pack guide sheet</label>
                    </div> 
                </div>

            </div>
        </div>
        <div class="handing_out_3rd_column card card-qi" @if(!$nearmiss || $nearmiss->what_was_error != 'Handing Out') style="display:none" @endif>
            <div class="card-body">
                <h5 class="text-info">
                    <input type="checkbox"  @if($nearmiss && $nearmiss->what_was_error == 'Handing Out') checked @endif class="error_value_handing_out error_value_checkboxes"> <span class="card-title">Handing Out</span>
                </h5>
                <div class="handing_out_to_wrong_patient_data handing_out_data" @if(!$nearmiss || !$nearmiss->handing_out_to_wrong_patient) style="display:none" @endif>
                    <div class="checkbox">   
                        <input type="checkbox" checked class="handing_out_to_wrong_patient_clone">
                        <label>&nbsp;Handed to wrong patient</label>
                    </div> 
                </div>

            </div>
        </div>


    </div>
</div>