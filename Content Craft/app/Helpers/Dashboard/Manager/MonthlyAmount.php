<?php

namespace App\Helpers\Dashboard\Manager;


class MonthlyAmount
{
    public static function monthBaseAmount($users)
    {
        try {

            $monthlyData = [];
            foreach ($users as $user) {
                foreach ($user->userToHistory as $planHistory) {
                    $monthYear = $planHistory->createdAt->format('F Y');
                    $amount = $planHistory->historyToPlan->amount;
                    if (isset($monthlyData[$monthYear])) {
                        $monthlyData[$monthYear] += $amount;
                    } else {
                        $monthlyData[$monthYear] = $amount;
                    }
                }
            }
            $formattedData = [];
            foreach ($monthlyData as $monthYear => $totalAmount) {
                $formattedData[] = [
                    'month' => $monthYear,
                    'amount' => $totalAmount,
                ];
            }
            return json_encode($formattedData);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
