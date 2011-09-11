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
<?php require_once('/home/nevercraft/connections/mxo_connection.php'); ?><?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO auth_users (username, password, email) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_mxo_connection, $mxo_connection);
  $Result1 = mysql_query($insertSQL, $mxo_connection) or die(mysql_error());
  
  
  // Create appropriate directories and data files for the new user.
  
  $dir_str =  "/home/nevercraft/www/subdomains/mxo/members/users/~";
  $dir_str .= strtolower($_POST['username']);
  $dat_str = $dir_str . "/dat";
  $img_str = $dir_str . "/images";
  $userdir = @mkdir($dir_str, 0777);
  $uimgdir = @mkdir($img_str, 0777);
  $udatdir = @mkdir($dat_str, 0777);
  
 
  
/*	if ($index = fopen("$dir_str/index.php", "a")) {
	
		$writeme  = "Index page contents here.";
		
		$write = fwrite("$dir_str/index.php", $writeme); 
	
	}
	fclose($index);
	*/
	
	$username2 = strtolower($username);
	$data  = '<?php' . "\n";   
	$data .= '	$username = "' . "$username2" . '";' . "\n";
	$data .= '	include_once("/home/nevercraft/www/subdomains/mxo/members/inc/show_user.php");' . "\n";
	$data .= '?>';
	$file = "$dir_str/index.php";    
	if (!$file_handle = fopen($file,"a")) { echo "Cannot open file"; }   
	if (!fwrite($file_handle, $data)) { echo "Cannot write to file"; }   
	echo "You have successfully written $file. (the index file)<br><br>";    
	fclose($file_handle); 
	
	$data2 = "Bio\n";   
	$file2 = "$dir_str/dat/bio.txt";    
	if (!$file_handle2 = fopen($file2,"a")) { echo "Cannot open file"; }   
	if (!fwrite($file_handle2, $data2)) { echo "Cannot write to file"; }   
	echo "You have successfully written $file2 (the bio file)<br><br>";    
	fclose($file_handle2); 
	
	$data3 = "Order Deny,Allow\n";
	$data3 .= "Deny from all\n";
	$data3 .= "Allow from 127.0.0.1";   
	$htaccess = '.htaccess';
	$file3 = "$dir_str/dat/$htaccess";    
	if (!$file_handle3 = fopen($file3,"a")) { echo "Cannot open file"; }   
	if (!fwrite($file_handle3, $data3)) { echo "Cannot write to file"; }   
	echo "You have successfully written $file3 (access)";    
	fclose($file_handle3); 
	
	$data4 = "Options -Indexes\n";   
	$htaccess2 = '.htaccess';
	$file4 = "$dir_str/images/$htaccess2";    
	if (!$file_handle4 = fopen($file4,"a")) { echo "Cannot open file"; }   
	if (!fwrite($file_handle4, $data4)) { echo "Cannot write to file"; }   
	echo "You have successfully written $file4 (access)";    
	fclose($file_handle4); 


	



}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
Add User to Auth Users:<br>
  <br>
  Username:<br>
  <input name="username" type="text" id="username" maxlength="20"> 
  <br>
  <br>
  Password:<br>
  <input name="password" type="text" id="password" maxlength="20">
  <br>
  <br>
  Email:<br>
  <input name="email" type="text" id="email" maxlength="50">
  <br>
  <br>
  Access Type:<br>
  <select name="access" id="access">
    <option value="admin">Administrator</option>
    <option value="junior">Junior Admin</option>
    <option value="standard">Standard Member</option>
  </select>
  <br>
  <br>
  Crew Rank:<br>
  <select name="crew" id="crew">
    <option value="1">Rank 1</option>
    <option value="2">Rank 2</option>
    <option value="3">Rank 3</option>
    <option value="4">Rank 4</option>
    <option value="5">Rank 5</option>
  </select> 
  <br>
  <br>
  Captain:<br>
  <select name="captain" id="captain">
    <option value="Iver">R1: Iver</option>
    <option value="Musou">R2: Musou</option>
    <option value="Kathaya">R3: Kathaya</option>
  </select>
  <br>
  <br>
  <input type="submit" name="Submit" value="Submit">
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
