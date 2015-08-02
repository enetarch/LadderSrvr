<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace config\Resources;

use \config\ladder_server;
use \ENetArch\Ladder\Client\ldrLadder;

Class Ladder_Client
{
	static public function connect ()
	{
		global $gblLadder;
		
		$gblLadder = new \ENetArch\Ladder\Client\ldrLadder ();
		if (gblError()->No() != 0)
			return (null);
		
		$gblLadder->Connect 
			( 
				ladder_server::szIP (), 
				ladder_server::szSerialNo (),
				ladder_server::szUID (),
				ladder_server::szPSW ()
			);
		if (gblError()->No() != 0)
			return (null);
	}
}

?>
