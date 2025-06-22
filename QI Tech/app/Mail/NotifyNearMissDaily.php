<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyNearMissDaily extends Mailable
{
    use Queueable, SerializesModels;
    public $reportedNearMisses = 0;
    public $toReportNearMisses = 0;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reportedNearMisses = 0, $toReportNearMisses = 0)
    {
        $this->reportedNearMisses = $reportedNearMisses;
        $this->toReportNearMisses = $toReportNearMisses;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->reportedNearMisses == 0){
            $subject = "You did not record any near misses today";
        }else{
            $subject = "You recorded less than expected near misses today";
        }
        return $this->subject($subject)->view('emails.notify_near_miss_daily')
                    ->with('subject',$subject)
                    ->with('toReportNearMisses',$this->toReportNearMisses);
    }
}
