<?
namespace Tests\Ladder\OSQL\Scanner;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use \ENetArch\Ladder\OSQL\Scanner\Token;

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
  
class test_Token extends \Tests\Test
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
		$this->test_01();
		
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
		$objToken = new Token();
		
		$objToken->code = 123;
		$objToken->value = "Mystery Sex";
				
		$this->assert (($objToken->code == 123), "01 - The token code matches");
		$this->assert (($objToken->value == "Mystery Sex"), "01 - The token value matches");
	}
	
}

test_Token::docFoo();

?>
