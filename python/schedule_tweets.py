from bot import create_twitter_client, create_cohere_client, schedule_tweets
import generate_tweet
import post_tweet
import time
import sys
import random

def main():

    if len(sys.argv) >= 2:
        raise ValueError("Too many arguments provided. Usage: schedule_tweets.py")
    
    """""
    client = create_twitter_client()
    co =     create_cohere_client()
    """""
    theme_anime = ["Fullmetal Alchemist: Brotherhood", "Attack on Titan", "Death Note", "Cowboy Bebop",
                   "Steins;Gate", "Hunter x Hunter (2011)", "Neon Genesis Evangelion", "Code Geass: Lelouch of the Rebellion",
                   "Your Name", "Spirited Away", "One Piece", "Naruto: Shippuden", "Bleach", "Demon Slayer: Kimetsu no Yaiba",
                   "My Hero Academia", "Jujutsu Kaisen", "Dragon Ball Z", "Clannad: After Story", "Monster",
                   "Haikyuu!!", "Vinland Saga", "Princess Mononoke", "A Silent Voice", "Ghost in the Shell",
                   "Akira", "Parasyte: The Maxim", "Mob Psycho 100", "Re:Zero Starting Life in Another World",
                   "The Promised Neverland", "Sword Art Online", "Tokyo Ghoul", "Death Parade", "Psycho-Pass",
                   "Gurren Lagann", "Fate/Zero", "Made in Abyss", "Berserk", "Erased", "JoJo's Bizarre Adventure",
                   "One Punch Man", "Hellsing Ultimate", "Trigun", "Samurai Champloo", "March Comes in Like a Lion",
                   "Puella Magi Madoka Magica", "Fruits Basket", "Cowboy Bebop: The Movie", "Howl’s Moving Castle",
                   "Grave of the Fireflies", "Perfect Blue"]
    
    theme_politics = ["Climate Policy Gridlock", "Authoritarian Regime Crackdowns", "Universal Basic Income Debates",
                      "Global Trade Wars", "Refugee Crisis Escalation", "Wealth Tax Proposals", "Cryptocurrency Regulation Battles",
                      "Arctic Resource Conflicts", "AI Governance Frameworks", "Healthcare System Collapse", "Military-Industrial Complex Lobbying",
                      "Rural-Urban Divide Widens", "Global Debt Crisis", "Labor Union Resurgence", "Disinformation Legislation Struggles",
                      "Corporate Monopoly Power", "Food Inflation Protests", "Cyber Warfare Escalation", "Water Rights Disputes",
                      "Pension System Reforms", "Space Militarization Tensions", "Climate Reparations Demands", "Ethnic Cleansing Allegations",
                      "Election Security Threats", "Green New Deal Pushback", "Social Welfare Cuts", "Supply Chain Nationalization",
                      "Global Tax Evasion", "AI Job Disruption", "Housing Affordability Crisis", "Bioengineered Crop Bans",
                      "Arms Trade Expansion", "Post-Colonial Reparations", "Energy Price Volatility", "Political Assassination Plots",
                      "Mass Surveillance Ethics", "Tax Haven Crackdowns", "Youth Unemployment Surge", "Pandemic Treaty Negotiations",
                      "Deepfake Election Interference", "Indigenous Land Rights", "Nuclear Proliferation Fears", "Brexit Fallout Analysis",
                      "Global Recession Risks", "Critical Mineral Scarcity", "Feminist Policy Backlash", "Autocratic Tech Censorship",
                      "Anti-Corruption Protest Movements", "Neocolonial Exploitation Accusations", "AI Arms Race"]
    
    theme_sports = ["World Cup Controversies", "Doping Scandals Resurface", "Esports Prize Pool Records", "Real Madrid", "FC Barcelona",
                    "Injury Comeback Stories", "Sports Betting Legalization", "Olympic Host Boycotts", "NIL Deals Impact", "Extreme Sports Risks",
                    "Fan Violence Incidents", "Sports Media Rights", "Gender Pay Gaps", "Draft Lottery Fixes", "Sports Diplomacy Tensions",
                    "Fantasy League Addiction", "Athlete Activism Backlash", "Stadium Funding Debates", "Performance-Enhancing Tech",
                    "Transgender Athlete Bans", "College Sports Corruption", "Rivalry Game Traditions", "Sports Gambling Addiction",
                    "Legacy-Building Retirements", "Salary Cap Loopholes", "Athlete Brand Endorsements", "Climate Impact on Events",
                    "Concussion Protocol Failures", "Sports Franchise Relocations", "Youth Sports Burnout", "Olympic Doping Scandals",
                    "Team Dynasty Collapses", "Sponsorship Deal Terminations", "E-Sports Inclusion Debates", "Sports Anime Influence",
                    "Athlete Social Media Drama", "Referee Corruption Scandals", "Sports Science Breakthroughs", "Fan Token Investments",
                    "March Madness Upsets", "Sports Documentary Hype", "Player Lockout Threats", "World Record Pursuits", "Sports Equipment Innovation",
                    "Minor League Struggles", "Global Sports Boycotts", "Athlete NFT Craze", "Sports Nutrition Trends", "Broadcasting Rights Wars",
                    "Ultra-Marathon Endurance Feats"]
    
    theme_technologies = ["AI Ethics Dilemmas", "Quantum Computing Race", "Neural Interface Breakthroughs", "6G Network Development", "Metaverse Privacy Risks",
                          "Deepfake Detection Tools", "Autonomous Drone Delivery", "Blockchain Voting Systems", "CRISPR Cure Trials", "Smart City Surveillance",
                          "Robotic Surgery Advancements", "Cybersecurity AI Arms Race", "Brain-Computer Interface Trials", "NFT Market Collapse",
                          "Edge Computing Expansion", "Biohacking Health Trends", "Vertical Farming Tech", "Lab-Grown Meat Commercialization",
                          "Carbon Capture Innovations", "Space Tourism Safety", "mRNA Vaccine Evolution", "Autonomous Trucking Logistics", "Dark Web Takedowns",
                          "Green Hydrogen Production", "Wearable Health Monitors", "3D-Printed Organs Progress", "IoT Hacking Vulnerabilities",
                          "Augmented Reality Workplaces", "Digital Twin Simulations", "Battery Recycling Solutions", "Exascale Supercomputing Milestones",
                          "AI-Generated Art Debates", "Solar Geoengineering Risks", "Smart Home Hacks", "Cryptocurrency Mining Bans", "Drone Swarm Technology",
                          "Neural Network Bias", "Ransomware Attack Surges", "Nuclear Fusion Progress", "EV Charging Infrastructure", "Facial Recognition Bans",
                          "Robotics Labor Strikes", "Algorithmic Trading Dominance", "Hypersonic Missile Tests", "Satellite Internet Expansion", "AI-Powered Drug Discovery",
                          "E-Waste Recycling Crisis", "Telemedicine Privacy Concerns", "5G Health Conspiracies", "Self-Healing Materials Research"]
    
    theme_entertaintment = ["Streaming Profitability Struggles", "Superhero Movie Fatigue", "TikTok Talent Agencies", "Celebrity Cancel Culture", "AI Scriptwriting Controversy",
                            "Film Piracy Resurgence", "K-Pop Global Domination", "Virtual Influencer Deals", "Reboot Franchise Backlash", "Award Show Decline",
                            "Podcast Network Mergers", "Music Sampling Lawsuits", "Netflix Password Crackdown", "Box Office Flops", "Diversity Quota Debates",
                            "VR Concert Experiences", "Biopic Accuracy Criticism", "Animation Unionization Efforts", "Merchandising Revenue Booms", "Disney Remake Backlash",
                            "NFT Movie Tickets", "Talent Agency Monopolies", "Film Tax Incentives", "Broadway Post-Pandemic Recovery", "Celebrity PR Crises",
                            "Deepfake Actor Replacements", "Fanfiction Legal Battles", "Reality TV Exploitation", "Music Festival Cancellations", "Censorship in Streaming",
                            "Video Game Adaptations", "Hollywood Strike Threats", "Indie Film Funding", "Social Media Stardom", "TikTok Dance Copyrights", "Celebrity NFT Drops",
                            "Child Star Burnout", "Film Set Safety Scandals", "Music Catalog Buyouts", "AI Voice Cloning", "Book-to-Screen Adaptations", "Comic-Con Decline",
                            "Star Power Inflation", "Patreon Creator Economy", "Piracy vs. Accessibility", "YouTube Algorithm Changes", "Influencer Marketing Fraud", "Studio Tax Avoidance",
                            "Celebrity Podcast Boom", "Crowdfunded Film Projects"]
        



    theme = random.choice(["theme_anime", "theme_politics", "theme_sports", "theme_technologies", "theme_entertaintment"])


    match theme:
        case "theme_anime":
            theme = random.choice(theme_anime)
        case "theme_politics":
            theme = random.choice(theme_politics)
        case "theme_sports":
            theme = random.choice(theme_sports)
        case "theme_technologies":
            theme = random.choice(theme_technologies)
        case "theme_entertaintment":
            theme = random.choice(theme_entertaintment)
        case _:
            raise ValueError("Invalid theme provided. Choose from: anime, politics, sports, technologies, entertainment")

    print(f"Selected theme: {theme}")
    
    adjective = ["humorous", "sarcastic", "satirical", "witty", "playful", "whimsical", 
                "inspirational", "relatable", "casual", "upbeat", "reflective", "conversational", 
                "lighthearted", "optimistic", "encouraging", "nostalgic", "cheeky", "thoughtful",
                "warm", "breezy", "authentic", "genuine", "clever", "unpretentious", "charming", 
                "balanced", "lively", "down-to-earth", "engaging", "ironic"]
                

    random_adjective = random.choice(adjective)

    print(f"Selected adjective: {random_adjective}")

    generate_tweet.main(theme, random_adjective)

    """
    post_tweet.main(prompt)
    
    time.sleep(interval_minutes * 60)  # Wait for the specified interval in minutes
    
    schedule_tweets(new_theme, interval_minutes)
    
    """
if __name__ == "__main__":
    main()
