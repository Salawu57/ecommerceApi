<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\TransformInput;
use App\Transformers\CategoryTransformer;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategoryController extends ApiController implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    //  public function __construct(){

    //     parent::__construct();
  
    //     $this->middleware('transaform.input:' . CategoryTransformer::class)->only(['store', 'update']);
  
    //   }

      public static function middleware(): array
      {
          return [
              new Middleware(TransformInput::class.':'.CategoryTransformer::class, only: ['store', 'update']),
          ];
      }

      
   

    public function index()
    {
        $categories = Category::all();

        return $this->showAll($categories);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $newCategory = Category::create($data);

        return $this->showOne($newCategory, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
       return $this->showOne($category);
      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
       $category->fill($request->only([
        'name',
        'description'
       ]));

       if($category->isClean()){

        return $this->errorResponse('You need to specify any different value to update', 422);

       }

       $category->save();

       return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}
