<?php

namespace App\Jobs\Order;

use App\Mail\Order\OrderCreatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OrderCreatedMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $riderData;
    public function __construct($riderData)
    {
        $this->riderData=$riderData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->riderData)->send( new OrderCreatedMail());
    }
}
