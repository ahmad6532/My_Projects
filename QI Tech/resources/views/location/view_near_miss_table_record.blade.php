@foreach($near_misses as $records)
@foreach($records as $nearMiss)
<?php
    # Code skips the near misses with drafts to users who did not saved them.
    if($nearMiss->status == 'draft'){
        $user = Auth::guard('web')->user();
        if($user && $nearMiss->user_id != $user->id ){
            ?>
            <tr class="nearmiss_record" style="display:none"></tr>
            <?php
            continue;
        } 
    }
    ?>
<tr class="nearmiss_record">
    <td>
        <b>Date:</b><br> ({{$nearMiss->day()}}) {{$nearMiss->date()}}
        <br><br>
        <b>Time:</b><br> {{$nearMiss->time()}}
        @if($location->near_miss_prescirption_dispensed_at_hub)
        <br><br><b>Prescriptions Dispensed: </b> {{$nearMiss->dispensed_at_hub}}<br>
        @endif
    </td>
    <td>
        {{$nearMiss->what_was_error}} {{$nearMiss->error()}} 
        @if($nearMiss->status == 'draft' || $nearMiss->status == 'deleted')<span class="nearmiss_status nearmiss_status_red badge badge-danger center">{{strtoupper($nearMiss->status)}}</span>@endif
        @if($nearMiss->status == 'deleted')
        <br><br> <span class="detail-title">Deleted By: </span> {{$nearMiss->deletedBy()}}<br>
                <span class="detail-title">Deleted At: </span> {{date('d/m/Y h:i a',strtotime($nearMiss->deleted_timestamp))}}<br>
                <span class="detail-title">Deleted Reason: </span> {{$nearMiss->delete_reason}}<br>
            @endif
    </td>
    <td>{!! $nearMiss->generateDrugsData() !!}</td>
    <td>{{$nearMiss->point_of_detection}}</td>
    <td>Reason(s):<br>
        <ul>
            @foreach($nearMiss->reasons() as $reason)
                <li>{{$reason}}</li>
            @endforeach
        </ul>
        Contributing Factors:<br>
        @foreach($nearMiss->generateContributingFactorsData() as $title=>$item)
        {{$title}}<br>
        <ul>
            @foreach($item as $checkbox)
            <li>{{$checkbox}}</li>
            @endforeach
        </ul>
        @endforeach
    </td>
    <td>Error By:
        <ul>
            <li>{{$nearMiss->errorBy()}}</li>
        </ul>
        <br>
        Detected By:
        <ul>
            <li>{{$nearMiss->errorDetectedBy()}}</li>
        </ul>
        <br>
    </td>
    <td>
        <a href="{{route('location.near_miss', $nearMiss->id)}}" title="Edit" class="text-info"><i class="fa fa-edit"></i> </a>
        @if($nearMiss->status != 'delete' && $nearMiss->canDelete())
        <a href="#" data-toggle="modal" data-target="#delete_table_model_{{$nearMiss->id}}" title="Delete" class="text-info"><i class="fa fa-trash"></i></a>
        @endif
        <div class="modal modal-md fade" id="delete_table_model_{{$nearMiss->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="text-danger">Why are you deleting this?</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2">
                        <form method="post" action="{{route('location.near_miss.delete', $nearMiss->id)}}">
                            @csrf
                            <div class="form-group">
                                <label>Reason for deleting this near misss?</label>
                                <input type="text" name="delete_reason" class="form-control" required value="">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel Delete</button>
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
    
</tr>
@endforeach
@endforeach