<?php

namespace App\Http\Controllers;
use Cloudinary\Cloudinary;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;

class productController extends Controller
{   
    public function table()
    {
        $pro = Product::paginate(5);
        $cate = Category::all();
        return view('Layout.table', ['pro' => $pro, 'cate' => $cate]);
    }

    public function index()
    {
        $pro = Product::with('category')->get();
        $cate = Category::all();
        $customer = Customer::latest()->get();
        return view('Layout.home', ['pro' => $pro, 'cate' => $cate, 'customer' => $customer]);
    }

    public function create(Request $request)
    {


        $image = $request->file('pic');

        /** @var Cloudinary $cloudinary */
        $cloudinary = app(Cloudinary::class);

        // Upload image to Cloudinary
        $uploadResult = $cloudinary->uploadApi()->upload($image->getRealPath());

        $uploadedFileUrl = $uploadResult['secure_url']; // get secure URL

        Product::create([
            'pro_name' => $request->name,
            'pro_price' => $request->price,
            'cate_id' => $request->category,
            'pro_pic' => $uploadedFileUrl,
        ]);

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    public function update(Request $rq)
    {
        $product = Product::find($rq->id);

        $product->pro_name = $rq->name;
        $product->pro_price = $rq->price;
        $product->cate_id = $rq->category;

        if ($rq->hasFile('pic')) {
            $image = $rq->file('pic');

            /** @var Cloudinary $cloudinary */
            $cloudinary = app(Cloudinary::class);

            // Upload image to Cloudinary
            $uploadResult = $cloudinary->uploadApi()->upload($image->getRealPath());

            $uploadedFileUrl = $uploadResult['secure_url']; // get secure URL

            $product->pro_pic = $uploadedFileUrl;
        }

        $product->save();

        return response('Successfully Updated');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return response('Successfully Deleted');
    }
}
