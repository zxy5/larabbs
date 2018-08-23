<?php
/**
 * Created by PhpStorm.
 * User: xiaoyu
 * Date: 2018/8/23
 * Time: 11:30
 */
namespace App\Transformers;
use App\Models\Link;
use League\Fractal\TransformerAbstract;

class LinkTransformer extends TransformerAbstract
{
    public function transform(Link $link)
    {
        return [
            'id' => $link->id,
            'title' => $link->title,
            'link' => $link->link,
        ];
    }
}