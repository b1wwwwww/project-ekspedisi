<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
    return [
        'nama' => ['required', 'string', 'max:255'], // Nama pengguna wajib diisi.

        'email' => [ // Email boleh kosong, tetapi tidak boleh sama dengan pengguna lain.
            'nullable',
            'string',
            'lowercase',
            'email',
            'max:255',
            Rule::unique(User::class)->ignore($this->user()->id),
        ],

        'telepon' => ['nullable', 'string', 'max:20'], // Nomor telepon boleh tidak diisi.
        ];
    }
}
