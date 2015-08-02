<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

use Tests\Tests;

/*	Scope
 * 
 * 	This test set covers the OSQL Scanner classes.
 * 	It is a full regression test suite.
 */



$aryTests = Array 
(
	"Tests/Ladder/OSQL/Scanner/test_Version",
	"Tests/Ladder/OSQL/Scanner/test_Token",
	"Tests/Ladder/OSQL/Scanner/test_TokenCodes",
	"Tests/Ladder/OSQL/Scanner/test_ReservedWord",
	"Tests/Ladder/OSQL/Scanner/test_ByteCode",
	"Tests/Ladder/OSQL/Scanner/test_ErrorMsgs",
	"Tests/Ladder/OSQL/Scanner/test_ReservedWords",
	"Tests/Ladder/OSQL/Scanner/test_Scanner",
);

const bDebugging = true;

$szLogFile = "TestLogs/" . time() . "-LogFile.txt";

$objTests = new \Tests\Tests ($szLogFile, $aryTests);

$objTests->printLog ();

?>
