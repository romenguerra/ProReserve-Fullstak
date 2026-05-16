<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Restaurant;
use App\Models\SportCenter;
use App\Models\LeisureCenter;
use App\Models\HealthCenter;
use App\Models\BeautyCenter;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query) {
            return Inertia::render('Search', [
                'results' => [],
                'query' => ''
            ]);
        }

        $results = collect();

        $models = [
            'gastronomia' => Restaurant::class,
            'deportes' => SportCenter::class,
            'ocio' => LeisureCenter::class,
            'salud' => HealthCenter::class,
            'belleza' => BeautyCenter::class,
        ];

        foreach ($models as $category => $modelClass) {
            $found = $modelClass::where('status', 'active')
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhereHas('services', function($sq) use ($query) {
                          $sq->where('name', 'like', "%{$query}%");
                      });
                })
                ->with(['services', 'resources'])
                ->get()
                ->map(function($item) use ($category) {
                    $item->category = $category;
                    return $item;
                });
            
            $results = $results->concat($found);
        }

        return Inertia::render('Search', [
            'results' => $results,
            'query' => $query
        ]);
    }
}
