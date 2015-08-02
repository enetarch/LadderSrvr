<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace config;

class connect_resources
{
	static public function connect ()
	{
		// get resource list
		
		// print (getcwd() . "/classes/config/resources.txt" . "<BR>");
		$aryResources = file (getcwd() . "/classes/config/resources.txt");

		// connect all resources
		
		foreach ($aryResources AS $line => $thsResource)
		{
			// add all the resources to the resource manager .. when it is created =)
			
			$thsResource = trim ($thsResource);
			$thsResource::connect();
		}
	}
}
?>
