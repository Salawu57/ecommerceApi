<?php

namespace App\Http\Controllers\Seller;

use Exception;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;


class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
       $products = $seller->products;

       return $this->showAll($products);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $seller)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image'=> 'required|image',
        ]);

        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        

        $data = $request->validate([ 
            'quantity' => 'integer|min:1',
             'status' => 'in:'. Product::AVAILABLE_PRODUCT . ',' . PRODUCT::UNAVAILABLE_PRODUCT,
            'image'=> 'image',
        ]); 

       
        $this->checkSeller($seller, $product); 

        // if($seller->id != $product->seller_id){
        
        //    return  $this->errorResponse('This specified seller is not the actual seller of the product', 422);
        // }

        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        if($request->has('status')){
            $product->status = $request->status;


            if($product->isAvailable() && $product->categories()->count() == 0){
                return $this->errorResponse('A active product must have at least one category', 409);
            }
        }

        if($request->hasFile('image')){

            Storage::delete($product->image);

            $product->image = $request->image->store('');
        }

        if($product->isClean()){
            return $this->errorResponse('You need to specify a different value to update', 422);
        }

        $product->save();

        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);

        $product->delete();
        
        Storage::delete($product->image);

        return $this->showOne($product);
    }



    protected function checkSeller(Seller $seller, Product $product){
        if($seller->id != $product->seller_id){
          throw new HttpException(422,"This specified seller is not the actual seller of the product");
        }
       
    }
}
