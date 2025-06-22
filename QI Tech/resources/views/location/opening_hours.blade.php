<div class="modal modal-md  fade" id="opening_hours_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">Location Opening Hours</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-2">
                <form method="post" action="{{ route('location.update_opening_hours') }}">
                @csrf
                <div class="table-responsive">
                    <table class="table">
                        <thead class="">
                            <tr>
                                <th></th>
                                <th>Day</th>
                                <th>Open Time</th>
                                <th>Close Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Monday</td>
                            <td><input type="checkbox" name="open_monday" class="open_monday" value="1" @if($location->opening_hours->open_monday) checked @endif></td>
                            <td><input type="time" @if(!$location->opening_hours->open_monday) disabled @endif class="form-control monday_start_time" name="monday_start_time" value="{{$location->opening_hours->monday_start_time}}"></td>
                            <td><input type="time" @if(!$location->opening_hours->open_monday) disabled @endif class="form-control monday_end_time" name="monday_end_time" value="{{$location->opening_hours->monday_end_time}}"></td>
                        </tr>
                        <tr>
                            <td>Tuesday</td>
                            <td><input type="checkbox" name="open_tuesday" class="open_tuesday" value="1" @if($location->opening_hours->open_tuesday) checked @endif></td>
                            <td><input type="time" @if(!$location->opening_hours->open_tuesday) disabled @endif class="form-control tuesday_start_time" name="tuesday_start_time" value="{{$location->opening_hours->tuesday_start_time}}"></td>
                            <td><input type="time" @if(!$location->opening_hours->open_tuesday) disabled @endif class="form-control tuesday_end_time" name="tuesday_end_time" value="{{$location->opening_hours->tuesday_end_time}}"></td>
                        </tr>
                        <tr>
                            <td>Wednesday</td>
                            <td><input type="checkbox" name="open_wednesday" class="open_wednesday" value="1" @if($location->opening_hours->open_wednesday) checked @endif></td>
                            <td><input type="time" @if(!$location->opening_hours->open_wednesday) disabled @endif class="form-control wednesday_start_time" name="wednesday_start_time" value="{{$location->opening_hours->wednesday_start_time}}"></td>
                            <td><input type="time" @if(!$location->opening_hours->open_wednesday) disabled @endif class="form-control wednesday_end_time" name="wednesday_end_time" value="{{$location->opening_hours->wednesday_end_time}}"></td>
                        </tr>
                        <tr>
                            <td>Thursday</td>
                            <td><input type="checkbox" name="open_thursday" class="open_thursday" value="1" @if($location->opening_hours->open_thursday) checked @endif></td>
                            <td><input type="time" @if(!$location->opening_hours->open_thursday) disabled @endif class="form-control thursday_start_time" name="thursday_start_time" value="{{$location->opening_hours->thursday_start_time}}"></td>
                            <td><input type="time" @if(!$location->opening_hours->open_thursday) disabled @endif class="form-control thursday_end_time" name="thursday_end_time" value="{{$location->opening_hours->thursday_end_time}}"></td>
                        </tr>
                        <tr>
                            <td>Friday</td>
                            <td><input type="checkbox" name="open_friday" class="open_friday" value="1" @if($location->opening_hours->open_friday) checked @endif></td>
                            <td><input type="time" @if(!$location->opening_hours->open_friday) disabled @endif class="form-control friday_start_time" name="friday_start_time" value="{{$location->opening_hours->friday_start_time}}"></td>
                            <td><input type="time" @if(!$location->opening_hours->open_friday) disabled @endif class="form-control friday_end_time" name="friday_end_time" value="{{$location->opening_hours->friday_end_time}}"></td>
                        </tr>
                        <tr>
                            <td>Saturday</td>
                            <td><input type="checkbox" name="open_saturday" class="open_saturday" value="1" @if($location->opening_hours->open_saturday) checked @endif></td>
                            <td><input type="time" @if(!$location->opening_hours->open_saturday) disabled @endif class="form-control saturday_start_time"  name="saturday_start_time" value="{{$location->opening_hours->saturday_start_time}}"></td>
                            <td><input type="time" @if(!$location->opening_hours->open_saturday) disabled @endif class="form-control saturday_end_time" name="saturday_end_time" value="{{$location->opening_hours->saturday_end_time}}"></td>
                        </tr>
                        <tr>
                            <td>Sunday</td>
                            <td><input type="checkbox" name="open_sunday" class="open_sunday" value="1" @if($location->opening_hours->open_sunday) checked @endif></td>
                            <td><input type="time" @if(!$location->opening_hours->open_sunday) disabled @endif class="form-control sunday_start_time" name="sunday_start_time" value="{{$location->opening_hours->sunday_start_time}}"></td>
                            <td><input type="time" @if(!$location->opening_hours->open_sunday) disabled @endif class="form-control sunday_end_time" name="sunday_end_time" value="{{$location->opening_hours->sunday_end_time}}"></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="btn-group right">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Ask Me Later</button>
                        <button class="btn btn-info update_opening_hours" type="submit" name="update_opening_hours" value="1">Submit</button>
                </div>
                </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div> 