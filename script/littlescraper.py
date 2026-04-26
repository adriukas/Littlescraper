import requests
import json
import sys
import re
from datetime import datetime, timedelta, timezone

TOKEN = sys.argv[1] if len(sys.argv) > 1 else ""
CHANNEL_ID = sys.argv[2] if len(sys.argv) > 2 else ""


def get_messages(channel_id):
    try:
        headers = {'Authorization': TOKEN}
        url = f"https://discord.com/api/v9/channels/{channel_id}/messages?limit=100"
        response = requests.get(url, headers=headers)
        data = response.json()
        return data if isinstance(data, list) else []
    except Exception:
        return []


def parse_embed(msg, embed):
    extracted = {
        'type': 'bot_success',
        'user': msg['author']['username'],
        'item': embed.get('title', 'Vinted Item'),
        'text': embed.get('description', ''),
        'time': msg['timestamp'],
        'price': 0
    }

    if embed.get('fields'):
        field_details = []
        for field in embed['fields']:
            f_name = field['name'].lower()
            f_val = field['value']

            if any(x in f_name for x in ["user", "utilisateur"]):
                extracted['user'] = f_val

            if any(x in f_name for x in ["article", "item", "region"]):
                if extracted['item'] == 'Vinted Item' or not extracted['item']:
                    extracted['item'] = f_val

            if any(x in f_name for x in ["price", "prix", "value", "kaina"]):
                extracted['text'] = f"Price: {f_val}"
                price_match = re.search(r'(\d+(?:[\.,]\d+)?)', f_val)
                if price_match:
                    extracted['price'] = float(price_match.group(1).replace(',', '.'))

            field_details.append(f"{field['name']}: {f_val}")

        if not extracted['text'] and field_details:
            extracted['text'] = " | ".join(field_details)

    return extracted


def parse_messages(messages):
    results = []
    cutoff_time = datetime.now(timezone.utc) - timedelta(hours=500)

    for msg in messages:
        timestamp_str = msg['timestamp'].replace('Z', '+00:00')
        msg_time = datetime.fromisoformat(timestamp_str)

        if msg_time <= cutoff_time:
            continue

        content = msg.get('content', '')
        is_purchase = any(w in content.lower() for w in ["secured", "success", "bought", "purchased", "vinted"])
        has_link = "discord.gg" in content or "vinted" in content.lower()

        if msg.get('embeds'):
            for embed in msg['embeds']:
                results.append(parse_embed(msg, embed))
        elif content:
            item_type = 'bot_success' if (is_purchase or has_link) else 'user_chat'
            item_name = 'Item Secured' if (is_purchase or has_link) else 'Discussion'
            results.append({
                'type': item_type,
                'user': msg['author']['username'],
                'item': item_name,
                'text': content,
                'time': msg['timestamp'],
                'price': 0
            })

    return results


def main():
    messages = get_messages(CHANNEL_ID)
    results = parse_messages(messages)
    print(json.dumps(results))


if __name__ == '__main__':
    main()
