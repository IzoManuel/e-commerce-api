<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\Api\Payment\PayPalController;
use App\Http\Controllers\Api\Payment\MpesaSTKPUSHController;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    use JsonRespondController;

    public function checkout(Request $request)
    {
        // if ($request->payment_option != null) {
        $result = (new OrderController)->store($request);
        // }
        //check selected payment_method
        if ($request->payment_method === 'paypal' && $result instanceof Order) {
            $paypalController = new PayPalController();
            $paypalResponse = $paypalController->handlePayment($result);

            return $paypalResponse;
            // if ($paypalResponse['success']) {
            //     // Redirect the user to the PayPal payment URL
            //     return redirect()->away($paypalResponse['payment_url']);
            // } else {
            //     // Handle the case where payment initiation failed
            //     return $this->respond(['message' => 'Payment initiation failed.']);
            // }
        } else if ($request->payment_method === 'mpesa' && $result instanceof Order) {
            $mpesaResponse = (new MpesaSTKPUSHController)->STKPush($request, $result);
            
            return $mpesaResponse;
        }
        return $result;
    }
}