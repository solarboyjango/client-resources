import os
from dotenv import load_dotenv

# 載入 .env 文件
load_dotenv()

# API 配置
API_BASE_URL = os.getenv("API_BASE_URL", "https://api.example.com")
API_KEY = os.getenv("API_KEY", "your_api_key")

# 範例參數
CONVERSATION_ID = "KPncYq5yZwo1KVAwTwzWAO-as"
CHANNEL_ID = "directline"
USER_ID = "php_demo"

# 請求標頭
HEADERS = {"X-API-Key": API_KEY, "Content-Type": "application/json"}
