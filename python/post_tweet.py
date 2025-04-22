import sys
import json
import os

try:
    from bot import create_twitter_client, post_tweet_v2
except ImportError as e:
    error_result = {"status": "error", "message": f"Module import error: {e}"}
    print(json.dumps(error_result))
    sys.exit(1)

def main():
    try:
        if len(sys.argv) < 2:
            raise ValueError("Insufficient arguments provided. Usage: post_tweet.py <tweet_content>")

        tweet_content = sys.argv[1]

        # Create Twitter client
        client = create_twitter_client()

        # Post tweet using v2 API
        result = post_tweet_v2(client, tweet_content)
        print(json.dumps(result))  # Ensure only JSON is printed
    except Exception as e:
        error_result = {"status": "error", "message": str(e)}
        print(json.dumps(error_result))

if __name__ == "__main__":
    main()
