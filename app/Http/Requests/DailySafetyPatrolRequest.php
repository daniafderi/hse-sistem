<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailySafetyPatrolRequest extends FormRequest
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
            'project_safety_id' => 'required|exists:project_safeties,id',
            'tanggal' => 'required|date',
            'permit' => 'required',
            'jam_kerja' => 'required',
            'jumlah_pekerja' => 'required',
            'reward' => 'nullable',
            'nearmiss' => 'nullable',
            'punishment' => 'nullable',
            'kecelakaan' => 'nullable',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'deskripsi' => 'nullable'
        ];
    }
}
