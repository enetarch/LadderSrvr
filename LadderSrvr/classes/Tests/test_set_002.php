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
//		"Tests/Ladder/OSQL/Parser/test_Version",
//		"Tests/Ladder/OSQL/Parser/test_Param",
//		"Tests/Ladder/OSQL/Parser/test_Params",
//		"Tests/Ladder/OSQL/Parser/test_initGlobals",
//		"Tests/Ladder/OSQL/Parser/test_initTokens",
//	"Tests/Ladder/OSQL/Parser/test_TokenCodes",
//	"Tests/Ladder/OSQL/Parser/test_Parser",
//	"Tests/Ladder/OSQL/Parser/test_miniParser",
	"Tests/Ladder/OSQL/Parser/test_osqlParser",
);

const bDebugging = true;

$szLogFile = "TestLogs/" . time() . "-LogFile.txt";

$objTests = new \Tests\Tests ($szLogFile, $aryTests);

$objTests->printLog ();

?>
