<?php

namespace Svdm\TwitterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/simple_html_dom.php');

ini_set('user_agent', 'svdm.blanktech.net');

class DefaultController extends Controller
{
    public function tweetAction() {

      $request = $this->getRequest();

      $blacklist = array($request->query->get(1), $request->query->get(2), $request->query->get(3));

      $tweet_json = get_tweet_json('http://search.twitter.com/search.json?q=%23svdm13%20OR%20%40prtyplnt&rpp=100&result_type=recent&include_entities=true');

      $cached = FALSE;

      if ($tweet_json == FALSE) {
        $tweet_json = get_tweet_json_from_cache();
        $cached = TRUE;
      }

      $tweets = make_twitter_objects($tweet_json);

      $timed_tweets = group_tweets($tweets);

      $final_tweet = false;

      if (count($timed_tweets['newest']) != 0) {
        $final_tweet = pick_tweet($timed_tweets['newest'], $blacklist);
      }

      if ($final_tweet == false) {

        while ($final_tweet == false) {
          $final_tweet = pick_tweet($timed_tweets['older'], $blacklist);
        }
      }

      $final_tweet->cached = $cached;
      $final_tweet->instagram_image = '';

      if ($final_tweet->instagram != '') {
        $html = file_get_html($final_tweet->instagram);

        $image = $html->find('img.photo');
        
        $final_tweet->instagram_image = $image[0]->src;

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

      return $this->render(
        'SvdmTwitterBundle:Default:index.html.twig'
      );
    }

}
