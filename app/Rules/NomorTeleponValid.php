<?php

namespace App\Rules;

use App\Services\TeleponNormalizer;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NomorTeleponValid implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Rule memakai normalizer yang sama agar validasi form dan proses impor tetap konsisten.
        if (TeleponNormalizer::normalize($value) === null) {
            $fail('Nomor telepon tidak valid. Pastikan hanya berisi angka dan minimal 10 digit.');
        }
    }
}
