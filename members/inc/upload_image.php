<?php

// THUMBNAILER

function createthumb($name,$filename,$new_w,$new_h) {
	$system=explode('.',$name);
	if (preg_match('/jpg|jpeg/',$system[1])) {
		$src_img=imagecreatefromjpeg($name);
	}
	if (preg_match('/png/',$system[1])) {
		$src_img=imagecreatefrompng($name);
	}
	
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	if ($old_x > $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$old_y*($new_h/$old_x);
	}
	if ($old_x < $old_y) {
		$thumb_w=$old_x*($new_w/$old_y);
		$thumb_h=$new_h;
	}
	if ($old_x == $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}
	
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	
	if (preg_match("/png/",$system[1])) {
		imagepng($dst_img,$filename); 
	} else {
		imagejpeg($dst_img,$filename); 
	}
	
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
}

// END THUMBNAILER
	
	$form_block = "You are currently using $used_space_per% (or $total_size_kb Kb) of your allotted 5 Mb
		<form action=\"$PHP_SELF?n=2";
		
	if ($u == "avatar") {
		$form_block .= "&u=avatar";
	} elseif ($u == "screen") {
		$form_block .= "&u=screen";
	}
		
	$form_block .= "\" method=\"post\" enctype=\"multipart/form-data\">
		  <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"300000\">
		  <strong>Upload Image:</strong>
		  <br>
	";
	
	if ($u == "avatar") {
		$form_block .= "  <input type=\"hidden\" name=\"uploadavatar\" value=\"upload\">\n";
	} elseif ($u == "screen") {
		$form_block .= "  <input type=\"hidden\" name=\"uploadscreen\" value=\"upload\">\n";
	}
	
	$form_block .= "
		  <input type=\"hidden\" name=\"action\" value=\"upload\"><br>
		  <input name=\"image\" type=\"file\" size=\"30\">
		  <input name=\"Submit\" type=\"submit\" value=\"Upload\">
		</form>
	";
	
	if ($action == "upload") { // If they clicked the upload button...
		if ( ($image_name != "") && ((substr($image_name, -3) == "jpg") || (substr($image_name, -3) == "gif")) ) {
		// If the image has a name and its a jpg/gif
		
			if (substr($image_name, -3) == 'jpg') { // The file is .jpg
				$ext = ".jpg";
			} elseif (substr($image_name, -3) == 'gif') { // The file is .gif
				$ext = ".gif";
			}
		
			if ($used_space_per < 100) { // They're using less than 100% of their storage space
			
				if ($uploadavatar == "upload") { // If they're trying to upload their avatar
					$upload_true = @copy("$image", "/home/nevercraft/www/subdomains/mxo/members/users/~$username/images/avatar$ext")
						or die("Couldn't upload avatar.");
						
					echo "$image_name (an $image_type file) successfully uploaded as avatar$ext";
					
				} elseif ($uploadscreen == "upload") { // They're just uploading a screenshot
					$upload_true = @copy("$image", "/home/nevercraft/www/subdomains/mxo/members/users/~$username/images/$image_name")
						or die("Couldn't upload screen.");
						/*$create_thumb = TRUE;*/
						echo "Click <a href=\"$PHP_SELF?n=2&image_name=$image_name&create_thumb=1\">here</a> to create the thumbnail.";
						
				}
				
			} else { // They're over the 5mb allotment
				echo "You've exceeded your storage space.";
			}
				
		} else { // They were either too dumb to choose a file or didn't choose an image file.
			echo "Either you didn't choose an image, or you chose one of the wrong format.  Only .jpg or .gif!";
		}
	} elseif ($create_thumb) {
		createthumb("/home/nevercraft/www/subdomains/mxo/members/users/~$username/images/$image_name", "/home/nevercraft/www/subdomains/mxo/members/users/~$username/images/tn_$image_name", 100, 100);
		echo "The thumbnail was created!";
	} else { // They haven't been here yet.  Show them the upload form.
		echo $form_block;
	}

?>