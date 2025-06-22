<?php

namespace App\Http\Livewire;

use App\Models\Forms\Form;
use Livewire\Component;

class DayScheduler extends Component
{
    public $isChecked = false;
    public $by_days = [];
    public $form;
    public function mount($form_id){
        if($form_id){
            $this->form = Form::where('id',$form_id)->firstOrFail();
            if(!isset($this->form->schedule_by_day)){
                $this->form->update(['schedule_by_day' => json_encode([
                    "Monday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                    "Tuesday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                    "Wednesday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                    "Thursday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                    "Friday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                    "Saturday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
                    "Sunday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false]
                ])]);
            }
            $this->by_days = json_decode($this->form->schedule_by_day,true);
        }
    }

    public function toggleActive($day){
        $this->by_days[$day]['active'] = !$this->by_days[$day]['active'];
        $json_days = json_encode($this->by_days);
        $this->form->update(['schedule_by_day' => $json_days]);
    }

    public function toggleDoNotSubmission($day){
        $this->by_days[$day]['do_not_allow_submissions'] = !$this->by_days[$day]['do_not_allow_submissions'];
        $json_days = json_encode($this->by_days);
        $this->form->update(['schedule_by_day' => $json_days]);
    }
    public function toggleMandatory($day){
        $this->by_days[$day]['mandatory'] = !$this->by_days[$day]['mandatory'];
        $json_days = json_encode($this->by_days);
        $this->form->update(['schedule_by_day' => $json_days]);
    }
    public function UpdateCutOfftime($day,$value){
        $this->by_days[$day]['cutoff'] = $value;
        $json_days = json_encode($this->by_days);
        $this->form->update(['schedule_by_day' => $json_days]);
    }

    public function addNewTime($day){
        $this->by_days[$day]['times'][] = '09:00';
        $json_days = json_encode($this->by_days);
        $this->form->update(['schedule_by_day' => $json_days]);
    }

    public function removeTime($day,$index){
        if (isset($this->by_days[$day]['times'][$index])) {
            unset($this->by_days[$day]['times'][$index]);
        }
        $json_days = json_encode($this->by_days);
        $this->form->update(['schedule_by_day' => $json_days]);
    }

    public function updateTime($day,$index,$value){
        if (isset($this->by_days[$day]['times'][$index])) {
            $this->by_days[$day]['times'][$index] = $value;
        }
        $json_days = json_encode($this->by_days);
        $this->form->update(['schedule_by_day' => $json_days]);
    }
    public function render()
    {
        return view('livewire.day-scheduler');
    }
}
