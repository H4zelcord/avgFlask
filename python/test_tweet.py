from bot import create_twitter_client, post_tweet_v2

def main():
    client = create_twitter_client()
    message = "Hello, world! This is a test tweet from the X API v2."
    result = post_tweet_v2(client, message)
    print(result)

if __name__ == "__main__":
    main()
