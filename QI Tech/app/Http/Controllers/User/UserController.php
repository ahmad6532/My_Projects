<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Http\base64Convert;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\be_spoke_form_record_drafts;
use App\Models\CaseRequestInformationDocument;
use App\Models\CaseRequestInformationQuestion;
use App\Models\Document;
use App\Models\Headoffices\CaseManager\Comment;
use App\Models\Headoffices\CaseManager\CommentDocument;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use App\Models\share_case_communications_views;
use App\Models\ShareCaseCommunication;
use App\Models\ShareCaseCommunicationDocument;
use App\Models\ShareCaseDocument;
use App\Models\ShareCaseLog;
use App\Models\ShareCaseRequestExtension;
use App\Models\User;
use App\Models\UserContactDetail;
use App\Models\UserName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Validator;

class UserController extends Controller
{
    //
    public function view_profile(Request $request)
    {
        if($request->query('from_company')){
            session(['from_company' => true]);
        }
        // Send users info here //
        $user = Auth::guard('user')->user();
        $otp = $user->otp;
        return view('user.view_profile', compact('user', 'otp'));
    }

    //
    public function requests()
    {
        $user = Auth::guard('user')->user();
        return view('user.user_requests', compact('user'));
    } //
    public function statement()
    {
        $user = Auth::guard('user')->user();
        $case_request_informations = $user->case_request_informations;
        return view('user.user_statement', compact('user', 'case_request_informations'));
    }
    //
    public function activity()
    {
        return view('user.user_activity');
    }
    //

    public function update_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required|max:80',
            'new_password' => 'required|min:8|max:80',
            'confirm_password' => 'same:new_password',
        ]);

        $user = Auth::guard('user')->user();

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = hash::make($request->new_password);
            $user->password_updated_at = Carbon::now();
            $user->save();
            return redirect()->back()->with('success_message', 'Password Updated Successfully!');
        }
        return back()
            ->withInput()
            ->withErrors(['old_password' => 'Entered password is incorrect']);
    }
    public function single_statement($id)
    {
        $user = Auth::guard('user')->user();
        $case_request_information = $user->case_request_informations()->find($id);
        if ($case_request_information) {
            return view('user.single_statement', compact('case_request_information'));
        }
        return back()->with('error', 'Rerquest not found');
    }
    public function single_statement_update(Request $request, $id, $type)
    {
        $user = Auth::guard('user')->user();
        $case_request_information = $user->case_request_informations()->find($id);
        if ($case_request_information && $case_request_information->status == 0) {
            $activity_log = new ActivityLog();
            $activity_log->type = 'Information Request';
            $activity_log->user_id = $user->id;
            $activity_log->head_office_id = $case_request_information->case->head_office_id;
            $activity_log->action = 'Information received from ' . $user->first_name . ' ' . $user->surname . ' ' . '(' . $user->email . ')';

            if (!$type) {
                $text = '';
                foreach ($case_request_information->questions as $question) {
                    $answer = 'answer_' . $question->id;
                    $question->answer = $request->$answer;
                    $text .= '<b>' . $question->question . '</b><br><p>' . $request->$answer . '</p><br>';
                    $question->save();
                }
                $case_request_information->status = 1;
                $case_request_information->note = $request->note;
                $case_request_information->save();

                $comment = new Comment();
                $comment->case_id = $case_request_information->case->id;
                $comment->user_id = $user->id;
                $comment->comment = "Requested information received from $user->first_name $user->surname";
                $comment->save();

                $comment = new Comment();
                $comment->case_id = $case_request_information->case->id;
                $comment->user_id = $user->id;
                $comment->comment = $text;
                $comment->save();

                $activity_log->comment_id = $comment->id;
                $activity_log->save();

                $documents = (array) $request->documents;
                CommentDocument::where('comment_id', $comment->id)->delete();

                foreach ($documents as $value) {
                    $doc = new CommentDocument();
                    $doc->comment_id = $comment->id;
                    $value = Document::where('unique_id', $value)->first();
                    if (!$value) {
                        continue;
                    }
                    $doc->document_id = $value->id;
                    $doc->type = $value->isImage() ? 'image' : 'document';
                    $doc->save();
                    $CaseRequestInformationDocument = new CaseRequestInformationDocument();
                    $CaseRequestInformationDocument->case_request_information_id = $case_request_information->id;
                    $CaseRequestInformationDocument->comment_document_id = $doc->id;
                    $CaseRequestInformationDocument->save();
                }
                return redirect()->route('user.view_profile')->with('Request Submitted successfully');
            } else {
                if ($request->confirm_note != $request->note) {
                    return back()->with('error', 'Number and comfirm number do not match');
                }
                $case_request_information->note = 'User will submit data over phone and phone number is ' . $request->note;
                $case_request_information->status = 1;
                $case_request_information->save();

                $comment = new Comment();
                $comment->case_id = $case_request_information->case->id;
                $comment->user_id = $user->id;
                $comment->comment = "Requested information received from $user->first_name $user->surname " . '<br>User will submit data over phone and phone number is ' . $request->note;
                $comment->save();

                $activity_log->comment_id = $comment->id;
                $activity_log->save();
            }
        }
        return redirect()->route('user.statement')->with('error', 'Statement already updated');
    }
    public function shared_cases()
    {
        $user = Auth::guard('user')->user();
        $share_cases = $user->share_cases->where('removed_by_user', 0);
        return view('user.user_shared_cases', compact('user', 'share_cases'));
    }
    public function shared_case($id)
    {
        $user = Auth::guard('user')->user();
        $shared_case = $user->share_cases->where('is_deleted', 0)->find($id);
        if ($shared_case->is_revoked) {
            return back()->with('error', 'Your access has revoked');
        }
        if($shared_case->duration_of_access < Carbon::now()){
            return back()->with('error', 'Your access has expired');
        }
        if ($shared_case) {
            return view('user.shared_case', compact('shared_case'));
        }
        return back()->with('error', 'Shared case not found');
    }
    public function change_password(Request $request)
    {
        $user = Auth::guard('user')->user();
        $db_user = User::find($user->id);
        if (isset($request->current_password) && isset($request->new_password)) {
            if ($request->current_password == $request->new_password) {
                return back()->with('error', 'Old and new password can not be same');
            }
            if (Hash::check($request->current_password, $db_user->password)) {
                $db_user->password = hash::make($request->new_password);
                $db_user->password_updated_at = Carbon::now();
                $db_user->save();
                return redirect()->back()->with('success', 'Password Updated Successfully!');
            }
            return back()->with('error', 'Old password is incorrect');
        } else {
            return back()->with('error', 'Please enter old and new password');
        }
    }
    public function shared_case_remove(Request $request, $id)
    {
        $user = Auth::guard('user')->user();
        $shared_case = $user->share_cases->where('is_deleted', 0)->find($id);
        if ($shared_case->is_revoked) {
            return back()->with('error', 'Your access has revoked');
        }
        if ($shared_case->removed_by_user) {
            $shared_case->removed_by_user = false;
            $shared_case->save();
        }
        if ($shared_case) {
            $shared_case->removed_by_user = true;
            $shared_case->save();
            $comment = new Comment();
            $comment->user_id = Auth::guard('user')->user()->id;
            $comment->case_id = $shared_case->case_id;
            $comment->type = 'removed share case';
            $comment->comment = isset($request->comment) ? $request->comment : 'User removed shared case';
            $comment->save();

            return redirect()->back()->with('success', 'Cased Removed Successfully');
        }
        return back()->with('error', 'Shared case not found');
    }
    public function request_extension(Request $request, $id)
    {
        if (Carbon::parse($request->duration_of_access) < Carbon::now()) {
            return back()->with('error', 'You can not request access in past');
        }
        $user = Auth::guard('user')->user();
        $shared_case = $user->share_cases->find($id);
        if ($shared_case) {
            $share_case_extension = $shared_case->share_case_extension;
            if ($share_case_extension) {
                if (count($share_case_extension->where('status', 0))) {
                    $comment = new Comment();
                    $comment->user_id = $user->id;
                    $comment->case_id = $shared_case->case->id;
                    $comment->comment = $user->name . ' tried for extension but his/her extension already in pending';
                    $comment->save();
                    return back()->with('error', 'Your request is already in pending.');
                }
            }
            $share_case_extension = new ShareCaseRequestExtension();
            $share_case_extension->share_case_id = $shared_case->id;
            $share_case_extension->share_case_id = $shared_case->id;
            $share_case_extension->extension_time = $request->duration_of_access;
            $share_case_extension->note = isset($request->note) ? $request->note : ' ';
            $share_case_extension->status = 0;
            $share_case_extension->requested_by = $user->id;
            $share_case_extension->save();

            ActivityLog::create([
                'user_id' => $user->id,
                'head_office_id' => $shared_case->case->head_office_id,
                'action' => 'Shared case access extension requested by' . $user->email . ' by ' . $request->duration_of_access,
                'type' => 'Share Case Externally',
                'timestamp' => now(),
            ]);

            $comment = new Comment();
            $comment->user_id = $user->id;
            $comment->case_id = $shared_case->case->id;
            $comment->comment = $user->name . " requested extension. \n Notes : " . (empty($request->note) ? 'N/A' : $request->note);

            $comment->save();

            $case_log = new ShareCaseLog();
            $case_log->log = "share case extension requested by $user->name " . Carbon::now()->diffForHumans() . "\n" . 'Notes: ' . $request->note;
            $case_log->share_case_id = $shared_case->id;
            $case_log->save();

            Mail::send('emails.request_information', ['case' => $shared_case->case, 'heading' => 'shared case extension requested', 'msg' => "$user->name has requested an extension to the shared case. Click this link " . route('case_manager.view_sharing', $shared_case->case->id) . ' to view.'], function ($message) use ($shared_case) {
                $message->to($shared_case->sharedBy->email);
                $message->subject(env('APP_NAME') . ' - Fill requested information');
            });
            // foreach($shared_case->case->case_interested_parties as $interested_party)
            // {
            //         Mail::send('emails.request_information', ['case' => $shared_case->case,'heading' => 'shared case extension requested',
            //     'msg' => "$user->name has requested an extension to the shared case. Click this link ".route('case_manager.view_sharing',$shared_case->case->id) ." to view." ]
            //     , function($message) use($interested_party){
            //         $message->to($interested_party->email);
            //         $message->subject(env('APP_NAME') . ' - Fill requested information');
            //     });
            // }
            return back()->with('success_message', 'Extension request successfully');
        }
        return back()->with('error', 'Shared case not found');
    }

    public function request_extension_remove($share_id, $extension_id)
    {
        $user = Auth::guard('user')->user();
        $shared_case = $user->share_cases->find($share_id);
        if ($shared_case) {
            $share_case_extension = $shared_case->share_case_extension()->find($extension_id);
            if ($share_case_extension) {
                $share_case_extension->delete();
                $case_log = new ShareCaseLog();
                $case_log->log = 'share case extension requested by ' . $share_case_extension->requested_by_user->name . " now deleted by $user->name at" . Carbon::now();

                $case_log->share_case_id = $shared_case->id;
                $case_log->save();

                ActivityLog::create([
                    'user_id' => $user->id,
                    'head_office_id' => $shared_case->case->head_office_id,
                    'action' => 'Removed extension.',
                    'type' => 'Share Case Externally',
                    'timestamp' => now(),
                ]);
                $comment = new Comment();
                $comment->user_id = $user->id;
                $comment->case_id = $shared_case->case->id;
                $comment->Type = "requeset canceled";
                $comment->comment = $user->name . ' has removed extension.';
                $comment->save();
                return back()->with('success_message', 'Removed successfully');
            }

            return back()->with('success_message', 'Extension not found');
        }

        return back()->with('success_message', 'Shared case not found');
    }

    public function share_case_comment(Request $request, $share_id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'nullable|string',
            'reminder_links' => 'nullable|array',
            'documents' => 'nullable|array',
            'audios' => 'nullable|array',
        ]);
        
        $validator->after(function ($validator) use ($request) {
            // Check if the 'comment' is non-empty
            $hasComment = !empty($request->comment);
        
            $hasReminderLinks = is_array($request->reminder_links) && array_filter($request->reminder_links, function($value) {
                return !is_null($value); // Return true if at least one value is not null
            });
        
            $hasDocuments = is_array($request->documents) && array_filter($request->documents, function($value) {
                return !is_null($value); // Return true if at least one value is not null
            });
        
            $hasAudios = is_array($request->audios) && array_filter($request->audios, function($value) {
                return !is_null($value); // Return true if at least one value is not null
            });
        
            if (!$hasComment && !$hasReminderLinks && !$hasDocuments && !$hasAudios) {
                $validator->errors()->add('at_least_one', 'At least one of comment, reminder links, documents, or audios must be present.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->with('error','Empty comment not allowed!');
        }

        $user = Auth::guard('user')->user();
        $shared_case = $user->share_cases->find($share_id);
        if ($shared_case) {
            $communicatoin = new ShareCaseCommunication();
            $communicatoin->is_user = 1;
            $communicatoin->user_id = $user->id;
            $communicatoin->share_case_id = $shared_case->id;
                        
            $case_comment = new Comment();
            $case_comment->case_id = $shared_case->case->id;
            $case_comment->type = 'messages at';
            $case_comment->user_id = $user->id;
            $string = Helper::check_link($request->link_title, $request->link_comment, $request->comment, $case_comment->case_id, $case_comment->id);

            $communicatoin->message = $string ? $string : $request->comment ?? ' ';
            $case_comment->comment = $string ? $string : $request->comment ?? ' ';
            $case_comment->save();
            $communicatoin->save();
            
            

            $documents = (array) $request->documents;
            ShareCaseCommunicationDocument::where('share_case_communication_id', $communicatoin->id)->delete();
        foreach ($documents as $value) {
            $doc = new ShareCaseCommunicationDocument();
            $doc->share_case_communication_id = $communicatoin->id;
            $value = Document::where('unique_id', $value)->first();
            if (!$value) {
                continue;
            }
            $doc->document_id = $value->id;
            $doc->type = $value->isImage() ? 'image' : 'document';
            $doc->save();
        }

        if (isset($request->audios)) {
            ShareCaseCommunicationDocument::where('share_case_communication_id', $communicatoin->id)->delete();
            foreach ($request->audios as $audio) {
                $doc = new ShareCaseCommunicationDocument();
                $doc->share_case_communication_id = $communicatoin->id;
                $audio = Document::where('unique_id', $audio)->first();
                if (!$audio) {
                    continue;
                }
                $doc->document_id = $audio->id;

                $doc->type = 'audio';
                $doc->save();
            }
        }
            return redirect()->route('user.share_case', [$share_id, 'tab=commmunication-tab']);
        }
        return back()->with('error', 'Shared case not found');
    }
    public function save_answer(Request $request)
    {
        $question_id = $request->question_id;
        $question = CaseRequestInformationQuestion::findOrFail($question_id);
        $answer = $request->answer;
        $user = Auth::guard('user')->user();
        $CaseRequestInformation = $user->requests()->findOrFail($question->CaseRequestInformation->id);
        if (!$CaseRequestInformation->status) {
            $question->answer = $answer;
            $question->save();
            return response(['result' => true]);
        }
        return response(['result' => false, 'msg' => 'Response Already submitted']);
    }
    public function submit_request(Request $request)
    {
        $req = $request->request_id;
        $user = Auth::guard('user')->user();
        $req = $user->requests()->find($req);
        if ($req->status) {
            return response(['result' => false, 'msg' => 'Answers already submitted']);
        }
        foreach ($req->questions as $question) {
            $question_field = 'question_' . $question->id;
            $question_id = $request->$question_field;
            $answer_field = 'answer_' . $question->id;
            $answer = $request->$answer_field;
            $q = $req->questions()->find($question_id);
            $q->answer = $answer;
            $q->save();
        }
        $req->status = 1;
        $req->save();
        return response(['result' => true, 'msg' => 'Answers submitted successfully']);
    }
    public function update_first_name(Request $request)
    {
        try {
            $user = Auth::guard('user')->user();
            $user->first_name = $request->first_name;
            $user->save();
            return response(['result' => true, 'msg' => 'First Name updated successfully']);
        } catch (Exception $e) {
            return response(['result' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function update_sur_name(Request $request)
    {
        try {
            $user = Auth::guard('user')->user();
            $user->surname = $request->surname;
            $user->save();
            return response(['result' => true, 'msg' => 'Surname updated successfully']);
        } catch (Exception $e) {
            return response(['result' => false, 'msg' => $e->getMessage()]);
        }
    }
    

    // public function update_sur_name(Request $request)
    // {
    //     try {
    //         $user = Auth::guard('user')->user();
    //         if ($user->last_name == $request->last_name && $user->surname == $request->sur_name) {
    //             return response(['result' => false]);
    //         }
    //         $user_name = new UserName();
    //         $user_name->user_id = $user->id;
    //         $user_name->first_name = $user->first_name;
    //         $user_name->sur_name = $user->surname;
    //         $user_name->save();

    //         $user->surname = $request->sur_name;
    //         $user->first_name = $request->first_name;
    //         $user->save();

    //         return response(['result' => true, 'msg' => 'Sur Name updated successfully']);
    //     } catch (Exception $e) {
    //         return response(['result' => false, 'msg' => $e->getMessage()]);
    //     }
    // }
    public function update_email(Request $request)
    {
        try {
            $user = Auth::guard('user')->user();
            $user->email = $request->email;
            $user->save();
            return response(['result' => true, 'msg' => 'Email updated successfully']);
        } catch (Exception $e) {
            return response(['result' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function update_phone(Request $request)
    {
        try {
            $user = Auth::guard('user')->user();
            $user->mobile_no = $request->phone;
            $user->save();
            return response(['result' => true, 'msg' => 'Phone updated successfully']);
        } catch (Exception $e) {
            return response(['result' => false, 'msg' => $e->getMessage()]);
        }
    }
    function create_contact(Request $request)
    {
        $value = $request->value;
        $user = Auth::guard('user')->user();
        $is_email_hidden = $user->is_email_hidden;
        $is_phone_hidden = $user->is_phone_hidden;
        $contacts = $user->contacts();
        $check = $contacts->find($request->id);

        if (!$check) {
            $check = $contacts->where('contact', $value)->first();
            //return  response(['result'=>$check]);
            if (!$check) {
                $check = new UserContactDetail();
                $check->user_id = $user->id;
                $check->type = $request->type;
            } else {
                return response(['result' => false, 'msg' => 'Contact already exsist', 'data' => $check, 'is_email_hidden' => $is_email_hidden, 'is_phone_hidden' => $is_phone_hidden]);
            }
        }
        $check->contact = $value;
        $check->save();
        return response(['result' => true, 'msg' => 'Contact updated successfully', 'data' => $check, 'is_email_hidden' => $is_email_hidden, 'is_phone_hidden' => $is_phone_hidden]);
    }
    public function delete_contact($id)
    {
        $user = Auth::guard('user')->user();
        $contact = $user->contacts()->findOrFail($id);
        if ($contact) {
            $contact->delete();
            return back()->with('success_message', 'contact delete successfully');
        }
        return back()->with('error', 'Contact not found');
    }
    public function hide_email(Request $request, $type)
    {
        $is_sub_contact = $request->input('is_sub_contact');
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
    
        if (!isset($user)) {
            return redirect()->route('login')->with('error', 'User not logged in');
        }
    
        $contacts = $user->contacts()->where('type', 1);
    
        if (!$is_sub_contact) {
            if ($type) {
                $user->is_email_hidden = 1;
            } else {
                $user->is_email_hidden = 0;
            }
    
            $user->save(); // Save the email hidden status
            $contacts->update(['is_contact_show' => 0]); // Update all contacts
            
            return back()->with('success_message', 'Contact hidden successfully');
        } else {
            if (!$user->is_email_hidden) {
                return back()->with('error', 'Main contact is already shown');
            }
    
            $contact = $contacts->find($request->id); // Find the specific contact
            if ($contact) {
                // Hide all other contacts
                $contacts->update(['is_contact_show' => 0]);
    
                // Show the selected contact
                $contact->is_contact_show = 1;
                $contact->save();
    
                return back()->with('success_message', 'Contact set to shown');
            }
    
            return back()->with('error', 'Contact not found');
        }
    }
    
    public function hide_phone(Request $request, $type)
    {
        $is_sub_contact = $request->is_sub_contact;
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $contacts = $user->contacts()->where('type', 0);
        if (!$is_sub_contact) {
            if ($type) {
                $user->is_phone_hidden = 1;
            } else {
                $user->is_phone_hidden = 0;
            }
            $user->save();
            $contacts->update(['is_contact_show' => 0]);
            return back()->with('success_message', 'contact hide successfully');
        } else {
            if (!$user->is_phone_hidden) {
                return back()->with('error', 'Main contact is already Shown');
            }

            $c = $contacts->find($request->id);
            if ($c) {
                $contacts = $user->contacts()->where('type', 0);
                $contacts->update(['is_contact_show' => 0]);
                //save([$contacts->is_contact_show => 0]);
                $c->is_contact_show = 1;
                $c->save();
                return back()->with('success_message', 'contact set to shown');
            }
            return back()->with('error', 'Contact not found');
        }
    }
    public function user_feedback($id = null)
    {
        $user = Auth::guard('user')->user();
        if ($id == null) {
            $id = $user->id;
        }
        if ($user->id != $id) {
            return Auth::logout();
        }
        $feedbacks = $user->getCaseFeedbacks();
        $headOfficeCases = HeadOfficeCase::all();
        return view('user.feedback', compact('user', 'feedbacks', 'headOfficeCases'));
    }

    public function user_feedback_seen($id){
        if(!isset($id)){
            return back()->with('error','Wrong data passed!');
        }
        $user = Auth::guard('user')->user();
        $feedback = $user->getCaseFeedbacks()->find($id);
        if (isset($feedback)) {
            if($feedback->mark_read == true){
                $feedback->mark_read = false;
                $feedback->marked_unseen = true;
            }else{
                $feedback->mark_read = true;
                $feedback->marked_unseen = false;
            }
            $feedback->save();
            return back()->with('success','Feedback status updated!');
        } else {
            return back()->with('error','not found');
        }
    }
    public function user_company()
    {
        $user = Auth::guard('user')->user();
        if (!isset($user)) {
            return Auth::logout();
        }
        $hos = Auth::guard('user')->user()->head_office_admins;
        return view('user.companies', compact('hos','user'));
    }
    public function view_request($id)
    {
        $user = Auth::guard('user')->user();
        $request = $user->requests()->find($id);
        return view('user.single_request', compact('user', 'request'));
    }
    public function update_picture(Request $request)
{
    $file = $request->input('file'); // Get the Base64 string
    $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();

    try {
        if (!is_dir('v2/user_profile')) {
            mkdir('v2/user_profile', 0777, true); // Create the directory if it doesn't exist
        }

        $name = $user->id . '.jpg'; // Generate the file name using user ID
        $destinationPath = 'v2/user_profile/' . $name; // Destination path to save the image

        // Check if the file starts with the Base64 image prefix
        if (preg_match('/^data:image\/(jpeg|jpg);base64,/', $file)) {
            // Remove the Base64 prefix
            $file = substr($file, strpos($file, ',') + 1);
            $file = str_replace(' ', '+', $file); // Ensure correct characters in the Base64 string
            $imageData = base64_decode($file); // Decode the Base64 string to binary data

            // Save the decoded image to the destination path
            file_put_contents($destinationPath, $imageData);
        } else {
            return response()->json(['error' => 'Invalid image data'], 400);
        }

        return response()->json(['message' => 'Profile picture updated successfully!', 'path' => $destinationPath]);

    } catch (Exception $e) {
        return response()->json(['error' => 'Error saving image: ' . $e->getMessage()], 500);
    }
}


    public function user_draft()
    {
        $user = Auth::guard('user')->user();
        if ($user) {
            $drafts = be_spoke_form_record_drafts::where('user_id', $user->id)->get();
            return view('user.draft', compact('user', 'drafts'));
        }
        return view('user.draft');
    }

    public function otp_security(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $otp = $user->otp;
            $otp->update([
                'isEnabled' => !$otp->isEnabled,
            ]);
            return redirect()->route('user.view_profile');
        }
        return redirect('/app.html#!/login?error=-1');
    }

    public function unseen_comment($id){
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:share_case_communications_views,comment_id',
        ]);
        if($validator->fails()){
            return abort('404');
        }
        $user = Auth::guard('user')->user();
        $ho_u = $user->getHeadOfficeUser();
        $comment = share_case_communications_views::where('comment_id',$id)->where('head_office_user_id',$ho_u->id)->first();
        if(isset($comment)){
            $comment->is_seen = false;
            $comment->save();
            return back()->with('success','Message marked as unseen!');
        }

        return back()->with('error','not found');
    }
    public function seen_comment($comment_id){
        $validator = Validator::make(['id' => $comment_id], [
            'id' => 'required|exists:share_case_communications_views,comment_id',
        ]);
        if($validator->fails()){
            return abort('404');
        }
        $user = Auth::guard('user')->user();
        $ho_u = $user->getHeadOfficeUser();
        $comment = share_case_communications_views::where('comment_id',$comment_id)->where('head_office_user_id',$ho_u->id)->first();
        if(isset($comment)){
            $comment->is_seen = true;
            $comment->save();
            return back()->with('success','Message marked as seen!');
        }

        return back()->with('error','not found');
    }

    public function delete_comment(Request $request, $id)
    {
        $user = Auth::guard('user')->user();
        $comment = ShareCaseCommunication::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
        if (!$comment) {
            abort(404);
        }
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
