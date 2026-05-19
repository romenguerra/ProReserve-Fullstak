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
        $query = $request->input('q', '');

        $results = collect();

        $models = [
            'gastronomia' => Restaurant::class,
            'deportes' => SportCenter::class,
            'ocio' => LeisureCenter::class,
            'salud' => HealthCenter::class,
            'belleza' => BeautyCenter::class,
        ];

        foreach ($models as $category => $modelClass) {
            $dbQuery = $modelClass::where('status', 'active');
            
            if (!empty($query)) {
                $dbQuery->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhereHas('services', function($sq) use ($query) {
                          $sq->where('name', 'like', "%{$query}%");
                      });
                });
            }

            $found = $dbQuery->with(['services', 'resources'])
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
