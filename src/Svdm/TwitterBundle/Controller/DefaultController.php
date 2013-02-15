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

      if ($final_tweet->instagram != '') {
        #$instagram_page = file_get_contents($final_tweet->instagram);
        #var_dump($instagram_page);

        $document = new DomDocument();

        $file = file_get_contents($final_tweet->instagram);

        $document->loadHTMLFile($file);
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
