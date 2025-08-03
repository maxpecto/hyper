<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Arabalar sayfasını göster
     */
    public function index(Request $request)
    {
        $query = Car::getActiveCars();

        // Kategori filtresi
        $category = $request->get('category', 'all');
        $query = $query->byCategory($category);

        // Arama filtresi
        $search = $request->get('search');
        $query = $query->search($search);

        $cars = $query->get();

        return view('cars', compact('cars', 'category', 'search'));
    }

    /**
     * Araba detaylarını göster
     */
    public function show($id)
    {
        $car = Car::findOrFail($id);
        return view('car-details', compact('car'));
    }
} 