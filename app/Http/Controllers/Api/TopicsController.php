<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Transformers\TopicTransformer;
use App\Http\Requests\Api\TopicRequest;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

class TopicsController extends Controller
{
    //发布话题
    public function store(TopicRequest $request,Topic $topic){
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();
        return $this->response->item($topic, new TopicTransformer())
            ->setStatusCode(201);
    }
    //修改话题
    public function update(TopicRequest $request, Topic $topic){
        $this->authorize('update',$topic);
        $topic->update($request->all());
        return $this->response->item($topic,new TopicTransformer());
    }
    //修改话题
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy',$topic);
        $topic->delete();
        return $this->response->noContent();
    }
}
