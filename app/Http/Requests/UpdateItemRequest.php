<?php

namespace Naweown\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends CreateItemRequest
{

    public function rules()
    {
        $id = $this->segment(2);

        return [
            'title' => 'required|string|min:4|max:220',
            'slug' => "string|max:200|slug|unique:items,slug,$id",
            'description' => 'required|string|min:6'
        ];
    }
}
