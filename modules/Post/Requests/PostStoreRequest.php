<?php

namespace Modules\Post\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
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
            'title' => 'required | min:3',
            'text' => 'required',
            'category_id' => 'required',
            'image' => 'file | image | max:5000',
            'status' => 'required',
        ];
    }

    public function normalizedData(): array
    {
        return array_merge($this->except('image'), ['user_id' => auth()->id()]);
    }
}
