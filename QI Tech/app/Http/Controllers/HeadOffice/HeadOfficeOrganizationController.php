<?php

namespace App\Http\Controllers\HeadOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationBrandUpdateRequestsFormRequest;
use App\Models\AssignedBespokeForm;
use App\Models\Forms\Record;
use App\Models\Headoffices\Organisation\Group;
use App\Models\Headoffices\Organisation\Level;
use App\Models\Headoffices\Organisation\LocationGroup;
use App\Models\Headoffices\Organisation\LocationTag;
use App\Models\Headoffices\Organisation\TagCategory;
use App\Models\Headoffices\Organisation\Tag;
use App\Models\OrganisationSettingAssignment;
use App\Models\remote_location_tokens;
use Illuminate\Support\Facades\Auth;
use App\Models\HeadOfficeLocation;
use App\Models\HeadOffice;
use App\Models\Headoffices\CaseManager\Task;
use App\Models\Location;
use App\Models\LocationBrandUpdateRequest;
use Illuminate\Http\Request;
;
use Illuminate\Support\Str;
use App\Models\Log;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class HeadOfficeOrganizationController extends Controller
{
    public $perPage = 25;
    
    # My organization
    public function index(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $locations = HeadOfficeLocation::where('head_office_id', $headOffice->id)->paginate($this->perPage);

        $totalLocations =count($locations);
        $assignedToGroups = LocationGroup::where('head_office_id', $headOffice->id)->distinct('head_office_location_id')->count();
        $allGroups = Group::where('head_office_id', $headOffice->id)->where('parent_id', null)->get();
        $notAssigned = $totalLocations - $assignedToGroups;
        return view('head_office.my_organisation.locations',compact('locations','assignedToGroups','notAssigned','allGroups'));
    }
    public function organisation_tags(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $categories = TagCategory::where('head_office_id',$headOffice->id)->get();
        return view('head_office.my_organisation.tag_categories',compact('categories'));
    }
    public function tag_category_save(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $tagCategory = TagCategory::where('head_office_id',$headOffice->id)->where('id',$request->id)->first();
        $creating = false;
        if(!$tagCategory){
            $tagCategory = new TagCategory();
            $creating = true;
        }
        $validated = $request->validate([
            'catgeory_name' => 'required|min:1|max:255'
        ]);
        
        $tagCategory->head_office_id =  $headOffice->id;
        $tagCategory->category_name = $request->catgeory_name;
        $tagCategory->save();

        $action = ($creating)?
            "New tag category '{$tagCategory->category_name}' is created in Head office '{$headOffice->company_name}'":
            "Tag category '{$tagCategory->category_name}' is edited in Head office '{$headOffice->company_name}'";
        Log::log($action,$user->id,'head_office_admin','head_office');
        return back()->with('congrats','Category Saved Successfully.');
    }
    public function tag_category_delete(Request $request,$id){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();

        $tagCategory = TagCategory::where('head_office_id',$headOffice->id)->where('id',$id)->first();
        if(!$tagCategory){
            abort(404);
        } 
        # Delete tag assignments
        $tags = Tag::where('category_id', $tagCategory->id)->get();
        foreach($tags as $tag){
            LocationTag::where('tag_id',$tag->id)->delete();
            $tag->delete();
        }
        # Delete Tags
        Tag::where('category_id', $tagCategory->id)->delete();
        $action = "Tag category '{$tagCategory->category_name}' is deleted with its assignments and tags, in head office '{$headOffice->company_name}'";
        $tagCategory->delete();
        Log::log($action,$user->id,'head_office_admin','head_office');

        return back()->with('congrats','Category Deleted Successfully.');
    }
    public function save_tag(Request $request,$id){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $tagCategory = TagCategory::where('head_office_id',$headOffice->id)->where('id',$id)->first();
        if(!$tagCategory){
            abort(404);
        } 
        $validated = $request->validate([
            'tag_name' => 'required|min:1|max:255',
            'tag_color' => 'required|min:1|max:255'
        ]);
        $tag = Tag::find($request->tag_id);
        if(!$tag){
            $tag = new Tag();
        }
        $tag->category_id =  $tagCategory->id;
        $tag->tag_name = $request->tag_name;
        $tag->color = $request->tag_color;
        $tag->save();

        return back()->with('congrats','Tag Saved Successfully.');
    }
    public function delete_tag(Request $request,$category_id,$id){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $tagCategory = TagCategory::where('head_office_id',$headOffice->id)->where('id',$category_id)->first();
        if(!$tagCategory){
            abort(404);
        } 
         # Delete tag assignments
        LocationTag::where('tag_id',$id)->delete();
        # Delete Tag
        Tag::find($id)->delete();
        return back()->with('congrats','Tag Deleted Successfully.');
    }

    public function organisation_structure(Request $request, $parent_id = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $allGroups = Group::where('head_office_id', $headOffice->id)->where('parent_id',null)->get();
        $parents  = Group::generateParentsArray($parent_id);
        $parentGroup =  Group::where('head_office_id', $headOffice->id)->where('id',$parent_id)->first();
        $groupWithChilds = Group::where('head_office_id', $headOffice->id)->get();

        if($request->query('ajax')){
            $id = $request->query('id');
            $action = $request->query('action');
            $group =  Group::where('head_office_id', $headOffice->id)->where('id',$id)->first();
            if($action != 'add_top_level_element' && !$group){
               abort(404);
            } 
            return view('head_office.my_organisation.organisation_structure_actions',compact('group','action','allGroups','groupWithChilds'));
        }
        
        $groups = Group::where('head_office_id', $headOffice->id)->where('parent_id',$parent_id)->get();
        $maximumDepth = Group::maximumDepthOfLevels();
        $levels = Level::generateLevels($maximumDepth,$headOffice);

        return view('head_office.my_organisation.organisation_structure',
                compact('groups','allGroups','parents','parentGroup','groupWithChilds','maximumDepth','levels'));
    }

    public function add_edit_group(Request $request){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $validated = $request->validate([
            'group_name' => 'required|min:1|max:60',
        ]);
        $group = Group::where('head_office_id', $headOffice->id)->where('id',$request->id)->first();
        if(!$group){
            $group = new Group(); 
            $group->head_office_id = $headOffice->id;
        }

        if (is_array($request->group_name)) {
            foreach ($request->group_name as $name) {
                $group = new Group();  
                $group->group = $name;
                $group->head_office_id = $headOffice->id;
                
                if ($request->is_sub_group) {
                    $group->parent_id = ((int)$request->parent_id) ? (int)$request->parent_id : null;
                } else {
                    $group->parent_id = null;
                }
                
                $group->save();  
            }
        } else {
            $group->group = $request->group_name;
            
            if ($request->is_sub_group) {
                $group->parent_id = ((int)$request->parent_id) ? (int)$request->parent_id : null;
            } else {
                $group->parent_id = null;
            }
            
            $group->save(); 
        }
        

        return redirect()->route('head_office.company_info','#organization_structure')->with('congrats','Group saved Successfully.');
    }
    
    public function delete_group(Request $request, $id  = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $group = Group::where('head_office_id',$headOffice->id)->where('id',$id)->first();
        if(!$group){
            abort(404);
        } 
        # Delete group assignments
        if(count($group->location_groups)){
            $whereToShift = Group::where('head_office_id',$headOffice->id)->where('id',$request->move_assigned_locations_to_group)->first();
            if($request->shift_locations && $whereToShift){
                foreach($group->location_groups as $lg){
                    $lg->group_id = $whereToShift->id; 
                    $lg->save();
                }
            }else{
                # Delete all assignments.
                LocationGroup::where('group_id',$group->id)->delete();
            }
        }
        if(count($group->children)){
            $whereToShift = Group::where('head_office_id',$headOffice->id)->where('id',$request->move_sub_levels_to_group)->first();
            if($request->shift_sub_groups && $whereToShift){
                foreach($group->children as $lg){
                    $lg->parent_id = $whereToShift->id; 
                    $lg->save();
                }
            }else{
                # Delete all assignments.
                Group::deleteNodeAndChildren($group->id);
            }
        }

            $group->delete();
        return redirect()->route('head_office.company_info','#organization_structure')->with('congrats','Group is deleted successfully');
    }

    public function assign_group_to_location(Request $request,$location_id = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $head_office_location = HeadOfficeLocation::where('head_office_id', $headOffice->id)->where('id',$location_id)->first();
        if(!$head_office_location){
            abort(404);
        } 
        $allGroups = Group::where('head_office_id', $headOffice->id)->where('parent_id',null)->get();
        return view('head_office.my_organisation.location_assign_group',compact('head_office_location','allGroups'));
    }
    public function assign_group_to_location_save(Request $request,$head_office_location_id = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $head_office_location = HeadOfficeLocation::where('head_office_id', $headOffice->id)->where('id',$head_office_location_id)->first();
        if(!$head_office_location){
            abort(404);
        } 
        $validated = $request->validate([
            'parent_id' => 'required|min:1',
        ]);

        $assignment = LocationGroup::where('head_office_id',$headOffice->id)
                                    ->where('group_id',$request->parent_id)
                                    ->where('head_office_location_id',$head_office_location->id)->first();
        if(!$assignment){
            $assignment  = new LocationGroup();
            $assignment->head_office_id = $headOffice->id;
            $assignment->group_id = $request->parent_id;
            $assignment->head_office_location_id = $head_office_location->id;
            $assignment->save();
        }
        return redirect()->route('head_office.company_info','#my_locations')->with('congrats','Group is successfully assigned.');
    }
    public function delete_group_from_location(Request $request,$assignment_id){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $assignment = LocationGroup::where('head_office_id', $headOffice->id)->where('id',$assignment_id)->first();

        if(!$assignment){
            abort(404);
        }

        $assignment->delete();
        return redirect()->route('head_office.company_info','#my_locations')->with('congrats','Group is removed.');
    } 
    public function assign_tags_to_location(Request $request,$location_id = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $head_office_location = HeadOfficeLocation::where('head_office_id', $headOffice->id)->where('id',$location_id)->first();
        if(!$head_office_location){
            abort(404);
        } 
        $cats = TagCategory::where('head_office_id', $headOffice->id)->get();
        return view('head_office.my_organisation.location_assign_tag',compact('head_office_location','cats'));
    }

    public function assign_tags_to_location_save (Request $request,$head_office_location_id = null){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $head_office_location = HeadOfficeLocation::where('head_office_id', $headOffice->id)->where('id',$head_office_location_id)->first();
        if(!$head_office_location){
            abort(404);
        } 
        $validated = $request->validate([
            'tag_id' => 'required|array|min:1',
        ]);

        $tags =(array) $request->tag_id;
        LocationTag::where('head_office_id',$headOffice->id)->where('head_office_location_id',$head_office_location->id)->delete();
        foreach($tags as $tag){
            $assignment  = new LocationTag();
            $assignment->head_office_id = $headOffice->id;
            $assignment->tag_id = $tag;
            $assignment->head_office_location_id = $head_office_location->id;
            $assignment->save();
        }
        return redirect()->route('head_office.my_organisation')->with('congrats','Tag is successfully assigned.');
    }

    public function delete_tag_from_location(Request $request,$assignment_id){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $assignment = LocationTag::where('head_office_id', $headOffice->id)->where('id',$assignment_id)->first();

        if(!$assignment){
            abort(404);
        }

        $assignment->delete();
        return redirect()->route('head_office.my_organisation')->with('congrats','Tag is removed.');
    } 

    public function save_level(Request $request){
        $validated = $request->validate([
            'id' => 'required',
            'level_name' => 'required|min:1:max:254',
        ]);
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $level = Level::where('head_office_id', $headOffice->id)->where('id',$request->id)->first();
        if(!$level){
            abort(404);
        }
       
        $level->level_name = $request->level_name;
        $level->save();
        return redirect()->route('head_office.organisation_structure')->with('congrats','Level is saved successfully.');
    }
    public function personalise_location($id) {
        $location = Auth::guard('web')->user()->selected_head_office->locations->where('location_id',$id)->first();
        if($location)
        {
            $location = $location->location;
            return view('head_office.my_organisation.personalise_location',compact('location'));
        }
        abort(403,"You don't have access to this page");
    }
    public function preview_location_color_branding(LocationBrandUpdateRequestsFormRequest $request, $id)
    {
        try {
            $data = $request->getData();
            $location = Auth::guard('web')->user()->selected_head_office->locations->where('location_id', $id)->firstOrFail();
            $location = $location->location;
            $user = Auth::guard('web')->user();

            $data['location_id'] = $location->id;
            $data['user_id'] = $user->id;

            if ($request->preview_btn == 'preview') {
                session(['bg_color_code' => $data['bg_color_code']]);
                session(['font' => $data['font']]);
                if ($request->hasFile('logo_file')) {
                    $request->file('logo_file')->move(public_path('data_images/location_brand_request_files/temp/logo'), $location->id . '.png');
                }
                if ($request->hasFile('bg_file')) {
                    $request->file('bg_file')->move(public_path('data_images/location_brand_request_files/temp/bg'), $location->id . '.png');
                }

                //                dd(Session::get('bg_color_code'),Session::get('font'));
                return view('head_office.my_organisation.location_preview', compact('location'));

            }
            
            $location->bg_color_code = $data['bg_color_code'];
            $location->font = $data['font'];
    
    
            // pick or remove files //
    
    
            $ibp=public_path('data_images/location_branding');
            if(!file_exists($ibp))
            {
                mkdir($ibp);
                mkdir($ibp.'/logo');
                mkdir($ibp.'/bg');
            }

            if($request->hasFile('logo_file'))
            {
                $request->file('logo_file')->move(public_path('data_images/location_branding/logo'), $location->id.'.png');
            }
            if($request->hasFile('bg_file'))
            {
                $request->file('bg_file')->move(public_path('data_images/location_branding/bg'), $location->id .'.png');
            }

            //     if(\file_exists($ibp.'/bg/'.$location->id.'.png'))
            //         \unlink($ibp.'/bg/'.$location->id.'.png');
            
    
            $location->save();

            
            return redirect()->back()->with('success_message','Location branding updated successfully');

        } catch (Exception $exception) {
            dd($exception);

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    public function preview_location_color_branding_get(Request $request){
        
        $location = Auth::guard('web')->user()->selected_head_office->locations->where('location_id', $request->id)->firstOrFail();
        $user = Auth::guard('web')->user();
        $ho = Auth::guard('web')->user()->selected_head_office;
        $location = $location->location;
        Auth::guard('location')->loginUsingId($location->id);
        Auth::guard('user')->login($user);
        session(['bg_color_code' => $location->bg_color_code]);
        session(['font' => $location->font]);
        
        $token = $location->regenerateToken($location->id,$request);


        $reminders = [];
        return redirect()->route('be_spoke_forms.be_spoke_form.records');
    }

    
    public function location_color_css(Request $request,$id)
    {
        
        $location = Auth::guard('web')->user()->selected_head_office->locations->where('location_id', $id)->firstOrFail();
        $location = $location->location;

        $branding = $location->branding;

        if ($request->has('p')) {

            $branding = $location->preview;

        }

        return response(view('styles.location_color', compact('branding')))->header('Content-Type', 'text/css');



    }

    public function assign_setting_to_location($id)  {
        $user = Auth::guard('web')->user();
        if($user->selected_head_office->locations()->find($id))
        {
            $location = $user->selected_head_office->locations()->find($id);
            $head_office_organisation_settings = $user->selected_head_office->organisationSettings()->paginate(10);
            return view('head_office.my_organisation.setting',compact('head_office_organisation_settings','id','location'));
        }
    }
    public function assign_setting_to_location_save(Request $request,$id) {
        $validated = $request->validate([
            'organisation_setting_id' => 'required',
        ]);
        $user = Auth::guard('web')->user();
        $loc = $user->selected_head_office->locations()->find($id);
        $location = $loc->location;

        if($location->organization_setting_assignment()->where([['location_id',$location->id],['o_s_id',$request->organisation_setting_id]])->first())
            return back()->with('cu-error','Setting alredy assigned to location');

        $location->organization_setting_assignment ? $location->organization_setting_assignment->delete(): null;

        // Deleting Existing Settings !
        $location->organization_setting_assignment()->delete(); 

        $location_settings = new OrganisationSettingAssignment();
        $location_settings->location_id = $location->id;
        $location_settings->o_s_id = $request->organisation_setting_id;
        $location_settings->save();

        foreach($location->assigned_bespoke_forms as $f)
        {
            $form = $location->assigned_bespoke_forms()->where([['location_id',$location->id],['be_spoke_form_id',$f->be_spoke_form_id]])->first();
            $form->delete();
            $f->delete();
        }

        $organisation_setting = $user->selected_head_office->organisationSettings()->findOrFail($request->organisation_setting_id);

        $location->font = $organisation_setting->font;
        $location->bg_color_code = $organisation_setting->bg_color_code;
        $location->save();

        foreach($organisation_setting->organisationSettingBespokeForms as $form)
        {
            $location_settings = new AssignedBespokeForm();
            $location_settings->location_id = $location->id;
            $location_settings->be_spoke_form_id = $form->form->id;
            $location_settings->save();

            if(!$location->records()->find($form->id))
            {
                $record = new Record();
                $record->form_id = $form->form->id;
                $record->location_id = $location->id;
                $record->status = 'open';
                $record->case_status = 0;
                $record->priority = 1;
                $record->save();
            }
        }

        return redirect()->route('head_office.company_info','#organization_setting')->with('success_message','Setting applied successfully');
    }

    public function organisation_settings_update($setting_id,$location_id) {
        
        $user = Auth::guard('web')->user();
        $loc = $user->selected_head_office->locations()->find($location_id);
        $location = $loc->location;

        if($location->organization_setting_assignment()->where([['location_id',$location->id],['o_s_id',$setting_id]])->first())
            return back()->with('cu-error','Setting alredy assigned to location');

        $location->organization_setting_assignment ? $location->organization_setting_assignment->delete(): null;

        // Deleting Existing Settings !
        $location->organization_setting_assignment()->delete(); 

        $location_settings = new OrganisationSettingAssignment();
        $location_settings->location_id = $location->id;
        $location_settings->o_s_id = $setting_id;
        $location_settings->save();

        foreach($location->assigned_bespoke_forms as $f)
        {
            $form = $location->assigned_bespoke_forms()->where([['location_id',$location->id],['be_spoke_form_id',$f->be_spoke_form_id]])->first();
            $form->delete();
            $f->delete();
        }

        $organisation_setting = $user->selected_head_office->organisationSettings()->findOrFail($setting_id);

        $location->font = $organisation_setting->font;
        $location->bg_color_code = $organisation_setting->bg_color_code;
        $location->save();

        foreach($organisation_setting->organisationSettingBespokeForms as $form)
        {
            $location_settings = new AssignedBespokeForm();
            $location_settings->location_id = $location->id;
            $location_settings->be_spoke_form_id = $form->form->id;
            $location_settings->save();

            if(!$location->records()->find($form->id))
            {
                $record = new Record();
                $record->form_id = $form->form->id;
                $record->location_id = $location->id;
                $record->status = 'open';
                $record->case_status = 0;
                $record->priority = 1;
                $record->save();
            }
        }

        return redirect()->back()->with('success_message','Setting applied successfully');
    }
    public function organisation_settings_update_multi(Request $request) {
        if($request->has('setting_id') && $request->has('loc_ids')){
            foreach($request->loc_ids as $loc_name){
                $loc = Location::where('username',$loc_name)->first();
                if($loc){
                    $loc->organization_setting_assignment()->delete(); 

                    $location_settings = new OrganisationSettingAssignment();
                    $location_settings->location_id = $loc->id;
                    $location_settings->o_s_id = $request->setting_id;
                    $location_settings->save();
                }
                else{
                    continue;
                }
            }
            return response()->json(['success'=>'Settings updated!'],200);
        }
        return response('Not Allowed', 403);
    }



    
}