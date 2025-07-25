import requests
import time
import os


def get_messages(watermark=None):
    """Step 4: Get conversation messages"""
    if watermark:
        print(f"Step 4: Checking for new messages since watermark: {watermark}")
    else:
        print("Step 4: Getting all conversation messages...")

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
    if watermark:
        url += f"?watermark={watermark}"

    headers = {"Authorization": f"Bearer {token}"}

    # Get messages
    response = requests.get(url, headers=headers)

    if response.status_code == 200:
        data = response.json()
        activities = data.get("activities", [])
        new_watermark = data.get("watermark")

        print(f"✅ Retrieved {len(activities)} message(s)")
        print(f"   New watermark: {new_watermark}")

        # Only display messages if not using watermark (i.e., getting all messages)
        # When using watermark, we only want to know if there are new messages, not display them
        if not watermark:
            # Display messages
            for activity in activities:
                sender = activity.get("from", {}).get("id", "unknown")
                text = activity.get("text", "")
                timestamp = activity.get("timestamp", "")

                print(f"   📨 {sender}: {text}")
                print(f"      Time: {timestamp}")

        # Save watermark for next check
        with open(os.path.join(data_dir, "watermark.txt"), "w") as f:
            f.write(str(new_watermark))

        return data
    else:
        print(f"❌ Failed to get messages: {response.text}")
        return None


def get_current_watermark():
    """Get current watermark without displaying messages"""
    # Get the directory where this script is located
    script_dir = os.path.dirname(os.path.abspath(__file__))
    data_dir = os.path.join(script_dir, "data")
    os.makedirs(data_dir, exist_ok=True)

    try:
        with open(os.path.join(data_dir, "token.txt"), "r") as f:
            token = f.read().strip()
        with open(os.path.join(data_dir, "conversation_id.txt"), "r") as f:
            conversation_id = f.read().strip()
    except FileNotFoundError:
        print("❌ Token or conversation ID not found. Run previous steps first.")
        return None

    url = f"https://directline.botframework.com/v3/directline/conversations/{conversation_id}/activities"
    headers = {"Authorization": f"Bearer {token}"}

    response = requests.get(url, headers=headers)
    if response.status_code == 200:
        data = response.json()
        return data.get("watermark")
    else:
        print(f"❌ Failed to get current watermark: {response.text}")
        return None


def poll_for_reply(max_attempts=10, interval=2):
    """Poll for bot reply using watermark"""
    print(
        f"\n🔄 Polling for bot reply (max {max_attempts} attempts, {interval}s interval)..."
    )

    # Get current watermark before polling
    watermark = get_current_watermark()
    if watermark is None:
        return None

    print(f"📌 Starting to poll from watermark: {watermark}")

    # Poll for new messages
    for attempt in range(max_attempts):
        print(f"\nAttempt {attempt + 1}/{max_attempts}...")
        time.sleep(interval)

        data = get_messages(watermark)
        if not data:
            continue

        activities = data.get("activities", [])
        new_watermark = data.get("watermark")

        # Check if we got new messages
        if len(activities) > 0:
            print(f"🎉 Bot replied! Found {len(activities)} new message(s)")
            # Display the new messages
            for activity in activities:
                sender = activity.get("from", {}).get("id", "unknown")
                text = activity.get("text", "")
                timestamp = activity.get("timestamp", "")

                print(f"   📨 {sender}: {text}")
                print(f"      Time: {timestamp}")
            return data

        watermark = new_watermark

    print("⏰ No reply received within time limit")
    return None


if __name__ == "__main__":
    # First get all messages
    get_messages()

    # Then poll for new reply
    poll_for_reply()
