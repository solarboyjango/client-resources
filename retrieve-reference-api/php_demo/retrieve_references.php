<?php
/**
 * 檢索聊天記錄參考文獻 API 範例
 */

require_once 'config.php';

function getReferences($conversation_id, $channel_id, $user_id) {
    global $headers;
    
    $url = API_BASE_URL . "/chatlog/conversation/{$conversation_id}/channel/{$channel_id}/user/{$user_id}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200) {
        return json_decode($response, true);
    } else {
        echo "錯誤: {$http_code}\n";
        return null;
    }
}

echo "檢索對話參考文獻...\n";

$result = getReferences(CONVERSATION_ID, CHANNEL_ID, USER_ID);

if ($result) {
    echo "成功!\n";
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    
    // 保存結果
    file_put_contents('data/response.json', json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "結果已保存到 data/response.json\n";
} else {
    echo "檢索失敗\n";
}
?> 