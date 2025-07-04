<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\orders;
use App\Models\order_items;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function showOrder()
    {
        $Orders = orders::where('userId', Auth::user()->id)->with('orderitems')->get();
        $OrderItems = order_items::with('product')->get();

        return view('orders', compact('OrderItems', 'Orders'));
    }

    public function showTracking($id)
    {
        // $Orders = orders::where('userId', Auth::user()->id)->with('orderitems')->get();
        $OrderItems = order_items::with('product', 'order')->where('id', $id)->get();

        return view('tracking', compact('OrderItems'));
    } 
}
