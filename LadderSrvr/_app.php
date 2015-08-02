<?
/*	=======================================
	Copyright 1998 - 2010 - E Net Arch
	This program is distributed under the terms of the GNU 

	General Public License (or the Lesser GPL).
	www.ENetArch.net
	======================================= */

error_reporting (E_ALL);

const cBACKSLASH = "\\";
const cSLASH = "/";

function __autoload($class) 
{
	$class = "classes/" . str_replace (cBACKSLASH, cSLASH, $class) . ".php";
	include_once ($class);
}

// ===============================================

use ENetArch\Ladder\Ladder;
use config\mysql_server;

// ===============================================
//		Database Connection

function gblLadder() { global $gblLadder; return ($gblLadder); }


/* ===============================================

2014-10-17 - updated code to use GUIDs

2014-06-04 - updated code to use namespaces

2010-07-29 - added session_start to the Main() function
   as sessions are a regular part of every application
   I build on top of Ladder.

2010-07-29 - added the function gblLadder() which
   returns the $gblLadder global variable.  Globals
   require the programmer to remember to put 
   'global $gblLadder;'  at the beginning of the 
   functions that use the global variable.  A better
   way to treat global variables is as global functions.


   ===============================================
*/ 

?>
