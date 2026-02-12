<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Interest;
use Inertia\Inertia;

class InterestController extends Controller
{
    public function create()
    {
        return Inertia::render('Interests/Setup', [
            'interests' => Interest::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'interests' => 'array',
            'interests.*' => 'exists:interests,id',
        ]);

        $user = auth()->user();
        
        if ($request->has('interests')) {
            $user->interests()->sync($request->interests);
        }

        return redirect()->route('home');
    }
}
