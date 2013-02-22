What is this?
============
This Symfony web application was designed to dispay a Twitter stream with image media during a live event.

The application uses the Twitter Search API to pull images from various image services (including Instagram). This particular version is listening for Tweets @prtyplnt or containing the #svdm13 hashtag, but it could be modified to pull any search criteria. It has simple logic to prefer newer Tweets and to ensure Tweets are not repeated at too frequent intervals.

How does it work?
================
The ``tweetAction()`` method calls the Twitter search api with some search criteria. Functions in ``lib.php`` process the JSON object and turn each tweet into PHP ojects with all relavent information.

``lib.php`` also puts each tweet into two buckets: messages from the last 60 seconds, and everything else.

``tweetAction()`` then picks a random tweet from the recent messages bucket. If that's empty, it chooses one randomly from all the tweets. The app also keeps track of the last 3 tweets in a blacklist to make sure they do not pop up too frequently.

Once ``tweetAction()`` chooses a final tweet, it returns a new JSON object. The ``requestAction()`` method polls the ``tweetAction()`` page every 10 seconds to get a new tweet and parses the JSON object with jQuery.

