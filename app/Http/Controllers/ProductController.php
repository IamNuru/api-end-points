<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    //search item
    public function search(Request $request)
    {
        $text = $request->input('text');
        $data = Product::where('title', 'like', '%' . $text . '%')
            ->orWhere('description', 'like', '%' . $text . '%')
            ->latest()
            ->get();

        return response()->json($data);
    }

    //fetch all products
    public function index()
    {
        $data = Product::with('category')
            ->where('qty', '>', 0)
            ->latest()
            ->get();

        return response()->json($data);
    }


    public function homepageProducts(Request $request, $catName)
    {
        $limit = $request->input('limit', 6);
        $catId = Category::where('name', $catName)
            ->orWhere('slug', $catName)
            ->first();
        $data = Product::with('category')
            ->where('category_id', $catId->id)
            ->where('qty', '>', 0)
            ->limit($limit)
            ->latest()
            ->get();

        return response()->json($data);
    }



    //fetch home page category of products
    public function categoryProducts($id)
    {
        $data = Product::where('category_id', $id)
            ->where('qty', '>', 0)
            ->limit(6)
            ->latest()
            ->get();

        return response()->json($data);
    }

    //fetch all products
    public function relatedProducts(Request $request, $id)
    {
        $limit = $request->input('limit', 6);
        $first = Product::where('id', $id)->first();
        $data = Product::where('category_id', $first->category_id)
            ->where('id', '!=', $id)
            ->limit($limit)
            ->get();

        return response()->json($data);
    }



    // store product
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|string|max:100|unique:products,title',
            'price' => 'required|numeric',
            'deductions' => 'nullable|numeric|lte:price',
            'qty' => 'nullable|numeric',
            'description' => 'nullable|string|min:2|max:250',
            'image_name' => 'required|image',
        ]);

        //check if image is selected
        if ($request->file('image_name')) {
            // if ($request->hasFile('image_name')) {

            //Get file name with Extension
            $filenameWithExt = $request->file('image_name')->getClientOriginalName();

            //Get just the file name
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            //Get just the Extension
            $extension = $request->file('image_name')->getClientOriginalExtension();

            //File name to store
            $filenameToStore = $filename . '_' . time() . '.' . $extension;

            //get file path
            $path = $request->file('image_name')->storeAs('public/images/products', $filenameToStore);
        } else {
            $filenameToStore = 'noimage.jpg';
        }
        $product = new Product();
        $product->category_id = $request->category;
        $product->title = $request->title;
        $product->slug =  Str::slug($request->title);
        $product->price = $request->price;
        $product->deduction = $request->deductions;
        $product->qty = $request->qty;
        $product->description = $request->description;
        $product->image = $filenameToStore;
        $product->save();

        return response()->json(['message' => 'Product Saved Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, $id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, $id)
    {
        $request->validate([
            'title' => 'required|min:3|string|max:100',
            'category' => 'required|integer',
            'price' => 'required|numeric',
            'deductions' => 'nullable|numeric|lte:price',
            'qty' => 'nullable|numeric',
            'description' => 'nullable|string|max:250',
            'image_name' => 'nullable|image',
        ]);

        if ($request->file('image_name')) {
            //get file name with extension
            $filenameWithExt = $request->file('image_name')->getClientOriginalName();

            //get file name
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('image_name')->getClientOriginalExtension();

            //file name to store
            $filenameToStore = $filename . '_' . time() . '.' . $extension;

            //get file path
            $path = $request->file('image_name')->storeAs('public/images/products', $filenameToStore);
        } else {
            $filenameToStore = 'noimage.jpg';
        }

        $product = Product::findOrFail($id);
        $product->title = $request->title;
        $product->category_id = $request->category;
        $product->slug =  Str::slug($request->title);
        $product->price = $request->price;
        $product->deduction = $request->deductions;
        $product->qty = $request->qty;
        $product->description = $request->description;
        $product->image = $filenameToStore;
        $product->update();

        return response()->json(['message' => 'Product Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, $id)
    {
        $product = Product::destroy($id);
        return response()->json(['message' => 'Product Deleted Successfully']);
    }

    /**
     * 
     * get cart items
     */
    public function cartItems(Request $request, $cart)
    {
        $ids = $request->input('ids');
        $aa = array_map('intval', explode(',', $ids));
        $cartItems = Product::whereIn('id', $aa)->get();

        /* foreach ($cart as $cat) {
            if ($cat->price != ) {
                # code...
            }
        } */


        return response()->json($cartItems);
    }
}
