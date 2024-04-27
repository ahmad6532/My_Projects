<?php

namespace App\Repositories\Repositories\Order;

use App\Enums\Order\OrderStatusEnum;
use App\Jobs\Order\OrderCreatedMailJob;
use App\Models\Order;
use Illuminate\Support\Str;
use App\Models\User;
use App\Repositories\Interfaces\Order\OrderInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderInterface
{
    // find single order 
    public function find($orderId)
    {
        try {
            $checkOrder = Order::where('customerId', auth()->id())->where('orderId', $orderId)->first();
           return $checkOrder;
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
 }
    }

    // find single order 
    public function findOrder($orderId)
    {
        try {
            $order = Order::find($orderId);
            return $order;
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    } 

    // add Order
    public function create($data)
    {
        try {
            DB::beginTransaction();
            Order::create([
                'productName' => $data['productName'],
                'quantity' => $data['quantity'],
                'riderId' => $data['rider'],
                'customerId' => $data['customer'],
                'status' => OrderStatusEnum::PENDING->value,
            ]);
            $riderMail = User::find($data['rider'])->email;
            dispatch(new OrderCreatedMailJob($riderMail));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    // update order Status
    public function updateStatus($orderId, $data)
    {
        try {
            DB::beginTransaction();
            Order::find($orderId)->update([
                'status' => Str::upper($data['status']),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
 }
    }


    // update Order
    public function update($orderId, $data)
    {
        try {
            DB::beginTransaction();
            $orderStatus = Order::find($orderId);
            if ($orderStatus->status === OrderStatusEnum::PENDING->value) {
                $orderStatus->update([
                    'productName' => $data['productName'],
                    'quantity' => $data['quantity'],
                ]);
                DB::commit();
                return $orderStatus;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    // get all user orders
    public function getAllUserOrders($status)
    {
        try {
            $STATUS = Str::upper($status);
            if ($STATUS === OrderStatusEnum::ALL->value) {
                $orders = Order::all();
                return $orders;
            }
            $orders = Order::where('status', $STATUS)->get();
            return $orders;
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    // get all  orders of customer
    public function getAllOrders()
    {
        try {
            $orders = Order::where('customerId', auth()->id())->get();
            return $orders;
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    //    delete a order
    public function delete($orderId)
    {
        try {
            DB::beginTransaction();
            $order =  Order::find($orderId)->delete();
            // dd($order);
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
