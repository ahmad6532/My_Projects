<?php

namespace App\Http\Livewire;

use App\Models\DefaultCaseStage;
use App\Models\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CaseLogic extends Component
{   public $stageId,$ruleIndex,$ruleType,$is_external;
    public $startedTableData = [];
    public $stage;
    public $isUser = true;
    public $user;
    public $title,$selectedStartedRule,$selectedEmailType=1,$users=[],$allowPrevStage = false,$allowFutureStage = false,$addUser;
    public $message;
    public $editData = [];
    public $userSelected = [];
    protected $listeners = ['updateStageId' => 'updateStageId','reloadComponent' => 'reloadComponent'];
    protected $rules = [
        'title' => 'required', 
        'selectedStartedRule' => 'required',
    ];
    public function mount(){
        $this->user = Auth::guard('web')->user()->selected_head_office;
    }
    public function changeUserNames($value){
        $this->users = [];
        if($value == 1){
            $this->isUser = true;
        }else if($value == 2){
            $this->isUser = false;
            
        }
    }
    public function reloadComponent(){
        $this->render();
        $this->reset();
        $this->user = Auth::guard('web')->user()->selected_head_office;
    }
    public function changeUserNames2($value){

        if($value == 1){
            $this->isUser = true;
        }else if($value == 2){
            $this->isUser = false;
            
        }
    }

    public function saveRecord(){
        
        if(isset($this->startedTableData)){
            $jsonData = $this->startedTableData;
        }else{
            $jsonData = [
                'started' => [],
                'completed' => []
            ];
        }
        $recordType =  $this->ruleType == 'c' ? 'completed' : 'started';

        if($this->selectedStartedRule == 1){
            $rule1 = [
                'condition_title' => $this->title ,
                'condition_type' => $this->selectedStartedRule,
                'user_profiles' => $this->users,
                'view_previous_stages' => $this->allowPrevStage,
                'view_future_stages' => $this->allowFutureStage,
                'add_user' => $this->addUser
            ];
            $jsonData[$recordType][] = $rule1;
        }
        elseif($this->selectedStartedRule == 2){
            $rule2 = [
                'condition_title' => $this->title ,
                'condition_type' => $this->selectedStartedRule,
                'users' => $this->users,
                'view_previous_stages' => $this->allowPrevStage,
                'view_future_stages' => $this->allowFutureStage,
            ];
            $jsonData[$recordType][] = $rule2;
        }elseif($this->selectedStartedRule == 3){
            $rule3 = [
                'condition_title' => $this->title ,
                'condition_type' => $this->selectedStartedRule,
                'email_user_type'    => $this->selectedEmailType,
                'message' => $this->message
            ];
            if($this->selectedEmailType == 1){
                $rule3['users'] = $this->users;
            }elseif($this->selectedEmailType == 2){
                $rule3['user_profiles'] =  $this->users;
            }
            $jsonData[$recordType][] = $rule3;
        }else{
            $this->validate();
        }
        
        $this->stage->stage_rules = json_encode($jsonData);
        $this->stage->save();
        $this->startedTableData = json_decode($this->stage->stage_rules,true);
        $this->emit('formSubmitted');
    }

    public function updateRecord(){
        $jsonData = $this->startedTableData;
        $recordType =  $this->ruleType == 'c' ? 'completed' : 'started';
            $this->startedTableData[$recordType][$this->ruleIndex]['condition_title'] = $this->title ;
            $this->startedTableData[$recordType][$this->ruleIndex]['condition_type'] = $this->selectedStartedRule ;
            if($this->selectedStartedRule == '1'){
                $this->startedTableData[$recordType][$this->ruleIndex]['user_profiles'] = $this->users;
                $this->startedTableData[$recordType][$this->ruleIndex]['view_previous_stages'] = $this->allowPrevStage ;
                $this->startedTableData[$recordType][$this->ruleIndex]['view_future_stages'] = $this->allowFutureStage ;
                $this->startedTableData[$recordType][$this->ruleIndex]['add_user'] = $this->addUser ;
            }else if($this->selectedStartedRule == '2'){
                $this->startedTableData[$recordType][$this->ruleIndex]['users'] = $this->users;
                $this->startedTableData[$recordType][$this->ruleIndex]['view_previous_stages'] = $this->allowPrevStage ;
                $this->startedTableData[$recordType][$this->ruleIndex]['view_future_stages'] = $this->allowFutureStage ;
            }else if($this->selectedStartedRule == '3'){
                $this->startedTableData[$recordType][$this->ruleIndex]['email_user_type'] = $this->selectedEmailType;
                if($this->selectedEmailType == 1){
                    $this->startedTableData[$recordType][$this->ruleIndex]['users'] = $this->users;
                }elseif($this->selectedEmailType == 2){
                    $this->startedTableData[$recordType][$this->ruleIndex]['user_profiles'] =  $this->users;
                }
                $this->startedTableData[$recordType][$this->ruleIndex]['view_previous_stages'] = $this->allowPrevStage ;
                $this->startedTableData[$recordType][$this->ruleIndex]['view_future_stages'] = $this->allowFutureStage ;
                $this->startedTableData[$recordType][$this->ruleIndex]['message'] = $this->message ;
            }
            $this->stage->stage_rules = json_encode($this->startedTableData);
            $this->stage->save();
    }
    public function selectedUser($value){
        dd($value);
    }
    public function deleteConditon($index,$type){
        if($type === 's'){
            unset($this->startedTableData['started'][$index] );
        }else{
            unset($this->startedTableData['completed'][$index] );
        }
        $this->stage->stage_rules = json_encode($this->startedTableData);
        $this->stage->save();
        $this->startedTableData = json_decode($this->stage->stage_rules,true);
    }

    public function editConditon($index,$type){
        $this->ruleIndex = $index;
        $this->ruleType = $type;
        $recordType =  $type == 'c' ? 'completed' : 'started';
            $this->editData = $this->startedTableData[$recordType][$index];
            $this->title = $this->startedTableData[$recordType][$index]['condition_title'];
            $this->selectedStartedRule = $this->startedTableData[$recordType][$index]['condition_type'];
            if($this->selectedStartedRule == '1'){
                $this->userSelected =$this->startedTableData[$recordType][$index]['user_profiles'];
                $this->allowPrevStage = $this->startedTableData[$recordType][$index]['view_previous_stages'];
                $this->allowFutureStage = $this->startedTableData[$recordType][$index]['view_future_stages'];
                $this->addUser = $this->startedTableData[$recordType][$index]['add_user'];
                $this->emit('updateUserSelected',$this->userSelected,'profile');
            }
            elseif($this->selectedStartedRule == '2'){
                $this->userSelected =$this->startedTableData[$recordType][$index]['users'];
                $this->allowPrevStage = $this->startedTableData[$recordType][$index]['view_previous_stages'];
                $this->allowFutureStage = $this->startedTableData[$recordType][$index]['view_future_stages'];
                $this->emit('updateUserSelected',$this->userSelected,'user');
            }
            elseif($this->selectedStartedRule == '3'){
                $this->selectedEmailType =  $this->startedTableData[$recordType][$index]['email_user_type'];
                if($this->selectedEmailType == 2){
                    $this->userSelected =$this->startedTableData[$recordType][$index]['user_profiles'];
                    $this->emit('updateUserSelected',$this->userSelected,'profile');
                }elseif($this->selectedEmailType == 1){
                    $this->userSelected =$this->startedTableData[$recordType][$index]['users'];
                    $this->emit('updateUserSelected',$this->userSelected,'user');
                }
                $this->message = $this->startedTableData[$recordType][$index]['message'];
                $this->emit('emailUpdate',$this->message);
            }

        
    }
    public function updateStageId($id)
    {
        $this->stage = DefaultCaseStage::findOrFail($id);
        $this->is_external = Form::find($this->stage->be_spoke_form_id)->is_external_link;
        $this->startedTableData = json_decode($this->stage->stage_rules,true);
    }
    public function toggleComplete($value){
        $this->ruleType = $value;
    }
    public function render()
    {
        return view('livewire.case-logic');
    }
}
