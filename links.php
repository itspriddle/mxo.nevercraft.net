<?php 
	include_once("/home/nevercraft/www/subdomains/mxo/inc/header.inc");
	$template = "news";
   	switch($feed) { 
   		case "main": $category = "3"; break; 
		case "dev": $category = "1"; break;
		case "musou": $category = "2"; break;
		case "iver": $category = "4"; break;
		
		default: $category = "3"; break;
	}
?>
<div id="content">
  <div class="top"> </div>
  <div class="body">
    <div class="title_bg">
      <div class="title">Links </div>
    </div>
    <div class="story">
	Nothing yet.</div>
    <div class="footer"></div>
  </div>
  <div class="bottom"> </div>
</div>
		
	
<?php
	include_once("/home/nevercraft/www/subdomains/mxo/inc/secondary.inc");
	include_once("/home/nevercraft/www/subdomains/mxo/inc/footer.inc");
?> 