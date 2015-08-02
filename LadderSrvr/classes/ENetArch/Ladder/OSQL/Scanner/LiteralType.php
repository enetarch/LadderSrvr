<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Ladder\OSQL\Scanner;

use \ENetArch\Common\DocFoo;

docFoo 
(Array 
(
	"Class" => "LiteralType",
	"Description" => "The literal is a number or word.",
	"Requirements" => Array 
		(
			"Indicate what type of value is was found in the text",
		),
	"Constants" => Array
		(
			"cINTEGER - The literal is an Integer",
			"cREAL - The literal is a Real",
			"cSTRING - The literal is a String",
		),
));

//*--------------------------------------------------------------
//*  Literal structure
//*--------------------------------------------------------------

class LiteralType
{
    const cINTEGER = 1;
    const cREAL = 2;
    const cSTRING = 3;
}

?>
