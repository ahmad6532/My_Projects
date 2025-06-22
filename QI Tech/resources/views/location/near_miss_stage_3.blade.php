<div class="causes_prescription_missing_signature row" @if(!$nearmiss || !$nearmiss->prescription_missing_signature) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_prescription_name'] ?? 'Prescription' }}</span></h5>
                <p>{{ $data['extra_fields']['prescription']['missing_signature']['label'] ?? 'Missing signature' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="prescription_missing_signature_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$PrescriptionReasonsOfNearMiss['prescription_missing_signature'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->prescription_missing_signature_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['prescription']['missing_signature']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['prescription']['missing_signature']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>





<div class="causes_prescription_expired row" @if(!$nearmiss || !$nearmiss->prescription_expired) style="display:none" @endif>

    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_prescription_name'] ?? 'Prescription' }}</span></h5>
                <p><span>{{ $data['extra_fields']['prescription']['prescription_expired']['label'] ?? 'Prescription expired' }}</span></p>
                <div class="form-group">
                    <label>Does this involve a controlled drug?</label>
                    <select class="form-control prescription_expired_involve_drug_clone">
                        <option value="No"  @if($nearmiss && $nearmiss->prescription_expired_involve_drug != 1) selected @endif>No</option>
                        <option value="Yes" @if($nearmiss && $nearmiss->prescription_expired_involve_drug == 1) selected @endif>Yes</option>
                    </select>
                </div>
                <div class="checkbox">
                    <div class="form-group prescription_expired_drug_name_clone" @if(!$nearmiss || $nearmiss->prescription_expired_involve_drug != 1) style="display:none" @endif>
                        <label>Drug Name</label>
                        <input type="text" class="form-control prescription_expired_drug_name_clone_field"   @if($nearmiss) value="{{$nearmiss->prescription_expired_drug_name}}" @endif>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="prescription_expired_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$PrescriptionReasonsOfNearMiss['prescription_expired'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->prescription_expired_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox {{$field}}_field @if($nearmiss && $nearmiss->$field) active @endif" @if(!isset($data)) @elseif(!$data['extra_fields']['prescription']['prescription_expired']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['prescription']['prescription_expired']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<div class="causes_prescription_old_treatment row"  @if(!$nearmiss || !$nearmiss->prescription_old_treatment) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_prescription_name'] ?? 'Prescription' }}</span></h5>
                <p>{{$data && isset($data['extra_fields']['prescription']['old_treatment']['label']) ? $data['extra_fields']['prescription']['old_treatment']['label'] : 'Old treatment'}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="prescription_old_treatment_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$PrescriptionReasonsOfNearMiss['prescription_old_treatment'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->prescription_old_treatment_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['prescription']['old_treatment']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['prescription']['old_treatment']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<div class="causes_prescription_tampered row" @if(!$nearmiss || !$nearmiss->prescription_tampered) style="display:none" @endif>

    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_prescription_name'] ?? 'Prescription' }}</span></h5>
                <p>{{$data && isset($data['extra_fields']['prescription']['fraudulent_tampered_prescription']['label']) ? $data['extra_fields']['prescription']['fraudulent_tampered_prescription']['label'] : 'Fraudulent/tampered prescription'}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="prescription_tampered_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$PrescriptionReasonsOfNearMiss['prescription_tampered'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->prescription_tampered_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['prescription']['fraudulent_tampered_prescription']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['prescription']['fraudulent_tampered_prescription']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Labelling Started -->
<!-- Wrong Brand -->
<div class="causes_labelling_wrong_brand row" @if(!$nearmiss || !$nearmiss->labelling_wrong_brand) style="display:none" @endif>
    
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_labelling_name'] ?? 'Labelling' }}</span></h5>
                <p><span>{{ $data['extra_fields']['labelling']['wrong_brand']['wrong_brand_text'] ?? 'Wrong brand' }}</span></p>
                <div class="form-group labelling_wrong_brand_prescribed_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label> <span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['prescribed_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_brand_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" class="form-control labelling_wrong_brand_drug_prescribed_clone" @if($nearmiss) value="{{$nearmiss->labelling_wrong_brand_drug_prescribed}}" @endif >
                </div>
                <div class="form-group labelling_wrong_brand_labelled_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_brand_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" class="form-control labelling_wrong_brand_drug_labelled_clone" @if($nearmiss) value="{{$nearmiss->labelling_wrong_brand_drug_labelled}}" @endif >
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="labelling_wrong_brand_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$LabellingReasonsOfNearMiss['labelling_wrong_brand'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->labelling_wrong_brand_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_brand']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['labelling']['wrong_brand']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Wrong Direction -->
<div class="causes_labelling_wrong_direction row" @if(!$nearmiss || !$nearmiss->labelling_wrong_direction) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_labelling_name'] ?? 'Labelling' }}</span></h5>
                <p><span>{{ $data['extra_fields']['labelling']['wrong_direction']['wrong_direction_label'] ?? 'Wrong direction' }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="labelling_wrong_direction_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$LabellingReasonsOfNearMiss['labelling_wrong_direction'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->labelling_wrong_direction_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_direction']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['labelling']['wrong_direction']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Wrong Item -->
<div class="causes_labelling_wrong_item row" @if(!$nearmiss || !$nearmiss->labelling_wrong_item) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_labelling_name'] ?? 'Labelling' }}</span></h5>
                <p><span>{{ $data['extra_fields']['labelling']['wrong_item']['wrong_item_text'] ?? 'Wrong item' }}</span></p>
                <div class="form-group labelling_wrong_item_prescribed_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label> <span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['prescribed_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_item_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" class="form-control labelling_wrong_item_drug_prescribed_clone" @if($nearmiss) value="{{$nearmiss->labelling_wrong_item_drug_prescribed}}" @endif >
                </div>
                <div class="form-group labelling_wrong_item_labelled_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_item_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" class="form-control labelling_wrong_item_drug_labelled_clone" @if($nearmiss) value="{{$nearmiss->labelling_wrong_item_drug_labelled}}" @endif >
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="labelling_wrong_item_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$LabellingReasonsOfNearMiss['labelling_wrong_item'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->labelling_wrong_item_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_item']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['labelling']['wrong_item']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Wrong Formulation -->
<div class="causes_labelling_wrong_formulation row" @if(!$nearmiss || !$nearmiss->labelling_wrong_formulation) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_labelling_name'] ?? 'Labelling' }}</span></h5>
                <p><span>{{ $data['extra_fields']['labelling']['wrong_formulation']['wrong_formulation_text'] ?? "Wrong formulation" }} </span></p>
                <div class="form-group labelling_wrong_formulation_prescribed_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label> <span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['prescribed_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_formulation_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" class="form-control labelling_wrong_formulation_drug_prescribed_clone" @if($nearmiss) value="{{$nearmiss->labelling_wrong_formulation_drug_prescribed}}" @endif >
                </div>
                <div class="form-group labelling_wrong_formulation_labelled_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_formulation_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" class="form-control labelling_wrong_formulation_drug_labelled_clone" @if($nearmiss) value="{{$nearmiss->labelling_wrong_formulation_drug_labelled}}" @endif >
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="labelling_wrong_formulation_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$LabellingReasonsOfNearMiss['labelling_wrong_formulation'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->labelling_wrong_formulation_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_formulation']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['labelling']['wrong_formulation']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Wrong Patient -->
<div class="causes_labelling_wrong_patient row" @if(!$nearmiss || !$nearmiss->labelling_wrong_patient) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_labelling_name'] ?? 'Labelling' }}</span></h5>
                <p><span>{{ $data['extra_fields']['labelling']['wrong_patient']['wrong_patient_label'] ?? 'Wrong patient' }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="labelling_wrong_patient_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$LabellingReasonsOfNearMiss['labelling_wrong_patient'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->labelling_wrong_patient_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_patient']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['labelling']['wrong_patient']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>


<!-- Wrong quantity -->
<div class="causes_labelling_wrong_quantity row" @if(!$nearmiss || !$nearmiss->labelling_wrong_quantity) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_labelling_name'] ?? 'Labelling' }}</span></h5>
                <p><span>{{ $data['extra_fields']['labelling']['wrong_quantity']['wrong_quantity_text'] ?? 'Wrong quantity' }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="labelling_wrong_quantity_reasons">
        <h5 class="text-info">Reason</h5>       
        @foreach(App\Models\NearMiss::$LabellingReasonsOfNearMiss['labelling_wrong_quantity'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->labelling_wrong_quantity_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_quantity']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['labelling']['wrong_quantity']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach

    </div>
</div>
<!-- Wrong strength -->
<div class="causes_labelling_wrong_strength row" @if(!$nearmiss || !$nearmiss->labelling_wrong_strength) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_labelling_name'] ?? 'Labelling' }}</span></h5>
                <p><span>{{ $data['extra_fields']['labelling']['wrong_strength']['wrong_strength_text'] ?? 'Wrong strength' }}</span></p>
                <div class="form-group labelling_wrong_strength_prescribed_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['prescribed_item']['hidden']) style="display: none;" @endif>
                        <label> <span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['prescribed_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_strength_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" class="form-control labelling_wrong_strength_drug_prescribed_clone" @if($nearmiss) value="{{$nearmiss->labelling_wrong_strength_drug_prescribed}}" @endif>
                </div>
                <div class="form-group labelling_wrong_strength_labelled_field " @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['labelled_item']['mandatory']) style="display: none;" @endif class="labelling_wrong_strength_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" class="form-control labelling_wrong_strength_drug_labelled_clone" @if($nearmiss) value="{{$nearmiss->labelling_wrong_strength_drug_labelled}}" @endif >
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="labelling_wrong_strength_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$LabellingReasonsOfNearMiss['labelling_wrong_strength'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->labelling_wrong_strength_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['labelling']['wrong_strength']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['labelling']['wrong_strength']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Picking -->

<!-- Outdate Item -->
<div class="causes_picking_out_of_date_item row" @if(!$nearmiss || !$nearmiss->picking_out_of_date_item) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_picking_name'] ?? 'Picking' }}</span></h5>
                <p><span>{{ $data['extra_fields']['picking']['out_of_date_item']['label'] ?? 'Out-of-date item' }}</span></p>
                <div class="form-group">
                    <label>Prescribed Item</label>
                    <input type="text" class="form-control picking_out_of_date_item_drug_name_clone" @if($nearmiss) value="{{$nearmiss->picking_out_of_date_item_drug_name}}" @endif  >
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="picking_out_of_date_item_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$PickingReasonsOfNearMiss['picking_out_of_date_item'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->picking_out_of_date_item_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['out_of_date_item']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['picking']['out_of_date_item']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Wrong brand -->
<div class="causes_picking_wrong_brand row" @if(!$nearmiss || !$nearmiss->picking_wrong_brand) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_picking_name'] ?? 'Picking' }}</span></h5>
                <p><span>{{ $data['extra_fields']['picking']['wrong_brand']['label'] ?? 'Wrong brand' }}</span></p>
                <div class="form-group picking_wrong_brand_prescribed_field " @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['prescribed_item']['hidden']) style="display: none;" @endif><label> <span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['prescribed_item']['mandatory']) style="display: none;" @endif class="picking_wrong_brand_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" class="form-control picking_wrong_brand_drug_prescribed_clone" @if($nearmiss) value="{{$nearmiss->picking_wrong_brand_drug_prescribed}}" @endif >
                </div>
                <div class="form-group picking_wrong_brand_labelled_field " @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_brand_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" class="form-control picking_wrong_brand_drug_labelled_clone" @if($nearmiss) value="{{$nearmiss->picking_wrong_brand_drug_labelled}}" @endif >
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="picking_wrong_brand_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$PickingReasonsOfNearMiss['picking_wrong_brand'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->picking_wrong_brand_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_brand']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['picking']['wrong_brand']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Wrong item -->
<div class="causes_picking_wrong_item row" @if(!$nearmiss || !$nearmiss->picking_wrong_item) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_picking_name'] ?? 'Picking' }}</span></h5>
                <p><span>{{ $data['extra_fields']['picking']['wrong_item']['label'] ?? 'Wrong item' }}</span></p>
                <div class="form-group picking_wrong_item_prescribed_field " @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['prescribed_item']['hidden']) style="display: none;" @endif><label> <span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['prescribed_item']['mandatory']) style="display: none;" @endif class="picking_wrong_item_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" class="form-control picking_wrong_item_drug_prescribed_clone" @if($nearmiss) value="{{$nearmiss->picking_wrong_item_drug_prescribed}}" @endif >
                </div>
                <div class="form-group picking_wrong_item_labelled_field " @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_item_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" class="form-control picking_wrong_item_drug_labelled_clone" @if($nearmiss) value="{{$nearmiss->picking_wrong_item_drug_labelled}}" @endif >
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="picking_wrong_item_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$PickingReasonsOfNearMiss['picking_wrong_item'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->picking_wrong_item_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_item']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['picking']['wrong_item']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Picking Wrong quantity -->
<div class="causes_picking_wrong_quantity row" @if(!$nearmiss || !$nearmiss->picking_wrong_quantity) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_picking_name'] ?? 'Picking' }}</span></h5>
                <p><span>{{ $data['extra_fields']['picking']['wrong_quantity']['label'] ?? 'Wrong quantity' }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="picking_wrong_quantity_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$PickingReasonsOfNearMiss['picking_wrong_quantity'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->picking_wrong_quantity_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_quantity']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['picking']['wrong_quantity']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Picking Wrong strength -->
<div class="causes_picking_wrong_strength row" @if(!$nearmiss || !$nearmiss->picking_wrong_strength) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_picking_name'] ?? 'Picking' }}</span></h5>
                <p><span>{{ $data['extra_fields']['picking']['wrong_strength']['label'] ?? 'Wrong strengths' }}</span></p>
                <div class="form-group picking_wrong_strength_prescribed_field " @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['prescribed_item']['hidden']) style="display: none;" @endif><label> <span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['prescribed_item']['mandatory']) style="display: none;" @endif class="picking_wrong_strength_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" class="form-control picking_wrong_strength_drug_prescribed_clone" @if($nearmiss) value="{{$nearmiss->picking_wrong_strength_drug_prescribed}}" @endif >
                </div>
                <div class="form-group picking_wrong_strength_labelled_field " @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_strength_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" class="form-control picking_wrong_strength_drug_labelled_clone" @if($nearmiss) value="{{$nearmiss->picking_wrong_strength_drug_labelled}}" @endif >
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="picking_wrong_strength_reasons">
        <h5 class="text-info">Reason</h5>   
        @foreach(App\Models\NearMiss::$PickingReasonsOfNearMiss['picking_wrong_strength'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->picking_wrong_strength_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_strength']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['picking']['wrong_strength']['reason'][$field.'_label'] ?? $label }}</span>
                </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Picking Wrong formulation -->
<div class="causes_picking_wrong_formulation row" @if(!$nearmiss || !$nearmiss->picking_wrong_formulation) style="display:none" @endif>

    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_picking_name'] ?? 'Picking' }}</span></h5>
                <p><span>{{ $data['extra_fields']['picking']['wrong_formulation']['label'] ?? 'Wrong formulation' }}</span></p>
                <div class="form-group picking_wrong_formulation_prescribed_field " @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['prescribed_item']['hidden']) style="display: none;" @endif><label> <span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['prescribed_item']['mandatory']) style="display: none;" @endif class="picking_wrong_formulation_prescribed_mandatory text-danger">*</span>Prescribed Item</label>
                    <input type="text" class="form-control picking_wrong_formulation_drug_prescribed_clone" @if($nearmiss) value="{{$nearmiss->picking_wrong_formulation_drug_prescribed}}" @endif >
                </div>
                <div class="form-group picking_wrong_formulation_labelled_field " @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['labelled_item']['hidden']) style="display: none;" @endif>
                        <label><span @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['labelled_item']['mandatory']) style="display: none;" @endif class="picking_wrong_formulation_labelled_mandatory text-danger">*</span>Labelled Item</label>
                    <input type="text" class="form-control picking_wrong_formulation_drug_labelled_clone" @if($nearmiss) value="{{$nearmiss->picking_wrong_formulation_drug_labelled}}" @endif >
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="picking_wrong_formulation_reasons">
        <h5 class="text-info">Reason</h5>   
        @foreach(App\Models\NearMiss::$PickingReasonsOfNearMiss['picking_wrong_formulation'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->picking_wrong_formulation_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['picking']['wrong_formulation']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['picking']['wrong_formulation']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>


<!-- Placing Basket Another patient's labels in/on the basket -->
<div class="causes_placing_basket_another_patient row" @if(!$nearmiss || !$nearmiss->placing_basket_another_patient_label_basket) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_placing_into_basket_name'] ?? 'Placing into Basket' }}</span></h5>
                <p>{{$data['extra_fields']['placing_to_basket']["another_patient_label_basket"]['label'] ?? ($request->another_patient_label_basket_label ?? 'Another patient\'s labels in/on the basket')}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="placing_basket_another_patient_label_basket_reasons">
        <h5 class="text-info">Reason</h5> 
        @foreach(App\Models\NearMiss::$PlacingIntoBasketReasonsOfNearMiss['placing_basket_another_patient_label_basket'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->placing_basket_another_patient_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['placing_to_basket']['another_patient_label_basket']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['placing_to_basket']['another_patient_label_basket']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Placing Basket Placed into the wrong basket -->
<div class="causes_placing_basket_wrong_basket row" @if(!$nearmiss || !$nearmiss->placing_basket_wrong_basket) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_placing_into_basket_name'] ?? 'Placing into Basket' }}</span></h5>
                <p>{{$data['extra_fields']['placing_to_basket']["wrong_basket"]['label'] ?? ($request->wrong_basket_label ?? 'Placed into the wrong basket')}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="placing_basket_wrong_basket_reasons">
        <h5 class="text-info">Reason</h5> 
        @foreach(App\Models\NearMiss::$PlacingIntoBasketReasonsOfNearMiss['placing_basket_wrong_basket'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->placing_basket_wrong_basket_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['placing_to_basket']['wrong_basket']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['placing_to_basket']['wrong_basket']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Placing Basket Missing Item -->
<div class="causes_placing_basket_missing_item row" @if(!$nearmiss || !$nearmiss->placing_basket_missing_item) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_placing_into_basket_name'] ?? 'Placing into Basket' }}</span></h5>
                <p>{{$data['extra_fields']['placing_to_basket']["missing_item"]['label'] ?? ($request->missing_item_label ?? 'Missing item')}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="placing_basket_missing_item_reasons">
        <h5 class="text-info">Reason</h5> 
        @foreach(App\Models\NearMiss::$PlacingIntoBasketReasonsOfNearMiss['placing_basket_missing_item'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->placing_basket_missing_item_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['placing_to_basket']['missing_item']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['placing_to_basket']['missing_item']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Placing Basket Labels to Wrong Item -->
<div class="causes_placing_basket_wrong_item row" @if(!$nearmiss || !$nearmiss->placing_basket_label_wrong_item) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_placing_into_basket_name'] ?? 'Placing into Basket' }}</span></h5>
                <p>{{$data['extra_fields']['placing_to_basket']["label_wrong_item"]['label'] ?? ($request->label_wrong_item_label ?? 'Label attached to the wrong item')}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="placing_basket_label_wrong_item_reasons">
        <h5 class="text-info">Reason</h5> 
        @foreach(App\Models\NearMiss::$PlacingIntoBasketReasonsOfNearMiss['placing_basket_label_wrong_item'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->placing_basket_wrong_item_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['placing_to_basket']['label_wrong_item']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['placing_to_basket']['label_wrong_item']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Bagging Wrong bag label -->
<div class="causes_bagging_wrong_bag_label row" @if(!$nearmiss || !$nearmiss->bagging_wrong_bag_label) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_bagging_name'] ?? 'Bagging' }}</span></h5>
                <p><span>{{ $data['extra_fields']['bagging']['wrong_bag_label']['label'] ?? 'Wrong bag label' }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="bagging_wrong_bag_label_reasons">
        <h5 class="text-info">Reason</h5> 
        @foreach(App\Models\NearMiss::$BaggingReasonsOfNearMiss['bagging_wrong_bag_label'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->bagging_wrong_bag_label_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['bagging']['wrong_bag_label']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['bagging']['wrong_bag_label']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Bagging Wrong bag label -->
<div class="causes_bagging_another_patient_med_in_bag row" @if(!$nearmiss || !$nearmiss->bagging_another_patient_med_in_bag) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_bagging_name'] ?? 'Bagging' }}</span></h5>
                <p><span>{{ $data['extra_fields']['bagging']['another_patient_med_in_bag']['label'] ?? 'Another patient\'s medication in bag' }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="bagging_another_patient_med_in_bag_reasons">
        <h5 class="text-info">Reason</h5> 
        @foreach(App\Models\NearMiss::$BaggingReasonsOfNearMiss['bagging_another_patient_med_in_bag'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->bagging_another_patient_med_in_bag_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['bagging']['another_patient_med_in_bag']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['bagging']['another_patient_med_in_bag']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Bagging Missed Out Items -->
<div class="causes_bagging_missed_items row" @if(!$nearmiss || !$nearmiss->bagging_missed_items) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_bagging_name'] ?? 'Bagging' }}</span></h5>
                <p><span>{{ $data['extra_fields']['bagging']['missed_items']['label'] ?? 'Missed out items' }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="bagging_missed_items_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$BaggingReasonsOfNearMiss['bagging_missed_items'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->bagging_missed_items_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['bagging']['missed_items']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['bagging']['missed_items']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Preparing Dosette Tray-->
<div class="causes_preparing_dosette_tray_error_on_blistring_pack row" @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_error_on_blister_pack) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_preparing_dosette_tray_name'] ?? 'Preparing Dosette Tray' }}</span></h5>
                <p><span>{{ $data['extra_fields']['preparing_dosette_tray']['preparing_dosette_tray_error_on_blister_pack']['label'] ?? 'Error on blister pack guide sheet' }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="preparing_dosette_tray_error_on_blister_pack_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$PreparingDosetteTrayReasonsOfNearMiss['preparing_dosette_tray_error_on_blister_pack'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->preparing_dosette_tray_blistring_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['preparing_dosette_tray']['preparing_dosette_tray_error_on_blister_pack']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['bagging']['preparing_dosette_tray_error_on_blister_pack']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>

<!-- Handing Out-->
<div class="causes_handing_out_to_wrong_patient row" @if(!$nearmiss || !$nearmiss->handing_out_to_wrong_patient) style="display:none" @endif>
    <div class="col-md-4">
        <div class="card card-qi">
            <div class="card-body">
                <h5 class="text-info"><span class="card-title">{{ $data['what']['what_was_error']['error_handing_out_name'] ?? 'Handing Out' }}</span></h5>
                <p>{{ $data['extra_fields']['handing_out']['handed_to_wrong_patient_label'] ?? ($request->handed_to_wrong_patient_label ?? 'Handed to wrong patient') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8 high" data-list="handing_out_to_wrong_patient_reasons">
        <h5 class="text-info">Reason</h5>
        @foreach(App\Models\NearMiss::$HandingReasonsOfNearMiss['handing_out_to_wrong_patient'] as $field=> $label)
            @if(Illuminate\Support\Str::contains($field,'other_field'))
                <div class="form-group {{$field}}"  @if(!$nearmiss || !$nearmiss->handing_out_to_wrong_patient_cause_other) style="display:none" @endif>
                    <label>Type reason for error</label>
                    <input type="text" name="{{$field}}"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif class="form-control" >
                </div>
            @else
            <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif {{$field}}_field" @if(!isset($data)) @elseif(!$data['extra_fields']['handing_out']['handed_to_wrong_patient']['reason'][$field.'_field']) style="display: none;" @endif>
                <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
                <span>{{$data['extra_fields']['handing_out']['handed_to_wrong_patient']['reason'][$field.'_label'] ?? $label }}</span>
            </label>
            @endif
        @endforeach
    </div>
</div>