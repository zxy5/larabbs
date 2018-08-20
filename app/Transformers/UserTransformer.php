<?php
/**
 * Created by PhpStorm.
 * User: xiaoyu
 * Date: 2018/8/17
 * Time: 11:19
 */
namespace App\Transformers;
use App\Models\User;
use League\Fractal\TransformerABstract;

class UserTransformer extends TransformerABstract
{
    public function transform(User $user){
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'introduction' => $user->introduction,
            'bound_phone' => $user->phone ? true : false,
            'bound_wechat' => ($user->weixin_unionid || $user->weixin_openid) ? true : false,
            'last_actived_at' => $user->last_actived_at->toDateTimeString(),
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
        ];
    }
}