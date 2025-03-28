<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class NewsletterController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ]);

        Newsletter::create($validated);

        Log::info('Email aggiunta alla newsletter di: ', [
            'email' => $validated['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        return response()->json("Ti sei iscritto alla newsletter di " . config('app.name'), 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $email)
    {
        $request->merge(['email' => $email]);
        $request->validate([
            'email' => 'required|email'
        ]);
    
        $subscriber = Newsletter::where('email', $email)->first();
        
        // Se non esiste
        if (!$subscriber) {
            return response()->json('Email non trovata nella newsletter', 404);
        }
    
        $subscriber->delete();
    
        Log::info("Email cancellata dalla newsletter: ", [
            'email' => $subscriber->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        return response()->json('Disiscrizione a ' . config('app.name') . ' avvenuta con successo', 200);
    }
}
