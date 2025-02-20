<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShareRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:shortlink,link,code,markdown,image',
            'content' => 'nullable|string',
            'file_url' => 'nullable|url',
            'short_code' => 'nullable|string|max:50',
        ];
    }
}
