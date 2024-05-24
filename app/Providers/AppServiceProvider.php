<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Product;
use App\Mail\UserCreated;
use App\Mail\UserMailChange;
use Laravel\Passport\Passport;
use Symfony\Component\Clock\now;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Shema::defaultStringLength(191);

        User::created(function($user){
          retry(5, function() use ($user){
            Mail::to($user)->send(new UserCreated($user));
          }, 100);
         
        });

        User::updated(function($user){
         
          if($user->isDirty('email')){

            retry(5, function() use ($user){

              Mail::to($user)->send(new UserMailChange($user));
              
            }, 100);
          
          }
         
        });
        
        Product::updated(function($product){
          if($product->quantity == 0 && $product->isAvailable()){
            $product->status = Product::UNAVAILABLE_PRODUCT;
            $product->save();
          }
        });

        Passport::tokensExpireIn(now()->addMinutes(30));
        Passport::refreshTokensExpireIn(now()->addDays(30));
       
        Passport::tokensCan([
          'purchase-product' => 'Create a new transaction for a specific product',
          'manage-products' => 'Create, read, update, and delete products( CRUD)',
          'manage-account' => 'Read your account data, id, name, email, if verified, and if admin (cannot read password). Modify your account data (email, and password). Cannot delete your account',
          'read-general' => 'Read general information like purchasing categories, purchased products, selling products, selling categories, your transactions (purchases and sales)',
      ]);
    }
}
