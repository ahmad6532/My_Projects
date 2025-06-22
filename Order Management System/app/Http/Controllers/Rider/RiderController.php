<?php

namespace App\Http\Controllers\Rider;

use App\DataTables\RiderDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rider\RiderCreateRequest;
use App\Http\Requests\Rider\RiderUpdateRequest;
use App\Http\Resources\Rider\RiderResource;
use App\Repositories\Repositories\Rider\RiderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RiderController extends Controller
{
    public function __construct(public RiderRepository $riderRepository)
    {
    }
    public function index(RiderDataTable $riderDataTable)
    {
        try {
            return $riderDataTable->render('rider.index');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
    // show create customer view
    public function create()
    {
        try {
            return view('rider.create');
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
    // store customer
    public function store(RiderCreateRequest $request)
    {
        try {
            $requestType = $request->is('api*') ? 'api' : 'web';
            $riderData = $this->riderRepository->create($request->all(), $requestType);

            if ($requestType === 'api') {
                if ($riderData) {
                    return response()->json(['response' => ['status' => true, 'data' => new RiderResource($riderData)]], JsonResponse::HTTP_CREATED);
                } else {
                    return response()->json(['response' => ['status' => false, 'data' => 'Email already exists.']], JsonResponse::HTTP_BAD_REQUEST);
                }
            } elseif ($riderData) {
                return redirect()->route('rider.index')->with('message', 'Rider Added Successfully...');
            } else {
                return response()->json(['response' => ['status' => false, 'data' => 'Email already exists.']], JsonResponse::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
    // show rider
    public function show($riderId)
    {
        try {
            $riderData = $this->riderRepository->find($riderId);
            return view('rider.show', compact('riderData'));
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    //edit rider
    public function edit($riderId)
    {
        try {
            $riderData = $this->riderRepository->find($riderId);
            return view('rider.edit', compact('riderData'));
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
    // update Rider 
    public function update(RiderUpdateRequest $riderUpdateRequest, $riderId)
    {
        try {
            $this->riderRepository->update($riderId, $riderUpdateRequest->all());
            return redirect('riders')->with('message', 'Rider Updated Successfully...');
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    // delete Rider 
    public function destroy($riderId)
    {
        try {
            $this->riderRepository->delete($riderId);
            return redirect('riders')->with('message', 'Rider Deleted Successfully...');
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
