<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Etkinlikler sayfasını göster
     */
    public function index()
    {
        $settings = Event::getSettings();
        
        return view('events', compact('settings'));
    }
} 