<?php

namespace App\Http\Controllers\Feedback;

use App\DataTables\FeedbackDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback\FeedbackCreateRequest;
use App\Repositories\Repositories\Feedback\FeedbackRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedbackController extends Controller
{
    public function __construct(public FeedbackRepository $feedbackRepository)
    {
    }
    public function index(FeedbackDataTable $feedbackDataTable)
    {
        try {
            return $feedbackDataTable->render('feedback.index');
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    // Give Feedback to Rider
    public function feedback(FeedbackCreateRequest $request)
    {
        try {
            $feedbackData = $this->feedbackRepository->create($request->all());
            if ($feedbackData) {
                return response()->json(['response' => ['status' => true, 'data' => 'Feedback Submitted Successfully...']], JsonResponse::HTTP_OK);
            }
            return response()->json(['response' => ['status' => true, 'data' => 'You Cannot give Feedback untill Order Deliverd']], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json(['response' => ['status' => false, 'data' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
