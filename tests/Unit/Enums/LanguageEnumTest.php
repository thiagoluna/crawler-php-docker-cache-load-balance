<?php

namespace Tests\Unit\Enums;

use App\Enums\LanguageEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LanguageEnumTest extends TestCase
{
    public function testEnumTotalValues(): void
    {
        $enum = new \ReflectionClass(LanguageEnum::class);

        $this->assertCount(5, $enum->getConstants());
    }

    #[DataProvider('enumDataProvider')]
    public function testEnumValues(string $value1, string $value2): void
    {
        $this->assertEquals($value1, $value2);
    }

    public static function enumDataProvider(): array
    {
        return [
            ['pt', LanguageEnum::BRAZILIAN->value],
            ['en', LanguageEnum::ENGLISH->value],
            ['fr', LanguageEnum::FRENCH->value],
            ['de', LanguageEnum::DEUTSCH->value],
            ['es', LanguageEnum::ESPANISH->value],
        ];
    }
}
