<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class AdminsController extends Controller
{

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function edit_my_details()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.edit_my_details', compact('admin'));
    }

    public function update_password()
    {
        return view('admin.update_password');
    }

    public function index()
    {
        $admins = Admin::paginate(25);

        return view('admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('admins.create');
    }

    /**
     * Store a new admin in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            Admin::create($data);

            return redirect()->route('admins.admin.index')
                ->with('success_message', 'Admin was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified admin.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $admin = Admin::findOrFail($id);

        return view('admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        

        return view('admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $admin = Admin::findOrFail($id);
            $admin->update($data);

            return redirect()->route('admins.admin.index')
                ->with('success_message', 'Admin was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified admin from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $admin->delete();

            return redirect()->route('admins.admin.index')
                ->with('success_message', 'Admin was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
                'first_name' => 'required|string|min:1|max:50',
            'surname' => 'required|string|min:1|max:50',
            'mobile_no' => 'nullable|max:20|string|min:0',
            'email' => 'required|email|min:3|max:150|unique:admins,email',
            'password' => 'required|min:8|max:70',
            'is_active' => 'boolean|nullable', 
        ];

        
        $data = $request->validate($rules);


        $data['is_active'] = $request->has('is_active');


        return $data;
    }

}
