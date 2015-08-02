<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace config;

class mysql_server
{
	static function connection_string () 
	{ return ("SERVER=localhost;UID=root;PSW=root;DSN=ENetArch_LadderSrvr;"); }
}

?>
