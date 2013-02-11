<?php

namespace Svdm\TwitterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction() {

      $request = $this->getRequest();

      $blacklist = array($request->query->get(1), $request->query->get(2), $request->query->get(3));

      #$tweet_json = get_tweet_json('https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&screen_name=JasonTwenties');

      $tweet_json = get_tweet_json_from_cache();

      $tweets = make_twitter_objects($tweet_json);

      $timed_tweets = group_tweets($tweets);

      print_r($timed_tweets);

      $final_tweet = false;

      while ($final_tweet == false) {
        if (count($timed_tweets['newest']) != 0) {
          $final_tweet = pick_tweet($timed_tweets['newest'], $blacklist);
        } else {
          $final_tweet = pick_tweet($timed_tweets['older'], $blacklist);
        }
      }

      return $this->render(
        'SvdmTwitterBundle:Default:index.html.twig',
        array('tweet' => $final_tweet->message, 'timestamp' => $final_tweet->timestamp, 'media_url' => $final_tweet->media_url)
      );
    }
}

class TwitterMessage {

  public $tid = '';

  public $message = '';

  public $timestamp = '';

  public $media_url = '';

  function __construct($tid, $message, $timestamp, $media_url) {
    $this->tid = $tid;
    $this->message = $message;
    $this->timestamp = $timestamp;
    $this->media_url = $media_url;
  }
  
}

function get_tweet_json($url) {
  $json = file_get_contents($url);

  $cache = fopen(dirname(__FILE__) . "/tweet_cache.js", 'r+');

  fwrite($cache, $json);

  fclose($cache);
  
  return json_decode($json, TRUE);
}

function get_tweet_json_from_cache() {
  
  $filename = dirname(__FILE__) . "/tweet_cache.js";

  $cache = fopen($filename, 'r+');

  $json = fread($cache, filesize($filename));

  return json_decode($json, TRUE);
}

function make_twitter_objects($json) {

  $tweets = array();

  foreach ($json as $tweet) {
    $tid = $tweet['id_str'];
    $message = $tweet['text'];
    $timestamp = $tweet['created_at'];

    if (isset($tweet['entities']['media'])) {
      $media_url = $tweet['entities']['media'][0]['media_url'];
    } else {
      $media_url = '';
    }

    $tweet_obj = new TwitterMessage($tid, $message, $timestamp, $media_url);

    $tweets[] = $tweet_obj;
  }

  return $tweets;
}

function group_tweets($tweet_array) {
  $now = time();
  $newest = array();
  $older = array();
  $tweet_time_array = array();

  foreach ($tweet_array as $tweet) {
    if (strtotime($tweet->timestamp) > ($now - 60)) {
      $newest[] = $tweet;
    } else {
      $older[] = $tweet;
    }
  }

  $tweet_time_array["newest"] = $newest;
  $tweet_time_array["older"] = $older;

  return $tweet_time_array;
}

function pick_tweet($tweets, $blacklist) {
  $random = rand(0, count($tweets) - 1);

  if (in_array($tweets[$random]->tid, $blacklist)) {
    return false;
  } else {
    return $tweets[$random];
  }
}
