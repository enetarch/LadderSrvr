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

	// ==================================
	// Returns

	CreateClass ("Returns_Returned", ldrGlobals::cisFolder(), 0 , true);
	//->
		$szStr =
			" nQuantity int ";
		CreateClass ("Returns_Quantity", ldrGlobals::cisItem(), 0 , true, $szStr);

		//"Returns_LineItem" Reference Class "LineItem_LineItem"


return ;
}
?>
