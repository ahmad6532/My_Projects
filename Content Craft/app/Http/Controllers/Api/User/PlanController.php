<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\PaymentRequest;
use App\Http\Resources\Plan\AllPlanResource;
use App\Http\Resources\Plan\SubscriptionPlanResource;
use App\Repositories\Repositories\PlanRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct(public PlanRepository $planRepository)
    {
    }
    //view all plan via endpoints
    public function allPlans()
    {
        try {
            $plans = $this->planRepository->all();
                return response()->json(['response' => ['success' => true, 'data' => AllPlanResource::collection($plans)]], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
return response()->json(['respoonse'=>['success'=>false, data=>$e->getMessage()]],App\Http\Controllers\Api\User\JsonResponse)
    //purchase Plan
    public function purchase(PaymentRequest $request)
    {
        try {
            $planData = $this->planRepository->purchasePlan($request->all());
                return response()->json(['response' => ['success' => true, 'data' => new SubscriptionPlanResource($planData)]], JsonResponse::HTTP_OK);
       } catch (\Exception $e) {
            return response()->json(['response' => ['success' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
