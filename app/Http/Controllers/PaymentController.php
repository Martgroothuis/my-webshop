<?php

namespace App\Http\Controllers;

use App\Order;
use App\ProductOrder;
use App\ProductVersion;


use Illuminate\Http\Request;

use Mollie\Laravel\Facades\Mollie;


class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $id = auth()->user()->id;

        $products = $request->session()->get('selectedProduct');

        $i = 0;
        $sum = 0;
        foreach ($products as $product) {
            $products[$i]['product_version_id'] = ProductVersion::Where('id', '=', $product['product_version_id'])
                ->with(array('image' => function ($query) {
                    $query->first();
                }))
                ->with(['color'])
                ->with(['product'])
                ->get()->first();

            $sum = $sum + $products[$i]['product_version_id']['price'] * $product['ammount'];


            $i++;
        }
        $sum = $sum + 5.95;

        $sum = strval($sum);
        $sum = number_format((float) $sum, 2, '.', '');
        // return $products;

        $order = Order::create([
            'user_id' => $id,
            'status' => "not pay",
            'price' => $sum
        ]);


        foreach ($products as $product) {
            ProductOrder::create([
                'order_id' => $order->id,
                'product_version_id' => $product['product_version_id']['id'],
                'size_id' => $product['size_id'],
                'ammount' => $product['ammount'],
            ]);
        }


        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => $sum, // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'description' => 'My first API payment',
            // 'webhookUrl' => route('webhooks.mollie'),
            'redirectUrl' => route('payments.create'),
        ]);

        $payment = Mollie::api()->payments()->get($payment->id);
        $request->session()->put('payment_id', $payment->id);
        $request->session()->put('order_id', $order->id);

        // redirect customer to Mollie checkout page
        return redirect($payment->getCheckoutUrl(), 303);
    }

    /**
     * After the customer has completed the transaction,
     * you can fetch, check and process the payment.
     * (See the webhook docs for more information.)
     */
    public function create(Request $request)
    {
        $payment_id = $request->session()->get('payment_id');
        $order_id = $request->session()->get('order_id');

        $payment = Mollie::api()->payments()->get($payment_id);

        if ($payment->isPaid()) {
            Order::where('id', '=', $order_id)->update([
                'status' => "pay",
            ]);

            $request->session()->forget('selectedProduct');

            return redirect('/orders');
        } else {
            return redirect('/shoppingcart');
        }
    }
}
