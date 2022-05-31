<?php

namespace App\Payments;

class FakePaymentCodeGenerator implements PaymentCodeGenerator
{
    public function generate()
    {
        // throw new \Exception('Method generate() is not implemented.');
        return 'TESTCODEJSAUA2341';
    }
}
