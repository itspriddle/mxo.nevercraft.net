<?php
	$userpath = "/Users/priddle/Sites/mxo.nevercraft.net/members/users/~";
	$biopath = "/dat/bio.txt";
	$biostr = "$userpath$username$biopath";
	$biofile = fopen("$biostr", "r") or die("Couldn't open bio.txt");
	while(!feof($biofile)) {
		$bio_str .= fgets($biofile);
		$bio_array = explode("|||\n", $bio_str);
	}
	fclose($biofile);

	$name = $bio_array[0];
	$mxoname = $bio_array[1];
	$age = $bio_array[2];
	$location = $bio_array[3];
	$email = $bio_array[4];
	$aim = $bio_array[5];
	$url = $bio_array[6];
	$interests = $bio_array[7];
	$interests = stripslashes($interests);
	$biography = $bio_array[8];
	$biography = stripslashes($biography);

	$imgdirname = "$userpath$username"."/images/";
	$imgdir = opendir($imgdirname);

	$total_size = 0;

	while ($file_name = readdir($imgdir)) {
		if (substr($file_name, 0, 6) == "avatar") {
			$avatar_found = $file_name;
		}

	$total_size = $total_size + filesize("$imgdirname$file_name");

	$total_size_kb = $total_size / 1024;

	$total_size_kb = number_format($total_size_kb, "2", ".", ",");

	$used_space = ($total_size / 5242880);

	$used_space_per = $used_space * 100;

	$used_space_per = number_format($used_space_per, "2", ".", ",");


	}
	closedir($imgdir);

?>
