<?php
namespace App\Mpesa;

use App\Models\MpesaSTK;
use Illuminate\Http\Request;

class STKPush
{
    public $failed = false;
    public $response = 'An unknown error occured';

    public function confirm(Request $request)
    {
        $payload = json_decode($request->getContent());

        if (property_exists($payload, 'Body') && $payload->Body->stkCallback->ResultCode == 0) {
            $merchant_request_id = $payload->Body->stkCallback->MerchantRequestID;
            $checkout_request_id = $payload->Body - stkCallback->CheckoutRequestID;
            $result_desc = $payload->Body->stkCallback->ResultDesc;
            $result_code = $payload->Body->stkCallBack->ResultCode;
            $amount = $payload->Body->stkCallback->CallbackMetadata->Item[0]->Value;
            $mpesa_receipt_number = $payload->Body->stkCallback->CallbackMetadata->item[1]->Value;
            $transaction_date = $payload->Body->stkCallBack->CallbackMetadata->item[3]->Value;
            $phone_number = $payload->Body->stkCallback->CallbackMetadata->Item[4]->Value;

            $stkPush = MpesaSTK::where('merchant_request_id', $merchant_request_id)
                ->where('checkout_request_id', $checkout_request_id)->first();

            if ($stkpush) {
                $stkPush->fill($data)->save();
            } else {
                MpesaStk::create(compact('result_desc', 'result_code', 'merchant_request_id', 'checkout_request_id', 'amount', 'mpesa_receipt_number', 'transaction_data', 'phone_number'));
            }
        } else {
            $this->Failed = true;
        }
        return $this;
    }
}