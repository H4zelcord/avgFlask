import sys
import json
import os

try:
    from bot import create_cohere_client, generate_ai_tweet
except ImportError as e:
    error_result = {"status": "error", "message": f"Module import error: {e}"}
    print(json.dumps(error_result))
    sys.exit(1)

def main(theme1, adjective1):
    try:
        if len(sys.argv) < 3:
            raise ValueError("Insufficient arguments provided. Usage: generate_tweet.py <theme> <adjective>")

        theme = sys.argv[1]
        adjective = sys.argv[2]


        if theme1 is not None:
            theme = theme1
        if adjective1 is not None:
            adjective = adjective1

            
        # Create Cohere client
        co = create_cohere_client()

        # Generate tweet
        tweet_content = generate_ai_tweet(co, theme, adjective)
        result = {"status": "success", "message": tweet_content}
        print(json.dumps(result))  # Ensure only JSON is printed
    except Exception as e:
        error_result = {"status": "error", "message": str(e)}
        print(json.dumps(error_result))

if __name__ == "__main__":
    main()
