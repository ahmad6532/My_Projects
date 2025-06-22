<?php

namespace App\Http\Controllers;

use App\Models\be_spoke_form_record_drafts;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {

        View::composer('layouts.user.sidebar-header', function ($view) {
        
            $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
            $drafts = be_spoke_form_record_drafts::where('user_id',$user->id)->get();
            $shared_cases = $user->share_cases()->whereNull('removed_by_user')->get();
            $case_request_informations = $user->case_request_informations;
            $view->with(
                ['case_request_informations'=>$case_request_informations,
                'shared_cases' => $shared_cases, 'drafts' => $drafts,
                ]
            );
        });

    }
}
