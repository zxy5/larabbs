<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Handlers\ImageUploadHandler;
use App\Transformers\ImageTransformer;
use App\Http\Requests\Api\ImagesRequest;


class ImagesController extends Controller
{
    //
    public function store(ImagesRequest $request,ImageUploadHandler $uploader, Image $image){
//        return 'ss';
        $user = $this->user();
        $size = $request->type == 'avatar' ? 362:1024;
        $result = $uploader->save($request->image,str_plural($request->type),$user->id,$size);
        $image->path = $result['path'];
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();
        return $this->response->item($image,new ImageTransformer())->setStatusCode(201);
    }
}
