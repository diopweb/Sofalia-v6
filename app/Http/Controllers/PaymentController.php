<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::with(['sale', 'client'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id'  => 'required|exists:sales,id',
            'client_id'=> 'required|exists:clients,id',
            'amount'   => 'required|numeric|min:0.01',
            'method'   => 'nullable|string',
        ]);

        $payment = Payment::create($request->all());
        return response()->json($payment, 201);
    }

    public function show(Payment $payment)
    {
        return $payment->load(['sale', 'client']);
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());
        return $payment;
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }
}
