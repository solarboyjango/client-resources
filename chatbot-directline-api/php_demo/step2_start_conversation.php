<?php
/**
 * Step 2: Start a new conversation session
 */

require_once 'config.php';

function startConversation() {
    echo "Step 2: Starting conversation...\n";
    
    // Ensure data directory exists
    $dataDir = 'data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    // Read token from previous step
    $tokenFile = $dataDir . '/token.txt';
    if (!file_exists($tokenFile)) {
        echo "❌ Token not found. Run step1_generate_token.php first.\n";
        return null;
    }
    
    $token = trim(file_get_contents($tokenFile));
    
    $url = 'https://directline.botframework.com/v3/directline/conversations';
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ];
    
    // Prepare cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    // Send request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 || $httpCode === 201) {
        $data = json_decode($response, true);
        $conversationId = $data['conversationId'] ?? null;
        $updatedToken = $data['token'] ?? null;
        
        if ($conversationId) {
            echo "✅ Conversation started successfully!\n";
            echo "   Conversation ID: {$conversationId}\n";
            echo "   Stream URL available: " . (isset($data['streamUrl']) ? 'Yes' : 'No') . "\n";
            
            // Save updated token and conversation ID
            if ($updatedToken) {
                file_put_contents($dataDir . '/token.txt', $updatedToken);
            }
            file_put_contents($dataDir . '/conversation_id.txt', $conversationId);
            
            return $conversationId;
        }
    }
    
    echo "❌ Failed to start conversation: HTTP {$httpCode}\n";
    if ($response) {
        echo "Response: {$response}\n";
    }
    
    return null;
}

// Run if called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    startConversation();
}
?> 