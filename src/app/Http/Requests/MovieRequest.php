<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'stock' => ['required','integer','min:0'],
            'sale_price' => ['required','numeric','min:0'],
            'rental_price' => ['required','numeric','min:0'],
            'availability' => ['required','boolean'],
            'title' => ['required','max:255'],
            'description'=> ['required','max:1000'],

        ];
        if($this->method() === 'POST'){
            $rules['image'] = ['required','array'];
            $rules['image.*'] = ['image','mimes:jpeg,png,jpg','max:2048'];
        }
        return $rules;
    }
}
