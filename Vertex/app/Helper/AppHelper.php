<?php
use Carbon\Carbon;
use App\Models\Log;
use App\Http\Controllers\CronsJobController;

function standard_date_time_format($datetime) {

    return date('h:i A, d-m-Y', strtotime($datetime));
}

function sendRequestForCronsJob($dateArray){
    foreach($dateArray as $date){
        CronsJobController::updateUserDailyAttendance($date);
        CronsJobController::updateUserMonthlyRecord(Carbon::parse($date)->format('Y-m'));
    }
}

function getDateArray($from,$to)
{
    $startDate = Carbon::parse($from);
    $endDate = Carbon::parse($to);

    // Calculate the difference in days
    $daysDifference = $startDate->diffInDays($endDate);
    $dateArray = [];
    $dateArray[] = $startDate->toDateString();

    // Add each day to the array
    for ($i = 1; $i <= $daysDifference; $i++) {
        $dateArray[] = $startDate->addDay()->toDateString();
    }

    return $dateArray;
}

function productImagePath($image_name)
{
    return public_path('images/products/'.$image_name);
}

function previousTenMinutesDateTime($date) {

    $currentDataDateTime = new \DateTime($date);
    $currentDataDateTime->modify('-10 minutes');
    $finalCurrentDataDateTime = $currentDataDateTime->format('Y-m-d H:i:s');

    return $finalCurrentDataDateTime;
}

if (! function_exists('createLog')) {
    function createLog($type, $msg){
        $user  = auth()->user();
        $userId  = $user->id;
        Log::create(['type'=> $type,'user_id'=> $userId,'msg'=> $msg]);
    }

    // function createCronJobLog($type, $msg, $cust_id){
    //     $customerId  = $cust_id;
    //     $userId  = User::where('customer_id',$customerId)->first();
    //     Log::create(['type'=> $type,'user_id'=> $userId->id,'msg'=> $msg]);
    // }
}

?>
