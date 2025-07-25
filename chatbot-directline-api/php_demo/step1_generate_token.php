<?php
/**
 * Step 1: Generate access token using Bot Secret
 */

require_once 'config.php';

function generateToken() {
    echo "Step 1: Generating access token...\n";
    
    // Ensure data directory exists
    $dataDir = 'data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    $url = 'https://directline.botframework.com/v3/directline/tokens/generate';
    $headers = [
        'Authorization: Bearer ' . BOT_SECRET,
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
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        $token = $data['token'] ?? null;
        $conversationId = $data['conversationId'] ?? null;
        
        if ($token && $conversationId) {
            echo "✅ Token generated successfully!\n";
            echo "   Conversation ID: {$conversationId}\n";
            echo "   Token expires in: {$data['expires_in']} seconds\n";
            
            // Save token and conversation ID for next steps
            file_put_contents($dataDir . '/token.txt', $token);
            file_put_contents($dataDir . '/conversation_id.txt', $conversationId);
            
            return [$token, $conversationId];
        }
    }
    
    echo "❌ Failed to generate token: HTTP {$httpCode}\n";
    if ($response) {
        echo "Response: {$response}\n";
    }
    
    return [null, null];
}

// Run if called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    generateToken();
}
?> 