#!/bin/bash

# Document Upload Test Runner
# This script will run comprehensive tests for document upload functionality

echo "🚀 Starting Document Upload Tests..."
echo "=================================="

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Check if Laravel is set up
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Please run this script from the Laravel project root.${NC}"
    exit 1
fi

echo -e "${BLUE}📋 Pre-test Setup${NC}"
echo "Setting up test environment..."

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Run migrations for testing
echo "Running migrations..."
php artisan migrate --force

# Create test data
echo "Creating test data..."
php artisan tinker --execute="
\App\Models\User::factory()->create(['email' => 'test@example.com', 'name' => 'Test User']);
\App\Models\Temuan::factory(5)->create();
"

echo -e "${GREEN}✅ Setup completed${NC}"
echo ""

echo -e "${BLUE}🧪 Running Feature Tests${NC}"
echo "Running document upload tests..."

# Run the specific test class
php artisan test tests/Feature/DocumentUploadTest.php --verbose

echo ""
echo -e "${BLUE}📊 Manual Test Instructions${NC}"
echo "========================================="
echo ""
echo "To run manual tests:"
echo "1. Start the development server:"
echo "   ${YELLOW}php artisan serve${NC}"
echo ""
echo "2. Visit the test page:"
echo "   ${YELLOW}http://localhost:8000/test-upload${NC}"
echo ""
echo "3. Test scenarios to run:"
echo "   📁 Generate test files (2MB, 5MB, 10MB, 50MB)"
echo "   📤 Upload with different size limits"
echo "   🌐 Test in online mode"
echo "   📱 Test in offline mode (Auditee only)"
echo "   🔄 Test with different file types (PDF, DOC, JPG, etc.)"
echo ""
echo -e "${BLUE}📈 Test Cases Covered:${NC}"
echo "• ✅ 2MB file upload (should succeed)"
echo "• ✅ 5MB file upload (should succeed)"
echo "• ❌ 10MB file upload (should fail with current 10MB limit)"
echo "• ❌ 50MB file upload (should fail)"
echo "• ✅ Valid file types (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG)"
echo "• ❌ Invalid file types (TXT, etc.)"
echo "• ✅ Document download"
echo "• ✅ Document deletion"
echo "• ✅ Document approval"
echo "• ✅ Document rejection"
echo ""
echo -e "${BLUE}🌐 Online/Offline Testing (Auditee Only):${NC}"
echo "• Open browser dev tools (F12)"
echo "• Go to Network tab"
echo "• Use 'Offline' or 'Slow 3G' to simulate poor connection"
echo "• Test upload behavior with different network conditions"
echo "• Note: Offline functionality is only available for auditee users"
echo ""
echo -e "${BLUE}📝 Test File Generation:${NC}"
echo "Use the built-in file generator on the test page, or create files manually:"
echo ""
echo "Create 2MB file:"
echo "${YELLOW}dd if=/dev/zero of=test_2mb.pdf bs=1024 count=2048${NC}"
echo ""
echo "Create 5MB file:"
echo "${YELLOW}dd if=/dev/zero of=test_5mb.pdf bs=1024 count=5120${NC}"
echo ""
echo "Create 10MB file:"
echo "${YELLOW}dd if=/dev/zero of=test_10mb.pdf bs=1024 count=10240${NC}"
echo ""
echo "Create 50MB file:"
echo "${YELLOW}dd if=/dev/zero of=test_50mb.pdf bs=1024 count=51200${NC}"
echo ""
echo -e "${BLUE}⚙️  Configuration Notes:${NC}"
echo "Current upload limits in php.ini should be set to:"
echo "• upload_max_filesize = 64M"
echo "• post_max_size = 64M"
echo "• max_execution_time = 300"
echo "• max_input_time = 300"
echo ""
echo "Current Laravel validation limits:"
echo "• BuktiPendukungController: 10MB (10240 KB)"
echo "• Test endpoints have different limits per test"
echo ""
echo -e "${GREEN}🎯 Expected Results:${NC}"
echo "• Small files (2MB, 5MB) should upload successfully"
echo "• Large files (50MB) should be rejected by validation"
echo "• Upload progress should be visible"
echo "• Connection status should be detected"
echo "• File information should be displayed"
echo "• Upload statistics should be tracked"
echo ""
echo -e "${BLUE}📱 Mobile Testing:${NC}"
echo "• Test on mobile devices"
echo "• Test with poor mobile connection"
echo "• Test file selection from mobile storage"
echo ""
echo -e "${YELLOW}⚠️  Important Notes:${NC}"
echo "• Ensure storage/app/public directory is writable"
echo "• Check disk space before testing large files"
echo "• Monitor server logs during testing"
echo "• Test with actual files, not just generated dummy files"
echo ""
echo -e "${GREEN}✨ Testing Complete!${NC}"
echo "Check the results in your browser and server logs."
echo ""
echo "Log files to monitor:"
echo "• storage/logs/laravel.log"
echo "• /var/log/nginx/error.log (if using nginx)"
echo "• /var/log/apache2/error.log (if using apache)"
