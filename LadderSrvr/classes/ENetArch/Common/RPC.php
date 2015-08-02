<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Common;

use ENetArch\Common\Error;

require_once ("init/json_error.php");

class RPC
{
	private $aryResults = Array();
	
	public function __construct ()
	{
		$aryResults = Array 
		(
			"return" => "false",
			"error" => null
		);		
	}
	
	public function process ( $szJSON, $szService)
	{
		if (! isset ( $szJSON ) )
		{
			$gblError->init ("RPC requires POST", -1, "RPC", "RPC", "");
			$this->setError ();
			return;
		}

		if ($szService == null )
		{
			$gblError->init ("No service provided", -1, "RPC", "RPC", "");
			$this->setError ();
			return;
		}

		// =================================

		$aryPOST = json_decode ($szJSON, true);

		// =================================
		
		$szCommand = (isset ($aryPOST ["szCommand"]) ? $aryPOST ["szCommand"] : "" );
		$szParams = (isset ($aryPOST ["szParams"]) ? $aryPOST ["szParams"] : "" );
		$bVerbose = (isset ($aryPOST ["bVerbose"]) ? $aryPOST ["bVerbose"] : false );

		// =================================
		
		gblTraceLog()->Log ("szCommand", $szCommand);
		gblTraceLog()->Log ("szParams", $szParams);
		
		$this->aryResults ["return"]  = $szService::execute ($szCommand, $szParams, $bVerbose);		
		$this->setError ();
	}
	
	public function setError ()
	{ 
		$this->aryResults ["error"] = gblError()->toArray(); 
		$this->aryResults ["tracelog"] = gblTraceLog()->getLog(); 
	}
	
	public function getResults ()
	{ return ( json_encode ($this->aryResults, JSON_FORCE_OBJECT) ); }	
}

?>
