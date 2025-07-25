"""
Author: Shawn
Date: 2025-07-25 21:41:25
LastEditTime: 2025-07-25 22:23:43
"""

#!/usr/bin/env python3
"""
檢索聊天記錄參考文獻 API 範例
"""

import requests
import json
from config import API_BASE_URL, CONVERSATION_ID, CHANNEL_ID, USER_ID, HEADERS


def get_references(conversation_id, channel_id, user_id):
    """檢索對話的參考文獻"""
    url = f"{API_BASE_URL}/chatlog/conversation/{conversation_id}/channel/{channel_id}/user/{user_id}"

    try:
        response = requests.get(url, headers=HEADERS)

        if response.status_code == 200:
            return response.json()
        else:
            print(f"錯誤: {response.status_code}")
            return None

    except Exception as e:
        print(f"請求錯誤: {e}")
        return None


def main():
    print("檢索對話參考文獻...")

    result = get_references(CONVERSATION_ID, CHANNEL_ID, USER_ID)

    if result:
        print("成功!")
        print(json.dumps(result, ensure_ascii=False, indent=2))

        # 保存結果
        with open("data/response.json", "w", encoding="utf-8") as f:
            json.dump(result, f, ensure_ascii=False, indent=2)
        print("結果已保存到 data/response.json")
    else:
        print("檢索失敗")


if __name__ == "__main__":
    main()
