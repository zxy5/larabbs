<?php
/**
 * Created by PhpStorm.
 * User: xiaoyu
 * Date: 2018/8/23
 * Time: 11:20
 */
namespace App\Transformers;

use Spatie\Permission\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
        ];
    }
}
