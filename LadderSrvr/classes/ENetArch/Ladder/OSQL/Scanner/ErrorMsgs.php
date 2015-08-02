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

//****************************************************************/
//*
//*      E R R O R   M E S S A G E S
//*
//****************************************************************/

docFoo 
(Array 
(
	"FileName" => "ErrorMsgs",
	"Description" => "A list of error messges used by classes for the scanner",
));

class ErrorMsgs
{
	//****************************************************************/
	//* - Scanner Errors

	Const errorMsg000 = "";
	Const errorMsg001 = "Syntax Error";
	Const errorMsg002 = "TOO MANY SYNTAX ERRORS";
	Const errorMsg003 = "The Reserved Word List is NULL";
	Const errorMsg004 = "The Buffer is NULL";
	Const errorMsg005 = "INVALID NUMBER ";
	Const errorMsg006 = "INVALID FRACTION ";
	Const errorMsg007 = "INVALID EXPONENT ";
	Const errorMsg008 = "TOO MANY DIGITS ";
	Const errorMsg009 = "REAL OUT OF RANGE ";
	Const errorMsg010 = "INTEGER OUT OF RANGE ";
	Const errorMsg011 = "Unrecognized Character";
	Const errorMsg012 = "The first digit is not a Number";
	

	//****************************************************************/
	//* - Reseved Word Errors

	Const errorMsg021 = "The Word is NULL";
	Const errorMsg022 = "The Token is NULL";
	Const errorMsg023 = "The Word is USED";
	Const errorMsg024 = "The Token is USED";
	Const errorMsg025 = "The Token code is 0";

	//****************************************************************/
	//* - Reseved Word Container Errors

	Const errorMsg031 = "The Container is Empty";
	Const errorMsg032 = "The Item Index is Out of Range";
	Const errorMsg033 = "";

	//****************************************************************/
	//* - ByteCode Errors

	Const errorMsg040 = "The Item Index is Out of Range";
}
?>
