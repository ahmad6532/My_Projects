<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PaymentRequest;
use App\Repositories\Repositories\PlanRepository;

class PlanController extends Controller
{
    public function __construct(public PlanRepository $planRepository)
    {
    }
    //view all plan via endpoints
    public function allPlans()
    {
        try {
            $planHistory = $this->planRepository->planHistory();
            $plans = $this->planRepository->all();
            return view('plan.index', compact('plans', 'planHistory'));
        } catch (\Exception $e) {
          return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // show single Plan
    public function singlePlan($planId)
    {
        try {
            $planData = $this->planRepository->find($planId);
            return view('plan.payment', compact('planData'));
        } catch (\Exception $e) {
          return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // show single Plan
    public function showUserPlan()
    {
        try {
            $userPlanData = $this->planRepository->showUserPlan();
            if ($userPlanData == null) {
                return view('plan.show', compact('userPlanData'));
            }
            $planData =  $this->planRepository->find($userPlanData->planId);
            return view('plan.show', compact('planData', 'userPlanData'));
        } catch (\Exception $e) {
          return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    //purchase Plan
    public function purchase(PaymentRequest $request)
    {
        try {
            $this->planRepository->purchasePlan($request->all());
            return  redirect('users/dashboard')->with('message' ,'Congratulations! You have subscribed plan successfully...');
         } catch (\Exception $e) {
          return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    //purchase Plan
    public function showReceipt($userId)
    {
        try {
            $receiptData  = $this->planRepository->receipt($userId);
            $userData = auth()->user()->firstName;
            return view('plan.receipt',compact('receiptData','userData'));
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
