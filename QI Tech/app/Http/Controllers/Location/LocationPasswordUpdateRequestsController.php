<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationPasswordUpdateRequestsFormRequest;
use App\Models\LocationPasswordUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LocationPasswordUpdateRequestsController extends Controller
{

    /**
     * Display a listing of the location password update requests.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $locationPasswordUpdateRequests = LocationPasswordUpdateRequest::with('user')->paginate(25);

        return view('location_password_update_requests.index', compact('locationPasswordUpdateRequests'));
    }

    /**
     * Show the form for creating a new location password update request.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $users = User::pluck('is_registered','id')->all();
        
        return view('location_password_update_requests.create', compact('users'));
    }

    /**
     * Store a new location password update request in the storage.
     *
     * @param App\Http\Requests\LocationPasswordUpdateRequestsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update_password(LocationPasswordUpdateRequestsFormRequest $request)
    {
        try {
            
            $data = $request->getData();

            $user = Auth::guard('user')->user();
            $location = Auth::guard('location')->user();

            if(!Hash::check($request->old_password, $location->password))
            {
                $tries = Session::has('tries') ? session('tries') + 1 : 1;
                session('tries', $tries);
                //Todo: block if tries are greater than a specific number //
                return back()->withInput()
                ->withErrors(['old_password' => 'Old Password is invalid.']);
            }

            $data['user_id'] = $user->id;
            $data['location_id'] = $location->id;
            
            $data['token'] = Str::random(64);
            
            LocationPasswordUpdateRequest::create($data);
            $link = route('location.confirm_location_password', $data['token']);

            $vals = [
                "New Password" => $request->new_password
            ];
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

            return redirect()->route('location.update_password_view')
                ->with('success_message', 'Password Update Request successfully submitted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.' . $exception->getMessage()]);
        }
    }

    public function confirm_location_password($token)
    {
        $update_request = LocationPasswordUpdateRequest::where('token', $token)->first();
        if(!$update_request)
            return abort(403); // Todo:further throtle options
        if($update_request->status == 1)
            return "Request was already approved";
        
        $location = Auth::guard('location')->user();
        $location->password = $update_request->new_password;

        $location->save();

        $update_request->status = 1;
        $update_request->save();
        
        return "Request approved. Password updated successfully.";
    }

    /**
     * Display the specified location password update request.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $locationPasswordUpdateRequest = LocationPasswordUpdateRequest::with('user')->findOrFail($id);

        return view('location_password_update_requests.show', compact('locationPasswordUpdateRequest'));
    }

    /**
     * Show the form for editing the specified location password update request.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $locationPasswordUpdateRequest = LocationPasswordUpdateRequest::findOrFail($id);
        $users = User::pluck('is_registered','id')->all();

        return view('location_password_update_requests.edit', compact('locationPasswordUpdateRequest','users'));
    }

    /**
     * Update the specified location password update request in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\LocationPasswordUpdateRequestsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, LocationPasswordUpdateRequestsFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            $locationPasswordUpdateRequest = LocationPasswordUpdateRequest::findOrFail($id);
            $locationPasswordUpdateRequest->update($data);

            return redirect()->route('location_password_update_requests.location_password_update_request.index')
                ->with('success_message', 'Location Password Update Request was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified location password update request from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $locationPasswordUpdateRequest = LocationPasswordUpdateRequest::findOrFail($id);
            $locationPasswordUpdateRequest->delete();

            return redirect()->route('location_password_update_requests.location_password_update_request.index')
                ->with('success_message', 'Location Password Update Request was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }



}
