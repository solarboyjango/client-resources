<?php
/**
 * Step 3: Send a message to the bot
 */

require_once 'config.php';

function sendMessage($messageText = null) {
    if ($messageText === null) {
        $messageText = MESSAGE;
    }
    
    echo "Step 3: Sending message: '{$messageText}'\n";
    
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
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ];
    
    // Prepare message payload
    $payload = [
        'type' => 'message',
        'from' => ['id' => USER_ID],
        'text' => $messageText
    ];
    
    // Prepare cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    // Send message
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        $messageId = $data['id'] ?? null;
        
        if ($messageId) {
            echo "✅ Message sent successfully!\n";
            echo "   Message ID: {$messageId}\n";
            
            return $messageId;
        }
    }
    
    echo "❌ Failed to send message: HTTP {$httpCode}\n";
    if ($response) {
        echo "Response: {$response}\n";
    }
    
    return null;
}

// Run if called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    // Send a test message
    sendMessage("Hello, this is a test message");
}
?> 