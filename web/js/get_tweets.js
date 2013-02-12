$(document).ready( function() {

  var blacklist = ['','','']
  var requestURL;

  var initURL = 'http://dev-site.vbox.local/symfony/web/app_dev.php/svdm/tweet'

  get_tweet(initURL);

  //setInterval(function() { get_tweet(requestURL)}, 1000);

  function get_tweet(url) {
    $.ajax({
      type: 'GET',
      url: url,
      success: function(data) { insertTweet(data); },
      contentType: "application/json",
      dataType: 'json'
    });

    console.log(url);
  }


  function insertTweet(tweet) {

    $('.tweet-media').html('');

    $('.tweet-message').text(tweet.message);

    if (tweet.media_url != "") {
      $('.tweet-media').html("<img src='" + tweet.media_url + "'>");
    }

    blacklist[0] = tweet.tid;

    requestURL = 'http://dev-site.vbox.local/symfony/web/app_dev.php/svdm/tweet?1=' + blacklist[0] + "&2=" + blacklist[1] + "&3=" + blacklist[2];
  }
});


