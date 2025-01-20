<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:10',
            'article' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:products,article',
            'status' => 'required|in:available,unavailable',
            'data' => 'required|json',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'название продукта',
            'article' => 'артикул',
            'status' => 'статус',
            'data' => 'дополнительные данные',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле "название" обязательно для заполнения.',
            'name.min' => 'Поле "название" должно содержать не менее 10 символов.',
            'article.required' => 'Поле "артикул" обязательно для заполнения.',
            'article.regex' => 'Поле "артикул" может содержать только латинские буквы и цифры.',
            'article.unique' => 'Такой артикул уже существует в базе данных.'
        ];
    }
}
