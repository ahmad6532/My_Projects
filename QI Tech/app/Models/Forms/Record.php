<?php

namespace App\Models\Forms;

use App\Models\case_feedback;
use App\Models\form_modification_logs;
use App\Models\FormRecordUpdate;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use App\Models\Headoffices\CaseManager\HeadOfficeLinkedCase;
use App\Models\lfpse_delete;
use App\Models\lfpse_errors;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\RecordDataEditedHistory;
use App\Models\User;
use Carbon\Carbon;

class Record extends Model
{
    use HasFactory;

    protected $table = 'be_spoke_form_records';

    protected $casts = [
        'created_at' => 'datetime',
      ];

    public function data(){
        return $this->hasMany(RecordData::class,'record_id');
    }
    public function getDateAttribute()
    {
        return $this->created_at->format(config('app.dateFormat'));
    }
    public function form(){
        return $this->belongsTo(Form::class,'form_id');
    }
    public function location(){
        return $this->belongsTo(Location::class,'location_id');
    }
    public function createdDate(){
        return $this->created_at->format('d/m/Y h:i a');
    }
    public function createdDateOnlyDate(){
        return $this->created_at->format('d/m/Y');
    }
    public function createdTime(){
        return $this->created_at->format('h:i a');
    }
    public function created_by(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function head_office_linked(){
        return $this->hasOne(HeadOfficeLinkedCase::class,'be_spoke_form_record_id');
    }
    public function recorded_case()
    {
        return $this->hasOne(HeadOfficeCase::class,'last_linked_incident_id');
    }
    public function record_data_edited_values()
    {
        return $this->hasMany(RecordDataEditedHistory::class,'record_id');
    }
    public function updates()
    {
        return $this->hasMany(FormRecordUpdate::class,'be_spoke_form_record_id');
    }
    public function LfpseSubmissions()
    {
        return $this->hasMany(LfpseSubmission::class, 'be_spoke_form_records_id');
    }

    public function get_filled_form()
    {
        $original_form = json_decode($this->form->form_json);
        $form_submission = json_decode($this->json_submission, true);
        // filling mandatory
        $form_submission_values = $form_submission['mandatory_questions'];
        foreach($original_form->pages as $p)
        {
            foreach($p->items as $item)
            {
                if($item->type == 'field')
                {
                    if(array_key_exists($item->id,$form_submission_values))
                        $item->input->value = $form_submission_values[$item->id]['value'];
                }
                else if($item->type == 'group')
                {
                    foreach($item->items as $fitem)
                    {
                        if(array_key_exists($fitem->id,$form_submission_values))
                            $fitem->input->value = $form_submission_values[$fitem->id]['value'];
                    }
                }
            }
        }
        // filling task values
        if(array_key_exists('task_questions', $form_submission) && isset($original_form->task_list))
        {
            $task_values = $form_submission['task_questions'];
            foreach($original_form->task_list->tasks as $task_id => $task)
            {
                foreach($task->pages as $p)
                {
                    foreach($p->items as $item)
                    {
                        if ($item->type == 'field') {
                            if (isset($task_values[$task_id]) && array_key_exists($item->id, $task_values[$task_id])) {
                                $item->input->value = $task_values[$task_id][$item->id]['value'];
                            }
                        } else if ($item->type == 'group') {
                            if (isset($item->items) && is_array($item->items)) {
                                foreach ($item->items as $fitem) {
                                    if (isset($task_values[$task_id]) && array_key_exists($fitem->id, $task_values[$task_id])) {
                                        $fitem->input->value = $task_values[$task_id][$fitem->id]['value'];
                                    }
                                }
                            }
                        }
                         
                    }
                }
            }
        }
        //filling patients if any
        if(array_key_exists('patients', $form_submission))
        {
            $task_values = $form_submission['patients'];
            $original_form->patients = $form_submission['patients'];
        }
        return $original_form;
    }

    public function set_values($request_form)
    {
        // to get actual real values only. This can save a lot of memory ! // can be further optimized !
        //$this->json_submission = json_encode($request_form); // temporarily leaving the optimization !
        //return;
        $involvements = [];
        if(isset($request_form['involvements'])){
            foreach($request_form['involvements'] as $involvement){
                $connectedInvolvment = collect($request_form['involvements'])->firstWhere('id', $involvement['connected_to']);
                $involvements[$involvement['id']] = [
                    'name' => $involvement['name'],
                    'relation_with' => $connectedInvolvment ? $connectedInvolvment['name'] : null,
                    'relation' => $involvement['connected_to_description'],
                    'inverse_relation' => $involvement['connected_from_description'],
                    'connected_fields' => []
                ];
            }
        }
        $mandatory_questions = [];
        foreach($request_form['pages'] as $p)
        {
            foreach($p['items'] as $item)
            {
                if($item['type'] == 'field')
                {
                    $mandatory_questions[$item['id']] = $this->prepare_value($item);
                    // adding contacts involvements
                    if (isset($item['connected_involvement'])) {
                        $involvementId = $item['connected_involvement'];
                        if (isset($involvements[$involvementId]) && !empty($item['connected_involvement_field']) && isset($item['input']['value'])) {
                            $involvements[$involvementId]['connected_fields'][$item['connected_involvement_field']] = $item['input']['value'];
                        }
                    }
                }
                else if($item['type'] == 'group') // it can be section. according to updates. check client side work
                {
                    foreach($item['items'] as $fitem)
                    {
                        $mandatory_questions[$fitem['id']] = $this->prepare_value($fitem);
                    }
                }
            }
        }
        // Task questions. only for Nhs at the moment !
        $task_questions = [];
        if(array_key_exists('task_list', $request_form))
        {
            foreach($request_form['task_list']['tasks'] as $task_id => $task)
            {
                foreach($task['pages'] as $p)
                {
                    foreach($p['items'] as $item)
                    {
                        if($item['type'] == 'field')
                        {
                            $task_questions[$task_id][$item['id']] = $this->prepare_value($item);
                        }
                        else if($item['type'] == 'group') // it can be section. according to updates. check client side work
                        {
                            foreach($item['items'] as $fitem)
                            {
                                $task_questions[$task_id][$fitem['id']] = $this->prepare_value($fitem);
                            }
                        }
                    }
                }
            }
        }
        // Patient Information //
        $patients = [];
        if(array_key_exists('patients', $request_form))
        {
            foreach($request_form['patients'] as $patient)
                $patients[] = $patient['data'];
        }
        $values = ["mandatory_questions" => $mandatory_questions, "task_questions" => $task_questions, 'patients' => $patients,'involvements' => $involvements];
        $this->json_submission = json_encode($values);
    }

    private function prepare_value($field)
    {
        //additional attributes are pending such as cards / contacts etc.
        $value = null;
        $conditions = [];
        if(array_key_exists('value', $field['input']))
            $value = $field['input']['value'];
        if(array_key_exists('records', $field['input'])){
            $value = $field['input']['records'];
        }
        if(array_key_exists('conditions', $field))
            $conditions = $field['conditions'];
        return ["label" => $field['label'], "order"=>$field['order'], "value"=>$value, "conditions" => $conditions];
    }

    public function getCaseFeedbacks(){
        $cases = case_feedback::where('location_id', $this->location_id)->where('is_feedback_location',true)->get();
        $filteredCases = [];
        if(isset($cases) && count($cases) != 0){
            foreach($cases as $case){
                $case_ids = json_decode($case->case_ids,true);
                if(!empty($this->recorded_case->id)&&in_array($this->recorded_case->id,$case_ids)){
                    $filteredCases[] = $case;
                }
            }
        }
        return $filteredCases;
    }

    public function all_linked_records(){
        $allRecords = collect();
        $queue = collect([$this]); // Start with the current record in the queue
    
        while ($queue->isNotEmpty()) {
            $currentRecord = $queue->shift(); // Get and remove the first record from the queue
    
            // Find all records linked to the current record
            $childRecords = Record::where('record_id', $currentRecord->id)->get();
            
            // Merge the found child records into the main collection
            $allRecords = $allRecords->merge($childRecords);
            
            // Add all child records to the queue to check for their children
            $queue = $queue->merge($childRecords);
        }

        $sortedRecords = $allRecords->sortBy('date');

        return $sortedRecords->count() > 0 ? $sortedRecords : collect([$this]);
    }
    
    
    public function latest_lfpse_record()
    {
        $records = Record::where('record_id', $this->id)->latest()->first();
        return $records;
    }

    public function first_lfpse_record()
    {
        $currentRecord = $this;
    
    while ($currentRecord->record_id) {
        $currentRecord = Record::where('id', $currentRecord->record_id)->oldest()->first();
        if (!$currentRecord) {
            break;
        }
    }

    return $currentRecord;
    }

    public function errors(){
        return $this->hasMany(lfpse_errors::class,'record_id');
    }
    public function lfpse_deletes(){
        return $this->hasOne(lfpse_delete::class,'record_id');
    }

    public function compare_form_values($modified_form,$original_form=null)
{
    if($original_form == null){
        $original_form = $this->json_submission;
    }
    $modified_form = json_decode($modified_form, true);
    $original_form = json_decode($original_form, true);
    $original_form_json = json_decode($this->form->form_json, true);
    $differences = [];

    // Helper function to compare two arrays of questions
    $compare_questions = function ($original_questions, $modified_questions) use (&$differences) {
        foreach ($original_questions as $id => $original_value) {
            if (array_key_exists($id, $modified_questions)) {
                $modified_value = $modified_questions[$id];

                // Compare values and store differences
                if ($original_value !== $modified_value) {
                    $differences[$id] = [
                        'original' => $original_value,
                        'modified' => $modified_value,
                    ];
                }
            }
        }
    };

    // Compare mandatory questions
    if (array_key_exists('mandatory_questions', $original_form) && array_key_exists('mandatory_questions', $modified_form)) {
        $compare_questions($original_form['mandatory_questions'], $modified_form['mandatory_questions']);
    }

    // Compare task questions
    if (array_key_exists('task_questions', $original_form) && array_key_exists('task_questions', $modified_form)) {
        foreach ($original_form['task_questions'] as $task_id => $original_task_questions) {
            if (array_key_exists($task_id, $modified_form['task_questions'])) {
                $modified_task_questions = $modified_form['task_questions'][$task_id];
                $compare_questions($original_task_questions, $modified_task_questions);
            }
        }
    }

    // Compare patient information if needed
    if (array_key_exists('patients', $original_form) && array_key_exists('patients', $modified_form)) {
        foreach ($original_form['patients'] as $index => $original_patient) {
            if (isset($modified_form['patients'][$index]) && $original_patient !== $modified_form['patients'][$index]) {
                $differences["patient_$index"] = [
                    'original' => $original_patient,
                    'modified' => $modified_form['patients'][$index],
                ];
            }
        }
    }

    return $differences;
}

public function get_question_details_by_id($question_id,$parent_record_id=null,$value)
{
    if($parent_record_id != null){
        $parent_record = Record::find($parent_record_id);
        $original_form = json_decode($parent_record->raw_form);
    }else{
        $original_form = json_decode($this->form->form_json);

    }
    // Helper function to search for the question in the form pages or tasks
    $search_question = function ($items) use ($question_id) {
        foreach ($items as $item) {
            if ($item->type == 'field' && $item->id == $question_id) {
                return $item;
            } else if ($item->type == 'group' && isset($item->items)) {
                foreach ($item->items as $fitem) {
                    if ($fitem->type == 'field' && $fitem->id == $question_id) {
                        return $fitem;
                    }
                }
            }
        }
        return null;
    };

    // Search in form pages
    foreach ($original_form->pages as $p) {
        $question = $search_question($p->items);
        if ($question) {
            break;
        }
    }
    // If not found, search in task questions
    if (!$question && isset($original_form->task_list)) {
        foreach ($original_form->task_list->tasks as $task) {
            foreach ($task->pages as $p) {
                $question = $search_question($p->items);
                if ($question) {
                    break 2;
                }
            }
        }
    }

    // If not found, return null
    if (!$question) {
        return null;
    }

    // Check the field type and return the label or options
    if (in_array($question->input->type, ['select', 'checkbox', 'radio'])) {
        $results = []; // Store all matching results
    
        if (isset($question->input->options)) {
            foreach ($question->input->options as $option) {
                // Normalize value to an array for consistent checks
                $valuesToCheck = is_array($value) ? $value : [$value];
    
                // Debug
    
                if (isset($option->val) && in_array($option->val, $valuesToCheck)) {
                    $results[] = $option->val;
                } elseif (isset($option->code) && in_array($option->code, $valuesToCheck)) {
                    $results[] = $option->val; // Return val even if matched on code
                }
            }
    
            return $results; // Return all matching values after processing all options
        } else {
            return []; // No options available
        }
    } else {
        return $value; // Direct return for non-select types
    }
    
    
}

public function get_modifications($company=false)
{
    $all_records = $this->all_linked_records();
    $all_records = $all_records->merge(collect([$this]));
    $all_modifications = [];

    foreach ($all_records as $record) {
        $modifications = form_modification_logs::where('parent_record_id', $record->id)
            ->orderBy('created_at', 'asc');

        if($company == false){
            $modifications = $modifications->where('is_company',false)->get();
        }else{
            $modifications = $modifications->where('is_company',true)->get();
        }
        foreach ($modifications as $modification) {
            $all_modifications[] = $modification;  // Convert each modification to an array and append it
        }
    }

    return $all_modifications;
}



}
