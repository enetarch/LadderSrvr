<?
namespace Tests\Ladder\OSQL\Scanner;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use \ENetArch\Ladder\OSQL\Scanner\ReservedWord;

// ============================================

docFoo ( Array 
(
	"Class" => "test_ReservedWord",
	"Description" => "Test the ByteCode class ",
	"Requirements" => Array 
		(
			"Store the Name and Value of the keyword and token code as pair",
		),
	"Include" => Array 
		(
			"\Tests\Tests",
			"\Tests\Test",
			"\ENetArch\Ladder\OSQL\Scanner\ReservedWord",
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
		),
	"Methods" => Array
		(
			"test_01 - Confirm that no error arrises when parameters are in the bounds",
			"test_02 - Confirm an error arrises when a word is 0 length is passed",
			"test_03 - Confirm an error arrises when a word of spaces is passed ",
			"test_04 - Confirm an error arrises when 0 value token is passed",
			"test_05 - Confirm the Word passed in is the same",
			"test_06 - Confirm the Token passed in is the same",
			"test_07 - Confirm the Word length passed in is the same",
		),
) );
  
class test_ReservedWord extends \Tests\Test
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
		self::docFoo_test_05a ();
		self::docFoo_test_05b ();
		self::docFoo_test_05c ();
		self::docFoo_test_05d ();
		self::docFoo_test_05e ();
		self::docFoo_test_05f ();

		self::docFoo_test_06 ();

		self::docFoo_test_07 ();
		self::docFoo_test_07a ();
		self::docFoo_test_07b ();
		self::docFoo_test_07c ();
		self::docFoo_test_07d ();
		self::docFoo_test_07e ();
		self::docFoo_test_07f ();
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
		$this->test_05a();
		$this->test_05b();
		$this->test_05c();
		$this->test_05d();
		$this->test_05e();
		$this->test_05f();

		$this->test_06();

		$this->test_07();
		$this->test_07a();
		$this->test_07b();
		$this->test_07c();
		$this->test_07d();
		$this->test_07e();
		$this->test_07f();
		
		return (true);
	}
	
	// ============================================
	
	public static function docFoo_test_01 ()
	{ docFoo ( Array 
		(
			"Method" => "test_01",
			"Description" => "Confirm that no error arrises when parameters are in the bounds",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm that no error occured
				Log the results of the test
				",
		 ) );
	}
	
	function test_01 ()
	{
		gblError()->Clear();
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$objRW->init ($key, $val);
			$bStatus |= (gblError()->No() == 0);
			
			if (gblError()->No() != 0)
				$this->assert (false, "01 - initializing RW with a keyword succeeded");
		}
		
		$this->assert ($bStatus, "01 - initializing RW with keywords succeeded");	
	}
	
	// ============================================
	
	public static function docFoo_test_02 ()
	{ docFoo ( Array 
		(
			"Method" => "test_02",
			"Description" => "Confirm an error arrises when a word is 0 length is passed",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Initialize and instance of Reserved Word with a invalid keyword and valid token pair
				Confirm that an error occured
				Log the results of the test
				",
		) );
	}

	function test_02 ()
	{
		gblError()->Clear();
		$objRW = new ReservedWord();
		
		$bStatus = false;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$objRW->init ("", $val);
			$bStatus |= (gblError()->No() == 0);
			
			if (gblError()->No() == 0)
				$this->assert (false, "02 - initializing RW with a 0 length keyword succeeded - " . gblError()->No());
		}
		
		$this->assert (!$bStatus, "02 - initializing RW with a 0 length keyword failed");	
	}
	
	// ============================================
	
	public static function docFoo_test_03 ()
	{ docFoo ( Array 
		(
			"Method" => "test_03",
			"Description" => "Confirm an error arrises when a word of spaces is passed ",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Initialize and instance of Reserved Word with a invalid keyword and valid token pair
				Confirm that an error occured
				Log the results of the test
				",
		) );
	}

	function test_03 ()
	{
		gblError()->Clear();
		$objRW = new ReservedWord();
		
		$bStatus = false;		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$objRW->init ("     ", $val);

			if (gblError()->No() == 0)
				$this->assert (false, "03 - initializing RW with a keyword of spaces succeeded - " . gblError()->No());

			$bStatus |= (gblError()->No() == 0);			
		}
		
		$this->assert (!$bStatus, "03 - initializing RW with a keyword of spaces failed");	
		print ("<p>");
	}
	
	// ============================================
	
	public static function docFoo_test_04 ()
	{ docFoo ( Array 
		(
			"Method" => "test_04",
			"Description" => "Confirm an error arrises when 0 value token is passed",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Initialize and instance of Reserved Word with a valid keyword and invalid token pair
				Confirm that an error occured
				Log the results of the test
				",
		) );
	}
	
	function test_04 ()
	{
		gblError()->Clear();
		$objRW = new ReservedWord();
		
		$bStatus = false;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$objRW->init ($key, 0);
			$bStatus |= (gblError()->No() == 0);
			
			if (gblError()->No() == 0)
				$this->assert (false, "04 - initializing RW with a keyword and 0 token succeeded - " . gblError()->No());	
		}
		
		$this->assert (!$bStatus, "04 - initializing RW with keywords and 0 token failed");	
	}

	// ============================================
	
	public static function docFoo_test_05 ()
	{ docFoo ( Array 
		(
			"Method" => "test_05",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_05 ()
	{
		gblError()->Clear();
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$objRW->init ($key, $val);
			if (gblError()->No() != 0)
				$this->assert (false, "05 - initializing RW with a keyword " . gblError()->No());

			$bStatus |= ($objRW->Word() == $key);
		}

		$this->assert ($bStatus, "05 - the RW keywords remained the same");
	}
	

	// ============================================
	
	public static function docFoo_test_05a ()
	{ docFoo ( Array 
		(
			"Method" => "test_05",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add the space before the keyword 
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_05a ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = "   " . $key . "";
			$objRW->init ($key2, $val);
			$bStatus |= ($objRW->Word() == $key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "05a - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "05a - initializing RW keywords with 'pre' space succeeded");	
		
	}
	

	// ============================================
	
	public static function docFoo_test_05b ()
	{ docFoo ( Array 
		(
			"Method" => "test_05",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add the space after the keyword 
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_05b ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = "" . $key . "   ";
			$objRW->init ($key2, $val);
			$bStatus |= ($objRW->Word() == $key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "05b - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "05b - initializing RW keywords with 'post' space succeeded");	
	}
	

	// ============================================
	
	public static function docFoo_test_05c ()
	{ docFoo ( Array 
		(
			"Method" => "test_05c",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add space before and after the keyword 
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_05c ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = "   " . $key . "   ";
			$objRW->init ($key2, $val);
			$bStatus |= ($objRW->Word() == $key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "05c - initializing RW with a keyword " . gblError()->No());
		}

		$this->assert ($bStatus, "05c - initializing RW keywords with 'pre' and 'post' space succeeded");	
	}
	
	// ============================================
	
	public static function docFoo_test_05d ()
	{ docFoo ( Array 
		(
			"Method" => "test_05d",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add the word 'pre' before the keyword 
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_05d ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = " pre " . $key . "   ";
			$objRW->init ($key2, $val);
			$bStatus |= ($objRW->Word() != $key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "05d - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "05d - initializing RW keywords with 'pre' didn't match");	
	}
	
	// ============================================
	
	public static function docFoo_test_05e ()
	{ docFoo ( Array 
		(
			"Method" => "test_05e",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add the word 'post' after the keyword 
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_05e ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = "   " . $key . " post ";
			$objRW->init ($key2, $val);
			$bStatus |= ($objRW->Word() != $key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "05e - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "05e - initializing RW keywords with 'post' didn't match");	
	}
	

	// ============================================
	
	public static function docFoo_test_05f ()
	{ docFoo ( Array 
		(
			"Method" => "test_05f",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add the word 'pre' before and 'post' after the keyword 
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_05f ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = " pre " . $key . " post ";
			$objRW->init ($key2, $val);
			$bStatus |= ($objRW->Word() != $key);
			
			if (gblError()->No() != 0)
				$this->assert (false, "05f - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "05f - initializing RW keywords with 'pre' and 'post' didn't match");	
	}
	

	// ============================================
	
	public static function docFoo_test_06 ()
	{ docFoo ( Array 
		(
			"Method" => "test_06",
			"Description" => "Confirm the Token passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Token passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_06 ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$objRW->init ($key, $val);
			$bStatus |= ($objRW->Token() == $val);
			
			if (gblError()->No() != 0)
				$this->assert (false, "06 - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "06 - initializing RW with keywords succeeded");	
	}
	

	// ============================================
	
	public static function docFoo_test_07 ()
	{ docFoo ( Array 
		(
			"Method" => "test_07",
			"Description" => "Confirm the Word length passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word length passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_07 ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$objRW->init ($key, $val);
			$bStatus |= (strlen($objRW->Word()) == strlen($key));
			
			if (gblError()->No() != 0)
				$this->assert (false, "07 - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "07 - The RW keyword lengths match");	
	}
	
	// ============================================
	
	public static function docFoo_test_07a ()
	{ docFoo ( Array 
		(
			"Method" => "test_07a",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add space to the keyword before it
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word length passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_07a ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = "   " . $key . "";
			$objRW->init ($key2, $val);
			$bStatus |= (strlen($objRW->Word()) == strlen($key));
			
			if (gblError()->No() != 0)
				$this->assert (false, "07a - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "07a - The RW keyword lengths with 'pre' space matched");	
	}
	

	// ============================================
	
	public static function docFoo_test_07b ()
	{ docFoo ( Array 
		(
			"Method" => "test_07b",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add space to the keyword after it
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word length passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_07b ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = "" . $key . "   ";
			$objRW->init ($key2, $val);
			$bStatus |= (strlen($objRW->Word()) == strlen($key));
			
			if (gblError()->No() != 0)
				$this->assert (false, "07b - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "07b - The RW keyword lengths with 'post' space matched");	
	}
	

	// ============================================
	
	public static function docFoo_test_07c ()
	{ docFoo ( Array 
		(
			"Method" => "test_07c",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add space to the keyword before and after it
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word length passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_07c ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = "   " . $key . "   ";
			$objRW->init ($key2, $val);
			$bStatus |= (strlen($objRW->Word()) == strlen($key));
			
			if (gblError()->No() != 0)
				$this->assert (false, "07c - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "07c - The RW keyword lengths with 'pre' and 'post' space matched");	
	}
	
	// ============================================
	
	public static function docFoo_test_07d ()
	{ docFoo ( Array 
		(
			"Method" => "test_07d",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add the word 'pre' before the keyword 
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_07d ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = " pre " . $key . "   ";
			$objRW->init ($key2, $val);
			$bStatus |= (strlen($objRW->Word()) != strlen($key));
			
			if (gblError()->No() != 0)
				$this->assert (false, "07d - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "07d - The RW keyword lengths with 'pre' didn't match");	
	}
	
	// ============================================
	
	public static function docFoo_test_07e ()
	{ docFoo ( Array 
		(
			"Method" => "test_07e",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add the word 'post' after the keyword 
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_07e ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = "   " . $key . " post ";
			$objRW->init ($key2, $val);
			$bStatus |= (strlen($objRW->Word()) != strlen($key));
			
			if (gblError()->No() != 0)
				$this->assert (false, "07e - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "07e - The RW keyword lengths with 'post' didn't match");	
	}
	

	// ============================================
	
	public static function docFoo_test_07f ()
	{ docFoo ( Array 
		(
			"Method" => "test_07f",
			"Description" => "Confirm the Word passed in is the same",
			"Globals" => Array 
			(
				"aryReservedWords - A String Array of Keywords and TokenCode Values",
			),
			"Core Code" =>
				"
				Loop through all the KeyWords / Token Values in aryReservedWords
				Add the word 'pre' before and 'post' after the keyword 
				Initialize and instance of Reserved Word with a valid keyword and token pair
				Confirm the Word passed in is the same
				Log the results of the test
				",
		 ) );
	}
	
	function test_07f ()
	{
		$objRW = new ReservedWord();
		
		$bStatus = true;
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			$key2 = " pre " . $key . " post ";
			$objRW->init ($key2, $val);
			$bStatus |= (strlen($objRW->Word()) != strlen($key));
			
			if (gblError()->No() != 0)
				$this->assert (false, "07f - initializing RW with a keyword " . gblError()->No());
		}
		
		$this->assert ($bStatus, "07f - The RW keyword lengths with 'pre' and 'post' didn't match");	
	}
	
}

test_ReservedWord::docFoo();

?>
