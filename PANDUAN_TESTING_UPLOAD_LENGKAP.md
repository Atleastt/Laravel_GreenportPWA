# Panduan Lengkap Testing Upload Dokumen

## 📋 Daftar Isi
1. [Persiapan Awal](#persiapan-awal)
2. [Testing via Terminal](#testing-via-terminal)
3. [Testing via Web UI](#testing-via-web-ui)
4. [Jenis Test yang Tersedia](#jenis-test-yang-tersedia)
5. [Interpretasi Hasil](#interpretasi-hasil)
6. [Troubleshooting](#troubleshooting)

---

## 🚀 Persiapan Awal

### 1. Pastikan Server Berjalan
```bash
# Jalankan server Laravel
php artisan serve
```
Server akan berjalan di: http://127.0.0.1:8000

### 2. Pastikan Database Siap
```bash
# Jalankan migrasi jika belum
php artisan migrate

# Seed data jika diperlukan
php artisan db:seed
```

### 3. Pastikan Storage Siap
```bash
# Buat symbolic link untuk storage
php artisan storage:link

# Pastikan folder storage writable
chmod -R 775 storage/
```

---

## 🖥️ Testing via Terminal

### Cara 1: Menjalankan Semua Test Upload
```bash
php artisan test --filter DocumentUploadTest
```

### Cara 2: Menjalankan Test Spesifik
```bash
# Test file size 2MB
php artisan test --filter DocumentUploadTest::test_upload_2mb_file

# Test file size 5MB
php artisan test --filter DocumentUploadTest::test_upload_5mb_file

# Test file size 10MB
php artisan test --filter DocumentUploadTest::test_upload_10mb_file

# Test file size 50MB
php artisan test --filter DocumentUploadTest::test_upload_50mb_file

# Test integritas file
php artisan test --filter DocumentUploadTest::test_file_integrity

# Test validasi file
php artisan test --filter DocumentUploadTest::test_file_validation
```

### Cara 3: Menggunakan Script Otomatis
```bash
# Jalankan script interactive
chmod +x run_upload_tests.sh
./run_upload_tests.sh
```

---

## 🌐 Testing via Web UI

### 1. Akses Halaman Test
Buka browser dan kunjungi: http://127.0.0.1:8000/test-upload

### 2. Fitur yang Tersedia di UI:

#### A. Test Upload Manual
- **Upload File**: Pilih file dan upload langsung
- **File Size Test**: Test dengan berbagai ukuran (2MB, 5MB, 10MB, 50MB)
- **File Type Test**: Test berbagai format file
- **Simulasi Offline/Online**: Toggle mode koneksi

#### B. Test Otomatis
- **Run All Tests**: Jalankan semua test sekaligus
- **Performance Test**: Ukur waktu upload
- **Integrity Test**: Verifikasi file integrity
- **Batch Upload**: Upload multiple files

#### C. Monitoring Real-time
- **Progress Bar**: Lihat progress upload
- **File Size Info**: Informasi ukuran file
- **Upload Speed**: Kecepatan upload
- **Error Handling**: Penanganan error

---

## 📊 Jenis Test yang Tersedia

### 1. Test Ukuran File
| Ukuran | Deskripsi | Expected Result |
|--------|-----------|-----------------|
| 2MB | Test file kecil | ✅ Success |
| 5MB | Test file sedang | ✅ Success |
| 10MB | Test file besar | ✅ Success |
| 50MB | Test file sangat besar | ⚠️ Depends on config |

### 2. Test Format File
| Format | Status | Catatan |
|--------|--------|---------|
| PDF | ✅ Allowed | Format utama |
| DOC/DOCX | ✅ Allowed | Microsoft Word |
| JPG/JPEG | ✅ Allowed | Gambar |
| PNG | ✅ Allowed | Gambar |
| TXT | ❌ Not allowed | Plain text |
| EXE | ❌ Not allowed | Executable |

### 3. Test Performa
- **Upload Speed**: Mengukur kecepatan upload
- **Memory Usage**: Monitor penggunaan memory
- **Processing Time**: Waktu pemrosesan file
- **Concurrent Upload**: Upload simultan

### 4. Test Integritas
- **File Hash**: Verifikasi hash file
- **File Size**: Verifikasi ukuran file
- **File Content**: Verifikasi isi file
- **Metadata**: Verifikasi metadata file

---

## 📈 Interpretasi Hasil

### Terminal Output
```bash
✅ PASS Tests\Feature\DocumentUploadTest::test_upload_2mb_file
✅ PASS Tests\Feature\DocumentUploadTest::test_upload_5mb_file
✅ PASS Tests\Feature\DocumentUploadTest::test_upload_10mb_file
⚠️  PASS Tests\Feature\DocumentUploadTest::test_upload_50mb_file
✅ PASS Tests\Feature\DocumentUploadTest::test_file_integrity
✅ PASS Tests\Feature\DocumentUploadTest::test_file_validation

Tests:  6 passed
Time:   12.34s
```

### Web UI Indicators
- **🟢 Green**: Test berhasil
- **🟡 Yellow**: Warning atau timeout
- **🔴 Red**: Test gagal
- **📊 Progress Bar**: Menunjukkan progress upload

---

## 🔧 Troubleshooting

### Problem 1: File Upload Gagal
**Gejala**: Error 413 atau file tidak ter-upload
**Solusi**:
```bash
# Periksa konfigurasi PHP
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Edit php.ini jika perlu
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
```

### Problem 2: Permission Denied
**Gejala**: Error permission saat menyimpan file
**Solusi**:
```bash
# Fix permission storage
chmod -R 775 storage/
chown -R www-data:www-data storage/ # Untuk production
```

### Problem 3: Test Timeout
**Gejala**: Test berhenti karena timeout
**Solusi**:
```bash
# Tambahkan timeout di phpunit.xml
<phpunit timeoutForSmallTests="60" 
         timeoutForMediumTests="180" 
         timeoutForLargeTests="300">
```

### Problem 4: Memory Limit
**Gejala**: Fatal error memory exhausted
**Solusi**:
```bash
# Increase memory limit
php -d memory_limit=512M artisan test
```

### Problem 5: Database Error
**Gejala**: Error saat menyimpan ke database
**Solusi**:
```bash
# Reset database test
php artisan migrate:fresh --env=testing
php artisan db:seed --env=testing
```

---

## ⚡ Quick Commands

### Setup Cepat
```bash
# One-liner setup
php artisan migrate && php artisan storage:link && php artisan serve &
```

### Test Cepat
```bash
# Quick test semua upload
php artisan test --filter DocumentUploadTest --stop-on-failure
```

### Clean Up
```bash
# Bersihkan file test
rm -rf storage/app/uploads/test_*
php artisan cache:clear
```

---

## 📝 Tips dan Best Practices

### 1. Sebelum Testing
- ✅ Pastikan server running
- ✅ Check disk space available
- ✅ Backup database jika perlu
- ✅ Monitor system resources

### 2. Selama Testing
- 📊 Monitor progress via UI
- 🔍 Check terminal output
- 💾 Verify file storage
- 📈 Note performance metrics

### 3. Setelah Testing
- 🧹 Clean up test files
- 📋 Review test results
- 📝 Document any issues
- 🔄 Plan improvements

---

## 🎯 Kesimpulan

### Kapan Menggunakan Terminal vs UI?

#### Terminal Testing ✅
- **Automation**: CI/CD pipeline
- **Batch Testing**: Multiple test runs
- **Performance**: Faster execution
- **Scripting**: Automated workflows

#### UI Testing ✅
- **Interactive**: Real user experience
- **Visual**: Progress monitoring
- **Debugging**: Step-by-step testing
- **Demo**: Presentasi ke stakeholder

### Command Favorit
```bash
# Untuk development sehari-hari
php artisan test --filter DocumentUploadTest

# Untuk demo dan presentasi
# Buka: http://127.0.0.1:8000/test-upload

# Untuk CI/CD
php artisan test --filter DocumentUploadTest --coverage
```

---

**💡 Pro Tip**: Gunakan kombinasi terminal dan UI testing untuk hasil optimal. Terminal untuk automation dan UI untuk user experience testing!
