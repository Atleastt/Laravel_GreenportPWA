# 📚 Dokumentasi Lengkap - Sistem Audit Green Port

## 📋 Daftar Isi

1. [Tentang Aplikasi](#tentang-aplikasi)
2. [Persyaratan Sistem](#persyaratan-sistem)
3. [Instalasi](#instalasi)
4. [Konfigurasi](#konfigurasi)
5. [Menjalankan Aplikasi](#menjalankan-aplikasi)
6. [Testing](#testing)
7. [Fitur Utama](#fitur-utama)
8. [Struktur Database](#struktur-database)
9. [API Documentation](#api-documentation)
10. [Deployment](#deployment)
11. [Troubleshooting](#troubleshooting)
12. [FAQ](#faq)

---

## 🎯 Tentang Aplikasi

**Sistem Audit Green Port** adalah aplikasi web berbasis Laravel untuk mengelola proses audit kepatuhan lingkungan pada pelabuhan. Aplikasi ini menyediakan fitur manajemen audit, upload dokumen bukti, checklist kepatuhan, dan pelaporan.

### Teknologi yang Digunakan

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade Templates + Alpine.js + Tailwind CSS
- **Database**: SQLite (development) / MySQL/PostgreSQL (production)
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage
- **Task Runner**: Vite
- **Testing**: PHPUnit
- **PWA Support**: Laravel PWA

---

## 💻 Persyaratan Sistem

### Minimum Requirements

- **PHP**: 8.2 atau lebih tinggi
- **Composer**: 2.x
- **Node.js**: 18.x atau lebih tinggi
- **NPM**: 9.x atau lebih tinggi
- **Web Server**: Apache/Nginx
- **Database**: SQLite/MySQL/PostgreSQL

### Extensions PHP yang Diperlukan

```bash
# Cek extensions yang tersedia
php -m

# Extensions yang harus ada:
- openssl
- pdo
- mbstring
- tokenizer
- xml
- ctype
- json
- bcmath
- fileinfo
- gd (untuk image processing)
- zip (untuk file compression)
```

### Tools Development (Opsional)

- **Git**: Version control
- **VS Code**: Code editor (recommended)
- **TablePlus/PhpMyAdmin**: Database management
- **Postman**: API testing

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
# Clone project
git clone [URL_REPOSITORY]
cd greenport

# Atau jika sudah ada folder
cd /Users/leonfarhan/Documents/code/joki/TA-aom/greenport
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies  
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Buat database SQLite (default)
touch database/database.sqlite

# Atau edit .env untuk menggunakan MySQL/PostgreSQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=greenport
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Jalankan migrations
php artisan migrate

# Seed data (opsional)
php artisan db:seed
```

### 5. Storage Setup

```bash
# Buat symbolic link untuk storage
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Untuk production server
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### 6. Build Assets

```bash
# Development mode
npm run dev

# Production build
npm run build
```

---

## ⚙️ Konfigurasi

### File Konfigurasi Utama

#### 1. `.env` Configuration

```bash
# Application
APP_NAME="Green Port Audit System"
APP_ENV=local
APP_KEY=base64:your_generated_key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=greenport
# DB_USERNAME=root
# DB_PASSWORD=

# File Upload Settings
FILESYSTEM_DISK=local
UPLOAD_MAX_SIZE=10240  # in KB (10MB)

# Queue
QUEUE_CONNECTION=database

# Mail (for notifications)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@greenport.com"
MAIL_FROM_NAME="${APP_NAME}"

# PWA Settings
VITE_APP_NAME="${APP_NAME}"
```

#### 2. PHP Configuration (`php.ini`)

```ini
# File Upload Settings
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
max_input_time = 300
memory_limit = 512M

# Session Settings
session.gc_maxlifetime = 7200
```

#### 3. Web Server Configuration

**Apache (.htaccess)**
```apache
# Sudah termasuk dalam Laravel public/.htaccess
# Pastikan mod_rewrite enabled
```

**Nginx**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/greenport/public;
    index index.php;

    client_max_body_size 64M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## 🏃‍♂️ Menjalankan Aplikasi

### Development Mode

```bash
# Method 1: Artisan Serve (Simple)
php artisan serve
# Akses: http://127.0.0.1:8000

# Method 2: Dengan Queue & Assets (Recommended)
composer run dev
# Ini akan menjalankan:
# - php artisan serve
# - php artisan queue:listen
# - php artisan pail (logs)
# - npm run dev (Vite)
```

### Production Mode

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set production environment
APP_ENV=production
APP_DEBUG=false

# Start queue worker (background)
php artisan queue:work --daemon

# Setup supervisor untuk queue (recommended)
# /etc/supervisor/conf.d/greenport-worker.conf
```

### Dengan Docker (Opsional)

```bash
# Install Laravel Sail
composer require laravel/sail --dev
php artisan sail:install

# Start dengan Sail
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

---

## 🧪 Testing

### Setup Testing Environment

```bash
# Install PHPUnit (sudah termasuk)
composer install --dev

# Test database (menggunakan in-memory SQLite)
# Sudah dikonfigurasi di phpunit.xml
```

### Menjalankan Tests

#### 1. Semua Tests

```bash
# Jalankan semua tests
php artisan test

# Dengan verbose output
php artisan test --verbose

# Stop on first failure
php artisan test --stop-on-failure
```

#### 2. Test Spesifik

```bash
# Test upload dokumen saja
php artisan test --filter DocumentUploadTest

# Test fitur tertentu
php artisan test --filter test_upload_2mb_file

# Test berdasarkan group
php artisan test --group upload
```

#### 3. Script Testing Otomatis

```bash
# Berikan permission
chmod +x run_upload_tests.sh
chmod +x test_upload.sh

# Jalankan script interaktif
./run_upload_tests.sh

# Jalankan test komprehensif
./test_upload.sh
```

#### 4. Testing via Web UI

```bash
# Start server
php artisan serve

# Akses halaman test
# http://127.0.0.1:8000/test-upload
```

### Test Coverage

```bash
# Generate coverage report
php artisan test --coverage

# Coverage dengan HTML report
php artisan test --coverage-html coverage-report
```

### Jenis Tests yang Tersedia

| Test Category | Description | Files |
|---------------|-------------|-------|
| **Unit Tests** | Model, Helper, Service tests | `tests/Unit/` |
| **Feature Tests** | HTTP requests, Integration | `tests/Feature/` |
| **Upload Tests** | Document upload functionality | `DocumentUploadTest.php` |
| **API Tests** | REST API endpoints | `ApiTest.php` |
| **Auth Tests** | Authentication flows | `AuthTest.php` |

---

## 🎨 Fitur Utama

### 1. Manajemen User & Authentication

- **Login/Register**: Laravel Breeze
- **Role & Permissions**: Spatie Laravel Permission
- **User Management**: CRUD users dengan roles
- **Profile Management**: Edit profile, change password

### 2. Sistem Audit

- **Audit Management**: Create, edit, delete audit sessions
- **Kriteria & Subkriteria**: Manage audit criteria
- **Indikator**: Audit indicators and requirements
- **Checklist**: Dynamic checklist templates

### 3. Upload & Manajemen Dokumen

- **Multi-format Support**: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG
- **File Size Validation**: Configurable max size (default 10MB)
- **Progress Tracking**: Real-time upload progress
- **File Integrity**: Hash verification
- **Batch Upload**: Multiple files upload
- **Download & Preview**: Document viewing

### 4. Visitasi Lapangan

- **Scheduling**: Schedule field visits
- **Documentation**: Photo and document capture
- **GPS Integration**: Location tracking
- **Offline Support**: Work without internet (for auditees)

### 5. Reporting & Analytics

- **Audit Reports**: Generate audit reports
- **Compliance Reports**: Compliance status tracking
- **Export Features**: PDF, Excel export
- **Dashboard**: Overview statistics

### 6. PWA Features (Auditee Only)

- **Offline Mode**: Work without internet (for auditees)
- **Push Notifications**: Real-time notifications
- **Mobile Optimized**: Responsive design
- **App Installation**: Install as mobile app

---

## 🗄️ Struktur Database

### Tabel Utama

#### Users & Authentication
```sql
-- users
id, name, email, password, role, created_at, updated_at

-- model_has_permissions, model_has_roles (Spatie)
-- permissions, roles, role_has_permissions
```

#### Audit System
```sql
-- audits
id, nama, tanggal_mulai, tanggal_selesai, status, created_by

-- kriteria
id, nama, bobot, deskripsi

-- subkriteria  
id, kriteria_id, nama, bobot

-- indikator
id, subkriteria_id, nama, target, satuan

-- indikator_dokumen
id, indikator_id, nama_dokumen, required
```

#### Documents & Evidence
```sql
-- bukti
id, temuan_id, nama_file, path, size, mime_type, hash

-- bukti_uploads
id, bukti_id, original_name, uploaded_at, user_id

-- temuan
id, audit_id, indikator_id, status, catatan
```

#### Field Visit
```sql
-- visitasi_lapangans
id, audit_id, tanggal, lokasi, status, created_by

-- checklist_audits
id, visitasi_id, item_checklist_id, status, catatan
```

### Entity Relationship Diagram

```
[Users] ─┐
         ├─ [Audits] ─┬─ [Temuan] ─┬─ [Bukti] ─ [BuktiUploads]
         │            │           └─ [Indikator] ─ [IndikatorDokumen]
         │            └─ [VisitasiLapangans] ─ [ChecklistAudits]
         │
         └─ [Kriteria] ─ [Subkriteria] ─ [Indikator]
```

---

## 🔌 API Documentation

### Authentication

```bash
# Login
POST /api/login
{
    "email": "user@example.com",
    "password": "password"
}

# Response
{
    "token": "bearer_token",
    "user": {...}
}
```

### Audit Endpoints

```bash
# Get audits
GET /api/audits
Authorization: Bearer {token}

# Create audit
POST /api/audits
{
    "nama": "Audit 2025",
    "tanggal_mulai": "2025-01-01",
    "tanggal_selesai": "2025-01-31"
}

# Upload document
POST /api/audits/{id}/upload
Content-Type: multipart/form-data
file: (binary file)
temuan_id: 123
```

### Response Format

```json
{
    "success": true,
    "message": "Operation successful",
    "data": {...},
    "meta": {
        "pagination": {...}
    }
}
```

---

## 🚢 Deployment

### 1. Shared Hosting

```bash
# Upload files ke public_html
# Pindahkan .env ke luar public_html
# Update .env dengan setting production

# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Optimize
php artisan optimize
php artisan migrate --force
```

### 2. VPS/Cloud Server

```bash
# Install dependencies
sudo apt update
sudo apt install nginx php8.2-fpm php8.2-mysql composer nodejs npm

# Setup nginx
sudo nano /etc/nginx/sites-available/greenport

# SSL dengan Let's Encrypt
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com

# Setup queue worker
sudo nano /etc/supervisor/conf.d/greenport-worker.conf
```

### 3. Docker Deployment

```dockerfile
# Dockerfile
FROM php:8.2-fpm-alpine

# Install dependencies
RUN apk add --no-cache nginx nodejs npm

# Copy application
COPY . /var/www/html

# Setup permissions
RUN chown -R www-data:www-data /var/www/html

# Build assets
RUN npm install && npm run build

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
```

### 4. Environment Variables (Production)

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=greenport_prod
DB_USERNAME=greenport_user
DB_PASSWORD=secure_password

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
```

---

## 🔧 Troubleshooting

### Common Issues

#### 1. Permission Denied

```bash
# Symptoms: 500 error, cannot write files
# Solution:
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chown -R www-data:www-data storage/
```

#### 2. Upload File Gagal

```bash
# Check PHP limits
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Increase limits in php.ini
upload_max_filesize = 64M
post_max_size = 64M

# Restart web server
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

#### 3. Database Connection Error

```bash
# Check database credentials in .env
# Test connection
php artisan tinker
DB::connection()->getPdo();

# Reset database
php artisan migrate:fresh --seed
```

#### 4. Assets Not Loading

```bash
# Rebuild assets
npm run build

# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Check public storage link
php artisan storage:link
```

#### 5. Queue Not Processing

```bash
# Check queue configuration
php artisan queue:monitor

# Restart queue worker
php artisan queue:restart

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Debug Tools

```bash
# Enable debug mode
APP_DEBUG=true

# Check logs
tail -f storage/logs/laravel.log

# Use pail for real-time logs
php artisan pail

# Tinker console
php artisan tinker
```

---

## ❓ FAQ

### Q: Bagaimana cara mengubah batas ukuran upload?

A: Edit file `.env` dan php.ini:
```bash
# .env
UPLOAD_MAX_SIZE=20480  # 20MB in KB

# php.ini
upload_max_filesize = 64M
post_max_size = 64M
```

### Q: Aplikasi lambat saat upload file besar?

A: Optimasi berikut:
```bash
# Increase PHP limits
max_execution_time = 600
memory_limit = 1G

# Use chunked upload untuk file > 50MB
# Enable queue untuk background processing
QUEUE_CONNECTION=database
```

### Q: Bagaimana cara backup database?

A: 
```bash
# SQLite
cp database/database.sqlite backup/database_$(date +%Y%m%d).sqlite

# MySQL
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Via Artisan
php artisan backup:run
```

### Q: Cara menambah user admin baru?

A:
```bash
php artisan tinker
$user = User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password')
]);
$user->assignRole('admin');
```

### Q: Aplikasi tidak bisa diakses setelah deploy?

A: Check:
1. Web server configuration
2. File permissions
3. .env file exists and readable
4. Database connection
5. Storage symlink exists

### Q: Cara enable HTTPS?

A:
```bash
# Update .env
APP_URL=https://yourdomain.com

# Force HTTPS in AppServiceProvider
public function boot()
{
    if($this->app->environment('production')) {
        \URL::forceScheme('https');
    }
}
```

---

## 📞 Support & Contact

### Development Team
- **Lead Developer**: [Your Name]
- **Email**: developer@example.com
- **GitHub**: [Repository URL]

### Documentation
- **API Docs**: `/docs/api`
- **User Guide**: `/docs/user-guide`
- **Video Tutorials**: [YouTube Channel]

### Resources
- **Laravel Documentation**: https://laravel.com/docs
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Alpine.js**: https://alpinejs.dev/