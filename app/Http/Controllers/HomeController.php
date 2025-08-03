<?php

namespace App\Http\Controllers;

use App\Models\HomePage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Ana sayfayı göster
     */
    public function index()
    {
        $homeSettings = HomePage::getSettings();
        
        return view('home', compact('homeSettings'));
    }
} 