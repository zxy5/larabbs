<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\UserRequest;
//use Auth;
class UsersController extends Controller
{
    //短信验证码注册
    public function store(UserRequest $request){
//        return 'ss';
        $verifyData = \Cache::get($request->verification_key);
        if(!$verifyData){
            return $this->response->error('验证码已失效',422);
        }
        if(!hash_equals($verifyData['code'], $request->verification_code)){
            return $this->response->errorUnauthorized('验证码错误');
        }
        $user = User::create([
           'name' => $request -> name,
           'phone' => $verifyData['phone'],
           'password' => bcrypt($request->password),

        ]);
        //清除验证码缓存
        \Cache::forget($request->verification_key);
//        return $this->response->created();
        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 3600
            ])
            ->setStatusCode(201);
    }

    //获取个人信息
    public function me(){
//        return 'aaa';
        return $this->response->item($this->user, new UserTransformer());
    }

    //编辑个人资料
    public function update(UserRequest $request){
        $user = $this->user();
        $attributes = $request->only(['name','email','introduction']);
        if($request->avatar_image_id){
            $image = Image::find($request->id);
            $attributes['avatar'] = $image->path;

        }
        $user->update($attributes);
        return $this->response->item($user,new UserTransformer());
    }


}
