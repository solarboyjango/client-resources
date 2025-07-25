<?php
/**
 * Step 4: Get conversation messages and poll for replies
 */

require_once 'config.php';

function getMessages($watermark = null) {
    if ($watermark) {
        echo "Step 4: Checking for new messages since watermark: {$watermark}\n";
    } else {
        echo "Step 4: Getting all conversation messages...\n";
    }
    
    // Ensure data directory exists
    $dataDir = 'data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    // Read token and conversation ID from previous steps
    $tokenFile = $dataDir . '/token.txt';
    $conversationIdFile = $dataDir . '/conversation_id.txt';
    
    if (!file_exists($tokenFile) || !file_exists($conversationIdFile)) {
        echo "❌ Token or conversation ID not found. Run previous steps first.\n";
        return null;
    }
    
    $token = trim(file_get_contents($tokenFile));
    $conversationId = trim(file_get_contents($conversationIdFile));
    
    $url = "https://directline.botframework.com/v3/directline/conversations/{$conversationId}/activities";
    if ($watermark) {
        $url .= "?watermark={$watermark}";
    }
    
    $headers = ['Authorization: Bearer ' . $token];
    
    // Prepare cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    // Get messages
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        $activities = $data['activities'] ?? [];
        $newWatermark = $data['watermark'] ?? null;
        
        echo "✅ Retrieved " . count($activities) . " message(s)\n";
        echo "   New watermark: {$newWatermark}\n";
        
        // Only display messages if not using watermark (i.e., getting all messages)
        if (!$watermark) {
            // Display messages
            foreach ($activities as $activity) {
                $sender = $activity['from']['id'] ?? 'unknown';
                $text = $activity['text'] ?? '';
                $timestamp = $activity['timestamp'] ?? '';
                
                echo "   📨 {$sender}: {$text}\n";
                echo "      Time: {$timestamp}\n";
            }
        }
        
        // Save watermark for next check
        file_put_contents($dataDir . '/watermark.txt', $newWatermark);
        
        return $data;
    }
    
    echo "❌ Failed to get messages: HTTP {$httpCode}\n";
    if ($response) {
        echo "Response: {$response}\n";
    }
    
    return null;
}

function getCurrentWatermark() {
    // Ensure data directory exists
    $dataDir = 'data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    // Read token and conversation ID from previous steps
    $tokenFile = $dataDir . '/token.txt';
    $conversationIdFile = $dataDir . '/conversation_id.txt';
    
    if (!file_exists($tokenFile) || !file_exists($conversationIdFile)) {
        echo "❌ Token or conversation ID not found. Run previous steps first.\n";
        return null;
    }
    
    $token = trim(file_get_contents($tokenFile));
    $conversationId = trim(file_get_contents($conversationIdFile));
    
    $url = "https://directline.botframework.com/v3/directline/conversations/{$conversationId}/activities";
    $headers = ['Authorization: Bearer ' . $token];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        return $data['watermark'] ?? null;
    }
    
    echo "❌ Failed to get current watermark: HTTP {$httpCode}\n";
    return null;
}

function pollForReply($maxAttempts = 10, $interval = 2) {
    echo "\n🔄 Polling for bot reply (max {$maxAttempts} attempts, {$interval}s interval)...\n";
    
    // Get current watermark before polling
    $watermark = getCurrentWatermark();
    if ($watermark === null) {
        return null;
    }
    
    echo "📌 Starting to poll from watermark: {$watermark}\n";
    
    // Poll for new messages
    for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
        echo "\nAttempt {$attempt}/{$maxAttempts}...\n";
        sleep($interval);
        
        $data = getMessages($watermark);
        if (!$data) {
            continue;
        }
        
        $activities = $data['activities'] ?? [];
        $newWatermark = $data['watermark'] ?? null;
        
        // Check if we got new messages
        if (count($activities) > 0) {
            echo "🎉 Bot replied! Found " . count($activities) . " new message(s)\n";
            
            // Display the new messages
            foreach ($activities as $activity) {
                $sender = $activity['from']['id'] ?? 'unknown';
                $text = $activity['text'] ?? '';
                $timestamp = $activity['timestamp'] ?? '';
                
                echo "   📨 {$sender}: {$text}\n";
                echo "      Time: {$timestamp}\n";
            }
            
            return $data;
        }
        
        $watermark = $newWatermark;
    }
    
    echo "⏰ No reply received within time limit\n";
    return null;
}

// Run if called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    // First get all messages
    getMessages();
    
    // Then poll for new reply
    pollForReply();
}
?> 