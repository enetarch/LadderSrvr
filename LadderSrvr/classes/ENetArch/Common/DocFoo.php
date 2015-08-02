<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace ENetArch\Common;

class DocFoo
{
	const cReport = false;

	private $aryKeys = Array 
	(
		"FileName", 			// Name of an include file containing static values / functions
		"NameSpace", 	// The unique name path for this class, constants, and functions
		"Class", 					// Name of the class
		"Extends", 			// The name of the class extended
		"Requirements", // Conditions[] the class / function must meet
		"Function", 			// Name of the Constant, Method or Property
		"Method",				// Name of method provided by this class
		"Property",			// Name of property provided by this class
		"Syntax", 				// Strings[] that describe various permutations
		"Description",		// Description of the class, method, property, or constant
		"Include",				// Files[] needed by this class
		"Globals", 				// Values[] used by the class and where to find them
		"Constants",			// Names - Descriptions[] of values provided by the class
		"Variables",			// Name - Descriptions[] of class variables
		"Methods",			// Names - Descriptions[] of methods provided by this class
		"Properties",			// Names - Descriptions[] of methods provided by this class
		"Parameters",		// Names - Descriptions[] of parameters used when calling a method
		"Validations",		// Rules[] describing variable bounds and error messages thrown
		"Core Code",		// Design logic for this method or property
		"Returns",				// Values[] returned by this method or property
		"Comments", 		// Thoughts about this class, method, property, or constant 
		"See Also",			// Other[] class, methods, properties, or constants with similar properties
		"Usage",					// Ways[] that this class, method, property, or constant have been used
		"Testing", 				// Unit Tests[] for full regression testing
		"Examples", 		// Examples[] of how to use this class, method, property, or constant
		"Errors",					// Errors[] that will be reported by this class, method, property, or constant
		"Future",					// Thoughts about future enhancements 
	);

	function docFoo_Report ()
	{
		docFoo ( Array
			(
				"Function" => "docFoo_Report",
				"Description" => "Generates the documentation in the of the keys listed in aryDocFoo_Keys ",
				"Syntax" => "docFoo_Report ( Array () )",
				"Parameters" => Array ("aryValues - an array of documentation values"),
				"Globals" => Array 
					(
						"aryDocFoo_Keys - List of keys used to generate the documentation.",
					),
				"Core Code" => 
						"read through the array of keywords to find matching keywords in the values
						passed in.  Then send that found value in the Name / Value pair to the output 
						buffer.  ",
				"Usage" => Array 
					(
						"Change this function to meet your individual reporting needs or requirements",
						"Individual reporting needs could be: RTF, HTML, XML, and/or Text (Man Pages)",
					),
				"Testing" => Array 
					(
						"Confirm specific keywords are found",
						"Confirm unknown keywords are ignored",
					)
			) ) ;
	}
	
	Function Report ($aryValues)
	{
		
		ForEach ($this->aryKeys as $Key => $Val)
			if (isset ($aryValues [$Val])) 
			if (is_array ($aryValues [$Val]))
			{
				print ("$Val:<BR>"); 
				ForEach ($aryValues [$Val] as $Key2 => $Val2)
					print ("$Key2:<BR>" . $Val2 . "<P>");
			}
			else
				switch ($Val)
				{
					case "Class":
						print ("<h3>$Val: " . $aryValues [$Val] . "</h3><P>");
						break;
						
					default:
						print ("$Val:<BR>" . $aryValues [$Val] . "<P>");
				}
			
		print ("==========<P>");
	}

}

?>
