<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateArticleRequest extends FormRequest
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
        return [
            'title' => 'required|min:50|string',
            'content' => 'required|min:50|string',
            'photo' => 'image|dimensions:min_width=1080|dimensions:min_height=600|max:size:10000',
            'author_id' => 'required|integer',
            'category_id' => 'required|integer',
        ];
    }
}
