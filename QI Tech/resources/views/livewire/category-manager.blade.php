<div style="position: relative;" x-data>
    @if (isset($category))
    <button id="main-cat" type="button"  wire:click="toggleCategoryVisibility"  class="btn btn-outline-secondary custom-button" style="background: {{$category->color}};color:{{$category->color !== '#ffffff' ? '#fff' : '#6c757d'}}; height:fit-content;">
        {{$category->name}}
    </button>
    @else
    <button id="main-cat" type="button" wire:click="toggleCategoryVisibility" class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center "><i class="fa fa-plus"></i></button>
    @endif
    @if($isVisible)
    <div  class="bg-backdrop" x-on:click='$wire.closeVisible()' x-show='$wire.isVisible'></div>
    <div class="category-wrapper" id="livewire-component" data-is-visible="{{ $isVisible }}" x-show='$wire.isVisible' x-transition>
        <div class="tooltip-category-wrapper2">
            <span class="arrow"></span>
            <div class="category-info-wrapper"> 
                @if($isEdit)
                <div class="category-btn-wrapper2">
                    @foreach($categories as $index => $cat)
                    <div class="cat-input-parent " wire:key="{{ $index }}">
                        <div class="cat-input-bucket">
                            <i class="fa-solid fa-fill-drip icon"></i>
                            <input type="color" class="color-input" wire:model.lazy='color_input.{{$cat->id}}' wire:blur='updateCategoryColor({{$cat->id}})'>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <input style="flex-grow: 1; padding-right: 10px; height: 35px;" wire:model.lazy='category_input.{{$cat->id}}' wire:blur='updateCategoryName({{$cat->id}})' class="input-name" />
                            <button style="width: 30px; height: 30px; padding: 0; margin-left: 5px; display: flex; align-items: center; justify-content: center;" type="button" wire:click.prevent='removeCategory({{$cat->id}})' class="cross-cat-btn"><i class="fa-solid fa-xmark" style="font-size: 18px;"></i> </button>
                        </div>
                    </div>
                    @endforeach
                    <button wire:click.prevent='addNewCategory' type="button" class="btn btn-outline-secondary custom-outline-btn " style="min-width:250px;"><i class="fa-solid fa-plus" style="font-size: 14px;"></i> New Category</button>
                </div>

                @else
                <div class="category-btn-wrapper">
                    @foreach($categories as $cat)
                    <button type="button" style="background-color:{{$cat->color}};" class="placeholder-btn"  wire:click.prevent='changeCategory({{$cat->id}})' >{{$cat->name}}</button>
                    @endforeach
                </div>
                @endif
                <hr style="width: 100%;margin-bottom:0.3rem;">
                <button wire:click='toggleEditMode' type="button" class="btn edit-btn"><i class="fa-solid fa-pencil"></i> {{$isEdit ? 'Apply' : 'Edit Category'}}</button>
                @isset($msg)
                <p class="err-msg">{{$msg}}</p>
                @endisset
            </div>
        </div>
    </div>
    @endif
</div>

