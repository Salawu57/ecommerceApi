<?php

namespace App\Http\Controllers\Product;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ProductBuyerTransactionController extends ApiController
{

    public function store(Request $request, Product $product, User $buyer)
    {

        $data = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if($buyer->id == $product->seller_id){
            return $this->errorResponse('The buyer must be different from the seller', 409);
        }

        if(!$buyer->isVerified()){
            return $this->errorResponse('The buyer must be a verified user', 409);
        }

        if(!$product->seller->isVerified()){
            return $this->errorResponse('The seller must be a verified user', 409);
        }

        if(!$product->isAvailable()){
            return $this->errorResponse('The product is not available', 409);
        }

        if($product->quantity < $request->quantity){
            return $this->errorResponse('The product does not have enough unit for this transaction', 409);
        }

        return DB::transaction(function() use($request, $product, $buyer){
          $product->quantity -= $request->quantity;
          $product->save();

          $transaction = Transaction::create([
             'quantity' => $request->quantity,
             'buyer_id' => $buyer->id,
             'product_id' => $product->id
          ]);

           return $this->showOne($transaction, 201);
        });
    }
  
}
