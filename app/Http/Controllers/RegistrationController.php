<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Araba Kayıt İşlemlerini Yöneten Controller
 * 
 * Bu controller, kullanıcıların araba kayıt formlarını işler ve
 * yüklenen fotoğrafları güvenli bir şekilde saklar.
 */
class RegistrationController extends Controller
{
    /**
     * Kayıt formunu görüntüler
     * 
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('register');
    }

    /**
     * Kayıt formunu işler ve veritabanına kaydeder
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Form validasyonu - tüm gerekli alanları kontrol eder
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'car_brand' => 'required|string|max:100',
            'car_model' => 'required|string|max:100',
            'car_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'engine_size' => 'required|string|max:50',
            'modifications' => 'nullable|string',
            'experience_years' => 'required|integer|min:0|max:50',
            'interests' => 'nullable|array',
            'interests.*' => 'string',
            'newsletter' => 'boolean',
            
            // Fotoğraf validasyonu - her fotoğraf için ayrı kurallar
            'photo_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_back' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_left' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_right' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_interior' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_engine' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            // Özel hata mesajları - Azerbaycan dili
            'full_name.required' => 'Ad və soyad tələb olunur',
            'phone.required' => 'Telefon nömrəsi tələb olunur',
            'email.required' => 'E-mail ünvanı tələb olunur',
            'email.email' => 'Düzgün e-mail ünvanı daxil edin',
            'car_brand.required' => 'Avtomobil markası tələb olunur',
            'car_model.required' => 'Avtomobil modeli tələb olunur',
            'car_year.required' => 'İl tələb olunur',
            'engine_size.required' => 'Mühərrik həcmi tələb olunur',
            'experience_years.required' => 'Təcrübə ili tələb olunur',
            'photo_front.required' => 'Ön foto tələb olunur',
            'photo_back.required' => 'Arxa foto tələb olunur',
            'photo_left.required' => 'Sol foto tələb olunur',
            'photo_right.required' => 'Sağ foto tələb olunur',
            'photo_interior.required' => 'İç foto tələb olunur',
            'photo_engine.required' => 'Mühərrik foto tələb olunur',
            'photo_front.image' => 'Ön foto şəkil formatında olmalıdır',
            'photo_back.image' => 'Arxa foto şəkil formatında olmalıdır',
            'photo_left.image' => 'Sol foto şəkil formatında olmalıdır',
            'photo_right.image' => 'Sağ foto şəkil formatında olmalıdır',
            'photo_interior.image' => 'İç foto şəkil formatında olmalıdır',
            'photo_engine.image' => 'Mühərrik foto şəkil formatında olmalıdır',
        ]);

        // Validasyon hatası varsa JSON yanıt döner
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Fotoğrafları güvenli bir şekilde yükler
            $photoPaths = $this->uploadPhotos($request);
            
            // Veritabanına kayıt oluşturur
            $registration = Registration::create([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'car_brand' => $request->car_brand,
                'car_model' => $request->car_model,
                'car_year' => $request->car_year,
                'engine_size' => $request->engine_size,
                'modifications' => $request->modifications,
                'experience_years' => $request->experience_years,
                'interests' => $request->interests ?? [], // Boş array varsayılanı
                'newsletter_subscription' => $request->boolean('newsletter'),
                
                // Fotoğraf yollarını kaydeder
                'photo_front' => $photoPaths['front'],
                'photo_back' => $photoPaths['back'],
                'photo_left' => $photoPaths['left'],
                'photo_right' => $photoPaths['right'],
                'photo_interior' => $photoPaths['interior'],
                'photo_engine' => $photoPaths['engine'],
                
                // Varsayılan durum: bekliyor
                'status' => 'pending',
            ]);

            // Başarılı yanıt döner
            return response()->json([
                'success' => true,
                'message' => 'Müraciətiniz uğurla qeydə alındı. Admin tərəfindən təsdiqləndikdən sonra sizə məlumat veriləcək.',
                'registration_id' => $registration->id
            ]);

        } catch (\Exception $e) {
            // Hata durumunda yüklenen dosyaları temizler
            $this->cleanupUploadedFiles($photoPaths ?? []);
            
            return response()->json([
                'success' => false,
                'message' => 'Xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.'
            ], 500);
        }
    }

    /**
     * Yüklenen fotoğrafları güvenli bir şekilde saklar
     * 
     * @param Request $request
     * @return array
     */
    private function uploadPhotos(Request $request)
    {
        $photoPaths = [];
        $photoFields = ['front', 'back', 'left', 'right', 'interior', 'engine'];
        
        foreach ($photoFields as $field) {
            if ($request->hasFile("photo_{$field}")) {
                $file = $request->file("photo_{$field}");
                
                // Benzersiz dosya adı oluşturur
                $fileName = time() . '_' . uniqid() . '_' . $field . '.' . $file->getClientOriginalExtension();
                
                // Dosyayı storage/app/public/car-photos klasörüne kaydeder
                $path = $file->storeAs('car-photos', $fileName, 'public');
                
                $photoPaths[$field] = $path;
            }
        }
        
        return $photoPaths;
    }

    /**
     * Hata durumunda yüklenen dosyaları temizler
     * 
     * @param array $photoPaths
     */
    private function cleanupUploadedFiles(array $photoPaths)
    {
        foreach ($photoPaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
