<?
namespace Tests\Ladder\OSQL\Scanner;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use \ENetArch\Ladder\OSQL\Scanner\Version;

// ============================================

docFoo ( Array 
	(
	"Class" => "test_Version",
	"Description" => "Test the Version Class",
	"Requirements" => Array 
		(
			"Store the current code version", 
			"Store the date the code was compiled last", 
			"Store who compiled the code last", 
		),
) );

// ============================================

class test_Version extends \Tests\Test
{
	protected $nLoops = 1;

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
		$objVersion = new Version();

		$this->assert (($objVersion->Version() == "1.2.3"), "The version matches");
		$this->assert (($objVersion->Compiled() == "2004-08-03 - 2:14 pm"), "The compiled date matches");
		$this->assert (($objVersion->CompiledBy() == "Michael J. Fuhrman"), "The author matches");
		
		return (true);
	}
	
}

test_Version::docFoo ();

?>
