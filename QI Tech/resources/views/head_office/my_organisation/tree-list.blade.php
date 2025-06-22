@if(!isset($multiple))
<ul class="tree-list @if(!isset($parent)) top-tree-list @endif @if(isset($parent) && isset($parents) && !in_array($parent->id,$parents)) collapse @elseif(isset($parent) && !isset($parents)) collapse   @endif fa-ul" @if(isset($parent)) parent-id="{{$parent->id}}" @endif>
    @foreach($groups as $g)
    @if(isset($deleting_tier_id) && $deleting_tier_id ==  $g->id) @continue @endif
    
    <li class="group_{{$g->id}}" >
        @if(!empty($g->children) && $g->children->count())
            <i class="fa-li fa fa-caret-down"></i>  
        @else 
            <i class="fa-li fa" aria-hidden="true"></i>
        @endif
        <label class=" btn btn-outline btn-outline-info @if(isset($parentGroup) && $g->id == $parentGroup->id) active @endif" >
            <input class="tree_radio" type="radio" @if(isset($parentGroup) && $g->id == $parentGroup->id) checked @endif @if(isset($tree_input_name)) name="{{$tree_input_name}}" @else name="parent_id" @endif value="{{$g->id}}"> {{$g->group}}
        </label>
        @if(!empty($g->children) && $g->children->count())
            @include('head_office.my_organisation.tree-list',['groups' => $g->children,'parent'=>$g])
        @endif
    </li>
    @endforeach
</ul>
@else
<ul class="tree-list multiple @if(!isset($parent)) top-tree-list @endif @if(isset($parent) && isset($parents) && !in_array($parent->id,$parents)) collapse @elseif(isset($parent) && !isset($parents)) collapse   @endif fa-ul" @if(isset($parent)) parent-id="{{$parent->id}}" @endif>
@foreach($groups as $g)  
    <li class="group_{{$g->id}}" >
        @if(!empty($g->children) && $g->children->count())
            <i class="fa-li fa fa-caret-down"></i>  
        @else 
            <i class="fa-li fa" aria-hidden="true"></i>
        @endif
        
        <label class=" btn btn-outline btn-outline-info @if(isset($selected) && $selected->contains('group_id',$g->id)) active @endif" >
            <input class="tree_radio tree_checkbox" type="checkbox" @if(isset($selected) && $selected->contains('group_id',$g->id)) checked @endif @if(isset($tree_input_name)) name="{{$tree_input_name}}" @else name="group_id[]" @endif value="{{$g->id}}"> 
            {{$g->group}}
        </label>
        @if(!empty($g->children) && $g->children->count())
            @include('head_office.my_organisation.tree-list',['groups' => $g->children,'parent'=>$g])
        @endif
    </li>
    @endforeach
</ul>
@endif