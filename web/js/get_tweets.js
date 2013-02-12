$(document).ready( function() {

  var blacklist1 = '' || "asdf";
  var blacklist2= '';
  var blacklist3 = '';

  var requestURL = 'http://dev-site.vbox.local/symfony/web/app_dev.php/svdm/tweet?1=' + blacklist1 + "&2=" + blacklist2 + "&3=" + blacklist3;

  console.log(requestURL);

  $.ajax({
    type: 'GET',
    url: requestURL,
    success: function(data) { insertTweet(data); },
    contentType: "application/json",
    dataType: 'json'
  });

  function insertTweet(tweet) {

    $('.tweet-message').text(tweet.message);

    if (tweet.media_url != "") {
      $('.tweet-media').html("<img src='" + tweet.media_url + "'>");
    }

    console.log(tweet.blacklist);
  }


});
