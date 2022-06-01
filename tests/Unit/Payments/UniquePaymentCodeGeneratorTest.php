<?php

namespace Tests\Unit\Payments;

use App\Payments\UniquePaymentCodeGenerator;
use PHPUnit\Framework\TestCase;

class UniquePaymentCodeGeneratorTest extends TestCase
{
    public function test_it_must_be_16_characters_long()
    {
        $generator = new UniquePaymentCodeGenerator;
        $code = $generator->generate();

        $this->assertEquals(16, strlen($code));
    }

    public function test_it_can_only_contain_uppercase_letters_and_numbers()
    {
        $generator = new UniquePaymentCodeGenerator;
        $code = $generator->generate();

        $this->assertMatchesRegularExpression('/^[A-Z0-9]+$/', $code);
    }

    public function test_code_must_be_unique()
    {
        $codes = collect();
        for ($i = 0; $i < 1000; $i++) {
            $codes->push((new UniquePaymentCodeGenerator)->generate());
        }

        $this->assertEquals($codes->count(), $codes->unique()->count());
    }
}
