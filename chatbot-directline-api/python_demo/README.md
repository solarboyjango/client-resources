# Bot API 使用範例

這是一個完整的 Bot API 使用範例，展示如何與機器人服務進行對話。

## 系統需求

### Python 版本
- **Python 3.7 或更高版本**
- 建議使用 Python 3.8+

### 檢查 Python 版本
```bash
python --version
# 或
python3 --version
```

## 快速開始

### 1. 安裝必要套件
```bash
pip install requests
```

### 2. 設定您的憑證
編輯 `config.py` 檔案：
```python
# 您的 Bot Secret - 請向技術支援團隊申請
BOT_SECRET = "your_bot_secret_here"

# 您的用戶 ID - 請替換為您的實際用戶 ID
USER_ID = "your_user_id_here"

# 要發送給機器人的訊息
MESSAGE = "您的訊息內容"
```

### 3. 執行程式
```bash
python main.py
```

## 程式執行流程

### 完整流程 (推薦)
```bash
python main.py
```
這會自動執行以下步驟：
1. 生成訪問 token
2. 啟動對話
3. 發送訊息
4. 等待並顯示機器人回覆

### 分步驟執行
如果您想了解每個步驟的細節：

```bash
# 步驟 1: 生成 token
python step1_generate_token.py

# 步驟 2: 啟動對話
python step2_start_conversation.py

# 步驟 3: 發送訊息
python step3_send_message.py

# 步驟 4: 獲取訊息和輪詢回覆
python step4_get_messages.py
```

## 各步驟說明

| 步驟 | 檔案 | 功能 | 輸出 |
|------|------|------|------|
| 1 | `step1_generate_token.py` | 使用 Bot Secret 生成訪問 token | `token.txt` |
| 2 | `step2_start_conversation.py` | 創建新的對話會話 | `conversation_id.txt` |
| 3 | `step3_send_message.py` | 發送訊息給機器人 | 訊息 ID |
| 4 | `step4_get_messages.py` | 獲取對話記錄和輪詢新回覆 | `watermark.txt` |

## 產生的檔案

執行後會產生以下檔案：
- `data/token.txt` - 訪問 token (有效期 1 小時)
- `data/conversation_id.txt` - 對話 ID
- `data/watermark.txt` - 最後的水印值 (用於輪詢)

## 自訂設定

### 修改輪詢參數
在 `main.py` 中修改：
```python
reply_data = poll_for_reply_with_watermark(
    watermark_before_send, 
    max_attempts=15,  # 最大嘗試次數
    interval=2        # 輪詢間隔 (秒)
)
```

### 修改訊息內容
在 `config.py` 中修改 `MESSAGE` 變數：
```python
MESSAGE = "您想要發送給機器人的訊息"
```

## 常見問題

### Q: 出現 "Token or conversation ID not found" 錯誤
**A**: 請按順序執行步驟，或先執行 `python main.py`

### Q: 機器人沒有回覆
**A**: 
- 檢查網路連接
- 確認 Bot Secret 正確
- 等待 30 秒 (程式會自動輪詢)

### Q: Token 過期
**A**: Token 有效期為 1 小時，重新執行 `python main.py` 即可

### Q: 想要發送不同訊息
**A**: 修改 `config.py` 中的 `MESSAGE` 變數

## 輪詢機制說明

程式使用 watermark 機制高效輪詢機器人回覆：
- 每 2 秒檢查一次新回覆
- 最多檢查 15 次 (30 秒)
- 只顯示機器人對您問題的最新回覆
- 避免重複獲取已讀訊息

## 範例輸出

```
🤖 Bot API Demo - Complete Flow
==================================================

📋 Step 1: Generate Access Token
✅ Token generated successfully!

📋 Step 2: Start Conversation  
✅ Conversation started successfully!

📋 Step 3: Get Initial State
✅ Retrieved 1 message(s)

📋 Step 4: Get Watermark Before Sending
📌 Watermark before sending: 0

📋 Step 5: Send Message
✅ Message sent successfully!

📋 Step 6: Poll for Bot Reply
🎉 Bot replied! Found 3 new message(s)
🤖 Bot's reply to your question:
   關於這問題的答案是...

🎉 Demo completed successfully!
```

## 技術支援

如有問題，請聯繫技術支援團隊：
- 申請 Bot Secret
- API 使用問題
- 錯誤排除 