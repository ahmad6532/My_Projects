<?php

namespace App\Repositories\Repositories\Feedback;

use App\Enums\Order\OrderStatusEnum;
use App\Models\Feedback;
use App\Models\Order;
use App\Repositories\Interfaces\Feedback\FeedbackInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FeedbackRepository implements FeedbackInterface
{
    // create feedback
    public function create($data)
    {
        try {
            DB::beginTransaction();
            $orderStatus = Order::where('riderId', $data['rider'])->first()->status;
            if ($orderStatus === OrderStatusEnum::DELIVERED->value) {
               $feedback = Feedback::create([
                    'feedback' => $data['feedback'],
                    'riderId' => $data['rider'],
                    'customerId' => auth()->id()
                ]);
                Order::where('customerId', auth()->id())->where('riderId', $data['rider'])->update([
                    'status' => OrderStatusEnum::COMPLETED->value
                ]);
                DB::commit();
               return $feedback;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
