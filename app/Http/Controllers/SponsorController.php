<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::active()
            ->ordered()
            ->get()
            ->groupBy('category');

        $categories = [
            'platinum' => $sponsors->get('platinum', collect()),
            'gold' => $sponsors->get('gold', collect()),
            'silver' => $sponsors->get('silver', collect()),
            'bronze' => $sponsors->get('bronze', collect()),
        ];

        return view('sponsors', compact('categories'));
    }
} 