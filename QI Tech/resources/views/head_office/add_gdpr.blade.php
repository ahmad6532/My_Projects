<div class="modal fade" @if (isset($gdpr)) id="edit_tag_{{$gdpr->id}}" @else id="add_new_tag" @endif tabindex="-1" @if(isset($remove_backdrop))
    data-backdrop="false" @endif role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Add New GDPR Tag
                </h4>
                <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" @if(isset($gdpr)) action="{{route('head_office.gdpr.save',$gdpr->id)}}" @else action="{{route('head_office.gdpr.save')}}" @endif
                    id="reset_form">
                    <div class="new_link_wrapper">
                        @csrf
                        <!-- <input type="hidden" name="links[]" value=""> -->
                        <div class="form-group">
                            <label for="tag_name">Name </label>
                            <input type="text" class="form-control" name="tag_name" id="tag_name" @if ($gdpr)
                                value="{{$gdpr->tag_name}}"
                            @endif>
                        </div>
                        <div class="form-group">
                            <label for="tag_name">Remove Number </label>
                            <input type="number" min="1" class="form-control" name="remove_after_number" id="remove_after_number" @if ($gdpr)
                                value="{{$gdpr->gdpr_tag_remove_action->remove_after_number}}"
                            @endif>
                        </div>
                        <div class="form-group">
                            <label for="tag_name">Remove Unit </label>
                            <select name="remove_after_unit" id="remove_after_unit" class="form-control">
                                <option value="days" @if (isset($gdpr) && $gdpr->gdpr_tag_remove_action->remove_after_unit == 'days') selected @endif>Days</option>
                                <option value="weeks" @if (isset($gdpr) && $gdpr->gdpr_tag_remove_action->remove_after_unit == 'weeks') selected @endif>Weeks</option>
                                <option value="months" @if (isset($gdpr) && $gdpr->gdpr_tag_remove_action->remove_after_unit == 'months') selected @endif>Months</option>
                                <option value="years" @if (isset($gdpr) && $gdpr->gdpr_tag_remove_action->remove_after_unit == 'years') selected @endif>Years</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <button type="submit" name="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                            class="fa fa-location-arrow"></i> </button>

            </div>
            </form>
        </div>
    </div>
</div>