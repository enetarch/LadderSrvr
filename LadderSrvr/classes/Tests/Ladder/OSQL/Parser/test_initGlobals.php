<?
namespace Tests\Ladder\OSQL\Parser;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

include_once ("classes/ENetArch/Ladder/OSQL/Parser/init_Globals.php");

// ============================================

docFoo ( Array 
(
	"Class" => "test_initGlobals",
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
  
class test_initGlobals extends \Tests\Test
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
		
		self::docFoo_test_01 ();

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

	function run () 
	{
		$this->test_01 ();
		return (true);
	}
	
	// ============================================
	
	public static function docFoo_test_01 ()
	{ docFoo ( Array 
		(
			"Method" => "test_01",
			"Description" => Array 
			(
				"Confirm globals initialized correctly",
				"Confirm that the funtions return the designated objects",
			)
		 ) );
	}
	
	function test_01 ()
	{
		gblError()->Clear();

		$objParams = gblParams();
		$this->assert (($objParams != null), "01 - the Parameters instance is initialized");	
		
		$gblRsvrWords = gblRsvrWords();
		$this->assert (($gblRsvrWords != null), "01 - the ReservedWords instance is initialized");	
		
		$objScanner = gblScanner();
		$this->assert (($objScanner != null), "01 - the Scanner instance is initialized");	

		$objParser = gblParser();
		$this->assert (($objParser != null), "01 - the Parser instance is initialized");	
	}
	
	
}

test_initGlobals::docFoo();

?>
