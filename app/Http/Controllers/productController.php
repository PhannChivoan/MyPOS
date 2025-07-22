<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;

class productController extends Controller
{   
    public function table(){
        $pro = Product::paginate(5);
        $cate = Category::all();
        return view('Layout.table',['pro'=>$pro,'cate'=>$cate]);
    }
    public function index(){
        $pro = Product::with('category')->get();
        $cate = Category::all();
        $customer = Customer::latest()->get();
        return view('Layout.home',['pro'=>$pro,'cate'=>$cate,'customer'=>$customer]);
    }
    public function create(Request $rq){
        // $name = $rq->name;
        // $price = $rq->price;
        // $cate = $rq->category;
        // $image = $rq->file('pic');
        // $originalName = $image->getClientOriginalName();
        // $fileName = time() . '_' . $originalName;
        // $image->move(public_path('images'), $fileName);

        // Product::create([
        //     'pro_name' => $name,
        //     'pro_price' => $price,
        //     'cate_id' => $cate,
        //     'pro_pic' => $fileName,
        // ]);
        // return redirect('/');
        return "Hello";
    }
    public function update(Request $rq){
        $product = Product::find($rq->id);
        $name = $rq->name;
        $price = $rq->price;
        $cate = $rq->category;
        $fileName = null;
         if($rq->hasFile('pic')){

            $image = $rq->file('pic');
            $originalName = $image->getClientOriginalName();
            $fileName = time() . '_' . $originalName;
            $image->move(public_path('images'), $fileName);
        }
        $product->pro_name = $name;
        $product->pro_price = $price;
        $product->cate_id = $cate;
        if($fileName!=null){
            $product->pro_pic = $fileName;
        }
        $product->save();
        return response("Successfuly Update");
    }
    public function destroy($id){
        $product = Product::find($id);
        $product->delete();
        return redirect("/table");
    }
}
