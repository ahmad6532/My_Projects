
@if($action == 'add_child')
<h3 class="text-info h3 font-weight-bold inline">Add New Level to {{$group->group}}</h3>
<form method="post" action="{{route('head_office.organisation.group.save')}}" @if(!$group) id="level_form" @endif>
    @csrf
    <input type="hidden" name="parent_id" @if($group) value="{{$group->id}}" @endif>
    <input type="hidden" name="is_sub_group" value="1">
    <div class="organisation-structure-add-content">
        <label class="inputGrou">New Tier/Group Name
            <p class="m-0 text-body-tertiary">use comma separated values to add multiple</p>
            <select id="group-name" name="group_name[]" multiple="multiple" required></select>
        </label>
        
    
    </div>
    <div class="form-group">
        <input type="submit" name="save" value="Save" class="btn btn-info">
    </div>
    
</form>
@elseif($action == 'edit')
<h3 class="text-info h3 font-weight-bold inline">Edit Level - {{$group->group}}</h3>
<form method="post" action="{{route('head_office.organisation.group.save')}}" @if(!$group) id="level_form" @endif>
    @csrf
    <input type="hidden" name="id" value="{{$group->id}}">
    @if($group && $group->parent_id)
    <input type="hidden" name="parent_id" @if($group) value="{{$group->parent_id}}" @endif>
    <input type="hidden" name="is_sub_group" value="1">
    @endif
    <div class="organisation-structure-add-content">
        <label class="inputGroup">New Name
            <input type="text" name="group_name" required value="{{$group->group}}">
        </label>
        
    </div>
    
    <div class="form-group">
        <input type="submit" name="save" value="Save" class="btn btn-info">
    </div>
</form>
@elseif($action == 'delete')
<form method="post" action="{{route('head_office.organisation.group.delete',$group->id)}}" @if(!$group) id="level_form" @endif>
    @csrf
    <input type="hidden" name="id" @if($group) value="{{$group->id}}" @endif>
    <h3 class="text-info h3 font-weight-bold inline">Deleting - {{$group->group}}</h3>
    <br>
    @if(count($group->location_groups))
    <div class="from-group">
        <label>
            <input type="checkbox" name="shift_locations" class="shift_locations" value="1">
            Move assigned locations to other group/tier?
        </label>
        
    </div>
    @else
    <p class="font-italic">This group has no locations assigned.</p> 
    @endif
    <div class="parent_group_wrapper" @if(!isset($parentGroup)) style="display:none" @endif>
        <label>Select group/tier to move locations</label>
        @include('head_office.my_organisation.tree-list',['groups' => $allGroups,'tree_input_name'=>'move_assigned_locations_to_group'])
    </div>
    @if(count($group->children))
    <div class="from-group">
        <label>
            <input type="checkbox" name="shift_sub_groups" class="shift_sub_groups" value="1">
            Move sub levels to new group/tier?
        </label>
    </div>
    @else
    <p class="font-italic">This group has no sub levels.</p> 
    @endif
    
   
    <div class="move_group_wrapper" style="display:none">
        <label>Select group/tier to move sub levels</label>
        @include('head_office.my_organisation.tree-list',['groups' => $allGroups,'tree_input_name'=>'move_sub_levels_to_group'])
    </div>
    <div class="form-group">
        <input type="submit" name="Delete" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this level?')">
    </div>
</form>
@elseif($action == 'add_top_level_element')
<form method="post" action="{{route('head_office.organisation.group.save')}}" @if(!$group) id="level_form" @endif>
    @csrf
    <h3 class="text-info h3 font-weight-bold inline">Add New Top Tier/Group</h3>
    <div class="organisation-structure-add-content">
        <label class="inputGrou">Tier/Group Name
            <p class="m-0 text-body-tertiary">use comma separated values to add multiple</p>
            <select id="group-name" name="group_name[]" multiple="multiple" required></select>
        </label>
        
    </div>
    <div class="form-group">
        <input type="submit" name="save" value="Save" class="btn btn-info">
</div>
@elseif($action == 'move')
<form method="post" action="{{route('head_office.organisation.group.save')}}" @if(!$group) id="level_form" @endif>
    @csrf
    <input type="hidden" name="id" @if($group) value="{{$group->id}}" @endif>
    <input type="hidden" name="is_sub_group" value="1">
    <input type="hidden" name="group_name" value="{{$group->group}}">

    <h3 class="text-info h3 font-weight-bold inline">Move group along with its child group/tiers - {{$group->group}}</h3>
    <div class="parent_group_wrapper">
        <label>Select new parent group/tier</label>
        @include('head_office.my_organisation.tree-list',['groups' => $allGroups])
    </div>
    <div class="form-group">
        <input type="submit" name="save" value="Save" class="btn btn-info">
    </div>
</form>
@endif

<script>
    $(document).ready(function() {
    // Initialize Select2 with tags support
    $('#group-name').select2({
        tags: true, // Enables user to create new options
        tokenSeparators: [','], // Defines comma as separator
        placeholder: "Tier/Group Name",
        width: '100%', // Set width to fit the parent container
        dropdownParent: $('#level_action_modal .modal-content')
    });

    // Optional: Preload existing group if $group exists (you can adapt this to fit your Laravel setup)
    @if($group)
        let existingGroup = '{{ $group->group }}'.split(',');
        $('#group-name').val(existingGroup).trigger('change');
    @endif
});

</script>