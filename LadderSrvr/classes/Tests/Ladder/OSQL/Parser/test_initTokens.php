<?
namespace Tests\Ladder\OSQL\Parser;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

include_once ("classes/ENetArch/Ladder/OSQL/Parser/init_Tokens.php");

// ============================================

docFoo ( Array 
(
	"Class" => "test_initTokens",
	"Description" => "Insure that the error messages compile correctly",
	"Requirements" => Array 
		(
			"Confirm the class initializes and the message can be retrieved",
			"Confirm additional runs of initTokens only reinitializes the Reserved Words container",
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
			"Test_01 - confirm that the error message compile correctly",
			"Test_02 - Confirm that initTokens when run 2x only reinitializes the Reserved Words container",
		),
) );
  
class test_initTokens extends \Tests\Test
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

	function run () 
	{
		$this->test_01 ();
		$this->test_02 ();
		
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
		
		$objReservedWords = gblRsvrWords();
		$this->assert (($objReservedWords != null), "01 - the ReservedWords container exists");	

		$objReservedWords->Clear();

		init_tokens ();
		if (gblError ()->No() != 0)
		{
			$this->log ("Error", "initTokens has an error. " . gblError ()->No() . " - " . gblError ()->Description());
			$this->log ("TraceLog", "<BR>" . implode (gblTraceLog ()->getLog (), "<BR>\r\n") );
		}

		$this->assert (($objReservedWords->count() == 82), "01 - the ReservedWord container count matches ( " . $objReservedWords->count() . " == 81 ) ");	
	}
	
	// ============================================
	
	public static function docFoo_test_02 ()
	{ docFoo ( Array 
		(
			"Method" => "test_02",
			"Description" => Array 
			(
				"Confirm that initTokens when run 2x only reinitializes the Reserved Words container",
			)
		 ) );
	}
	
	function test_02 ()
	{
		gblError()->Clear();
		
		$objReservedWords = gblRsvrWords();
		$this->assert (($objReservedWords != null), "02 - the ReservedWords container exists");	

		$objReservedWords->Clear();

		init_tokens ();
		if (gblError ()->No() != 0)
		{
			$this->log ("Error", "initTokens has an error. " . gblError ()->No() . " - " . gblError ()->Description());
			$this->log ("TraceLog", "<BR>" . implode (gblTraceLog ()->getLog (), "<BR>\r\n") );
		}

		$this->assert (($objReservedWords->count() == 82), "02 - the ReservedWord container count matches ( " . $objReservedWords->count() . " == 81 ) ");	

		init_tokens ();
		if (gblError ()->No() != 0)
		{
			$this->log ("Error", "initTokens has an error. " . gblError ()->No() . " - " . gblError ()->Description());
			$this->log ("TraceLog", "<BR>" . implode (gblTraceLog ()->getLog (), "<BR>\r\n") );
		}

		$this->assert (($objReservedWords->count() == 82), "02 - the ReservedWord container count matches ( " . $objReservedWords->count() . " == 81 ) ");	
		$this->assert (true, "02 - the ReservedWord container was reinitialized");	
	}
		
	
}

test_initTokens::docFoo();

?>
