<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'age' => ['nullable', 'integer', 'min:10', 'max:120'],
            'height' => ['nullable', 'integer', 'min:80', 'max:260'],
            'weight' => ['nullable', 'numeric', 'min:20', 'max:350'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'goal_mode' => ['nullable', Rule::in(['auto', 'custom'])],
            'activity_level' => ['nullable', 'numeric', 'in:1.2,1.375,1.55,1.725,1.9'],
            'calorie_intake' => ['nullable', 'integer', 'min:800', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
