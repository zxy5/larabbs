<?php
/**
 * Created by PhpStorm.
 * User: xiaoyu
 * Date: 2018/8/22
 * Time: 17:28
 */
namespace App\Transformers;

use Spatie\Permission\Models\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    public function transform(Permission $permission)
    {
        return [
            'id' => $permission->id,
            'name' => $permission->name,
        ];
    }
}