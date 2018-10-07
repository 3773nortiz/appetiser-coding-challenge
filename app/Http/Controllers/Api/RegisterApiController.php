<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;

class RegisterApiController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/user/register",
     *     operationId="register",
     *     tags={"User"},
     *     summary="create new user",
     *     description="create new user", 
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      required=true,     
     *      description="create new user",
     *      @SWG\Schema(
     *        @SWG\Property(
     *           property="name", 
     *           type="string",   
     *           example="example name"
     *        ),     
     *        @SWG\Property(
     *           property="email", 
     *           type="string",   
     *           example="email@example.com"
     *        ),     
     *        @SWG\Property(
     *           property="password", 
     *           type="string",   
     *           example="123456"
     *        )
     *      )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(type="object")
     *     )
     * )
     */
    public function register (Request $request) {
      $success = false;
      $errors = [];
      $token = '';
      $data = [];
      
      if (!$request->get('name') || !$request->get('email') || 
        !$request->get('password')) {
        return response()->json([
          'success' => $success
        ], 500);
      }

      $validator = Validator::make(
       	$request->all(), 
       	User::$rules, 
       	User::$error_message
	    );

      if ($validator->fails()) { 
       	$errors = $validator->errors();
       	foreach ($errors->all() as $error) {
       		return response()->json([
	       		'success' => $success,
	       		'message' => $error
	       	]);
       	}
      }

      $users_request_data = [
       	'name' => $request->get('name'),
        'email' => $request->get('email'),
        'password' => bcrypt($request->get('password')),
      ]; 

      $user_create = User::create($users_request_data);

   	  if (!$user_create->save()) {
   	   	return response()->json([
         		'message' => 'Could not create user.!', 
         		'token' => $token,
         		'success' => $success
         	]);
   	  }

      $user = User::first();
      $token = JWTAuth::fromUser($user);
      if ($token) $success = true;

      return response()->json([
     	  'success' => $success,
     	  'token' => $token
      ]);
    }
}
