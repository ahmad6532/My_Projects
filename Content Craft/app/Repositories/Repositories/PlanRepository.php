<?php

namespace App\Repositories\Repositories;

use App\Enums\PlanStatusEnum;
use App\Models\History;
use App\Models\Payment;
use App\Models\Plans;
use App\Models\Subscriptions;
use App\Repositories\Interfaces\PlanInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Stripe;

class PlanRepository implements PlanInterface
{
    public function all()
    {
        try {
            return Plans::all();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // find single plan
    public function find($planId)
    {
        try {
            return Plans::where('planId', $planId)->first();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // find user plan
    public function showUserPlan()
    {
        try {
            return Subscriptions::where('userId', auth()->user()->id)->first();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // find user plan history
    public function planHistory()
    {
        try {
            return History::where('userId', auth()->user()->id)->where('planId', 1)->first();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // purchase plan
    public function purchasePlan($data)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            DB::beginTransaction();
            $articleData = Plans::where('planId', $data['planId'])->first();
            $userPlan = Subscriptions::where('userId', auth()->user()->id)->first();
           $payment =  Charge::create([
                "amount" => $data['amount'] * 100,
                "currency" => "usd",
                "source" => $data['stripeToken'],
                "description" => "Test payment from Task 10."
            ]);
            if ($userPlan) {
                $remainingArticles = $userPlan->articles;
                $userPlan->update([
                    'planId' => $data['planId'],
                    'status' => PlanStatusEnum::PAID,
                    'articles' => $articleData->articles + $remainingArticles,
                ]);
            } else {
                Subscriptions::create([
                    'userId' => auth()->user()->id,
                    'planId' => $data['planId'],
                    'status' => PlanStatusEnum::PAID,
                    'articles' => $articleData->articles,
                ]);
            }
            History::create([
                'userId' => auth()->user()->id,
                'planId' => $data['planId']
            ]);
            Payment::create([
                'paymentId' => $payment->id,
                'userId' => auth()->id()
            ]);
            DB::commit();
            return $articleData;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


    // payment receipt
    public function receipt($userId)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $receiptData =[];
         $paymentId = Payment::where('userId',$userId)->get();
        foreach($paymentId as $id){
                $receiptData[] = Charge::retrieve($id->paymentId);
            }
            // dd($receiptData);
            return $receiptData;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


}
