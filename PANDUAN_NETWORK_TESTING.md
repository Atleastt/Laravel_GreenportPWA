# 🌐 Panduan Network Condition Testing - 90 Test Cases

## 📋 Daftar Isi
1. [Overview](#overview)
2. [Persiapan Environment](#persiapan-environment)
3. [Metode Testing](#metode-testing)
4. [Konfigurasi Test Case](#konfigurasi-test-case)
5. [Menjalankan Testing](#menjalankan-testing)
6. [Interpretasi Hasil](#interpretasi-hasil)
7. [Troubleshooting](#troubleshooting)
8. [Best Practices](#best-practices)

---

## 🎯 Overview

Sistem ini mendukung testing komprehensif untuk **90 test cases** dengan kondisi jaringan yang berbeda:

### Test Case Specification
- **Total Tests**: 90 cases
- **File Size**: 500-800 KB image attachments
- **Conditions**: 3 network conditions
- **Repetitions**: 3x per condition
- **Uploads per condition**: 10 uploads

### Network Conditions
| Kondisi | Deskripsi | Expected Success Rate |
|---------|-----------|----------------------|
| **Stable Online** | Normal internet connection | 95-100% |
| **Offline** | No internet connection | 0% (expected) |
| **Intermittent** | 300 Kbps + random disconnections | 60-80% |

---

## 🚀 Persiapan Environment

### 1. Setup Laravel Project
```bash
# Pastikan di root directory project
cd /path/to/greenport

# Install dependencies
composer install
npm install

# Setup database
php artisan migrate
php artisan storage:link

# Generate application key jika belum
php artisan key:generate
```

### 2. Konfigurasi PHP
Pastikan `php.ini` memiliki setting yang sesuai:
```ini
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
max_input_time = 300
memory_limit = 512M
```

### 3. Start Development Server
```bash
# Method 1: Simple server
php artisan serve

# Method 2: With queue (recommended)
composer run dev
```

Server akan berjalan di: `http://127.0.0.1:8000`

---

## 🔧 Metode Testing

### Method 1: Web UI Testing (Recommended) 🌟

#### Akses Interface
```bash
# Buka browser dan kunjungi:
http://127.0.0.1:8000/network-test
```

#### Fitur yang Tersedia:
- ✅ **Network Simulator**: Simulasi 3 kondisi jaringan
- ✅ **Automated Test Runner**: Jalankan 90 test cases otomatis
- ✅ **Real-time Progress**: Monitor progress testing
- ✅ **Results Export**: Export hasil ke CSV
- ✅ **Visual Dashboard**: Grafik success/failure rate

#### Langkah-langkah:
1. **Set Configuration**:
   - Uploads per condition: `10`
   - Repetitions: `3`
   - File size range: `500-800 KB`

2. **Start Testing**:
   - Klik "🚀 Start Automated Testing (90 Cases)"
   - Monitor progress real-time
   - Tunggu hingga selesai (~5-10 menit)

3. **Review Results**:
   - Lihat summary statistics
   - Export results ke CSV
   - Analisis success rate per kondisi

### Method 2: Command Line Testing 💻

#### Jalankan Script Otomatis
```bash
# Berikan permission
chmod +x run_network_tests.sh

# Jalankan testing
./run_network_tests.sh
```

#### Output yang Dihasilkan:
```
🌐 Network Condition Testing - 90 Cases
========================================
📋 Test Configuration:
• Uploads per condition: 10
• Repetitions: 3
• File size range: 500-800 KB
• Total tests: 90

🚀 Starting Automated Network Testing

🔄 Testing condition: stable
  Repetition 1/3
[1/90] stable (Rep 1, Upload 1): 652KB - Success (1250ms)
[2/90] stable (Rep 1, Upload 2): 743KB - Success (1180ms)
...

🎉 Testing Complete!
==================================
📊 Results Summary:
• Total tests: 90
• Successful: 75
• Failed: 15
• Success rate: 83%
```

#### Files Generated:
- **CSV Results**: `storage/test-results/network-test-YYYYMMDD_HHMMSS.csv`
- **Summary Report**: `storage/test-results/network-test-summary-YYYYMMDD_HHMMSS.txt`

### Method 3: Manual Browser Testing 🖱️

#### Setup Browser Dev Tools
```bash
# Akses test page
http://127.0.0.1:8000/test-upload
```

#### Simulasi Network Conditions:
1. **Buka Developer Tools** (F12)
2. **Go to Network Tab**
3. **Set Network Throttling**:
   - **Stable**: No throttling
   - **Offline**: Offline mode
   - **Intermittent**: Slow 3G atau Custom (300 Kbps)

#### Manual Testing Steps:
1. Generate test files (500-800 KB)
2. Set network condition
3. Upload file dan monitor
4. Record results manually
5. Repeat untuk semua conditions

---

## ⚙️ Konfigurasi Test Case

### Web UI Configuration
```javascript
// Dapat diubah di interface /network-test
const config = {
    uploadsPerCondition: 10,    // Jumlah upload per kondisi
    repetitions: 3,             // Jumlah repetisi
    minFileSize: 500,           // KB - ukuran file minimum
    maxFileSize: 800,           // KB - ukuran file maksimum
    
    // Network simulation settings
    connectionSpeed: 300,       // Kbps untuk intermittent
    disconnectionChance: 0.1,   // 10% chance per second
    reconnectionDelay: 2000     // 2 seconds
};
```

### Script Configuration
```bash
# Edit run_network_tests.sh
UPLOADS_PER_CONDITION=10
REPETITIONS=3
MIN_FILE_SIZE=500  # KB
MAX_FILE_SIZE=800  # KB

# Network simulation
INTERMITTENT_SPEED="37k"  # 300 Kbps = ~37 KB/s
DISCONNECTION_CHANCE=20   # 20% chance
```

### Advanced Configuration
```php
// app/Http/Controllers/DocumentUploadTestController.php
public function apiUpload(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:10240', // Adjust max size
        'temuan_id' => 'required|integer',
        'nama_dokumen' => 'required|string|max:255',
    ]);
    
    // Network delay simulation
    if ($request->has('simulate_slow_network')) {
        sleep(2); // Adjust delay
    }
}
```

---

## 🏃‍♂️ Menjalankan Testing

### Quick Start (Web UI)
```bash
# 1. Start server
php artisan serve

# 2. Open browser
open http://127.0.0.1:8000/network-test

# 3. Click "Start Automated Testing"
# 4. Wait for completion
# 5. Export results
```

### Quick Start (Command Line)
```bash
# One-liner execution
./run_network_tests.sh
```

### Detailed Testing Process

#### Phase 1: Stable Online (30 tests)
```
Condition: stable
├── Repetition 1 (10 uploads: 500-800KB each)
├── Repetition 2 (10 uploads: 500-800KB each)
└── Repetition 3 (10 uploads: 500-800KB each)
Expected: ~100% success rate
```

#### Phase 2: Offline (30 tests)
```
Condition: offline
├── Repetition 1 (10 uploads: 500-800KB each)
├── Repetition 2 (10 uploads: 500-800KB each)
└── Repetition 3 (10 uploads: 500-800KB each)
Expected: 0% success rate (all should fail)
```

#### Phase 3: Intermittent (30 tests)
```
Condition: intermittent (300 Kbps + random disconnections)
├── Repetition 1 (10 uploads: 500-800KB each)
├── Repetition 2 (10 uploads: 500-800KB each)
└── Repetition 3 (10 uploads: 500-800KB each)
Expected: 60-80% success rate
```

---

## 📊 Interpretasi Hasil

### CSV Results Format
```csv
Test_Number,Condition,Repetition,Upload_Number,File_Size_KB,Status,Duration_MS,Error
1,stable,1,1,652,Success,1250,""
2,stable,1,2,743,Success,1180,""
31,offline,1,1,598,Failed,50,"Network offline"
61,intermittent,1,1,721,Success,3450,""
62,intermittent,1,2,634,Failed,2100,"Random disconnection"
```

### Success Rate Analysis
```bash
# Expected Results:
Stable Online:    28-30/30 (93-100%)
Offline:          0/30     (0%)
Intermittent:     18-24/30 (60-80%)
Overall:          46-54/90 (51-60%)
```

### Performance Metrics
| Metric | Stable | Offline | Intermittent |
|--------|--------|---------|--------------|
| **Avg Duration** | 1-2s | <100ms | 3-5s |
| **Success Rate** | 95-100% | 0% | 60-80% |
| **Timeout Rate** | <1% | 0% | 10-15% |
| **Error Types** | Validation | Connection | Disconnection |

### Web UI Dashboard
```
📊 Test Progress
Overall Progress: [████████████████████] 90/90 (100%)

Results Summary:
✅ Successful: 52    🔴 Failed: 38    ⏳ Pending: 0

By Condition:
🟢 Stable:      30/30 (100%)
🔴 Offline:     0/30  (0%)
🟡 Intermittent: 22/30 (73%)
```

---

## 🔧 Troubleshooting

### Common Issues

#### 1. Server Not Running
**Symptoms**: Connection refused errors
**Solution**:
```bash
# Check if server is running
curl -s http://127.0.0.1:8000 || echo "Server not running"

# Start server
php artisan serve

# Or use background process
nohup php artisan serve > server.log 2>&1 &
```

#### 2. File Upload Fails
**Symptoms**: 413 Request Entity Too Large
**Solution**:
```bash
# Check PHP limits
php -i | grep -E "(upload_max_filesize|post_max_size|memory_limit)"

# Increase limits in php.ini
upload_max_filesize = 64M
post_max_size = 64M
memory_limit = 512M

# Restart server
php artisan serve
```

#### 3. Permission Denied
**Symptoms**: Cannot write to storage
**Solution**:
```bash
# Fix storage permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# For production
chown -R www-data:www-data storage/
```

#### 4. Network Simulation Not Working
**Symptoms**: All tests pass even in offline mode
**Solution**:
```bash
# Check JavaScript console for errors
# Ensure network-simulator.js is loaded
# Verify fetch API override is working

# Test manually in browser console:
window.networkSimulator.startIntermittentSimulation();
```

#### 5. CSV Export Issues
**Symptoms**: Empty or corrupted CSV files
**Solution**:
```bash
# Check storage directory permissions
ls -la storage/test-results/

# Create directory if missing
mkdir -p storage/test-results
chmod 775 storage/test-results

# Check disk space
df -h
```

### Debug Commands
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Monitor system resources
top -p $(pgrep php)

# Check network connectivity
ping -c 3 127.0.0.1

# Test API endpoint manually
curl -X POST http://127.0.0.1:8000/api/test-upload \
  -F "file=@test.jpg" \
  -F "temuan_id=1" \
  -F "nama_dokumen=Test"
```

---

## 💡 Best Practices

### Before Testing
- ✅ **Backup Database**: `php artisan backup:run`
- ✅ **Check Disk Space**: Ensure >1GB free space
- ✅ **Close Other Apps**: Minimize resource usage
- ✅ **Stable Internet**: Use reliable connection for baseline

### During Testing
- 📊 **Monitor Progress**: Watch real-time updates
- 🔍 **Check Logs**: Monitor `storage/logs/laravel.log`
- 💾 **Save Intermediate Results**: Export data periodically
- 🚫 **Avoid Interruption**: Don't close browser/terminal

### After Testing
- 📋 **Review Results**: Analyze success patterns
- 🧹 **Clean Up**: Remove test files
- 📝 **Document Issues**: Note any anomalies
- 🔄 **Plan Improvements**: Based on findings

### Performance Optimization
```bash
# Optimize Laravel for testing
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Increase PHP limits for large batches
php -d memory_limit=1G -d max_execution_time=600 artisan test

# Use SSD storage for better I/O performance
# Monitor system resources during testing
```

### Testing Strategy
1. **Start Small**: Test with 3 cases first
2. **Validate Setup**: Ensure all conditions work
3. **Run Full Suite**: Execute all 90 cases
4. **Repeat if Needed**: For statistical significance
5. **Compare Results**: Against expected benchmarks

---

## 📈 Advanced Usage

### Custom Network Conditions
```javascript
// Add custom network condition
const customCondition = {
    name: 'slow-2g',
    speed: 50, // Kbps
    disconnectionChance: 0.3, // 30%
    reconnectionDelay: 5000 // 5 seconds
};

networkSimulator.addCustomCondition(customCondition);
```

### Batch Testing with Different Parameters
```bash
# Test different file sizes
for size in 100 200 500 800 1000; do
    echo "Testing with ${size}KB files"
    sed -i "s/MIN_FILE_SIZE=.*/MIN_FILE_SIZE=$size/" run_network_tests.sh
    sed -i "s/MAX_FILE_SIZE=.*/MAX_FILE_SIZE=$size/" run_network_tests.sh
    ./run_network_tests.sh
done
```

### Integration with CI/CD
```yaml
# .github/workflows/network-test.yml
name: Network Testing
on: [push, pull_request]

jobs:
  network-test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
      - name: Install dependencies
        run: composer install
      - name: Run network tests
        run: ./run_network_tests.sh
      - name: Upload results
        uses: actions/upload-artifact@v2
        with:
          name: network-test-results
          path: storage/test-results/
```

---

## 🎯 Kesimpulan

Sistem network testing ini menyediakan:

### ✅ **Capabilities**
- **90 automated test cases**
- **3 network conditions simulation**
- **Real-time progress monitoring**
- **Comprehensive result analysis**
- **Multiple testing methods**

### 🚀 **Quick Commands**
```bash
# Web UI Testing
php artisan serve && open http://127.0.0.1:8000/network-test

# Command Line Testing
./run_network_tests.sh

# Manual Testing
open http://127.0.0.1:8000/test-upload
```

### 📊 **Expected Outcomes**
- **Stable Online**: 95-100% success rate
- **Offline**: 0% success rate (expected)
- **Intermittent**: 60-80% success rate
- **Overall**: Comprehensive network resilience analysis

Sistem ini **siap digunakan** untuk melakukan testing sesuai spesifikasi yang diminta dengan hasil yang akurat dan dapat dianalisis! 🎉

---

## 📞 Support

Jika mengalami masalah:
1. Check [Troubleshooting](#troubleshooting) section
2. Review Laravel logs: `storage/logs/laravel.log`
3. Test individual components first
4. Ensure all prerequisites are met

**Happy Testing!** 🚀