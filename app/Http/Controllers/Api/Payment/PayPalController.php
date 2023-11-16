<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    use JsonRespondController;

    public function handlePayment(Order $order)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessTOken();
        $response = $provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('success.payment'),
                'cancel_url' => route('cancel.payment'),
                'user_action' => 'PAY_NOW',
            ],
            'purchase_units' => [
                0 => [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $order->grand_total,
                    ],
                ],
            ],

        ]);
        Log::info('HandlePayment Response' . $response['id']);

        if (isset($response['id']) && $response['id'] != null) {
            $order->paypal_order_id = $response['id'];
            $order->save();

            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return $this->respond([
                        'message' => $links['href'],
                    ]);
                }
            }

            return $this->respond([
                'message' => 'cancel.payment',
            ]);
        } else {
            return $this->respond(['message' => 'create.payment']);
        }

    }

    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return $this->respond(['message' => 'Transaction complete']);
        } else {
            return $this->respond(['message' => 'Something Went wrong']);
        }
    }

    public function paymentCancel()
    {
        return $this->respond(['message' => 'Payment Cancelled']);
    }

    public function handleWebhook(Request $request)
    {
        $response = collect($request);
        // Update order status, log events, etc.
        switch ($request->event_type) {
            case 'PAYMENT.CAPTURE.COMPLETED':
                Log::info('INSIDE : PAYMENT.CAPTURE.COMPLETED'.$response);
                // Assuming $payload contains order_id, amount, currency, etc.
                if (isset($response['resource']['supplementary_data']['related_ids']['order_id'])) {
                    $orderID = $response['resource']['supplementary_data']['related_ids']['order_id'];
                    Log::info('INSIDE ISSER ORDERID:');
                    // Now you have the order_id, you can use it in your application
                    $order = Order::where('paypal_order_id', $orderID)->first();

                    if ($order) {
                        // Order found, proceed with further processing
                        Log::info('ORDER FOUND:');
                        $order->payment_status = 'paid';
                        $order->save();
                    } else {
                        // Order not found, handle accordingly
                        Log::info('ORDER NOT FOUND:');
                    }
                } else {
                    // Data structure in response is not as expected, handle accordingly
                }
                //$order = Order::where('paypal_order_id', $payload['order_id'])->first();

                // if ($order) {
                //     // Update order status
                //     $order->payment_status = 'paid';
                //     $order->save();

                //     // Record transaction details (optional)
                //     // $transaction = new Transaction;
                //     // $transaction->order_id = $order->id;
                //     // $transaction->transaction_id = $payload['transaction_id'];
                //     // $transaction->amount = $payload['amount'];
                //     // $transaction->currency = $payload['currency'];
                //     // $transaction->save();

                //     // Send email notification (optional)
                //     // Add your email sending code here
                // }
                break;
                // Add cases for other event types...
        }
        Log::info('Webhook working: YYY'.$response);
        Log::info($response);
        return $this->respond(['message' => 'Webhook received']);
    }
}