<?php

namespace App\Helpers\Dashboard\Manager;

use App\Models\Plan;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;

class TotalTransactionsHelper
{
    public static function totalTransactions($users)
    {
        try {
            $sumOfAmount = 0;
            foreach ($users as $user) {
                foreach ($user->userToHistory as $planHistory) {
                    $sumOfAmount += $planHistory->historyToPlan->amount;
                }
            }
            return $sumOfAmount;
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
