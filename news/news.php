<div class="outline">
    <div class="news_outline">
      <?php
  	$template="news";
  	switch($_GET['feed']) {
	case "dev": $category="1";  break;
	case "news": $category="3"; break;
	case "musou": $category="2"; break;
	case "iver": $category="4"; break;
	default: $category="3"; break;
	}

  	include "show_news.php";
  ?>
    </div>
    <div id="secondary">
      <div class="top">&nbsp;</div>
      <div class="border">
        <div class="inner">
          <div class="title">
            <div class="padded_left">Active Newsfeeds </div>
          </div>
          <div class="body">
            <div class="padded"> &bull; <a href="?feed=main">Main News Feed</a><br>
&bull; <a href="?feed=dev">Development Feed</a><br>
&bull; <a href="?feed=musou">Musou's Diary</a><br>
&bull; <a href="?feed=iver">Iver's Diary</a></div>
          </div>
        </div>
      </div>
      <div class="bottom">&nbsp;</div>
      <div class="top">&nbsp;</div>
      <div class="border">
        <div class="inner">
          <div class="title">
            <div class="padded_left">Join Us </div>
          </div>
          <div class="body">
            <div class="padded">Click here to find out how to join us. <a href="?feed=iver"></a></div>
          </div>
        </div>
      </div>
      <div class="bottom">&nbsp;</div>
      <div class="top">&nbsp;</div>
      <div class="border">
        <div class="inner">
          <div class="title">
            <div class="padded_left">Live Events </div>
          </div>
          <div class="body">
            <div class="padded">There are currently no live events scheduled. <a href="?feed=iver"></a></div>
          </div>
        </div>
      </div>
      <div class="bottom">&nbsp;</div>
      <div class="top">&nbsp;</div>
      <div class="border">
        <div class="inner">
          <div class="title">
            <div class="padded_left">Member Spotlight </div>
          </div>
          <div class="body">
            <div class="padded">There is no one currently in the spotlight. Click here to nominate someone for our Member Spotlight <a href="?feed=iver"></a></div>
          </div>
        </div>
      </div>
      <div class="bottom">&nbsp;</div>
      <div class="top">&nbsp;</div>
      <div class="border">
        <div class="inner">
          <div class="title">
            <div class="padded_left">Current Crews </div>
          </div>
          <div class="body">
            <div class="padded">Rank 1: The Northpark Allstars (C. Iver) <br>
              Rank 2: The Northpark Gangsters (C. Musou) <br>
              Rank 3: The Northpark Assassins (C. Kathaya) </div>
          </div>
        </div>
      </div>
      <div class="bottom">&nbsp;</div>
    </div>
  </div>

