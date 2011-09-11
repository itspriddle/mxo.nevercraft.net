<?php
//initialize the session
session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  session_unregister('MM_Username');
  session_unregister('MM_UserGroup');

  $logoutGoTo = "goodbye.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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

$MM_restrictGoTo = "login.php";
// if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
//   $MM_qsChar = "?";
//   $MM_referrer = $_SERVER['PHP_SELF'];
//   if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
//   if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
//   $MM_referrer .= "?" . $QUERY_STRING;
//   $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
//   header("Location: ". $MM_restrictGoTo);
//   exit;
// }
?>
<?php
	$username = strtolower($_SESSION['MM_Username']);
 include("members/inc/show_bio.php");

?>
<?php include_once("../inc/header.inc"); ?>
<div id="members">
  <div class="body">
    <div class="top">&nbsp;</div>
    <div class="header">
      <div class="topnav"><a href="index.php">Main</a> | <s>My Blog</s> | <a href="<?php echo $PHP_SELF ."?n=3"; ?>">View Screenshots</a> | <a href="<?php echo $PHP_SELF ."?n=4"; ?>">Delete Screenshots</a> | <s>My Favorites</s> | <a href="<?php echo $logoutAction ?>">Logout</a></div>
    </div>
    <div class="content">
      <div class="left">
        <div class="avatar"><?php if ($avatar_found) { echo "<img src=\"http://users.mxo.nevercraft.net/~$username/images/$avatar_found\" border=\"0\" />"; } ?></div>
        <div class="bio">
		<div class="bioentrylng"><div class="padded_lr"> >> <a href="<?php echo $PHP_SELF ."?n=2&u=screen"; ?>">Upload a Screenshot</a> </div></div>
		<div class="bioentrylng"><div class="padded_lr"> >> <a href="<?php echo $PHP_SELF; ?>?n=1">Edit Bio</a> </div></div>
		<div class="bioentrylng"><div class="padded_lr"> >> <a href="<?php echo "http://users.mxo.nevercraft.net/~$username"; ?>" target="_blank">View Homepage</a> </div></div>
		<div class="hdiv6px"></div>
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
		  <div class="clear">&nbsp;</div>
        </div>
      </div>
      <div class="right">
        <div class="title">Welcome <?php echo "$username"; ?>
        </div>
		The members area is still heavily under construction.  Email <a href="mailto:Jpriddle@gmail.com">JPriddle@gmail.com</a> if you experience _huge_ problems.<br><br>
		<hr align="right" width="430" noshade style="position: relative; left: -18px;">
		<br>
		<?php

		switch($n) { case "4": include("inc/del_imgs.php"); break; case "3": include("inc/show_gal.php"); break; case "1": include("inc/edit_bio.php"); break; case "2": include("inc/upload_image.php"); break; default: $category = "5"; $template = "news"; echo "<h4>Members Newsfeed</h4>\n<div id=\"news\">\n"; include("../news/show_news.php"); echo "</div>\n"; break;} ?><br>
		<br>
      </div>
      <div class="clear">&nbsp;</div>
    </div>
    <div class="bottom">&nbsp;</div>
  </div>
</div>
<?php include_once("../inc/footer.inc"); ?>
