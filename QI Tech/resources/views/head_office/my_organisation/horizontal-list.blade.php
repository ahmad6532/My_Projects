<div class="row horizontal-scroll @if(isset($parent)) child child_{{$parent->id}} @endif" >
@foreach($groups as $g) 
    <div data-bs-id="{{$g->id}}" id="level_{{$g->id}}"  class="col-auto col-infinite col-tree" @if($g->parent_id) draggable="true" @endif>
        <div class="btn btn-full btn-level @if(!isset($parent)) top-level @endif">{{$g->group}}
            <div class="action-bar card card-qi">
                <a href="#" data-bs-toggle="modal" data-action="add_child" data-level="{{$g->id}}" data-bs-target="#level_action_modal" class="btn organisation_level_actions"><i class="fa fa-plus"></i> Add Child</a>
                <a href="#" data-bs-toggle="modal" data-action="edit" data-level="{{$g->id}}" data-bs-target="#level_action_modal" class="btn text-info organisation_level_actions"><i class="fa fa-edit"></i> Edit</a>
                <a href="#"  data-bs-toggle="modal" data-action="delete" data-level="{{$g->id}}" data-bs-target="#level_action_modal" class="organisation_level_actions btn text-danger"><i class="fa fa-trash"></i> Delete</a>
                <a href="#"  data-bs-toggle="modal" data-action="move" data-level="{{$g->id}}" data-bs-target="#level_action_modal" class="organisation_level_actions text-info"><i class="fas fa-arrows-alt"></i> Move</a>
            </div>
        </div>
        @if(!empty($g->children) && $g->children->count())
        <div class="tree-childs row">
            <div class="col-auto col-infinite col-tree">
                @include('head_office.my_organisation.horizontal-list',['groups' => $g->children,'parent'=>$g])
            </div>
        </div> 
    @endif
</div> 
@endforeach
</div>