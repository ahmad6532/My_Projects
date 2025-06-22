<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\EmployeeResignation;
use App\Models\user_approval;
use App\Models\EmployeeDetail;

class ResignationStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resignation:update';
    protected $description = 'Update resignation status';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $resignations = EmployeeResignation::where('is_approved', '1')->get();

        foreach ($resignations as $data) {
            $resignationDate = Carbon::parse($data->resignation_date);
            $nextDay = $resignationDate->addDay();
            
            if ($nextDay->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                $user = user_approval::where('emp_id', $data->emp_id)->first();
                $user->is_active = "0";
                $user->update();

                $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
                $user_detail->is_active = "0";
                $user_detail->update();
            }
        }
    }
}
