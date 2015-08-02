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
	Test_02.php
	
	Description:
		This test confirms basic functionality of Ladder by retrieving the root folders,
		the Root_Classes folder and it's first level children.

	Features Tested
		Ladder can retrieve the root folders
		Ladder can retrieve a folder from the folder list
		Ladder can state the number of children present

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

$objFolders = $gblLadder->getRoots ();
print ("count = " . $objFolders->count()  . "<BR>");


$fldrRoot = $objFolders->getItem (2);
print ("name = " . $fldrRoot->Name()  . "<BR>");
print ("count = " . $fldrRoot->Count()  . "<BR>");

$objLinks = $fldrRoot->getChildren ();
print ("count = " . $objLinks->count()  . "<BR>");

$objItem = $fldrRoot->Item (1);
print ("name = " . $objItem->Name()  . "<BR>");


print ( date_create()->format ("Y-m-d h:m:s") . "<P>");

?>
