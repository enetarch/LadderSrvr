<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

error_reporting (E_ALL);

// const cBACKSLASH = "\\";
// const cSLASH = "/";

function __autoload($class) 
{
	// print ("calling for class .. '" . $class . "'<BR>");
	
	$class = "classes/" . str_replace (cBACKSLASH, cSLASH, $class) . ".php";
	
	if (!file_exists ($class))
		return;
		
	// print ("getting file .. '" . $class . "'<BR>");

	include_once ($class);
}

?>
