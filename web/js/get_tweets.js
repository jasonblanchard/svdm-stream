$(document).ready( function() {

  var blacklist = ['','','']
  var requestURL;

  var initURL = "http://" + app_url + "/svdm/tweet";

  get_tweet(initURL);

  setInterval(function() { 
    $('.wrapper').fadeOut('fast');
    get_tweet(requestURL)}, 
    10000);

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

    console.log("Current tweet = " + tweet.tid + " at " + tweet.timestamp);
    console.log("Cache = " + tweet.cached);

    processTweet(tweet);

    $('.wrapper').fadeIn('fast');

    blacklist.pop();
    blacklist.unshift(tweet.tid);

    requestURL = initURL + "?1=" + blacklist[0] + "&2=" + blacklist[1] + "&3=" + blacklist[2];
  }

  function processTweet(tweet) {

    var profilePic = tweet.profile_pic.replace('normal','bigger');

    $('.tweet-media').html('');

    $('.tweet-message').text(tweet.message);

    $('.profile-pic').html("<img src='" + profilePic + "'>");
    $('.tweet-username').text("@" + tweet.username);

    if (tweet.media_url != "") {
      $('.tweet-media').html("<img src='" + tweet.media_url + "'>");
    }

    if (tweet.instagram_image != "") {
      $('.tweet-media').html("<img src='" + tweet.instagram_image + "'>");
    }

  }

});


