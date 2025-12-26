<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
   public function login(Request $request)
    {
        $inputs = $request->validate([
            'username'=>['required','string','min:4','max:250'],
            'password'=>['required']
        ]);




        $user = User::where('username',$inputs['username'])->first();

        if(!$user || !Hash::check($inputs['password'],$user->password)){
            return response()->json([
                'message'=>'wrong username or password'
            ],401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'access_token'=>$token,
            'type'=>'Bearer'
        ]);

    } 
    public function logout (Request $request) 
    {
      $request->user()->currentAccessToken()->delete();

       return response()->json([
        'message' => 'You logged out'
    ]);

} 
    public function editProfile(Request $request)
    {
     $request->validate([
       'current_password' => 'required',
        'password' => 'required',
        ]); 
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $request->user()->password)) {
        return response()->json([
            'message' => 'كلمة المرور الحالية غير صحيحة'
        ], 401);
    }

      $request->user()->update([
    'password' => \Illuminate\Support\Facades\Hash::make($request->password)
]);

        return response()->json([
        'message' => 'password updated successfully',
        'user' => $request->user()
    ]); }

}
