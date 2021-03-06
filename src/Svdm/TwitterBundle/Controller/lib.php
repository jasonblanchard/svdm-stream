<?php

class TwitterMessage {

  public $tid = '';

  public $message = '';

  public $timestamp = '';

  public $media_url = '';

  public $profile_pic = '';

  public $cached = '';

  public $username = '';

  public $instagram = '';

  public $instagram_image = '';

  function __construct($tid, $message, $timestamp, $media_url, $profile_pic, $username, $instagram) {
    $this->tid = $tid;
    $this->message = $message;
    $this->timestamp = $timestamp;
    $this->media_url = $media_url;
    $this->profile_pic = $profile_pic;
    $this->username = $username;
    $this->instagram = $instagram;
  }
  
}

function get_tweet_json($url) {

  $headers = get_headers($url);

  if ($headers[0] != "HTTP/1.0 200 OK") {
    return FALSE;
  }

  $json = file_get_contents($url);

  $cache = fopen(dirname(__FILE__) . "/tweet_cache.js", 'r+');

  fwrite($cache, $json);

  fclose($cache);

  return json_decode($json, TRUE);
}

function get_tweet_json_from_cache() {
  
  $filename = dirname(__FILE__) . "/tweet_cache.js";

  $cache_file = fopen($filename, 'r+');

  $json = fread($cache_file, filesize($filename));

  return json_decode($json, TRUE);
}

function make_twitter_objects($json) {

  $tweets = array();

  foreach ($json['results'] as $tweet) {
    if (isset($tweet['id_str'])) {
      $tid = $tweet['id_str'];
      $message = $tweet['text'];
      $timestamp = $tweet['created_at'];

      if (isset($tweet['entities']['media'])) {
        $media_url = $tweet['entities']['media'][0]['media_url'];
      } else {
        $media_url = '';
      }

      $profile_pic = $tweet['profile_image_url'];
      $username = $tweet['from_user'];

      $instagram = "";

      if (isset($tweet['entities']['urls'][0]['expanded_url'])) {
        $url = $tweet['entities']['urls'][0]['expanded_url'];

        if (strncmp("http://instagr.am", $url, 17) == 0) {
          $instagram = substr_replace($url, 'www.', 7, 0);
        }
      }

      $tweet_obj = new TwitterMessage($tid, $message, $timestamp, $media_url, $profile_pic, $username, $instagram);

      $tweets[] = $tweet_obj;
    }
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
