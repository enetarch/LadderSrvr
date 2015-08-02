<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Service;

require_once ("init/global_Ladder.php");

use ENetArch\Common\Error;

Class Service 
{
	static public function execute ($szCommand, $szParams, $bVerbose)
	{
		// ==================================
		// connect to resources
		// can this be abstracted and obfiscated?

		// ==================================
		// connect to resources
		
		\config\connect_resources::connect();
			
		// ==================================

		if (strpos ($szCommand, ".") < 1)
		{
			gblError()->init ("( . ) missing period from Comamnd.Method call", -1, "Service", "", $szCommand);
			return (null);
		}

		$nStart = strpos ($szCommand, ".");
		$szClass = trim (substr ($szCommand, 0, $nStart));
		$szMethod = trim (substr ($szCommand, $nStart+1));
		
		// ==================================
		
		$szClass = "Commands\\" . $szClass;

		try { class_exists ($szClass); }
		catch (\ErrorException $e)
		{
			gblError()->init ("No Such Class", -1, "Service", "", $szClass);
			return (null);
		}
		
		if (! method_exists ($szClass, $szMethod)) 
		{
			gblError()->init ("No Such Method", -1, "Service", "", $szClass . "::" . $szMethod);
			return (null);
		}
		
		// ==================================
		
		return ( $szClass::$szMethod ($szParams) );
	}
}

?>
