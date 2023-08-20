<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\returnSelf;

class AuthController extends Controller
{

    public function signin(Request $request){
        $messages=[
            'email.required'=>"please enter email",
            'email.exists'=>'email not register',
            'email.email'=>'please enter valid email',
        ];
        $validator=Validator::make($request->all(),[
            'email'=>'required|email|exists:users,email',
            'password'=>'required'
        ],$messages);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'data'=>$validator->errors(),
                'message'=>$validator->errors()->all()[0]
            ],404);
        }

        if(Auth::attempt($request->all())){
            $user = Auth::user();
            $success['token']=$user->createToken('user_secret')->plainTextToken;
            $success['credential']=[
                'firstName'=>$user->first_name,
                'lastName'=>$user->last_name,
                'email'=>$user->email,
                'id'=>$user->id
            ];
            return response()->json([
                'success'=>true,
                'data'=>$success,
                'message'=>'user signed in successfully'
            ],200);
        }else{
            return response()->json([
                'success'=>false,
                'data'=>null,
                'message'=>'something wrong. please provide valid information'
            ],200);
        }
    }

   public function signup(Request $request){
       $validator = $request->validate([
        'firstName'=>'required',
        'email'=>'required',
        'password'=>'required|min:8',
       ]);
        if(!$validator){
            return response()->json([
                'message'=>'please provide information correctly',
            ],404);
        }
        $password=Hash::make($request->password);
        $data=[
            'first_name'=>$request->firstName,
            "last_name"=>$request->lastName,
            "phone"=>$request->phone,
            "address"=>$request->address,
            "birthdate"=>$request->birthdate,
            "email"=>$request->email,
            "password"=>$password
        ];

      $result =  User::create($data);

      if(!$result){
        return response()->json([
            'success'=>false,
            'data'=>null,
            'message'=>'something wrong, please provide correct information'
        ],404);
      }
        return response()->json([
            'success'=>true,
            'data'=>$result,
            'message'=>'user created successfullty'
        ],200);
   }

    public function users(){
        $users=User::all();
        if(count($users)>0 && $users){
            return response()->json([
                'success'=>true,
                'data'=>$users,
                'message'=>'users retrieved successfully'
            ],200);
        }else{
            return response()->json([
                'success'=>false,
                'data'=>null,
                'message'=>'users not retrieved successfully'
            ],404);
        }
    }

    public function user(){
        $user=Auth::user();
        if(!$user){
            return response()->json([
                'success'=>false,
                'data'=>null,
                'message'=>'something wrong, please provide correct information'
            ],404);
          }
            return response()->json([
                'success'=>true,
                'data'=>$user,
                'message'=>'logged in user information'
            ],200);
    }

    public function signout(){
        Session::flush();

        Auth::user()->tokens()->delete();
        return response()->json([
            'success'=>true,
            'message'=>'signout successfully'
        ],200);
    }

}
