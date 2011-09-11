<?php require_once('/home/nevercraft/connections/mxo_connection.php'); ?>
<?php
// *** Validate request to login to this site.
session_start();

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($accesscheck)) {
  $GLOBALS['PrevUrl'] = $accesscheck;
  session_register('PrevUrl');
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "http://mxo.nevercraft.net/members/index.php";
  $MM_redirectLoginFailed = "http://mxo.nevercraft.net/members/login.php?l=failed";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_mxo_connection, $mxo_connection);
  
  $LoginRS__query=sprintf("SELECT username, password FROM auth_users WHERE username='%s' AND password='%s'",
    get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password)); 
   
  $LoginRS = mysql_query($LoginRS__query, $mxo_connection) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $GLOBALS['MM_Username'] = $loginUsername;
    $GLOBALS['MM_UserGroup'] = $loginStrGroup;	      

    //register the session variables
    session_register("MM_Username");
    session_register("MM_UserGroup");

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<?php include_once("/home/nevercraft/www/subdomains/mxo/inc/header.inc"); ?>

<div id="content">
  <div class="top"> </div>
  <div class="body">
    <div class="title_bg">
      <div class="title">Login MNFRM.NEVERCRAFT</div>
    </div>
    <div class="story">
	Enter your username and password below to enter the mainframe.
      <div id="login">
        <form name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
          <div class="heading">Username:<br>
            <input name="username" type="text" id="username" maxlength="20">
          </div>
          <br>
          <div class="heading">Password:<br>
            <input name="password" type="password" id="password" maxlength="20">
          </div>
          <br>
          <br>
          <input name="Submit" type="submit" id="Submit" value="Login">
        </form>
      </div>
    </div>
    <div class="footer"></div>
  </div>
  <div class="bottom"> </div>
</div>
<?php include_once("/home/nevercraft/www/subdomains/mxo/inc/footer.inc"); ?>
