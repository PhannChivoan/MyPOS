<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

    public function create(Request $rq)
    {
        try {
            if (!$rq->hasFile('pic')) {
                return response()->json(['error' => 'No image uploaded'], 400);
            }

            $image = $rq->file('pic');
            if (!$image->isValid()) {
                return response()->json(['error' => 'Invalid image uploaded'], 400);
            }

            $originalName = $image->getClientOriginalName();
            $fileName = time() . '_' . $originalName;

            $destination = storage_path('app/public/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0775, true);
            }

            $image->move($destination, $fileName);

            Product::create([
                'pro_name' => $rq->name,
                'pro_price' => $rq->price,
                'cate_id' => $rq->category,
                'pro_pic' => $fileName,
            ]);

            return redirect('/')->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
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

            $destination = storage_path('app/public/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0775, true);
            }

            $image->move($destination, $fileName);
        }

        $product->pro_name = $name;
        $product->pro_price = $price;
        $product->cate_id = $cate;
        if($fileName != null){
            $product->pro_pic = $fileName;
        }
        $product->save();

        return response("Successfully Updated");
    }

    public function destroy($id){
        $product = Product::find($id);

        if ($product->pro_pic) {
            $path = storage_path('app/public/images/' . $product->pro_pic);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $product->delete();
        return redirect("/table");
    }
}
