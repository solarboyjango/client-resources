import requests
import os
from config import USER_ID


def send_message(message_text):
    """Step 3: Send a message to the bot"""
    print(f"Step 3: Sending message: '{message_text}'")

    # Get the directory where this script is located
    script_dir = os.path.dirname(os.path.abspath(__file__))
    data_dir = os.path.join(script_dir, "data")
    os.makedirs(data_dir, exist_ok=True)

    # Read token and conversation ID from previous steps
    try:
        with open(os.path.join(data_dir, "token.txt"), "r") as f:
            token = f.read().strip()
        with open(os.path.join(data_dir, "conversation_id.txt"), "r") as f:
            conversation_id = f.read().strip()
    except FileNotFoundError:
        print("❌ Token or conversation ID not found. Run previous steps first.")
        return None

    url = f"https://directline.botframework.com/v3/directline/conversations/{conversation_id}/activities"
    headers = {
        "Authorization": f"Bearer {token}",
        "Content-Type": "application/json",
    }

    # Prepare message payload
    payload = {"type": "message", "from": {"id": USER_ID}, "text": message_text}

    # Send message
    response = requests.post(url, headers=headers, json=payload)

    if response.status_code == 200:
        data = response.json()
        message_id = data.get("id")

        print(f"✅ Message sent successfully!")
        print(f"   Message ID: {message_id}")

        return message_id
    else:
        print(f"❌ Failed to send message: {response.text}")
        return None


if __name__ == "__main__":
    # Send a test message
    send_message("Hello, this is a test message")
