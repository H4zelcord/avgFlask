import tweepy
import os
import cohere
import time
from dotenv import load_dotenv

# Load environment variables from .env file
load_dotenv()

def create_twitter_client():
    """
    Create a Tweepy Client for Twitter API v2.
    """
    consumer_key = os.getenv("CONSUMER_KEY")
    consumer_secret = os.getenv("CONSUMER_SECRET")
    access_token = os.getenv("ACCESS_TOKEN")
    access_token_secret = os.getenv("ACCESS_TOKEN_SECRET")
    bearer_token = os.getenv("BEARER_TOKEN")

    if not all([consumer_key, consumer_secret, access_token, access_token_secret, bearer_token]):
        raise ValueError("Twitter API keys, tokens, or Bearer Token are not properly set in the .env file.")

    return tweepy.Client(
        bearer_token=bearer_token,
        consumer_key=consumer_key,
        consumer_secret=consumer_secret,
        access_token=access_token,
        access_token_secret=access_token_secret
    )

def create_cohere_client():
    """
    Create a Cohere client for AI text generation.
    """
    cohere_api_key = os.getenv("COHERE_API_KEY")
    if not cohere_api_key:
        raise ValueError("Cohere API key is not set in the .env file.")
    return cohere.Client(cohere_api_key)

def generate_ai_tweet(co, theme, adjective):
    """
    Generate an AI tweet based on the given theme and adjective.
    """
    prompt = (
        f"Answer ONLY a tweet that doesn't exceed 3 lines. "
        f"The theme should be {theme} and stuff related to it. "
        f"Write it in a {adjective} way. "
        f"DO NOT include hashtags, links, or mentions. "
        f"Refrain from using any offensive or controversial language, unless it seems appropiate. "
        f"Write it like a human would, and don't worry about typos or slang."
    )
    response = co.generate(
        model='command-xlarge',
        prompt=prompt,
        max_tokens=100,
        temperature=0.7,
        stop_sequences=["."],
        presence_penalty=0.5,
        frequency_penalty=0.5
    )
    return response.generations[0].text.strip()

def generate_direct_tweet(co, direct_prompt):
    response = co.generate(
        model='command-xlarge',
        prompt=direct_prompt,
        max_tokens=200,
        temperature=0.7,
        stop_sequences=["."],
        presence_penalty=0.5,
        frequency_penalty=0.5
    )
    return response.generations[0].text.strip()







def post_tweet_v2(client, message):
    """
    Post a tweet using the Twitter API v2 with Tweepy's Client class.
    """
    try:
        response = client.create_tweet(text=message)  # Use Tweepy's Client method for posting tweets
        return {"status": "success", "message": "Tweet posted successfully", "data": response.data}
    except Exception as e:
        return {"status": "error", "message": str(e)}
