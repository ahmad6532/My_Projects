<?php

namespace App\Http\Controllers;

use App\Models\DefaultDocument;
use App\Models\DefaultDocumentDocument;
use App\Models\Document;
use App\Models\form_default_links;
use App\Models\Forms\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DefaultDocumentController extends Controller
{
    public function store(Request $request)
{
    if (!$request->has('form_id') || !$request->form_id) {
        return back()->with('error_message', 'Please save the form first.');
    }
    $form = Form::findOrFail($request->form_id);
    if ($request->has('default_document_id')) {
        $task = $form->defaultDocuments()->find($request->default_document_id);
        $task->updated_by = Auth::guard('web')->user()->id;
    } else{
        $task = new DefaultDocument();
        $task->uploaded_by = Auth::guard('web')->user()->id;
        if($request->has('case_log')){
            $task->from_case_log = true;
        }
    }
    $task->be_spoke_form_id = $request->form_id;
    $task->title = $request->title;
    $task->description = $request->description;
    $task->save();

    $documents = (array) $request->documents;
    DefaultDocumentDocument::where('default_document_id', $task->id)->delete();
    foreach ($documents as $value) {
        $doc = new DefaultDocumentDocument();
        $doc->default_document_id = $task->id;
        $value = Document::where('unique_id', $value)->first();
        if (!$value) {
            continue;
        }
        $doc->document_id = $value->id;
        $doc->type = ($value->isImage()) ? 'image' : 'document';
        $doc->save();
    }

    return back()->with('success_message', 'Document created successfully');
}

    public function delete($id){
        $doc = DefaultDocument::find($id);
        if(!$doc){
            return redirect()->back()->with('error','document not found!');
        }
        $doc->delete();
        return redirect()->back()->with('success','document deleted!');
    }

    public function store_links(Request $request){
        $user = Auth::guard('web')->user();
        $ho = Auth::guard('web')->user()->selected_head_office;
        $ho_user = $user->getHeadOfficeUser($ho->id);
        $link = form_default_links::find($request->default_link_id);
        if(!isset($link)){
            $link = new form_default_links();
            $link->link = $request->link;
            $link->title = $request->title;
            $link->link_description = $request->link_description;
            $link->form_id = $request->form_id;
            $link->uploaded_by = $ho_user->id;
            $link->save();
            return redirect()->back()->with('success','link saved!');
        }else{
            $link->link = $request->link;
            $link->title = $request->title;
            $link->link_description = $request->link_description;
            $link->form_id = $request->form_id;
            $link->updated_by = $ho_user->id;
            $link->save();
            return redirect()->back()->with('success','link updated!');
        }
    }

    public function delete_link($id){
        $link = form_default_links::find($id);
        if(isset($link)){
            $link->delete();
            return redirect()->back()->with('success','link deleted!');
        }else{
            return redirect()->back()->with('error','link not found!');
        }
    }
    public function activate_link($id){
        $link = form_default_links::find($id);
        if(isset($link)){
            $link->is_active = !$link->is_active ;
            $link->save();
            return redirect()->back()->with('success','link updated!');
        }else{
            return redirect()->back()->with('error','link not found!');
        }
    }
}
