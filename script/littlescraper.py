import requests
import json
import sys
import re
from datetime import datetime, timedelta, timezone

TOKEN = sys.argv[1] if len(sys.argv) > 1 else ""
CHANNEL_ID = sys.argv[2] if len(sys.argv) > 2 else ""

headers = {'Authorization': TOKEN}

def get_messages(channel_id):
    try:
        url = f"https://discord.com/api/v9/channels/{channel_id}/messages?limit=100"
        response = requests.get(url, headers=headers)
        return response.json()
    except Exception as e:
        return {"error": str(e)}

messages = get_messages(CHANNEL_ID)
results = []
cutoff_time = datetime.now(timezone.utc) - timedelta(hours=500)

if isinstance(messages, list):
    for msg in messages:
        timestamp_str = msg['timestamp'].replace('Z', '+00:00')
        msg_time = datetime.fromisoformat(timestamp_str)
        
        if msg_time > cutoff_time:
            content = msg.get('content', '')
            
            is_purchase_text = any(word in content.lower() for word in ["secured", "success", "bought", "purchased", "vinted"])
            has_link = "discord.gg" in content or "vinted" in content.lower()

            # APDOROJAME EMBEDS 
            if msg.get('embeds'):
                for embed in msg['embeds']:
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
                            
                            # Vartotojo identifikavimas
                            if any(x in f_name for x in ["user", "utilisateur"]):
                                extracted['user'] = f_val
                            
                            # Prekės/Regiono identifikavimas
                            if any(x in f_name for x in ["article", "item", "region"]):
                                if extracted['item'] == 'Vinted Item' or not extracted['item']:
                                    extracted['item'] = f_val
                            
                            # Kainos identifikavimas
                            if any(x in f_name for x in ["price", "prix", "value", "kaina"]):
                                extracted['text'] = f"Price: {f_val}"
                                price_match = re.search(r'(\d+(?:[\.,]\d+)?)', f_val)
                                if price_match:
                                    extracted['price'] = float(price_match.group(1).replace(',', '.'))
                            
                            field_details.append(f"{field['name']}: {f_val}")
                        
                        if not extracted['text'] and field_details:
                            extracted['text'] = " | ".join(field_details)

                    results.append(extracted)

            # 2. APDOROJAME PAPRASTĄ TEKSTĄ
            elif content:
                item_type = 'user_chat'
                item_name = 'Discussion'
                
                # Jei žinutė atrodo kaip pirkimas (Vinted nuoroda ar "Success")
                if is_purchase_text or has_link:
                    item_type = 'bot_success'
                    item_name = 'Item Secured'

                results.append({
                    'type': item_type,
                    'user': msg['author']['username'],
                    'item': item_name,
                    'text': content,
                    'time': msg['timestamp'],
                    'price': 0
                })

print(json.dumps(results))