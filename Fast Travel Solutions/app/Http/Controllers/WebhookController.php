<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;

use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // Retrieve the raw POST data
        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');
        $endpointSecret = env('STRIPE_ENDPOINT_SECRET');

        // Log::info('Request Data', ['request data' => $request->all()]);
        // Log::info('Stripe Signature Header', ['signature' => $sigHeader]);

        if (!$sigHeader) {
            Log::error('Stripe webhook error: Missing signature header');
            return response()->json(['status' => 'Missing signature header'], 400);
        }

        try {
            // Verify the event using Stripe's library
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook error: Invalid payload', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook error: Invalid signature checking', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                Log::info('Payment Successful', ['session' => $session]);
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                Log::info('Payment Intent Succeeded', ['paymentIntent' => $paymentIntent]);
                break;

            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                Log::warning('Invoice Payment Failed', ['invoice' => $invoice]);
                break;

            default:
                Log::info('Unhandled event type', ['event' => $event->type]);
                return response()->json(['status' => 'Unhandled event type'], 400);
        }

        return response()->json(['status' => 'Success']);
    }
}
