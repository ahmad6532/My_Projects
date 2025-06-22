<?php

namespace App\Jobs;

use App\Mail\NotifyNearMissDaily;
use App\Models\NearMiss;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Location;
class NearMissDailyCheckEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        # Daily check your near misses and report if they are less than defined value.
        $location = Auth('location')->user();
        if(empty((int)$location->near_miss_reporting_less_than_week)){
            return;
        }
        $value = (int) $location->near_miss_reporting_less_than_week;
        $locations = Location::where('is_active',1)
                                ->where('is_suspended',0)
                                ->where('is_archived',0)
                                ->where('email_verified_at','!=',null)->get();

        foreach($locations as $location){
            $totalOpenDays = $location->totalOpenDays();
            if($totalOpenDays == 0){
                # Opening Hours are not set.
                return;
            }
            $perDay = (int)(floor($value /  $totalOpenDays));
            
            if(!$location->openToday()){
                # Not Open Today
                return;
            }
            $nearMissesCount = NearMiss::where('date', date('Y-m-d'))
                            ->where('location_id', $location->id)
                            ->where('status', 'active')
                            ->count();
            if($nearMissesCount < $perDay){
                # Send Email
                $email = new NotifyNearMissDaily($nearMissesCount,$perDay);
                Mail::to($location->email )->send($email);
            }
        }
    }
}
