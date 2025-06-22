<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\base64Convert;
use App\Models\ActivityLog;
use App\Models\Address;
use App\Models\address_comments;
use App\Models\contact_groups;
use App\Models\contact_tags;
use App\Models\contact_to_groups;
use App\Models\contacts_to_addresses;
use App\Models\Document;
use App\Models\HeadOffice;
use App\Models\HeadOfficeUser;
use App\Models\location_comments;
use App\Models\matching_contacts;
use App\Models\new_address_document;
use App\Models\new_contact_addresses;
use App\Models\new_contact_comment_activity;
use App\Models\new_contact_comments;
use App\Models\new_contact_documents;
use App\Models\new_contact_links;
use App\Models\new_contacts;
use App\Models\new_contacts_relations;
use App\Models\stage_case_handler;
use App\Models\tag_to_contact;
use App\Models\tag_to_contacts;
use App\Models\tag_to_group;
use App\Models\User;
use App\Models\user_favourite_contacts;
use App\Models\user_to_addresses;
use App\Models\user_to_contacts;
use Carbon\Carbon;
use Exception;
use FuzzyWuzzy\Fuzz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpParser\JsonDecoder;
use Validator;
use App\Mail\LocationInfoUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        $contacts = $head_office->new_contacts;
        $contact_groups = $head_office->contact_groups;
        $contact_tags = $head_office->contact_tags;
        $head_office_users = $head_office->users;
        $new_contact_addresses = $head_office->new_contact_addresses;
        $user_favourite_contacts = Auth::guard('web')->user()->getHeadOfficeUser()->user_favourite_contacts;
        $user_to_contacts = Auth::guard('web')->user()->getHeadOfficeUser()->user_to_contacts;
        return view('head_office.contacts.contacts', compact('contact_groups', 'contact_tags', 'contacts', 'user_favourite_contacts', 'user_to_contacts', 'head_office_users', 'new_contact_addresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id = null)
    {
        // dd($request->all());
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }

        if ($id) {
            $new_contact = $head_office->new_contacts()->find($id);
            if (!$new_contact) {
                return redirect()->route('head_office.contacts.index')->with('error', 'Contact not found');
            }
            $new_contact->contacts_to_addresses()->delete();
            $new_contact->contact_to_groups()->delete();
            new_contacts_relations::where('source_contact_id', $id)->orWhere('target_contact_id', $id)->delete();

            $new_contact->tag_to_contacts()->delete();
            $new_contact->user_to_contacts()->delete();
        } else {
            $new_contact = new new_contacts();
        }
        $file = $request->image;
        if (!isset($file)) {
            $avatar = 'logo_blue.png';
        } else {
            try {
                if (!is_dir('v2')) {
                    mkdir('v2', 0777, true);
                }

                $avatar = Str::uuid() . '.jpg';
                $destinationPath = 'v2/' . $avatar;
                base64Convert::save_base64($file, $destinationPath);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        $new_contact->name = isset($request->name) ? $request->name : null;
        $new_contact->avatar = $avatar;
        $new_contact->date_of_birth = isset($request->date_of_birth) ? Carbon::createFromFormat('Y-m-d', $request->date_of_birth) : null;
        $new_contact->nhs_no = isset($request->nhs_no) ? $request->nhs_no : null;
        $new_contact->ethnicity = isset($request->ethnicity) ? $request->ethnicity : null;
        $new_contact->sexual_orientation = isset($request->sexual_orientation) ? $request->sexual_orientation : null;
        $new_contact->marital_status = isset($request->marital_status) ? $request->marital_status : null;
        $new_contact->gender = isset($request->gender) ? $request->gender : null;
        $new_contact->pronoun = isset($request->pronoun) ? $request->pronoun : null;
        $new_contact->religion = isset($request->religion) ? $request->religion : null;
        $new_contact->passport_no = isset($request->passport_no) ? $request->passport_no : null;
        $new_contact->driver_license_no = isset($request->driver_license_no) ? $request->driver_license_no : null;
        $new_contact->profession = isset($request->profession) ? $request->profession : null;
        $new_contact->registration_no = isset($request->registration_no) ? $request->registration_no : null;
        $new_contact->work_emails = isset($request->work_emails) ? json_encode($request->work_emails) : null;
        $new_contact->personal_emails = isset($request->personal_emails) ? json_encode($request->personal_emails) : null;
        $new_contact->work_mobiles = isset($request->work_mobiles) ? json_encode($request->work_mobiles) : null;
        $new_contact->personal_mobiles = isset($request->personal_mobiles) ? json_encode($request->personal_mobiles) : null;
        $new_contact->home_telephones = isset($request->home_telephones) ? json_encode($request->home_telephones) : null;
        $new_contact->work_telephones = isset($request->work_telephones) ? json_encode($request->work_telephones) : null;
        $new_contact->facebook = isset($request->facebook) ? $request->facebook : null;
        $new_contact->instagram = isset($request->instagram) ? $request->instagram : null;
        $new_contact->twitter = isset($request->twitter) ? $request->twitter : null;
        $new_contact->other_link = isset($request->other_link) ? $request->other_link : null;
        $new_contact->head_office_id = $head_office->id;
        $new_contact->save();
        if (!empty($request->group_ids)) {
            foreach ($request->group_ids as $group) {
                $contact_to_group = new contact_to_groups();
                $contact_to_group->contact_id = $new_contact->id;
                $contact_to_group->group_id = $group;
                $contact_to_group->save();
            }
        }
        if (!empty($request->addresses)) {
            foreach ($request->addresses as $address) {
                $contacts_to_address = new contacts_to_addresses();
                $contacts_to_address->contact_id = $new_contact->id;
                $contacts_to_address->address_id = $address;
                $contacts_to_address->save();
            }
        }
        if (!empty($request->tag_ids)) {
            foreach ($request->tag_ids as $tag) {
                $tag_to_contact = new tag_to_contacts();
                $tag_to_contact->tag_id = $tag;
                $tag_to_contact->contact_id = $new_contact->id;
                $tag_to_contact->save();
            }
        }
        if (!empty($request->new_contacts_relations)) {
            $contacts_relations = json_decode($request->new_contacts_relations, true);
            foreach ($contacts_relations as $relation) {
                $contact_relation = new new_contacts_relations();
                $contact_relation->source_contact_id = $new_contact->id;
                $contact_relation->target_contact_id = $relation['target_contact_id'];
                $contact_relation->relation = $relation['relation'];
                $contact_relation->reverse_relation = $relation['reverse_relation'];
                $contact_relation->save();
                $contact_relation_target = new new_contacts_relations();
                $contact_relation_target->source_contact_id = $relation['target_contact_id'];
                $contact_relation_target->target_contact_id = $new_contact->id;
                $contact_relation_target->relation = $relation['reverse_relation'];
                $contact_relation_target->reverse_relation = $relation['relation'];
                $contact_relation_target->save();
            }
        }
        if (!empty($request->assigns)) {
            foreach ($request->assigns as $assigne) {
                $user_to_contact = new user_to_contacts();
                $user_to_contact->head_office_user_id = $assigne;
                $user_to_contact->contact_id = $new_contact->id;
                $user_to_contact->save();
            }
        }

        $contacts = $head_office->new_contacts;
        $matchs = self::findPotentialMatches($contacts);
        if (!empty($matchs)) {
            foreach ($matchs as $match) {
                $contact1Id = $match['contact_id'];
                $contact2Id = $match['matching_contact_id'];
        
                $contact1Exists = new_contacts::find($contact1Id);
                $contact2Exists = new_contacts::find($contact2Id);
        
                if ($contact1Exists && $contact2Exists) {
                    $existingMatch = matching_contacts::where(function ($query) use ($contact1Id, $contact2Id) {
                        $query->where('contact_1', $contact1Id)
                              ->where('contact_2', $contact2Id);
                    })->orWhere(function ($query) use ($contact1Id, $contact2Id) {
                        $query->where('contact_1', $contact2Id)
                              ->where('contact_2', $contact1Id);
                    })->first();
        
                    if (!$existingMatch) {
                        $new_match = new matching_contacts();
                        $new_match->contact_1 = $contact1Id;
                        $new_match->contact_2 = $contact2Id;
                        $new_match->match = (float) $match['similarity_percentage'];
                        $new_match->save();
                    }
                }
            }
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'Contact created successfully');
    }


    public function view($id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }

        $new_contact = $head_office->new_contacts()->find($id);
        if (!$new_contact) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Contact not found');
        }
        $contact_to_groups = $new_contact->contact_to_groups;
        $contact_tags = $head_office->contact_tags;
        $new_contacts = $head_office->new_contacts;
        $contact_to_addresses = $new_contact->contacts_to_addresses;
        $new_contacts_relations = $new_contact->new_contacts_relations;
        $tag_to_contacts = $new_contact->tag_to_contacts;
        $head_office_users = $head_office->users;
        $user_to_contacts = $new_contact->user_to_contacts;
        $new_contact_comments = $new_contact->new_contact_comments;
        $contacts_attr = $head_office->new_contacts->map(function ($contact) {
            return ['id'=>$contact->id, 'name'=>$contact->name];
        });
        $users_attr = $head_office->users->map(function ($user) {
            return ['id'=>$user->user->id, 'name'=>$user->user->name];
        });

        $addresses_attr = $head_office->new_contact_addresses->map(function ($addresse) {
            return ['id'=>$addresse->id, 'name'=>$addresse->address];
        });
        return view('head_office.contacts.view', compact('user','new_contact', 'contact_to_groups', 'contact_tags', 'new_contacts', 'contact_to_addresses', 'new_contacts_relations', 'tag_to_contacts', 'head_office_users', 'user_to_contacts', 'new_contact_comments','contacts_attr','addresses_attr','users_attr'));
    }

    public function edit($id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }

        $new_contact = $head_office->new_contacts()->find($id);
        if (!$new_contact) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Contact not found');
        }
        $contact_groups = $head_office->contact_groups;
        $contact_tags = $head_office->contact_tags;
        $new_contacts = $head_office->new_contacts;
        $contacts_to_addresses = $new_contact->contacts_to_addresses;
        $new_contacts_relations = $new_contact->new_contacts_relations;
        $tag_to_contacts = $new_contact->tag_to_contacts;
        $head_office_users = $head_office->users;
        $user_to_contacts = $new_contact->user_to_contacts;
        $new_contact_comments = $new_contact->new_contact_comments;
        $new_contact_addresses = $head_office->new_contact_addresses;

        $contacts_attr = $head_office->new_contacts->map(function ($contact) {
            return ['id'=>$contact->id, 'name'=>$contact->name];
        });
        $users_attr = $head_office->users->map(function ($user) {
            return ['id'=>$user->user->id, 'name'=>$user->user->name];
        });
        $addresses_attr = $head_office->new_contact_addresses->map(function ($addresse) {
            return ['id'=>$addresse->id, 'name'=>$addresse->address];
        });
        return view('head_office.contacts.contacts_edit', compact('user','new_contact', 'contact_groups', 'contact_tags', 'new_contacts', 'contacts_to_addresses', 'new_contacts_relations', 'tag_to_contacts', 'head_office_users', 'user_to_contacts', 'new_contact_comments', 'new_contact_addresses','contacts_attr','addresses_attr','users_attr'));
    }

    public function AddressEdit($id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }

        $address = $head_office->new_contact_addresses->find($id);
        if (!$address) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Contact not found');
        }
        $contact_tags = $head_office->contact_tags;
        $new_contacts = $head_office->new_contacts;
        $tag_to_contacts = $address->tag_to_addresses;
        $head_office_users = $head_office->users;
        $user_to_contacts = $address->user_to_addresses;
        $new_contact_comments = $address->address_comments;

        $contacts_attr = $head_office->new_contacts->map(function ($contact) {
            return ['id'=>$contact->id, 'name'=>$contact->name];
        });
        $users_attr = $head_office->users->map(function ($user) {
            return ['id'=>$user->user->id, 'name'=>$user->user->name];
        });
        $addresses_attr = $head_office->new_contact_addresses->map(function ($addresse) {
            return ['id'=>$addresse->id, 'name'=>$addresse->address];
        });
        return view('head_office.contacts.address_edit', compact('user','address', 'contact_tags', 'new_contacts',  'tag_to_contacts', 'head_office_users', 'user_to_contacts', 'new_contact_comments','contacts_attr','addresses_attr','users_attr'));
    }

    public function favourite_contact(Request $request, $contact_id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }

        if (!isset($contact_id)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select contact first');
        }

        $user_favourite_contacts = user_favourite_contacts::where('head_office_user_id', Auth::guard('web')->user()->getHeadOfficeUser()->id)
            ->where('contact_id', $contact_id)
            ->first();
        if ($user_favourite_contacts) {
            $user_favourite_contacts->delete();
            return redirect()->route('head_office.contacts.index')->with('success', 'Contact removed from favourite');
        }
        $favourite = new user_favourite_contacts();
        $favourite->head_office_user_id = Auth::guard('web')->user()->getHeadOfficeUser()->id;
        $favourite->contact_id = $contact_id;
        $favourite->save();
        return redirect()->route('head_office.contacts.index')->with('success', 'Contact added to favourite');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\new_contacts  $new_contacts
     * @return \Illuminate\Http\Response
     */
    public function create_contact()
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        $contact_groups = $head_office->contact_groups;
        $contact_tags = $head_office->contact_tags;
        $new_contacts = $head_office->new_contacts;
        $head_office_users = $head_office->users;
        $new_contact_addresses = $head_office->new_contact_addresses;
        return view('head_office.contacts.contacts_create', compact('contact_groups', 'contact_tags', 'new_contacts', 'head_office_users', 'new_contact_addresses'));
    }

    public function create_group(Request $request, $id = null)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        if (isset($id)) {
            $group = $head_office->contact_groups()->find($id);
            if (!$group) {
                return redirect()->route('head_office.contacts.index')->with('error', 'Group not found');
            }
            $group->group_name = $request->group_name;
            $group->save();
            return redirect()->route('head_office.contacts.index')->with('success', 'Group updated successfully');
        }
        $group = new contact_groups();
        $group->group_name = $request->group_name;
        $group->head_office_id = $head_office->id;
        $group->save();
        return redirect()->route('head_office.contacts.index')->with('success', 'Group created successfully');
    }

    public function delete_group(Request $request, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        $group = $head_office->contact_groups()->find($id);
        if (!$group) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Group not found');
        }
        $group->delete();
        return redirect()->route('head_office.contacts.index')->with('success', 'Group deleted successfully');
    }

    public function create_tag(Request $request, $id = null)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        if (isset($id)) {
            $tag = $head_office->contact_tags()->find($id);
            if (!$tag) {
                return redirect()->route('head_office.contacts.index')->with('error', 'Tag not found');
            }
            $tag->tag_name = $request->tag_name;
            $tag->type = $request->type;
            $tag->icon = $request->icon;
            $tag->save();
            tag_to_group::where('tag_id', $tag->id)->delete();

            if ($tag->type === 'group_specific' && !empty($request->groups)) {
                foreach ($request->groups as $group) {
                    $tag_to_group = new tag_to_group();
                    $tag_to_group->tag_id = $tag->id;
                    $tag_to_group->group_id = $group;
                    $tag_to_group->save();
                }
            }
            return redirect()->route('head_office.contacts.index')->with('success', 'Group updated successfully');
        }

        $tag = new contact_tags();
        $tag->tag_name = $request->tag_name;
        $tag->type = $request->type;
        $tag->icon = $request->icon;
        $tag->head_office_id = $head_office->id;
        $tag->save();

        if ($tag->type === 'group_specific' && !empty($request->groups)) {
            foreach ($request->groups as $group) {
                $tag_to_group = new tag_to_group();
                $tag_to_group->tag_id = $tag->id;
                $tag_to_group->group_id = $group;
                $tag_to_group->save();
            }
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'Tag created successfully');
    }
    public function delete_tag(Request $request, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        $tag = $head_office->contact_tags()->find($id);
        if (!$tag) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Tag not found');
        }
        tag_to_group::where('tag_id', $tag->id)->delete();
        $tag->delete();
        return redirect()->route('head_office.contacts.index')->with('success', 'Tag deleted successfully');
    }

    public function save_comment(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();

        if ($request->close_comment) {
            $validated = $request->validate(
                [
                    'close_comment' => 'required|min:1',
                    'case_id' => 'required|min:1',
                ],
                [
                    'close_comment.required' => 'Please add a comment.',
                ],
            );
        } else {
            $validated = $request->validate(
                [
                    'comment' => 'required|min:1',
                    'contact_id' => 'required|min:1',
                ],
                [
                    'comment.required' => 'Please add a comment.',
                    'contact_id.required' => 'The contact ID field is required.',
                ],
            );
        }
        // dd($request->all());
        $new_contact = new_contacts::where('id', $request->contact_id)
            ->where('head_office_id', $headOffice->id)
            ->first();
        if (!$new_contact) {
            abort(403, 'Data access denied');
        }
        $comment = new_contact_comments::where('user_id', $user->id)
            ->where('id', $request->id)
            ->first();
        $editing = true;
        if (!$comment) {
            $comment = new new_contact_comments();
            $editing = false;
        }
        $comment->contact_id = $request->contact_id;
        $comment->user_id = $user->id;
        $comment->comment = '';
        $comment->save();

        if (!$editing) {
            $comment->parent_id = $request->parent_id ? $request->parent_id : null;
        }

        $comment->comment = $request->comment;

        $activity_log = new new_contact_comment_activity();
        $activity_log->type = 'Comment';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $headOffice->id;
        $activity_log->action = 'Comment added by ' . $user->first_name . ' ' . $user->surname;
        $activity_log->comment_id = $comment->id;
        $activity_log->save();
        $comment->save();

        if ($request->reminder_links) {
            foreach ($request->reminder_links as $link) {
                if ($link) {
                    $data = json_decode($link);
                    $link = new new_contact_links();
                    $link->title = $data->title;
                    $link->link = $data->url;
                    $link->description = $data->comment;
                    $link->user_id = Auth::guard('web')->user()->id;
                    $link->save();
                }
            }
        }

        $documents = (array) $request->documents;
        new_contact_documents::where('comment_id', $comment->id)->delete();
        foreach ($documents as $value) {
            $doc = new new_contact_documents();
            $doc->comment_id = $comment->id;
            $value = Document::where('unique_id', $value)->first();
            if (!$value) {
                continue;
            }
            $doc->document_id = $value->id;
            $doc->type = $value->isImage() ? 'image' : 'document';
            $doc->save();
        }

        if (isset($request->audios)) {
            new_contact_documents::where('comment_id', $comment->id)->delete();
            foreach ($request->audios as $audio) {
                $doc = new new_contact_documents();
                $doc->comment_id = $comment->id;
                $audio = Document::where('unique_id', $audio)->first();
                if (!$audio) {
                    continue;
                }
                $doc->document_id = $audio->id;

                $doc->type = 'audio';
                $doc->save();
            }
        }

        return redirect()
            ->route('head_office.contacts.edit', $request->contact_id)
            ->with('success', 'Comment saved successfully.');
    }
    public function save_address_comment(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();

        if ($request->close_comment) {
            $validated = $request->validate(
                [
                    'close_comment' => 'required|min:1',
                    'case_id' => 'required|min:1',
                ],
                [
                    'close_comment.required' => 'Please add a comment.',
                ],
            );
        } else {
            $validator = Validator::make($request->all(), [
                'address_id' => 'required|min:1',
                'comment' => 'nullable|string',
                'reminder_links' => 'nullable|array',
                'documents' => 'nullable|array',
                'audios' => 'nullable|array',
            ], [
                'address_id.required' => 'The case ID field is required.',
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
        }
        // dd($request->all());
        $new_contact = Address::where('id', $request->address_id)
            ->where('head_office_id', $headOffice->id)
            ->first();
        if (!$new_contact) {
            abort(403, 'Data access denied');
        }
        $comment = address_comments::where('user_id', $user->id)
            ->where('id', $request->id)
            ->first();
        $editing = true;
        if (!isset($comment)) {
            $comment = new address_comments();
            $editing = false;
        }
        $comment->address_id = $request->address_id;
        $comment->user_id = $user->id;
        $comment->comment = '';
        $comment->save();

        if (!$editing) {
            $comment->parent_id = $request->parent_id ? $request->parent_id : null;
        }

        $comment->comment = $request->comment ?? '';

        $activity_log = new ActivityLog();
        $activity_log->type = 'Comment';
        $activity_log->user_id = $user->id;
        $activity_log->head_office_id = $headOffice->id;
        $activity_log->action = 'Comment added by ' . $user->first_name . ' ' . $user->surname;
        $activity_log->save();
        $comment->save();

        if ($request->reminder_links) {
            foreach ($request->reminder_links as $link) {
                if ($link) {
                    $data = json_decode($link);
                    $link = new new_contact_links();
                    $link->title = $data->title;
                    $link->link = $data->url;
                    $link->description = $data->comment;
                    $link->user_id = Auth::guard('web')->user()->id;
                    $link->save();
                }
            }
        }

        $documents = (array) $request->documents;
        new_address_document::where('comment_id', $comment->id)->delete();
        foreach ($documents as $value) {
            $doc = new new_address_document();
            $doc->comment_id = $comment->id;
            $value = Document::where('unique_id', $value)->first();
            if (!$value) {
                continue;
            }
            $doc->document_id = $value->id;
            $doc->type = $value->isImage() ? 'image' : 'document';
            $doc->save();
        }

        if (isset($request->audios)) {
            new_address_document::where('comment_id', $comment->id)->delete();
            foreach ($request->audios as $audio) {
                $doc = new new_address_document();
                $doc->comment_id = $comment->id;
                $audio = Document::where('unique_id', $audio)->first();
                if (!$audio) {
                    continue;
                }
                $doc->document_id = $audio->id;

                $doc->type = 'audio';
                $doc->save();
            }
        }

        return redirect()
            ->route('head_office.address.edit', $request->address_id)
            ->with('success', 'Comment saved successfully.');
    }

    public function delete_comment(Request $request, $id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $comment = new_contact_comments::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
        if (!$comment) {
            abort(404);
        }
        $contact_id = $comment->contact_id;
        $comment->delete();
        return redirect()->route('head_office.contacts.edit', $contact_id)->with('success_message', 'Comment deleted successfully.');
    }
    public function delete_address_comment(Request $request, $id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $comment = address_comments::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
        if (!$comment) {
            abort(404);
        }
        $contact_id = $comment->address_id;
        $comment->delete();
        return redirect()->route('head_office.address.edit', $contact_id)->with('success_message', 'Comment deleted successfully.');
    }


    public function delete_comment_location(Request $request, $id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $user = Auth::guard('web')->user();
        $comment = location_comments::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
        if (!$comment) {
            abort(404);
        }
        $comment->delete();
        return redirect()->back()->with('success_message', 'Comment deleted successfully.');
    }

    public function location_info_update(Request $request){
    $validtor = Validator::make($request->all(), [
        'ho_location_id' => 'required|exists:head_office_locations,id',
        'logo_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'username' => 'required|string|max:100|min:4|regex:/^\S*$/',
        'registration_no' => 'required|numeric|digits_between:5,100',
        'location_code' => 'required|numeric|digits_between:3,100',
        'telephone_no' => 'required|string|max:100|min:5',
        'email' => 'required|email',
        // 'emails.*' => 'required|email|unique:locations,email|unique:locations,emails',
        'location_type_id' => 'required|exists:location_types,id',
        'location_pharmacy_type_id' => 'required|exists:location_pharmacy_types,id',
        'location_regulatory_body_id' => 'required|exists:location_regulatory_bodies,id',
        'emails.*' => [
        'required',
        'email',
        function ($attribute, $value, $fail) {
            $existsInEmail = DB::table('locations')
                ->where('email', $value)
                ->exists();

            $existsInEmailsJson = DB::table('locations')
                ->whereRaw("JSON_CONTAINS(emails, '\"$value\"')")
                ->exists();

            if ($existsInEmail || $existsInEmailsJson) {
                $fail("The email $value is already taken.");
            }
        },
    ],
    ],[
        'location_code.digits_between' => 'Location Id must be at least 3 characters',
        'location_code.required' => 'Location Id is required',
    ]);
    if($validtor->fails()){
        return back()->with('error',$validtor->errors()->first());
    }
    $head_office = Auth::guard('web')->user()->selected_head_office;
    $ho_location = $head_office->locations->find($request->ho_location_id);
    if(!isset($ho_location)){
        return back()->with('error','No location found');
    }
    $location = $ho_location->location;
    $setting = $ho_location->org_settings();
    if ($request->hasFile('logo_file') && isset($setting)) {
        $request->file('logo_file')->move(public_path('data_images/setting/logo'), $setting->id . '.png');
    }
    $oldUsername = $location->username;
    $oldEmail = $location->email;

    if ($request->email !== $oldEmail && \DB::table('locations')->where('email', $request->email)->exists()) {
        return back()->with('error', 'The email address is already in use.');
    }
    if (!empty($request->emails)) {
        foreach ($request->emails as $email) {
            if (\DB::table('locations')->where('email', $email)->where('id', '!=', $location->id)->exists()) {
                return back()->with('error', 'The provided emails is already in use.');
            }
        }
    }
    $location->username = $request->username;
    $location->registration_no = $request->registration_no;
    $location->location_code = $request->location_code;
    $location->telephone_no = $request->telephone_no;
    $location->location_type_id = $request->location_type_id;
    $location->location_pharmacy_type_id = $request->location_pharmacy_type_id;
    $location->location_regulatory_body_id = $request->location_regulatory_body_id;
    $location->email = $request->email;
    $location->phones = json_encode($request->phones);
    $location->email_notes = json_encode($request->email_note);
    $location->phone_notes = json_encode($request->phone_note);


    $primaryEmailIndex = $request->primary_email;
    $emails = $request->emails;
    if (isset($primaryEmailIndex) && isset($emails[$primaryEmailIndex])) {
        $location->email = $emails[$primaryEmailIndex];
        $emails[$primaryEmailIndex] = $request->email ?? $location->email;
        $location->emails = json_encode($emails);
    }else{

    }
    $location->emails = json_encode($emails);

    $primaryPhoneIndex = $request->primary_phone;
    $phones = $request->phones;

    if (isset($primaryPhoneIndex) && isset($phones[$primaryPhoneIndex])) {
        $location->telephone_no = $phones[$primaryPhoneIndex];
        $phones[$primaryPhoneIndex] = $request->telephone_no ?? $location->telephone_no;
        $location->phones = json_encode($phones);
    }
    

    $location->save();
    if ($oldUsername !== $request->username) {
        Mail::to($location->email)->send(new LocationInfoUpdated(Auth::user(), 'username', $oldUsername, $request->username));
    }
    if ($oldEmail !== $request->email) {
        Mail::to($request->email)->send(new LocationInfoUpdated(Auth::user(), 'email', $oldEmail, $request->email));
    }

    return back()->with('success','Location updated successfully');

}
    public function updateAddress(Request $request) 
    {
    $request->validate([
        'ho_location_id' => 'required|exists:head_office_locations,id',
        'address_line1' => 'nullable|string|max:100',
        'address_line2' => 'nullable|string|max:50',
        'address_line3' => 'nullable|string|max:50',
        'town' => 'nullable|string|max:50',
        'county' => 'nullable|string|max:50',
        'country' => 'required|in:Northern Ireland,Republic of Ireland', // Validate selected country
        'postcode' => 'nullable|string|max:20',
    ]);

    $head_office = Auth::guard('web')->user()->selected_head_office;
    $ho_location = $request['ho_location_id'];
    // $ho_location = $head_office->locations->find($request->ho_location_id);

    if (!$ho_location) {
        return back()->with('error', 'No location found for the given ID under this head office.');
    }

    // yaha prr DB::table use iss lie kia Q k IDK same upar waly function ki trh krny sai location nahi mill rahi thi 

    // $location = $ho_location->location;
    $location = DB::table('locations')
        ->where('id', $ho_location)
        ->first();

    if (!$location) {
        return back()->with('error', 'Location not found.');
    }

    DB::table('locations')
        ->where('id', $ho_location)
        ->update([
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'address_line3' => $request->address_line3,
            'town' => $request->town,
            'county' => $request->county,
            'country' => $request->country,
            'postcode' => $request->postcode,
        ]);

    return back()->with('success', 'Address updated successfully!');
    }
    
    public function users_list(Request $request)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $q = $request->query('q');
        $head_office_users = HeadOfficeUser::where('head_office_id', $headOffice->id)->get('user_id');
        if (!count($head_office_users)) {
            return response()->json([]);
        }
        $ids = [];
        foreach ($head_office_users as $u) {
            $ids[] = $u->user_id;
        }
        $users = User::whereIn('id', $ids);
        $new_contacts = new_contacts::where('head_office_id', $headOffice->id);
        if (!empty($q)) {
            $users->where(function ($query) use ($q) {
                $query->orWhere('first_name', 'LIKE', '%' . $q . '%');
                $query->orWhere('surname', 'LIKE', '%' . $q . '%');
            });
            $new_contacts = $new_contacts->where('name', 'LIKE', '%' . $q . '%');
        }
        $users = $users->get();
        $new_contacts = $new_contacts->get();
        if (!count($users)) {
            return response()->json([]);
        }
        $to_return = [];
        foreach ($users as $u) {
            $to_return[] = [
                'id' => $u->id,
                'key' => $u->name,
                'value' => $u->name,
                'template' => '<a href="#"><input type="hidden" name="users[]" value="' . $u->id . '">@' . $u->name . '</a>',
            ];
        }

        foreach ($new_contacts as $u) {
            $to_return[] = [
                'id' => $u->id,
                'key' => $u->name,
                'value' => $u->name,
                'template' => '<a href="' . route('head_office.contacts.edit', $u->id) . '"><input type="hidden" name="users[]" value="' . $u->id . '">@' . $u->name . '</a>',
            ];
        }
        return response()->json($to_return);
    }

    public function delete_contact($id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        $contact = $head_office->new_contacts()->find($id);
        if (!$contact) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Contact not found');
        }
        if( $contact->is_deleted == true && $contact->is_archive == false ){
            $contact->delete();
        }else{
            $contact->is_deleted = true;
            $contact->is_archive = false;
            $contact->save();
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'Contact deleted successfully');
    }

    public function delete_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }

        $delete_ids = json_decode($request->contact_ids, true);

        foreach ($delete_ids as $id) {
            $contact = new_contacts::find($id);
            if (!$contact) {
                continue;
            }
            if( $contact->is_deleted == true && $contact->is_archive == false ){
                $contact->delete();
            }else{
                $contact->is_deleted = true;
                $contact->is_archive = false;
                $contact->save();
            }
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'Contacts deleted successfully');
    }
    public function restore_contact_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }

        $delete_ids = json_decode($request->contact_ids, true);

        foreach ($delete_ids as $id) {
            $contact = new_contacts::find($id);
            if (!$contact) {
                continue;
            }
            if( $contact->is_deleted == true ){
                $contact->is_deleted = false;
                $contact->is_archive = false;
                $contact->save();
            }else{
                return redirect()->route('head_office.contacts.index')->with('success', 'Invalid contacts data provided!');
            }
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'Contacts deleted successfully');
    }
    public function archive_contact_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }

        $delete_ids = json_decode($request->contact_ids, true);

        foreach ($delete_ids as $id) {
            $contact = new_contacts::find($id);
            if (!$contact) {
                continue;
            }
            $contact->is_archive = true;
            $contact->save();
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'Contacts archived successfully');
    }

    public function unarchive_contact_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }

        $delete_ids = json_decode($request->contact_ids, true);

        foreach ($delete_ids as $id) {
            $contact = new_contacts::find($id);
            if (!$contact) {
                continue;
            }
            $contact->is_archive = false;
            $contact->save();
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'Contacts archived successfully');
    }

    public function assign_tags_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        $contact_ids = json_decode($request->contact_ids, true);
        $tags = $request->tags;

        foreach ($contact_ids as $contact_id) {
            $contact = new_contacts::find($contact_id);
            if (!$contact) {
                continue;
            }
            foreach ($tags as $tag) {
                $tag_to_contact = tag_to_contacts::where('contact_id', $contact_id)->where('tag_id', $tag)->first();
                if ($tag_to_contact) {
                    continue;
                }
                $tag_to_contact = new tag_to_contacts();
                $tag_to_contact->contact_id = $contact_id;
                $tag_to_contact->tag_id = $tag;
                $tag_to_contact->save();
            }
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'Tags assigned successfully');
    }

    public function assign_users_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        $contact_ids = json_decode($request->contact_ids, true);
        $assigns = $request->assigns;

        foreach ($contact_ids as $contact_id) {
            $contact = new_contacts::find($contact_id);
            if (!$contact) {
                continue;
            }
            foreach ($assigns as $assign) {
                $user_to_contact = user_to_contacts::where('contact_id', $contact_id)->where('head_office_user_id', $assign)->first();
                if ($user_to_contact) {
                    continue;
                }
                $user_to_contact = new user_to_contacts();
                $user_to_contact->contact_id = $contact_id;
                $user_to_contact->head_office_user_id = $assign;
                $user_to_contact->save();
            }
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'Users assigned successfully');
    }

    public function assign_group_bulk(Request $request)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        $contact_ids = json_decode($request->contact_ids, true);
        $group_id = $request->group_id;

        foreach ($contact_ids as $contact_id) {
            foreach ($request->groups as $group) {
                $contact_to_group = contact_to_groups::where('contact_id', $contact_id)->where('group_id', $group)->first();
                if ($contact_to_group) {
                    continue;
                }
                $contact_to_group = new contact_to_groups();
                $contact_to_group->contact_id = $contact_id;
                $contact_to_group->group_id = $group;
                $contact_to_group->save();
            }
        }

        return redirect()->route('head_office.contacts.index')->with('success', 'Group assigned successfully');
    }
    public function create_address(Request $request, $id = null)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        if (isset($id)) {
            $address = $head_office->new_contact_addresses()->find($id);
            if (!$address) {
                return redirect()->route('head_office.contacts.index')->with('error', 'address not found');
            }
        } else {
            $address = new new_contact_addresses();
        }

        $file = $request->image;
        if (!isset($file) && !isset($id) && !isset($request->avatar)) {
            $avatar = 'logo_blue.png';
        } elseif (isset($request->avatar) && !isset($file)) {
            $avatar = $request->avatar;
        } else {
            try {
                if (!is_dir('v2')) {
                    mkdir('v2', 0777, true);
                }

                $avatar = Str::uuid() . '.jpg';
                $destinationPath = 'v2/' . $avatar;
                base64Convert::save_base64($file, $destinationPath);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        if (!empty($request->assigns)) {
            foreach ($request->assigns as $assigne) {
                $user_to_contact = new user_to_addresses();
                $user_to_contact->head_office_user_id = $assigne;
                $user_to_contact->address_id = $address->id;
                $user_to_contact->save();
            }
        }

        $address->avatar = $avatar;
        $address->name = $request->name;
        $address->address_tag = $request->address_tag;
        $address->address = $request->address;
        $address->head_office_id = $head_office->id;
        $address->save();
        if (isset($id)) {
            return redirect()->route('head_office.address.edit', $id)->with('success', 'address updated successfully');
        }
        return redirect()->route('head_office.contacts.index')->with('success', 'address created successfully');
    }

    public function delete_address(Request $request, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        if (!isset($head_office)) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Please select head office first');
        }
        $address = $head_office->new_contact_addresses()->find($id);
        if (!$address) {
            return redirect()->route('head_office.contacts.index')->with('error', 'Address not found');
        }
        $address->delete();
        return redirect()->route('head_office.contacts.index')->with('success', 'Address deleted successfully');
    }

    static public function calculateFuzzySimilarity($string1, $string2)
{
    $fuzz = new Fuzz();

    $similarityPercentage = $fuzz->tokenSetRatio($string1, $string2);

    return $similarityPercentage;
}
    
    static public function findPotentialMatches($contacts, $customThreshold = 70)
    {
        $potentialMatches = [];
        $user = Auth::guard('web')->user();
        if(!isset($user)){
            return;
        }
        $head_office = $user->selected_head_office;
    
        foreach ($contacts as $contact) {
            $dob = $contact->date_of_birth ? Carbon::parse($contact->date_of_birth)->format('Y-m-d') : null;
            $fullName1 = $contact->name;
            foreach ($contacts as $otherContact) {
                $dob2 = $otherContact->date_of_birth ? Carbon::parse($otherContact->date_of_birth)->format('Y-m-d') : null;
                if ($contact->id !== $otherContact->id && $dob2 == $dob) {
                    $fullName2 = $otherContact->name;
    
                    $similarityPercentage = self::calculateFuzzySimilarity($fullName1, $fullName2);   
                    if ($similarityPercentage >= $customThreshold) {
                        $mainContact = $contact->id < $otherContact->id ? $contact : $otherContact;
                    $secondaryContact = $contact->id < $otherContact->id ? $otherContact : $contact;
                    if($similarityPercentage >= ($head_office->percentage_merge ?? 70)){
                        self::mergeContacts($mainContact, $secondaryContact);
                    }
                    // Merge attributes of the secondary contact into the main contact
                        $potentialMatches[] = [
                            'contact_id' => $contact->id,
                            'matching_contact_id' => $otherContact->id,
                            'similarity_percentage' => $similarityPercentage
                        ];
                    }
                }
            }
        }
        return $potentialMatches;
    }
    
    static public function mergeContacts($mainContact, $secondaryContact)
{
    $mainContact->name = $mainContact->name ?: $secondaryContact->name;
    
    $mainContact->date_of_birth = $mainContact->date_of_birth ?: $secondaryContact->date_of_birth;
    $mainContact->nhs_no = $mainContact->nhs_no ?: $secondaryContact->nhs_no;
    $mainContact->gender = $mainContact->gender ?: $secondaryContact->gender;
    $mainContact->ethnicity = $mainContact->ethnicity ?: $secondaryContact->ethnicity;
    $mainContact->religion = $mainContact->religion ?: $secondaryContact->religion;
    $mainContact->marital_status = $mainContact->marital_status ?: $secondaryContact->marital_status;
    $mainContact->profession = $mainContact->profession ?: $secondaryContact->profession;
    $mainContact->registration_no = $mainContact->registration_no ?: $secondaryContact->registration_no;
    $mainContact->work_emails = $mainContact->work_emails ?: $secondaryContact->work_emails;

    if ($secondaryContact->work_emails) {
        $mainContact->work_emails = json_encode(array_merge(
            json_decode($mainContact->work_emails, true) ?: [],
            json_decode($secondaryContact->work_emails, true) ?: []
        ));
    }

    $mainContact->work_mobiles = $mainContact->work_mobiles ?: $secondaryContact->work_mobiles;
    if ($secondaryContact->work_mobiles) {
        $mainContact->work_mobiles = json_encode(array_merge(
            json_decode($mainContact->work_mobiles, true) ?: [],
            json_decode($secondaryContact->work_mobiles, true) ?: []
        ));
    }


    // $mainContact->social_media = $mainContact->social_media ?: $secondaryContact->social_media;
    // if ($secondaryContact->social_media) {
    //     $mainContact->social_media = json_encode(array_merge(
    //         json_decode($mainContact->social_media, true) ?: [],
    //         json_decode($secondaryContact->social_media, true) ?: []
    //     ));
    // }

    $mainContact->other_link = $mainContact->other_link ?: $secondaryContact->other_link;
    if ($secondaryContact->other_link) {
        $mainContact->other_link = json_encode(array_merge(
            json_decode($mainContact->other_link, true) ?: [],
            json_decode($secondaryContact->other_link, true) ?: []
        ));
    }

    $mainContact->save();

    $secondaryContact->delete();
}



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\new_contacts  $new_contacts
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\new_contacts  $new_contacts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, new_contacts $new_contacts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\new_contacts  $new_contacts
     * @return \Illuminate\Http\Response
     */
    public function destroy(new_contacts $new_contacts)
    {
        //
    }

    public function contact_view_timeline(Request $request,$id){
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $new_contact  = $head_office->new_contacts()->find($id);
        if(!isset($new_contact)){
            abort(404);
        }
        $user_to_contacts = $new_contact->user_to_contacts;
        $user_to_case_handlers = $user_to_contacts->pluck('case_handler')->flatten()->unique('case_id')->sortByDesc('created_at');

        

        $records = $user_to_case_handlers->pluck('case')
        ->groupBy(function($record) {
            return Carbon::parse($record->created_at)->format('Y-m-d');
        });
        $cases = $user_to_case_handlers->pluck('case');

        if ($request->filled('start_date') || $request->filled('end_date')) {
            if(!$request->input('start_date')){
                $startDate = $cases->sortBy('created_at')->first()->created_at;
                
            }else{
                $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            }
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        
            // Filter the cases based on the date range
            $records = $user_to_case_handlers->pluck('case')->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy(function($record) {
            return Carbon::parse($record->created_at)->format('Y-m-d');
        });
            $cases = $user_to_case_handlers->pluck('case')->whereBetween('created_at', [$startDate, $endDate]);
        }

        return view('head_office.contacts.contact_timeline', compact('user_to_contacts', 'new_contact','user_to_case_handlers','records','cases'));
    }
    public function contact_view_intelligence(Request $request,$id){
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $new_contact  = $head_office->new_contacts()->find($id);
        if(!isset($new_contact)){
            abort(404);
        }
        $user_to_contacts = $new_contact->user_to_contacts;
        $user_to_case_handlers = $user_to_contacts->pluck('case_handler')->flatten()->unique('case_id')->sortByDesc('created_at');

        

        $records = $user_to_case_handlers->pluck('case')
        ->groupBy(function($record) {
            return Carbon::parse($record->created_at)->format('Y-m-d');
        });
        $cases = $user_to_case_handlers->pluck('case');

        $groupedCases = $cases->groupBy(function($case) {
            return Carbon::parse($case->created_at)->format('Y-m-d'); // Group by date
        });
        
        // Prepare data for the chart
        $chartData = [];
        $incidentTypes = [];
        
        // Count occurrences of each incident type by date
        foreach ($groupedCases as $date => $cases) {
            foreach ($cases as $case) {
                $incidentType = $case->incident_type;
                
                // Initialize if not set
                if (!isset($chartData[$date])) {
                    $chartData[$date] = [];
                }
                if (!isset($chartData[$date][$incidentType])) {
                    $chartData[$date][$incidentType] = 0; // Initialize count
                }
                
                // Increment the count
                $chartData[$date][$incidentType]++;
                
                // Collect incident types for dataset creation
                if (!in_array($incidentType, $incidentTypes)) {
                    $incidentTypes[] = $incidentType;
                }
            }
        }
        
        // Define Chart.js colors
        $chartColors = [
            'rgba(255, 99, 132, 1)', // Red
            'rgba(54, 162, 235, 1)', // Blue
            'rgba(255, 206, 86, 1)', // Yellow
            'rgba(75, 192, 192, 1)', // Teal
            'rgba(153, 102, 255, 1)', // Purple
            'rgba(255, 159, 64, 1)', // Orange
            'rgba(255, 99, 132, 0.2)', // Red transparent
            'rgba(54, 162, 235, 0.2)', // Blue transparent
            'rgba(255, 206, 86, 0.2)', // Yellow transparent
            'rgba(75, 192, 192, 0.2)', // Teal transparent
            'rgba(153, 102, 255, 0.2)', // Purple transparent
            'rgba(255, 159, 64, 0.2)', // Orange transparent
        ];
        
        // Prepare datasets for Chart.js
        $datasets = [];
        foreach ($incidentTypes as $index => $type) {
            $dataPoints = [];
            foreach ($groupedCases as $date => $cases) {
                $dataPoints[] = $chartData[$date][$type] ?? 0; // Use zero if not set
            }
            
            // Use the color from the chartColors array, cycling through if there are more types than colors
            $colorIndex = $index % count($chartColors); // Cycle through available colors
            $datasets[] = [
                'label' => $type,
                'data' => $dataPoints,
                'borderColor' => $chartColors[$colorIndex], // Use color for line
                // 'backgroundColor' => $chartColors[$colorIndex].'0.2', // Use transparent color for area
                'fill' => false, // Fill under the line
            ];
        }
        
        // Prepare labels (dates) for the chart
        $labels = array_keys($chartData);


        return view('head_office.contacts.contact_intelligence', compact('user_to_contacts', 'new_contact','user_to_case_handlers','records','cases','labels','datasets'));
    }
    public function contact_view_matchs(Request $request,$id){
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $new_contact  = $head_office->new_contacts()->find($id);
        if(!isset($new_contact)){
            abort(404);
        }
        $matching_contacts = $new_contact->get_all_matching_contacts();
        


        return view('head_office.contacts.contact_matchs', compact('matching_contacts','new_contact'));
    }

    public function randomColor() {
        $letters = '0123456789ABCDEF';
        $color = '#';
        for ($i = 0; $i < 6; $i++) {
            $color .= $letters[rand(0, 15)];
        }
        return $color;
    }
}
