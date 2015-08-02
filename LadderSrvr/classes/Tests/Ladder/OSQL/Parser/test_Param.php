<?
namespace Tests\Ladder\OSQL\Parser;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use \ENetArch\Ladder\OSQL\Scanner\LiteralType;
use \ENetArch\Ladder\OSQL\Parser\Param;

// ============================================

docFoo ( Array 
(
	"Class" => "test_Token",
	"Description" => "Test the Token class ",
	"Requirements" => Array 
		(
			"Store byte codes for later retrieval", 
			"Store a string value for later retrieval", 
		),
	"Include" => Array 
		(
			"\Tests\Tests",
			"\Tests\Test",
			"\ENetArch\Ladder\OSQL\Scanner\Token",
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
			"Confirm byte code stored matches later",
			"Confirm the value stored matches later",
		),
) );
  
class test_Param extends \Tests\Test
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
		self::docFoo_test_02 ();
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
		$this->test_01();
		$this->test_02();
		
		return (true);
	}
	
	// ============================================
	
	public static function docFoo_test_01 ()
	{ docFoo ( Array 
		(
			"Method" => "test_01",
			"Description" => "Confirm byte code and value stored matches later",
		 ) );
	}
	
	function test_01 ()
	{
		$objParam = new Param();
		
		$objParam->szClass = "Ladder_Folder";
		$objParam->szAlias = "a001";
		$objParam->szField = "Name";
		$objParam->nType = LiteralType::cSTRING;
		$objParam->szParams = "ASDF";
	
				
		$this->assert (($objParam->szClass == "Ladder_Folder"), "01 - The class matches");
		$this->assert (($objParam->szAlias == "a001"), "01 - The alias matches");
		$this->assert (($objParam->szField == "Name"), "01 - The field matches");
		$this->assert (($objParam->nType == LiteralType::cSTRING), "01 - The type matches");
		$this->assert (($objParam->szParams == "ASDF"), "01 - The token code matches");
	}
	
	// ============================================
	
	public static function docFoo_test_02 ()
	{ docFoo ( Array 
		(
			"Method" => "test_02",
			"Description" => "Confirm byte code and value stored matches later",
		 ) );
	}
	function test_02 ()
	{
		$objParam = new Param();

		$objParam->init ("Ladder_Folder", "a001", "Name", LiteralType::cSTRING, "ASDF");

		$this->assert (($objParam->szClass == "Ladder_Folder"), "02 - The class matches");
		$this->assert (($objParam->szAlias == "a001"), "02 - The alias matches");
		$this->assert (($objParam->szField == "Name"), "02 - The field matches");
		$this->assert (($objParam->nType == LiteralType::cSTRING), "02 - The type matches");
		$this->assert (($objParam->szParams == "ASDF"), "02 - The token code matches");
	}
	
}

test_Param::docFoo();

?>
