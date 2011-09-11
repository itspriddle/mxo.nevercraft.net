<?

// Image Gallery Script


// Find the folder associated with the ID
$dir_name="/home/nevercraft/www/subdomains/mxo/members/users/~$username/images";

// Open the folder
$dir = opendir($dir_name);


// Create an array called images
$images = array();

// While there are files in the directory...
while ($file_name = readdir($dir)) {

	// If the file name isn't . or .. then
	if (($file_name != ".") && ($file_name != "..") && (substr($file_name, 0, 3) != "tn_") && (substr($file_name, 0, 6) != "avatar")) {
	
		// Add the file name to images array
		array_push($images, "$file_name");
	}
}


// Close the directory
closedir($dir);

sort($images);

		
if($dodelete) {
	$num2del = count($deleteme);
	$i = 0;
	for ($i = 0; $i <= $num2del; $i++) {
			@unlink("$dir_name/$deleteme[$i]");
			@unlink("$dir_name/tn_$deleteme[$i]");
			$msg = "Images successfully deleted!";
	}
} else {
	
	// For loop to generate images
	for ($i=0; ($i * 3) < count($images); $i++) { // Start $i at 0, loop, add 1 to $i
	/* 	The layout we want is:
		0  1  2
		3  4  5
		6  7  8 (etc)
	
		To accomplish this, we set $i = 0, and multiply by 3.
		Each for loop creates one table row.
		Theoretically, you should be able to use any number of columns
		as long as you multiply $i by the number of columns and follow
		the pattern.
	
		So...
		$i = 0	// Or the first table row
		($i * 3) = 0	($i * 3) + 1 = 1	($i * 3) + 2 = 2
		(0 * 3)  = 0	(0 * 3) + 1  = 1	(0 * 3) + 2 = 2
	
		$i = 1 // The second table row
		($i * 3) = 3	($i * 3) + 1 = 4	($i * 3) + 2 = 5
		(1 * 3)  = 3	(1 * 3) + 1  = 4	(1 * 3) + 2 = 5
	
		Etc...
	
		That long explanation is for Tony Montemorano. */
		
	
		$a = $i * 3;
		$b = ($i * 3) + 1;
		$c = ($i * 3) + 2;
	
	
		// Set individual variables to array elements
		$image_a = $images["$a"]; // Referes to the ($i * 3) number image in the array
		$image_b = $images["$b"];
		$image_c = $images["$c"];	
	 
		$form_del_a = "<input type=\"checkbox\" name=\"deleteme[]\" value=\"$images[$a]\">Delete";
		$form_del_b = "<input type=\"checkbox\" name=\"deleteme[]\" value=\"$images[$b]\">Delete";
		$form_del_c = "<input type=\"checkbox\" name=\"deleteme[]\" value=\"$images[$c]\">Delete";
	 
		/*	We only an image tag printed if there is an image for the cell it should be in.
			If there isn't one, stop the <img> tag from being generated.  */
		if ($image_a != "") {	// Check image A for data
			$show_gal_a .= "<div class=\"thumb\"><a href=\"http://users.mxo.nevercraft.net/~$username/images/$images[$a]\" target=\"_blank\"><img src=\"http://users.mxo.nevercraft.net/~$username/images/tn_$images[$a]\"></a>$form_del_a</div><br>";
		} else {
			$show_gal_a .="<div class=\"thumb\"></div><br>";
		}
		if ($image_b != "") {	// Check image B for data
			$show_gal_b .= "<div class=\"thumb\"><a href=\"http://users.mxo.nevercraft.net/~$username/images/$images[$b]\" target=\"_blank\"><img src=\"http://users.mxo.nevercraft.net/~$username/images/tn_$images[$b]\"></a>$form_del_b</div><br>";
		} else {
			$show_gal_b .="<div class=\"thumb\"></div><br>";
		}
		if ($image_c != "") {	// Check image C for data
			$show_gal_c .= "<div class=\"thumb\"><a href=\"http://users.mxo.nevercraft.net/~$username/images/$images[$c]\" target=\"_blank\"><img src=\"http://users.mxo.nevercraft.net/~$username/images/tn_$images[$c]\"></a>$form_del_c</div><br>";
		} else {
			$show_gal_c .="<div class=\"thumb\"></div><br>";
		}
	}
	echo "<form name=\"delimages\" id=\"delimages\" method=\"post\" action=\"$PHP_SELF?n=4&dodelete=1\">";
	echo "<div id=\"gallery\">";
	echo "<div class=\"col1\">" . $show_gal_a . "</div>";
	echo "<div class=\"col1\">" . $show_gal_b . "</div>";
	echo "<div class=\"col3\">" . $show_gal_c . "</div>";
	echo "</div><div class=\"clear\">&nbsp;</div><input type=\"submit\" name=\"submit\" value=\"Delete Selected Images\"></form>";
}


echo $msg;


?>