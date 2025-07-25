# Python 範例

## 安裝依賴

```bash
pip install -r requirements.txt
```

## 使用方法

### 1. 配置參數

編輯 `config.py` 文件：

```python
API_BASE_URL = "https://api.example.com"  # 替換為實際的 API URL
API_KEY = "{CMS-API-KEY}"                 # 替換為您的 API Key

CONVERSATION_ID = "1752304746"            # 替換為實際的對話 ID
CHANNEL_ID = "directline"                 # 替換為實際的頻道 ID
USER_ID = "febf6976-d245-4490-a38a-7fd9e905e3df"  # 替換為實際的用戶 ID
```

### 2. 執行

```bash
python retrieve_references.py
```

## 範例輸出

```
檢索對話參考文獻...
成功!
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
        }
      ]
    }
  ]
}
結果已保存到 data/response.json
``` 