<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Vote;
use App\Models\VotingCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Oylama Sistemi Controller'ı
 * 
 * Bu controller, oylama sayfasını görüntüler ve oylama API'lerini yönetir.
 * Kod doğrulama, oy verme ve istatistik alma işlemlerini gerçekleştirir.
 */
class VotingController extends Controller
{
    /**
     * Oylama sayfasını görüntüler
     * 
     * Onaylanmış arabaları listeler ve başlangıç istatistiklerini gösterir.
     * 
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Sadece onaylanmış arabaları getirir
        $approvedCars = Registration::where('status', 'approved')
            ->with('votes') // Oy ilişkisini yükler
            ->get();
        
        // Başlangıç istatistiklerini hesaplar
        $stats = $this->calculateStats($approvedCars);
        
        return view('voting', compact('approvedCars', 'stats'));
    }

    /**
     * Oylama kodunu doğrular
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(Request $request)
    {
        // Gelen veriyi doğrular
        $request->validate([
            'code' => 'required|string|max:10'
        ]);

        $code = strtoupper(trim($request->code));

        // Kodu veritabanında arar
        $votingCode = VotingCode::where('code', $code)->first();

        if (!$votingCode) {
            return response()->json([
                'success' => false,
                'message' => 'Kod tapılmadı. Zəhmət olmasa düzgün kodu daxil edin.'
            ]);
        }

        // Kodun daha önce kullanılıp kullanılmadığını kontrol eder
        if ($votingCode->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kod artıq istifadə edilib. Hər kod yalnız bir dəfə istifadə edilə bilər.'
            ]);
        }

        // Kod geçerliyse başarılı yanıt döner
        return response()->json([
            'success' => true,
            'message' => 'Kod təsdiqləndi. İndi oy verə bilərsiniz.',
            'code_id' => $votingCode->id
        ]);
    }

    /**
     * Oy verme işlemini gerçekleştirir
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request)
    {
        // Gelen veriyi doğrular
        $request->validate([
            'code_id' => 'required|integer|exists:voting_codes,id',
            'registration_id' => 'required|integer|exists:registrations,id'
        ]);

        // Transaction başlatır - veri tutarlılığı için
        return DB::transaction(function () use ($request) {
            
            // Kodu tekrar kontrol eder (race condition'a karşı)
            $votingCode = VotingCode::lockForUpdate()->find($request->code_id);
            
            if (!$votingCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kod tapılmadı.'
                ]);
            }

            if ($votingCode->is_used) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu kod artıq istifadə edilib.'
                ]);
            }

            // Arabayı kontrol eder
            $registration = Registration::find($request->registration_id);
            
            if (!$registration || $registration->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Seçilən avtomobil tapılmadı və ya təsdiqlənməyib.'
                ]);
            }

            // Oy kaydını oluşturur
            Vote::create([
                'voting_code_id' => $votingCode->id,
                'registration_id' => $registration->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Kodu kullanıldı olarak işaretler
            $votingCode->update([
                'is_used' => true,
                'used_at' => now(),
                'used_ip' => $request->ip()
            ]);

            // Güncel istatistikleri hesaplar
            $stats = $this->getStats();

            return response()->json([
                'success' => true,
                'message' => 'Oy uğurla qeydə alındı. Təşəkkür edirik!',
                'stats' => $stats
            ]);
        });
    }

    /**
     * Güncel oylama istatistiklerini döner
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        $stats = $this->calculateStats();
        
        return response()->json($stats);
    }

    /**
     * İstatistikleri hesaplar
     * 
     * @param Collection|null $cars (opsiyonel - belirtilmezse tüm onaylanmış arabaları alır)
     * @return array
     */
    private function calculateStats($cars = null)
    {
        if ($cars === null) {
            $cars = Registration::where('status', 'approved')->with('votes')->get();
        }

        // Toplam oy sayısını hesaplar
        $totalVotes = Vote::count();
        
        // Kullanılan kod sayısını hesaplar
        $usedCodes = VotingCode::where('is_used', true)->count();
        
        // Toplam araba sayısını hesaplar
        $totalCars = $cars->count();

        // Her arabanın oy sayısını ve yüzdesini hesaplar
        $carsWithStats = $cars->map(function ($car) use ($totalVotes) {
            $voteCount = $car->votes->count();
            $percentage = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100, 1) : 0;
            
            return [
                'id' => $car->id,
                'votes' => $voteCount,
                'percentage' => $percentage
            ];
        });

        return [
            'total_votes' => $totalVotes,
            'total_cars' => $totalCars,
            'used_codes' => $usedCodes,
            'cars' => $carsWithStats
        ];
    }
}
