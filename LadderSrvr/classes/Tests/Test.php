<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace Tests;

class Test
{
	public $tests = null;
	protected $nLoops = 1;
	
	public function __construct ($thsTests)
	{ $this->tests = $thsTests; }
	
	public function setup () { return (true); }
	// override this function

	public function run () {}
	// override this function

	public function teardown () { return (true); }
	// override this function
	
	public function assert ($tf, $szLog)
	{ $this->tests->log ((($tf) ? "Succeeded" : "Failed") . " - " . $szLog); }

	public function log ($szName, $szLog)
	{ $this->tests->log ($szName . " - " . $szLog); }
	
	function logError (\ENetArch\Common\Error $msg)
	{ $this->tests->log ("ErrorMsg - <BR>" . $msg->No() . " - " . $msg->Description() .  ' - ' .  $msg->SQL()  . "<p>Call Path:<BR>" . $msg->CallPath()."<p>" ); }
	
	// function logTrace (\ENetArch\Common\TraceLog $log)
	function logTrace ($log)
	{ $this->tests->log ("TraceLog", "<BR>\r\n" . implode ("<BR>\r\n", $log ) ); }
}
?>
