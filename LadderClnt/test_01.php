<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

// ===================================================
/*
	Test_01.php
	
	Description:
		This test verifies that the client can connect to the server and determine if
		Ladder is installed or not.  It then uninstalls it if it is installed.  Andfinally,
		installs Ladder.
	
	Features Tested
		Ladder can detect when installed on server
		Ladder can uninstall itself if needed
		Ladder can install itself

	Test Setup
		Several runs are needed to complete this test.
		1st run - Ladder is not installed
		2nd run - Ladder is installed
		3rd run - Ladder is installed
		
	Version
			2013-08-22 - 8:00 am - MJF - Created
		
	
 */
// ===================================================


include_once ("Ladder/Ladder_Ladder.php");

print ( date_create()->format ("Y-m-d h:m:s") . "<P>");

$gblLadder = new ENetArch_Ladder ();
// $rtn = $gblLadder->Version ();

$gblLadder->Connect ("192.168.0.35", "szSerNo", "szUID", "szPSW");

$bInstalled = $gblLadder->isInstalled ();
print ("is installed = " . $bInstalled . "<BR>");

if ($bInstalled == true)
{
	$fldrRoots =  $gblLadder->getRoots();
	print ("Retrieved Roots = " . $bInstalled . "<BR>");
	
	$bInstalled = $gblLadder->unInstall ();
	print ("unInstalled = " . $bInstalled . "<BR>");
}

$bInstalled = $gblLadder->Install ();
print ("installed = " . $bInstalled . "<BR>");

$fldrRoots =  $gblLadder->getRoots ("Root Folder", ENetArch_Ladder_Globals::cClass_RootFolder());
print ("Retrieved Roots = " ); 
print_r ($fldrRoots);
print ( "<BR>");

print ( date_create()->format ("Y-m-d h:m:s") . "<P>");

?>
