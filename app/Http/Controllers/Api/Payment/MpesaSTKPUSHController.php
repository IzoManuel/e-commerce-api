<?php

namespace App\Http\Controllers\Api\Payment;

use App\Models\MpesaSTK;
use App\Mpesa\STKPush;
use Iankumu\Mpesa\Facades\Mpesa;
use App\Http\Controllers\Controller;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaSTKPUSHController extends Controller
{
    use JsonRespondController;

    public $result_code = 1;
    public $result_desc = 'An error occured';

    public function STKPush(Request $request, $order=null)
    {
        $amount = 1;//$order->grand_total;
        $phone = $request->phone_number;
        $accountNumber = config('mpesa.shortcode');

        Log::info('This is an information message for debugging.');
        $response = Mpesa::stkpush($phone, $amount, $accountNumber);
        $result = json_decode((string) $response, true);

        // return $this->respond([
        //     'message' => json_decode((string) $response, true),
        //     'short_code' => $request->account_number
        // ]);
        MpesaSTK::create([
            'merchant_request_id' => $result['MerchantRequestID'],
            'checkout_request_id' => $result['CheckoutRequestID'],
        ]);

        return $result;
    }

    public function STKConfirm(Request $request)
    {
        $stkPushConfirm = ((new STKpush))->confirm($request);

        if ($stk_push_confirm) {
            $this->result_code = 0;
            $this->result_desc = 'Success';
        }

        return $this->respond([
            'ResultCode' => $this->result_code,
            'ResultDesc' => $this->result_desc
        ]);
    }
}