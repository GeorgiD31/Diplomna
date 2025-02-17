<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'        => 'required|string|max:255|unique:articles,title',
            'author'       => 'nullable|string|max:255',
            'description'  => 'required|string',
            'content'      => 'nullable|string',
            'url'          => 'nullable|url',
            'url_to_image' => 'nullable|url',
            'source_name'  => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ];
    }
}
