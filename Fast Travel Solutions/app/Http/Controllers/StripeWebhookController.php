<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );

            // Handle the event
            switch ($event['type']) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event['data']['object']; // contains a \Stripe\PaymentIntent
                    // Then define and call a method to handle the successful payment
                    $this->handlePaymentIntentSucceeded($paymentIntent);
                    break;
                // Other event types can be handled here
                default:
                    // Unexpected event type
                    return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'success']);
        } catch (SignatureVerificationException $e) {
            return response()->json(['status' => 'failure', 'message' => 'Invalid signature']);
        }
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Handle the payment intent succeeded event (e.g., update database)
    }
}
?>
