@foreach(App\Models\NearMiss::$contributingFactors as $title=> $factors)
    <div class="contributing_factor_{{strtolower(str_replace(' ','_',$title))}}_section col-md-6
    @if(!isset($data)) 
    @elseif($title == 'Tasks & Workload' && $data['contribution']['tasks']['hidden']) 
    hide
    @elseif(isset($data['contribution'][strtolower($title)]) && $data['contribution'][strtolower($title)]['hidden'])
    hide
    @endif">
        <h5 class="text-info m-t-10">{{$title}}</h5>
    @foreach($factors as $field=>$label)
        @if(Illuminate\Support\Str::contains($field,'other_field'))
            <div class="form-group {{$field}}" @if(!$nearmiss || !$nearmiss->contributing_factor_other) style="display:none" @endif>
                <label>Please type other contributing factor</label>
                <input type="text" name="{{$field}}" class="form-control"  @if($nearmiss) value="{{$nearmiss->$field}}" @endif >
            </div>
        @else
        <label class="checkbox @if($nearmiss && $nearmiss->$field) active @endif"
            @if (!isset($data))
            @elseif($title == 'Tasks & Workload' && !$data['contribution']['tasks'][str_replace(' ', '_', strtolower($label))]) 
            style="display: none;"
            @elseif($label == 'Pharmacist self-checking' && isset($data['contribution'][strtolower($title)]['pharmacist_self_checking']) && !$data['contribution'][strtolower($title)]['pharmacist_self_checking'])
            style="display: none;"
            @elseif(isset($data['contribution'][strtolower($title)][str_replace(' ', '_', strtolower($label))]) && !$data['contribution'][strtolower($title)][str_replace(' ', '_', strtolower($label))])
            style="display: none;"
            @endif>
            <input type="checkbox" name="{{$field}}" class="{{$field}}" @if($nearmiss && $nearmiss->$field) checked @endif value="1">
            <span>
                @php
                    $labelKey = str_replace(' ', '_', strtolower($label)) . '_label';
                    $valueKey = str_replace(' ', '_', strtolower($label));
                    $sections = ['staff', 'tasks', 'person', 'training', 'environment'];
                    $labelValue = null;
            
                    foreach ($sections as $section) {
                        if (isset($data['contribution'][$section][$labelKey])) {
                            $labelValue = $data['contribution'][$section][$labelKey];
                            break;
                        }
                    }
                @endphp
            
                {{ $labelValue ?? $label }}
            </span>
        </label>
        @endif
    @endforeach
    </div>
@endforeach