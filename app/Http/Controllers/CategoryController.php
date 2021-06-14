<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = Category::with('products')->get();

        return response()->json($data);
    }



    //fetch products by categories
    public function categoryProducts($catName)
    {
        $data = Category::with('products')
            ->where('name', $catName)
            ->orWhere('slug', $catName)->get();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|string|min:3|max:25|unique:categories,name',
            'description' => 'nullable|string|min:10|max:250',
        ]);
        $category = new Category();
        $category->name = $request->categoryName;
        $category->slug = Str::slug($request->categoryName);
        $category->description = $request->description;
        $category->save();

        return response()->json(['message' => 'Category Saved Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, $id)
    {
        $category = Category::findOrFail($id);

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'categoryName' => 'required|string|min:3|max:25',
            'description' => 'nullable|string|max:250',
        ]);
        $category = Category::findOrFail($id);
        $category->name = $request->categoryName;
        $category->slug = Str::slug($request->categoryName);
        $category->description = $request->description;
        $category->update();

        return response()->json(['message' => 'Category Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, $id)
    {
        $category = Category::destroy($id);

        return response()->json(['message' => 'Category Deleted Successfully']);
    }
}
