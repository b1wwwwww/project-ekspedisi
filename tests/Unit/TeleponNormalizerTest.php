<?php

namespace Tests\Unit;

use App\Services\TeleponNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TeleponNormalizerTest extends TestCase
{
    #[DataProvider('validNomorTelepon')]
    public function test_normalizes_valid_indonesian_phone_numbers(string $input, string $expected): void
    {
        $this->assertSame($expected, TeleponNormalizer::normalize($input));
    }

    #[DataProvider('invalidNomorTelepon')]
    public function test_rejects_invalid_phone_numbers(mixed $input): void
    {
        $this->assertSame(null, TeleponNormalizer::normalize($input));
    }

    public static function validNomorTelepon(): array
    {
        return [
            'local number' => ['08123456789', '08123456789'],
            'international prefix with plus sign' => ['+628123456789', '08123456789'],
            'international prefix' => ['628123456789', '08123456789'],
            'spaces and hyphens' => ['0812-345 6789', '08123456789'],
        ];
    }

    public static function invalidNomorTelepon(): array
    {
        return [
            'contains letters' => ['abc123'],
            'has fewer than ten digits' => ['0812'],
            'is null' => [null],
            'is not a string' => [8123456789],
        ];
    }
}
