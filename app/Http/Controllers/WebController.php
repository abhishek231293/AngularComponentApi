<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use JWTAuthException;
use DB;
use Hash;
use Carbon\Carbon;
use Mockery\Exception;
use phpDocumentor\Reflection\Types\Null_;

class WebController extends Controller
{
    public function __construct()
    {
//        $currentPath    = Route::getFacadeRoot()->current()->uri();
    }

    public function login(Request $request)
    {
//        echo 'hi there';
        $credentials = $request->only('email', 'password');
        $token = null;
        $email = $request->input('email')?stripslashes(trim($request->input('email'))):'';
        try {
            if($email){
                $status = DB::table('users')->where('email',$email)->first()->is_active;
                if($status == 0){
                    return response()->json(['message'=>'unverified','email'=>$email], 200);
                }
            }
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'invalid email or password'], 422);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['message' => 'failed to authorize token'], 500);
        }
        return response()->json(compact('token'));
    }

    public function logout(Request $request){
        try{
            $response = JWTAuth::invalidate($request->token);
            return response()->json(['status'=> 200, 'result' => $response]);
        }catch (\Exception $e){
            return response()->json(['status'=> 422, 'result' => $e->getMessage()]);
        }
    }

    public function user(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $user = array('first_name'=>$user->first_name,'last_name'=>$user->last_name);
        return response()->json(['result' => $user]);
    }

}
