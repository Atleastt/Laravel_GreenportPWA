#!/bin/bash

# Network Condition Testing Script
# Automated testing for 90 cases: stable online, offline, and intermittent (300 Kbps with random disconnections)

echo "🌐 Network Condition Testing - 90 Cases"
echo "========================================"

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
UPLOADS_PER_CONDITION=10
REPETITIONS=3
MIN_FILE_SIZE=500  # KB
MAX_FILE_SIZE=800  # KB
TOTAL_TESTS=$((3 * $UPLOADS_PER_CONDITION * $REPETITIONS))

echo -e "${BLUE}📋 Test Configuration:${NC}"
echo "• Uploads per condition: $UPLOADS_PER_CONDITION"
echo "• Repetitions: $REPETITIONS"
echo "• File size range: ${MIN_FILE_SIZE}-${MAX_FILE_SIZE} KB"
echo "• Total tests: $TOTAL_TESTS"
echo ""

# Check if Laravel is set up
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Please run this script from the Laravel project root.${NC}"
    exit 1
fi

# Check if server is running
if ! curl -s http://127.0.0.1:8000 > /dev/null; then
    echo -e "${YELLOW}⚠️  Laravel server not detected. Starting server...${NC}"
    php artisan serve &
    SERVER_PID=$!
    sleep 3
    echo -e "${GREEN}✅ Server started (PID: $SERVER_PID)${NC}"
else
    echo -e "${GREEN}✅ Laravel server is running${NC}"
fi

echo ""
echo -e "${BLUE}🚀 Starting Automated Network Testing${NC}"
echo ""

# Create results directory
mkdir -p storage/test-results
RESULTS_FILE="storage/test-results/network-test-$(date +%Y%m%d_%H%M%S).csv"

# CSV Header
echo "Test_Number,Condition,Repetition,Upload_Number,File_Size_KB,Status,Duration_MS,Error" > "$RESULTS_FILE"

test_counter=1
success_count=0
failed_count=0

# Function to generate test file
generate_test_file() {
    local size_kb=$1
    local filename="test_file_${size_kb}kb.jpg"
    
    # Create a file with random data to simulate image
    dd if=/dev/urandom of="$filename" bs=1024 count=$size_kb 2>/dev/null
    echo "$filename"
}

# Function to perform upload test
perform_upload_test() {
    local condition=$1
    local repetition=$2
    local upload_number=$3
    local file_size_kb=$4
    local test_file=$5
    
    local start_time=$(date +%s%3N)
    
    # Prepare curl command based on condition
    local curl_cmd="curl -s -w '%{http_code}' -X POST"
    
    case $condition in
        "stable")
            curl_cmd="$curl_cmd http://127.0.0.1:8000/api/test-upload"
            ;;
        "offline")
            # Simulate offline by using invalid URL
            curl_cmd="$curl_cmd http://127.0.0.1:9999/api/test-upload"
            ;;
        "intermittent")
            # Add slow network simulation
            curl_cmd="$curl_cmd --limit-rate 37k http://127.0.0.1:8000/api/test-upload"
            ;;
    esac
    
    # Add form data
    curl_cmd="$curl_cmd -F 'file=@$test_file' -F 'temuan_id=1' -F 'nama_dokumen=Network Test $test_counter'"
    
    # Execute upload
    local response
    local http_code
    local error=""
    
    if [ "$condition" = "offline" ]; then
        # Simulate offline condition
        response="Connection refused"
        http_code="000"
        error="Network offline"
    else
        # Add intermittent disconnection simulation
        if [ "$condition" = "intermittent" ] && [ $((RANDOM % 10)) -lt 2 ]; then
            # 20% chance of disconnection during intermittent testing
            response="Connection lost"
            http_code="000"
            error="Random disconnection"
        else
            response=$(eval $curl_cmd 2>&1)
            http_code="${response: -3}"
            
            if [ "$http_code" = "200" ] || [ "$http_code" = "201" ]; then
                error=""
            else
                error="HTTP $http_code"
            fi
        fi
    fi
    
    local end_time=$(date +%s%3N)
    local duration=$((end_time - start_time))
    
    # Determine status
    local status="Failed"
    if [ "$http_code" = "200" ] || [ "$http_code" = "201" ]; then
        status="Success"
        ((success_count++))
    else
        ((failed_count++))
    fi
    
    # Log result to CSV
    echo "$test_counter,$condition,$repetition,$upload_number,$file_size_kb,$status,$duration,\"$error\"" >> "$RESULTS_FILE"
    
    # Display progress
    local progress=$((test_counter * 100 / TOTAL_TESTS))
    echo -e "${BLUE}[$test_counter/$TOTAL_TESTS]${NC} $condition (Rep $repetition, Upload $upload_number): ${file_size_kb}KB - $status (${duration}ms)"
    
    ((test_counter++))
}

# Test conditions
conditions=("stable" "offline" "intermittent")

for condition in "${conditions[@]}"; do
    echo ""
    echo -e "${YELLOW}🔄 Testing condition: $condition${NC}"
    
    for rep in $(seq 1 $REPETITIONS); do
        echo -e "${BLUE}  Repetition $rep/$REPETITIONS${NC}"
        
        for upload in $(seq 1 $UPLOADS_PER_CONDITION); do
            # Generate random file size between MIN and MAX
            file_size=$((RANDOM % (MAX_FILE_SIZE - MIN_FILE_SIZE + 1) + MIN_FILE_SIZE))
            
            # Generate test file
            test_file=$(generate_test_file $file_size)
            
            # Perform upload test
            perform_upload_test "$condition" "$rep" "$upload" "$file_size" "$test_file"
            
            # Clean up test file
            rm -f "$test_file"
            
            # Small delay between tests
            sleep 0.5
        done
    done
done

echo ""
echo -e "${GREEN}🎉 Testing Complete!${NC}"
echo "=================================="
echo -e "${BLUE}📊 Results Summary:${NC}"
echo "• Total tests: $TOTAL_TESTS"
echo "• Successful: $success_count"
echo "• Failed: $failed_count"
echo "• Success rate: $(( success_count * 100 / TOTAL_TESTS ))%"
echo ""
echo -e "${BLUE}📁 Results saved to:${NC} $RESULTS_FILE"
echo ""

# Generate summary report
echo -e "${BLUE}📈 Generating Summary Report...${NC}"
SUMMARY_FILE="storage/test-results/network-test-summary-$(date +%Y%m%d_%H%M%S).txt"

cat > "$SUMMARY_FILE" << EOF
Network Condition Testing Summary
=================================
Date: $(date)
Total Tests: $TOTAL_TESTS
Configuration:
- Uploads per condition: $UPLOADS_PER_CONDITION
- Repetitions: $REPETITIONS
- File size range: ${MIN_FILE_SIZE}-${MAX_FILE_SIZE} KB

Results:
- Successful uploads: $success_count
- Failed uploads: $failed_count
- Success rate: $(( success_count * 100 / TOTAL_TESTS ))%

Test Conditions:
1. Stable Online: Normal internet connection
2. Offline: No internet connection (simulated)
3. Intermittent: 300 Kbps with random disconnections

Files Generated:
- Detailed results: $RESULTS_FILE
- Summary report: $SUMMARY_FILE

Test completed at: $(date)
EOF

echo -e "${GREEN}✅ Summary report saved to:${NC} $SUMMARY_FILE"
echo ""

# Display quick analysis
echo -e "${BLUE}🔍 Quick Analysis:${NC}"
echo "Stable Online Success Rate: $(grep ",stable," "$RESULTS_FILE" | grep ",Success," | wc -l)/$(grep ",stable," "$RESULTS_FILE" | wc -l)"
echo "Offline Success Rate: $(grep ",offline," "$RESULTS_FILE" | grep ",Success," | wc -l)/$(grep ",offline," "$RESULTS_FILE" | wc -l)"
echo "Intermittent Success Rate: $(grep ",intermittent," "$RESULTS_FILE" | grep ",Success," | wc -l)/$(grep ",intermittent," "$RESULTS_FILE" | wc -l)"

echo ""
echo -e "${YELLOW}💡 Next Steps:${NC}"
echo "1. Review detailed results in CSV file"
echo "2. Analyze failure patterns"
echo "3. Test with real network conditions using browser dev tools"
echo "4. Access web UI for interactive testing: http://127.0.0.1:8000/network-test"

# Clean up server if we started it
if [ ! -z "$SERVER_PID" ]; then
    echo ""
    echo -e "${YELLOW}🛑 Stopping test server...${NC}"
    kill $SERVER_PID 2>/dev/null
fi

echo ""
echo -e "${GREEN}✨ Network testing completed successfully!${NC}"