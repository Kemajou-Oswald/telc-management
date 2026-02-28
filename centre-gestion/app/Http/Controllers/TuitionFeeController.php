<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\Level;
use App\Models\TuitionFee;
use Illuminate\Http\Request;

class TuitionFeeController extends Controller
{
    public function index()
    {
        $fees = TuitionFee::with(['centre', 'level'])
            ->orderBy('language')
            ->orderBy('level_id')
            ->get();

        return view('tuition_fees.index', compact('fees'));
    }

    public function create()
    {
        $centres = Centre::orderBy('name')->get();
        $levels = Level::orderBy('name')->get();

        return view('tuition_fees.create', compact('centres', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'centre_id' => ['nullable', 'exists:centres,id'],
            'level_id' => ['required', 'exists:levels,id'],
            'language' => ['required', 'string', 'max:100'],
            'label' => ['required', 'string', 'max:255'],
            'course_type' => ['nullable', 'string', 'in:standard,vorbereitung'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'duration_label' => ['nullable', 'string', 'max:100'],
            'inscription_fee' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
        ]);

        if (empty($validated['currency'])) {
            $validated['currency'] = 'FCFA';
        }
        if (!isset($validated['course_type'])) {
            $validated['course_type'] = 'standard';
        }
        if (isset($validated['inscription_fee']) === false || $validated['inscription_fee'] === '') {
            $validated['inscription_fee'] = 10000;
        }

        TuitionFee::create($validated);

        return redirect()->route('tuition_fees.index')->with('success', 'Cours créé avec succès.');
    }
}

