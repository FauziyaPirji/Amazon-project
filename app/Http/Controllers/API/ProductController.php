<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\User;
use App\Models\cart;
use App\Models\orders;
use App\Models\order_items;
use App\Models\deliveryOption;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //
    // 1. Add Product Data
    // 2. Fetch All Product Data
    // 3. Fetch All Product Data with name
    // 4. Add product to Cart
    // 5. Place Order

    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'required|file',
            'price' => 'required|integer|min:0',
        ]);

        if($validator->fails()) 
        {
            return response()->json($validator->errors());
        }

        $Category = '';

        if($request->category) {
            $Category = $request->category;
        }
        $file = $request->image;
        $fileName = $file->getClientOriginalName();
        $path = $request->file('image')->storeAs('images/products',$fileName,'public');

        $product = product::create([
            'name' => $request->name,
            'product_image' => $path,
            'price' => $request->price,
            'type' => $Category
        ]);
        if($product) {
            return response()->json([
                'msg' => 'Product Inserted Successfully',
                'product' => $product
            ]);
        }
    }

    public function editProduct(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|integer|min:0',
        ]);

        if($validator->fails()) 
        {
            return response()->json($validator->errors());
        }

        $Category = '';

        if($request->category !== 'null') {
            $Category = $request->category;
        }

        if($request->image == '') {
            $data = [
                'name' => $request->name,
                'price' => $request->price,
                'type' => $Category
            ];
            DB::table('products')->where('id', '=',  $id)->update($data);
            return response()->json([
                'msg' => 'successfully updated'
            ]);
        }
        else {
            $file = $request->image;
            $fileName = $file->getClientOriginalName();
            $path = $request->file('image')->storeAs('images/products',$fileName,'public');

            $data = [
                'name' => $request->name,
                'price' => $request->price,
                'type' => $Category,
                'product_image' => $path
            ];
            DB::table('products')->where('id', '=',  $id)->update($data);
            return response()->json([
                'msg' => 'successfully updated'
            ]); 
        }

        return response()->json([
            'msg' => 'Not updated'
        ]);
    }

    public function deleteProduct($id) {
        $product = product::where('id', $id)->first();
        $product->delete();
        return response()->json([
            'msg' => 'successfully deleted'
        ]);
    }

    public function fetchProducts()
    {
		// print_r("hii");
        $session_id = Session::get('session_id');
        if(empty($session_id)) {
            $session_id = Str::random(40);
            // Session::put('session_id', $session_id);
        }

        $product = product::all();

        return response()->json([
            'product' => $product,
            'session' => $session_id
        ]);
    }

    public function fetchProductName()
    {
        $product = product::all('name');

        return response()->json([
            'product-name' => $product
        ]);
    }

    public function fetchSearchProducts(Request $request, $productName)
    {
        $query = product::query();

        if($productName) {
            $query->where('name', 'like', "%$productName%");
        }

        $Products = $query->get();

        if($Products) {
            return response()->json($Products);
        }
    }

    // public function addProductToCartWithSession(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'quantity' => 'required|min:1',
    //     ]);

    //     if($validator->fails()) 
    //     {
    //         return response()->json($validator->errors());
    //     }

    //     $cart = [
    //         "productId" => $id,
    //         "itemQty" => $request->quantity,
    //         "deliveryOptionId" => "1"
    //     ];
    //     // $cart = session()->get('cart');

    //     // $cart[$id] = [
    //     //     "id" => Str::random(3),
    //     //     "productId" => $id,
    //     //     "itemQty" => $request->quantity,
    //     //     "deliveryOptionId" => "1"
    //     // ];

    //     // session()->put('cart', $cart);

    //     // $cartt = session()->get('cart');

    //     return response()->json($cart);
    // }

    public function placeOrderWithSession(Request $request, $total) 
    {
        $key = 'Authorization';
    
        if($request->header($key)) {
            $token = $request->header($key);
            if($token != 'null') {
                $cart = $request->cart; 
                $user = User::where('remember_token', $token)->first();
                $Orders = orders::create([
                    'userId' => $user->id,
                    'placeDate' => now(),
                    'total_price' => $total
                ]);
                foreach($cart as $cartItem) {
                    if($cartItem["deliveryOptionId"] == 1) {
                        $addDay = 7;
                    }
                    if($cartItem["deliveryOptionId"] == 2) {
                        $addDay = 3;
                    }
                    if($cartItem["deliveryOptionId"] == 3) {
                        $addDay = 1;
                    }

                    $cart_item = order_items::create([
                        'orderId' => $Orders->id,
                        'productId' => $cartItem["productId"],
                        'itemQty' => $cartItem["quantity"],
                        'arrivalDate' => now()->addDays($addDay),
                        'deliveryOptionId' => $cartItem["deliveryOptionId"]
                    ]);
                }

                $items = order_items::query()->where('orderId', $Orders->id)->get();

                return response()->json([
                    'msg_success' => 'order place successfully',
                    'order' => $Orders,
                    'orderItem' => $items
                ]);
            }
            else {
                return response()->json([
                    'msg' => 'Login First'
                ]);
            }
        }
        else {
            return response()->json([
                'msg' => 'token not given'
            ]);
        }
    }

    public function addProductToCartWithTable(Request $request, $id)
    {
        if($request->quantity) {
            $quantity = $request->quantity;
        }
        else {
            $quantity = 1;
        }

        $key = 'Authorization';

        if($request->header($key)) {
            $token = $request->header($key);
            $user = User::where('remember_token', $token)->first();
            $cartExist = cart::where('userId', $user->id)->where('productId', $id)->get();

            if($cartExist->isNotEmpty()) {
                $data = [
                    'itemQty' => $quantity,
                ];
                DB::table('cart')->where('userId', $user->id)->where('productId', $id)->update($data);
                $total = cart::where('userId', $user->id)->get()->count();
                return response()->json([
                    'msg' => 'Product added to cart',
                    'cart' => $total,
                ]);
            }
            else{
                $cart = cart::create([
                    'productId' => $id,
                    'userId' => $user->id,
                    'itemQty' => $quantity,
                    'deliveryOptionId' => 1 
                ]);

                if($cart) {
                    $total = cart::where('userId', $user->id)->get()->count();
                    return response()->json([
                        'msg' => 'Product added to cart',
                        'cart' => $total
                    ]);
                }
                else {
                    return response()->json([
                        'msg' => 'Product not add to cart',
                    ]);
                }
            }
        }
        else {
            return response()->json([
                'msg' => 'token not define'
            ]);
        } 
    }

    public function totalQty(Request $request) {
        $key = 'Authorization';

        if($request->header($key)) {
            $token = $request->header($key);
            $user = User::where('remember_token', $token)->first();
            $total = cart::where('userId', $user->id)->get()->count();
            return response()->json([
                'total' => $total,
            ]);
        }
        else {
            return response()->json([
                'msg' => 'token not define'
            ]);
        }   
    }

    public function showCart(Request $request) {
        $key = 'Authorization';

        if($request->header($key)) {
            $token = $request->header($key);
            $user = User::where('remember_token', $token)->first();

            $cart = Cart::where('userId', $user->id)->get();

            if($cart->isEmpty()) {
                return response()->json([
                    'msg' =>'cart is empty'
                ]);
            }

            $cart_data;
            foreach($cart as $cart_list){
                if($cart_list->deliveryOptionId == 1) {
                    $delivery  = 7;
                }
                else if($cart_list->deliveryOptionId == 2) {
                    $delivery = 3;
                }
                else {
                    $delivery = 1;
                }
                $products = product::where('id', $cart_list->productId)->get();
                $deliveryDetails = deliveryOption::where('id', $cart_list->deliveryOptionId)->get();
                foreach($products as $product) {
                foreach($deliveryDetails as $deliveryOption)
                    $cart_data [] = [
                        'id' => $cart_list->id,
                        'productId' => $cart_list->productId,
                        'userId' => $cart_list->userId,
                        'itemQty' => $cart_list->itemQty,
                        'deliveryOptionId' => $delivery,
                        'created_at' => $cart_list->created_at,
                        'product' => [
                            $product
                        ],
                        'deliveryDetails' => [
                            $deliveryOption
                        ]
                    ];
                }
            }

            return response()->json($cart_data);
        }
        else {
            return response()->json([
                'msg' => 'token not define'
            ]);
        } 
    }

    public function updateQuantity(Request $request, $id) 
    {
        if($request->qty) {
            $data = [
                'itemQty' => $request->qty,
            ];

            $key = 'Authorization';

            if($request->header($key)) {
                $token = $request->header($key);
                $user = User::where('remember_token', $token)->first();

                DB::table('cart')->where('userId', $user->id)->where('productId', $id)->update($data);
                $cart = cart::query()->where('userId', $user->id)->get();

                $cart_data;
                foreach($cart as $cart_list){
                    if($cart_list->deliveryOptionId == 1) {
                        $delivery  = 7;
                    }
                    else if($cart_list->deliveryOptionId == 2) {
                        $delivery = 3;
                    }
                    else {
                        $delivery = 1;
                    }
                    $products = product::where('id', $cart_list->productId)->get();
                    $deliveryDetails = deliveryOption::where('id', $cart_list->deliveryOptionId)->get();
                    foreach($products as $product) {
                    foreach($deliveryDetails as $deliveryOption)
                        $cart_data [] = [
                            'id' => $cart_list->id,
                            'productId' => $cart_list->productId,
                            'userId' => $cart_list->userId,
                            'itemQty' => $cart_list->itemQty,
                            'deliveryOptionId' => $delivery,
                            'created_at' => $cart_list->created_at,
                            'product' => [
                                $product
                            ],
                            'deliveryDetails' => [
                                $deliveryOption
                            ]
                        ];
                    }
                }

                return response()->json($cart_data);
            }
            else {
                return response()->json([
                    'msg' => 'token not define'
                ]);
            } 
        }
        else {
            return response()->json([
                'msg' => 'plz give update quantity'
            ]);
        } 
    }

    public function updateDeliveryOption(Request $request, $id, $value) 
    {
        $data = [
            'deliveryOptionId' => $value,
        ];

        $key = 'Authorization';

        if($request->header($key)) {
            $token = $request->header($key);
            $user = User::where('remember_token', $token)->first();

            DB::table('cart')->where('userId', $user->id)->where('id', $id)->update($data);

            $cart = cart::query()->where('userId', $user->id)->get();

            $cart_data;
            foreach($cart as $cart_list){
                if($cart_list->deliveryOptionId == 1) {
                    $delivery  = 7;
                }
                else if($cart_list->deliveryOptionId == 2) {
                    $delivery = 3;
                }
                else {
                    $delivery = 1;
                }
                $products = product::where('id', $cart_list->productId)->get();
                $deliveryDetails = deliveryOption::where('id', $cart_list->deliveryOptionId)->get();
                foreach($products as $product) {
                foreach($deliveryDetails as $deliveryOption)
                    $cart_data [] = [
                        'id' => $cart_list->id,
                        'productId' => $cart_list->productId,
                        'userId' => $cart_list->userId,
                        'itemQty' => $cart_list->itemQty,
                        'deliveryOptionId' => $delivery,
                        'created_at' => $cart_list->created_at,
                        'product' => [
                            $product
                        ],
                        'deliveryDetails' => [
                            $deliveryOption
                        ]
                    ];
                }
            }

            return response()->json($cart_data);
        }
        else {
            return response()->json([
                'msg' => 'token not define'
            ]);
        } 
    }

    public function deleteCartProduct(Request $request, $id)
    {
        $key = 'Authorization';
    
        if($request->header($key)) {
            $token = $request->header($key);
            $user = User::where('remember_token', $token)->first();

            $cart = cart::query()->where('userId', $user->id)->where('productId', $id)->first();
            // $cart1 = cart::query()->where('userId', $user->id)->where('productId', $id)->get();

            // if($cart1->isNotEmpty()) {
            //     $cart->delete();
            // }

            if($cart != null) {
                $cart->delete();
            }
            else {
                return response()->json([
                    'msg' => 'cart is empty'
                ]);
            }

            $Cart = cart::query()->where('userId', $user->id)->get();

            $cart_data;
            foreach($Cart as $cart_list){
                if($cart_list->deliveryOptionId == 1) {
                    $delivery  = 7;
                }
                else if($cart_list->deliveryOptionId == 2) {
                    $delivery = 3;
                }
                else {
                    $delivery = 1;
                }
                $products = product::where('id', $cart_list->productId)->get();
                $deliveryDetails = deliveryOption::where('id', $cart_list->deliveryOptionId)->get();
                foreach($products as $product) {
                foreach($deliveryDetails as $deliveryOption)
                    $cart_data [] = [
                        'id' => $cart_list->id,
                        'productId' => $cart_list->productId,
                        'userId' => $cart_list->userId,
                        'itemQty' => $cart_list->itemQty,
                        'deliveryOptionId' => $delivery,
                        'created_at' => $cart_list->created_at,
                        'product' => [
                            $product
                        ],
                        'deliveryDetails' => [
                            $deliveryOption
                        ]
                    ];
                }
            }

            if(isset($cart_data)) {
                return response()->json($cart_data);
            }
            else {
                return response()->json([
                    'msg' => 'cart is empty'
                ]);
            }
        }
        else {
            return response()->json([
                'msg' => 'token not define'
            ]);
        }
    }

    public function placeOrder(Request $request, $total)
    {
        $key = 'Authorization';
    
        if($request->header($key)) {
            $token = $request->header($key);
            $user = User::where('remember_token', $token)->first();

            $cart = cart::query()->where('userId', $user->id)->get();

            if($cart->isNotEmpty()) {
                $Orders = orders::create([
                    'userId' => $user->id,
                    'placeDate' => now(),
                    'total_price' => $total
                ]); 
            }
            else {
                return response()->json([
                    'msg' => 'cart is empty'
                ]);
            }

            foreach($cart as $cartItem) {
                if($cartItem->deliveryOptionId == 1) {
                    $addDay = 7;
                }
                if($cartItem->deliveryOptionId == 2) {
                    $addDay = 3;
                }
                if($cartItem->deliveryOptionId == 3) {
                    $addDay = 1;
                }

                $cart_item = order_items::create([
                    'orderId' => $Orders->id,
                    'productId' => $cartItem->productId,
                    'itemQty' => $cartItem->itemQty,
                    'arrivalDate' => now()->addDays($addDay),
                    'deliveryOptionId' => $cartItem->deliveryOptionId
                ]);

                $cartItem->delete();
            }

            $items = order_items::query()->where('orderId', $Orders->id)->get();

            return response()->json([
                'msg_success' => 'order place successfully',
                'order' => $Orders,
                'orderItem' => $items
            ]);
        }
        else {
            return response()->json([
                'msg' => 'token not define'
            ]);
        }
    }

    public function showOrders(Request $request) 
    {
        $key = 'Authorization';
    
        if($request->header($key)) {
            $token = $request->header($key);
            $user = User::where('remember_token', $token)->first();
            $orders = orders::where('userId', $user->id)->with('orderitems')->get();
            if($orders->isEmpty()) {
                return response()->json([
                    'msg' => 'No Orders'
                ]);
            }
            $order_data;
            foreach($orders as $order) {
                $OrderItems = order_items::where('orderId', $order->id)->with('product')->get();
                $order_data [] = [
                    'id' => $order->id,
                    'placeDate' => $order->placeDate,
                    'total_price' => $order->total_price,
                    'products' => $OrderItems,
                ];
            }
            return response()->json($order_data);
        }
        else {
            return response()->json([
                'msg' => 'token not define'
            ]);
        }
    }

    public function tracking(Request $request, $id) 
    {
        $key = 'Authorization';
    
        if($request->header($key)) {
            $OrderItems = order_items::with('product', 'order')->where('id', $id)->first();
            return response()->json([$OrderItems]);
        }
        else {
            return response()->json([
                'msg' => 'token not define'
            ]);
        }
    }
}
