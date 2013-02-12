<?php

namespace Svdm\TwitterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

require_once(dirname(__FILE__) . '/lib.php');

class DefaultController extends Controller
{
    public function tweetAction() {

      $request = $this->getRequest();

      $blacklist = array($request->query->get(1), $request->query->get(2), $request->query->get(3));

      #$tweet_json = get_tweet_json('https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&screen_name=JasonTwenties');

      $tweet_json = get_tweet_json_from_cache();

      $tweets = make_twitter_objects($tweet_json);

      $timed_tweets = group_tweets($tweets);

      $final_tweet = false;

      while ($final_tweet == false) {
        if (count($timed_tweets['newest']) != 0) {
          $final_tweet = pick_tweet($timed_tweets['newest'], $blacklist);
        } else {
          $final_tweet = pick_tweet($timed_tweets['older'], $blacklist);
        }
      }

      $response = new Response(json_encode($final_tweet));
      $response->headers->set('Content-Type', 'application/json');

      return $response;

      /*

      return $this->render(
        'SvdmTwitterBundle:Default:index.html.twig',
        array('tweet' => $final_tweet->message, 'timestamp' => $final_tweet->timestamp, 'media_url' => $final_tweet->media_url)
      );

      */
    }

    public function requestAction() {

      $request = $this->getRequest();

      $blacklist = array($request->query->get(1), $request->query->get(2), $request->query->get(3));

      return $this->render(
        'SvdmTwitterBundle:Default:index.html.twig',
        array('blacklist1' => $blacklist[0], 'blacklist2' => $blacklist[1], 'blacklist3' => $blacklist[2])
      );
    }

}
