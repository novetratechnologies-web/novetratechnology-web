<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SermonRequest extends FormRequest
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


        Validator::extend('alpha_spaces', function ($attribute, $value) {

            // This will only accept alpha and spaces. 
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('check_title', function ($attribute, $value, $parameters, $validator) {
            //validation for tamil letters
            return preg_match('/\pL\pM*|./u', request('title'));
        });

        Validator::extend('check_description', function ($attribute, $value, $parameters, $validator) {
            //validation for tamil letters
            return preg_match('/\pL\pM*|./u', request('description'));
        });


        $rules =
            [

                'title'       => 'required|check_title',
                'description' => 'required|check_description',
                'youtube_url' => ['nullable', 'url', function ($attribute, $value, $fail) {
                    if (!$this->isValidYouTubeUrl($value)) {
                        $fail('Enter a valid YouTube watch, Shorts, or live URL.');
                    }
                }],
                //'cover_image' => 'required|mimes:jpeg,jpg,bmp,png',

            ];
        return $rules;
    }

    public function messages()
    {

        return [
            'title.required' => 'Name is Required',
            'title.check_title'  => ' Enter Valid Title',
            'description.required' => 'Enter your description',
            'description.check_description' => 'Enter Valid Description',
            'cover_image.required' => 'Select your image',
            'cover_image.mimes' => 'file extension error',
        ];
    }

    private function isValidYouTubeUrl(?string $value): bool
    {
        if (!$value) return true;
        $host = strtolower((string) parse_url($value, PHP_URL_HOST));
        return in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com', 'youtu.be', 'www.youtu.be'], true)
            && (bool) preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:[^#]*&)?v=|embed/|live/|shorts/))([A-Za-z0-9_-]{11})~i', $value);
    }
}
