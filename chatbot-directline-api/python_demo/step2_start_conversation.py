import requests
import os


def start_conversation():
    """Step 2: Start a new conversation session"""
    print("Step 2: Starting conversation...")

    # Get the directory where this script is located
    script_dir = os.path.dirname(os.path.abspath(__file__))
    data_dir = os.path.join(script_dir, "data")
    os.makedirs(data_dir, exist_ok=True)

    # Read token from previous step
    try:
        with open(os.path.join(data_dir, "token.txt"), "r") as f:
            token = f.read().strip()
    except FileNotFoundError:
        print("❌ Token not found. Run step1_generate_token.py first.")
        return None

    url = "https://directline.botframework.com/v3/directline/conversations"
    headers = {
        "Authorization": f"Bearer {token}",
        "Content-Type": "application/json",
    }

    # Send request to start conversation
    response = requests.post(url, headers=headers, json={})

    if response.status_code in [200, 201]:
        data = response.json()
        conversation_id = data.get("conversationId")
        updated_token = data.get("token")

        print(f"✅ Conversation started successfully!")
        print(f"   Conversation ID: {conversation_id}")
        print(f"   Stream URL available: {'Yes' if data.get('streamUrl') else 'No'}")

        # Save updated token and conversation ID
        with open(os.path.join(data_dir, "token.txt"), "w") as f:
            f.write(updated_token)
        with open(os.path.join(data_dir, "conversation_id.txt"), "w") as f:
            f.write(conversation_id)

        return conversation_id
    else:
        print(f"❌ Failed to start conversation: {response.text}")
        return None


if __name__ == "__main__":
    start_conversation()
