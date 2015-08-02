<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Ladder\OSQL\Scanner;

docFoo 
(Array 
(
	"Class" => "CharCode",
	"Description" => "A list of constants that classify a character in the text",
	"Requirements" => Array 
		(
			"Indicate what character type was found in the text",
		),
	"Constants" => Array
		(
			"cLETTER - The character is an Alphabet Letter",
			"cDIGIT - The character is a Digit",
			"cQUOTE - The character is a Quote",
			"cSPECIAL - The character is a Special",
			"cEOFCODE - The End Of File has been reached",
		),
));

//*--------------------------------------------------------------
//*  Character codes
//*--------------------------------------------------------------

class CharCode
{
	 const cLETTER = 1;
	 const cDIGIT = 2;
	 const cQUOTE = 3;
	 const cSPECIAL = 4;
	 const cEOFCODE = 5;
}

?>
