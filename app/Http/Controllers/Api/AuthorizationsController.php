<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\AuthorizationRequest;
use Illuminate\Support\Facades\Redis;
use Auth;

class AuthorizationsController extends Controller
{
    //登录
    public function store(AuthorizationRequest $request){
        $username = $request->username;
        filter_var($username,FILTER_VALIDATE_EMAIL)?
            $credentials['email'] = $username:
            $credentials['phone'] = $username;
            $credentials['password'] = $request->password;
            if(!$token = \Auth::guard('api')->attempt($credentials)){
                return $this->response->errorUnauthorized('用户名或密码错误');
            }
            return $this->respondWithToken($token)->setStatusCode(201);
    }

    //第三方登录
    public function socialStore($type,SocialAuthorizationRequest $request){
        if(!in_array($type,['weixin'])){
            return $this->response->errorBadRequest();
        }
        $driver = \Socialite::driver($type);
        try{
            if ($code = $request->code){
                $response = $driver->getAccessTokenResponse($code);
                $token = array_get($response,'access_token');
            } else{
                $token = $request->access_token;
                if($type == 'weixin') {
                    $driver->setOpenId($request->openid);
                }
            }
            $oauthUser = $driver->userFromToken($token);
//            return 's';
        }catch (\Exception $e){
            return $this->response->errorUnauthorized('参数错误，未获取用户信息');
        }
        switch ($type){
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid')?$oauthUser->offertGet('unionid'):null;
                if($unionid){
                    $user = User::where('weixin_unionid',$unionid)->first();
                } else{
                    $user = User::where('weixin_openid',$oauthUser->getId())->first();
                }
                //没有用户，默认创建一个用户
            if(!$user){
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
            }
            break;
        }
        $token = Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token)->setStatusCode(201);
//        return $this->response->array(['token'=>$user->id]);
    }

    protected function respondWithToken($token){
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    //刷新token
    public function update(){
//        return 'a';
        $token = Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }
    //删除token
    public function destroy(){
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }
}
