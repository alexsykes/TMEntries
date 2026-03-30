<?php

namespace App\Http\Controllers;

use App\Models\EntryPurchase;
use Illuminate\Http\Request;

class EntryPurchaseController extends Controller
{
    public function index()
    {
        return EntryPurchase::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'entry_id' => ['required', 'integer'],
            'stripe_price_id' => ['required'],
        ]);

        return EntryPurchase::create($data);
    }

    public function show(EntryPurchase $entryPurchase)
    {
        return $entryPurchase;
    }

    public function update(Request $request, EntryPurchase $entryPurchase)
    {
        $data = $request->validate([
            'entry_id' => ['required', 'integer'],
            'stripe_price_id' => ['required'],
        ]);

        $entryPurchase->update($data);

        return $entryPurchase;
    }

    public function destroy(EntryPurchase $entryPurchase)
    {
        $entryPurchase->delete();

        return response()->json();
    }
}
