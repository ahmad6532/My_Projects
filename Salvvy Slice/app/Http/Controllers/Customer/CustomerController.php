<?php

namespace App\Http\Controllers\Customer;

use App\DataTables\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Http\Resources\Customer\CustomerCreateResource;
use App\Http\Resources\Customer\CustomerResponse;
use App\Repositories\Repositories\Customer\CustomerRepository;
use App\Repositories\Repositories\Rider\RiderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    public function __construct(public CustomerRepository $customerRepository, public RiderRepository $riderRepository)
    {
    }
    public function index(CustomerDataTable $customerDataTable)
    {
        try {
            return $customerDataTable->render('customer.index');
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
    // show create customer view
    public function create()
    {
        try {
            $riders = $this->riderRepository->all();
            return view('customer.create', compact('riders'));
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
    // store customer
    public function store(CustomerCreateRequest $request)
    {
        try {
            $requestType = $request->is('api*') ? 'api' : 'web';
            $customerData = $this->customerRepository->create($request->all(), $requestType);
            if ($requestType === 'api') {
                if ($customerData) {
                    return response()->json(['response' => ['status' => true, 'data' => new CustomerCreateResource($customerData)]], JsonResponse::HTTP_CREATED);
                } else {
                    return response()->json(['response' => ['status' => false, 'data' => 'Email already exists.']], JsonResponse::HTTP_BAD_REQUEST);
                }
            } elseif ($customerData) {
                return redirect()->route('customer.index')->with('message', 'Customer Added Successfully...');
            } else {
                return response()->json(['response' => ['status' => false, 'data' => 'Email already exists.']], JsonResponse::HTTP_BAD_REQUEST);
            }} catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
    // update customer
    public function update(CustomerUpdateRequest $request, $customerId)
    {
        try {
            $requestType = $request->is('api*') ? 'api' : 'web';
            if ($requestType === 'api') {
                $this->customerRepository->update(auth()->id(), $request->all());
                return response()->json(['response' => ['status' => true, 'data' => 'Profile Updated Successfully...']], JsonResponse::HTTP_OK);
            }
            $this->customerRepository->update($customerId, $request->all());
            return redirect()->route('customer.index')->with('message', 'Customer Updated Successfully...');
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    // show customer
    public function show($customerId)
    {
        try {
            $customerData = $this->customerRepository->find($customerId);
            return view('customer.show', compact('customerData'));
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    //edit customer
    public function edit($customerId)
    {
        try {
            $customerData = $this->customerRepository->find($customerId);
            return view('customer.edit', compact('customerData'));
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    // delete customer 
    public function destroy($customerId)
    {
        try {
            $this->customerRepository->delete($customerId);
            return redirect()->route('customer.index')->with('message', 'Customer Deleted Successfully...');
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
