<div id="draggable" class="bottom-nav draggable d-flex position-fixed display_contacts_nav" style="z-index: 10;" aria-describedby="drag"
    data-selected-tab={{ $tab }}>
    <div class="left-side">
        <div class="info-wrapper">
            <div class="selected-show">
                <h5 id="count">0</h5>
            </div>
            <div class="info-heading" style="max-width: 180px;overflow:hidden;">
                <p>Items Selected</p>

            </div>
        </div>

        <div style="max-width: 570px;overflow-x:scroll;height:100%;" class="custom-drag-scroll">
            <div class="btn-wrapper" style="width: fit-content">
                @if ($tab == 'all_contacts')
                    <button id='export-case-btn' class="bar-btn" title="Assign To Person" style="width: 150px;"
                        data-bs-toggle="modal" data-bs-target="#{{ $tab }}-assignToModal">
                        <img src="{{ asset('images/user-01.svg') }}" alt="icon">
                        <p>Assign To Person</p>
                    </button>
                    <button style="width: 150px;" class="bar-btn" title="Unarchive selected contacts" data-bs-toggle="modal"
                        data-bs-target="#{{ $tab }}-groupsModal">
                        <img src="{{ asset('images/folder.svg') }}" alt="icon">
                        <p>Assign To Group</p>
                    </button>
                    <button style="width: 150px;" class="bar-btn" title="Unarchive selected contacts" data-bs-toggle="modal"
                        data-bs-target="#{{ $tab }}-tagsModal">
                        <img src="{{ asset('images/tag-01.svg') }}" alt="icon">
                        <p>Assign To Tag</p>
                    </button>
                    <button class="bar-btn" data-bs-toggle="modal" data-bs-target="#{{ $tab }}-archiveModal" >
                        <img src="{{ asset('images/folder-lock.svg') }}" alt="icon">
                        <p>Archive</p>
                    </button>
                </button>
                @elseif($tab == 'archived_contacts')
                    <button style="width: 100px;"  class="bar-btn" title="Unarchive selected" data-bs-toggle="modal" data-bs-target="#{{ $tab }}-unarchiveModal">
                        <img src="{{ asset('images/folder.svg') }}" alt="icon">
                        <p>Unarchive</p>
                    </button>

                @endif

                <button style="width: 100px;" class="bar-btn" title="Unarchive selected contacts" data-bs-toggle="modal"
                    data-bs-target="#{{ $tab }}-deleteModal">
                    <img src="{{ asset('images/trash-01.svg') }}" alt="icon">
                    <p>Delete</p>
                </button>

                @if ($tab == 'deleted_contacts')
                    <button style="width: 100px;" class="bar-btn" title="Restore selected contacts" data-bs-toggle="modal"
                        data-bs-target="#{{ $tab }}-restoreModal">
                        <img src="{{ asset('images/refresh-cw-03.svg') }}" alt="icon">
                        <p>Restore</p>
                    </button>
                @endif
            </div>
        </div>
    </div>
    <button class="drag-btn">
        <img src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
        <img style="margin-top:-15px;" src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
    </button>
</div>






<!-- Assign TO -->
<div class="modal fade" id="{{ $tab }}-assignToModal" tabindex="-1" role="dialog"
    aria-labelledby="{{ $tab }}-assignToModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('head_office.contacts.assign_users_bulk') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $tab }}-assignToModalLabel">Assign To Person</h5>
                    <button type="button" class="btn-close" style="position:absolute; top:4px; right:4px;"
                        data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <select id="assigns-select" name="assigns[]" multiple
                        class="chosen-select form-control groups_select">
                        @if (!empty($head_office_users) && count($head_office_users) > 0)
                            @foreach ($head_office_users as $head_office_user)
                                <option value="{{ $head_office_user->id }}"
                                    data-user-name="{{ (isset($head_office_user->user->first_name) ? $head_office_user->user->first_name : '') .
                                        ' ' .
                                        (isset($head_office_user->user->surname) ? $head_office_user->user->surname : '') }}"
                                    data-user-position="{{ isset($head_office_user->user->position) ? $head_office_user->user->position->name : '' }}">
                                    {{ (isset($head_office_user->user->first_name) ? $head_office_user->user->first_name : '') .
                                        ' ' .
                                        (isset($head_office_user->user->surname) ? $head_office_user->user->surname : '') }}
                                </option>
                            @endforeach
                        @endif
                    </select>



                </div>
                <input type="hidden" value="contact_ids[]" name="contact_ids" class={{ $tab }}>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Groups --}}

<div class="modal fade" id="{{ $tab }}-groupsModal" tabindex="-1" role="dialog"
    aria-labelledby="{{ $tab }}-groupsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('head_office.contacts.assign_group_bulk') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $tab }}-groupsModalLabel">Assign To Group</h5>
                    <button type="button" class="btn-close" style="position:absolute; top:4px; right:4px;"
                        data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <select class="chosen-select form-control" multiple id="tags-select" name="groups[]" multiple>
                        @if (!empty($contact_groups) && count($contact_groups) > 0)
                            @foreach ($contact_groups as $group)
                                <option value="{{ $group->id }}">
                                    {{ $group->group_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>

                </div>
                <input type="hidden" value="contact_ids[]" name="contact_ids" class={{ $tab }}>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Assign To Tags --}}


<div class="modal fade" id="{{ $tab }}-tagsModal" tabindex="-1" role="dialog"
    aria-labelledby="{{ $tab }}-tagsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('head_office.contacts.assign_tags_bulk') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $tab }}-tagsModalLabel">Assign To Tags</h5>
                    <button type="button" class="btn-close" style="position:absolute; top:4px; right:4px;"
                        data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <select id="tags-select" name="tags[]" multiple class="form-control chosen-select"
                        style="width: 100%">
                        @if (!empty($contact_tags) && count($contact_tags) > 0)
                            @foreach ($contact_tags as $tag)
                                <option data-tag-type="{{ $tag->type }}" value="{{ $tag->id }}">
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <input type="hidden" value="contact_ids[]" name="contact_ids" class={{ $tab }}>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}

<div class="modal fade" id="{{ $tab }}-deleteModal" tabindex="-1" role="dialog"
    aria-labelledby="{{ $tab }}-deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('head_office.contacts.delete_bulk') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $tab }}-deleteModalLabel">Are you sure you want to
                        delete?</h5>
                    <button type="button" class="btn-close" style="position:absolute; top:4px; right:4px;"
                        data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" value="contact_ids[]" name="contact_ids" class={{ $tab }}>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}

<div class="modal fade" id="{{ $tab }}-archiveModal" tabindex="-1" role="dialog"
    aria-labelledby="{{ $tab }}-archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('head_office.contacts.archive_bulk') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $tab }}-archiveModalLabel">Are you sure you want to
                        archive?</h5>
                    <button type="button" class="btn-close" style="position:absolute; top:4px; right:4px;"
                        data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <input type="hidden" value="contact_ids[]" name="contact_ids" class={{ $tab }}>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Unarchive --}}
<div class="modal fade" id="{{ $tab }}-unarchiveModal" tabindex="-1" role="dialog"
    aria-labelledby="{{ $tab }}-unarchiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('head_office.contacts.unarchive_bulk') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $tab }}-unarchiveModalLabel">Are you sure you want to
                        unarchive?</h5>
                    <button type="button" class="btn-close" style="position:absolute; top:4px; right:4px;"
                        data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <input type="hidden" value="contact_ids[]" name="contact_ids" class={{ $tab }}>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- restore Modal --}}

<div class="modal fade" id="{{ $tab }}-restoreModal" tabindex="-1" role="dialog"
    aria-labelledby="{{ $tab }}-restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('head_office.contacts.restore_bulk') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $tab }}-restoreModalLabel">Are you sure you want to
                        restore?</h5>
                    <button type="button" class="btn-close" style="position:absolute; top:4px; right:4px;"
                        data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <input type="hidden" value="contact_ids[]" name="contact_ids" class={{ $tab }}>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.chosen-select').chosen({
            width: '100%', 
        });
    });
</script>
