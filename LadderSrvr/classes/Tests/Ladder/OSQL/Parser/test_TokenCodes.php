<?
namespace Tests\Ladder\OSQL\Parser;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use \ENetArch\Ladder\OSQL\Parser\TokenCodes;
use \ENetArch\Ladder\OSQL\Scanner\ByteCode;

// ============================================

docFoo ( Array 
	(
	"Class" => "test_001",
	"Description" => "Test the Version Class",
	"Requirements" => Array 
		(
			"Store byte codes for later retrieval - (1, 2, 3, 4, 5, 6, 7)",
			"return position of added code - (4)",
			"backup / remove last code entered - (7)",
			"updated a code as position x - (5)",
			"retrieve code at position x - (2, 4)",
			"return the number of codes stored in the object - (1, 2, 3, 4, 5, 6, 7)",
			"return a space seperated string of codes - (8)",
			"clear the codes from the object - (6)",
		),
) );


// ============================================

docFoo ( Array 
(
	"Class" => "test_002",
	"Description" => "Test the ByteCode class ",
	"Include" => Array 
		(
			"\Tests\Tests",
			"\Tests\Test",
			"\ENetArch\Ladder\OSQL\Scanner\TokenCodes",
			"\ENetArch\Ladder\OSQL\Scanner\ByteCode",
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
			"test_01 - Test storage of byte codes for later retrieval",
			"test_02 - Add a random number of codes match their random values",
			"test_03 - Test boundaries when retrieving byte codes.",
			"test_04 - Return byte codes from position of added code",
			"test_05 - Test that codes can be updated at position x",
			"test_06 - Test that the object can be cleared of all byte codes",
			"test_07 - Test that codes can be remove from the end of the end ",
			"test_08 - Test that a space seperated string of codes is returned",
			"test_09 - Test boundaries when updating byte codes.",
		),
) );
  
class test_TokenCodes extends \Tests\Test
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
		self::docFoo_test_03 ();
		self::docFoo_test_04 ();
		self::docFoo_test_05 ();
		self::docFoo_test_06 ();
		self::docFoo_test_07 ();
		self::docFoo_test_08 ();
		self::docFoo_test_09 ();
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
		$this->test_03();
		$this->test_04();
		$this->test_05();
		$this->test_06();
		$this->test_07();
		$this->test_08();
		$this->test_09();
	}
	
	// ============================================
	
	public static function docFoo_test_01 ()
	{ docFoo ( Array 
		(
			"Method" => "test_01",
			"Description" => "Add a random number of codes and confirm the length",
		 ) );
	}
	
	function test_01 ()
	{
		$objBC = new ByteCode();
		
		$nLen = rand (1, 1000);
		for ($t=0; $t<$nLen; $t++)
			$objBC->AddItem (rand (1, 1000));
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "01 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");

	}
	
	// ============================================
	
	public static function docFoo_test_02 ()
	{ docFoo ( Array 
		(
			"Method" => "test_02",
			"Description" => "Add a random number of codes match their random values",
		) );
	}

	function test_02 ()
	{
		$objBC = new ByteCode();
		
		$nLen = rand (1, 1000);
		$aryValues = Array();
		for ($t=0; $t<$nLen; $t++)
		{
			$val = rand (1, 1000);
			$aryValues [] = $val;
			$objBC->AddItem ($val);
		}
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "02 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");

		$bMatch = True;
		for ($t=0; $t<$nCount; $t++)
		{
			$val1 = $aryValues [$t];
			$val2 = $objBC->code ($t);
			$bMatch = ($val1 == $val2) && $bMatch;
		}

		$this->assert ($bMatch, "02 - The codes matched");
	}
	
	// ============================================
	
	public static function docFoo_test_03 ()
	{ docFoo ( Array 
		(
			"Method" => "test_03",
			"Description" => "Out of Range Error when retrieving an bytecode should return -1",
		) );
	}

	function test_03 ()
	{
		$objBC = new ByteCode();
		
		$nLen = rand (1, 1000);
		for ($t=0; $t<$nLen; $t++)
		{
			$val = rand (1, 1000);
			$objBC->AddItem ($val);
		}
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "03 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");

		$rtn = $objBC->code (-1);
		$this->assert ((gblError()->No() != 0), "03 - [-1] is out of range and returned " . $rtn);
		$this->assert (($rtn === -1), "03 - The return value is -1 ");

		$rtn = $objBC->code ($nCount);
		$this->assert ((gblError()->No() != 0), "03 - [$nCount] is out of range and returned " . $rtn);
		$this->assert (($rtn === -1), "03 - The return value is -1 ");
	}
	
	// ============================================
	
	public static function docFoo_test_04 ()
	{ docFoo ( Array 
		(
			"Method" => "test_05",
			"Description" => "added codes are as position x ",
		) );
	}
	
	function test_04 ()
	{
		$objBC = new ByteCode();
		
		$nLen = rand (1, 1000);
		$bMatch = True;
		for ($t=0; $t<$nLen; $t++)
		{
			$val1 = rand (1, 1000);
			$nPos = $objBC->AddItem ($val1);
			$val2 = $objBC->code ($nPos);
			$bMatch = ($val1 == $val2) && $bMatch;
		}
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "04 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");

		$this->assert ($bMatch, "04 - The codes at position (x) matched");
	}
	
	// ============================================
	
	public static function docFoo_test_05 ()
	{ docFoo ( Array 
		(
			"Method" => "test_05",
			"Description" => "update the codes stored in the object",
		 ) );
	}
	
	function test_05 ()
	{
		$objBC = new ByteCode();
		
		$nLen = rand (1, 1000);
		$aryValues = Array();
		for ($t=0; $t<$nLen; $t++)
		{
			$val1 = rand (1, 1000);
			$objBC->AddItem ($val1);
		}
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "05 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");

		for ($t=0; $t<$nLen; $t++)
		{
			$val2 = rand (1, 1000);
			$objBC->Update ($t, $val2);
			$aryValues [] = $val2;
		}

		$bMatch = True;
		for ($t=0; $t<$nCount; $t++)
		{
			$val1 = $aryValues [$t];
			$val2 = $objBC->code ($t);
			$bMatch = ($val1 == $val2) && $bMatch;
		}

		$this->assert ($bMatch, "05 - The codes matched");
	}

	// ============================================
	
	public static function docFoo_test_06 ()
	{ docFoo ( Array 
		(
			"Method" => "test_06",
			"Description" => "Clear the codes",
		 ) );
	}
	
	function test_06 ()
	{
		$objBC = new ByteCode();
		
		$nLen = rand (1, 1000);
		$aryValues = Array();
		for ($t=0; $t<$nLen; $t++)
		{
			$val1 = rand (1, 1000);
			$val2 = rand (1, 1000);
			$objBC->AddItem ($val1);
			$aryValues [] = $val2;
		}
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "06 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");

		$objBC->clear();
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == 0), "06 - The length of the ByteCode should be ( 0 ) it is ( $nCount )");
	}

	// ============================================
	
	public static function docFoo_test_07 ()
	{ docFoo ( Array 
		(
			"Method" => "test_07",
			"Description" => "Clear the codes",
		 ) );
	}
	
	function test_07 ()
	{
		$objBC = new ByteCode();
		
		$nLen = rand (1, 1000);
		$aryValues = Array();
		for ($t=0; $t<$nLen; $t++)
		{
			$val1 = rand (1, 1000);
			$val2 = rand (1, 1000);
			$objBC->AddItem ($val1);
			$aryValues [] = $val2;
		}
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "07 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");
		$nBackup = rand (1, 100);
		$nLen -= $nBackup;
		
		for ($t=0; $t<$nBackup; $t++)
		{	
			$objBC->Backup ();
		}
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "07 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");
	}

	// ============================================
	
	public static function docFoo_test_08 ()
	{ docFoo ( Array 
		(
			"Method" => "test_08",
			"Description" => "return a comma string of codes",
		 ) );
	}
	
	function test_08 ()
	{
		$objBC = new ByteCode();
		
		$nLen = 10; rand (1, 1000);
		$aryValues = Array();
		$szCodes1 = "";
		for ($t=0; $t<$nLen; $t++)
		{
			$val1 = rand (1, 1000);
			$objBC->AddItem ($val1);
			$szCodes1 .= $val1 . " ";
		}
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "08 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");

		$szCodes2 = $objBC->toString();
		
		$this->log ("szCodes1", "[" . $szCodes1 . "]");
		$this->log ("szCodes2", "[" . $szCodes2 . "]");
		
		$nLen1 = strlen ($szCodes1);
		$nLen2 = strlen ($szCodes2);
		
		$this->assert (($nLen1 == $nLen2), "08 - The byte code string length's ($nLen1) == ($nLen2) match");
		$this->assert (($szCodes1 == $szCodes2), "08 - The byte code strings match");
	}

	// ============================================
	
	public static function docFoo_test_09 ()
	{ docFoo ( Array 
		(
			"Method" => "test_09",
			"Description" => "Out of Range Error when updating an bytecode should return -1",
		) );
	}

	function test_09 ()
	{
		$objBC = new ByteCode();
		
		$nLen = rand (1, 1000);
		for ($t=0; $t<$nLen; $t++)
		{
			$val = rand (1, 1000);
			$objBC->AddItem ($val);
		}
		
		$nCount = $objBC->length();
		
		$this->assert (($nCount == $nLen), "09 - The length of the ByteCode should be ( $nLen ) it is ( $nCount )");

		$rtn = $objBC->update (-1, 0);
		$this->assert ((gblError()->No() != 0), "09 - [-1] is out of range and returned " . $rtn);
		$this->assert (($rtn === -1), "09 - The return value is -1 ");

		$rtn = $objBC->update ($nCount, 0);
		$this->assert ((gblError()->No() != 0), "09 - [$nCount] is out of range and returned " . $rtn);
		$this->assert (($rtn === -1), "09 - The return value is -1 ");
	}
	

}

test_TokenCodes::docFoo();

?>
