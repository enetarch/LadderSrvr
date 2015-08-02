<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

use \ENetArch\Common\TraceLog;

global $gblTraceLog;
$gblTraceLog = new TraceLog();

function gblTraceLog () { global $gblTraceLog; return ($gblTraceLog); }

?>
