<div style="position: relative;" x-data>
    <div class="d-flex align-items-center gap-1 flex-wrap">
        @foreach ($case_default_tags as $index => $case_tag)
            @php
                $case_tag = \App\Models\case_tags::find($case_tag)
            @endphp
                @if (isset($case_tag))
                              
                <button wire:key="{{ $case_tag->id }}" id="main-cat" type="button" wire:click="toggleTagVisibility" class="btn btn-outline-secondary custom-button" style="background: {{$case_tag->color}}; color: {{$case_tag->text_color}}">
                    {{$case_tag->name}} 
                {{-- <i class="fa-solid fa-xmark mx-2 " style="margin-top: 2px;" wire:click.stop="removeTag({{$case_tag->id}})"></i> --}}
                </button>
                @endif
        @endforeach
        <button type="button" wire:click="toggleTagVisibility" class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center "><i class="fa fa-plus"></i></button>
    </div>
    @if($isVisible)
    <div  class="bg-backdrop" x-on:click='$wire.closeVisible()' x-show='$wire.isVisible'></div>
    <div class="category-wrapper" id="livewire-component" data-is-visible="{{ $isVisible }}" x-show='$wire.isVisible' x-transition>
        <div class="tooltip-category-wrapper2">
            <span class="arrow"></span>
            <div class="category-info-wrapper"> 
                @if ($isEdit && $isCustomTag)
                    <div class="custom-tag-settings-wrapper">
                        <div class="svg-wrapper">
                            @foreach ($svgs as $index => $svg)
                            <div class="svg-btn" wire:click='updateTagCustomIcon({{$selected_tag->id}},{{$index}})'>
                                {!! $svg !!}
                            </div>
                            @endforeach
                        </div>
                        <div class="">
                            <label for="">Icon Color</label>
                            <input wire:model.lazy='selected_tag_icon_color' wire:blur='updateTagCustomIconColor({{$selected_tag->id}})' class="form-control shadow-none" style="height: 40px;" type="color" >
                        </div>
                        <div class="">
                            <label for="">Text Color</label>
                            <input wire:model.lazy='selected_tag_text_color' wire:blur='updateTagCustomTextColor({{$selected_tag->id}})'  class="form-control shadow-none" style="height: 40px;" type="color" >
                        </div>
                    </div>
                @endif

                @if($isEdit)
                <div class="category-btn-wrapper3">
                    @foreach($all_tags as $index => $tag)
                    <div class="cat-input-parent " wire:key="{{ $index }}">
                        <button type="button" class="btn btn-hover border-0" wire:click="custom_visible({{$tag->id}})" style="position: absolute;left:-42px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="#C7C7C7" d="M8.99997 11.2224L12.7778 15.0002M7.97485 20.975C6.60801 22.3419 4 22.0002 2 22.0002C3.0251 20.0002 1.65827 17.3921 3.0251 16.0253C4.39194 14.6585 6.60801 14.6585 7.97485 16.0253C9.34168 17.3921 9.34168 19.6082 7.97485 20.975ZM11.9216 15.9248L21.0587 6.05671C21.8635 5.18755 21.8375 3.83776 20.9999 3.00017C20.1624 2.16258 18.8126 2.13663 17.9434 2.94141L8.07534 12.0785C7.5654 12.5507 7.31043 12.7868 7.16173 13.0385C6.80514 13.6423 6.79079 14.3887 7.12391 15.0057C7.26283 15.2631 7.50853 15.5088 7.99995 16.0002C8.49136 16.4916 8.73707 16.7373 8.99438 16.8762C9.6114 17.2093 10.3578 17.195 10.9616 16.8384C11.2134 16.6897 11.4494 16.4347 11.9216 15.9248Z"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                                
                        </button>
                        <div class="cat-input-bucket">
                            <i class="fa-solid fa-fill-drip icon"></i>
                            <input type="color" class="color-input" wire:model.lazy='color_input.{{$tag->id}}' wire:blur='updateTagColor({{$tag->id}})'>
                        </div>
                        <input wire:model.lazy='category_input.{{$tag->id}}' wire:blur='updateTagName({{$tag->id}})' class="input-name"   />
                        <button type="button" wire:click.prevent='removeCategory({{$tag->id}})' class="cross-cat-btn"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    @endforeach
                    <button wire:click.prevent='addNewTag' type="button" class="btn btn-outline-secondary custom-outline-btn " style="min-width:250px;"><i class="fa-solid fa-plus" style="font-size: 14px;"></i> New Tag</button>
                </div>
                @else
                <div class="category-btn-wrapper">
                    @foreach($all_tags as $tag)
                    <Button type="button" style="background-color:{{$tag->color}};" class="placeholder-btn d-flex align-items-center gap-1 justify-content-center"  wire:click.prevent='assignTag({{$tag->id}})' >
                        {{-- {!!$this->getSvg($tag->icon,$tag->icon_color)!!} --}}
                        {{ $tag->name}}</Button>
                    @endforeach
                </div>
                @endif
                <hr style="width: 100%;margin-bottom:0.3rem;">
                <button wire:click='toggleEditMode' type="button" class="btn edit-btn"><i class="fa-solid fa-pencil"></i> {{$isEdit ? 'Apply' : 'Edit Tags'}}</button>
                @isset($msg)
                <p class="err-msg">{{$msg}}</p>
                @endisset
            </div>
        </div>
    </div>
    @endif
</div>
