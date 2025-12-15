<?php

namespace App\Http\Controllers;

use App\Models\UnsubscribeRequest;
use Illuminate\Http\Request;

class UnsubscribeRequestController extends Controller
{
    public function index()
    {
        return UnsubscribeRequest::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:254'],
            'isCompleted' => ['boolean'],
            'note' => ['required'],
        ]);

        return UnsubscribeRequest::create($data);
    }

    public function show(UnsubscribeRequest $unsubscribeRequest)
    {
        return $unsubscribeRequest;
    }

    public function update(Request $request, UnsubscribeRequest $unsubscribeRequest)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:254'],
            'isCompleted' => ['boolean'],
            'note' => ['required'],
        ]);

        $unsubscribeRequest->update($data);

        return $unsubscribeRequest;
    }

    public function destroy(UnsubscribeRequest $unsubscribeRequest)
    {
        $unsubscribeRequest->delete();

        return response()->json();
    }
}
