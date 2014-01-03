<?
/*	=======================================
	Copyright 1998 - 2013 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

include_once ("shared/_app.php");
include_once ("Ladder/ENetArch.php");

// ====================================================================

$aryResults = Array 
(
	"return" => "false",
	"error" => null
);

// ====================================================================

$gblError = ENetArch::Common_Error ();

$gblLadder = ENetArch::Ladder();
if ($gblError->No() != 0)
	exitTree ($aryResults);

$gblLadder->Connect ($szODBC);
if ($gblError->No() != 0)
	exitTree ($aryResults);
	
$rtnVersion = $gblLadder->Version ()->VersionNo (); 
	
$rtnIsInstalled = $gblLadder->isInstalled (); 

// ====================================================================

$aryResults ["Version"] = $rtnVersion;
$aryResults ["isInstalled"] = $rtnIsInstalled;

exitTree ($aryResults);

// ==========================================

function exitTree ($aryResults)
{
	global $gblError;
	
	$aryResults ["error"] = $gblError->toArray();

	print ("<pre>");
	print_r ($aryResults);
	print ("</pre>");

	exit ();
}
?>
