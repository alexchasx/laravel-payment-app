<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Payments\FakePaymentCodeGenerator;
use App\Payments\PaymentCodeGenerator;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function create()
    {
        return view('payments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|integer|min:1',
        ]);

        $payment = $request->user()->payments()->create([
            'status_id' => PaymentStatus::NEW,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'email' => $request->email,
            'name' => $request->name,
            'description' => $request->description,
            'message' => $request->message,
            'code' => app(PaymentCodeGenerator::class)->generate(),
        ]);
    }
}
