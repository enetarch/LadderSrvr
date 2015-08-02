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
	Const szVersion = "1.2.3";
	Const szCompiled = "2004-08-03 - 2:14 pm";
	Const szCompiledBy = "Michael J. Fuhrman";

	// ============================================

	public static function docFoo_docFoo ()
	{ docFoo ( Array 
		(
			"Method" => "docFoo",
			"Syntax" => "docFoo ()",
			"Description" => "Generates the documentation for this clas",
			"Globals" => Array 
				(
					"docFoo_report - determines if the document should be generated"
				),
			"Core Code" => 
					"Call self documenting methods within the class",		
		) );
	}

	public static function docFoo ()
	{
		if (!DocFoo::cReport) return;
		
		self::docFoo_Version ();
		self::docFoo_Compiled ();
		self::docFoo_CompiledBy ();
	}

	// ============================================
	
	public static function docFoo_Version ()
	{ docFoo ( Array 
		(
		"Method" => "Version",
		"Syntax" => "Version ()",
		"Description" => "return the current working version of this code base",
	 ) );
	}

   public function Version() 
   {  return self::szVersion; }

	// ============================================
	
	public static function docFoo_Compiled ()
	{ docFoo ( Array 
		(
		"Method" => "Compiled",
		"Syntax" => "Compiled ()",
		"Description" => "return the date that this code base was last compiled",
	 ) );
	}

   public function Compiled() 
   {  return self::szCompiled; }

	// ============================================
	
	public static function docFoo_CompiledBy ()
	{ docFoo ( Array 
		(
		"Method" => "CompiledBy",
		"Syntax" => "CompiledBy ()",
		"Description" => "return the name of the individual who compiled this code base",
	 ) );
	}

   public function CompiledBy() 
   {   return self::szCompiledBy; }

	// =================================================
	// Verion History

	//  2004-08-03 - 2:14 pm - recompiled
	//  2004-01-28 - 1:16 pm - Recompiling all
	//  2004-01-27 - 11:50 pm - Recompiled due to DebugW
	//  2003-07-02 - 12:22 am - found xmlDoc single Threaded
	//  2003-07-01 - 6:05 pm - changed project properties ->->
	//     Unattended execution - checked
	//     Retained in Memory - checked

	//  2003-05-21 - 9:48 am - szBR was updated, but this module wasn//t
	//   recompiled till now
	//  2002-07-31 - 3:07 pm - updated DebugW
	//  2002-07-22 - 3:16 pm  - removed all stop commands

	// 2002-06-09 - 10:14 pm - switched to appartment threading

	// 2002-05-16 - 2:20 am - added byref and private to functions and parameters

	// 2002-04-26 - 2:52 pm - changed the error handler which forced
	//     this object to be recompiled-> =(
	
}
?>
