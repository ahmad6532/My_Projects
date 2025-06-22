<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DefaultCaseStageTask;
use App\Models\DMD;
use App\Models\dmd_amp;
use App\Models\dmd_vmp;
use App\Models\dmd_vtm;
use App\Models\Forms\Form;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use App\Models\Headoffices\Organisation\LocationGroup;
use App\Models\LfpseOption;
use Cache;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RateLimiter;

class LfpseDataController extends Controller
{
    //
    public function get_options(Request $request, $collection, $version)
    {
        $q = $request->q;
        $result = LfpseOption::where('collection_name', $collection)->where('version', $version);
        if($q){
            $result = $result->where('val', 'LIKE', "%" . $q . "%");
        }else{
            $result = $result->take(10);
        }
        $result = $result->orderBy('val', 'asc');
        if($collection === 'ods_codes'){
            $result = $result->where('val', 'LIKE', "%" . $q . "%")->orWhere('code', 'LIKE', "%" . $q . "%");
            $result = $result->selectRaw("id, CONCAT(val, ' (', code, ')') as text, code")->take(20)->get();
        }else{
            $result = $result->select(['val as text', 'id'])->take(10)->get();
        }
        $formatted = [
            "results"=> $result,
            "pagination"=> [
              "more"=> false
            ]
        ];
        return $formatted;
    }

    public function get_dmd_options(Request $request){
        $q = $request->q;
        
        $results = DMD::where('description', 'LIKE', "%{$q}%")
                    ->select(['description as text', 'id'])
                    ->get();

        $formatted = [
            "results" => $results,
            "pagination" => [
                "more" => false
            ]
        ];
        return $formatted;
    }
    public function get_dmd_options_vtm(Request $request){
        $q = $request->q;
        
        $results = dmd_vtm::where('NM', 'LIKE', "%{$q}%")
                    ->select(['NM as text', 'VTMID'])
                    ->take(10)
                    ->get();

        $formatted = [
            "results" => $results,
            "pagination" => [
                "more" => false
            ]
        ];
        return $formatted;
    }
    public function get_dmd_options_vmp(Request $request){
        $q = $request->q;
        
        if(isset($q)){
            $results = dmd_vmp::where('VTMID',$q)
                        ->select(['NM as text', 'VPID'])
                        ->get();
        }else{
            $results = null;
        }

        $formatted = [
            "results" => $results,
            "pagination" => [
                "more" => false
            ]
        ];
        return $formatted;
    }

    public function get_dmd_options_vmp_new(Request $request) {
        $q = $request->q;
    
        if (isset($q)) {
            // Split the query into words
            $words = explode(' ', $q);
            
            // Build query logic to match all words
            $results = dmd_amp::where(function($query) use ($words) {
                foreach ($words as $word) {
                    $query->where('NM', 'LIKE', "%{$word}%");
                }
            })
            ->orderByRaw("LOCATE(?, NM) ASC", [$q]) // Prioritize exact phrase matches
            ->select(['NM as text', 'id'])
            ->take(10)
            ->get();
        } else {
            $results = [];
        }
    
        $formatted = [
            "results" => $results,
            "pagination" => [
                "more" => false
            ]
        ];
        return $formatted;
    }
    

    public function location_groups($id) {
        $form = Form::find($id);
        if(!isset($form)){
            $form = DefaultCaseStageTask::find($id);
            if(isset($form)){
                $form = $form->stage->form;
            }
        }
        $headOffice = $form->form_owner;
        $groups = $headOffice->head_office_organisation_groups;
    
        // Helper function to add levels and check if a group has children
        function addLevels($groups, $parentId = null, $level = 0) {
            $result = [];
            foreach ($groups as $group) {
                if ($group->parent_id === $parentId) {
                    // Check if the group has children
                    $hasChildren = $groups->where('parent_id', $group->id)->count() > 0;
                    
                    $formattedGroup = [
                        'id' => $group->id,
                        'group' => $group->group,
                        'parent_id' => $group->parent_id,
                        'level' => $level,
                        'has_children' => $hasChildren
                    ];
                    // Recursively add children with increased level
                    $children = addLevels($groups, $group->id, $level + 1);
                    $result[] = $formattedGroup;
                    $result = array_merge($result, $children);
                }
            }
            return $result;
        }
    
        // Call the helper function to format the groups with levels and child status
        $formatted = addLevels($groups);
    
        return $formatted;
    }
    

    public function all_locations($id, Request $request) {
        $search = explode(',', $request->search);
        $q = $request->q;
    
        $form = Form::find($id);
        if(!isset($form)){
            $form = DefaultCaseStageTask::find($id);
            if(isset($form)){
                $form = $form->stage->form;
            }
        }
        $headOffice = $form->form_owner;
        
        $ho_locations = $headOffice->locations()->with(['groups.group.parent'])->get();
    
        $filteredLocations = [];
    
        foreach ($ho_locations as $loc) {
            $locationGroups = $loc->groups->pluck('group_id')->toArray();
    
            $hasMatchingGroup = count(array_intersect($search, $locationGroups)) > 0;
            
            $location = $loc->location;
            $matchesQuery = !$q || stripos($location->trading_name, $q) !== false || stripos($location->full_address, $q) !== false;
    
            if (!$hasMatchingGroup && $matchesQuery) {
                $group_hierarchies = $loc->groups->flatMap(function($loc_group) {
                    $hierarchy = [$loc_group->group_id];
                    $parent = $loc_group->group->parent;
    
                    while ($parent) {
                        $hierarchy[] = $parent->id;
                        $parent = $parent->parent;
                    }
    
                    return $hierarchy;
                })->toArray();
                $group_hierarchies = array_unique($group_hierarchies);
                $tags = $loc->location->location_tag->pluck('id')->toArray();
                $filteredLocations[] = [
                    'id' => $location->id,
                    'location_id' => $location->location_code,
                    'name' => $location->trading_name,
                    'address' => $location->full_address,
                    'groups_involved' => $group_hierarchies,
                    'tags' => $tags
                ];
            }
        }
    
        return [
            "results" => $filteredLocations
        ];
    }
    
    

    public function check_submission($email){
        $headOfficeCases = HeadOfficeCase::where('location_email',$email)->get();
        return response()->json($headOfficeCases->count() > 0 ? true : false,200);
    }


    public function get_ods_codes(Request $request, $code)
    {
        $maxAttempts = 10; // Maximum number of attempts within the time window
        $decayMinutes = 1; // Time window in minutes for rate-limiting

        $clientIp = $request->ip();
        $rateLimiterKey = 'get_ods_codes_' . $clientIp;

        if (RateLimiter::tooManyAttempts($rateLimiterKey, $maxAttempts)) {
            return response()->json([
                'error' => 'Too many requests. Please try again later.'
            ], 429);
        }

        RateLimiter::hit($rateLimiterKey, $decayMinutes * 60);

        $url = "https://directory.spineservices.nhs.uk/ORD/2-0-0/organisations?PostCode=" . urlencode($code);

        try {
            $response = Http::timeout(10) // Set a timeout for the request
                            ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                $cacheKey = 'ods_codes_' . $code;
                Cache::put($cacheKey, $data, now()->addMinutes(10));

                return response()->json($data);
            } else {
                return response()->json([
                    'error' => 'Failed to fetch data from the external service.',
                    'status' => $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching the data.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function get_ods_details(Request $request, $code)
    {
        $maxAttempts = 10; // Maximum number of attempts within the time window
        $decayMinutes = 1; // Time window in minutes for rate-limiting

        $clientIp = $request->ip();
        $rateLimiterKey = 'get_ods_codes_' . $clientIp;

        if (RateLimiter::tooManyAttempts($rateLimiterKey, $maxAttempts)) {
            return response()->json([
                'error' => 'Too many requests. Please try again later.'
            ], 429);
        }

        RateLimiter::hit($rateLimiterKey, $decayMinutes * 60);

        $url = "https://directory.spineservices.nhs.uk/ORD/2-0-0/organisations/" . urlencode($code);

        try {
            $response = Http::timeout(10) // Set a timeout for the request
                            ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                $cacheKey = 'ods_detials_' . $code;
                Cache::put($cacheKey, $data, now()->addMinutes(10));

                return response()->json($data);
            } else {
                return response()->json([
                    'error' => 'Failed to fetch data from the external service.',
                    'status' => $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching the data.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function ods_check(Request $request,$code)
    {

        $maxAttempts = 10; // Maximum number of attempts within the time window
        $decayMinutes = 1; // Time window in minutes for rate-limiting

        $clientIp = $request->ip();
        $rateLimiterKey = 'get_ods_codes_' . $clientIp;

        if (RateLimiter::tooManyAttempts($rateLimiterKey, $maxAttempts)) {
            return response()->json([
                'error' => 'Too many requests. Please try again later.'
            ], 429);
        }

        RateLimiter::hit($rateLimiterKey, $decayMinutes * 60);

        $url = "https://directory.spineservices.nhs.uk/ORD/2-0-0/organisations/" . urlencode($code);

        try {
            $response = Http::timeout(10) // Set a timeout for the request
                            ->get($url);

            

            if ($response->successful()) {
                $data = $response->json();

                $cacheKey = 'ods_detials_' . $code;
                Cache::put($cacheKey, $data, now()->addMinutes(10));

                
                return response()->json(
                    ['exists' => true,'val' => $code],
                    $response->status());
            } else {
                return response()->json(
                    ['exists' => false],
                    $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching the data.',
                'message' => $e->getMessage()
            ], 500);
        }
        
        
    }
}
