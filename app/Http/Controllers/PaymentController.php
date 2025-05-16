<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'payment_method' => 'required|in:razorpay,stripe'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $appointment = Appointment::findOrFail($request->appointment_id);

        if ($request->payment_method === 'razorpay') {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $order = $api->order->create([
                'receipt' => 'appointment_' . $appointment->id,
                'amount' => $appointment->amount * 100, // Convert to paise
                'currency' => 'INR'
            ]);

            return response()->json([
                'order_id' => $order->id,
                'amount' => $appointment->amount,
                'currency' => 'INR'
            ]);
        } else {
            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $appointment->amount * 100, // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'appointment_id' => $appointment->id
                ]
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $appointment->amount,
                'currency' => 'USD'
            ]);
        }
    }

    public function handleRazorpayWebhook(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('X-Razorpay-Signature');

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            $api->utility->verifyWebhookSignature(
                json_encode($payload),
                $signature,
                config('services.razorpay.webhook_secret')
            );

            if ($payload['event'] === 'payment.captured') {
                $appointment = Appointment::where('payment_id', $payload['payload']['payment']['entity']['id'])
                    ->firstOrFail();

                $appointment->update([
                    'payment_status' => 'completed'
                ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );

            if ($event->type === 'payment_intent.succeeded') {
                $appointment = Appointment::where('payment_id', $event->data->object->id)
                    ->firstOrFail();

                $appointment->update([
                    'payment_status' => 'completed'
                ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
