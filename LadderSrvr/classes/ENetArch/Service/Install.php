<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Service;

require_once "init/global_Ladder.php";

use \ENetArch\Common\Error;

Class Install 
{
	static public function execute ($bVerbose=false)
	{
		// ==================================
		// connect to resources
		// can this be abstracted and obfiscated?

		// ==================================
		// connect to resources
		
		\config\connect_resources::connect();
			
		// ==================================

		return ( (gblLadder()->isInstalled () ) ? true : gblLadder()->Install () );
	}
}

?>
