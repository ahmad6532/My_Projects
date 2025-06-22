<a style="float: right;" type="button" class="btn primary-btn" data-bs-toggle="modal" data-bs-target="#tagModal">
    Add New Tag
</a>
<table class="table table-bordered  w-100 new-table" id="session-dataTable">
    <thead>
        <th style="white-space: nowrap;">Name</th>
        <th style="white-space: nowrap;">Type</th>
        <th style="white-space: nowrap;">Groups</th>
        <th style="white-space: nowrap;">Actions</th>
    </thead>
    <tbody>
        @if (!empty($contact_tags))
            @foreach ($contact_tags as $tag)
                <tr>
                    <td>{{ $tag->tag_name }}</td>
                    <td>
                        @if ($tag->type == 'group_specific')
                            Group Specific
                        @else
                            General
                        @endif
                    </td>
                    <td>
                        @if ($tag->type == 'group_specific' && isset($tag->tag_to_groups))
                            @foreach ($tag->tag_to_groups as $tag_to_group)
                                <button
                                    class="badge badge-success badge-user border-0">{{ $tag_to_group->contact_group->group_name }}</button>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <button class="badge badge-success badge-user border-0" data-bs-toggle="modal"
                            data-bs-target="#tagModal{{ $tag->id }}">Edit</button>
                        <button class="badge bg-danger badge-sm remove-share border-0" data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{ $tag->id }}">Remove</button>
                    </td>
                </tr>


                <div class="modal fade" id="deleteModal{{ $tag->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="deleteModalLabel{{ $tag->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form method="post" action="{{ route('head_office.contacts.delete_tag', $tag->id) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tagModalLabel">Delete tag</h5>
                                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h5>Are you sure you want to delete this tag? All the linked
                                        contacts and groups will be
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                    <button class="btn btn-primary">Yes</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="tagModal{{ $tag->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="tagModalLabel{{ $tag->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('head_office.contacts.create_tag', $tag->id) }}" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tagModalLabel">Edit tag
                                    </h5>
                                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @csrf
                                    <label style="margin: 0;font-size: 12px;" for="email">Tag
                                        Name</label>
                                    <input type="text" name="tag_name" placeholder="Tag Name" class="form-control"
                                        style="height:50px" required value="{{ $tag->tag_name }}">












                                    <p class="mt-4" style="margin: 0;font-size: 12px;">Tag Type</p>
                                    <div class="form-check">
                                        <input class="form-check-input edit_type" type="radio" name="type"
                                            id="exampleRadios1" value="general"
                                            @if ($tag->type == 'general') checked @endif>
                                        <label class="form-check-label" for="exampleRadios1">
                                            General
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input edit_type" type="radio" name="type"
                                            id="exampleRadios2" value="group_specific"
                                            @if ($tag->type == 'group_specific') checked @endif>
                                        <label class="form-check-label" for="exampleRadios2">
                                            Group Specific
                                        </label>
                                    </div>
                                    <div
                                        class="group_select_edit {{ $tag->type == 'group_specific' ? '' : 'd-none' }}">
                                        <select name="groups[]" multiple
                                            class="form-contorl  select_2"style="width: 100%">
                                            @if (!empty($contact_groups))
                                                @foreach ($contact_groups as $group)
                                                    <option @if (in_array($group->id, $tag->tag_to_groups->pluck('group_id')->toArray())) selected @endif
                                                        value="{{ $group->id }}">{{ $group->group_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>


                                    <div class="mt-4 tag_icon_svg_edit" data-tag-id="{{ $tag->id }}">
                                        {!! $tag->icon !!}
                                    </div>

                                    <input type="hidden" name="icon" id="tag_icon_edit_{{ $tag->id }}"
                                        value="{{ $tag->icon }}" data-tag-id="{{ $tag->id }}">

                                    <div class="d-none edit_icon_container mt-4 gap-1"
                                        data-tag-id="{{ $tag->id }}">
                                        <svg data-tag-id="{{ $tag->id }}" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M22 12H18L15 21L9 3L6 12H2" stroke="black" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg data-tag-id="{{ $tag->id }}" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.5 11.5H14.5L13 14.5L11 8.5L9.5 11.5H8.5M11.9932 5.13581C9.9938 2.7984 6.65975 2.16964 4.15469 4.31001C1.64964 6.45038 1.29697 10.029 3.2642 12.5604C4.75009 14.4724 8.97129 18.311 10.948 20.0749C11.3114 20.3991 11.4931 20.5613 11.7058 20.6251C11.8905 20.6805 12.0958 20.6805 12.2805 20.6251C12.4932 20.5613 12.6749 20.3991 13.0383 20.0749C15.015 18.311 19.2362 14.4724 20.7221 12.5604C22.6893 10.029 22.3797 6.42787 19.8316 4.31001C17.2835 2.19216 13.9925 2.7984 11.9932 5.13581Z"
                                                stroke="black" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <svg data-tag-id="{{ $tag->id }}" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4 7.9966C3.83599 7.99236 3.7169 7.98287 3.60982 7.96157C2.81644 7.80376 2.19624 7.18356 2.03843 6.39018C2 6.19698 2 5.96466 2 5.5C2 5.03534 2 4.80302 2.03843 4.60982C2.19624 3.81644 2.81644 3.19624 3.60982 3.03843C3.80302 3 4.03534 3 4.5 3H19.5C19.9647 3 20.197 3 20.3902 3.03843C21.1836 3.19624 21.8038 3.81644 21.9616 4.60982C22 4.80302 22 5.03534 22 5.5C22 5.96466 22 6.19698 21.9616 6.39018C21.8038 7.18356 21.1836 7.80376 20.3902 7.96157C20.2831 7.98287 20.164 7.99236 20 7.9966M10 13H14M4 8H20V16.2C20 17.8802 20 18.7202 19.673 19.362C19.3854 19.9265 18.9265 20.3854 18.362 20.673C17.7202 21 16.8802 21 15.2 21H8.8C7.11984 21 6.27976 21 5.63803 20.673C5.07354 20.3854 4.6146 19.9265 4.32698 19.362C4 18.7202 4 17.8802 4 16.2V8Z"
                                                stroke="black" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
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

<div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="tagModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('head_office.contacts.create_tag') }}" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="tagModalLabel">Add New Tag</h5>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <label style="margin: 0;font-size: 12px;" for="tag_name">Tag Name</label>
                    <input type="text" name="tag_name" placeholder="Tag Name" class="form-control"
                        style="height:50px" required>


                    <p class="mt-4" style="margin: 0;font-size: 12px;">Tag Type</p>
                    <div class="form-check">
                        <input class="form-check-input add_type" type="radio" name="type" id="exampleRadios1"
                            value="general" checked>
                        <label class="form-check-label" for="exampleRadios1">
                            General
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input add_type" type="radio" name="type" id="exampleRadios2"
                            value="group_specific">
                        <label class="form-check-label" for="exampleRadios2">
                            Group Specific
                        </label>
                    </div>
                    <div class="group_select_add d-none">
                        <select name="groups[]" multiple class="form-contorl  select_2"style="width: 100%">
                            @if (!empty($contact_groups))
                                @foreach ($contact_groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>



                    <div class="mt-4" id="tag_icon_svg_add">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.5 11.5H14.5L13 14.5L11 8.5L9.5 11.5H8.5M11.9932 5.13581C9.9938 2.7984 6.65975 2.16964 4.15469 4.31001C1.64964 6.45038 1.29697 10.029 3.2642 12.5604C4.75009 14.4724 8.97129 18.311 10.948 20.0749C11.3114 20.3991 11.4931 20.5613 11.7058 20.6251C11.8905 20.6805 12.0958 20.6805 12.2805 20.6251C12.4932 20.5613 12.6749 20.3991 13.0383 20.0749C15.015 18.311 19.2362 14.4724 20.7221 12.5604C22.6893 10.029 22.3797 6.42787 19.8316 4.31001C17.2835 2.19216 13.9925 2.7984 11.9932 5.13581Z"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <input type="hidden" name="icon" id="tag_icon_add"
                        value='<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.5 11.5H14.5L13 14.5L11 8.5L9.5 11.5H8.5M11.9932 5.13581C9.9938 2.7984 6.65975 2.16964 4.15469 4.31001C1.64964 6.45038 1.29697 10.029 3.2642 12.5604C4.75009 14.4724 8.97129 18.311 10.948 20.0749C11.3114 20.3991 11.4931 20.5613 11.7058 20.6251C11.8905 20.6805 12.0958 20.6805 12.2805 20.6251C12.4932 20.5613 12.6749 20.3991 13.0383 20.0749C15.015 18.311 19.2362 14.4724 20.7221 12.5604C22.6893 10.029 22.3797 6.42787 19.8316 4.31001C17.2835 2.19216 13.9925 2.7984 11.9932 5.13581Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>'>
                    <div class="d-none justify-content-start align-items-start gap-2 mt-4" id="add_icon_container">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 12H18L15 21L9 3L6 12H2" stroke="black" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.5 11.5H14.5L13 14.5L11 8.5L9.5 11.5H8.5M11.9932 5.13581C9.9938 2.7984 6.65975 2.16964 4.15469 4.31001C1.64964 6.45038 1.29697 10.029 3.2642 12.5604C4.75009 14.4724 8.97129 18.311 10.948 20.0749C11.3114 20.3991 11.4931 20.5613 11.7058 20.6251C11.8905 20.6805 12.0958 20.6805 12.2805 20.6251C12.4932 20.5613 12.6749 20.3991 13.0383 20.0749C15.015 18.311 19.2362 14.4724 20.7221 12.5604C22.6893 10.029 22.3797 6.42787 19.8316 4.31001C17.2835 2.19216 13.9925 2.7984 11.9932 5.13581Z"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4 7.9966C3.83599 7.99236 3.7169 7.98287 3.60982 7.96157C2.81644 7.80376 2.19624 7.18356 2.03843 6.39018C2 6.19698 2 5.96466 2 5.5C2 5.03534 2 4.80302 2.03843 4.60982C2.19624 3.81644 2.81644 3.19624 3.60982 3.03843C3.80302 3 4.03534 3 4.5 3H19.5C19.9647 3 20.197 3 20.3902 3.03843C21.1836 3.19624 21.8038 3.81644 21.9616 4.60982C22 4.80302 22 5.03534 22 5.5C22 5.96466 22 6.19698 21.9616 6.39018C21.8038 7.18356 21.1836 7.80376 20.3902 7.96157C20.2831 7.98287 20.164 7.99236 20 7.9966M10 13H14M4 8H20V16.2C20 17.8802 20 18.7202 19.673 19.362C19.3854 19.9265 18.9265 20.3854 18.362 20.673C17.7202 21 16.8802 21 15.2 21H8.8C7.11984 21 6.27976 21 5.63803 20.673C5.07354 20.3854 4.6146 19.9265 4.32698 19.362C4 18.7202 4 17.8802 4 16.2V8Z"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>







<script src="{{ asset('admin_assets/js/select2.min.js') }}"></script>
<script>
    $(document).on('click', '#add_icon_container svg', function() {
        $('#tag_icon_svg_add svg').replaceWith($(this).clone());
        $('#tag_icon_add').val($(this).html());
    });

    $(document).on('click', '#tag_icon_svg_add', function(e) {
        console.log("fkhlsdjfkls");
        $('#add_icon_container').removeClass('d-none');
        $('#add_icon_container').addClass('d-flex');
        e.stopPropagation(); // Stop the event from propagating to the document
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#add_icon_container').length) {
            $('#add_icon_container').addClass('d-none');
            $('#add_icon_container').removeClass('d-flex');
        }
    });



    $('.add_type').on('change', function() {
        if ($(this).val() == 'group_specific') {
            $('.group_select_add').removeClass('d-none');
            $('.general').addClass('d-none');
        } else {
            $('.group_select_add').addClass('d-none');
            $('.general').removeClass('d-none');
        }
    });
    $('.edit_type').on('change', function() {
        if ($(this).val() == 'group_specific') {
            $('.group_select_edit').removeClass('d-none');
            $('.general').addClass('d-none');
        } else {
            $('.group_select_edit').addClass('d-none');
            $('.general').removeClass('d-none');
        }
    });


    $(document).ready(function() {
        $(document).on('click', '.tag_icon_svg_edit', function(e) {
            const tagId = $(this).data('tag-id');
            $(`.edit_icon_container[data-tag-id="${tagId}"]`).removeClass('d-none').addClass('d-flex');
            e.stopPropagation();
        });
        $(document).on('click', '.edit_icon_container svg', function() {
            const tagId = $(this).data('tag-id');
            const svgContent = $(this).clone().prop('outerHTML');

            $(`.tag_icon_svg_edit[data-tag-id="${tagId}"]`).html(svgContent);
            $(`#tag_icon_edit_${tagId}`).val(svgContent);
            $(`.edit_icon_container[data-tag-id="${tagId}"]`).addClass('d-none').removeClass('d-flex');
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.edit_icon_container, .tag_icon_svg_edit').length) {
                $('.edit_icon_container').addClass('d-none').removeClass('d-flex');
            }
        });
    });
</script>

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select_2').select2();
        });
    </script>
@endsection
