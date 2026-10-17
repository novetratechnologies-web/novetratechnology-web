<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SermonUpdateRequest extends FormRequest
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
            //
            'title'       => 'required',
            'description' => 'required',
            'youtube_url' => ['nullable', 'url', function ($attribute, $value, $fail) {
                if (!$value) return;
                $host = strtolower((string) parse_url($value, PHP_URL_HOST));
                $validHost = in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com', 'youtu.be', 'www.youtu.be'], true);
                $hasVideoId = preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:[^#]*&)?v=|embed/|live/|shorts/))([A-Za-z0-9_-]{11})~i', $value);
                if (!$validHost || !$hasVideoId) $fail('Enter a valid YouTube watch, Shorts, or live URL.');
            }],
            //'cover_image' => 'nullable|mimes:jpeg,jpg,bmp,png',
        ];
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
}
