<?php
/**
 * Author: Shawn
 * Date: 2025-07-19 00:41:56
 * LastEditTime: 2025-07-19 00:42:01
 * 
 * Run complete Bot API demo
 */

require_once 'config.php';
require_once 'step1_generate_token.php';
require_once 'step2_start_conversation.php';
require_once 'step3_send_message.php';
require_once 'step4_get_messages.php';

function main() {
    echo "🤖 Bot API Demo - Complete Flow\n";
    echo str_repeat("=", 50) . "\n";
    
    // Step 1: Generate token
    echo "\n📋 Step 1: Generate Access Token\n";
    echo str_repeat("-", 30) . "\n";
    list($token, $conversationId) = generateToken();
    if (!$token) {
        echo "❌ Demo failed at Step 1\n";
        return;
    }
    
    // Step 2: Start conversation
    echo "\n📋 Step 2: Start Conversation\n";
    echo str_repeat("-", 30) . "\n";
    $conversationId = startConversation();
    if (!$conversationId) {
        echo "❌ Demo failed at Step 2\n";
        return;
    }
    
    // Step 3: Get initial state (before sending message)
    echo "\n📋 Step 3: Get Initial State\n";
    echo str_repeat("-", 30) . "\n";
    $initialData = getMessages();
    if (!$initialData) {
        echo "❌ Demo failed at Step 3\n";
        return;
    }
    
    // Step 4: Get watermark before sending message
    echo "\n📋 Step 4: Get Watermark Before Sending\n";
    echo str_repeat("-", 30) . "\n";
    $watermarkBeforeSend = getCurrentWatermark();
    if ($watermarkBeforeSend === null) {
        echo "❌ Demo failed at Step 4\n";
        return;
    }
    echo "📌 Watermark before sending: {$watermarkBeforeSend}\n";
    
    // Step 5: Send message
    echo "\n📋 Step 5: Send Message\n";
    echo str_repeat("-", 30) . "\n";
    $messageId = sendMessage(MESSAGE);
    if (!$messageId) {
        echo "❌ Demo failed at Step 5\n";
        return;
    }
    
    // Step 6: Poll for bot reply
    echo "\n📋 Step 6: Poll for Bot Reply\n";
    echo str_repeat("-", 30) . "\n";
    echo "⏳ Waiting for bot to process and reply...\n";
    $replyData = pollForReplyWithWatermark($watermarkBeforeSend, 15, 2);
    
    if ($replyData) {
        echo "\n🎉 Demo completed successfully!\n";
        echo "✅ Bot replied to your message\n";
    } else {
        echo "\n⚠️ Demo completed with timeout\n";
        echo "ℹ️ Bot may still be processing your message\n";
    }
    
    echo "\n📁 Generated files:\n";
    echo "   - data/token.txt (access token)\n";
    echo "   - data/conversation_id.txt (conversation ID)\n";
    echo "   - data/watermark.txt (last watermark)\n";
}

function pollForReplyWithWatermark($startWatermark, $maxAttempts = 10, $interval = 2) {
    echo "\n🔄 Polling for bot reply (max {$maxAttempts} attempts, {$interval}s interval)...\n";
    echo "📌 Starting to poll from watermark: {$startWatermark}\n";
    
    // Poll for new messages
    for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
        echo "\nAttempt {$attempt}/{$maxAttempts}...\n";
        sleep($interval);
        
        $data = getMessages($startWatermark);
        if (!$data) {
            continue;
        }
        
        $activities = $data['activities'] ?? [];
        $newWatermark = $data['watermark'] ?? null;
        
        // Check if we got new messages
        if (count($activities) > 0) {
            echo "🎉 Bot replied! Found " . count($activities) . " new message(s)\n";
            
            // Find the latest bot reply (skip user messages and earlier bot messages)
            $latestBotReply = null;
            foreach ($activities as $activity) {
                $sender = $activity['from']['id'] ?? 'unknown';
                if ($sender === 'graphic-bot') {
                    // Get the latest bot message
                    if ($latestBotReply === null || 
                        ($activity['timestamp'] ?? '') > ($latestBotReply['timestamp'] ?? '')) {
                        $latestBotReply = $activity;
                    }
                }
            }
            
            if ($latestBotReply) {
                $text = $latestBotReply['text'] ?? '';
                $timestamp = $latestBotReply['timestamp'] ?? '';
                echo "🤖 Bot's reply to your question:\n";
                echo "   {$text}\n";
                echo "   Time: {$timestamp}\n";
            }
            
            return $data;
        }
        
        $startWatermark = $newWatermark;
    }
    
    echo "⏰ No reply received within time limit\n";
    return null;
}

// Run main function
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    main();
}
?>
