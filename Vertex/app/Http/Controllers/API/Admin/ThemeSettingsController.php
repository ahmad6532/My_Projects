<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Models\ThemeSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Config;
use Illuminate\Http\Request;

class ThemeSettingsController extends BaseController
{
    public function themeList()
    {
        $user = auth()->user();
        $getAllThemes = ThemeSetting::where('is_delete', '0')->paginate();
        $getActiveTheme = Setting::where('perimeter', 'Active_Theme_Id')->first()['value'];
        $data['all_themes'] = $getAllThemes;
        $data['active_theme'] = $getActiveTheme;
        if ($data) {
            return $this->sendResponse($data, 'Theme list fetched successfully!', 200);
        } else {
            return $this->sendResponse($data, 'Data not found!', 200);
        }
    }

    // public function saveAppearance(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    //         'theme_name' => 'required',
    //         'heading_color' => 'required',
    //         'text_color' => 'required',
    //         'sidebar_color' => 'required',
    //         'sidebar_background_color' => 'required',
    //         'body_background_color' => 'required',
    //         'header_background_color' => 'required',
    //         'sidebar_hover' => 'required',
    //         'button_background_color' => 'required',
    //         'button_text_color' => 'required',
    //         'btn_border_color' => 'required',
    //         'pagination_active_bg' => 'required',
    //         'pagination_active_color' => 'required',
    //         'tabs_color' => 'required',
    //         'tabs_active_color' => 'required',
    //         'tabs_active_background_color' => 'required',
    //         'icon_color' => 'required',
    //     ]);
    //     if ($validate->fails()) {
    //         return $this->sendResponse([], $validate->errors(), 400);
    //     }

    //     if ($request->has('theme_id') && $request->theme_id != null) {
    //         $addTheme = ThemeSetting::find($request->theme_id);
    //     } else {
    //         $addTheme = new ThemeSetting();
    //     }
    //     $hexColor = $request->BTN_SHADOW_COLOR;


    //     if (strpos($hexColor, '#') === 0) {
    //         $hexColor = substr($hexColor, 1);
    //     }

    //     $red = hexdec(substr($hexColor, 0, 2));
    //     $green = hexdec(substr($hexColor, 2, 2));
    //     $blue = hexdec(substr($hexColor, 4, 2));
    //     $addTheme->theme_name = $request->theme_name;
    //     $addTheme->heading_color = $request->heading_color;
    //     $addTheme->text_color = $request->text_color;
    //     $addTheme->sidebar_color = $request->sidebar_color;
    //     $addTheme->sidebar_background_color = $request->sidebar_background_color;
    //     $addTheme->body_background_color = $request->body_background_color;
    //     $addTheme->header_background_color = $request->header_background_color;
    //     $addTheme->sidebar_hover = $request->sidebar_hover;
    //     $addTheme->button_background_color = $request->button_background_color;
    //     $addTheme->button_text_color = $request->button_text_color;
    //     $addTheme->btn_border_color = $request->btn_border_color;
    //     $addTheme->pagination_active_bg = $request->pagination_active_bg;
    //     $addTheme->pagination_active_color = $request->pagination_active_color;
    //     $addTheme->tabs_color = $request->tabs_color;
    //     $addTheme->tabs_active_color = $request->tabs_active_color;
    //     $addTheme->tabs_active_background_color = $request->tabs_active_background_color;
    //     $addTheme->icon_color = $request->icon_color;

    //     $addTheme->save();

    //     $msg = 'Appearance "' . $request->theme_name . '" Added Successfully';
    //     createLog('global_action', $msg);
    //     if ($request->has('apply')) {
    //         $setActiveTheme = Setting::where('perimeter', 'Active_Theme_Id')->update(['value' => $addTheme->id]);
    //         $updateBtn = Config::where('param', 'heading_color')->update(['value' => $request->heading_color]);
    //         $updateBtnClr = Config::where('param', 'text_color')->update(['value' => $request->text_color]);
    //         $updateBtnShd = Config::where('param', 'sidebar_color')->update(['value' => $addTheme->sidebar_color]);
    //         $updateTxtClr = Config::where('param', 'sidebar_background_color')->update(['value' => $request->sidebar_background_color]);
    //         $updateBGClr = Config::where('param', 'body_background_color')->update(['value' => $request->body_background_color]);
    //         $updateIClr = Config::where('param', 'header_background_color')->update(['value' => $request->header_background_color]);
    //         $updateHClr = Config::where('param', 'sidebar_hover')->update(['value' => $request->sidebar_hover]);
    //         $updateBdrClr = Config::where('param', 'button_background_color')->update(['value' => $request->button_background_color]);
    //         $updateRDBgClr = Config::where('param', 'button_text_color')->update(['value' => $request->button_text_color]);
    //         $updatebDBgClr = Config::where('param', 'btn_border_color')->update(['value' => $request->btn_border_color]);
    //         $updatebDBgClar = Config::where('param', 'pagination_active_bg')->update(['value' => $request->pagination_active_bg]);
    //         $updatebDBgClbr = Config::where('param', 'pagination_active_color')->update(['value' => $request->pagination_active_color]);
    //         $updatebDBgClcr = Config::where('param', 'tabs_color')->update(['value' => $request->tabs_color]);
    //         $updatebDBgCldr = Config::where('param', 'tabs_active_color')->update(['value' => $request->tabs_active_color]);
    //         $updatebDBgCler = Config::where('param', 'tabs_active_background_color')->update(['value' => $request->tabs_active_background_color]);
    //         $updatebDBgClfr = Config::where('param', 'icon_color')->update(['value' => $request->icon_color]);
    //         if ($updatebDBgClfr && $request->has('theme_id')) {
    //             return $this->sendResponse([], 'Apperance update successfuly!', 200);
    //         } else {
    //             return $this->sendResponse([], 'Apperance update successfully!', 200);
    //         }
    //     }
    //     if ($addTheme) {
    //         if ($request->has('theme_id')) {
    //             return $this->sendResponse([], 'Apperance update successfully!', 200);
    //         } else {
    //             return $this->sendResponse([], 'Apperance save successfully!', 200);
    //         }
    //     } else {
    //         return $this->sendResponse([], 'An Error occured! Please try again later!', 500);
    //     }
    // }
    public function saveAppearance(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'appearance_name' => 'required|string|max:255',
            'navbar_heading_color' => 'required|regex:/^#([a-fA-F0-9]{6})$/',
            'navbar_background_color' => 'required|regex:/^#([a-fA-F0-9]{6})$/',
            'body_background_color' => 'required|regex:/^#([a-fA-F0-9]{6})$/',
            'primary_color' => 'required|regex:/^#([a-fA-F0-9]{6})$/',
            'sidebar_background_color' => 'required|regex:/^#([a-fA-F0-9]{6})$/',
            'sidebar_text_color' => 'required|regex:/^#([a-fA-F0-9]{6})$/',
            'heading_color' => 'required|regex:/^#([a-fA-F0-9]{6})$/',
            'sub_heading_text_color' => 'required|regex:/^#([a-fA-F0-9]{6})$/',
            'paragraph_text_color' => 'required|regex:/^#([a-fA-F0-9]{6})$/',
        ]);

        if ($validate->fails()) {
            return $this->sendResponse([], $validate->errors(), 400);
        }

        $addTheme = $request->has('theme_id') ? ThemeSetting::find($request->theme_id) : new ThemeSetting();
        if (!$addTheme) {
            return $this->sendResponse([], 'Theme not found.', 404);
        }

        $hexColor = ltrim($request->BTN_SHADOW_COLOR ?? '#000000', '#');
        $red = $green = $blue = 0;
        if (ctype_xdigit($hexColor) && strlen($hexColor) == 6) {
            $red = hexdec(substr($hexColor, 0, 2));
            $green = hexdec(substr($hexColor, 2, 2));
            $blue = hexdec(substr($hexColor, 4, 2));
        }

        $addTheme->theme_name = $request->appearance_name;
        $addTheme->navbar_heading_color = $request->navbar_heading_color;
        $addTheme->navbar_background_color = $request->navbar_background_color;
        $addTheme->body_background_color = $request->body_background_color;
        $addTheme->primary_color = $request->primary_color;
        $addTheme->sidebar_background_color = $request->sidebar_background_color;
        $addTheme->sidebar_text_color = $request->sidebar_text_color;
        $addTheme->heading_color = $request->heading_color;
        $addTheme->sub_heading_text_color = $request->sub_heading_text_color;
        $addTheme->paragraph_text_color = $request->paragraph_text_color;

        $addTheme->save();

        $msg = 'Appearance "' . $request->appearance_name . '" Added Successfully';
        createLog('global_action', $msg);

        if ($request->has('apply')) {
            DB::transaction(function () use ($addTheme, $request) {
                Setting::where('perimeter', 'Active_Theme_Id')->update(['value' => $addTheme->id]);
                Config::where('param', 'appearance_name')->update(['value' => $request->appearance_name]);
                Config::where('param', 'navbar_heading_color')->update(['value' => $request->navbar_heading_color]);
                Config::where('param', 'navbar_background_color')->update(['value' => $request->navbar_background_color]);
                Config::where('param', 'body_background_color')->update(['value' => $request->body_background_color]);
                Config::where('param', 'primary_color')->update(['value' => $request->primary_color]);
                Config::where('param', 'side_menu_background_color')->update(['value' => $request->sidebar_background_color]);
                Config::where('param', 'side_menu_text_color')->update(['value' => $request->sidebar_text_color]);
                Config::where('param', 'heading_text_color')->update(['value' => $request->heading_color]);
                Config::where('param', 'sub_heading_text_color')->update(['value' => $request->sub_heading_text_color]);
                Config::where('param', 'paragraph_text_color')->update(['value' => $request->paragraph_text_color]);
            });

            return response()->json([
                'status' => 1,
                'message' => 'Appearance applied and saved successfully!',
            ], 200);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Appearance saved successfully',
            'data' => $addTheme,
        ], 200);
    }

    public function setDefaultTheme(Request $request)
    {
        $themeId = $request->theme_id;

        // Update the active theme ID setting
        $setActiveTheme = Setting::where('perimeter', 'Active_Theme_Id')->update(['value' => $themeId]);

        // Retrieve the theme details to apply
        $getThemeData = ThemeSetting::find($themeId);
        if (!$getThemeData) {
            return $this->sendError(null, 'Theme not found!', 404);
        }

        // Apply theme configuration values to the Config table
        Config::where('param', 'appearance_name')->update(['value' => $getThemeData->theme_name]);
        Config::where('param', 'navbar_heading_color')->update(['value' => $getThemeData->navbar_heading_color]);
        Config::where('param', 'navbar_background_color')->update(['value' => $getThemeData->navbar_background_color]);
        Config::where('param', 'body_background_color')->update(['value' => $getThemeData->body_background_color]);
        Config::where('param', 'primary_color')->update(['value' => $getThemeData->primary_color]);
        Config::where('param', 'side_menu_background_color')->update(['value' => $getThemeData->sidebar_background_color]);
        Config::where('param', 'side_menu_text_color')->update(['value' => $getThemeData->sidebar_text_color]);
        Config::where('param', 'heading_text_color')->update(['value' => $getThemeData->heading_color]);
        Config::where('param', 'sub_heading_text_color')->update(['value' => $getThemeData->sub_heading_text_color]);
        Config::where('param', 'paragraph_text_color')->update(['value' => $getThemeData->paragraph_text_color]);

        // Check if the active theme setting was updated successfully
        if ($setActiveTheme) {
            $msg = 'Appearance "' . $getThemeData->theme_name . '" applied successfully';
            createLog('global_action', $msg);

            return $this->sendResponse($getThemeData, 'Theme activated successfully!', 200);
        } else {
            return $this->sendError(null, 'An error occurred! Please try again later!', 500);
        }
    }


    public function deleteTheme(Request $request)
    {
        $id = $request->theme_id;
        $theme = ThemeSetting::where('id', $id)->first();
        $theme->update(['is_delete' => '1']);

        if ($theme) {
            return response()->json([
                'status' => 1,
                'message' => 'Record Deleted Successfully',
                'data' => [],
            ], 200);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Record not found',
                'data' => [],
            ], 404);
        }
    }

    public function updatetheme(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tab_name' => 'required',
            'favicon' => 'image|mimes:png,ico,svg|max:2048',
            'applogo' => 'image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse([], $validator->errors(), 400);
        }

        $fields = [
            'favicon' => 'App_Favicon',
            'applogo' => 'App_Logo',
        ];

        foreach ($fields as $field => $perimeter) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $value = $request->file($field);
                $setting = DB::table('settings')->where('perimeter', $perimeter)->first();

                if ($setting && $setting->value) {
                    $file_old = public_path('assets/images/theme/' . $setting->value);
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }

                $filename = time() . '_' . $value->getClientOriginalName();
                $value->move(public_path('assets/images/theme/'), $filename);
                DB::table('settings')->where('perimeter', $perimeter)->update(['value' => $filename]);
            }
        }

        $updateFields = [
            'tab_name' => 'App_Name'
        ];

        foreach ($updateFields as $field => $perimeter) {
            $value = $request->input($field);
            $updateTheme = DB::table('settings')->where('perimeter', $perimeter)->update(['value' => $value]);
        }

        $msg = 'Theme updated successfully';
        createLog('global_action', $msg);

        return $this->sendResponse([], 'Theme updated successfully!', 200);

    }
    public function getThemeSetting()
    {
        $query = DB::table('settings')
            ->whereIn('perimeter', ['App_Favicon', 'App_Logo', 'App_Name'])
            ->get()
            ->keyBy('perimeter');

        $filePaths = [];

        foreach ($query as $key => $setting) {
            $filePaths[$key] = url('api/public/assets/images/theme/' . $setting->value);
        }

        $result = (object)[
            'App_Favicon' => $filePaths['App_Favicon'] ?? null,
            'App_Logo' => $filePaths['App_Logo'] ?? null,
            'App_Name' => $query['App_Name']->value,
        ];

        return response()->json($result);
    }

    public function editApperanceTheme(Request $request)
    {
        $themeId = $request->id;

        $themeDetails = ThemeSetting::select('theme_name', 'navbar_heading_color', 'navbar_background_color', 'body_background_color', 'primary_color', 'sidebar_background_color', 'sidebar_text_color', 'heading_color', 'sub_heading_text_color', 'paragraph_text_color', 'id')
                                    ->find($themeId);

        if($themeDetails){
            return response()->json([
                'status' => 1,
                'message' => 'Record fetch successfully',
                'data' => $themeDetails,
            ], 200);

        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Record Not Found',
                'data' => [],
            ], 404);
        }
    }
    public function activeTheme(Request $request)
    {
        $activeThemeId = DB::table('settings')->where('perimeter', 'Active_Theme_Id')->value('value');

        $query = DB::table('theme_setting')->where('id', $activeThemeId)->where('is_delete', '0')->first();

        if($query){
            return response()->json([
                'status' => 1,
                'message' => 'Apprerance active fetch successfully',
                'data' => $query,
            ], 200);

        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Apprerance active record Not Found',
                'data' => [],
            ], 200);
        }
    }
}



    // Config::where('param', 'heading_color')->update(['value' => $request->heading_color]);
    // Config::where('param', 'text_color')->update(['value' => $request->text_color]);
    // Config::where('param', 'sidebar_color')->update(['value' => $addTheme->sidebar_color]);
    // Config::where('param', 'sidebar_background_color')->update(['value' => $request->sidebar_background_color]);
    // Config::where('param', 'body_background_color')->update(['value' => $request->body_background_color]);
    // Config::where('param', 'header_background_color')->update(['value' => $request->header_background_color]);
    // Config::where('param', 'sidebar_hover')->update(['value' => $request->sidebar_hover]);
    // Config::where('param', 'button_background_color')->update(['value' => $request->button_background_color]);
    // Config::where('param', 'button_text_color')->update(['value' => $request->button_text_color]);
    // Config::where('param', 'btn_border_color')->update(['value' => $request->btn_border_color]);
    // Config::where('param', 'pagination_active_bg')->update(['value' => $request->pagination_active_bg]);
    // Config::where('param', 'pagination_active_color')->update(['value' => $request->pagination_active_color]);
    // Config::where('param', 'tabs_color')->update(['value' => $request->tabs_color]);
    // Config::where('param', 'tabs_active_color')->update(['value' => $request->tabs_active_color]);
    // Config::where('param', 'tabs_active_background_color')->update(['value' => $request->tabs_active_background_color]);
    // Config::where('param', 'icon_color')->update(['value' => $request->icon_color]);

    // Assigning values to $addTheme object
    // $addTheme->theme_name = $request->theme_name;
    // $addTheme->heading_color = $request->heading_color;
    // $addTheme->text_color = $request->text_color;
    // $addTheme->sidebar_color = $request->sidebar_color;
    // $addTheme->sidebar_background_color = $request->sidebar_background_color;
    // $addTheme->body_background_color = $request->body_background_color;
    // $addTheme->header_background_color = $request->header_background_color;
    // $addTheme->sidebar_hover = $request->sidebar_hover;
    // $addTheme->button_background_color = $request->button_background_color;
    // $addTheme->button_text_color = $request->button_text_color;
    // $addTheme->btn_border_color = $request->btn_border_color;
    // $addTheme->pagination_active_bg = $request->pagination_active_bg;
    // $addTheme->pagination_active_color = $request->pagination_active_color;
    // $addTheme->tabs_color = $request->tabs_color;
    // $addTheme->tabs_active_color = $request->tabs_active_color;
    // $addTheme->tabs_active_background_color = $request->tabs_active_background_color;
    // $addTheme->icon_color = $request->icon_color;

    // 'theme_name' => 'required',
    // 'heading_color' => 'required',
    // 'text_color' => 'required',
    // 'sidebar_color' => 'required',
    // 'sidebar_background_color' => 'required',
    // 'body_background_color' => 'required',
    // 'header_background_color' => 'required',
    // 'sidebar_hover' => 'required',
    // 'button_background_color' => 'required',
    // 'button_text_color' => 'required',
    // 'btn_border_color' => 'required',
    // 'pagination_active_bg' => 'required',
    // 'pagination_active_color' => 'required',
    // 'tabs_color' => 'required',
    // 'tabs_active_color' => 'required',
    // 'tabs_active_background_color' => 'required',
    // 'icon_color' => 'required',
