<?php

namespace App\Http\Controllers;

use App\Models\WebContact;
use Illuminate\Http\Request;

class WebContactController extends Controller
{
    public function index()
    {
        return WebContact::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => ['nullable'],
            'from' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'content' => ['required'],
            'response' => ['nullable'],
            'responded_at' => ['nullable', 'date'],
            'closed' => ['boolean'],
            'action' => ['nullable'],
            'action_by' => ['required'],
        ]);

        return WebContact::create($data);
    }

    public function show(WebContact $webContact)
    {
        return $webContact;
    }

    public function update(Request $request, WebContact $webContact)
    {
        $data = $request->validate([
            'category' => ['nullable'],
            'from' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'content' => ['required'],
            'response' => ['nullable'],
            'responded_at' => ['nullable', 'date'],
            'closed' => ['boolean'],
            'action' => ['nullable'],
            'action_by' => ['required'],
        ]);

        $webContact->update($data);

        return $webContact;
    }

    public function destroy(WebContact $webContact)
    {
        $webContact->delete();

        return response()->json();
    }
}
