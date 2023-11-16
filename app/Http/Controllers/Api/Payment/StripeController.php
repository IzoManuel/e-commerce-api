<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Exception\CardException;

class StripeController extends Controller
{
    use JsonRespondController;
    
    public function handlePayment(Request $request)
    {
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET'));

            $stripe->paymentIntents->create([
                'amount' => 100,
                'currency' => 'usd',
                'payment_method' => $request->payment_method,
                'description' => 'Demo payment with stripe',
                'confirm' => true,
                'receipt_email' => 'a@gmail.com'//$request->email, //fix
            ]);
        } catch (CardException $error) {
            throw new Exception('There was a problem processing your payment', 1);
        }
    }
}