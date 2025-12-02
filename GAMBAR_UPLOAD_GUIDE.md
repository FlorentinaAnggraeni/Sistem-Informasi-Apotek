# Image Upload & Storage Guide

## ✅ Perbaikan Upload Gambar

### Masalah Lama
- Gambar upload rusak/pecah
- Format file tidak konsisten
- Tidak ada kompresi
- Storage tidak ter-link dengan public

### Solusi Baru
1. **Menggunakan Intervention Image** - Kompresi gambar otomatis
2. **Service Layer** - `ImageService` menangani semua upload
3. **Error Handling** - Validasi ketat untuk mencegah file rusak
4. **Storage Link** - Sudah di-setup otomatis

---

## 📋 Konfigurasi

### 1. Storage Symlink
Sudah di-run otomatis:
```bash
php artisan storage:link
```

Hasil:
```
public/storage → storage/app/public
```

### 2. Direktori Upload
```
storage/app/public/
├── obat/           # Gambar obat
├── resep/          # Foto resep dokter
└── bukti-pembayaran/  # Bukti pembayaran
```

---

## 🛠️ Implementasi di Controller

### ObatController - Upload Gambar Obat
```php
use App\Services\ImageService;

class ObatController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(Request $request)
    {
        if ($request->hasFile('gambar_obat')) {
            try {
                $path = $this->imageService->uploadAndCompress(
                    $request->file('gambar_obat'), 
                    'obat',  // folder
                    800,     // max width
                    80       // quality
                );
                $validated['gambar_obat'] = $path;
            } catch (\Exception $e) {
                return back()->withErrors(['gambar_obat' => $e->getMessage()]);
            }
        }
    }
}
```

### ResepController - Upload Foto Resep
```php
// Folder: resep, Max Width: 1200px, Quality: 85
$path = $this->imageService->uploadAndCompress(
    $request->file('foto_resep'),
    'resep',
    1200,
    85
);
```

### PesananController - Upload Bukti Pembayaran
```php
// Folder: bukti-pembayaran, Max Width: 1000px, Quality: 85
$path = $this->imageService->uploadAndCompress(
    $request->file('bukti_pembayaran'),
    'bukti-pembayaran',
    1000,
    85
);
```

---

## 📊 Optimasi Gambar

### Compression Settings
| Tipe | Folder | Max Width | Quality | Ukuran Maksimal |
|------|--------|-----------|---------|-----------------|
| Obat | `obat` | 800px | 80% | 2MB |
| Resep | `resep` | 1200px | 85% | 5MB |
| Bukti Pembayaran | `bukti-pembayaran` | 1000px | 85% | 2MB |

### Format Support
- Input: JPEG, PNG, JPG
- Output: JPEG (terkompresi)

---

## 💾 Penggunaan ImageService

### Upload File
```php
$imageService = app(ImageService::class);

$path = $imageService->uploadAndCompress(
    $file,           // UploadedFile
    'obat',          // folder
    800,             // max width
    80               // quality (0-100)
);
// Return: "obat/1234567890_abc123_nama.jpg"
```

### Get Image URL
```php
$url = $imageService->getUrl('obat/1234567890_abc123.jpg');
// Return: "http://localhost/storage/obat/1234567890_abc123.jpg"
```

### Delete Image
```php
$imageService->delete('obat/1234567890_abc123.jpg');
```

### Delete Multiple Images
```php
$imageService->deleteMultiple([
    'obat/image1.jpg',
    'obat/image2.jpg'
]);
```

---

## 🎨 Penggunaan di Blade Template

### Simple Image Display
```blade
<img src="{{ $imageService->getUrl($obat->gambar_obat) }}" alt="Obat" class="img-fluid">
```

### Dengan Fallback
```blade
@if($obat->gambar_obat)
    <img src="{{ asset('storage/' . $obat->gambar_obat) }}" alt="{{ $obat->nama_obat }}" class="img-fluid rounded">
@else
    <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="img-fluid rounded">
@endif
```

### Responsive Image
```blade
<img src="{{ asset('storage/' . $obat->gambar_obat) }}" 
     alt="{{ $obat->nama_obat }}" 
     class="img-fluid rounded"
     loading="lazy"
     decoding="async">
```

---

## 🔍 Troubleshooting

### 1. Gambar Tetap Tidak Muncul
```bash
# Pastikan storage link ada
php artisan storage:link

# Set permissions
chmod -R 755 storage/app/public
chmod -R 755 public/storage
```

### 2. Error: "Can't guess image format"
- Pastikan file adalah image valid (JPEG/PNG/JPG)
- Check file size < limit
- Validate MIME type di form validation

### 3. Error: "Unable to write image"
- Storage permissions tidak valid
- Disk space penuh
- Check `storage/logs/` untuk error detail

### 4. Gambar Masih "Pecah"
- Clear browser cache (Ctrl+Shift+Delete)
- Hard refresh (Ctrl+F5)
- Check console untuk 404 errors
- Pastikan symlink ada: `php artisan storage:link`

---

## 📝 Validation Rules

### Obat
```php
'gambar_obat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
```

### Resep
```php
'foto_resep' => 'required|image|mimes:jpeg,png,jpg|max:5120'
```

### Bukti Pembayaran
```php
'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048'
```

---

## 🚀 Deployment Checklist

- [x] Storage link sudah di-setup
- [x] Upload directories sudah dibuat
- [x] Permissions sudah di-set (755+)
- [x] ImageService dengan Intervention Image
- [x] Controllers menggunakan ImageService
- [x] Error handling di setiap upload
- [x] Blade templates menggunakan helper functions
- [ ] Jalankan: `php artisan storage:link` di production
- [ ] Set environment variables di `.env` production
- [ ] Test upload dengan berbagai format/ukuran

---

## 📚 Dependencies

Required:
- `intervention/image` - untuk kompresi
- Laravel 12.x

Install:
```bash
composer require intervention/image
```

---

## 🔐 Security Notes

- ✅ File validation ketat
- ✅ Rename file → mencegah path traversal
- ✅ Disimpan di storage (bukan public)
- ✅ Accessible melalui symlink
- ⚠️ CORS tidak set (add jika perlu API)
