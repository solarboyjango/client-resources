import time
from step1_generate_token import generate_token
from step2_start_conversation import start_conversation
from step3_send_message import send_message
from step4_get_messages import get_messages, get_current_watermark
from config import MESSAGE


def main():
    """Run complete Bot API demo"""
    print("🤖 Bot API Demo - Complete Flow")
    print("=" * 50)

    # Step 1: Generate token
    print("\n📋 Step 1: Generate Access Token")
    print("-" * 30)
    token, conversation_id = generate_token()
    if not token:
        print("❌ Demo failed at Step 1")
        return

    # Step 2: Start conversation
    print("\n📋 Step 2: Start Conversation")
    print("-" * 30)
    conversation_id = start_conversation()
    if not conversation_id:
        print("❌ Demo failed at Step 2")
        return

    # Step 3: Get initial state (before sending message)
    print("\n📋 Step 3: Get Initial State")
    print("-" * 30)
    initial_data = get_messages()
    if not initial_data:
        print("❌ Demo failed at Step 3")
        return

    # Step 4: Get watermark before sending message
    print("\n📋 Step 4: Get Watermark Before Sending")
    print("-" * 30)
    watermark_before_send = get_current_watermark()
    if watermark_before_send is None:
        print("❌ Demo failed at Step 4")
        return
    print(f"📌 Watermark before sending: {watermark_before_send}")

    # Step 5: Send message
    print("\n📋 Step 5: Send Message")
    print("-" * 30)
    message_id = send_message(MESSAGE)
    if not message_id:
        print("❌ Demo failed at Step 5")
        return

    # Step 6: Poll for bot reply
    print("\n📋 Step 6: Poll for Bot Reply")
    print("-" * 30)
    print("⏳ Waiting for bot to process and reply...")
    reply_data = poll_for_reply_with_watermark(
        watermark_before_send, max_attempts=15, interval=2
    )

    if reply_data:
        print("\n🎉 Demo completed successfully!")
        print("✅ Bot replied to your message")
    else:
        print("\n⚠️ Demo completed with timeout")
        print("ℹ️ Bot may still be processing your message")


def poll_for_reply_with_watermark(start_watermark, max_attempts=10, interval=2):
    """Poll for bot reply using specific watermark"""
    print(
        f"\n🔄 Polling for bot reply (max {max_attempts} attempts, {interval}s interval)..."
    )

    print(f"📌 Starting to poll from watermark: {start_watermark}")

    # Poll for new messages
    for attempt in range(max_attempts):
        print(f"\nAttempt {attempt + 1}/{max_attempts}...")
        time.sleep(interval)

        data = get_messages(start_watermark)
        if not data:
            continue

        activities = data.get("activities", [])
        new_watermark = data.get("watermark")

        # Check if we got new messages
        if len(activities) > 0:
            print(f"🎉 Bot replied! Found {len(activities)} new message(s)")

            # Find the latest bot reply (skip user messages and earlier bot messages)
            latest_bot_reply = None
            for activity in activities:
                sender = activity.get("from", {}).get("id", "unknown")
                if sender == "graphic-bot":
                    # Get the latest bot message
                    if latest_bot_reply is None or activity.get(
                        "timestamp", ""
                    ) > latest_bot_reply.get("timestamp", ""):
                        latest_bot_reply = activity

            if latest_bot_reply:
                text = latest_bot_reply.get("text", "")
                timestamp = latest_bot_reply.get("timestamp", "")
                print("🤖 Bot's reply to your question:")
                print(f"   {text}")
                print(f"   Time: {timestamp}")

            return data

        start_watermark = new_watermark

    print("⏰ No reply received within time limit")
    return None


if __name__ == "__main__":
    main()
