<?php

namespace App\Http\Controllers;

use App\Models\CustomerBooking;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PaymentController extends Controller
{
    public function cash(Request $request){

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'email' => 'required',
            'amount' => 'required',
            'payment_type' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }

        $booking = CustomerBooking::find($request->booking_id);

        if($booking){

            $payment = new UserPayment();
            $payment->user_id = $request->user_id;
            $payment->booking_id = $request->booking_id;
            $payment->email = $request->email;
            $payment->amount = $request->amount;
            $payment->payment_type = $request->payment_type;
            $payment->status = 1;
            $payment->save();

            // $booking = CustomerBooking::find($request->booking_id);
            // $booking->booking_status = 'accepted';
            // $booking->save();


            return response()->json([
                'status' => true,
                'message' => 'Cash payment record add successfully!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Booking does not exist against ID!',
            ]);

        }

    }
}
