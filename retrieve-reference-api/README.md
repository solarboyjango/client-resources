# 聊天記錄使用參考文獻 API 說明

## 概述

提供對話聊天記錄所引用的參考文獻

**重要**: 使用本 API 前，請先向我們的技術支援團隊申請 CMS API Key，這是訪問我們服務的必要憑證。

## 快速開始

### 程式語言範例

我們提供多種程式語言的完整範例，包含所有 API 調用的實作：

- 📁 [Python 範例](python_demo/) - 完整的 Python 實作
- 🐘 [PHP 範例](php_demo/) - 完整的 PHP 實作

## 基礎資訊

- **認證方式**: API Key (X-API-Key Header)
- **內容類型**: `application/json`
- **API Key 格式**: `{CMS-API-KEY}`

## API 端點詳細說明

### 檢索聊天記錄

**端點**: `GET /chatlog/conversation/{conversation_id}/channel/{channel_id}/user/{user_id}`

**用途**: 檢索特定對話的聊天記錄，提取元數據並格式化回應

#### Request 參數

**Headers**:
```
X-API-Key: {CMS-API-KEY}
Content-Type: application/json
```

**URL 參數**:

| 參數名稱 | 類型 | 必填 | 描述 | 範例值 |
|---------|------|------|------|--------|
| `conversation_id` | string | 是 | 要檢索記錄的對話唯一標識符 | `"1752304746"` |
| `channel_id` | string | 是 | 頻道標識符 | `"directline"` |
| `user_id` | string | 是 | 用戶唯一標識符 | `"febf6976-d245-4490-a38a-7fd9e905e3df"` |

#### Response 參數

**成功 Response** (status_code = 200):

| 欄位名稱 | 類型 | 必填 | 描述 | 範例值 |
|---------|------|------|------|--------|
| `code` | integer | 是 | HTTP 狀態碼 | `200` |
| `msg` | string | 是 | 確認訊息 | `"Chat logs retrieved successfully"` |
| `data` | array | 是 | 聊天記錄摘要陣列 | 見下方範例 |
| `conversation_id` | string | 是 | 對話標識符 | `"1752304746"` |
| `channel_id` | string | 是 | 頻道標識符 | `"directline"` |
| `created_at` | integer | 是 | Unix 時間戳記 (秒) | `1752275951` |
| `meta` | array | 是 | 包含文件標題的元數據物件陣列 | 見下方範例 |
| `title` | string | 是 | 參考文件的標題 | `"G492.txt"` |

**Response 範例**:
```json
{
  "code": 200,
  "msg": "Chat logs retrieved successfully",
  "data": [
    {
      "conversation_id": "1752304746",
      "channel_id": "directline",
      "created_at": 1752275951,
      "meta": [
        {
          "title": "G492.txt"
        },
        {
          "title": "114期導入文創打開市場敲門磚.pdf"
        },
        {
          "title": "132期運用ChatGPT生成文章的步驟.pdf"
        }
      ]
    }
  ]
}
```

#### 狀態碼

| 狀態碼 | 描述 |
|--------|------|
| `200 OK` | 聊天記錄檢索成功 |
| `403 Forbidden` | 缺少或無效的 API Key |
| `500 Internal Server Error` | 伺服器端錯誤 |

#### 注意事項

- 只會返回具有有效元數據的聊天記錄
- `meta` 欄位包含從原始聊天記錄元數據中提取的文件標題
- 時間戳記以 Unix 時間戳記格式返回 (自紀元以來的秒數)
- 沒有元數據的文件會自動被過濾掉
- API 支援每個聊天記錄條目的多個元數據物件

## 錯誤處理

| 錯誤碼 | 描述 |
|--------|------|
| 400 Bad Request | 請求包含無效參數或缺少欄位 |
| 403 Forbidden | 缺少或無效的 API Key |
| 404 Not Found | 請求的資源不存在 |
| 500 Internal Server Error | 意外的伺服器端錯誤 |

## 使用範例

### cURL 範例

```bash
curl -X GET "https://api.example.com/chatlog/conversation/1752304746/channel/directline/user/febf6976-d245-4490-a38a-7fd9e905e3df" \
  -H "X-API-Key: {CMS-API-KEY}" \
  -H "Content-Type: application/json"
```



## 支援

如果您在使用過程中遇到任何問題，請聯繫我們的技術支援團隊。