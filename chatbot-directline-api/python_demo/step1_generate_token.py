import requests
import os
from config import BOT_SECRET


def generate_token():
    """Step 1: Generate access token using Bot Secret"""
    print("Step 1: Generating access token...")

    # Get the directory where this script is located
    script_dir = os.path.dirname(os.path.abspath(__file__))
    data_dir = os.path.join(script_dir, "data")
    os.makedirs(data_dir, exist_ok=True)

    url = "https://directline.botframework.com/v3/directline/tokens/generate"
    headers = {
        "Authorization": f"Bearer {BOT_SECRET}",
        "Content-Type": "application/json",
    }

    # Send request to generate token
    response = requests.post(url, headers=headers, json={})

    if response.status_code == 200:
        data = response.json()
        token = data.get("token")
        conversation_id = data.get("conversationId")

        print(f"✅ Token generated successfully!")
        print(f"   Conversation ID: {conversation_id}")
        print(f"   Token expires in: {data.get('expires_in')} seconds")

        # Save token and conversation ID for next steps
        with open(os.path.join(data_dir, "token.txt"), "w") as f:
            f.write(token)
        with open(os.path.join(data_dir, "conversation_id.txt"), "w") as f:
            f.write(conversation_id)

        return token, conversation_id
    else:
        print(f"❌ Failed to generate token: {response.text}")
        return None, None


if __name__ == "__main__":
    generate_token()
