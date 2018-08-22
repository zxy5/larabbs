<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Http\Requests\Api\ReplyRequest;
use App\Transformers\ReplyTransformer;
use App\Models\Reply;

class RepliesController extends Controller
{
    //回复话题
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->content = $request->contents;
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->user()->id;
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }
    //删除回复
    public function destroy(Topic $topic,Reply $reply){
        if($reply->topic_id != $topic->id){
            return $this->response->errorBadRequest();
        }
        $this->authorize('destroy',$reply);
        $reply->delete();
        return $this->response->noContent();
    }
}
