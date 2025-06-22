<a style="float: right;" type="button" class="btn primary-btn" data-bs-toggle="modal"
                        data-bs-target="#groupModal">
                        Add New Group
                    </a>
                        <table class="table table-bordered  w-100 new-table" id="session-dataTable">
                            <thead>
                                <th style="white-space: nowrap;">Type</th>
                                <th style="white-space: nowrap;">Actions</th>
                            </thead>
                            <tbody>
                                @if (!empty($contact_groups))
                                    @foreach ($contact_groups as $group)
                                        <tr>
                                            <td>{{ $group->group_name }}</td>
                                            <td>
                                                <button class="badge badge-success badge-user border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#groupModal{{ $group->id }}">Edit</button>
                                                <button class="badge bg-danger badge-sm remove-share border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $group->id }}">Remove</button>
                                            </td>
                                        </tr>


                                        <div class="modal fade" id="deleteModal{{ $group->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="deleteModalLabel{{ $group->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <form  method="post" action="{{ route('head_office.contacts.delete_group', $group->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="groupModalLabel">Delete group</h5>
                                                        <button type="button" class="btn-close float-right"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true"></span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Are you sure you want to delete this group? All the linked
                                                            contacts will be
                                                            affected.</h5>
                                                        {{-- <input type="checkbox" name="move_to_new_group" id="move_to_new_group">
                                                        <label for="move_to_new_group">Move attached fields to new group</label>

                                                        <select class="form-select my-2 d-none"
                                                            aria-label="Default select example" name="new_group_id"
                                                            id="new_group_id">
                                                            @foreach ($contact_groups as $gdpr)
                                                                @if ($gdpr->id != $group->id)
                                                                    <option value="{{ $gdpr->id }}">
                                                                        {{ $gdpr->group_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select> --}}
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


                                        <div class="modal fade" id="groupModal{{ $group->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="groupModalLabel{{ $group->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('head_office.contacts.create_group', $group->id) }}"
                                                        method="post">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="groupModalLabel">Edit group
                                                            </h5>
                                                            <button type="button" class="btn-close float-right"
                                                                data-bs-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true"></span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @csrf
                                                            <label style="margin: 0;font-size: 12px;" for="email">Group
                                                                Name</label>
                                                            <input type="text" name="group_name" placeholder="Group Name"
                                                                class="form-control" style="height:50px" required
                                                                value="{{ $group->group_name }}">

                                                            

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

                        <div class="modal fade" id="groupModal" tabindex="-1" role="dialog"
                            aria-labelledby="groupModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('head_office.contacts.create_group') }}" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="groupModalLabel">Add New Group</h5>
                                            <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true"></span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @csrf
                                            <label style="margin: 0;font-size: 12px;" for="group_name">Group Name</label>
                                            <input type="text" name="group_name" placeholder="group Name"
                                                class="form-control" style="height:50px" required>
                                            

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


                     