<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $users = User::all();
      return $this->showall($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
       $data = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
       ]);

       $data['password'] = bcrypt($request->password);
       $data['verified'] = User::UNVERIFIED_USER;
       $data['verification_token'] = User::generateVerificationCode();
       $data['admin'] = User::REGULAR_USER;

       $user = User::create($data);

       return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        
        return $this->showOne($user);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
         
       

        $data = $request->validate([
            'email' => 'email|unique:users,email,'. $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
         ]);

         if($request->has('name')){
            $user->name = $request->name;
         }

         if($request->has('email') && $user->email != $request->email){

            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
         }

         if($request->has('password')){
            $user->password = bcrypt($request->password);
         }

         if($request->has('admin')){

            if(!$user->isVerified()){
                return $this->errorResponse('Only verified users can modify the adnin field', 409);
            }

            $user->admin = $request->admin;
         }

         if(!$user->isDirty()){

           return $this->errorResponse('You need to specify a different value to update', 422);

         }

         $user->save();

         return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
      

        $user->delete();

        return $this->showOne($user);
    }
}
