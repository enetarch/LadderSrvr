<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace ENetArch\Common;

class TraceLog 
{
	private $aryTraceLog = Array ();
	
	function log ($szKey, $szValue) 
	{ 
//		print ("type (szKey) = " . gettype ($szKey) . "<BR>");
//		print ("type (szValue) = " . gettype ($szValue) . "<BR>");
//		print ("value (szValue) = <pre>"); print_r ($szValue); print("</pre><BR>");
//		print ("value (szValue) = " . implode ("<BR>", $szValue) . "<BR>");
		
		if (is_array ($szValue))
		{ 
			print ("Tracelog Error .. szValue = <pre>"); print_r ($szValue); print("</pre><BR>"); 
		}
		else
		{ $this->aryTraceLog [] = $szKey . " - " . $szValue; }
	} 
	
	function getLog () { return ($this->aryTraceLog); }
	
	function ErrorMsg (\ENetArch\Common\Error $msg)
	{ $this->aryTraceLog ["ErrorMsg"] = $msg->No() . " - " . $msg->Description() . " - " . $msg->CallPath() . " - " . $msg->SQL(); }
}
?>
