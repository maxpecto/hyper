<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard()
    {
        $registrations = Registration::orderBy('created_at', 'desc')->get();
        $stats = [
            'total' => Registration::count(),
            'pending' => Registration::where('status', 'pending')->count(),
            'approved' => Registration::where('status', 'approved')->count(),
            'rejected' => Registration::where('status', 'rejected')->count(),
        ];
        
        return view('admin.dashboard', compact('registrations', 'stats'));
    }

    public function approve($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Send WhatsApp notification
        $this->sendWhatsAppNotification($registration);
        
        // Send email notification
        $this->sendEmailNotification($registration);

        return response()->json([
            'success' => true,
            'message' => 'Başvuru təsdiqləndi və bildiriş göndərildi.'
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        $registration = Registration::findOrFail($id);
        $registration->update([
            'status' => 'rejected',
            'admin_notes' => $request->notes,
            'rejected_at' => now(),
        ]);

        // Send rejection notification
        $this->sendRejectionNotification($registration);

        return response()->json([
            'success' => true,
            'message' => 'Başvuru rədd edildi və bildiriş göndərildi.'
        ]);
    }

    private function sendWhatsAppNotification($registration)
    {
        // WhatsApp API integration would go here
        // For now, we'll just log the message
        $message = "Salam {$registration->first_name}! HyperDrive qeydiyyatınız təsdiqləndi. Ətraflı məlumat üçün bizimlə əlaqə saxlayın.";
        
        \Log::info('WhatsApp notification sent', [
            'phone' => $registration->phone,
            'message' => $message
        ]);
    }

    private function sendEmailNotification($registration)
    {
        // Email notification logic would go here
        $message = "Salam {$registration->first_name}! HyperDrive qeydiyyatınız təsdiqləndi. Ətraflı məlumat üçün bizimlə əlaqə saxlayın.";
        
        \Log::info('Email notification sent', [
            'email' => $registration->email,
            'message' => $message
        ]);
    }

    private function sendRejectionNotification($registration)
    {
        $message = "Salam {$registration->first_name}! HyperDrive qeydiyyatınız rədd edildi. Səbəb: {$registration->admin_notes}";
        
        \Log::info('Rejection notification sent', [
            'email' => $registration->email,
            'phone' => $registration->phone,
            'message' => $message
        ]);
    }
}
