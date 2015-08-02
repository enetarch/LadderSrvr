<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

$data = array
	(
		"szCommand" => "isInstalled", 
		"bVerbose" => true, 
		"szParams" => Array 
		(
			"1" => "3",
			"2" => "4",
		)
	);
	                                                            
$data_string = "json=" . json_encode($data) . "&";

// print ($data_string . "<BR>");

$ch = curl_init("http://192.168.0.35/LadderSrvr/tree.php");   
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
$results = curl_exec($ch);

print ($results);
?>
