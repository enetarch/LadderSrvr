<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace config\Resources;

use \config\mysql_server;
use \ENetArch\Ladder\Server\Ladder;

Class Ladder_Server 
{
	static public function connect ()
	{
		global $gblLadder;
		
		$gblLadder = new \ENetArch\Ladder\Server\Ladder ();
		if (gblError()->No() != 0)
			return (null);
		
		$gblLadder->Connect ( mysql_server::connection_string() );
		if (gblError()->No() != 0)
			return (null);
	}
}

?>
