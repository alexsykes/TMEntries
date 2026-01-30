<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class TmeScoreController extends Controller
{
    public function index()
    {
        return Score::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'trial_id' => ['required', 'exists:trials'],
            'day' => ['required', 'integer'],
            'sheet' => ['required', 'integer'],
            'rider' => ['required', 'integer'],
            'lap' => ['required', 'integer'],
            'section' => ['required', 'integer'],
            'score' => ['nullable'],
        ]);

        return Score::create($data);
    }

    public function show(Score $tmeScore)
    {
        return $tmeScore;
    }

    public function update(Request $request, Score $tmeScore)
    {
        $data = $request->validate([
            'trial_id' => ['required', 'exists:trials'],
            'day' => ['required', 'integer'],
            'sheet' => ['required', 'integer'],
            'rider' => ['required', 'integer'],
            'lap' => ['required', 'integer'],
            'section' => ['required', 'integer'],
            'score' => ['nullable'],
        ]);

        $tmeScore->update($data);

        return $tmeScore;
    }

    public function destroy(Score $tmeScore)
    {
        $tmeScore->delete();

        return response()->json();
    }
}
