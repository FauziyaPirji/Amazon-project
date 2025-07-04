<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\product;
use App\Models\cart;
use App\Models\orders;
use App\Models\order_items;
use App\Models\deliveryOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartController extends Controller
{
    function showCart()
    {
        $Products = product::all();
        $DeliveryOption = deliveryOption::all();

        return view('checkout', compact('Products','DeliveryOption'));
    }

    function addToCart(Request $request, $id)
    {
        $Products = product::find($id);

        $cart = session()->get('cart');

        $cart[$id] = [
            "id" => Str::random(3),
            "productId" => $Products->id,
            "itemQty" => $request->quantity,
            "deliveryOptionId" => "1"
        ];

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart successfully');
    }

    public function update(Request $request)
    {
        if($request->quantity <= 0 || $request->quantity > 1000 ) {

        }
        else {
            if($request->id && $request->quantity){
                $cart = session()->get('cart');
                $cart[$request->id]["itemQty"] = $request->quantity;
                session()->put('cart', $cart);
            }
        }

        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
        $cart = session()->get('cart');

         if (isset($cart[$id])) {
            unset($cart[$id]);

            session()->put('cart', $cart);
         }

        return redirect()->back();
    }

    public function updateRadio(Request $request, $id)
    {
        $cart = session()->get('cart');

        $cart[$id]["deliveryOptionId"] = $request->radio_value;

        session()->put('cart', $cart);

        return redirect()->back();
    }

    public function placeOrder($total)
    {
        if(Auth::check()) {
            $cart = session()->get('cart');

            $Orders = orders::create([
                'userId' => Auth::user()->id,
                'placeDate' => now(),
                'total_price' => $total
            ]);

            foreach(session('cart') as $id => $details) { 
                $Cart = cart::create([
                    'productId' => $details["productId"],
                    'userId' => Auth::user()->id,
                    'itemQty' => $details["itemQty"],
                    'deliveryOptionId' => $details["deliveryOptionId"]
                ]);

                if($Cart->deliveryOptionId == 1) {
                    $addDay = 7;
                }
                if($Cart->deliveryOptionId == 2) {
                    $addDay = 3;
                }
                if($Cart->deliveryOptionId == 3) {
                    $addDay = 1;
                }

                $OrderItems = order_items::create([
                    'orderId' => $Orders->id,
                    'productId' => $details["productId"],
                    'itemQty' => $details["itemQty"],
                    'arrivalDate' => now()->addDays($addDay),
                    'deliveryOptionId' => $details["deliveryOptionId"]
                ]);
            }
            
            session()->forget('cart');

            return redirect('orders');
        }
        else {
            return redirect()->back();
        }
    }
}
