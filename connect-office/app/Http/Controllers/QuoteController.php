<?php

namespace App\Http\Controllers;

use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'company_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string',
        ]);



        // 2. Création en base de données
        QuoteRequest::create([
            'type'         => $request->type ?? 'devis',
            'product_id'   => $request->product_id,
            'company_name' => $request->company_name,
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'message'      => $request->message,
            'status'       => 'new',
        ]);

        // 3. Retour vers la page avec message
        return back()->with('success', 'Votre demande a été envoyée avec succès.');
    }
}
