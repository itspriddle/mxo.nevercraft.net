<a href="?go=news">news</a> || <a href="?go=headlines">headlines</a> || <a href="?go=archives">arhcives</a> || <a href="?go=search">search</a>
<hr>
<?PHP
error_reporting (E_ALL ^ E_NOTICE);
if(!$go){ $go = $HTTP_GET_VARS['go']; }

if($go=="" or $go=="news"){
	include("show_news.php");
}elseif($go=="headlines"){
	$template = "Headlines";
	include("show_news.php");
}elseif($go=="archives"){
	include("show_archives.php");
}elseif($go=="search"){
	include("search.php");
}
?>