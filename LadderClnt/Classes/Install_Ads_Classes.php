<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

	function dirPath () { return ("../../"); }
	Include_Once (dirPath() . "Shared/_app.inc");

Function php_Main ()
{
	Include_Once ("Install_Functions.inc");
	Global $gblLadder;

	// ==================================
	// Ads
	//
	//	Ad
	//		Header
	//		Clicks
	//			Click
	//		Image_URL
	//		Target_URL

	CreateClass ("Ads_Ad", ldrGlobals::cisFolder(), 0 , true);
	//->
		$szStr =
			" dStart DateTime, " .
			" dStop DateTime, " .
			" nFrequency Int, " .
			" dLast_Displayed DateTime ";
		CreateClass ("Ads_Header", ldrGlobals::cisItem(), 0 , true, $szStr);

		CreateClass ("Ads_Clicks", ldrGlobals::cisFolder(), 0 , true);
		//->
			$szStr =
				" nClicks Int";
			CreateClass ("Ads_Click", ldrGlobals::cisItem(), 0 , true, $szStr);

		$szStr =
			" szURL varChar(250)";
		CreateClass ("Ads_Image_URL", ldrGlobals::cisItem(), 0 , true);

		$szStr =
			" szURL varChar(250)";
		CreateClass ("Ads_Target_URL", ldrGlobals::cisItem(), 0 , true);

return ;
}
?>
