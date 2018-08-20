<?php
/**
 * Created by PhpStorm.
 * User: xiaoyu
 * Date: 2018/8/20
 * Time: 14:38
 */
namespace App\Transformers;

use App\Models\Category;
use Doctrine\DBAL\Schema\Table;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return[
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
        ];
    }
}