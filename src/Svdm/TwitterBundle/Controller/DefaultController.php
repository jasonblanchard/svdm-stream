<?php

namespace Svdm\TwitterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction() {

      $tweet_json = get_tweet_json('https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=about__blank');

      $tweets = make_twitter_objects($tweet_json);

      $timed_tweets = group_tweets($tweets);

      print_r($timed_tweets);

      return $this->render(
        'SvdmTwitterBundle:Default:index.html.twig',
        array('tweet' => $tweets[0]->message, 'timestamp' => $tweets[0]->timestamp)
      );
    }
}

class TwitterMessage {

  public $tid = '';

  public $message = '';

  public $timestamp = '';

  function __construct($tid, $message, $timestamp) {
    $this->tid = $tid;
    $this->message = $message;
    $this->timestamp = $timestamp;
  }
  
}

function get_tweet_json($url) {
  $json = file_get_contents($url);
  return json_decode($json, TRUE);
}

function make_twitter_objects($json) {

  $tweets = array();

  foreach ($json as $tweet) {
    $tid = $tweet['id_str'];
    $message = $tweet['text'];
    $timestamp = $tweet['created_at'];

    $tweet_obj = new TwitterMessage($tid, $message, $timestamp);

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
    if (($now - 60) < $tweet->timestamp) {
      $newest[] = $tweet;
    } else {
      $older[] = $tweet;
    }
  }

  $tweet_time_array["newest"] = $newest;
  $tweet_time_array["older"] = $older;

  return $tweet_time_array;
}

