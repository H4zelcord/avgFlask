import sys
import json
import os

try:
    from bot import create_twitter_client, create_cohere_client, generate_ai_tweet, post_tweet_v2
except ImportError as e:
    print(f"Error importing modules: {e}")
    print(json.dumps({"status": "error", "message": f"Module import error: {e}"}))
    sys.exit(1)

def main():
    try:
        if len(sys.argv) < 3:
            raise ValueError("Insufficient arguments provided. Usage: manual_tweet.py <theme> <adjective>")

        theme = sys.argv[1]
        adjective = sys.argv[2]

        # Debugging: Log received arguments
        print(f"Theme: {theme}, Adjective: {adjective}")

        # Create API clients
        client = create_twitter_client()
        co = create_cohere_client()

        # Generate tweet
        tweet_content = generate_ai_tweet(co, theme, adjective)
        print(f"Generated Tweet: {tweet_content}")  # Debugging: Log generated tweet

        # Post tweet using v2 API
        result = post_tweet_v2(client, tweet_content)
        print(json.dumps(result))  # Return result as JSON
    except Exception as e:
        print(f"Error: {e}")
        print(json.dumps({"status": "error", "message": str(e)}))

if __name__ == "__main__":
    main()
