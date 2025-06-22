<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SmtpController extends BaseController
{
    public function smtp()
    {
        $data = [];
        $user = auth()->user();
        $settings = Setting::all();
        foreach ($settings as $key => $setting) {
            if ($setting['perimeter'] == 'smtp_from_email') {
                $data['smtp_from_email'] = $setting['value'];
            }
            if ($setting['perimeter'] == 'smtp_from_name') {
                $data['smtp_from_name'] = $setting['value'];
            }
            if ($setting['perimeter'] == 'smtp_encryption') {
                $data['smtp_encryption'] = $setting['value'];
            }
            if ($setting['perimeter'] == 'smtp_email') {
                $data['smtp_email'] = $setting['value'];
            }
            if ($setting['perimeter'] == 'smtp_host') {
                $data['smtp_host'] = $setting['value'];
            }
            if ($setting['perimeter'] == 'smtp_password') {
                $data['smtp_password'] = $setting['value'];
            }
            if ($setting['perimeter'] == 'smtp_port') {
                $data['smtp_port'] = $setting['value'];
            }
        }
        if ($data) {
            return $this->sendResponse($data, 'SMPTs fetched successfully!', 200);
        } else {
            return $this->sendResponse($data, 'Data not found!', 200);
        }
    }

    // public function updateSMTP(Request $request)
    // {
    //     $password = DB::table('settings')->where('perimeter', 'smtp_password')->first();
    //     if ($password->value == '' || $password->value != $request->password) {
    //         $updateFields = [
    //             'email' => 'smtp_from_email',
    //             'from_name' => 'smtp_from_name',
    //             'encryption' => 'smtp_encryption',
    //             'username' => 'smtp_email',
    //             'smtphost' => 'smtp_host',
    //             'password' => 'smtp_password',
    //             'port' => 'smtp_port',
    //         ];
    //     } else {
    //         $updateFields = [
    //             'email' => 'smtp_from_email',
    //             'from_name' => 'smtp_from_name',
    //             'encryption' => 'smtp_encryption',
    //             'username' => 'smtp_email',
    //             'smtphost' => 'smtp_host',
    //             'port' => 'smtp_port',
    //         ];
    //     }

    //     foreach ($updateFields as $field => $perimeter) {
    //         $value = $field;

    //         DB::table('settings')->where('perimeter', $perimeter)->update(['value' => $value]);
    //     }

    //     Session::flash('success', 'SMTP Updated Successfully');
    //     Session::flash('alert-class', 'alert-success');

    //     $msg = 'SMTP Updated';
    //     createLog('global_action', $msg);
    //     return $this->sendResponse([], 'SMTPs update successfully!', 200);
    // }

    public function updateSMTP(Request $request)
    {
        $passwordSetting = Setting::where('perimeter', 'smtp_password')->first();

        $updateFields = [
            'smtp_from_email' => 'smtp_from_email',
            'smtp_from_name' => 'smtp_from_name',
            'smtp_encryption' => 'smtp_encryption',
            'smtp_email' => 'smtp_email',
            'smtp_host' => 'smtp_host',
            'smtp_port' => 'smtp_port',
        ];

        // If password needs to be updated, include it
        if ($passwordSetting->value == '' || $passwordSetting->value != $request->smtp_password) {
            $updateFields['smtp_password'] = 'smtp_password';
        }

        foreach ($updateFields as $requestField => $dbField) {
            $value = $request->input($requestField);

            Setting::where('perimeter', $dbField)->update(['value' => $value]);
        }

        Session::flash('success', 'SMTP Updated Successfully');
        Session::flash('alert-class', 'alert-success');

        $msg = 'SMTP Updated';
        createLog('global_action', $msg);

        return $this->sendResponse([], 'SMTPs updated successfully!', 200);
    }

}
