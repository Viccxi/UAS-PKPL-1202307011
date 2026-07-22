<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority'    => ['required', 'in:low,medium,high'],
            'status'      => ['required', 'in:pending,progress,completed'],
            'due_date'    => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required'          => 'Judul tugas wajib diisi.',
            'title.max'               => 'Judul tugas maksimal 255 karakter.',
            'priority.required'       => 'Prioritas tugas wajib diisi.',
            'priority.in'             => 'Prioritas harus salah satu dari: low, medium, high.',
            'status.required'         => 'Status tugas wajib diisi.',
            'status.in'               => 'Status harus salah satu dari: pending, progress, completed.',
            'due_date.required'       => 'Tanggal tenggat wajib diisi.',
            'due_date.date'           => 'Format tanggal tenggat tidak valid.',
            'due_date.after_or_equal' => 'Tanggal tenggat tidak boleh sebelum hari ini.',
        ];
    }
}
