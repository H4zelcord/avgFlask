import tweepy

def create_api():
    consumer_key = "your_consumer_key"
    consumer_secret = "your_consumer_secret"
    access_token = "your_access_token"
    access_token_secret = "your_access_token_secret"

    auth = tweepy.OAuth1UserHandler(consumer_key, consumer_secret, access_token, access_token_secret)
    return tweepy.API(auth)

def post_tweet(api, message):
    api.update_status(message)

if __name__ == "__main__":
    api = create_api()
    post_tweet(api, "Hello, world! This is my first bot tweet.")
