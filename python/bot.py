import tweepy
import os
import cohere
from dotenv import load_dotenv

# Load environment variables from .env file
load_dotenv()

def create_twitter_api():
    consumer_key = os.getenv("CONSUMER_KEY")
    consumer_secret = os.getenv("CONSUMER_SECRET")
    access_token = os.getenv("ACCESS_TOKEN")
    access_token_secret = os.getenv("ACCESS_TOKEN_SECRET")

    if not all([consumer_key, consumer_secret, access_token, access_token_secret]):
        raise ValueError("Twitter API keys and tokens are not properly set in the .env file.")

    auth = tweepy.OAuth1UserHandler(consumer_key, consumer_secret, access_token, access_token_secret)
    return tweepy.API(auth)

def create_cohere_client():
    cohere_api_key = os.getenv("COHERE_API_KEY")
    if not cohere_api_key:
        raise ValueError("Cohere API key is not set in the .env file.")
    return cohere.Client(cohere_api_key)

def generate_ai_tweet(co, prompt):
    response = co.generate(
        model='command-nightly',
        prompt=prompt,
        max_tokens=50,
        temperature=0.7,
        stop_sequences=["."]
    )
    return response.generations[0].text.strip()

