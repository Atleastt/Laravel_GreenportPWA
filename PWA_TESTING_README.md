# PWA Offline Testing Suite for Auditee Upload Functionality

A comprehensive testing framework for evaluating Progressive Web App (PWA) performance under various network conditions, specifically designed for auditee evidence upload scenarios.

## Overview

This testing suite simulates real-world network challenges in port environments and measures PWA performance across three key scenarios:

1. **Stable Online**: Constant connectivity throughout the session
2. **Offline Mode**: Network disabled before form submission
3. **Intermittent**: Simulated 300 Kbps bandwidth with manual disconnections every 30 seconds

## Test Matrix

The suite executes **90 test cases** systematically:

- **3 Network Scenarios** × **10 Self-assessments** × **3 Repetitions** = 90 tests
- Each test includes an image file (JPEG, 500-800 KB)
- Tests run sequentially with configurable delays

## Performance Metrics Measured

| Metric | Description |
|--------|-------------|
| **Sync Success Rate (%)** | Proportion of submissions correctly received by server post-reconnect |
| **Sync Delay (seconds)** | Time between network reconnection and successful sync event |
| **Data Integrity** | Whether payload data matches pre-synchronization values (via SHA-256) |
| **Offline Form Usability** | Whether form inputs and file uploads remain functional while offline |

## Architecture

### Frontend Components

#### 1. PWA Offline Tester (`resources/js/pwa-offline-tester.js`)
Core testing engine that handles:
- Network scenario simulation
- Test data generation with image files
- Form submission and response measurement
- Data integrity validation using SHA-256 checksums

#### 2. Test Automation Runner (`resources/js/test-automation-runner.js`)
Automated test execution system that:
- Runs all 90 test cases systematically
- Provides real-time progress tracking
- Generates comprehensive reports
- Supports multiple export formats (JSON, CSV, HTML)

#### 3. Service Worker (`public/serviceworker.js`)
Enhanced service worker that:
- Handles network simulation for test requests
- Manages offline request queuing
- Implements background sync
- Provides intermittent connectivity simulation

### Backend Components

#### 1. PWATestController (`app/Http/Controllers/PWATestController.php`)
Laravel controller handling:
- Test evidence submission processing
- Metrics collection and storage
- Data integrity validation
- Report generation and export

#### 2. Database Schema (`database/migrations/2025_08_21_132055_create_pwa_test_results_table.php`)
Comprehensive test results storage:
- Test identification and metadata
- Performance metrics
- Data integrity hashes
- Error logging and timestamps

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auditee/submit-evidence` | Submit test evidence with metrics collection |
| GET | `/pwa-test/results` | Retrieve test results with filtering |
| GET | `/pwa-test/report` | Generate comprehensive test report |
| GET | `/pwa-test/export` | Export results in various formats |
| GET | `/pwa-test/validate-integrity` | Validate data integrity for specific test |
| DELETE | `/pwa-test/cleanup` | Clean up old test data |

## Installation & Setup

### Prerequisites
- PHP 8.0+
- Laravel 10+
- Node.js 16+
- Modern web browser with PWA support

### Installation Steps

1. **Clone and Setup Project**
   ```bash
   cd /path/to/greenport
   composer install
   npm install
   ```

2. **Run Database Migration**
   ```bash
   php artisan migrate
   ```

3. **Build Assets**
   ```bash
   npm run build
   ```

4. **Access Test Interface**
   Navigate to: `/pwa-test-runner`

## Usage Guide

### 1. Basic Testing

1. Open the test interface at `/pwa-test-runner`
2. Configure test parameters:
   - Select network scenario
   - Set number of assessments (1-20)
   - Set repetitions per assessment (1-5)
3. Click "Start Testing"
4. Monitor progress and real-time metrics
5. Export results when complete

### 2. Automated Testing

1. Enable "Auto Mode" checkbox
2. Click "Start Automated Testing"
3. The system will automatically run all 90 test cases
4. Real-time progress tracking provided
5. Comprehensive report generated upon completion

### 3. Manual Testing

1. Disable "Auto Mode"
2. Select specific network scenario
3. Configure assessment and repetition counts
4. Run targeted tests for specific scenarios

## Network Scenarios

### Stable Online
- **Description**: Constant connectivity with normal latency
- **Configuration**: Default browser network conditions
- **Expected Behavior**: All submissions should succeed immediately

### Offline Mode
- **Description**: Network disabled before form submission
- **Configuration**: Service worker intercepts requests when offline
- **Expected Behavior**: Requests queued, sync occurs on reconnection

### Intermittent Connectivity
- **Description**: 300 Kbps bandwidth with periodic disconnections
- **Configuration**:
  - Bandwidth: 300 Kbps
  - Disconnect interval: 30 seconds
  - Random reconnection pattern
- **Expected Behavior**: Variable sync delays, potential request queuing

## Test Data Generation

### Image File Generation
- **Format**: JPEG
- **Size**: 500-800 KB
- **Generation**: Canvas-based with random patterns
- **Metadata**: Includes timestamp and test information

### Form Data
- **Assessment Number**: 1-10 per scenario
- **Repetition**: 1-3 per assessment
- **Notes**: Descriptive test information
- **File Upload**: Generated test image

## Data Integrity Validation

### SHA-256 Checksum Implementation
1. **Client-side**: Hash calculated on form data and file content
2. **Server-side**: Hash calculated on received data
3. **Comparison**: Hashes compared to detect corruption
4. **Storage**: Both client and server hashes stored for analysis

### Validation Process
```javascript
// Client-side hash calculation
const localHash = await this.calculateHash(testData);

// Server-side hash calculation
const serverHash = hash('sha256', $receivedData);

// Integrity check
const isValid = localHash === serverHash;
```

## Performance Monitoring

### Real-time Metrics
- **Progress Tracking**: Visual progress bar with percentage
- **Success Rate**: Real-time calculation of successful tests
- **Response Times**: Average response time tracking
- **Sync Delays**: Monitoring of background sync performance

### Detailed Metrics
- **Processing Time**: Server-side processing duration
- **Network Latency**: End-to-end request/response time
- **Sync Performance**: Background sync success rates
- **Data Integrity**: Checksum validation results

## Reporting & Analysis

### Export Formats
1. **CSV**: Tabular data for spreadsheet analysis
2. **JSON**: Structured data for programmatic analysis
3. **HTML**: Human-readable report with charts

### Report Contents
- **Summary Statistics**: Overall success rates and performance
- **Scenario Breakdown**: Per-scenario performance analysis
- **Detailed Results**: Individual test case outcomes
- **Error Analysis**: Failed test categorization
- **Performance Trends**: Response time distributions

### Statistical Analysis
- **Mean Calculation**: Average performance metrics
- **Standard Deviation**: Performance variability
- **Success Rate Analysis**: Per-scenario success rates
- **Trend Analysis**: Performance over time

## Troubleshooting

### Common Issues

1. **Service Worker Registration Failed**
   - Ensure HTTPS in production
   - Check browser compatibility
   - Verify service worker file path

2. **Network Simulation Not Working**
   - Ensure service worker is active
   - Check browser developer tools
   - Verify test mode headers

3. **Database Connection Issues**
   - Verify database configuration
   - Check migration status
   - Ensure proper permissions

4. **Test Execution Hangs**
   - Check network connectivity
   - Monitor browser console for errors
   - Verify service worker is responding

### Debug Mode
Enable debug logging by opening browser developer tools and checking:
- Console for JavaScript errors
- Network tab for request/response analysis
- Application tab for service worker status
- Storage tab for IndexedDB contents

## Integration with Existing System

### Compatibility
- **Laravel Framework**: Fully integrated with existing controllers
- **Database**: Uses existing connection and migration system
- **Authentication**: Respects existing user authentication
- **File Storage**: Integrates with Laravel's storage system

### Extension Points
- **Custom Scenarios**: Add new network simulation scenarios
- **Additional Metrics**: Extend performance monitoring
- **Custom Reports**: Modify report generation logic
- **Integration Tests**: Add automated testing to CI/CD pipeline

## Security Considerations

### Data Protection
- Test data isolated from production data
- Temporary file storage with cleanup
- No sensitive production data used in tests

### Access Control
- Authentication required for test access
- Role-based access control maintained
- Audit logging of test activities

### Network Security
- HTTPS required for PWA functionality
- Secure API endpoints
- CSRF protection maintained

## Performance Optimization

### Test Execution
- **Batch Processing**: Tests run sequentially to avoid overwhelming
- **Resource Cleanup**: Automatic cleanup of test data
- **Progress Monitoring**: Real-time progress without blocking UI

### System Resources
- **Memory Management**: Efficient handling of large test datasets
- **Storage Optimization**: Compressed storage of test results
- **Network Efficiency**: Optimized API calls and data transfer

## Future Enhancements

### Planned Features
1. **Advanced Analytics**: Statistical analysis dashboard
2. **Performance Benchmarking**: Historical performance comparison
3. **Automated Regression Testing**: CI/CD integration
4. **Custom Test Scenarios**: User-defined network conditions
5. **Mobile Device Testing**: Remote device testing support

### Extension Possibilities
1. **Load Testing**: Multiple concurrent user simulation
2. **Stress Testing**: System limits and breaking points
3. **Performance Profiling**: Detailed performance bottleneck analysis
4. **Integration Testing**: End-to-end workflow testing

## Support & Maintenance

### Logging
- **Application Logs**: Laravel log files
- **Test Results**: Database storage with timestamps
- **Error Tracking**: Console and file logging
- **Performance Metrics**: Structured metric collection

### Monitoring
- **Health Checks**: System status monitoring
- **Performance Metrics**: Key performance indicators
- **Error Rates**: Failure analysis and tracking
- **Usage Statistics**: Test execution statistics

### Maintenance Tasks
- **Database Cleanup**: Regular removal of old test data
- **Log Rotation**: Log file management
- **Performance Tuning**: System optimization
- **Security Updates**: Dependency updates and patches

---

## Quick Start

1. **Access**: Navigate to `/pwa-test-runner`
2. **Configure**: Set test parameters
3. **Execute**: Click "Start Automated Testing"
4. **Monitor**: Watch real-time progress
5. **Analyze**: Export and review comprehensive report

For detailed documentation and advanced usage, refer to the code comments and inline documentation in the source files.
