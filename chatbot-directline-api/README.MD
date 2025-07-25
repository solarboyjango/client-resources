# 機器人對話 API 說明

## 概述

Direct Line API 直接與我們的機器人服務對話，可以要直接控制對話流程。

**重要**: 使用本 API 前，請先向我們的技術支援團隊申請 Bot Secret，這是訪問我們服務的必要憑證。

## 快速開始

### 程式語言範例

我們提供多種程式語言的完整範例，包含所有 API 調用的實作：

- 📁 [Python 範例](python_demo/) - 完整的 Python 實作
- 🐘 [PHP 範例](php_demo/) - 完整的 PHP 實作

## 基礎資訊

- **Base URL**: `https://directline.botframework.com/v3/directline`
- **認證方式**: Bearer Token
- **內容類型**: `application/json`

## API 端點詳細說明

### 1. 生成 Token

**端點**: `POST /tokens/generate`

**用途**: 生成用於後續 API 調用的訪問 token

####  Request 參數

**Headers**:
```
Authorization: Bearer {YOUR_BOT_SECRET}
Content-Type: application/json
```

**注意**: `YOUR_BOT_SECRET` 需要向我們的技術支援團隊申請取得，請聯繫我們的客戶服務部門獲取專屬的 Bot Secret。

**Request Body**: `{}` (無)

#### Response 參數

**成功 Response** (status_code =  200):

| 欄位名稱 | 類型 | 必填 | 描述 | 範例值 |
|---------|------|------|------|--------|
| `conversationId` | string | 是 | 對話的唯一標識符 | `"IxMX0bH5G4aKssAPYa18Pu-as"` |
| `token` | string | 是 | JWT 格式的訪問 token | `"eyJhbGciOiJSUzI1NiIsImtpZCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsIng1dCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsInR5cCI6IkpXVCJ9..."` |
| `expires_in` | integer | 是 |  token過期時間（秒） | `3600` |

**Response 範例**:
```json
{
  "conversationId": "IxMX0bH5G4aKssAPYa18Pu-as",
  "token": "eyJhbGciOiJSUzI1NiIsImtpZCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsIng1dCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsInR5cCI6IkpXVCJ9.eyJib3QiOiJncmFwaGljLWJvdCIsInNpdGUiOiI5M3hlOHdrM1VmcndnOXNnaWRzam9BQWpxWjlTeXJlZGFRV3JicXJsMklqM3V3VEtHN1QwSlFRSjk5QkdBQzI0cGJFQUFyb2hBQUFCQVpCUzJwWjgiLCJjb252IjoiSXhNWDBiSDVHNGFLc3NBUFlhMThQdS1hcyIsIm5iZiI6MTc1Mjg1MzE4MSwiZXhwIjoxNzUyODU2NzgxLCJpc3MiOiJodHRwczovL2RpcmVjdGxpbmUuYm90ZnJhbWV3b3JrLmNvbS8iLCJhdWQiOiJodHRwczovL2RpcmVjdGxpbmUuYm90ZnJhbWV3b3JrLmNvbS8ifQ.yyAWWX8sX-Mq__5u68I_XIGv7-SJdyJIXu9byRexm9QFE6yil4F8gk7LmTFbRqjdrYm-ufJh2tOCAOmRDno5HR9qjGh0p8Xkrs2vZDlVdwawBefUABiihFn96tSb61prumoRkc1nV--bKqy3CBKzE6orafwDCH7uarUlrL2EGt12wNo0XF-_nqreLH7OIwomKxdsGqX7HAByufQu4pj326LGFnFbtlzyVJi96BN59YypZrp6YbxTJ7Vd14jao8XzU8OfQBDzcAXLRuSteB1cd2nXpHFbAFAOjwaoqNkxAncpO2UD6qmbi1trp6y5jImsPpsgvG45X1txKdl9Omk0fA",
  "expires_in": 3600
}
```

### 2. 啟動對話

**端點**: `POST /conversations`

**用途**: 創建新的對話會話

####  Request 參數

**Headers**:
```
Authorization: Bearer {YOUR_TOKEN}
Content-Type: application/json
```

**注意**: `YOUR_TOKEN` 是通過上述 Bot Secret 生成的訪問 token ，有效期為 1 小時。

**Request Body**: `{}` (無)

####  Response 參數

**成功 Response** (status_code =  200 或 201):

| 欄位名稱 | 類型 | 必填 | 描述 | 範例值 |
|---------|------|------|------|--------|
| `conversationId` | string | 是 | 對話的唯一標識符 | `"IxMX0bH5G4aKssAPYa18Pu-as"` |
| `token` | string | 是 | 更新的 JWT  token | `"eyJhbGciOiJSUzI1NiIsImtpZCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsIng1dCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsInR5cCI6IkpXVCJ9..."` |
| `expires_in` | integer | 是 |  token過期時間（秒） | `3600` |
| `streamUrl` | string | 是 | WebSocket 流連接 URL | `"wss://directline.botframework.com/v3/directline/conversations/IxMX0bH5G4aKssAPYa18Pu-as/stream?watermark=-&t=..."` |
| `referenceGrammarId` | string | 是 | 參考語法 ID | `"cfedcfc4-c053-ad52-b7a7-d09259924d1d"` |

**Response 範例**:
```json
{
  "conversationId": "IxMX0bH5G4aKssAPYa18Pu-as",
  "token": "eyJhbGciOiJSUzI1NiIsImtpZCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsIng1dCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsInR5cCI6IkpXVCJ9.eyJib3QiOiJncmFwaGljLWJvdCIsInNpdGUiOiI5M3hlOHdrM1VmcndnOXNnaWRzam9BQWpxWjlTeXJlZGFRV3JicXJsMklqM3V3VEtHN1QwSlFRSjk5QkdBQzI0cGJFQUFyb2hBQUFCQVpCUzJwWjgiLCJjb252IjoiSXhNWDBiSDVHNGFLc3NBUFlhMThQdS1hcyIsIm5iZiI6MTc1Mjg1MzE4MSwiZXhwIjoxNzUyODU2NzgxLCJpc3MiOiJodHRwczovL2RpcmVjdGxpbmUuYm90ZnJhbWV3b3JrLmNvbS8iLCJhdWQiOiJodHRwczovL2RpcmVjdGxpbmUuYm90ZnJhbWV3b3JrLmNvbS8ifQ.yyAWWX8sX-Mq__5u68I_XIGv7-SJdyJIXu9byRexm9QFE6yil4F8gk7LmTFbRqjdrYm-ufJh2tOCAOmRDno5HR9qjGh0p8Xkrs2vZDlVdwawBefUABiihFn96tSb61prumoRkc1nV--bKqy3CBKzE6orafwDCH7uarUlrL2EGt12wNo0XF-_nqreLH7OIwomKxdsGqX7HAByufQu4pj326LGFnFbtlzyVJi96BN59YypZrp6YbxTJ7Vd14jao8XzU8OfQBDzcAXLRuSteB1cd2nXpHFbAFAOjwaoqNkxAncpO2UD6qmbi1trp6y5jImsPpsgvG45X1txKdl9Omk0fA",
  "expires_in": 3600,
  "streamUrl": "wss://directline.botframework.com/v3/directline/conversations/IxMX0bH5G4aKssAPYa18Pu-as/stream?watermark=-&t=eyJhbGciOiJSUzI1NiIsImtpZCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsIng1dCI6IkpHd3R5VFZ6S1Z3ZjVIT0U5YlpqWmNFdjEtbyIsInR5cCI6IkpXVCJ9.eyJib3QiOiJncmFwaGljLWJvdCIsInNpdGUiOiI5M3hlOHdrM1VmcndnOXNnaWRzam9BQWpxWjlTeXJlZGFRV3JicXJsMklqM3V3VEtHN1QwSlFRSjk5QkdBQzI0cGJFQUFyb2hBQUFCQVpCUzJwWjgiLCJjb252IjoiSXhNWDBiSDVHNGFLc3NBUFlhMThQdS1hcyIsIm5iZiI6MTc1Mjg1MzE4MSwiZXhwIjoxNzUyODUzMjQxLCJpc3MiOiJodHRwczovL2RpcmVjdGxpbmUuYm90ZnJhbWV3b3JrLmNvbS8iLCJhdWQiOiJodHRwczovL2RpcmVjdGxpbmUuYm90ZnJhbWV3b3JrLmNvbS8ifQ.F3MuDtVV9vrP0eMXLa45gnAfN9fwLgRMFM31B7jV9XJE4gAKHWWHsQic0Em60X7s1g2ZtSQU1Sph2u3p7LqMLepqQq4g-iX5qlN2yDP96CFok8nhBCiaDoWEd0REIyS6c9CTEJDW6WssroToSwj0FpHzSBzxg4FEvFr85b86oMI6HK_tekLD_H24KyObbIQzU1u-knXKFX6IP_bmtZwDp1KFSuURyxrrbcKMhOCLdLUe84x2Mvi-uLodTxyLUZJk8gUB0n03jOtpRcCeDJQN84UTOQ5A-WqAWZOUXNUnZOkWRmGB853eAFQwHtGWKXvPkGDq4Rb0Ruv6LcblhGhzhg",
  "referenceGrammarId": "cfedcfc4-c053-ad52-b7a7-d09259924d1d"
}
```

### 3. 發送訊息

**端點**: `POST /conversations/{conversationId}/activities`

**用途**: 向我們的機器人服務發送訊息

####  Request 參數

**Headers**:
```
Authorization: Bearer {YOUR_TOKEN}
Content-Type: application/json
```

**注意**: `YOUR_TOKEN` 是通過 Bot Secret 生成的訪問 token ，請確保 token 未過期。

**Path Parameters**:
| 參數名稱 | 類型 | 必填 | 描述 |
|---------|------|------|------|
| `conversationId` | string | 是 | 對話 ID |

**Request Body**:
| 欄位名稱 | 類型 | 必填 | 描述 | 範例值 |
|---------|------|------|------|--------|
| `type` | string | 是 | 活動類型，固定值為 `"message"` | `"message"` |
| `from` | object | 是 | 發送者資訊 | `{"id": "user_id"}` |
| `text` | string | 是 | 訊息內容 | `"你好，這是測試訊息"` |

**重要說明**:
- `type` 欄位必須固定為 `"message"`，不可更改
- `from.id` 必須填入您的用戶 ID，用於識別訊息發送者
- 請確保 `user_id` 是您系統中的有效用戶標識符

** Request 範例**:
```json
{
  "type": "message",
  "from": {
    "id": "user_12345"
  },
  "text": "你好，這是測試訊息"
}
```

**格式要求**:
- `type` 必須為 `"message"`
- `from.id` 必須為有效的用戶 ID
- 請將 `user_12345` 替換為您實際的用戶 ID

####  Response 參數

**成功 Response** (status_code =  200):

| 欄位名稱 | 類型 | 必填 | 描述 | 範例值 |
|---------|------|------|------|--------|
| `id` | string | 是 | 活動的唯一標識符 | `"IxMX0bH5G4aKssAPYa18Pu-as|0000002"` |

**Response 範例**:
```json
{
  "id": "IxMX0bH5G4aKssAPYa18Pu-as|0000002"
}
```

### 4. 獲取對話狀態

**端點**: `GET /conversations/{conversationId}/activities`

**用途**: 檢索對話中的所有狀態記錄

**為什麼需要這個 API？**
當您發送訊息給機器人後，機器人需要時間處理並回覆。由於處理時間不固定，您需要定期檢查是否有回覆。這個 API 讓您能夠獲取機器人的回覆狀態。

**兩種用法**:
1. **獲取所有記錄**: 不使用 watermark 參數，獲取完整對話歷史
2. **獲取新回覆**: 使用 watermark 參數，只獲取上次檢查後的新回覆

####  Request 參數

**Headers**:
```
Authorization: Bearer {YOUR_TOKEN}
```

**注意**: `YOUR_TOKEN` 是通過 Bot Secret 生成的訪問 token ，請確保 token 未過期。

**Path Parameters**:
| 參數名稱 | 類型 | 必填 | 描述 |
|---------|------|------|------|
| `conversationId` | string | 是 | 對話 ID |

**Query Parameters**:
| 參數名稱 | 類型 | 必填 | 描述 | 範例值 |
|---------|------|------|------|--------|
| `watermark` | string | 否 | 只檢索指定水印之後的新回覆 | `"3"` |

**Watermark 機制說明**:
- Watermark 是一個數字標識符，代表對話中最後一個狀態的序號
- 當您使用 watermark 參數時，API 只會返回該水印之後的新回覆
- 這可以避免重複獲取已經讀取過的訊息，以提高效率
- 每次獲取狀態時，響應中都會包含新的 watermark 值
- 建議在下次請求時使用這個新的 watermark 值


#### Response 參數

**成功 Response** (status_code =  200):

| 欄位名稱 | 類型 | 必填 | 描述 |
|---------|------|------|------|
| `activities` | array | 是 | 狀態記錄列表 |
| `watermark` | string | 是 | 當前水印值 |

**`activities` 結構**:
| 欄位名稱 | 類型 | 必填 | 描述 | 範例值 |
|---------|------|------|------|--------|
| `type` | string | 是 | 活動類型 | `"message"` |
| `id` | string | 是 | 狀態記錄 ID | `"IxMX0bH5G4aKssAPYa18Pu-as|0000000"` |
| `timestamp` | string | 是 | 時間戳 | `"2025-07-18T15:39:41.9094513Z"` |
| `channelId` | string | 是 | 頻道 ID | `"directline"` |
| `from` | object | 是 | 發送者資訊 | `{"id": "user_12345"}` 或 `{"id": "graphic-bot", "name": "graphic-bot"}` |
| `conversation` | object | 是 | 對話資訊 | `{"id": "IxMX0bH5G4aKssAPYa18Pu-as"}` |
| `text` | string | 是 | 訊息內容 | `"Hi, 有什麼可以幫助您的嗎？"` |
| `inputHint` | string | 否 | 輸入提示 | `"acceptingInput"` |
| `replyToId` | string | 否 | 回覆的狀態記錄 ID | `"EU8s4dhSq1Y"` |
| `serviceUrl` | string | 否 | 服務 URL | `"https://directline.botframework.com/"` |

**Response 範例**:
```json
{
  "activities": [
    {
      "type": "message",
      "id": "IxMX0bH5G4aKssAPYa18Pu-as|0000000",
      "timestamp": "2025-07-18T15:39:41.9094513Z",
      "channelId": "directline",
      "from": {
        "id": "graphic-bot",
        "name": "graphic-bot"
      },
      "conversation": {
        "id": "IxMX0bH5G4aKssAPYa18Pu-as"
      },
      "text": "Hi, 有什麼可以幫助您的嗎？",
      "inputHint": "acceptingInput",
      "replyToId": "EU8s4dhSq1Y"
    },
    {
      "type": "message",
      "id": "IxMX0bH5G4aKssAPYa18Pu-as|0000002",
      "timestamp": "2025-07-18T15:39:42.2011152Z",
      "serviceUrl": "https://directline.botframework.com/",
      "channelId": "directline",
      "from": {
        "id": "user_12345"
      },
      "conversation": {
        "id": "IxMX0bH5G4aKssAPYa18Pu-as"
      },
      "text": "你好，這是測試訊息"
    },
    {
      "type": "message",
      "id": "IxMX0bH5G4aKssAPYa18Pu-as|0000003",
      "timestamp": "2025-07-18T15:39:44.146617Z",
      "channelId": "directline",
      "from": {
        "id": "graphic-bot",
        "name": "graphic-bot"
      },
      "conversation": {
        "id": "IxMX0bH5G4aKssAPYa18Pu-as"
      },
      "text": "你好！再次收到您的測試訊息，隨時告訴我需要協助的內容喔！😊",
      "inputHint": "acceptingInput",
      "replyToId": "IxMX0bH5G4aKssAPYa18Pu-as|0000002"
    }
  ],
  "watermark": "3"
}
```

**Watermark 使用場景**:
- **輪詢機器人回覆**: 定期檢查機器人是否有新回覆
- **避免重複**: 防止重複獲取已經處理過的狀態記錄
- **提高效率**: 減少不必要的數據傳輸
- **實時更新**: 配合輪詢機制實現類似實時的效果

**實際應用流程**:
1. 用戶發送訊息 → 機器人開始處理
2. 定期檢查回覆（每 1-3 秒）→ 使用 watermark 只檢查新回覆
3. 收到機器人回覆 → 更新 watermark
4. 繼續等待或發送下一條訊息

**使用流程**:
1. 首次獲取對話狀態時不使用 watermark 參數
2. 從響應中獲取 watermark 值（例如："3"）
3. 下次請求時使用 `?watermark=3`
4. 如果沒有新狀態，返回空列表和相同的 watermark
5. 如果有新狀態，返回新狀態和更新的 watermark

**輪詢建議**:
- 建議每 1-3 秒檢查一次新回覆
- 機器人處理時間通常在 1-10 秒之間
- 如果超過 30 秒沒有回覆，可能需要重新發送訊息

**Response 範例** (當沒有新狀態時):
```json
{
  "activities": [],
  "watermark": "3"
}
```

**Response 範例** (當有新狀態時):
```json
{
  "activities": [
    {
      "type": "message",
      "id": "IxMX0bH5G4aKssAPYa18Pu-as|0000004",
      "timestamp": "2025-07-18T15:40:00.0000000Z",
      "channelId": "directline",
      "from": {
        "id": "graphic-bot",
        "name": "graphic-bot"
      },
      "conversation": {
        "id": "IxMX0bH5G4aKssAPYa18Pu-as"
      },
      "text": "這是新收到的訊息",
      "inputHint": "acceptingInput"
    }
  ],
  "watermark": "4"
}
```

**注意**: 在響應中，`from.id` 會顯示實際的用戶 ID（如 `user_12345`）或機器人 ID（如 `graphic-bot`）



## 錯誤代碼

| HTTP 狀態碼 | 描述 | 解決方案 |
|------------|------|----------|
| 200 |  Request 成功 | - |
| 201 | 資源創建成功 | - |
| 400 |  Request 參數無效 | 檢查 Request 參數格式 |
| 401 |  token無效或已過期 | 重新生成 token |
| 404 | 對話未找到 | 檢查對話 ID |
| 429 |  Request 頻率過高 | 降低 Request 頻率 |
| 500 | 服務器內部錯誤 | 稍後重試 |

## 重要注意事項

1. **token過期**:  token在 1 小時後過期（3600 秒）
2. **對話狀態**: 每個對話會話都是獨立的
3. **錯誤處理**: 始終檢查 HTTP 狀態碼
4. **輪詢策略**: 由於機器人回覆時間不固定，建議每 1-3 秒檢查一次新回覆
5. **安全性**: 切勿在客戶端代碼中暴露您的 Bot Secret，如有遺失請立即聯繫我們的技術支援團隊
6. **水印使用**: 使用水印只獲取新狀態，避免重複獲取已處理的狀態記錄