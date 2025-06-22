 <?php 
    $action = route('head_office.head_office_profile_save');
    if(isset($access_rights)){
        $action = route('head_office.head_office_access_right_save');
    }
 ?>
 <form method="post" action="{{$action}}">
    @csrf
    @if(!isset($access_rights))
        @if(isset($profile) &&  $profile->super_access)
        <div class="alert alert-primary">This is system default profile. Editing is disabled.</div>
        @endif
    @endif

    @if(isset($access_rights) && isset($headOfficeUsers))
        <input type="hidden" name="head_office_user_id" value="{{$headOfficeUsers->id}}">
    @endif

    @if(isset($profile) && $profile)
        <input type="hidden" name="id" value="{{$profile->id}}">
    @endif

    @if(!isset($access_rights))
    <div class="profile-page-contents hide-placeholder-parent" style="max-width: 25%; margin:inherit;">
        <label class="inputGroup">Profile Name

            <input style="width:auto;" type="text" name="profile_name" @if(isset($profile)) value="{{$profile->profile_name}}" @endif required >        

        </label>
    </div>
    @endif
    <div class="row">
        @foreach($permission::allModules() as $module=>$title)
        <div class="col-sm-4 my-2">
            <div class="permission_module module_{{$module}} shadow">
                <label class="permission_module_header"> 
                    <input type="checkbox" @if(isset($profile) && ($profile->super_access || $profile->hasPerm($module))) checked @endif class="perm_module checkbox-medium" name="permissions[]" value="{{$module}}"> {{$title}}
                </label>
                <div class="pl-2 module_perms permission_{{$module}}" @if(isset($profile) && ($profile->super_access || $profile->hasPerm($module))) @else style="display:none" @endif>
                    @foreach($permission::getModulePermissions($module) as $key=>$permissionTitle)
                        <label class="block" style="display:block">
                            <input type="checkbox" @if(isset($profile) && ($profile->super_access || $profile->hasPerm($key))) checked @endif class="perm_module checkbox-medium" name="permissions[]" value="{{$key}}"> {{$permissionTitle}}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row">
        <input type="submit" @if(isset($access_rights)) value="Save Access Rights" @else value="Save Profile & Permissions" @endif class="btn btn-info  clear clear-both" @if(isset($profile) && $profile->super_access) style="display:none" @endif>
    </div>
</form>