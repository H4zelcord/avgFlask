import sys
import json
import os

try:
    from bot import create_cohere_client, generate_direct_tweet
except ImportError as e:
    error_result = {"status": "error", "message": f"Module import error: {e}"}
    print(json.dumps(error_result))
    sys.exit(1)

def main():
    try:
        if len(sys.argv) < 2:
            raise ValueError("Insufficient arguments provided. Usage: direct_tweet.py <full_prompt>")

        # Combine all arguments into a single string for the full prompt
        full_prompt = " ".join(sys.argv[1:])

        # Create Cohere client
        co = create_cohere_client()

        # Generate tweet based on the full prompt
        tweet_content = generate_direct_tweet(co, full_prompt)
        result = {"status": "success", "message": tweet_content}
        print(json.dumps(result))  # Ensure only JSON is printed
    except Exception as e:
        error_result = {"status": "error", "message": str(e)}
        print(json.dumps(error_result))

if __name__ == "__main__":
    main()
