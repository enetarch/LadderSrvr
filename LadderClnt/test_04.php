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
		Ladder can create a new class - for data items
		Ladder can create a data item - Address, in the folder - Root_Folder
		Ladder can state the number of children present
		Ladder can update the state of a data item
		Ladder can delete the child data item - Address, from the folder - Root_Folder

	Test Setup
		It is assumed that Ladder has been successfully installed in Test_01.php
		It is assumed that the class Common_Address is not installed
	
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

	$szStr =
		" szStreet1 varChar(40), " .
		" szStreet2 varChar(40), " .
		" szCity varChar(20), " .
		" szState varChar(2), " .
		" szZip varChar(10) " ;

	$szData = Array
	(
		"szStreet1" => "PO Box 43766",
		"szStreet2" => "",
		"szCity" => "Detroit",
		"szState" => "MI",
		"szZip" => "48243-0766",
	);

$rootFolders = $gblLadder->getRoots();
print ("root folders count = " . $rootFolders->count()  . "<BR>");

$rootFolder = $rootFolders->getItem (1);
print ("root folder count = " . $rootFolder->count()  . "<BR>");

$itmsAddresses = $rootFolder->getChildren ("Address", Array ("Common_Address"), 0, 0, true);
print ("itmsAddresses.count() = " . $itmsAddresses->count() . "<BR>");

$itmsAddresses = $rootFolder->Items ("Address", Array ("Common_Address"), 0, true);
print ("itmsAddresses.count() = " . $itmsAddresses->count() . "<BR>");

print ("=====================================<BR>");
for ($t=1; $t<$itmsAddresses->Count()+1; $t++)
{
	$objItem = $itmsAddresses->getItem ($t);
	$objItem->Delete();
}

print ("=====================================<BR>");

$itmsAddresses = $rootFolder->Items ("Address", Array ("Common_Address"), 0, true);
print ("itmsAddresses.count() = " . $itmsAddresses->count() . "<BR>");


$itmAddress = $rootFolder->Create_Item ("Address", "myAddress", "Common_Address");
$itmAddress->setData ($szData);
$itmAddress->Store ();

print ("created item - 1 " . $itmAddress->Name() . " " . $itmAddress->GUID() . "<BR>");

/*
$itmNewAddr = $gblLadder->getItem ($itmAddress->GUID());
$szData = $itmNewAddr->getData ();
print ("itmNewAddr->getData () - 1 = ");
print_r ($szData);
print ("<BR>");
*/

	$szData = Array
	(
		"szStreet1" => "PO Box 64-0662",
		"szStreet2" => "",
		"szCity" => "San Francisco",
		"szState" => "CA",
		"szZip" => "94164",
	);

print ("============================<BR>");
print ("============================<BR>");

$itmAddress->setData ($szData);
$itmAddress->Store ();

print ("============================<BR>");
print ("item updated - 2 " . $itmAddress->Name() . " " . $itmAddress->GUID() . "<BR>");
print ("count = " . $rootFolder->count()  . "<BR>");

print ("itmAddress->GUID() = " . $itmAddress->GUID() . "<BR>");

$itmNewAddr = $gblLadder->getItem ($itmAddress->GUID());
$szData = $itmNewAddr->getData ();
print ("itmNewAddr->getData () -2 = ");
print_r ($szData);
print ("<BR>");

// $itmAddress->Delete();
print ("count = " . $rootFolder->count()  . "<BR>");



print ( date_create()->format ("Y-m-d h:m:s") . "<P>");

?>
