<?php

namespace App\Services;

use App\Contracts\PaymentFlow;
use App\Models\Order;
use App\Models\OrderItem;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    use HttpResponses;

    public function __construct(protected PaymentFlow $gateway)
    {
    }

    public function processPayment(Request $request)
    {
        return $this->gateway->buildUrl($this->makePayment($request), $request->shipping_data['phone_number']);
    }


    /**
     * First: Paymob Authentication.
     */
    public function auth()
    {
        $json = [
            'api_key' => config('paymob.auth.api_key')
        ];
        $response = Http::post(config('paymob.keys.PAYMOB_AUTH_URL'), $json)->object();

        return $response->token;
    }

    /**
     * Second: Order Registration API
     */
    public function registration(Request $request)
    {
        //stablish auth
        $token = $this->auth();

        $request->mergeIfMissing(['auth_token' => $token]);

        $response =  Http::acceptJson()->post(config('paymob.keys.PAYMOB_BASE_URL'), $request)->collect();

        if (count($response) == 0) {
            return $this->error(null, "no response", 404);
        }

        // save order DB
        $order = Order::create([
            'order_id' => $response['id'],
            'user_id' => Auth::user()->id,
            'email' => $response['shipping_data']['email'],
            'fname' => $response['shipping_data']['first_name'],
            'lname' => $response['shipping_data']['last_name'],
            'street' => $response['shipping_data']['street'],
            'building' => $response['shipping_data']['building'],
            'floor' => $response['shipping_data']['floor'],
            'apartment' => $response['shipping_data']['apartment'],
            'phone' => $response['shipping_data']['phone_number'],
            'payment_method' => $request->payment_method,
            'shipping_fees' => $request->shipping_fees,
            'total_amount' => $request->total_amount,
        ]);

        if ($order) {
            // save order products
            $this->createOrderItems($order->order_id, $request->toArray());
        }

        return $response->put('auth_token', $token);
    }

    /**
     * Third: Payment Key Request
     */
    public function makePayment(Request $request)
    {
        $regisResponse = $this->registration($request);

        $json = [
            "auth_token" => $regisResponse['auth_token'],
            "amount_cents" => $regisResponse['amount_cents'],
            "expiration" => 3600,
            "order_id" => $regisResponse['id'],
            "currency" => $regisResponse['currency'],
            "integration_id" => 3510156,
            "billing_data" => [
                "email" => $regisResponse['shipping_data']['email'],
                "first_name" => $regisResponse['shipping_data']['first_name'],
                "last_name" => $regisResponse['shipping_data']['last_name'],
                "apartment" => $regisResponse['shipping_data']['apartment'],
                "floor" => $regisResponse['shipping_data']['floor'],
                "street" => $regisResponse['shipping_data']['street'],
                "building" => $regisResponse['shipping_data']['building'],
                "phone_number" => $regisResponse['shipping_data']['phone_number'],
                "city" => $regisResponse['shipping_data']['city'],
                "country" => $regisResponse['shipping_data']['country'],
                "state" => $regisResponse['shipping_data']['apartment'],
            ],
        ];
        $response = Http::acceptJson()->post(config('paymob.keys.PAYMOB_PAYMENT_URL'), $json);

        return $response['token'];
    }


    //create order items
    public function createOrderItems($id, array $request)
    {
        if (count($request) == 0) {
            return null;
        }

        foreach ($request['items'] as $item) {
            OrderItem::create([
                'order_id' => $id,
                'product_id' => (int)$item['product_id'],
                'price' => (int)$item['amount_cents'] / 100,
                'qty' => (int)$item['quantity']
            ]);
        }
    }
}
