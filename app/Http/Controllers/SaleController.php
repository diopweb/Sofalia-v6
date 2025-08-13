<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ProductVariant;
use App\Models\Payment;
use App\Models\ClientTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        return Sale::with(['client', 'items.variant', 'payments'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items'     => 'required|array|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'client_id'      => $request->client_id,
                'invoice_number' => 'INV-' . time(),
                'total'          => 0,
                'status'         => 'pending',
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $lineTotal = $variant->price * $item['qty'];
                $total += $lineTotal;

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'variant_id' => $variant->id,
                    'qty'        => $item['qty'],
                    'unit_price' => $variant->price,
                    'total'      => $lineTotal,
                ]);
            }

            $sale->update(['total' => $total]);

            return $sale->load('items.variant');
        });
    }

    public function show(Sale $sale)
    {
        return $sale->load(['client', 'items.variant', 'payments']);
    }

    public function update(Request $request, Sale $sale)
    {
        $sale->update($request->only(['status']));
        return $sale;
    }

    public function destroy(Sale $sale)
    {
        $sale->items()->delete();
        $sale->payments()->delete();
        $sale->delete();
        return response()->noContent();
    }

    public function addPayment(Request $request, Sale $sale)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $sale) {
            $transaction = ClientTransaction::create([
                'client_id' => $sale->client_id,
                'type'      => 'credit',
                'amount'    => $request->amount,
                'note'      => 'Paiement facture ' . $sale->invoice_number,
            ]);

            $payment = Payment::create([
                'sale_id'               => $sale->id,
                'client_transaction_id' => $transaction->id,
                'client_id'             => $sale->client_id,
                'amount'                => $request->amount,
                'method'                => $request->method,
            ]);

            return $payment;
        });
    }
}
