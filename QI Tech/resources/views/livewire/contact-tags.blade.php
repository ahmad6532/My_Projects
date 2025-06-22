<div style="position: relative;" x-data>

    <div class="d-flex align-items-center gap-1 flex-wrap justify-content-end">
        @foreach ($contact_default_tags as $index => $contact_tag)
            <button wire:key="{{ $contact_tag['id'] }}" id="main-cat" type="button" wire:click="toggleTagVisibility"
                class="btn btn-outline-secondary custom-button rounded fw-normal d-flex align-items-center gap-2 case-tags"
                style="background: {{ $contact_tag['color'] }};color:{{ $contact_tag['text_color'] }}">
                {!! $this->getSvg($contact_tag['icon'], $contact_tag['icon_color']) !!} {{ $contact_tag['name'] }} <i class="fa-solid fa-xmark mx-2 "
                    style="margin-top: 2px;" wire:click.stop="removeTag({{ $contact_tag['id'] }})"></i>
                <input type="hidden" name="tag_ids[]" value="{{ $contact_tag['id'] }}">
            </button>
        @endforeach
        <button type="button" wire:click="toggleTagVisibility"
            class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center "><i
                class="fa fa-plus"></i></button>
    </div>
    @if ($isVisible)
    <div class="category-wrapper" id="livewire-component" data-is-visible="{{ $isVisible }}"
            x-show='$wire.isVisible' x-transition>
            <div class="bg-backdrop" x-on:click='$wire.closeVisible()' x-show='$wire.isVisible'></div>
            <div class="tooltip-category-wrapper2">
                <span class="arrow"></span>
                <div class="category-info-wrapper">
                    @if ($isEdit && $isCustomTag)
                        <div class="custom-tag-settings-wrapper">
                            <div class="svg-wrapper">
                                @foreach ($svgs as $index => $svg)
                                    <div class="svg-btn"
                                        wire:click='updateTagCustomIcon({{ $selected_tag->id }},{{ $index }})'>
                                        {!! $svg !!}
                                    </div>
                                @endforeach
                            </div>
                            <div class="">
                                <label for="">Icon Color</label>
                                <input wire:model.lazy='selected_tag_icon_color'
                                    wire:blur='updateTagCustomIconColor({{ $selected_tag->id }})'
                                    class="form-control shadow-none" style="height: 40px;" type="color">
                            </div>
                            <div class="">
                                <label for="">Text Color</label>
                                <input wire:model.lazy='selected_tag_text_color'
                                    wire:blur='updateTagCustomTextColor({{ $selected_tag->id }})'
                                    class="form-control shadow-none" style="height: 40px;" type="color">
                            </div>
                        </div>
                    @endif

                    @if ($isEdit)
                        <div class="category-btn-wrapper3">
                            @foreach ($all_tags as $index => $tag)
                            @if (
                                    isset($type_input[$tag->id]) 
                                    && $type_input[$tag->id] === 'group_specific' 
                                    && isset($user_contact_groups)
                                    && $tag->tag_to_groups->contains(function ($tagGroup) use ($user_contact_groups) {
                                        return $user_contact_groups->contains('group_id', $tagGroup->group_id);
                                    })
                                )
                                @elseif($tag->type == 'group_specific' && count($tag->tag_to_groups) != 0)
                                    @continue
                                @endif
                            
                                    <div class="cat-input-parent " wire:key="{{ $tag->id.'_'.$index }}">
                                        <button type="button" class="btn btn-hover border-0"
                                            wire:click="custom_visible({{ $tag->id }})"
                                            style="position: absolute;left:-42px;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke="#C7C7C7"
                                                    d="M8.99997 11.2224L12.7778 15.0002M7.97485 20.975C6.60801 22.3419 4 22.0002 2 22.0002C3.0251 20.0002 1.65827 17.3921 3.0251 16.0253C4.39194 14.6585 6.60801 14.6585 7.97485 16.0253C9.34168 17.3921 9.34168 19.6082 7.97485 20.975ZM11.9216 15.9248L21.0587 6.05671C21.8635 5.18755 21.8375 3.83776 20.9999 3.00017C20.1624 2.16258 18.8126 2.13663 17.9434 2.94141L8.07534 12.0785C7.5654 12.5507 7.31043 12.7868 7.16173 13.0385C6.80514 13.6423 6.79079 14.3887 7.12391 15.0057C7.26283 15.2631 7.50853 15.5088 7.99995 16.0002C8.49136 16.4916 8.73707 16.7373 8.99438 16.8762C9.6114 17.2093 10.3578 17.195 10.9616 16.8384C11.2134 16.6897 11.4494 16.4347 11.9216 15.9248Z"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>


                                        </button>

                                        <div class="cat-input-bucket">
                                            <i class="fa-solid fa-fill-drip icon"></i>
                                            <input type="color" class="color-input"
                                                wire:model.lazy='color_input.{{ $tag->id }}'
                                                wire:blur='updateTagColor({{ $tag->id }})'>
                                        </div>
                                        <input wire:model.lazy='category_input.{{ $tag->id }}'
                                            wire:blur='updateTagName({{ $tag->id }})' class="input-name" />

                                        <select class="form-select" aria-label="Type"
                                            wire:model.lazy='type_input.{{ $tag->id }}'
                                            wire:blur='updateTagType({{ $tag->id }})'>
                                            <option value="general" @if ($tag->type == 'general') selected @endif>
                                                General</option>
                                            <option value="group_specific"
                                                @if ($tag->type == 'group_specific') selected @endif>Group Specific</option>
                                        </select>

                                        @if (isset($type_input[$tag->id]) && $type_input[$tag->id] === 'group_specific')
                                            <select id="tag-select-{{ $tag->id }}" name="tags[]" multiple
                                                class="form-control tag_select select_3" style="width: 100%"
                                                wire:model.lazy='groups_input.{{ $tag->id }}'>
                                                @if (!empty($contact_groups) && count($contact_groups) > 0)
                                                    @foreach ($contact_groups as $group)
                                                        @if (isset($user_contact_groups) && $user_contact_groups->contains('group_id', $group->id))
                                                            <option value="{{ $group->id }}"
                                                                @if (in_array($group->id, isset($tag->group_ids) ? $tag->group_ids : [])) selected @endif>
                                                                {{ $group->group_name }}
                                                            </option>
                                                            
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        @endif


                                        <button style="display: block" type="button"
                                            wire:click.prevent='removeCategory({{ $tag->id }})'
                                            class="cross-cat-btn"><i class="fa-solid fa-xmark"></i></button>


                                    </div>
                            @endforeach
                            <button wire:click.prevent='addNewTag' type="button"
                                class="btn btn-outline-secondary custom-outline-btn " style="min-width:250px;"><i
                                    class="fa-solid fa-plus" style="font-size: 14px;"></i> New Tag</button>
                        </div>
                    @else
                        <div class="category-btn-wrapper">
                            @foreach ($all_tags as $tag)
                            @if (
                                isset($type_input[$tag->id]) 
                                && $type_input[$tag->id] === 'group_specific' 
                                && isset($user_contact_groups)
                                && $tag->tag_to_groups->contains(function ($tagGroup) use ($user_contact_groups) {
                                    return $user_contact_groups->contains('group_id', $tagGroup->group_id);
                                })
                            )
                            @elseif($tag->type == 'group_specific' && count($tag->tag_to_groups) != 0)
                                @continue
                            @endif
                                

                                <Button type="button" style="background-color:{{ $tag->color }};"
                                    @if (empty(array_intersect($tag->tag_to_groups->pluck('group_id')->toArray(), array_column($default_contact_groups, 'id'))
                                        ) && $tag->type == 'group_specific') disabled @endif
                                    class="placeholder-btn d-flex align-items-center gap-1 justify-content-center"
                                    wire:click.prevent='assignTag({{ $tag->id }})'>{!! $this->getSvg($tag->icon, $tag->icon_color) !!}{{ $tag->name }}</Button>
                            @endforeach

                        </div>
                    @endif
                    <hr style="width: 100%;margin-bottom:0.3rem;">
                    <button wire:click='toggleEditMode' type="button" class="btn edit-btn"><i
                            class="fa-solid fa-pencil"></i> {{ $isEdit ? 'Apply' : 'Edit Tags' }}</button>
                    @isset($msg)
                        <p class="err-msg">{{ $msg }}</p>
                    @endisset
                </div>
            </div>
        </div>
    @endif


















    <div class="d-flex align-items-center gap-1 flex-wrap justify-content-end">
        @foreach ($default_contact_groups as $index => $group)
            <button wire:key="{{ $group['id'] }}" id="main-cat" type="button" wire:click="toggleGroupVisibility"
                class="btn btn-outline-secondary custom-button rounded fw-normal d-flex align-items-center gap-2 case-tags"
                style="background: {{ $group['color'] }};color:{{ $group['text_color'] }}">
                {!! $this->getSvg($group['icon'], $group['icon_color']) !!} {{ $group['group_name'] }} <i class="fa-solid fa-xmark mx-2 " style="margin-top: 2px;"
                    wire:click.stop="removeGroup({{ $group['id'] }})"></i>
                <input type="hidden" name="group_ids[]" value="{{ $group['id'] }}">
            </button>
        @endforeach
        <button type="button" wire:click="toggleGroupVisibility"
            class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center "><i
                class="fa fa-plus"></i></button>
    </div>
    @if ($isGroupVisible)
        <div class="bg-backdrop" x-on:click='$wire.closeGroupVisible()' x-show='$wire.isGroupVisible'></div>
        <div class="category-wrapper" id="livewire-component" data-is-visible="{{ $isGroupVisible }}"
            x-show='$wire.isGroupVisible' x-transition>
            <div class="tooltip-category-wrapper2">
                <span class="arrow"></span>
                <div class="category-info-wrapper">

                    @if ($isGroupEdit && $isCustomGroup)
                        <div class="custom-tag-settings-wrapper">
                            <div class="svg-wrapper">
                                @foreach ($svgs as $index => $svg)
                                    <div class="svg-btn"
                                        wire:click='updateGroupCustomIcon({{ $selected_tag->id }},{{ $index }})'>
                                        {!! $svg !!}
                                    </div>
                                @endforeach
                            </div>
                            <div class="">
                                <label for="">Icon Color</label>
                                <input wire:model.lazy='selected_tag_icon_color'
                                    wire:blur='updateGroupCustomIconColor({{ $selected_tag->id }})'
                                    class="form-control shadow-none" style="height: 40px;" type="color">
                            </div>
                            <div class="">
                                <label for="">Text Color</label>
                                <input wire:model.lazy='selected_tag_text_color'
                                    wire:blur='updateGroupCustomTextColor({{ $selected_tag->id }})'
                                    class="form-control shadow-none" style="height: 40px;" type="color">
                            </div>
                        </div>
                    @endif

                    @if ($isGroupEdit)
                        
                        <div class="category-btn-wrapper3">
                            @foreach ($contact_groups as $index => $tag)
                                <div class="cat-input-parent " wire:key="{{ $tag->id.$tag->group_name }}">
                                    <button type="button" class="btn btn-hover border-0"
                                        wire:click="custom_group_visible({{ $tag->id }})"
                                        style="position: absolute;left:-42px;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke="#C7C7C7"
                                                d="M8.99997 11.2224L12.7778 15.0002M7.97485 20.975C6.60801 22.3419 4 22.0002 2 22.0002C3.0251 20.0002 1.65827 17.3921 3.0251 16.0253C4.39194 14.6585 6.60801 14.6585 7.97485 16.0253C9.34168 17.3921 9.34168 19.6082 7.97485 20.975ZM11.9216 15.9248L21.0587 6.05671C21.8635 5.18755 21.8375 3.83776 20.9999 3.00017C20.1624 2.16258 18.8126 2.13663 17.9434 2.94141L8.07534 12.0785C7.5654 12.5507 7.31043 12.7868 7.16173 13.0385C6.80514 13.6423 6.79079 14.3887 7.12391 15.0057C7.26283 15.2631 7.50853 15.5088 7.99995 16.0002C8.49136 16.4916 8.73707 16.7373 8.99438 16.8762C9.6114 17.2093 10.3578 17.195 10.9616 16.8384C11.2134 16.6897 11.4494 16.4347 11.9216 15.9248Z"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>


                                    </button>

                                    <div class="cat-input-bucket">
                                        <i class="fa-solid fa-fill-drip icon"></i>
                                        <input type="color" class="color-input"
                                            wire:model.lazy='color_input.{{ $tag->id }}'
                                            wire:blur='updateGroupColor({{ $tag->id }})'>
                                    </div>
                                    <input wire:model.lazy='group_input.{{ $tag->id }}'
                                        wire:blur='updateGroupName({{ $tag->id }})' class="input-name" />

                                    


                                    <button style="display: block" type="button"
                                        wire:click.prevent='deleteGroup({{ $tag->id }})'
                                        class="cross-cat-btn"><i class="fa-solid fa-xmark"></i></button>


                                </div>
                            @endforeach
                            <button wire:click.prevent='addNewGroup' type="button"
                                class="btn btn-outline-secondary custom-outline-btn " style="min-width:250px;"><i
                                    class="fa-solid fa-plus" style="font-size: 14px;"></i> New Group</button>
                        </div>
                    @else
                        <div class="category-btn-wrapper">
                            @foreach ($contact_groups as $group)
                                    <Button type="button" style="background-color:{{ $group->color }};"
                                        class="placeholder-btn d-flex align-items-center gap-1 justify-content-center"
                                        wire:click.prevent='assignGroup({{ $group->id }})'>{!! $this->getSvg($group->icon, $group->icon_color) !!}{{ $group->group_name }}</Button>
                            @endforeach
                        </div>
                    @endif
                    <hr style="width: 100%;margin-bottom:0.3rem;">
                    <button wire:click='toggleGroupEditMode' type="button" class="btn edit-btn"><i
                            class="fa-solid fa-pencil"></i> {{ $isGroupEdit ? 'Apply' : 'Edit Groups' }}</button>
                    @isset($msgGroup)
                        <p class="err-msg">{{ $msgGroup }}</p>
                    @endisset
                </div>
            </div>
        </div>
    @endif





























</div>


@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            function initializeSelect2() {
                $('.select_3').select2();

                $('.select_3').on('change', function(e) {
                    let id = $(this).attr('id').split('-')[2];
                    let selectedValues = $(this).val();
                    @this.set('groups_input.' + id, selectedValues);
                    @this.call('updateTagGroups', id);
                });
            }

            initializeSelect2();

            Livewire.hook('message.processed', (message, component) => {
                initializeSelect2();
            });
        });
    </script>
@endpush
