<?php

namespace App\Http\Controllers\Order;

use App\DataTables\OrderDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderCreateRequest;
use App\Http\Requests\Order\OrderStatusUpdateRequest;
use App\Http\Requests\Order\OrderUpdateRequest;
use App\Http\Resources\Order\AllUserOrderResource;
use App\Http\Resources\Order\SingleOrderResource;
use App\Repositories\Repositories\Customer\CustomerRepository;
use App\Repositories\Repositories\Order\OrderRepository;
use App\Repositories\Repositories\Rider\RiderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
   public function __construct(public RiderRepository $riderRepository, public CustomerRepository $customerRepository, public OrderRepository $orderRepository)
   {
   }
   public function index(OrderDataTable $orderDataTable)
   {
      try {
         return $orderDataTable->render('order.index');
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }
   // show create view
   public function create()
   {
      try {
         $customers = $this->customerRepository->all();
         $riders = $this->riderRepository->all();
         return view('order.create', compact('riders', 'customers'));
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }

   // store order
   public function store(OrderCreateRequest $request)
   {
      try {
         $this->orderRepository->create($request->all());
         return redirect()->route('dashboard')->with('message', 'Order Added Successfully...');
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }

   // update Order Status
   public function updateOrderStatus(OrderStatusUpdateRequest $request, $orderId)
   {
      try {
         $this->orderRepository->updateStatus($orderId, $request->all());
         return response()->json(['response' => ['status' => true, 'data' => 'Status Updated Successfully...']], JsonResponse::HTTP_OK);
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }

   // view single order
   public function viewOrder($orderId)
   {
      try {
         $orderDetail =  $this->orderRepository->find($orderId);
         if ($orderDetail) {
            return response()->json(['response' => ['status' => true, 'data' => new SingleOrderResource($orderDetail)]], JsonResponse::HTTP_OK);
            return;
         } else {
            return response()->json(['response' => ['status' => true, 'data' => 'No Order Found on this OrderId']], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
         }
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }

   // get order on status base or all orders
   public function allUserOrder($status)
   {
      try {
         $orders = $this->orderRepository->getAllUserOrders($status);
         return response()->json(['response' => ['status' => true, 'data' => AllUserOrderResource::collection($orders)]], JsonResponse::HTTP_OK);
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }

   // get all order of customer
   public function customerAllOrder()
   {
      try {
         $orders = $this->orderRepository->getAllOrders();
         return response()->json(['response' => ['status' => true, 'data' => AllUserOrderResource::collection($orders)]], JsonResponse::HTTP_OK);
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }

   // update customer order till PENDING
   public function updateOrder(OrderUpdateRequest $orderUpdateRequest, $orderId)
   {
      try {
         $orderStatus = $this->orderRepository->update($orderId, $orderUpdateRequest->all());
         $requestType = $orderUpdateRequest->is('api*') ? 'api' : 'web';
         if ($requestType === 'api') {
         if ($orderStatus) {
            return response()->json(['response' => ['status' => true, 'data' => 'Order Updated Successfully...']], JsonResponse::HTTP_OK);
         } else {
            return response()->json(['response' => ['status' => true, 'data' => 'You cannot Update Order Now.']], JsonResponse::HTTP_OK);
         }
      }
      elseif($orderStatus){
            return redirect()->route('dashboard')->with('message', 'Order Updated Successfully...');
      }
         return redirect()->route('dashboard')->with('message', 'You Cannot Update Order');

      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }


   //edit order
   public function edit($orderId)
   {
      try {
         $orderData = $this->orderRepository->findOrder($orderId);
         return view('order.edit', compact('orderData'));
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }
   // show customer
   public function show($orderId)
   {
      try {
         $orderData = $this->orderRepository->findOrder($orderId);
         return view('order.show', compact('orderData'));
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }

   // delete order 
   public function destroy($orderId)
   {
      try {
         $this->orderRepository->delete($orderId);
         return redirect()->route('dashboard')->with('message', 'Order Deleted Successfully...');
      } catch (\Exception $e) {
         return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
      }
   }
}
