<?php

namespace App\Http\Livewire;

use App\Models\CalenderEvent;
use App\Models\Forms\Form;
use Livewire\Component;

class Calender extends Component
{
    public $form;
    public $events;

    public $event_title = '';
    public $event;
    protected $listeners = ['editEvent'];


    public function mount($form_id){
        if($form_id){
            $this->form = Form::find($form_id);
            if(isset($this->form)){
                $raw_events = $this->form->calenderEvent;
                $this->events = $raw_events->map(function($event){
                    return [
                        'event_id' => $event->id,
                        'active' => $event->active,
                        'title' => $event->title,
                        'start' => $event->start_date,
                        'end' => $event->end_date,
                        'form_id' => $event->form_id,
                        'times' => json_decode($event->times,true),
                        'cutoff'=> $event->cutoff,
                        'do_not_allow_submissions'=> $event->do_not_allow_submissions,
                        'color' => $event->active == true ? '#2BAFA5' : '#999'
                    ];
                });
            }
        }
    }

    public function toggleActive($event_id){
        if($event_id){
            if($this->event->id == $event_id){
                $this->event->update(['active' => !$this->event->active]);
                $this->refreshArrayData();
                $this->emit('eventToggled', $this->events);
            }else{
                dd('id doenstmatch');
            }
        }
    }
    public function repeatToggle($event_id,$value){
        if($event_id && $this->event->id == $event_id){
            if($value == false){
                $this->event->update(['repeat_state' => 'off']);
            }else if($value == '1'){
                $this->event->update(['repeat_state' => 'month']);
            }else{
                $this->event->update(['repeat_state' => 'year']);
            }
        }else{
            dd('id doenstmatch');
        }
        $this->refreshArrayData();
$this->emit('eventToggled', $this->events);
        
    }
    public function addNewTime($event_id){
        $timesArray = json_decode($this->event->times, true);
        $timesArray[] = '09:00';
        $this->event->update(['times' => json_encode($timesArray)]);
        $this->refreshArrayData();
        $this->emit('eventToggled', $this->events);
    }

    public function removeTime($event_id,$index){
        $event_times = json_decode($this->event->times);
        if (isset($event_times[$index])) {
            unset($event_times[$index]);
        }
        $this->event->update(['times' => json_encode($event_times)]);
        $this->refreshArrayData();
        $this->emit('eventToggled', $this->events);
    }
    public function editEvent($event_id){
        if($event_id){
            $this->event = CalenderEvent::find($event_id);
            $this->event_title = $this->event->title;
        }
    }

    public function updateTime($event_id,$index,$value){
        $event_times = json_decode($this->event->times);
        if (isset($event_times[$index])) {
            $event_times[$index] = $value;
        }
        $this->event->update(['times' => json_encode($event_times)]);
        $this->refreshArrayData();
        $this->emit('eventToggled', $this->events);
    }

    public function UpdateCutOfftime($event_id,$value){
        $this->event->update(['cutoff'=>$value]);
    }
    public function toggleDoNotSubmission($event_id){
        $this->event->update(['do_not_allow_submissions'=> !$this->event['do_not_allow_submissions']]);
    }

    public function updateTitle($event_id){
        
        $this->event->update(['title' => $this->event_title ]);
        $this->refreshArrayData();
        $this->emit('eventToggled', $this->events);
    }

    
    public function addEvent($date){
        $newEvent = new CalenderEvent();
        $newEvent->form_id = $this->form->id;
        $newEvent->start_date = $date;
        $newEvent->end_date = $date;
        $newEvent->title = 'New Event';
        $newEvent->times = json_encode(["09:00"]);
        $newEvent->save();
        $this->refreshArrayData();
        $this->emit('eventToggled', $this->events);
    }

    public function resetEvent(){
        $this->event = null;
        $this->render();
    }

    public function removeEvent($event_id){
        if($event_id){
            $this->event->delete();
            $this->event = null;
            $this->refreshArrayData();
        $this->emit('eventToggled', $this->events);
        }
    }

    function refreshArrayData(){
        if(isset($this->form)){
            $this->form->refresh();
            $raw_events = $this->form->calenderEvent;
            $this->events = $raw_events->map(function($event){
                return [
                    'event_id' => $event->id,
                    'active' => $event->active,
                    'title' => $event->title,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'form_id' => $event->form_id,
                    'times' => json_decode($event->times,true),
                    'color' => $event->active == true ? '#2BAFA5' : '#999'
                ];
            });
        }
    }
    public function render()
    {
        return view('livewire.calender');
    }
}
