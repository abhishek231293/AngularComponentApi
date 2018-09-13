<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



use Illuminate\Support\Facades\Route;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url             = config('app.webUrl');

        try{
            if ($request->is('api*')) {
                $user = JWTAuth::parseToken()->toUser();
            }else{
                $user = JWTAuth::toUser($request->token);
            }
        }catch (JWTException $e) {
            // dd($e->getMessage());

            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                if ($request->is('api*')) {
                    return response()->json(['status' => 204, 'result' => $e->getMessage()]);
                }else{
                    return Redirect::to($url);
                }
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {

                if ($request->is('api*')) {
                    return response()->json(['status' => 204, 'result' => $e->getMessage()]);
                }else{
                    return Redirect::to($url);
                }
            }else{

                if ($request->is('api*')) {
                    return response()->json(['status' => 204, 'result' => $e->getMessage()]);
                }else{
                    return Redirect::to($url);
                }
            }
        }
        return $next($request);
    }
//    public function handle($request, Closure $next)
//    {
//        try{
//            $user = JWTAuth::toUser($request->input('token'));
//        }catch (JWTException $e) {
//            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
//                // return response()->json(['token_expired'], $e->getStatusCode());
//                return response()->json(['status' => 204,'result'=>$e->getStatusCode()]);
//            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
//               // return response()->json(['token_invalid'], $e->getStatusCode());
//                return response()->json(['status' => 204,'result'=>$e->getStatusCode()]);
//            }else{
//                // return response()->json(['error'=>'Token is required']);
//                return response()->json(['status' => 204,'result'=>$e->getStatusCode()]);
//            }
//        }
//        return $next($request);
//    }
}