import requests
import json
import sys

# Get arguments from Laravel
TOKEN = sys.argv[1] if len(sys.argv) > 1 else ""
CHANNEL_ID = sys.argv[2] if len(sys.argv) > 2 else ""

headers = {'Authorization': TOKEN}

def test_connection():
    # We try to get basic info about the channel
    url = f"https://discord.com/api/v9/channels/{CHANNEL_ID}"
    
    try:
        response = requests.get(url, headers=headers)
        
        if response.status_code == 200:
            data = response.json()
            return {
                "status": "success",
                "message": f"Connected to Discord! Channel name: #{data.get('name')}",
                "raw_data": data
            }
        elif response.status_code == 401:
            return {"status": "error", "message": "Invalid Token (401 Unauthorized)"}
        elif response.status_code == 403:
            return {"status": "error", "message": "Access Denied (403). Are you in this server?"}
        else:
            return {"status": "error", "message": f"Discord error {response.status_code}"}
            
    except Exception as e:
        return {"status": "error", "message": str(e)}

if __name__ == "__main__":
    result = test_connection()
    print(json.dumps(result))