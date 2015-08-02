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
	// Shopping Cart
	//
	//	Shopping_Cart
	//		Orders
	//			Order

	CreateClass ("ShoppingCart_ShoppingCart", ldrGlobals::cisFolder(), 0 , true);
	//->
		CreateClass ("ShoppingCart_Orders", ldrGlobals::cisFolder(), 0 , true);
		//->
			//"ShoppingCart_Order" Folder Class "Orders_Order"
			$clsOrders_Order = $gblLadder->getClass ("Orders_Order")->ID();
			CreateClass ("ShoppingCart_Order", ldrGlobals::cisFolder(), $clsOrders_Order , true);

return ;
}
?>
