<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContributionRequest extends FormRequest
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
            'title'=>'required',
            'type'=>'required|min:1|max:2',
            'content'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'title is required',
            'type.required' => 'type is required',
            'content.required'  => 'content is required',
        ];
    }
}
