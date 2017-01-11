<?php

namespace Naweown\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends CreateItemRequest
{

    public function rules()
    {
        return [
            'title' => 'required|string|min:4|max:220',
            'slug' => "string|max:200|slug|exists:items",
            'description' => 'required|string|min:6'
        ];
    }
}
