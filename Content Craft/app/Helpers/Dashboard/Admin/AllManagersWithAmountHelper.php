<?php

namespace App\Helpers\Dashboard\Admin;

use Illuminate\Http\JsonResponse;

class AllManagersWithAmountHelper
{
    public static function totalManagersWithAmount($managers)
    {
        try {
            $managerWithAmount = [];
            foreach ($managers as $manager) {
                $totalAmount = 0;
                $tempAmount = 0;
                foreach ($manager->managerToUsers as $user) {
                    if ($user) {
                        foreach ($user->userToHistory as $planHistory) {
                            if ($planHistory) {
                                $singlePlan = $planHistory->historyToPlan;
                                $planAmount = $singlePlan->amount;
                                $tempAmount += $planAmount;
                            }
                        }
                        $totalAmount += $tempAmount;

                        $tempAmount = 0;
                    }
                }
                $managerName = $manager->firstName . ' ' . $manager->lastName;
                $managerWithAmount[] = [
                    'manager' => $managerName,
                    'amount' => $totalAmount
                ];
            }
            $amountAndManager = json_encode($managerWithAmount);
            return $amountAndManager;
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
