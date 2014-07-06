<?php

//generate unique file name for photo on server
function generateFileName(){
	$assembledname = "";
	$chars  = "abcdefghijklmnopqrstuvwxyz1234567890";
	
	for($i=0;$i<20;$i++){
		$randomChar = rand(0,strlen($chars)-1);
		$assembledname .= substr($chars,$randomChar,1);
	}

	return $assembledname;
}

if(isset($_POST["google_url"])){
	$ch = curl_init();
	$timeout = 0;
//	curl_setopt ($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/streetview?key=YOURAPIKEY&location=33.869709,-84.20649700000001&heading=0&pitch=0&fov=90&size=400x400&sensor=false");
	curl_setopt ($ch, CURLOPT_URL, $_POST["google_url"]);

	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

	// Getting binary data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	$filecontents = curl_exec($ch);
	curl_close($ch);

	$filename = generateFileName() . ".jpg";
	$friends_house_on_server = getcwd() . "/quarantined/" . $filename; 
	$tape_graphic = getcwd() . "/tape.png";

	file_put_contents($friends_house_on_server, $filecontents);
	chmod($friends_house_on_server, 0776);

	$yourhouse = imagecreatefromjpeg($friends_house_on_server);
	$cautiontape = imagecreatefrompng($tape_graphic);

	imagecopy($yourhouse,$cautiontape,0,0, 0, 0,400,400);

	imagejpeg($yourhouse,$friends_house_on_server,100);

	imagedestroy($yourhouse);
	imagedestroy($cautiontape);

	echo $filename;
}

?>