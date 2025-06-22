<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationBrandUpdateRequestsFormRequest;
use App\Models\LocationBrandUpdateRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class LocationBrandUpdateRequestsController extends Controller
{

    /**
     * Display a listing of the location brand update requests.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $locationBrandUpdateRequests = LocationBrandUpdateRequest::with('user')->paginate(25);

        return view('location_brand_update_requests.index', compact('locationBrandUpdateRequests'));
    }

    /**
     * Show the form for creating a new location brand update request.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('location_brand_update_requests.create');
    }

    /**
     * Store a new location brand update request in the storage.
     *
     * @param App\Http\Requests\LocationBrandUpdateRequestsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update_location_branding(LocationBrandUpdateRequestsFormRequest $request)
    {
        try {
            $data = $request->getData();

            $location = Auth::guard('location')->user();
            $user = Auth::guard('web')->user();

            $data['location_id'] = $location->id;
            $data['user_id'] = $user->id;

            if($request->preview_btn=='preview')
            {
                session(['bg_color_code'=> $data['bg_color_code']]);
                session(['font'=> $data['font']]);
                if($request->hasFile('logo_file'))
                {
                    $request->file('logo_file')->move(public_path('data_images/location_brand_request_files/temp/logo'), $location->id.'.png');
                }
                if($request->hasFile('bg_file'))
                {
                    $request->file('bg_file')->move(public_path('data_images/location_brand_request_files/temp/bg'), $location->id .'.png');
                }

//                dd(Session::get('bg_color_code'),Session::get('font'));
                return view('location.location_preview',compact('location'));

            }


            $vals = [];
            foreach($data as $dk => $dv)
            {
                $vals[$dk] = $dv;
            }
            $data['token'] = Str::random(64);
            
            $lb = LocationBrandUpdateRequest::create($data);
            if($request->hasFile('logo_file'))
            {
                $request->file('logo_file')->move(public_path('data_images/location_brand_request_files/logo'), $lb->id.'.png');
            }
            if($request->hasFile('bg_file'))
            {
                $request->file('bg_file')->move(public_path('data_images/location_brand_request_files/bg'), $lb->id .'.png');
            }

            $link = route('location.confirm_location_branding', $data['token']);

            // Time to send email to registered account email //
            Mail::send('emails.location_update', ['user' => $user, 
            'location' =>  $location,
            'vals' => $vals,
            'link' => $link,
            ]
            , function($message) use($location){
                $message->to($location->email);
                $message->subject(env('APP_NAME') . ' - Update Requested');
            });
            

            return redirect()->route('location.edit_location_branding')
                ->with('success_message', 'Details Update Request successfully submitted. Changes will be applied after approval.');

            return redirect()->route('location.color_branding')
                ->with('success_message', 'Location Brand Update Request submitted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    public function confirm_location_branding($token)
    {
        $update_request = LocationBrandUpdateRequest::where('token', $token)->first();
        if(!$update_request)
            return abort(403); // Todo:further throtle options
        if($update_request->status == 1)
            return "Request was already approved";
        
        $location = Auth::guard('location')->user();
        $location->bg_color_code = $update_request->bg_color_code;
        $location->font = $update_request->font;


        // pick or remove files //


        $ibp=public_path('data_images/location_branding');
        if(!file_exists($ibp))
        {
            mkdir($ibp);
            mkdir($ibp.'/logo');
            mkdir($ibp.'/bg');
        }
        $bgfp=public_path('data_images/location_brand_request_files/bg/') .'/'.$update_request->id.'.png';
        $lfp=public_path('data_images/location_brand_request_files/logo').'/'. $update_request->id.'.png';

        if(file_exists($lfp))
        {
            copy($lfp,$ibp.'/logo/'.$location->id.'.png');
        }

        if(file_exists($bgfp))
        {
            copy($bgfp,$ibp.'/bg/'.$location->id.'.png');
        }
        else // if no new bg file is there. means remove bg file.
        {
            if(\file_exists($ibp.'/bg/'.$location->id.'.png'))
                \unlink($ibp.'/bg/'.$location->id.'.png');
        }

        $location->save();

        $update_request->status = 1;
        $update_request->save();
        
        return "Request approved";
    }

    /**
     * Display the specified location brand update request.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $locationBrandUpdateRequest = LocationBrandUpdateRequest::with('user')->findOrFail($id);

        return view('location_brand_update_requests.show', compact('locationBrandUpdateRequest'));
    }

    /**
     * Show the form for editing the specified location brand update request.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $locationBrandUpdateRequest = LocationBrandUpdateRequest::findOrFail($id);
        

        return view('location_brand_update_requests.edit', compact('locationBrandUpdateRequest'));
    }

    /**
     * Update the specified location brand update request in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\LocationBrandUpdateRequestsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, LocationBrandUpdateRequestsFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            $locationBrandUpdateRequest = LocationBrandUpdateRequest::findOrFail($id);
            $locationBrandUpdateRequest->update($data);

            return redirect()->route('location_brand_update_requests.location_brand_update_request.index')
                ->with('success_message', 'Location Brand Update Request was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified location brand update request from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $locationBrandUpdateRequest = LocationBrandUpdateRequest::findOrFail($id);
            $locationBrandUpdateRequest->delete();

            return redirect()->route('location_brand_update_requests.location_brand_update_request.index')
                ->with('success_message', 'Location Brand Update Request was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }



}
