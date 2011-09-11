<?php
	session_start();
	$profile_loc = "/home/nevercraft/www/subdomains/mxo/members/users/~";
	
	$user = strtolower($_SESSION['MM_Username']);
	
	$bio_str = "$profile_loc$user/dat/bio.txt";
	
	$form_block = "
	<h3>Edit Your Profile</h3><br>
	This part's pretty obvious.  Fill out the form and update your bio.<br>
	<br>
	>> <a href=\"$PHP_SELF?n=2&u=avatar\">Upload an Avatar</a><br>
	<br>
	<form name=\"form1\" method=\"post\" action=\"$PHP_SELF?n=1\">
	  Name:<br>
	  <input name=\"name\" type=\"text\" id=\"name\" value=\"$name\">
	  <br>
	  <br>
	  MXO Character Name:<br>
	  <input name=\"mxoname\" type=\"text\" id=\"mxoname\" value=\"$mxoname\">
	  <br>
	  <br>
	  Age:<br>
	  <select name=\"age\" id=\"age\">
	  	<option value=\"$age\" selected>$age</selected>
	";
	$i = 13;
	while ($i <= 99) {
		
		$form_block .= "<option value=\"$i\">$i</option>\n";
		$i++;
	}
	$form_interests = stripslashes($interests);
	$form_interests = preg_replace("'<br />'", "", $form_interests);
	$form_biography = stripslashes($biography);
	$form_biography = preg_replace("'<br />'", "", $form_biography);
	$form_block .= "
	  </select> 
	  <br>
	  <br>
	  Location:<br>
	  <input name=\"location\" type=\"text\" id=\"location\" value=\"$location\">
	  <br>
	  <br>
	  Email:<br>
	  <input name=\"email\" type=\"text\" id=\"email\" value=\"$email\">
	  <br>
	  <br>
	  AIM:<br>
	  <input name=\"aim\" type=\"text\" id=\"aim\" maxlength=\"16\" value=\"$aim\">
	  <br>
	  <br>
	  Personal URL:<br>
	  <input name=\"url\" type=\"text\" id=\"url\" value=\"$url\">
	  <br>
	  <br>
	  Interests:<br>
	  <textarea name=\"interests\" id=\"interests\">$form_interests</textarea>
	  <br>
	  <br>
	  Detailed Biography:
	  <br>
	  <textarea name=\"biography\" id=\"biography\">$form_biography</textarea>
	  <br>
	  <br>
	  <input type=\"hidden\" name=\"editme\" value=\"yes\">
	  <input type=\"submit\" name=\"Submit\" value=\"Submit\">
	</form>
	";

	
	
	if ($editme == "yes")   { // If they filled out the form and clicked submit
		if ($b = fopen($bio_str, "w")) {
		
			$postbio = $_POST['biography'];
			$postbio = nl2br($postbio);
			$postint = $_POST['interests'];
			$postint = nl2br($postint);
			

		
			$writeme  = $_POST['name'] . "|||\n";
			$writeme .= $_POST['mxoname'] . "|||\n";
			$writeme .= $_POST['age'] . "|||\n";
			$writeme .= $_POST['location'] . "|||\n";
			$writeme .= $_POST['email'] . "|||\n";
			$writeme .= $_POST['aim'] . "|||\n";
			$writeme .= $_POST['url'] . "|||\n";
			$writeme .= $postint . "|||\n";
			$writeme .= $postbio . "|||\n";
			$edit = fwrite($b, $writeme); 
			
			echo "You've successfully edited your bio.  Click <a href=\"index.php\">here</a> to continue.<br><br>Changes may not take effect untill you click the above link or refresh your browser window.";
			
		
		} else {
			echo "Couldn't write to file.";
		}
	} else {
		echo $form_block;
	}



?>
