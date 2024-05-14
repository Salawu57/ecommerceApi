<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
       $categories = $product->categories;
       return $this->showAll($categories);
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, Category $category)
    {
        //attach == add to the record but duplicate
        //sync === overidde all record with the new one
        //syncWithoutdetaching  === add to record without duplicate
        $product->categories()->syncWithoutDetaching([$category->id]);
        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Category $category)
    {
       if(!$product->categories()->find($category->id)){

        return $this->errorResponse('This specified category is not a category of this product', 404);

       }

       $product->categories()->detach($category->id);

       return $this->showAll($product->categories);
       
    }
}
