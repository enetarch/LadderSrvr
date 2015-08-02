<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Ladder\OSQL\Parser;

use \ENetArch\Common\DocFoo;

docFoo 
( Array
(
	"Class" => "Version",
	
	"Description" => 
		"Version is used generically to identify the version, compiled date and who compiled the code.
			This information allows future code blocks to confirm the version of the library being used
			so that it can determine if it can work with it, or needs to be updated.
		",

	"Requirements" => Array 
	(
		"Store the current code version", 
		"Store the date the code was compiled last", 
		"Store who compiled the code last", 
	),

	"Constants" => Array
		(
			"szVersion - the current working version of this code base",
			"szCompiled - the date that this code base was last compiled",
			"szCompiledBy - the name of the individual who compiled this code base",
		),

	"Methods" => Array
		(
			"Version - INTEGER.INTEGER.INTEGER STRING - Version of this code base ", 
			"Compiled - DATE STRING - The date of the last compilation", 
			"CompiledBy - STRING - who compiled the code base last", 
		),
	
	"Tests" => Array
		(
			"Confirm the version returned matches the version set in the constant",
			"Confirm the date compiled returned matches the date set in the constant",
			"Confirm who compiled the code matches the name set in the constant",
		),
));

class Version
{
	Const szVersion = "1.3.0";
	Const szCompiled = "2004-08-03 - 2:14 pm";
	Const szCompiledBy = "Michael J. Fuhrman";

   public function Version() 
   {  return self::szVersion; }

   public function Compiled() 
   {  return self::szCompiled; }

   public function CompiledBy() 
   {   return self::szCompiledBy; }

}

// =================================================
// Verion History

//  2004-08-03 - 2:14 pm - recompiled

//  2004-04-19 - 12:25 pm - added parser->get/set debug so that
//     all debug code data is stored in one object

//  2004-01-28 - 1:16 pm - Recompiling all
//  2004-01-27 - 11:52 pm - Recompiled due to DebugW
//  2003-10-05 - 9:33 pm - added TokenCodes::tcFunction to parser to allow
//     the passing of functions to the SQL Server, while at the
//     same time converting the fieldnames->

//  2003-07-02 - 12:22 am - found xmlDoc single Threaded
//  2003-07-01 - 6:05 pm - changed project properties ->->
//     Unattended execution - checked
//     Retained in Memory - checked

//  2003-05-21 - 9:48 am - udpated szBR
//  2002-07-31 - 4:37 pm - cleaned up the debug class
//  2002-07-31 - 3:14 pm - cleaned up the debug class
//  2002-07-31 - 2:49 pm - added DebugClear to the Parser class->
//  2002-07-31 - 2:20 pm - added remaining link property fields for searching
//        by them ->-> such as ID, Name, Description, Parent, and
//        Date, UID and IP - that - Created, Accessed, Modified the link->

//  2002-07-22 - 3:23 pm - removed all stop commands

//  2002-06-09 - 10:14 pm - swtiched to appartment threading

// 2002-05-25 - 10:31 pm - added the RESULTS keyword so that
//     values can be selected->


// 2002-05-14 - 8:18 pm - missed the ID field->

// 2002-05-11 - 8:31 am - added the ability for select to return a folder
//     based on just a path being passed in->

// 2002-05-08 - 4:47 pm - added the ability to use the FOLDER token
//        in an expression, such as CONTAINS->

// 2002-04-26 - 2:53 pm - changed the error handler which forced
//     this object to be recompiled-> =(

?>
