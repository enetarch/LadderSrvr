<?
/*	=======================================
	Copyright 1998 - 2013 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

   Include_Once ("functions.php");

//***************************************************************
//*
//*		Database Connection
//*
//***************************************************************

$szODBC = "HOST=localhost;UID=root;PSW=root;DBF=WEBSERVER_DEV;";

$szIP = getenv ("REMOTE_ADDR");
	
// ==========================================

function gblLadder() { global $gblLadder; return ($gblLadder); }

// ==========================================

?>
