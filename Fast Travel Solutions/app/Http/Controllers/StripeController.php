<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe as StripeStripe;
use Stripe\Webhook;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */


    public function check(Request $request){

        return 'okay';
    }

    public function stripePost(Request $request): RedirectResponse
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));


        Stripe\Charge::create([
            "amount" => $request->payment * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Booking Payment"
        ]);

        return back()->with('success', 'Payment successful!');
    }

   
}
