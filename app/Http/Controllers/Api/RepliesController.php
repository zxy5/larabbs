<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Http\Requests\Api\ReplyRequest;
use App\Transformers\ReplyTransformer;
use App\Models\Reply;

class RepliesController extends Controller
{
    //
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
//        return 's';
        $reply->content = $request->contents;
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->user()->id;
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }
}
