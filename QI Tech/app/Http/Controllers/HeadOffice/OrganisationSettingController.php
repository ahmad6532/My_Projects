<?php

namespace App\Http\Controllers\HeadOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganisationSettingFormRequest;
use App\Models\OrganisationSetting;
use App\Models\OrganisationSettingBespokeForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganisationSettingController extends Controller
{
    public function index()
    {
        $head_office = Auth::guard('web')->user();
        $head_office_organisation_settings = $head_office->selected_head_office->organisationSettings()->paginate(10);
       
        return view('head_office.organisation_settings.index', compact('head_office_organisation_settings'));
    }
    public function create($id = null)
    {
        $head_office = Auth::guard('web')->user();
        $setting = Auth::guard('web')->user()->selected_head_office->organisationSettings()->find($id);
        $forms = $head_office->selected_head_office->be_spoke_forms;
        $ids = [];
        return view('head_office.organisation_settings.create', compact('setting','forms','ids'));
    }
    public function store(OrganisationSettingFormRequest $request, $id = null)
    {

        $data = $request->getData();
        $user = Auth::guard('web')->user();
            $check = $user->selected_head_office->organisationSettings()->where('name', $data['name'])->first();
            if ($check)
                return back()->with('error', 'Setting Name already exsist');
            $setting = new OrganisationSetting();
            $setting->name = $data['name'];
            $setting->head_office_id = $user->selected_head_office->id;
            $setting->bg_color_code = $data['bg_color_code'];
            $setting->font = 'Arial';
            $setting->save();

            // $ids = [];

            // foreach($request->ids as $id){
            //     $organisation_settings_bespoke_form = $setting->organisationSettingBespokeForms()->find($id);
                
            //     if(!$organisation_settings_bespoke_form)
            //     {
            //         $organisation_settings_bespoke_form = new OrganisationSettingBespokeForm();
            //         $organisation_settings_bespoke_form->o_s_id = $setting->id;
            //         $organisation_settings_bespoke_form->be_spoke_form_id = $id;
            //         $organisation_settings_bespoke_form->save();
            //     }
            // }


            if ($request->hasFile('logo_file')) {
                $request->file('logo_file')->move(public_path('data_images/setting/logo'), $setting->id . '.png');
            }
            if ($request->hasFile('bg_file')) {
                $request->file('bg_file')->move(public_path('data_images/setting/bg'), $setting->id . '.png');
            }
            // if($request->hasFile('bg_file'))
            // {
            //     $request->file('bg_file')->move(public_path('data_images/ho_brand_files/bg'), $ho->id .'.png');
            // }

            return redirect()->route('head_office.company_info','#themeClick')
                ->with('success_message', 'Organisation Setting Created successfully');
    }
    public function edit($id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $setting = $user->organisationSettings()->find($id);
        $forms = $user->be_spoke_forms;
        $ids = [];
        foreach($setting->organisationSettingBespokeForms as $form)
        {
            $ids[] = $form->be_spoke_form_id;
        }
        return view('head_office.organisation_settings.create', compact('setting','forms','ids'));
    }
    public function update($id, OrganisationSettingFormRequest $request)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $setting = $user->organisationSettings()->find($id);
        $setting->update($request->getData());

        // foreach($request->ids as $id){
        //     $organisation_settings_bespoke_form = $setting->organisationSettingBespokeForms()->where('be_spoke_form_id',$id)->first();
        //     if(!$organisation_settings_bespoke_form)
        //     {
        //         $organisation_settings_bespoke_form = new OrganisationSettingBespokeForm();
        //         $organisation_settings_bespoke_form->o_s_id = $setting->id;
        //         $organisation_settings_bespoke_form->be_spoke_form_id = $id;
        //         $organisation_settings_bespoke_form->save();
        //     }
        // }

        if ($request->hasFile('logo_file')) {
            $request->file('logo_file')->move(public_path('data_images/setting/logo'), $setting->id . '.png');
        }
        if ($request->hasFile('bg_file')) {
            $request->file('bg_file')->move(public_path('data_images/setting/bg'), $setting->id . '.png');
        }
        return redirect()->route('head_office.company_info','#themeClick')->with('success_message', 'Setting ' . $setting->name . ' Updated Successfully');
    }
    public function delete($id) {
        $user = Auth::guard('web')->user()->selected_head_office;
        $setting = $user->organisationSettings()->find($id);
        $setting->delete();
        return back()->with('success_message','Setting deleted Successfully');

    }
}