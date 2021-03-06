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
	Test_03.php
	
	Description:
		This test confirms that the classes are retrieved, and can be used to 
		create a new folder in the Root_Folder folder.

	Features Tested
		Ladder can retrieve the folder - Root_Folder
		Ladder can create a folder - DayTimer, in the folder - Root_Folder
		Ladder can state the number of children present
		Ladder can delete the child folder - DayTimer, from the folder - Root_Folder

	Test Setup
		It is assumed that Ladder has been successfully installed in Test_01.php
	
	Version
			2013-08-22 - 8:00 am - MJF - Created
		
	
 */
// ===================================================

include_once ("Ladder/Ladder_Ladder.php");

print ( date_create()->format ("Y-m-d h:m:s") . "<P>");

$gblLadder = new ENetArch_Ladder ();

$gblLadder->Connect ("192.168.0.35", "szSerNo", "szUID", "szPSW");

$bInstalled = $gblLadder->isInstalled ();
print ("is installed = " . $bInstalled . "<BR>");

if ($bInstalled == false)
{
	print ("Ladder not installed");
	exit ();
}

$rootFolder = $gblLadder->getItem (1);
print ("count = " . $rootFolder->count()  . "<BR>");

$fldrDayTimer = $rootFolder->Create_Folder ("DayTimer", "", ENetArch_Ladder_Classes_Ladder_Folder );
$fldrDayTimer->Store();

print ("created folder " . $fldrDayTimer->Name() . " " . $fldrDayTimer->ID() . "<BR>");
print ("count = " . $rootFolder->count()  . "<BR>");

$fldrDayTimer->Delete();
print ("count = " . $rootFolder->count()  . "<BR>");

print ( date_create()->format ("Y-m-d h:m:s") . "<P>");

?>
