<?php
	include_once("inc/header.inc");
	$template = "news";
   	switch($_GET['feed']) {
   		case "main": $category = "3"; break;
		case "dev": $category = "1"; break;
		case "musou": $category = "2"; break;
		case "iver": $category = "4"; break;

		default: $category = "3"; break;
	}

	echo "<div id=\"news\">\n";
	include "news/show_news.php";
	echo "</div>\n";
	include "inc/secondary.inc";



	include_once("inc/footer.inc");
?>
