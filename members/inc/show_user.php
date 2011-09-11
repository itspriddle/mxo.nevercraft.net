<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $username; ?>'s Profile - The Nevercrafters</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--

-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<link href="http://mxo.nevercraft.net/css/v1.css" rel="stylesheet" type="text/css">
<link href="http://mxo.nevercraft.net/css/members.css" rel="stylesheet" type="text/css">
<link href="http://mxo.nevercraft.net/css/news.css" rel="stylesheet" type="text/css">
</head>
<body> 
<?php

	include_once("/home/nevercraft/www/subdomains/mxo/members/inc/show_bio.php");

?> 
<div id="members"> 
  <div class="body"> 
    <div class="top">&nbsp;</div> 
    <div class="header"> 
      <div class="topnav">Main | My Blog | <a href="<?php echo $PHP_SELF . "?n=3"; ?>">My Screenshots</a> | My Favorites</div> 
    </div> 
    <div class="content"> 
      <div class="left"> 
        <div class="avatar"> 
          <?php if ($avatar_found) { echo "<img src=\"http://users.mxo.nevercraft.net/~$username/images/$avatar_found\" border=\"0\" />"; } ?> 
        </div> 
        <div class="bio"> 
          <div class="bioleft"> 
            <div class="bioentry">&nbsp;Name:</div> 
            <div class="bioentry">&nbsp;Alias:</div> 
            <div class="bioentry">&nbsp;Age:</div> 
            <div class="bioentry">&nbsp;Location:</div> 
            <div class="bioentry">&nbsp;Email:</div> 
            <div class="bioentry">&nbsp;AIM:</div> 
          </div> 
          <div class="bioright"> 
            <div class="bioentry">&nbsp;<?php echo "$name"; ?></div> 
            <div class="bioentry">&nbsp;<?php echo "$mxoname"; ?></div> 
            <div class="bioentry">&nbsp;<?php echo "$age"; ?></div> 
            <div class="bioentry">&nbsp;<?php echo "$location"; ?></div> 
            <div class="bioentry">&nbsp;<a href="mailto:<?php echo "$email"; ?>"><?php echo "$email"; ?></a></div> 
            <div class="bioentry">&nbsp;<a href="aim:goim?screenname=<?php $aimlink = ereg_replace(" ", "", $aim); echo "$aimlink"; ?>&message=Follow+the+white+rabbit"><?php echo "$aim"; ?></a></div> 
          </div> 
          <div class="bioentrylng"> 
            <div class="padded_lr"><strong>URL: </strong><a href="<?php echo "$url"; ?>" target="_blank"><?php echo "$url"; ?></a></div> 
          </div> 
          <div class="bioentrylng"> 
            <div class="padded_lr"><strong>Interests: </strong><?php echo "$interests"; ?></div> 
          </div> 
          <div class="bioentrylng"> 
            <div class="padded_lr"><strong>Bio: </strong><?php echo "$biography"; ?></a></div> 
          </div> 
        </div> 
      </div> 
      <div class="right"> 
        <?php
		  	switch($n) {
				case "3": include("show_gal.php"); break;
			}
		  ?> 
      </div> 
    </div> 
    <div class="clearboth">&nbsp;</div> 
  </div> 
  <div class="bottom">&nbsp;</div> 
</div> 
</div> 
</body>
</html>
