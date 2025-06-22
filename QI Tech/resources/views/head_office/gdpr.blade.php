@extends('layouts.head_office_app')
@section('title', 'Head office Settings')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-info">Data Retention Setting
                <a style="float: right;" type="button" class="btn primary-btn" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    Add New GDPR Tag
                </a>
            </h1>
        </div>
        @include('layouts.error')
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="collapseCard">
                    <div class="card-body">
                        {{-- @dd($gdpr_linked_forms) --}}
                        <table class="table table-bordered  w-100 new-table" id="session-dataTable">
                            <thead>
                                <th style="white-space: nowrap;">Type</th>
                                <th style="white-space: nowrap;">Retain for</th>
                                <th style="white-space: nowrap;">Applied to</th>
                                <th style="white-space: nowrap;">Actions</th>
                            </thead>
                            <tbody>
                                @if (!empty($gdprs))
                                    @foreach ($gdprs as $tag)
                                        <tr>
                                            <td>{{ $tag->tag_name }}</td>
                                            <td>{{ $tag->gdpr_tag_remove_action->remove_after_number }}
                                                {{ $tag->gdpr_tag_remove_action->remove_after_unit }}</td>
                                            <td>
                                                @if (!empty($gdpr_linked_forms))
                                                    @foreach ($gdpr_linked_forms as $form)
                                                        @if (!empty($form['gdpr_ids']) && in_array($tag->id, $form['gdpr_ids']))
                                                            <h4>{{ $form['name'] }}</h4>
                                                            @foreach ($form['questions'] as $question)
                                                                @if ($question['gdpr'] == $tag->id)
                                                                    <p class="m-0 p-0">{{ $question['label'] }}</p>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <button class="badge badge-success badge-user border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal{{ $tag->id }}">Edit</button>
                                                <button class="badge bg-danger badge-sm remove-share border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $tag->id }}">Remove</button>
                                            </td>
                                        </tr>


                                        <div class="modal fade" id="deleteModal{{ $tag->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="deleteModalLabel{{ $tag->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <form  method="post" action="{{ route('head_office.gdpr.delete', $tag->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete GDPR Tag</h5>
                                                        <button type="button" class="btn-close float-right"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true"></span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Are you sure you want to delete this GDPR Tag? All the linked
                                                            fields will be
                                                            affected.</h5>
                                                        <input type="checkbox" name="move_to_new_tag" id="move_to_new_tag">
                                                        <label for="move_to_new_tag">Move attached fields to new tag</label>

                                                        <select class="form-select my-2 d-none"
                                                            aria-label="Default select example" name="new_tag_id"
                                                            id="new_tag_id">
                                                            @foreach ($gdprs as $gdpr)
                                                                @if ($gdpr->id != $tag->id)
                                                                    <option value="{{ $gdpr->id }}">
                                                                        {{ $gdpr->tag_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">No</button>
                                                        <button 
                                                            class="btn btn-primary">Yes</button>
                                                    </div>

                                                </form>
                                            </div>
                                            </div>
                                        </div>


                                        <div class="modal fade" id="exampleModal{{ $tag->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel{{ $tag->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('head_office.gdpr.save', $tag->id) }}"
                                                        method="post">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit GDPR Tag
                                                            </h5>
                                                            <button type="button" class="btn-close float-right"
                                                                data-bs-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true"></span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @csrf
                                                            <label style="margin: 0;font-size: 12px;" for="email">Tag
                                                                Name</label>
                                                            <input type="text" name="tag_name" placeholder="Tag Name"
                                                                class="form-control" style="height:50px" required
                                                                value="{{ $tag->tag_name }}">

                                                            <div class=" dead_line_date d-flex align-items-center gap-2">
                                                                <p class=" mt-4">Retain for</p>
                                                                <div class="">

                                                                    <input type="number" name="duration_of_access_number"
                                                                        value={{ $tag->gdpr_tag_remove_action->remove_after_number }}
                                                                        min="1"
                                                                        class="form-control duration_of_access_number">

                                                                </div>
                                                                <div class="">
                                                                    <select
                                                                        class="form-control dead_line_units duration_of_access_type"
                                                                        name="duration_of_access_type"
                                                                        id="duration_of_access_type">
                                                                        <option value="days"
                                                                            @if ($tag->gdpr_tag_remove_action->remove_after_unit == 'days') selected @endif>
                                                                            Days
                                                                        </option>
                                                                        <option value="months"
                                                                            @if ($tag->gdpr_tag_remove_action->remove_after_unit == 'months') selected @endif>
                                                                            Months
                                                                        </option>
                                                                        <option value="years"
                                                                            @if ($tag->gdpr_tag_remove_action->remove_after_unit == 'years') selected @endif>
                                                                            Years
                                                                        </option>
                                                                    </select>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>












                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('head_office.gdpr.save') }}" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New GDPR Tag</h5>
                                            <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true"></span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @csrf
                                            <label style="margin: 0;font-size: 12px;" for="email">Tag Name</label>
                                            <input type="text" name="tag_name" placeholder="Tag Name"
                                                class="form-control" style="height:50px" required>

                                            <div class=" dead_line_date d-flex align-items-center gap-2">
                                                <p class=" mt-4">Retain for</p>
                                                <div class="">

                                                    <input type="number" name="duration_of_access_number" value="1"
                                                        min="1" class="form-control duration_of_access_number">

                                                </div>
                                                <div class="">
                                                    <select class="form-control dead_line_units duration_of_access_type"
                                                        name="duration_of_access_type" id="duration_of_access_type">
                                                        <option value="days">Days
                                                        </option>
                                                        <option value="months">Months
                                                        </option>
                                                        <option value="years">Years
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(".duration_of_access_number, .duration_of_access_type").on('change', function() {
            var value = $(".duration_of_access_number").val();
            var duration_of_access_type = $(".duration_of_access_type").val();
            var now = new Date();
            value = parseInt(value);

            var newDate;
            if (duration_of_access_type == 'days') {
                newDate = new Date(now.setDate(now.getDate() + value));
            } else if (duration_of_access_type == 'weeks') {
                newDate = new Date(now.setDate(now.getDate() + value * 7));
            } else if (duration_of_access_type == 'months') {
                newDate = new Date(now.setMonth(now.getMonth() + value));
            } else if (duration_of_access_type == 'years') {
                newDate = new Date(now.setFullYear(now.getFullYear() + value));
            }

            // Format the date
            var day = newDate.getDate().toString().padStart(2, '0');
            var month = (newDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
            var year = newDate.getFullYear();
            var hours = newDate.getHours();
            var minutes = newDate.getMinutes().toString().padStart(2, '0');
            var seconds = newDate.getSeconds().toString().padStart(2, '0');

            // Convert to 12-hour format
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            hours = hours.toString().padStart(2, '0');

            var formattedDate = '<span class="fw-bold">' + day + '/' + month + '/' + year + '</span>' + ' at ' +
                hours + ':' + minutes + ' ' + ampm;

            $(".date").html('Data will be deleted on ' + formattedDate);
        });
        $('#move_to_new_tag').change(function() {
            if (this.checked) {
                $('#new_tag_id').removeClass('d-none');
            } else {
                $('#new_tag_id').addClass('d-none');
            }
        });
    </script>
@endsection
