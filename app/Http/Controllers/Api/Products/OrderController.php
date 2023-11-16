<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Traits\JsonRespondController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Payment\PayPalController;

class OrderController extends Controller
{

    use JsonRespondController;

    public function index()
    {

        $orders = Order::latest()->paginate(12);
        return $this->respond([
            'data' => $orders,
        ]);
        // return OrderResource::collection($orders);
    }

    public function store(Request $request)
    {
        
        $this->validateRequest($request);
        $cart = $request->cart;

        if (count($cart) < 1) {
            return $this->respond([
                'message' => 'Cart empty',
            ]);
        }

        $shippingAddress = [];
        $shippingAddress['name'] = $request->name;
        $shippingAddress['email'] = $request->email;
        $shippingAddress['country'] = $request->country;
        $shippingAddress['city'] = $request->city;
        $shippingAddress['postal_code'] = $request->postal_code;
        $shippingAddress['phone'] = $request->phone;

        $order = new Order;
        $order->shipping_address = json_encode($shippingAddress);
        $order->payment_type = $request->payment_option;
        $order->deliver_viewed = '0';
        $order->payment_status_viewed = '0';
        $order->code = date('Ymd-His') . rand(10, 99);
        $order->date = strtotime('now');
        //Todo: fix payment
        $order->save();
        
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        $coupon_discount = 0;

        // order details
        foreach ($cart as $cartItem) {
            // TODO: more order data
            $product = Product::find($cartItem['id']);
            if ($cartItem['quantity'] > $product->current_stock) {
                $order->delete();
                return $this->setHTTPStatusCode(401)->respond([
                    'message' => 'The requested quantity is not available for ' . $product->name,
                    'errors' => ['quantity' => 'The requested quantity is not available for ' . $product->name]
                ]);
                // return $this->setHTTPStatusCode(401)->respond([
                    
                // ]);
            } else {
                $product->current_stock -= $cartItem['quantity'];
            }

            $orderDetail = new OrderDetail;
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $product->id;
            $orderDetail->price = $product->unit_price * $cartItem['quantity'];
            $orderDetail->quantity = $cartItem['quantity'];

            $orderDetail->save();

            $product->num_of_sale += $cartItem['quantity'];
            $product->save();

            $subtotal += $product->unit_price * $cartItem['quantity'];
            // TODO: tax and coupon
        }
        
        $order->grand_total = $subtotal + $tax + $shipping;

        $order->save();

        return $order;
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        $order->delete();

        return $this->respond([
            'message' => 'Item deleted successfully',
        ]);
    }

    private function validateRequest(Request $request)
    {
        $customMessages = [
            'name.required' => 'The name field is required.',
            'payment_method.required' => 'The Payment method is required'
        ];
        
        $rules = [];
        $rules['email'] = 'required|email';
        $rules['country'] = 'required';
        $rules['city'] = 'required';
        $rules['postal_code'] = 'required';
        $rules['cart'] = 'required|Array';
        $rules['payment_method'] = 'required';
        // if ($request->payment_method == 'mpesa') {
        //     $rules[phone]
        // }

        $validated = $request->validate([
            'email' => 'required|email',
            'country'=> 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'cart' => 'required|Array',
            'payment_method' => 'required',
            'phone_number' => 'required'
        ], $customMessages);
        return $validated;
    }
}