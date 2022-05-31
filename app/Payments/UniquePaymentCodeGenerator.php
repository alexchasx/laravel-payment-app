<?php

namespace App\Payments;

class UniquePaymentCodeGenerator implements PaymentCodeGenerator
{
    public function generate()
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 16;

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }
}
