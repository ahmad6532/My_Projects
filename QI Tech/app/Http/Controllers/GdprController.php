<?php

namespace App\Http\Controllers;

use App\Models\DefaultCaseStageTask;
use App\Models\Forms\Form;
use App\Models\GdprTag;
use App\Models\GdprTagRemoveAction;
use App\Models\HeadOffice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GdprController extends Controller
{
    function index()
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $gdprs = $head_office->gdprs;
        $gdprs_ids_array = $gdprs->pluck('id')->toArray();
        $forms = $head_office->be_spoke_forms;
        $gdpr_linked_forms = [];
        if (!empty($forms)) {
            foreach ($forms as $record) {
                if ($record->form_json) {
                    $questionsJson = json_decode($record->form_json, true);
                    if (!empty($questionsJson['pages']) && count($questionsJson['pages']) > 0) {
                        foreach ($questionsJson['pages'] as $page) {
                            $page_questions = [];
                            $page_gdpr_ids = [];
                            if ($page['items'] && count($page['items']) > 0) {
                                foreach ($page['items'] as $item) {
                                    if (isset($item['label'], $item['gdpr']) && in_array($item['gdpr'], $gdprs_ids_array)) {
                                        $label = $item['label'];
                                        $gdpr_id = $item['gdpr'];
                                        $page_questions[] = ['label' => $label, 'gdpr' => $gdpr_id];
                                        if (!in_array($gdpr_id, $page_gdpr_ids)) {
                                            $page_gdpr_ids[] = $gdpr_id;
                                        }
                                    }
                                }
                            }
                            if (count($page_questions) > 0) {
                                $gdpr_linked_forms[] = ['name' => $record->name, 'questions' => $page_questions, 'gdpr_ids' => $page_gdpr_ids];
                            }
                        }
                    }
                }
            }
        }

        return view('head_office.gdpr', compact('gdprs', 'gdpr_linked_forms'));
    }
    public function save(Request $request, $id = null)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $gdpr = $head_office->gdprs()->find($id);
        if (!$gdpr) {
            $gdpr = new GdprTag();
        }

        $gdpr->tag_name = $request->tag_name;
        $gdpr->head_office_id = $head_office->id;
        $gdpr->save();
        $action = $gdpr->gdpr_tag_remove_action;
        if (!$action) {
            $action = new GdprTagRemoveAction();
        }

        $action->gdpr_tag_id = $gdpr->id;
        $action->remove_after_number = $request->duration_of_access_number;
        $action->remove_after_unit = $request->duration_of_access_type;
        $action->save();

        return redirect()
            ->route('head_office.gdpr.index')
            ->with('success_message', 'GDPR tag' . ' ' . ($id ? ' updated' : 'created') . ' successfully');
    }
    function delete(Request $request, $id)
    {
        $head_office = Auth::guard('web')->user()->selected_head_office;

        $gdpr = $head_office->gdprs()->find($id);
        if (!$gdpr) {
            return back()->with('error', 'Gdpr tag not found');
        }
       
        if (isset($request->move_to_new_tag) && isset($request->new_tag_id)) {
            $forms = $head_office->be_spoke_forms;
            if (!empty($forms)) {
                foreach ($forms as $record) {
                    if ($record->form_json) {
                        $questionsJson = json_decode($record->form_json, true);
                        if (!empty($questionsJson['pages']) && count($questionsJson['pages']) > 0) {
                            foreach ($questionsJson['pages'] as $page) {
                                if ($page['items'] && count($page['items']) > 0) {
                                    foreach ($page['items'] as $item) {
                                        if (isset($item['gdpr'])) {
                                            $gdpr_id = $item['gdpr'];
                                            if ($gdpr_id == $id) {
                                                $questionsJson['pages'][array_search($page, $questionsJson['pages'])]['items'][array_search($item, $page['items'])]['gdpr'] = $request->new_tag_id;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $record->form_json = json_encode($questionsJson, JSON_PRETTY_PRINT);
                        $record->save();
                    }
                }
            }
        }
        $gdpr->gdpr_tag_remove_action->delete();
        $gdpr->delete();
        return redirect()->route('head_office.gdpr.index')->with('success_message', 'Gdpr tag delete successfully');
    }

    public function get_options($id)
    {
        $form = Form::find($id);
        if(!isset($form)){
            $form = DefaultCaseStageTask::find($id);
            if(isset($form)){
                $form = $form->stage->form;
            }
        }
        $headOffice = HeadOffice::find($form->reference_id);
        if (!isset($form) || !isset($headOffice)) {
            return response()->json('unauthorized', 401);
        }
        $gdpr_tags = GdprTag::where('head_office_id', $headOffice->id)
            ->get()
            ->map(function ($tag) {
                return ['id' => $tag->id, 'text' => $tag->tag_name];
            });
        if ($gdpr_tags->isEmpty()) {
            return response()->json(['error' => 'No GDPR tags found'], 404);
        }
        return response()->json($gdpr_tags);
    }
}
