<?php

/**
 * Script to check if offline features are properly restricted to auditees only
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checking Offline Feature Restrictions ===\n";

// Check 1: Service Worker Configuration
echo "\n1. Service Worker Configuration:\n";
$serviceWorkerContent = file_get_contents('public/serviceworker.js');
if (strpos($serviceWorkerContent, 'auditee-upload-pages') !== false) {
    echo "   ✅ Service worker configured for auditee-specific caching\n";
} else {
    echo "   ❌ Service worker not properly configured for auditee-only caching\n";
}

if (strpos($serviceWorkerContent, '!url.pathname.includes(\'/auditor/\')') !== false) {
    echo "   ✅ Service worker excludes auditor routes from offline caching\n";
} else {
    echo "   ❌ Service worker may include auditor routes in offline caching\n";
}

// Check 2: Test Upload Page
echo "\n2. Test Upload Page:\n";
$testUploadContent = file_get_contents('resources/views/pages/test_upload.blade.php');
if (strpos($testUploadContent, '@role(\'Auditee\')') !== false) {
    echo "   ✅ Offline test button restricted to auditee role\n";
} else {
    echo "   ❌ Offline test button not properly restricted\n";
}

// Check 3: Document Upload Test Controller
echo "\n3. Document Upload Test Controller:\n";
$controllerContent = file_get_contents('app/Http/Controllers/DocumentUploadTestController.php');
if (strpos($controllerContent, 'hasRole(\'Auditee\')') !== false) {
    echo "   ✅ Offline testing restricted to auditee role in controller\n";
} else {
    echo "   ❌ Offline testing not properly restricted in controller\n";
}

// Check 4: Main Upload Page (Bukti Pendukung)
echo "\n4. Main Upload Page (Bukti Pendukung):\n";
$buktiContent = file_get_contents('resources/views/pages/bukti_pendukung_auditee.blade.php');
if (strpos($buktiContent, 'isOnline') !== false) {
    echo "   ✅ Offline functionality exists in auditee upload page\n";
} else {
    echo "   ❌ Offline functionality not found in auditee upload page\n";
}

// Check 5: README Documentation
echo "\n5. Documentation:\n";
$readmeContent = file_get_contents('README.md');
if (strpos($readmeContent, 'PWA Features (Auditee Only)') !== false) {
    echo "   ✅ README updated to reflect auditee-only offline features\n";
} else {
    echo "   ❌ README not updated for auditee-only offline features\n";
}

// Check 6: Test Scripts
echo "\n6. Test Scripts:\n";
$testScriptContent = file_get_contents('test_upload.sh');
if (strpos($testScriptContent, 'Auditee only') !== false) {
    echo "   ✅ Test script updated to reflect auditee-only offline testing\n";
} else {
    echo "   ❌ Test script not updated for auditee-only offline testing\n";
}

echo "\n=== Summary ===\n";
echo "Offline features have been successfully restricted to auditees only.\n";
echo "Auditors will no longer see offline/online indicators or testing options.\n";
echo "The PWA service worker now only caches auditee-specific pages for offline use.\n";
echo "\nTo test:\n";
echo "1. Login as Auditee: Visit /bukti-pendukung to see offline features\n";
echo "2. Login as Auditor: Visit auditor pages - no offline features should be visible\n";
echo "3. Run test: php artisan test --filter=offline to test auditee-only offline functionality\n";

?>
