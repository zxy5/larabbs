<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class ImagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ruls = [
            'type' => 'required|string|in:avatar,topic',
        ];
        if($this->type == 'avatar') {
            $ruls['image'] = 'required|mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200';
        }else{
            $ruls['image'] = 'required|mimes:jpeg,bmp,png,gif';
        }
        return $ruls;
    }
    public function messages()
    {
        return [
            'image.dimensions' => '圖片的清晰度不够，宽和高度要200px以上',
        ];
    }
}
