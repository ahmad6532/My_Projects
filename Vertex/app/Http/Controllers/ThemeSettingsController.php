<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Models\ThemeSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Config;
use Illuminate\Http\Request;

class ThemeSettingsController extends Controller
{
    public function AppearanceSetting()
    {
        $user = auth()->user();
        $getAllThemes = ThemeSetting::where('is_delete', '0')->get();
        $getActiveTheme = Setting::where('perimeter', 'Active_Theme_Id')->first()['value'];
        return view('theme.appearance_setting', compact('getAllThemes', 'getActiveTheme','user'));
    }

    public function NewAppearanceSetting()
    {
        $getThemeData = null;
        return view('theme.new-appearance-setting', compact('getThemeData'));
    }

    public function saveAppearance(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'theme_name' => 'required',
            'heading_color' => 'required',
            'text_color' => 'required',
            'sidebar_color' => 'required',
            'sidebar_background_color' => 'required',
            'body_background_color' => 'required',
            'header_background_color' => 'required',
            'sidebar_hover' => 'required',
            'button_background_color' => 'required',
            'button_text_color' => 'required',
            'btn_border_color' => 'required',
            'pagination_active_bg' => 'required',
            'pagination_active_color' => 'required',
            'tabs_color' => 'required',
            'tabs_active_color' => 'required',
            'tabs_active_background_color' => 'required',
            'icon_color' => 'required',

        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        if ($request->has('theme_id') && $request->theme_id != null) {
            $addTheme = ThemeSetting::find($request->theme_id);
        } else {
            $addTheme = new ThemeSetting();
        }
        $hexColor = $request->BTN_SHADOW_COLOR;

        // Remove the '#' symbol if it exists
        if (strpos($hexColor, '#') === 0) {
            $hexColor = substr($hexColor, 1);
        }

        $red = hexdec(substr($hexColor, 0, 2));
        $green = hexdec(substr($hexColor, 2, 2));
        $blue = hexdec(substr($hexColor, 4, 2));
        $addTheme->theme_name = $request->theme_name;
        $addTheme->heading_color = $request->heading_color;
        $addTheme->text_color = $request->text_color;
        $addTheme->sidebar_color = $request->sidebar_color;
        $addTheme->sidebar_background_color = $request->sidebar_background_color;
        $addTheme->body_background_color = $request->body_background_color;
        $addTheme->header_background_color = $request->header_background_color;
        $addTheme->sidebar_hover = $request->sidebar_hover;
        $addTheme->button_background_color = $request->button_background_color;
        $addTheme->button_text_color = $request->button_text_color;
        $addTheme->btn_border_color = $request->btn_border_color;
        $addTheme->pagination_active_bg = $request->pagination_active_bg;
        $addTheme->pagination_active_color = $request->pagination_active_color;
        $addTheme->tabs_color = $request->tabs_color;
        $addTheme->tabs_active_color = $request->tabs_active_color;
        $addTheme->tabs_active_background_color = $request->tabs_active_background_color;
        $addTheme->icon_color = $request->icon_color;

        $addTheme->save();
        $msg = 'Appearance "'.$request->theme_name.'" Added Successfully';
        createLog('global_action',$msg);
        if ($request->has('apply')) {
            $setActiveTheme = Setting::where('perimeter', 'Active_Theme_Id')->update(['value' => $addTheme->id]);
            $updateBtn = Config::where('param', 'heading_color')->update(['value' => $request->heading_color]);
            $updateBtnClr = Config::where('param', 'text_color')->update(['value' => $request->text_color]);
            $updateBtnShd = Config::where('param', 'sidebar_color')->update(['value' => $addTheme->sidebar_color]);
            $updateTxtClr = Config::where('param', 'sidebar_background_color')->update(['value' => $request->sidebar_background_color]);
            $updateBGClr = Config::where('param', 'body_background_color')->update(['value' => $request->body_background_color]);
            $updateIClr = Config::where('param', 'header_background_color')->update(['value' => $request->header_background_color]);
            $updateHClr = Config::where('param', 'sidebar_hover')->update(['value' => $request->sidebar_hover]);
            $updateBdrClr = Config::where('param', 'button_background_color')->update(['value' => $request->button_background_color]);
            $updateRDBgClr = Config::where('param', 'button_text_color')->update(['value' => $request->button_text_color]);
            $updatebDBgClr = Config::where('param', 'btn_border_color')->update(['value' => $request->btn_border_color]);
            $updatebDBgClar = Config::where('param', 'pagination_active_bg')->update(['value' => $request->pagination_active_bg]);
            $updatebDBgClbr = Config::where('param', 'pagination_active_color')->update(['value' => $request->pagination_active_color]);
            $updatebDBgClcr = Config::where('param', 'tabs_color')->update(['value' => $request->tabs_color]);
            $updatebDBgCldr = Config::where('param', 'tabs_active_color')->update(['value' => $request->tabs_active_color]);
            $updatebDBgCler = Config::where('param', 'tabs_active_background_color')->update(['value' => $request->tabs_active_background_color]);
            $updatebDBgClfr = Config::where('param', 'icon_color')->update(['value' => $request->icon_color]);
            if ($updatebDBgClfr && $request->has('theme_id')) {
                Session::flash('success', 'Theme Appearance Updated and Applied Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('appearance.setting')->with('Success', 'Theme Appearance Updated and Applied Successfully!');
            } else {
                Session::flash('success', 'Theme Appearance Added and Applied Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('appearance.setting')->with('Success', 'Theme Appearance Added and Applied Successfully!');
            }
        }
        if ($addTheme) {
            if ($request->has('theme_id')) {
                Session::flash('success', 'Theme Appearance Updated Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('appearance.setting')->with('Success', 'Theme Appearance Added Successfully!');
            } else {
                Session::flash('success', 'Theme Appearance Added Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('appearance.setting')->with('Success', 'Theme Appearance Added Successfully!');
            }
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('appearance.setting')->with('Error', 'An Error occured! Please try again later');
        }
    }

    public function setDefaultTheme($id)
    {
        $setActiveTheme = Setting::where('perimeter', 'Active_Theme_Id')->update(['value' => $id]);
        $getThemeData = ThemeSetting::find($id);
        $updateBtn = Config::where('param', 'heading_color')->update(['value' => $getThemeData->heading_color]);
        $updateBtnClr = Config::where('param', 'text_color')->update(['value' => $getThemeData->text_color]);
        $updateBtnShd = Config::where('param', 'sidebar_color')->update(['value' => $getThemeData->sidebar_color]);
        $updateTxtClr = Config::where('param', 'sidebar_background_color')->update(['value' => $getThemeData->sidebar_background_color]);
        $updateBGClr = Config::where('param', 'body_background_color')->update(['value' => $getThemeData->body_background_color]);
        $updateIClr = Config::where('param', 'header_background_color')->update(['value' => $getThemeData->header_background_color]);
        $updateHClr = Config::where('param', 'sidebar_hover')->update(['value' => $getThemeData->sidebar_hover]);
        $updateBdrClr = Config::where('param', 'button_background_color')->update(['value' => $getThemeData->button_background_color]);
        $updateRDBgClr = Config::where('param', 'button_text_color')->update(['value' => $getThemeData->button_text_color]);
        $updateRDBgaClr = Config::where('param', 'btn_border_color')->update(['value' => $getThemeData->btn_border_color]);
        $updateRDBgbClr = Config::where('param', 'pagination_active_bg')->update(['value' => $getThemeData->pagination_active_bg]);
        $updateRDBgcClr = Config::where('param', 'pagination_active_color')->update(['value' => $getThemeData->pagination_active_color]);
        $updateRDBgdClr = Config::where('param', 'tabs_color')->update(['value' => $getThemeData->tabs_color]);
        $updateRDBgeClr = Config::where('param', 'tabs_active_color')->update(['value' => $getThemeData->tabs_active_color]);
        $updateRDBgfClr = Config::where('param', 'tabs_active_background_color')->update(['value' => $getThemeData->tabs_active_background_color]);
        $updateRDBggClr = Config::where('param', 'icon_color')->update(['value' => $getThemeData->icon_color]);
        if ($setActiveTheme) {
            Session::flash('success', 'Theme Activated Successfully');
            Session::flash('alert-class', 'alert-success');

            $msg = 'Appearance "'.$getThemeData->theme_name.'" Applied successfully';
            createLog('global_action',$msg);

            return redirect()->back()->with('Success', 'Theme Activated Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }

    }

    public function updateAppearance($id)
    {
        $getThemeData = ThemeSetting::find($id);

        $msg = 'Appearance "'.$getThemeData->theme_name.'" Updated Successfully';
        createLog('global_action',$msg);

        return view('theme.new-appearance-setting', compact('getThemeData'));
    }

    public function deleteTheme($id)
    {
        $theme = ThemeSetting::where('id', $id)->first();
        $theme->update(['is_delete' => '1']);

        if ($theme) {
            Session::flash('success', 'Theme Deleted Successfully');
            Session::flash('alert-class', 'alert-success');

            $msg = 'Appearance "'.$theme->theme_name.'" Deleted Successfully';
            createLog('global_action',$msg);

            return redirect()->back()->with('Success', 'Theme Deleted Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }

    public function themeSettings(Request $request)
    {
        $user = auth()->user();
        $AppFaviconL = DB::table('settings')->where('perimeter', 'App_Favicon')->first();
        $AppLogo = DB::table('settings')->where('perimeter', 'App_Logo')->first();
        $appName = DB::table('settings')->where('perimeter', 'App_Name')->first();
        return view('theme.theme_setting', compact('AppFaviconL', 'AppLogo', 'appName','user'))->with('Success', 'Theme Updated Successfully');
    }

    public function updatetheme(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tab_name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $fields = [
            'favicon' => 'App_Favicon',
            'applogo' => 'App_Logo',
        ];

        $path = public_path('assets/images/theme/');

        foreach ($fields as $field => $perimeter) {
            $value = $request->file($field);
            if ($request->hasFile($field)) {
                $setting = DB::table('settings')->where('perimeter', $perimeter)->first();
                if ($setting->value != '' && $setting->value != null) {
                    $file_old = $path . $setting->value;
                    if (file_exists(public_path() . 'assets/images/theme/' . $setting->value)) {
                        unlink($file_old);
                    }
                }
                $filename = $value->getClientOriginalName();
                $time = intval(microtime(true) * 1000);
                $filename = $time . '_' . $filename;
                $value->move($path, $filename);
                DB::table('settings')->where('perimeter', $perimeter)->update(['value' => $filename]);
            }
        }
        $updateFields = [
            'tab_name' => 'App_Name'
        ];
        foreach ($updateFields as $field => $perimeter) {
            $value = $request->$field;
            DB::table('settings')->where('perimeter', $perimeter)->update(['value' => $value]);
        }

        $msg = 'Theme updated successfully';
        createLog('global_action',$msg);

        Session::flash('success', 'Theme Settings Updated Successfully');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back()->with('Success', 'Theme Settings Updated Successfully');
    }

}
