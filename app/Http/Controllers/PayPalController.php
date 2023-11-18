<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalController extends Controller
{
    public function payment()
    {
        $data = [];
        $data['items'] = [
            [
                'name' => 'child T-shirt',
                'price' => 500,
                'des' => 'blue T-shirt' ,
                'quantity' => 2,
            ],
            [
                'name' => 'screen',
                'price' => 1000,
                'des' => 'black screen' ,
                'quantity' => 1,
            ],
        ];

        $data['invoice_id'] = 1;
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = 'http://127.0.0.1:8000/payment/success';
        $data['cancel_url'] = 'http://127.0.0.1:8000/payment/cancel';
        $data['total'] = 1500;

        $provider = new  ExpressCheckout();
        $response = $provider->setExpressCheckout($data , true);
        return redirect($response['paypal_link']);

    }

    public function cancel()
    {
        return response()->json('Payment cancelled' , '402');
    }

    public function success(Request $request)
    {
        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);
        if (in_array(strtoupper($response['ACK']) , ['SUCCESS' , 'SUCCESSWITHWARNING'])){
            return response()->json('PAID SUCCESS');
        }

        return response()->json('Payment cancelled' , '402');
        // dd($response);

    }
}
