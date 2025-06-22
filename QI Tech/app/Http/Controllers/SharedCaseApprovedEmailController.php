<?php

namespace App\Http\Controllers;

use App\Models\SharedCaseApprovedEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SharedCaseApprovedEmailController extends Controller
{
    public function store(Request $request,$id)
    {
        $request->validate([
            'shared_case_approved_email' => 'required',
        ]);

        $share_case_approved_email = $request->has('shared_case_approved_email_id') ? SharedCaseApprovedEmail::findOrFail($request->shared_case_approved_email_id) : new SharedCaseApprovedEmail() ; 
        
        $user = Auth::guard('web')->user()->selected_head_office;
        $form = $user->be_spoke_forms()->find($id);
        if($form)
        {
            
            $share_case_approved_email->email = $request->shared_case_approved_email;
            $share_case_approved_email->description = $request->description;
            $share_case_approved_email->be_spoke_form_id = $id;
            $share_case_approved_email->save();
            return back()->with('success_message','Email assigned');
        }
        return back()->with('error','Form not found');
    }
    public function delete($id)
    {
        $user = Auth::guard('web')->user()->selected_head_office;
        $forms = $user->be_spoke_forms;
        foreach($forms as $form)
        {
            $email = $form->shared_case_approved_emails()->find($id);
            if($email)
            {
                $email->delete();
                return back()->with('success_message','Email deleted');
            }
        }
        return back()->with('error','Email not found');

    }
}