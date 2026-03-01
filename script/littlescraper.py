import requests
import json
import sys

TOKEN = sys.argv[1] if len(sys.argv) > 1 else ""
CHANNEL_ID = sys.argv[2] if len(sys.argv) > 2 else ""

headers = {'Authorization': TOKEN}

def get_messages(channel_id):
    url = f"https://discord.com/api/v9/channels/{channel_id}/messages?limit=50"
    response = requests.get(url, headers=headers)
    return response.json()

messages = get_messages(CHANNEL_ID)
results = []

if isinstance(messages, list):
    for msg in messages:
        # checking if the message has an embed 
        if msg.get('embeds'):
            for embed in msg['embeds']:
                title = embed.get('title', '')
                if "success" in title.lower():
                    extracted = {
                        'username': msg.get('author', {}).get('username', 'Unknown'),
                        'timestamp': msg.get('timestamp', ''),

                        'content': title
                    }

                    if embed.get('fields'):
                        for field in embed['fields']:
                            name = field.get('name', '').lower()
                            value = field.get('value', '')
                            if "article" in name or "price" in name:
                                extracted['content'] += f" | {value}"
                    results.append(extracted)

print(json.dumps(results))