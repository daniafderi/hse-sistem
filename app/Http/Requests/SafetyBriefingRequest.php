<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SafetyBriefingRequest extends FormRequest
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
            'tempat' => 'required',
            'pekerjaan' => 'required',
            'jumlah_peserta' => 'required',
            'images' => 'nullable|array',
            'images.*' => 'mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable'
        ];
    }
}
