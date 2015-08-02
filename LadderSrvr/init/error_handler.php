<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

error_reporting (E_ALL);

function exception_error_handler ($errno, $errstr, $errfile, $errline ) 
{ 
	print ("error caught - " . $errno . " - " . $errfile . " - " . $errline . "<BR>");
	throw new \ErrorException ($errstr, 0, $errno, $errfile, $errline); 
}

set_error_handler("exception_error_handler");

function exception_handler($exception) 
{
	print ("Uncaught exception: " . $exception->getMessage() . "\n");
  
	gblTraceLog()->Log ("included files", get_included_files () );
	gblTraceLog()->Log ("function call trace", debug_backtrace());	
}

set_exception_handler('exception_handler');
?>
