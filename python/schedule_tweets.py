from bot import create_twitter_client, create_cohere_client, schedule_tweets

def main():
    client = create_twitter_client()
    co = create_cohere_client()
    theme = "technology"
    adjective = "creative"
    interval_minutes = 30  # Post every 30 minutes
    schedule_tweets(client, co, theme, adjective, interval_minutes)

if __name__ == "__main__":
    main()
