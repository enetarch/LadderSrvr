<?
namespace Tests\Ladder\OSQL\Scanner;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use \ENetArch\Ladder\OSQL\Scanner\ReservedWord;
use \ENetArch\Ladder\OSQL\Scanner\ReservedWords;


// ============================================

docFoo ( Array 
(
	"Class" => "test_ReservedWords",
	"Description" => "Test the Reserved Words class ",
	"Requirements" => Array 
		(
			"Add a keyword / code pair to the container",
			"Find a keyword in the container",
			"Confirm that an empty container doesn't throw an error",
			"Confirm a keyword not in the container doesn't throw an error ",
			"Confirm a blank keyword returns an error",
			"Retrieve a keyword in the container bounds",
			"Retrieve a keyword outside teh container bounds",
			"Delete a keyword in the container bounds",
			"Delete a keyword outside teh container bounds",
			"Delete a keyword from an empty container",
			"Confirm duplicate token causes error to arise",
			"Confirm an error arrises when a word is 0 length is passed",
			"Confirm an error arrises when a word of spaces is passed ",
			"Confirm an error arrises when 0 value token is passed",
			"Confirm the count matches the number of instances created",
			"Confirm a Reserved Word is found",
			"Confirm a Reserved Word is not found",
			"Confirm duplicate keyword are not allowed",
		),
	"Include" => Array 
		(
			"\Tests\Tests",
			"\Tests\Test",
			"\ENetArch\Ladder\OSQL\Scanner\ReservedWord",
			"\ENetArch\Ladder\OSQL\Scanner\ReservedWords",
		),
	"Globals" => Array 
		(
			"docFoo_report - determines if the document should be generated"
		),
	"Constants" => Array 
		(
			"nLoops - The number of times thiis series of tests should be run",
		),
	"Variables" => Array
		(
			"aryReservedWords - A String Array of Keywords and TokenCode Values",
			"aryWordList2 - list of keywords not in keyword list",
		),	
	"Methods" => Array
		(
			"test_01 - Add a keyword / code pair to the container",
			"test_02 - Find a keyword in the container",
			"test_03 - Retrieve a keyword in the container bounds",
			"test_04 - Confirm that an empty container doesn't throw an error",
			"test_05 - Confirm a keyword not in the container doesn't throw an error ",
			"test_06 - Confirm a blank keyword returns an error",
			"test_07 - Retrieve a keyword outside teh container bounds",
			"test_08 - Delete a keyword in the container bounds",
			"test_09 - Delete a keyword outside teh container bounds",
			"test_10 - Delete a keyword from an empty container",
			"test_11 - Confirm duplicate token causes error to arise",
			"test_12 - Confirm an error arrises when a word is 0 length is passed",
			"test_13 - Confirm an error arrises when a word of spaces is passed ",
			"test_14 - Confirm an error arrises when 0 value token is passed",
			"test_15 - Confirm the count matches the number of instances created",
			"test_16 - Confirm a Reserved Word is found",
			"test_17 - Confirm a Reserved Word is not found",
			"test_18 - Confirm duplicate keyword are not allowed",
		),
		
		"Comment" => Array 
			(
				"Tests confirming boundaries for adding a keyword and token code to the 
				container, are duplicated in the test_ReservedWord ",
			),
) );
  
class test_ReservedWords extends \Tests\Test
{
	protected $nLoops = 1;
	protected $aryReservedWords = Array
	(
		"in" => 2,
		"and" => 3,
		"like" => 4,
		"class" => 5,
		"select" => 6,
		"classes" => 7,
		"subclass" => 8,
		"templates" => 9,
		"identifier" => 10,
		"datecreated" => 11,
		"uidcreated" => 12,
		"ipcreated" => 13,
	);

	protected $aryWordList2 = Array
	(
		"tcUPARROW" => 2,
		"tcASTERICK" => 3,
		"tcPLUS" => 4,
		"tcRPAREN" => 5,
		"tcLT" => 6,
		"tcPERIOD" => 7,
		"tcSLASH" => 8,
		"tcGE" => 9,
		"tcSEMICOLON" => 10,
		"tcNUMBER" => 11,
		"tcIDENTIFIER" => 12,
		"tcEOF" => 13,
	);

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
		self::docFoo_test_10 ();
		self::docFoo_test_11 ();
		self::docFoo_test_12 ();
		self::docFoo_test_13 ();
		self::docFoo_test_14 ();
		self::docFoo_test_15 ();
		self::docFoo_test_16 ();
		self::docFoo_test_17 ();
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
		$this->test_10();
		$this->test_11();
		$this->test_12();
		$this->test_13();
		$this->test_14();
		$this->test_15();
		$this->test_16();
		$this->test_17();
		
		return (true);
	}
	
	
	
	// ============================================
	
	public static function docFoo_test_01 ()
	{ docFoo ( Array 
		(
			"Method" => "test_01",
			"Description" => Array
			(
				"Add a keyword / code pair to the container",
				"Confirm the count matches the number of instances created",
			),
		 ) );
	}
	
	function test_01 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			$bStatus |= (gblError()->No() == 0);
			
			if (gblError()->No() != 0)
				$this->assert (false, "01 - initializing RW with a keyword succeeded");
		}
		
		$this->assert ($bStatus, "01 - initializing RW with keywords succeeded");	
		
		$nLen = count ($this->aryReservedWords);
		$nCount = $objRWs->Count(); 
		$this->assert (($nCount == $nLen), "01 - the number of reserved words match ($nLen == $nCount)");	
	}
	
	// ============================================
	
	public static function docFoo_test_02 ()
	{ docFoo ( Array 
		(
			"Method" => "test_02",
			"Description" => "Find a keyword in the container",
		 ) );
	}
	
	function test_02 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "02 - initializing RW with a keyword succeeded");
		}
		
		// Test
		$bStatus = true;
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRW = $objRWs->Find ($key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "02 - Find RW with a keyword succeeded");

			$bStatus &= (isset ($objRW) ? $objRW->Word() == $key : false);
		}
		
		$nCount = $objRWs->Count(); 
		
		$this->assert ($bStatus, "02 - All [$nCount] Stored Keywords were found");	
	}
	
	// ============================================
	
	public static function docFoo_test_03 ()
	{ docFoo ( Array 
		(
			"Method" => "test_03",
			"Description" => "Retrieve a keyword in the container bounds",
		) );
	}
	
	function test_03 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "03 - initializing RW with a keyword succeeded");
		}
		
		// Test
		$nCount = $objRWs->Count();
		
		$bStatus = true;
		For ($t=0; $t<$nCount; $t++)
		{
			gblError()->Clear();
			$objRW = $objRWs->item ($t);
			
			if (gblError()->No() != 0)
				$this->assert (false, "03 - GET keyword [$t] succeeded");

			if ($objRW == null)
			{
				$this->log ("", "03 - $t returned NULL instead of [" . $this->aryReservedWords[$t] . "]");
				return;
			}
			
			$bStatus &= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
		}
	
		$this->assert ($bStatus, "03 - All [$nCount] Keywords were retrieved");	
	}
	
	// ============================================
	
	public static function docFoo_test_04 ()
	{ docFoo ( Array 
		(
			"Method" => "test_04",
			"Description" => "Confirm a keyword not in the container does not throw an error ",
			"Core Code" => 
				"	Create Reserved Word Container
					Initialize the container with aryReservedWords 
					Retrieve words from the container using aryWordList2
					Confirm that no error is thrown
				",
		) );
	}

	function test_04 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "04 - initializing RW with a keyword succeeded");
		}
		
		// Test
		$nCount = count ($this->aryWordList2);
		
		$bStatus = False;
		ForEach ($this->aryWordList2 AS $key => $val)
		{
			gblError()->Clear();
			$objRW = $objRWs->Find ($key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "04 - GET keyword [$key] failed");

			if ($objRW != null)
			{
				$this->log ("", "04 - $key returned INSTANCE instead of [" . $this->aryWordList2 [$key] . "]");
				return;
			}
			
			$bStatus |= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
		}
	
		$this->assert (!$bStatus, "04 - All [$nCount] Non Matching Keywords were not found");	
	}
	
	// ============================================
	
	public static function docFoo_test_05 ()
	{ docFoo ( Array 
		(
			"Method" => "test_05",
			"Description" => "Confirm that an empty container doesn't throw an error",
			"Core Code" => 
				"	Create an empty Reserved Word Container
					Retrieve words from the container using aryWordList2
					Confirm that no error is thrown
				",
		) );
	}

	function test_05 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Test
		$nCount = count ($this->aryWordList2);
		
		$bStatus = False;
		ForEach ($this->aryWordList2 AS $key => $val)
		{
			gblError()->Clear();
			$objRW = $objRWs->Find ($key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "05 - GET keyword [$key] failed");

			if ($objRW != null)
			{
				$this->log ("", "05 - $key returned INSTANCE instead of [" . $this->aryWordList2 [$key] . "]");
				return;
			}
			
			$bStatus |= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
			$bStatus |= (gblError()->No() != 0);
		}
	
		$this->assert (!$bStatus, "05 - All [$nCount] Non Matching Keywords were not found in the empyt container");	
	}
	
	// ============================================
	
	public static function docFoo_test_06 ()
	{ docFoo ( Array 
		(
			"Method" => "test_06",
			"Description" => "Confirm a blank keyword returns an error",
			"Core Code" => 
				"	Create Reserved Word Container
					Initialize the container with aryReservedWords 
					Retrieve a blank or all space word from the container using aryWordList2
					Confirm that an error is thrown
				",
		) );
	}

	function test_06 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "06 - initializing RW with a keyword succeeded");
		}
		
		// Test
		
		$bStatus = False;
		
		// =====
		
		gblError()->Clear();
		$key = "";
		$objRW = $objRWs->Find ($key);
		if (gblError()->No() == 0)
			$this->assert (false, "06 - GET keyword [$key] succeeded");

		if ($objRW != null)
			$this->log ("", "06 - $key returned INSTANCE instead of [" . $this->aryWordList2 [$key] . "]");
			
		$bStatus |= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
		$bStatus |= (gblError()->No() == 0);
		
		// =====
		
		gblError()->Clear();
		$key = "   ";
		$objRW = $objRWs->Find ($key);
		if (gblError()->No() == 0)
			$this->assert (false, "06 - GET keyword [$key] succeeded");

		if ($objRW != null)
			$this->log ("", "06 - $key returned INSTANCE instead of [" . $this->aryWordList2 [$key] . "]");
			
		$bStatus |= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
		$bStatus |= (gblError()->No() == 0);
		
		// =====
		
		$this->assert (!$bStatus, "06 - The search for a blank or space Keywords failed");	
	}
	
	// ============================================
	
	public static function docFoo_test_07 ()
	{ docFoo ( Array 
		(
			"Method" => "test_07",
			"Description" => "Retrieve a keyword outside the container bounds",
		 ) );
	}
	
	function test_07 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "07 - initializing RW with a keyword succeeded");
		}
		
		// Test
		
		$nCount = $objRWs->Count();
		$bStatus = False;
		
		// =====
		
		gblError()->Clear();
		$objRW = $objRWs->item (-1);
		if (gblError()->No() == 0)
			$this->assert (false, "07 - GET keyword [$key] succeeded");

		if ($objRW != null)
			$this->log ("", "07 - item (-1) returned INSTANCE instead of [ NULL ]");
			
		$bStatus |= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
		$bStatus |= (gblError()->No() == 0);
		
		// =====
		
		gblError()->Clear();
		$key = "   ";
		$objRW = $objRWs->item ($nCount+1);
		if (gblError()->No() == 0)
			$this->assert (false, "07 - GET keyword [$key] succeeded");

		if ($objRW != null)
			$this->log ("", "07 - item ($nCount+1) returned INSTANCE instead of [ NULL ]");
			
		$bStatus |= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
		$bStatus |= (gblError()->No() == 0);
		
		// =====
		
		$this->assert (!$bStatus, "07 - Retrieval of Keywords outside the bounds failed");	
	}

	// ============================================
	
	public static function docFoo_test_08 ()
	{ docFoo ( Array 
		(
			"Method" => "test_08",
			"Description" => "Delete a keyword in the container bounds",
			"Core Code" => "",
		 ) );
	}
	
	function test_08 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "08 - initializing RW with a keyword succeeded");
		}
		
		// Test
		
		$nCount1 = $objRWs->Count();
		$this->log ("nCount1", "08 - There [$nCount1] Keywords in the container");	
		
		// =====
		
		gblError()->Clear();
		$objRWs->Delete ($nCount1-1);
		$this->assert ((gblError()->No() == 0), "08 - DELETE - gblError->No == 0 is TRUE");

		$bStatus = (gblError()->No() == 0);
		$nCount2 = $objRWs->Count();
		
		$this->log ("nCount2", "08 - There [$nCount2] Keywords in the container");	
		
		$this->assert (($nCount1 ==  $nCount2+1), "08 - A keyword was successfully removed from the container");	

		// =====
		
		gblError()->Clear();
		$objRWs->Delete (1);
		$this->assert ((gblError()->No() == 0), "08 - DELETE - gblError->No == 0 is TRUE");

		$bStatus = (gblError()->No() == 0);
		$nCount3 = $objRWs->Count();
		
		$this->log ("nCount3", "08 - There [$nCount3] Keywords in the container");	
		
		$this->assert (($nCount1 ==  $nCount3+2), "08 - A keyword was successfully removed from the container");	
	}

	// ============================================
	
	public static function docFoo_test_09 ()
	{ docFoo ( Array 
		(
			"Method" => "test_09",
			"Description" => "Delete a keyword outside the container bounds",
		 ) );
	}
	
	function test_09 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "09 - initializing RW with a keyword succeeded");
		}
		
		// Test
		
		$nCount = $objRWs->Count();
		$bStatus = False;
		
		// =====
		
		gblError()->Clear();
		$objRW = $objRWs->Delete (-1);
		if (gblError()->No() == 0)
			$this->assert (false, "09 - GET keyword [$key] succeeded");

		if ($objRW != null)
			$this->log ("", "09 - item (-1) returned INSTANCE instead of [ NULL ]");
			
		$bStatus |= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
		$bStatus |= (gblError()->No() == 0);
		
		// =====
		
		gblError()->Clear();
		$key = "   ";
		$objRW = $objRWs->Delete ($nCount+1);
		if (gblError()->No() == 0)
			$this->assert (false, "09 - GET keyword [$key] succeeded");

		if ($objRW != null)
			$this->log ("", "09 - item ($nCount+1) returned INSTANCE instead of [ NULL ]");
			
		$bStatus |= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
		$bStatus |= (gblError()->No() == 0);
		
		// =====
		
		$this->assert (!$bStatus, "09 - Deletion of Keywords outside the bounds failed");	
	}

	// ============================================
	
	public static function docFoo_test_10 ()
	{ docFoo ( Array 
		(
			"Method" => "test_10",
			"Description" => "Delete a keyword from an empty container",
		) );
	}

	function test_10 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Test
		
		$nCount = $objRWs->Count();
		$bStatus = False;
		
		// =====
		
		gblError()->Clear();
		$objRW = $objRWs->Delete (0);
		if (gblError()->No() == 0)
			$this->assert (false, "10 - GET keyword [$key] succeeded");

		if ($objRW != null)
			$this->log ("", "10 - item (-1) returned INSTANCE instead of [ NULL ]");
			
		$bStatus |= (isset ($objRW) ? isset ($this->aryReservedWords [$objRW->Word()])  : false);
		$bStatus |= (gblError()->No() == 0);
		
		// =====
		
		$this->assert (!$bStatus, "10 - Deletion of Keywords on empty container failed");	
	}
	
	// ============================================
	
	public static function docFoo_test_11 ()
	{ docFoo ( Array 
		(
			"Method" => "test_11",
			"Description" => "Confirm duplicate token causes error to arise",
		) );
	}

	function test_11 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "11 - initializing RW with a keyword succeeded");
		}
		
		// Test
		
		$nCount = $objRWs->Count();
		$bStatus = False;
		
		// =====
		
		gblError()->Clear();
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRw = $objRWs->AddItem ($key, $val);
			
			if (gblError()->No() == 0)
				$this->assert (false, "11 - ADD keyword [$key] succeeded");

			if ($objRw != null)
				$this->log ("", "11 - Keyword [ $key ] returned INSTANCE instead of [ NULL ]");
				
			$bStatus |= isset ($objRw);
			$bStatus |= (gblError()->No() == 0);
		}
		
		// =====
		
		$this->assert (!$bStatus, "11 - Add duplicate Keywords outside the bounds failed");	
	}
	
	// ============================================
	
	public static function docFoo_test_12 ()
	{ docFoo ( Array 
		(
			"Method" => "test_12",
			"Description" => "Confirm an error arrises when a word is 0 length is passed",
		) );
	}

	function test_12 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ("", $val);
			$bStatus |= (gblError()->No() != 0);
			
			if (gblError()->No() == 0)
				$this->assert (false, "12 - initializing RW with a 0 length keyword succeeded");
		}
		
		$this->assert ($bStatus, "12 - initializing RW with blank keywords failed");	
		
		$nLen = count ($this->aryReservedWords);
		$nCount = $objRWs->Count(); 
		$this->assert (($nCount == 0), "12 - the number of reserved words match (0 == $nCount)");	
	}
	
	// ============================================
	
	public static function docFoo_test_13 ()
	{ docFoo ( Array 
		(
			"Method" => "test_13",
			"Description" => "Confirm an error arrises when a word of spaces is passed ",
		) );
	}

	function test_13 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ("   ", $val);
			$bStatus |= (gblError()->No() != 0);
			
			if (gblError()->No() == 0)
				$this->assert (false, "13 - initializing RW with a x length space keyword succeeded");
		}
		
		$this->assert ($bStatus, "13 - initializing RW with space keywords failed");	
		
		$nLen = count ($this->aryReservedWords);
		$nCount = $objRWs->Count(); 
		$this->assert (($nCount == 0), "13 - the number of reserved words match (0 == $nCount)");	
	}
	
	// ============================================
	
	public static function docFoo_test_14 ()
	{ docFoo ( Array 
		(
			"Method" => "test_14",
			"Description" => "Confirm an error arrises when 0 value token is passed",
		) );
	}

	function test_14 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, 0);
			$bStatus |= (gblError()->No() != 0);
			
			if (gblError()->No() == 0)
				$this->assert (false, "14 - initializing RW with a 0 value token succeeded");
		}
		
		$this->assert ($bStatus, "14 - initializing RW with 0 value token failed");	
		
		$nLen = count ($this->aryReservedWords);
		$nCount = $objRWs->Count(); 
		$this->assert (($nCount == 0), "14 - the number of reserved words match (0 == $nCount)");	
	}
	
	// ============================================
	
	public static function docFoo_test_15 ()
	{ docFoo ( Array 
		(
			"Method" => "test_15",
			"Description" => "Confirm a Reserved Word is found",
		) );
	}

	function test_15 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "15 - initializing RW with a keyword succeeded");
		}
		
		// Test
		$bStatus = true;
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$bStatus &= $objRWs->isFound ($key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "15 - Found a RW with a keyword failed");
		}
		
		$nCount = $objRWs->Count(); 
		
		$this->assert ($bStatus, "15 - All [$nCount] Stored Keywords were found");	
	}
	
	// ============================================
	
	public static function docFoo_test_16 ()
	{ docFoo ( Array 
		(
			"Method" => "test_16",
			"Description" => "Confirm an unregistered Reserved Word is not found",
		) );
	}

	function test_16 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "16 - initializing RW with a keyword succeeded");
		}
		
		// Test
		$bStatus = false;
		ForEach ($this->aryWordList2 AS $key => $val)
		{
			gblError()->Clear();
			$bStatus |= $objRWs->isFound ($key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "16 - Found a RW with a keyword succeeded");
		}
		
		$nCount = $objRWs->Count(); 
		
		$this->assert (!$bStatus, "16 - All [$nCount] Stored Keywords were not found");	
	}
	
	public static function docFoo_test_17 ()
	{ docFoo ( Array 
		(
			"Method" => "test_17",
			"Description" => "Confirm duplicate Reserved Words are not allowed",
		) );
	}

	function test_17 ()
	{
		gblError()->Clear();
		$objRWs = new ReservedWords();
		
		// Setup
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "17 - initializing RW with a keyword succeeded");
		}
		
		// Test
		$bStatus = false;
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$objRWs->AddItem ($key, $val);
			
			$bStatus |= (gblError()->No() == 0);
			
			if (gblError()->No() == 0)
				$this->assert (false, "17 - A duplicate word was added");
		}
		
		$nCount = $objRWs->Count(); 
		
		$this->log ("nCount", "17 - There [$nCount] Keywords in the container");	
		$this->assert (!$bStatus, "17 - No duplicate keywords were added to the container");	
	}
	
}

test_ReservedWords::docFoo();

?>
