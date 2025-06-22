@foreach ($near_misses as $date => $records)
    <div class="line line-date date_{{ date('Y_m_d', strtotime($date)) }}">
        <div class="timeline-label">
            @if ($date == date('Y-m-d'))
                TODAY
            @else
                {{ date('D jS F Y', strtotime($date)) }}
            @endif
        </div>
    </div>
    @foreach ($records as $key => $nearMiss)
        <?php
    # Code skips the near misses with drafts to users who did not saved them.
    if($nearMiss->status == 'draft'){
        $user = Auth::guard('web')->user();
        if($user && $nearMiss->user_id != $user->id ){
            ?>
        <div class="nearmiss_record nearmiss_skipped_record" style="display:none"></div>
        <?php
            continue;
        } 
    }
    ?>
        <div
            class="line nearmiss_hidden @if ($counter % 2 == 0) left-record @else  right-record @endif nearmiss_record nearmiss_{{ $nearMiss->id }} nearmiss_status_{{ strtolower(str_replace(' ', '_', $nearMiss->status)) }}">
            <div class="date time">{{ $nearMiss->time() }}</div>
            @if ($nearMiss->status == 'deleted')
                <div class="nearmiss_mini">
                    <span class=" badge badge-danger"><i class="fa fa-eye"></i> {{ strtoupper($nearMiss->status) }}<br>
                        <a href="#" style="display:none" class="click_to_view">Click to view</a>
                    </span>

                </div>
            @endif
            <div class="content-timeline">
                @if ($nearMiss->status == 'draft' || $nearMiss->status == 'deleted')
                    <span
                        class="nearmiss_status nearmiss_status_red badge badge-danger center">{{ strtoupper($nearMiss->status) }}</span>
                @endif
                @if ($nearMiss->status != 'deleted')
                    <div class="actions" style="display:none">
                        <a href="{{ route('location.near_miss', $nearMiss->id) }}" title="Edit" class="text-info"><i
                                class="fa fa-edit"></i></a><br>
                        @if ($nearMiss->canDelete())
                            <a href="#" data-toggle="modal" data-target="#delete_model_{{ $nearMiss->id }}"
                                title="Delete" class="text-info"><i class="fa fa-trash"></i></a>
                        @endif
                    </div>
                @endif
                <h2 class="timeline_category_title">
                    <img class="timeline_icon icon" src="{{ asset('images/' . $nearMiss->icon()) }}" width="32">
                    <span
                        class="timeline_what_was_error_title {{ strtolower(str_replace(' ', '_', $nearMiss->what_was_error)) }}_title">{{ $nearMiss->what_was_error }}</span>
                    <span class="timeline_error_title">{{ $nearMiss->error() }}</span>
                </h2>

                <p class="timeline-drugs-data">{!! $nearMiss->generateDrugsData() !!}</p>
                @if ($nearMiss->status == 'deleted')
                    <p><span class="detail-title">Deleted By: </span> {{ $nearMiss->deletedBy() }}</p>
                    <p><span class="detail-title">Deleted At: </span>
                        {{ date('d/m/Y h:i a', strtotime($nearMiss->deleted_timestamp)) }}</p>
                    <p><span class="detail-title">Deleted Reason: </span> {{ $nearMiss->delete_reason }}</p><br>
                @endif
                <div class="details details_{{ $nearMiss->id }}" style="display:none">
                    @if ($location->near_miss_prescirption_dispensed_at_hub)
                        <p><span class="detail-title">Prescriptions Dispensed: </span>
                            {{ $nearMiss->dispensed_at_hub }}</p><br>
                    @endif
                    <p><span class="detail-title">Point of Detection: </span> {{ $nearMiss->point_of_detection }}</p>
                    <br>
                    <p><span class="detail-title">Error By: </span> {{ $nearMiss->errorBy() }}</p><br>
                    <p><span class="detail-title">Error Detected By: </span> {{ $nearMiss->errorDetectedBy() }}</p><br>
                    <span class="detail-title">Reason:</span><br>
                    <ul>
                        @foreach ($nearMiss->reasons() as $reason)
                            <li>{{ $reason }}</li>
                        @endforeach
                    </ul>
                    <span class="detail-title">Contributing Factors:</span><br>
                    @if (!count($nearMiss->generateContributingFactorsData()))
                        No Factors Found
                    @endif
                    @foreach ($nearMiss->generateContributingFactorsData() as $title => $item)
                        {{ $title }}<br>
                        <ul>
                            @foreach ($item as $checkbox)
                                <li>{{ $checkbox }}</li>
                            @endforeach
                        </ul>
                        <br>
                    @endforeach
                </div>
                <p class="see_details">
                    <a href="#" data-id="{{ $nearMiss->id }}"
                        class="text-info see_details_btn see_details_btn_{{ $nearMiss->id }} ">See Details</a>
                    <a href="#" style="display:none" data-id="{{ $nearMiss->id }}"
                        class="text-info show_less_btn show_less_btn_{{ $nearMiss->id }} ">Show Less</a>
                    @if ($nearMiss->status == 'deleted')
                        <a href="#" data-id="{{ $nearMiss->id }}"
                            class="text-info hide_deleted_btn hide_deleted_btn_{{ $nearMiss->id }} ">| Hide</a>
                    @endif
                </p>
                <!-- <p class="see_details">
                
            </p> -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal modal-md fade" id="delete_model_{{ $nearMiss->id }}" tabindex="-1" role="dialog"
            aria-hidden="true">
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
                            <form method="post" action="{{ route('location.near_miss.delete', $nearMiss->id) }}">
                                @csrf
                                <div class="form-group">
                                    <label>Reason for deleting this near misss?</label>
                                    <input type="text" name="delete_reason" class="form-control" required
                                        value="">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-info">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php $counter++; ?>
    @endforeach
@endforeach
