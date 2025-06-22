<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class ActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $head_office = Auth::guard('web')->user();
        if(!isset($head_office)){
            return $next($request);
        }
        $this->logActivity($request);
        return $next($request);
    }


    private function logActivity($request)
    {
        $userId = Auth::id();
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if(!isset($userId)){
            $userId = null;
        }
        

        $action = $this->getAction($request);
        if($action !== "" ){
            $existingLog = ActivityLog::where('user_id', $userId)
            ->where('action', $action)
            ->where('timestamp', '>=', now()->subSeconds(60)) 
            ->exists();
            if (!$existingLog) {
                if(isset($head_office)){
                    ActivityLog::create([
                        'user_id' => $userId,
                        'head_office_id' =>$head_office->id,
                        'action' => $action,
                        'timestamp' => now(),
                    ]);
                }else{
                    ActivityLog::create([
                        'user_id' => $userId,
                        'action' => $action,
                        'timestamp' => now(),
                    ]);
                }
            }
        }
    }

    private function getAction($request){
        $path = $request->path();
        $method = $request->method();
        $action = '';
        
        switch ($path) {
            case 'head_office/case/manager':
                if ($method === 'GET') {
                    $action = 'User accessed case manager';
                }
                break;
            case Str::startsWith($path, 'head_office/case/manager/view/'):
                if ($method === 'GET') {
                    $caseId = $request->route('id');
                    $action = "User viewed case ID: $caseId";
                }
                break;
            case Str::startsWith($path, 'head_office/case/manager/request_information/'):
                if ($method === 'GET') {
                    $caseId = $request->route('id');
                    $action = "User sent an Information Request for case ID: $caseId";
                }
                break;
            case 'head_office/case/manager/view/comment/save':
                if($method === 'POST'){
                    $action = 'User made a comment in case log';
                }
                break;
            default:
                break;
        }
        return $action;
    }
}
