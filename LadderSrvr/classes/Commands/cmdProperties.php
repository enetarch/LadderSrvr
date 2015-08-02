<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace Commands;

class cmdProperties // Factory Class
{
	static public function  Store ($szParams)
	{ return (gblLadder()->update_Link ($szParams [0]) );  }

	static public function  getPath ($szParams)
	{ 
		$item = gblLadder()->getItem ($szParams [0]);
		
		return ( $item->Path() );  
	}
}
?>
