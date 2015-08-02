<?
namespace Tests\Ladder\OSQL\Scanner;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use \ENetArch\Ladder\OSQL\Scanner\ErrorMsgs;

// ============================================

docFoo ( Array 
(
	"Class" => "test_ErrorMsgs",
	"Description" => "Insure that the error messages compile correctly",
	"Requirements" => Array 
		(
			"Confirm the class initializes and the message can be retrieved",
		),
	"Include" => Array 
		(
			"\Tests\Tests",
			"\Tests\Test",
			"\ENetArch\Ladder\OSQL\Scanner\ErrorMsgs",
		),
	"Globals" => Array 
		(
			"docFoo_report - determines if the document should be generated"
		),
	"Constants" => Array 
		(
			"nLoops - The number of times thiis series of tests should be run"
		),
	"Methods" => Array
		(
			"Test_001 - confirm that the error message compile correctly",
		),
) );
  
class test_ErrorMsgs extends \Tests\Test
{
	protected $nLoops = 1;
	
	// ============================================
	
	public static function docFoo_docFoo ()
	{ docFoo ( Array 
		(
			"Method" => "docFoo",
			"Syntax" => "docFoo ()",
			"Description" => "Generates the documentation for this clas",
			"Globals" => Array 
				(
					"docFoo_report - determines if the document should be generated"
				),
			"Core Code" => 
					"Call self documenting methods within the class",		
		) );
	}

	public static function docFoo ()
	{
		if (!DocFoo::cReport) return;
		
		self::docFoo_setup ();
		self::docFoo_run ();
		self::docFoo_teardown ();

	}
	
	// ============================================
	
	public static function docFoo_setup ()
	{ docFoo ( Array 
		(
		"Method" => "setup",
		"Description" => "prepare tests to be run in an existing environment",
	 ) );
	}

	// function setup() {}
	
	// ============================================
	public static function docFoo_teardown ()
	{ docFoo ( Array 
		(
		"Method" => "teardown",
		"Description" => "deconstruct the environment created to run tests in.",
	 ) );
	}

	// function teardown() {}
	
	// ============================================
	public static function docFoo_run ()
	{ docFoo ( Array 
		(
		"Method" => "run",
		"Description" => "run full regression tests on the target object",
	 ) );
	}

	function run () { return (true); }
	
	
}

test_ErrorMsgs::docFoo();

?>
