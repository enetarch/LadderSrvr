<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

use \ENetArch\Ladder\Ladder;

global $gblLadder;
$gblLadder = new \ENetArch\Ladder\Server\Ladder ();

function gblLadder() { global $gblLadder; return ($gblLadder); }
?>
