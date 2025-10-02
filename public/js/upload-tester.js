/**
 * File Generator Utility for Upload Testing
 * Creates files of specified sizes for testing upload functionality
 */

class FileGenerator {
    /**
     * Generate a file with specified size
     * @param {number} sizeMB - Size in megabytes
     * @param {string} fileName - Name of the file
     * @param {string} mimeType - MIME type of the file
     * @returns {Blob} Generated file blob
     */
    static generateFile(sizeMB, fileName = 'test.txt', mimeType = 'text/plain') {
        const sizeBytes = sizeMB * 1024 * 1024;
        
        // Create content based on file type
        let content;
        
        if (mimeType === 'application/pdf') {
            // Simple PDF header + content
            content = this.generatePDFContent(sizeBytes);
        } else if (mimeType.includes('image')) {
            // Generate binary-like content for images
            content = this.generateBinaryContent(sizeBytes);
        } else {
            // Generate text content
            content = this.generateTextContent(sizeBytes);
        }
        
        return new Blob([content], { type: mimeType });
    }
    
    /**
     * Generate text content of specified size
     * @param {number} sizeBytes - Size in bytes
     * @returns {string} Generated text content
     */
    static generateTextContent(sizeBytes) {
        const chunkSize = 1024; // 1KB chunks
        const chunks = Math.ceil(sizeBytes / chunkSize);
        let content = '';
        
        const sampleText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. '.repeat(10);
        const chunk = sampleText.substring(0, chunkSize);
        
        for (let i = 0; i < chunks; i++) {
            content += chunk;
        }
        
        return content.substring(0, sizeBytes);
    }
    
    /**
     * Generate simple PDF content
     * @param {number} sizeBytes - Size in bytes
     * @returns {string} Generated PDF content
     */
    static generatePDFContent(sizeBytes) {
        const pdfHeader = `%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj

2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj

3 0 obj
<<
/Type /Page
/Parent 2 0 R
/MediaBox [0 0 612 792]
/Contents 4 0 R
>>
endobj

4 0 obj
<<
/Length ${sizeBytes - 200}
>>
stream
BT
/F1 12 Tf
72 720 Td
`;
        
        const pdfFooter = `
ET
endstream
endobj

xref
0 5
0000000000 65535 f 
0000000010 00000 n 
0000000079 00000 n 
0000000173 00000 n 
0000000301 00000 n 
trailer
<<
/Size 5
/Root 1 0 R
>>
startxref
${sizeBytes - 50}
%%EOF`;
        
        const contentSize = sizeBytes - pdfHeader.length - pdfFooter.length;
        const content = 'Test PDF content for upload testing. '.repeat(Math.ceil(contentSize / 37));
        
        return pdfHeader + content.substring(0, contentSize) + pdfFooter;
    }
    
    /**
     * Generate binary-like content for images
     * @param {number} sizeBytes - Size in bytes
     * @returns {Uint8Array} Generated binary content
     */
    static generateBinaryContent(sizeBytes) {
        const buffer = new Uint8Array(sizeBytes);
        
        // Fill with pseudo-random data
        for (let i = 0; i < sizeBytes; i++) {
            buffer[i] = Math.floor(Math.random() * 256);
        }
        
        return buffer;
    }
    
    /**
     * Download a generated file
     * @param {Blob} blob - File blob to download
     * @param {string} fileName - Name for the downloaded file
     */
    static downloadFile(blob, fileName) {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
    
    /**
     * Generate and download test files for upload testing
     */
    static generateTestFiles() {
        const sizes = [2, 5, 10, 50]; // MB
        const types = [
            { ext: 'txt', mime: 'text/plain' },
            { ext: 'pdf', mime: 'application/pdf' },
            { ext: 'jpg', mime: 'image/jpeg' }
        ];
        
        sizes.forEach(size => {
            types.forEach(type => {
                const fileName = `test_${size}MB.${type.ext}`;
                const blob = this.generateFile(size, fileName, type.mime);
                
                console.log(`Generated ${fileName}: ${this.formatBytes(blob.size)}`);
                
                // Uncomment to auto-download all files
                // this.downloadFile(blob, fileName);
            });
        });
    }
    
    /**
     * Format bytes to human readable format
     * @param {number} bytes - Number of bytes
     * @returns {string} Formatted string
     */
    static formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
}

// Upload Testing Utilities
class UploadTester {
    constructor() {
        this.results = [];
        this.isOnline = navigator.onLine;
        
        // Listen for online/offline events
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.updateConnectionStatus();
        });
        
        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.updateConnectionStatus();
        });
    }
    
    /**
     * Test file upload with different parameters
     * @param {File} file - File to upload
     * @param {string} endpoint - Upload endpoint
     * @param {Object} additionalData - Additional form data
     * @returns {Promise} Upload result
     */
    async testUpload(file, endpoint, additionalData = {}) {
        const startTime = performance.now();
        
        const formData = new FormData();
        formData.append('file', file);
        
        // Add additional data
        Object.keys(additionalData).forEach(key => {
            formData.append(key, additionalData[key]);
        });
        
        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            const endTime = performance.now();
            const duration = (endTime - startTime) / 1000;
            
            const result = {
                success: response.ok,
                status: response.status,
                duration: duration,
                fileSize: file.size,
                fileName: file.name,
                endpoint: endpoint,
                isOnline: this.isOnline,
                timestamp: new Date().toISOString()
            };
            
            if (response.ok) {
                result.data = await response.json();
            } else {
                result.error = await response.text();
            }
            
            this.results.push(result);
            return result;
            
        } catch (error) {
            const endTime = performance.now();
            const duration = (endTime - startTime) / 1000;
            
            const result = {
                success: false,
                error: error.message,
                duration: duration,
                fileSize: file.size,
                fileName: file.name,
                endpoint: endpoint,
                isOnline: this.isOnline,
                timestamp: new Date().toISOString()
            };
            
            this.results.push(result);
            return result;
        }
    }
    
    /**
     * Run comprehensive upload tests
     */
    async runComprehensiveTests() {
        console.log('🚀 Starting comprehensive upload tests...');
        
        const testSizes = [2, 5, 10, 50]; // MB
        const endpoints = [
            '/test-upload/2mb',
            '/test-upload/5mb',
            '/test-upload/10mb',
            '/test-upload/50mb'
        ];
        
        for (let i = 0; i < testSizes.length; i++) {
            const size = testSizes[i];
            const endpoint = endpoints[i];
            
            console.log(`📤 Testing ${size}MB upload...`);
            
            // Generate test file
            const blob = FileGenerator.generateFile(size, `test_${size}MB.pdf`, 'application/pdf');
            const file = new File([blob], `test_${size}MB.pdf`, { type: 'application/pdf' });
            
            // Test upload
            const result = await this.testUpload(file, endpoint, {
                temuan_id: 1, // Assuming temuan with ID 1 exists
                nama_dokumen: `Test Upload ${size}MB`
            });
            
            console.log(`${result.success ? '✅' : '❌'} ${size}MB test:`, result);
            
            // Wait between tests
            await new Promise(resolve => setTimeout(resolve, 1000));
        }
        
        this.generateReport();
    }
    
    /**
     * Generate test report
     */
    generateReport() {
        console.log('\n📊 Upload Test Report');
        console.log('===================');
        
        const totalTests = this.results.length;
        const successfulTests = this.results.filter(r => r.success).length;
        const failedTests = totalTests - successfulTests;
        
        console.log(`Total Tests: ${totalTests}`);
        console.log(`Successful: ${successfulTests}`);
        console.log(`Failed: ${failedTests}`);
        console.log(`Success Rate: ${((successfulTests / totalTests) * 100).toFixed(2)}%`);
        
        const avgDuration = this.results.reduce((sum, r) => sum + r.duration, 0) / totalTests;
        console.log(`Average Duration: ${avgDuration.toFixed(2)}s`);
        
        console.log('\nDetailed Results:');
        this.results.forEach((result, index) => {
            const status = result.success ? '✅' : '❌';
            const size = FileGenerator.formatBytes(result.fileSize);
            console.log(`${index + 1}. ${status} ${result.fileName} (${size}) - ${result.duration.toFixed(2)}s`);
        });
        
        return {
            totalTests,
            successfulTests,
            failedTests,
            successRate: (successfulTests / totalTests) * 100,
            averageDuration: avgDuration,
            results: this.results
        };
    }
    
    /**
     * Update connection status in UI (only for auditees)
     */
    updateConnectionStatus() {
        const statusElement = document.getElementById('connection-status');
        if (statusElement) {
            statusElement.className = this.isOnline ? 'online' : 'offline';
            statusElement.textContent = this.isOnline ? 'Online' : 'Offline';
        }
        
        // Log offline status changes for auditees only
        if (document.querySelector('[data-role="auditee"]')) {
            console.log('Connection status changed for auditee:', this.isOnline ? 'Online' : 'Offline');
        }
    }
    
    /**
     * Clear test results
     */
    clearResults() {
        this.results = [];
        console.log('Test results cleared');
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { FileGenerator, UploadTester };
}

// Global access for browser console
window.FileGenerator = FileGenerator;
window.UploadTester = UploadTester;

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.uploadTester = new UploadTester();
    
    console.log('📁 File Generator and Upload Tester loaded!');
    console.log('Available commands:');
    console.log('- FileGenerator.generateFile(sizeMB, fileName, mimeType)');
    console.log('- FileGenerator.downloadFile(blob, fileName)');
    console.log('- uploadTester.testUpload(file, endpoint, data)');
    console.log('- uploadTester.runComprehensiveTests()');
    console.log('- uploadTester.generateReport()');
});
