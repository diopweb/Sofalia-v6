<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientTransaction;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::with('transactions')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    public function show(Client $client)
    {
        return $client->load('transactions');
    }

    public function update(Request $request, Client $client)
    {
        $client->update($request->all());
        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->noContent();
    }

    public function addCredit(Request $request, Client $client)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);

        $transaction = $client->transactions()->create([
            'type'   => 'credit',
            'amount' => $request->amount,
            'note'   => 'Acompte ajouté',
        ]);

        return response()->json($transaction, 201);
    }

    public function addDebt(Request $request, Client $client)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);

        $transaction = $client->transactions()->create([
            'type'   => 'debt',
            'amount' => $request->amount,
            'note'   => 'Dette enregistrée',
        ]);

        return response()->json($transaction, 201);
    }
}
