<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
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

    public function create(Request $rq)
    {
        $rq->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required|exists:categories,id',
            'pic' => 'required|image|max:2048',
        ]);

        $file = $rq->file('pic');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $mime = $file->getMimeType();
        $content = file_get_contents($file);

        // Upload to Supabase
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => $mime,
        ])->put(env('SUPABASE_URL') . '/storage/v1/object/' . env('SUPABASE_BUCKET') . '/' . $fileName, $content);

        if (!$response->successful()) {
            return response()->json(['error' => 'Image upload failed'], 500);
        }

        $url = env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $fileName;

        // Save product to DB
        Product::create([
            'pro_name' => $rq->name,
            'pro_price' => $rq->price,
            'cate_id' => $rq->category,
            'pro_pic' => $url,
        ]);

        return redirect()->back()->with('success', 'Product added');
    }

    public function update(Request $rq)
    {
        $rq->validate([
            'id' => 'required|exists:products,id',
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required|exists:categories,id',
            'pic' => 'nullable|image|max:2048',
        ]);

        $product = Product::find($rq->id);

        $product->pro_name  = $rq->name;
        $product->pro_price = $rq->price;
        $product->cate_id   = $rq->category;

        if ($rq->hasFile('pic')) {
            $file = $rq->file('pic');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $mime = $file->getMimeType();
            $content = file_get_contents($file);

            // Upload new image to Supabase
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'apikey' => env('SUPABASE_SERVICE_KEY'),
                'Content-Type' => $mime,
            ])->put(env('SUPABASE_URL') . '/storage/v1/object/' . env('SUPABASE_BUCKET') . '/' . $fileName, $content);

            if (!$response->successful()) {
                return response()->json(['error' => 'Image upload failed'], 500);
            }

            // Build the public URL to the uploaded image
            $url = env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $fileName;

            $product->pro_pic = $url;
        }

        $product->save();

        return response('Successfully Updated');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('table');
    }
}
