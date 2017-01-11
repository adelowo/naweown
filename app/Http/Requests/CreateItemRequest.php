<?php

namespace Naweown\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->isAccountActivated();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|min:4|max:220',
            'slug' => 'string|max:200|slug',
            'description' => 'required|string|min:6'
        ];
    }
}
