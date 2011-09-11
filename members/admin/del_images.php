<?php require_once('/home/nevercraft/connections/mxo_connection.php'); ?>
<?php
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "http://mxo.nevercraft.net/members/denied.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
/*function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if ((isset($_GET['username'])) && ($_GET['username'] != "")) {
  $deleteSQL = sprintf("DELETE FROM auth_users WHERE username=%s",
                       GetSQLValueString($_GET['username'], "text"));

  mysql_select_db($database_mxo_connection, $mxo_connection);
  $Result1 = mysql_query($deleteSQL, $mxo_connection) or die(mysql_error());
}*/
?>
<?
	$dir = "/home/nevercraft/www/subdomains/mxo/members/users/~" . $username;
	$dat_dir = "$dir/dat";
	$img_dir = "$dir/images";
	
	/*$del_user = @opendir($dir) or die("No such user or directory. [ del_user ]");
	$del_dat = @opendir($dat_dir);*/
	$del_img = @opendir($img_dir);
	
	
	/*while ($file = readdir($del_dat)) {
	
		if (($file != ".") && ($file != "..")) {
		
			@unlink ("$dat_dir/$file");
			
		}
  	}*/
	while ($file = readdir($del_img)) {
	
		if (($file != ".") && ($file != "..")) {
		
			@unlink("$img_dir/$file");
			
		}
  	}
	/*
	while ($file = readdir($del_user)) {
	
		if (($file != ".") && ($file != "..")) {
		
			@unlink("$dir/$file");
			
		}
	
	}
	
	//$dlim = rmdir("$dir/images");
	//$dldt = rmdir("$dir/dat");
	//$dldr = rmdir("$dir");

	if ((!$dlim) || (!$dldt) || (!$dldr)) {
	
		echo "Click <a href=\"#\">here</a> to delete the directories.";
	
	} else {
	
		if ($dlim) {
			echo "$img_dir deleted!\n";
		}
		
		if ($dldt) {
			echo "$dat_dir deleted!\n";
		}
		
		if ($dldr) {
			echo "$dir deleted!\n";
		}
	
	}*/
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

</body>
</html>
