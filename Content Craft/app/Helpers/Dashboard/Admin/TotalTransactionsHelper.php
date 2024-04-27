<?php

namespace App\Helpers\Dashboard\Admin;

use App\Models\Plans;

use Illuminate\Http\JsonResponse;

class TotalTransactionsHelper
{
    public static function totalTransactions(){
        try {
          $transactions = Plans::has('planToHistory')->get();
            $sumOfAmount = 0;
            foreach ($transactions as $planRecord) {
                $sumOfAmount += $planRecord->amount;
            }
            return $sumOfAmount;
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}