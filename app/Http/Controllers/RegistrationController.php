<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    public function show()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:registrations,email',
            'phone' => 'required|string|max:20',
            'username' => 'required|string|max:255|unique:registrations,username',
            'carBrand' => 'required|string|max:255',
            'carModel' => 'required|string|max:255',
            'carYear' => 'required|string|max:10',
            'carColor' => 'required|string|max:100',
            'modifications' => 'nullable|string',
            'experience' => 'required|string|max:50',
            'interests' => 'nullable|array',
            'frontPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'backPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'leftPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'rightPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'interiorPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'enginePhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'agreement' => 'required|accepted',
            'newsletter' => 'nullable|boolean',
        ]);

        try {
            $photoPaths = [];
            $photoFields = ['frontPhoto', 'backPhoto', 'leftPhoto', 'rightPhoto', 'interiorPhoto', 'enginePhoto'];
            
            foreach ($photoFields as $field) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store('car-photos', 'public');
                    $photoPaths[str_replace('Photo', '_photo', $field)] = $path;
                }
            }

            $registration = Registration::create([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'username' => $request->username,
                'car_brand' => $request->carBrand,
                'car_model' => $request->carModel,
                'car_year' => $request->carYear,
                'car_color' => $request->carColor,
                'modifications' => $request->modifications,
                'experience' => $request->experience,
                'interests' => $request->interests && is_array($request->interests) ? $request->interests : [],
                'newsletter_subscription' => $request->boolean('newsletter'),
                'status' => 'pending',
            ] + $photoPaths);

            return response()->json([
                'success' => true,
                'message' => 'Qeydiyyatınız uğurla göndərildi! Təsdiqləndikdən sonra sizə məlumat veriləcək.',
                'registration_id' => $registration->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xəta baş verdi: ' . $e->getMessage()
            ], 500);
        }
    }
}
