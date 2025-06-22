@foreach($received_alerts as $alerts)
@foreach($alerts as $alert)
<tr class="alert_record">
    <td>              
       {{ date('d/m/Y',strtotime($alert->national_alert->start_time))}}
       <br>  {{ date('h:i a',strtotime($alert->national_alert->start_time))}}

       @if($alert->national_alert->is_overdue() && $alert->status  == App\Models\LocationReceivedAlert::$unactionedStatus)   
            <br><br>
               <div class="overdue"> <i class="fas fa-exclamation-triangle overdue-icon"></i>
                <strong>Overdue</strong> - by {{$alert->national_alert->generateOverDueString()}}!
                </div>
        @elseif(count($alert->actions) == 0) 
            <br><br><span class="new-icon">NEW</span>
        @endif
        <div class="users_action">
            @foreach($alert->actions as $key=>$action)
                <div class="action_person" title="{{$action->user->nameWithPosition()}}">{{$action->user->initials}}</div>
                @if($key == 8)
                    <div class="action_person action_person_green">+{{(count($alert->actions) - 7)}}</div>
                    
                    @break
                @endif
            @endforeach
        </div>
    </td>
    <td>
       {{$alert->national_alert->title}}<br>
       
       @if($alert->national_alert->class !== 'None')
            <span class="alert_class {{$alert->national_alert->alertColor()}}" data-toggle="tooltip"  title="{{$alert->national_alert->getClassDescripiton($alert->national_alert->class)}}">
            
                <span class="alert_class_name">{{$alert->national_alert->class}}</span> 
                <span class="alert_class_description ">{{$alert->national_alert->showClassTitle($alert->national_alert->class)}}</span>
            @endif
        </span>
        <br><br>
        <b>Action Within:</b> {{$alert->national_alert->showActionWithinTitle()}}
    </td>
    <td>
        @if($alert->national_alert->type  != 'None')
            <b>Type:</b> @if($alert->national_alert->type  == 'Custom') {{$alert->national_alert->custom_type }}
            @else {{ $alert->national_alert->type }}
                @if($alert->national_alert->type == 'Company-Led Medicines Recall/Notification' || $alert->national_alert->type == 'Medicines Recall' ) 
                @if($alert->national_alert->patient_level_recall) <br><span class="badge badge-danger">Patient Level Recall</span> @endif
                @endif 
            @endif
            <br><br>
            @endif
         <b>Originator(s):</b>
         @foreach($alert->national_alert->originators as $o) 
                    @if($o->originator == 'Custom')
                        {{$alert->national_alert->custom_originator}} @if(!$loop->last), @endif
                    @else
                    {{$o->originator}}@if(!$loop->last), @endif
                    @endif
                @endforeach
    </td>
    <td>
        <a href="{{route('location.view_patient_safety_alert', $alert->id)}}" title="Preview" class="text-info"><i class="fa fa-eye"></i> </a>
    </td>
    
</tr>
@endforeach
@endforeach