<?php
/**
 * API 配置文件
 */

// API 配置
define('API_BASE_URL', 'https://api.example.com');
define('API_KEY', 'cms_12345');

// 範例參數
define('CONVERSATION_ID', '1752304746');
define('CHANNEL_ID', 'directline');
define('USER_ID', 'febf6976-d245-4490-a38a-7fd9e905e3df');

// 請求標頭
$headers = [
    'X-API-Key: ' . API_KEY,
    'Content-Type: application/json'
];
?> 