# Image Upload Setup - Windows Deployment Script
# Usage: .\deploy-images.ps1 -ProjectPath "D:\apotikdraft\Manajement-Apotik-main"

param(
    [string]$ProjectPath = ".",
    [switch]$Help
)

if ($Help) {
    Write-Host "Image Upload Setup Script for Windows" -ForegroundColor Green
    Write-Host ""
    Write-Host "Usage:" -ForegroundColor Yellow
    Write-Host "  .\deploy-images.ps1 -ProjectPath 'D:\your\project\path'"
    Write-Host ""
    exit 0
}

Write-Host "🚀 Starting Image Upload Deployment..." -ForegroundColor Green
Write-Host "Project: $ProjectPath" -ForegroundColor Cyan
Write-Host ""

# Change to project directory
Set-Location $ProjectPath

# 1. Create directories
Write-Host "📁 Creating image directories..." -ForegroundColor Yellow
try {
    New-Item -ItemType Directory -Path "storage\app\public\obat" -Force -ErrorAction Stop | Out-Null
    New-Item -ItemType Directory -Path "storage\app\public\resep" -Force -ErrorAction Stop | Out-Null
    New-Item -ItemType Directory -Path "storage\app\public\bukti-pembayaran" -Force -ErrorAction Stop | Out-Null
    Write-Host "✓ Directories created" -ForegroundColor Green
} catch {
    Write-Host "✗ Error creating directories: $_" -ForegroundColor Red
    exit 1
}
Write-Host ""

# 2. Create storage link (if not exists)
Write-Host "🔗 Verifying storage symlink..." -ForegroundColor Yellow
if (-Not (Test-Path "public\storage")) {
    try {
        $target = (Get-Item "storage\app\public").FullName
        $link = (Get-Item "public").FullName + "\storage"
        
        # Try to create junction
        & cmd /c mklink /J "$link" "$target" 2>&1 | Out-Null
        
        if (Test-Path "public\storage") {
            Write-Host "✓ Storage symlink created" -ForegroundColor Green
        } else {
            Write-Host "⚠ Could not create symlink - please run as Administrator" -ForegroundColor Yellow
            Write-Host "   Or run: php artisan storage:link" -ForegroundColor Cyan
        }
    } catch {
        Write-Host "⚠ Symlink creation skipped (requires Administrator)" -ForegroundColor Yellow
        Write-Host "   Run: php artisan storage:link" -ForegroundColor Cyan
    }
} else {
    Write-Host "✓ Storage symlink already exists" -ForegroundColor Green
}
Write-Host ""

# 3. Verify setup
Write-Host "✅ Verification..." -ForegroundColor Yellow

if (Test-Path "public\storage") {
    Write-Host "✓ Symlink active" -ForegroundColor Green
} else {
    Write-Host "✗ Symlink MISSING - Run: php artisan storage:link" -ForegroundColor Red
}

if (Test-Path "storage\app\public\obat") {
    Write-Host "✓ obat directory exists" -ForegroundColor Green
} else {
    Write-Host "✗ obat directory MISSING" -ForegroundColor Red
}

if (Test-Path "storage\app\public\resep") {
    Write-Host "✓ resep directory exists" -ForegroundColor Green
} else {
    Write-Host "✗ resep directory MISSING" -ForegroundColor Red
}

if (Test-Path "storage\app\public\bukti-pembayaran") {
    Write-Host "✓ bukti-pembayaran directory exists" -ForegroundColor Green
} else {
    Write-Host "✗ bukti-pembayaran directory MISSING" -ForegroundColor Red
}

Write-Host ""

# 4. Test Laravel artisan
Write-Host "🧪 Testing Laravel setup..." -ForegroundColor Yellow
try {
    $output = & php artisan list 2>&1 | Select-Object -First 1
    Write-Host "✓ Laravel artisan working" -ForegroundColor Green
} catch {
    Write-Host "✗ Laravel error: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "🎉 Image Upload Deployment Complete!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Test upload di application (obat, resep, atau pesanan)" -ForegroundColor Cyan
Write-Host "2. Verify gambar tampil sempurna (tidak pecah)" -ForegroundColor Cyan
Write-Host "3. Monitor: Get-Content storage\logs\laravel.log -Tail 20" -ForegroundColor Cyan
Write-Host "4. If issues: Check documentation files (*.md)" -ForegroundColor Cyan
Write-Host ""
Write-Host "Documentation:" -ForegroundColor Yellow
Write-Host "- IMAGE_UPLOAD_QUICKSTART.md" -ForegroundColor Cyan
Write-Host "- GAMBAR_UPLOAD_GUIDE.md" -ForegroundColor Cyan
Write-Host "- CHECKLIST_PERBAIKAN_GAMBAR.md" -ForegroundColor Cyan
Write-Host ""
