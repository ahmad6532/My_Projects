@foreach($received_alerts as $year => $records)
    <div class="line line-date date_{{$year}}">
        <div class="timeline-label">{{$year}}</div>
    </div>
    @foreach($records as $key=>$alert)
    <div class="line @if( $counter%2  == 0) left-record @else  right-record @endif alert_record alert_record_{{$alert->id}} alert_status_{{strtolower(str_replace(' ','_',$alert->status))}}">
    @if($alert->national_alert->is_overdue() && $alert->status  == App\Models\LocationReceivedAlert::$unactionedStatus) 
        <i class="fas fa-exclamation-triangle overdue-icon"></i> 
    @elseif(count($alert->actions) == 0) <span class="overdue-icon new-icon">NEW</span>
    @endif
        <div class="date time">{{$alert->timeline_date()}}<br>{{$year}}</div>
        <div class="content-timeline">
            <h2 class="timeline_category_title">
                <span class="timeline_what_was_error_title">{{$alert->national_alert->title}}</span>
                <div class="alert_class {{$alert->national_alert->alertColor()}}" data-toggle="tooltip"  title="{{$alert->national_alert->getClassDescripiton($alert->national_alert->class)}}">
                    @if($alert->national_alert->class !== 'None')
                    <span class="alert_class_name">{{$alert->national_alert->class}}</span> 
                    <span class="alert_class_description ">{{$alert->national_alert->showClassTitle($alert->national_alert->class)}}</span>
                    @endif
                </div>
            </h2>
            <div class="alert_summary">
                {{$alert->national_alert->short_summary()}}
            </div>
            <div class="details details_{{$alert->id}}" style="display:none">
            </div>
            <p class="see_details">
                <a href="{{route('location.view_patient_safety_alert', $alert->id)}}" data-id="{{$alert->id}}" class="text-info see_details_btn_{{$alert->id}} ">See Details</a>
            </p>       
        </div>
            @if($alert->national_alert->is_overdue() && $alert->status  == App\Models\LocationReceivedAlert::$unactionedStatus)
                <div class="overdue"><strong>Overdue</strong> - by {{$alert->national_alert->generateOverDueString()}}!</div>
            @endif
            <div class="users_action">
            @foreach($alert->actions as $key=>$action)
                <div class="action_person" title="{{$action->user->nameWithPosition()}}">{{$action->user->initials}}</div>
                @if($key == 11)
                    <div class="action_person action_person_green">+{{(count($alert->actions) - 7)}}</div>
                    @break
                @endif
            @endforeach
            </div>
    </div>
        <?php $counter++ ?> 
    @endforeach
@endforeach



