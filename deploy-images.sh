#!/bin/bash
# Image Upload Setup - Production Deployment Script
# Usage: bash deploy-images.sh /path/to/project

PROJECT_PATH="${1:-.}"

echo "🚀 Starting Image Upload Deployment..."
echo "Project: $PROJECT_PATH"
echo ""

# 1. Create directories
echo "📁 Creating image directories..."
mkdir -p "$PROJECT_PATH/storage/app/public/obat"
mkdir -p "$PROJECT_PATH/storage/app/public/resep"
mkdir -p "$PROJECT_PATH/storage/app/public/bukti-pembayaran"
echo "✓ Directories created"
echo ""

# 2. Set permissions
echo "🔒 Setting permissions..."
chmod -R 755 "$PROJECT_PATH/storage/app/public"
chmod -R 755 "$PROJECT_PATH/storage/app"
echo "✓ Permissions set to 755"
echo ""

# 3. Set ownership (if running as root)
if [ "$EUID" -eq 0 ]; then
    echo "👤 Setting file ownership..."
    chown -R www-data:www-data "$PROJECT_PATH/storage"
    echo "✓ Ownership changed to www-data:www-data"
    echo ""
fi

# 4. Create storage link
echo "🔗 Creating storage symlink..."
cd "$PROJECT_PATH"
php artisan storage:link 2>&1 || echo "⚠ Storage link might already exist"
echo "✓ Storage link created/verified"
echo ""

# 5. Verify setup
echo "✅ Verification..."
if [ -L "public/storage" ]; then
    echo "✓ Symlink active"
else
    echo "✗ Symlink MISSING - Run: php artisan storage:link"
fi

if [ -d "storage/app/public/obat" ]; then
    echo "✓ obat directory exists"
else
    echo "✗ obat directory MISSING"
fi

if [ -d "storage/app/public/resep" ]; then
    echo "✓ resep directory exists"
else
    echo "✗ resep directory MISSING"
fi

if [ -d "storage/app/public/bukti-pembayaran" ]; then
    echo "✓ bukti-pembayaran directory exists"
else
    echo "✗ bukti-pembayaran directory MISSING"
fi

echo ""
echo "🎉 Image Upload Deployment Complete!"
echo ""
echo "Next steps:"
echo "1. Test upload di application"
echo "2. Monitor logs: tail -f storage/logs/laravel.log"
echo "3. Check disk space: df -h"
echo "4. Setup backup untuk storage folder"
echo ""
