<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

include_once ("DocFoo.php");

use \ENetArch\Common\DocFoo;

Function docFoo ($aryValues) 
{	
	if (DocFoo::cReport) 
	{
		$thsDoc = new DocFoo();
		$thsDoc->Report ($aryValues); 
	}
}

docFoo 
( Array
(
	"Function" => "docFoo",
	"Description" => "DocFoo is used to capture and generate documentation that is stored in code.",
	"Syntax" => "docFoo (Array () )",
	"Parameters" => Array ("aryValues - an array of documentation values"),

	"Globals" => Array 
	(
		"docFoo::cReport - determines if the document should be generated"
	),

	"Core Code" => "if (report) call reporting function",

	"Usage" => Array 
	(
		
		"As a function call, use: docFoo (Array ( Name / Values Pairs )); ",
		
		"When embedding docFoo in a class, create public static functions using the naming
		  practice: docFoo_[names] () { docFoo (Array ( Name / Values Pairs )); }, then use a 
		  master static function to call all the docFoo_[names] () to generate your 
		  documentation. At the end of the class file, place the the following fuction call, 
		  [classname]::docFoo();  This call has to be outside the class brackets {} to be 
		  called at run time.",
		  
		  "
				Class Test
				{
					public static function docFoo ()
					{
						if (! docFoo_report) return;
						
						docFoo_myFoo ();
					}
					
					// ========================
					public static function docFoo_myFoo ()
					{ 
						// JSON array of name value pairs 
						Array ( \"name\" => \"value\" ); 
					}						
					
					public function myFoo () {}
				}
				
				Test::docFoo();
		  ",
		  
		  "Another possible usage format is to create arrays assgned to variables in the
		  class."
	),
		
	"Testing" => Array 
	( 
		"No documentation is generated when docFoo_report is FALSE",
		"Documentation is generated when docFoo_report is TRUE",
	),
		
	"Comments" => "It's ironic that documenting self documenting code requires so much documentation",
) ) ;

?>
