<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SupportRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = SupportRequest::with(['centre', 'creator', 'resolver'])
            ->orderByDesc('created_at');

        if ($user->role === 'super_admin') {
            // voit tout
        } else {
            // Directeur / Secrétaire voient uniquement leur centre
            $query->where('centre_id', $user->centre_id);
        }

        $requests = $query->paginate(15);

        return view('support_requests.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        SupportRequest::create([
            'centre_id' => $user->centre_id,
            'created_by' => $user->id,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => isset($validated['description']) ? $validated['description'] : null,
            'status' => 'ouvert',
        ]);

        return back()->with('success', 'Demande enregistrée et envoyée à la direction.');
    }

    public function resolve(SupportRequest $supportRequest)
    {
        $user = auth()->user();

        if ($user->role === 'directeur' && $supportRequest->centre_id !== $user->centre_id) {
            abort(403);
        }

        if ($supportRequest->status === 'resolu') {
            return back()->with('info', 'Cette demande est déjà résolue.');
        }

        $supportRequest->update([
            'status' => 'resolu',
            'resolved_by' => $user->id,
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Demande marquée comme résolue.');
    }
}

