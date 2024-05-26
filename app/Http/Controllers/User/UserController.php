<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends ApiController implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */


    public static function middleware(): array
    {
        return [
         new Middleware('client.credentials', only: ['store', 'resend']),
         new Middleware('auth:api', except:['store', 'verify', 'resend','userLogin']),
         new Middleware(TransformInput::class.':'. UserTransformer::class, only: ['store', 'update']),
         new Middleware('scope:manage-account', only:['show','update']),
         new Middleware('can:view,user', only:['show']),
         new Middleware('can:update,user', only:['update']),
         new Middleware('can:delete,user', only:['destroy']),


        ];
    }
    

    public function index()
    {

      $this->allowedAdminAction();

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

            $this->allowedAdminAction();

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

    public function userLogin(Request $request)
    {

       $data = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
       ]);

       if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
         $user = Auth::user();
         $token = $user->createToken('Token',['manage-account'])->accessToken;
         return $this->loginMessage($user, $token);
       }
    }

    public function logout(){

      $user = Auth::user();

      $token = $user->token();

      $token->revoke();

      return response()->json([
         "status" => true,
         "message" => "Successfully Logout"
      ]);

    }


    public function verify($token){
      $user = User::where('verification_token', $token)->firstOrFail();
      $user->verified = User::VERIFIED_USER;
      $user->verification_token = null;

      $user->save();

      return $this->showMessage('This account has been verified successfully');

    }

    public function resend(User $user){

      if($user->isVerified()){

         return $this->errorResponse('This user is already verified', 409);
      }

      retry(5, function() use ($user){
         Mail::to($user)->send(new UserCreated($user));
       }, 100);
      
         return $this->showMessage('The verification email has been send');
    }
}
