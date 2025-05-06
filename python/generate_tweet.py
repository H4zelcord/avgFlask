import sys
import json
import os

try:
    from python.bot import create_cohere_client, generate_ai_tweet
except ImportError as e:
    error_result = {"status": "error", "message": f"Module import error: {e}"}
    print(json.dumps(error_result))
    sys.exit(1)

def main(theme1=None, adjective1=None):
    try:
        # Handle command-line arguments if provided
        if len(sys.argv) >= 3:
            theme = sys.argv[1]
            adjective = sys.argv[2]
        elif theme1 is not None and adjective1 is not None:
            theme = theme1
            adjective = adjective1
        else:
            raise ValueError("Insufficient arguments provided. Usage: generate_tweet.py <theme> <adjective>")

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
    # Call main() without arguments; it will handle sys.argv internally
    main()
