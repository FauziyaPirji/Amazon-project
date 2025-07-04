<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{
    public function showAdminProducts()
    {
        if(Auth::check()) {
            if(Auth()->user()->user_type=='admin') {
                $Products = product::all();
                return view('admin.admin_products', compact('Products'));
            }
            else {
                return redirect('login')->withErrors([
                'email' => 'Admin login required'
            ]);
            }
        }
        else {
            return redirect('login')->withErrors([
                'email' => 'Admin login required'
            ]);
        }
    }

    public function productAdd(Request $request)
    {
        $Category = '';

        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:png,jpeg,jpg',
            'price' => 'required|integer|min:0',
        ]);

        if($request->category) {
            $Category = $request->category;
        }
        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();
        $path = $request->file('image')->storeAs('images/products',$fileName,'public');

        $product = product::create([
            'name' => $request->name,
            'product_image' => $path,
            'price' => $request->price,
            'type' => $Category
        ]);

        if($product) {
            return redirect()->back();
        }
    }

    public function productUpdate(Request $request, $id)
    {
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'type' => $request->category
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = $request->file('image')->storeAs('images/products', $fileName, 'public');
            $data['image'] = $path; 
        }

        DB::table('products')->where('id', $id)->update($data);

        return redirect()->route('admin/products');
    }

    public function productDelete($id)
    {
        $Product = product::find($id);
        if(! is_null($Product)) {
            $Product->delete();
        }
        return redirect()->route('admin/products');
    }
}
