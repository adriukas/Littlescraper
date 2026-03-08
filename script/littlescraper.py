#TODO: kai scrapinu purchases, tik pirmas psl rodo pilna kaina, reik sutvarkyt sita dalyka
#chathistory reik prideti carda
#jei scrapinu tai duomenys uzsiraso daug kartu, reik patikrinti ar jau yra ir tada prirasyti
import requests
import json
import sys
import re
from datetime import datetime, date, timedelta, timezone

TOKEN = sys.argv[1] if len(sys.argv) > 1 else ""
CHANNEL_ID = sys.argv[2] if len(sys.argv) > 2 else ""

headers = {'Authorization': TOKEN}

def get_messages(channel_id):
    url = f"https://discord.com/api/v9/channels/{channel_id}/messages?limit=100"
    response = requests.get(url, headers=headers)
    return response.json()

messages = get_messages(CHANNEL_ID)
results = []
cutoff_time = datetime.now(timezone.utc) - timedelta(hours=24)

if isinstance(messages, list):
    for msg in messages:
        msg_time = datetime.fromisoformat(msg['timestamp'])        
        
        if msg_time > cutoff_time:        
            # Bot Embeds 
            if msg.get('embeds'):
                for embed in msg['embeds']:
                    if "success" in embed.get('title', '').lower() or "secured" in embed.get('title', '').lower():
                        extracted = {
                            'type': 'bot_success',
                            'user': msg['author']['username'],
                            'item': 'Success Box',
                            'text': embed.get('description', 'No details'),
                            'time': msg['timestamp']
                        }
                    
                    if embed.get('fields'):
                        for field in embed['fields']:
                            if "article" in field['name'].lower() or "utilisateur" in field['name'].lower(): extracted['item'] = field['value']
                            if "price" in field['name'].lower() or "prix" in field['name'].lower(): 
                                extracted['text'] = f"Price: {field['value']}"
                               
                    results.append(extracted)
        
        #Member messages
            elif msg.get('content'):
                results.append({
                    'type': 'user_chat',
                    'user': msg['author']['username'],
                    'item': 'Discussion',
                    'text': msg['content'],
                    'time': msg['timestamp']
                })

print(json.dumps(results))