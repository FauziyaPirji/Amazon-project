<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\review;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class productController extends Controller
{
    public function showProducts() 
    {
        $session_id = Session::get('session_id');
        if(empty($session_id)) {
            $session_id = Str::random(40);
            Session::put('session_id', $session_id);
        }

        $Products = product::all();
        return view('welcome', compact('Products', 'session_id'));
    }

    public function review(Request $request, $id)
    {
        $Review = review::create([
            'productId' => $id,
            'userId' => $request->userId,
            'review' => $request->review,
            'star' => $request->star
        ]);

        if($Review) {
            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $query = product::query();

        if ($request->search_text) {
            $query->where('name', 'like', "%$request->search_text%");
        }

        $Products = $query->get();
        $session_id = Session::get('session_id');
        return view('welcome', compact('Products', 'session_id'));
    }
}
