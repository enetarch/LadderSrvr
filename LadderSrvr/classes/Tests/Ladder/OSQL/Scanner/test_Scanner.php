<?
namespace Tests\Ladder\OSQL\Scanner;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use \ENetArch\Ladder\OSQL\Scanner\ErrorMsgs;
use \ENetArch\Ladder\OSQL\Scanner\TokenCodes;

use \ENetArch\Ladder\OSQL\Scanner\Token;
use \ENetArch\Ladder\OSQL\Scanner\ByteCode;
use \ENetArch\Ladder\OSQL\Scanner\ReservedWord;
use \ENetArch\Ladder\OSQL\Scanner\ReservedWords;
use \ENetArch\Ladder\OSQL\Scanner\Scanner;
use ENetArch\Ladder\OSQL\Scanner\TokenCode;

// ============================================

docFoo ( Array 
(
	"Class" => "test_Scanner",
	"Description" => "Test the Scanner class ",
	"Include" => Array 
		(
			"\Tests\Tests",
			"\Tests\Test",
			"\ENetArch\Ladder\OSQL\Scanner\ErrorMsgs",
			"\ENetArch\Ladder\OSQL\Scanner\TokenCodes",

			"\ENetArch\Ladder\OSQL\Scanner\Token",
			"\ENetArch\Ladder\OSQL\Scanner\ByteCode",
			"\ENetArch\Ladder\OSQL\Scanner\ReservedWord",
			"\ENetArch\Ladder\OSQL\Scanner\ReservedWords",
			"\ENetArch\Ladder\OSQL\Scanner\Scanner",
			
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
				"Confirm error messages are recieved",
				"Confirm that a reserved word is found",
				"Confirm that a reserved word is not found",
				"Confirm that the Token is updated correctly",
				"Confirm that a string starting with a non-digit value throws an error",
				"Confirm an alphanumeric string stops accumulating digits at the first non-digit character",
				"Confirm an all digit string throws no error",
				"Confirm special characters are recognized",
				"Confirm non-special characters throw errors",
				"Confirm that the string end character is found",
				"Confirm that an EOF terminates the function",
				"Confirm alphanumeric characters cause an error",
				"Confirm numeric pattern [ x ]",
				"Confirm numeric pattern [ x. ]",
				"Confirm numeric pattern [ x.x ]",
				"Confirm numeric pattern [ x.e+x ]",
				"Confirm numeric pattern [ x.e-x ]",
				"Confirm the errors are thrown",
				"Confirm that a coma or space seperated string returns x words",
				"Confirm reserved words are properly identified",
				"Confirm the tokens returned match the buffer string",
				"Confirm that preceeding white space characters are ignored",
				"Confirm that inline white space characters are ignored",
				"Confirm that ending white space characters are ignored",
				"Confirm Buffer returns EOF when end of Buffer is reached",
				"Confirm characters returned are in order of those put into the Buffer",
				"Confirm TAB characters are turned to spaces",
				"Confirm LINE FEED characters are turned to spaces",
				"Confirm CARRIAGE RETURN characters are turned to spaces",
				"Confirm that the text buffer length 0 returns an error",
				"Confirm that a null Reserved Word list returns an error",
				"Confirm that the scanner can find specific reserved words",
				"Confirm position of buffer pointers during the scanning process",
				"Confirm inner boundaries of the charcodes",
				"Confirm outer boundaries of the charcodes",
				"Confirm the values are correct", 
				"Confirm that all objects are initialized",
				
				"Confirm the bytecode is correct",
				"Confirm reserved words are case insensitive", 
		),
) );
  
class test_Scanner extends \Tests\Test
{
	protected $nLoops = 1;
	
	protected $objReservedWords = null;
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

	protected $aryStrings = Array
		(
			Array (" ", "", array () ),
			Array ("a = 11 + 22; ", "4, 13, 5, 12, 5, 27, ", array ("a", "=", "11", "+", "22", ";")),
			Array 
			(
				"Michael is the greatest person on earth", 
				"4, 4, 4, 4, 4, 4, 4, ", 
				array ("michael",  "is", "the", "greatest", "person", "on", "earth") 
			),
			Array 
			(
				"special characters = < > << >> =< <= => >= ; ! @ $ % ^ & * ( ) { } [ ] ` ~  : ; , . ? \\ /", 
				"4, 4, 13, 20, 21, 20, 20, 21, 21, 13, 20, 24, 13, 21, 25, 27, 28, 29, 30, 31, 7, 33, 8, 9, 10, 16, 17, 14, 15, 34, 35, 18, 27, 19, 22, 37, 36, 23, ",
				array ("special", "characters", "=", "<", ">", "<", "<", ">", ">", "=", "<", "<=", "=", ">", ">=", ";", "!", "@", "$", "%", "^", "&", "*", "(", ")", "{", "}", "[", "]", "`", "~", ":", ";", ",", ".", "?", "\\", "/", ),
			),
			Array 
			(
				"digits 11 22 33 44 55 66 77 88 99 0 1.0 1.1 1.2 1.3 1.4 1.5 1.6 1.7 1.8 1.9 1e1 1.0e1 1.0e+1 1.0e-1", 
				"4, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, ", 
				array ("digits", "11", "22", "33", "44", "55", "66", "77", "88", "99", "0", "1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1e1", "1.0e1", "1.0e+1", "1.0e-1", ),
			),
			Array 
			(
				"special words = in and like class select classes subclass templates identifier datecreated uidcreated ipcreated", 
				"4, 4, 13, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ", 
				array ("special", "words", "=", "in", "and", "like", "class", "select", "classes", "subclass", "templates", "identifier", "datecreated", "uidcreated", "ipcreated", ) 
			),
			Array 
			(
				"white space	must  be 
				removed", 
				"4, 4, 4, 4, 4, ", 
				array ("white", "space", "must", "be", "removed", ) 
			),
			Array ("find string 1 #this is a string# ", "4, 4, 5, 6, ", array ("find", "string", "1", "#this is a string#") ),
			Array ("find string 2 'this is a string' ", "4, 4, 5, 6, ", array ("find", "string", "2", "'this is a string'") ),
			Array ("find string 3 \"this is a string\" ", "4, 4, 5, 6, ", array ("find", "string", "3", "\"this is a string\"") ),

			Array 
			(
				"a b c d e f g h i j k l m n o p q r s t u v w x y z ", 
				"4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, ", 
				array ("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", ) 
			),

			Array 
			(
				"A B C D E F G H I J K L M N O P Q R S T U V W X Y Z ", 
				"4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, ", 
				array ("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", ) ,
				array ("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", ) 
			),

			Array 
			(
				"1 2 3 4 5 6 7 8 9 0 ", 
				"5, 5, 5, 5, 5, 5, 5, 5, 5, 5, ", 
				array ("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", ) 
			),
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
	}
	
	// ============================================
	
	public static function docFoo_setup ()
	{ docFoo ( Array 
		(
		"Method" => "setup",
		"Description" => Array 
			(
				"prepare tests to be run in an existing environment",
			),
			"Core Code" => Array
			(
				"Add Keywords to Reserved Word list",
			),
		
	 ) );
	}

	function setup() 
	{
		gblError()->Clear();
		$this->objReservedWords = new ReservedWords();
		
		ForEach ($this->aryReservedWords AS $key => $val)
		{
			gblError()->Clear();
			$this->objReservedWords->AddItem ($key, $val);
			
			if (gblError()->No() != 0)
			{
				$this->log ("Failed", "Setup");
				return (false);
			}
		}
		
		$this->log ("Succeeded", "Setup");
		return (true);
	}
	
	// ============================================
	public static function docFoo_teardown ()
	{ docFoo ( Array 
		(
		"Method" => "teardown",
		"Description" => "deconstruct the environment created to run tests in.",
	 ) );
	}

	function teardown() { return (true); }
	
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
		
		return (true);
	}
	
	// ============================================
	
	public static function docFoo_test_01 ()
	{ docFoo ( Array 
		(
			"Method" => "test_01",
			"Description" => "Confirm that all objects are initialized",
		 ) );
	}
	
	function test_01 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		
		$bSuccess = true;
		$bSuccess &= ($objScanner->Position() == 0);
		$bSuccess &= ($objScanner->token != null);
		$bSuccess &= ($objScanner->ByteCode() != null);
		
		$this->assert ($bSuccess, "01 - An instance of Scanner has been instantiated");
	}
	
	// ============================================
	
	public static function docFoo_test_02 ()
	{ docFoo ( Array 
		(
			"Method" => "test_02",
			"Description" => "Confirm the values returned match",
		 ) );
	}
	
	function test_02 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		
		$bSuccess = True;
		
		$nLen = count ($this->aryStrings);
		for ($t=0; $t<$nLen; $t++)
		{
			gblError()->Clear ();
			$localSuccess = true;
			
			$this->Log ("Scanning", "02 - [ $t ] - " . $this->aryStrings[$t][0]);
			$objScanner->init ($this->aryStrings[$t][0], $this->objReservedWords);
			
			$nLen2 = count ($this->aryStrings[$t][2]);
			$rtn = "";
			$z = 0;
			$bRun = True;
			
			$token = $objScanner->getToken ();
			if (gblError()->No() != 0)
			{
				$this->Log ("Error", "02 - " . gblError()->No() . " - " . 
					gblError()->Description() . " @ " . 
					$objScanner->Position() . " [ " . $objScanner->token->value . " ] "
					);
				
				$bRun = false;
			}

			while 
			(
				($token->code != TokenCodes::tcEOF) &&
				$bRun 
			)
			{
				$rtn .= $token->code . ", ";
				
				if ($z >= $nLen2)
				{
					print ("t = [ $t ] z = [ $z ]  exceeded limit of [ $nLen2 ] token [$token->code]<BR>");
					$this->Log ("z", "z = [ $z ]  exceeded limit of [ $nLen2 ]");
				}
				
				if ($z < $nLen2)
				if ($token->value != $this->aryStrings[$t][2][$z])
					$this->Log ("[ $z ] value / expected", $token->value . " / " . $this->aryStrings[$t][2][$z]);
				
				if ($z < $nLen2)
				{ $localSuccess &= ($token->value == $this->aryStrings[$t][2][$z]); }
				else
				{ $localSuccess = false; }
				
				$z++;

				$token = $objScanner->getToken ();
				if (gblError()->No() != 0)
				{
					$this->Log ("Error", "02 - " . gblError()->No() . " - " . 
						gblError()->Description() . " @ " . 
						$objScanner->Position() . " [ " . $objScanner->token->value . " ] "
						);
					
					$bRun = false;
				}
				
			}
			
			$this->assert ($localSuccess, "02 - [ $t ] - Token Values Match");
			$this->Log ("=======", "=======");

			$bSuccess &= $localSuccess;
		}
		
		$this->assert ($bSuccess, "02 - All values matched");
	}
	

	// ============================================
	
	public static function docFoo_test_03 ()
	{ docFoo ( Array 
		(
			"Method" => "test_03",
			"Description" => "Confirm the ByteCode is correct",
		 ) );
	}
	
	function test_03 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		
		$bSuccess = True;
		
		$nLen = count ($this->aryStrings);
		for ($t=0; $t<$nLen; $t++)
		{
			gblError()->Clear ();
			
			$this->Log ("Scanning", "03 - [ $t ] - " . $this->aryStrings[$t][0]);
			$objScanner->init ($this->aryStrings[$t][0], $this->objReservedWords);
			
			$rtn = "";
			$rtn2 = "";
			$bRun = True;
			$token = $objScanner->getToken ();
			while 
			(
				( $token->code != TokenCodes::tcEOF) &&
				$bRun 
			)
			{
				$rtn .= $token->code . ", ";
				$rtn2 .= "\"" . $token->value . "\", ";
				$token = $objScanner->getToken ();
				if (gblError()->No() != 0)
				{
					$this->Log ("Error", "03 - " . gblError()->No() . " - " . 
						gblError()->Description() . " @ " . 
						$objScanner->Position() . " [ " . $token->value . " ] "
						);
					$bRun = false;
				}
			}
			
			$localSuccess = ($rtn == $this->aryStrings[$t][1]);
			if (!$localSuccess)
			{
				$this->Log ("Expected", $this->aryStrings[$t][1]);
				$this->Log ("Collected", $rtn);
				$this->Log ("Collected", $rtn2);
			}
			
			$localSuccess &= (gblError()->No() == 0);
				
			$this->assert ($localSuccess, "03 - [ $t ] - Token Codes Match");
			$this->Log ("=======", "=======");
			
			$bSuccess &= $localSuccess;
		}
		
		$this->assert ($bSuccess, "03 - All tokens matched");
	}
	
	// ============================================
	//		Negative Testing Suite
	// ============================================
	
	public static function docFoo_test_04 ()
	{ docFoo ( Array 
		(
			"Method" => "test_04",
			"Description" => "Confirm the errors on initialization",
		 ) );
	}
	
	function test_04 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		$bSuccess = false;
		
		$objScanner->init (null, $this->objReservedWords);
		$bSuccess = (gblError()->No() == 4);
		if (gblError()->No() != 4)
		{
			$this->log ("Error", "04 - " , gblError()->No() . " - " . gblError()->Description() );
			return;
		}
		
//		$objScanner->init ("this is a test", null);
//		$bSuccess |= (gblError()->No() != 0);

		$bSuccess = True;
		
		$this->assert ($bSuccess, "04 - Initialization errors confirmed [ " . gblError()->No() . " ]");
	}
	
	// ============================================

	public static function docFoo_test_05 ()
	{ docFoo ( Array 
		(
			"Method" => "test_05",
			"Description" => "Confirm the MaxDigit error is generated",
		 ) );
	}
	
	function test_05 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		$bSuccess = false;
		
		$szValue = " ";
		for ($t = 0; $t < Scanner::MAXDIGITCOUNT; $t++)
			$szValue .= $t;
			
		$objScanner->init ($szValue, $this->objReservedWords);
		if (gblError()->No() != 0)
		{
			$this->log ("Error", "05 - " , gblError()->No() . " - " . gblError()->Description() );
			return;
		}
		
		$token = $objScanner->getToken();
		$bSuccess |= (gblError()->No() == 0);
		
		$this->assert (!$bSuccess, "05 - MaxDigit error confirmed [ " . gblError()->No() . " ] " . $szValue . " / " .  $token->value);
	}
	
	// ============================================

	public static function docFoo_test_06 ()
	{ docFoo ( Array 
		(
			"Method" => "test_06",
			"Description" => "Confirm the MaxValue error is generated",
		 ) );
	}
	
	function test_06 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		$bSuccess = false;
		
		$szValue = " " . (Scanner::MAXINTEGER+1);
			
		$objScanner->init ($szValue, $this->objReservedWords);
		if (gblError()->No() != 0)
		{
			$this->log ("Error", "06 - " , gblError()->No() . " - " . gblError()->Description() );
			return;
		}
		
		$token = $objScanner->getToken();
		$bSuccess |= (gblError()->No() == 0);
		if (gblError()->No() != 10)
			$this->log ("Error", "06.a - [ " . gblError()->No() . " ] " . $szValue . " / " . $token->value);
		
		// ============
		
		gblError()->Clear();
		$szValue = "-" . (Scanner::MAXINTEGER+1);
			
		$objScanner->init ($szValue, $this->objReservedWords);
		if (gblError()->No() != 0)
		{
			$this->log ("Error", "06 - " , gblError()->No() . " - " . gblError()->Description() );
			return;
		}
		
		$token = $objScanner->getToken(); // retrieve the minus sign ( - )
		
		$token = $objScanner->getToken();
		$bSuccess |= (gblError()->No() == 0);
		if (gblError()->No() != 10)
			$this->log ("Error", "06.b - [ " . gblError()->No() . " ] " . $szValue . " / " . $token->value);

		$this->assert (!$bSuccess, "06 - MaxValue error confirmed [ 10 ] " . $szValue . " / " . Scanner::MAXINTEGER);
	}

	// ============================================

	public static function docFoo_test_07 ()
	{ docFoo ( Array 
		(
			"Method" => "test_07",
			"Description" => "Confirm the MaxExponent Value error is generated",
		 ) );
	}
	
	function test_07 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		$bSuccess = false;
		
		$szValue = "1.2e" . (Scanner::MAXEXPONENT+1);
			
		$objScanner->init ($szValue, $this->objReservedWords);
		if (gblError()->No() != 0)
		{
			$this->log ("Error", "07 - " , gblError()->No() . " - " . gblError()->Description() );
			return;
		}
		
		$token = $objScanner->getToken();
		$bSuccess |= (gblError()->No() == 0);
		
		$this->assert (!$bSuccess, "07 - MaxExponent Value error confirmed [ " . gblError()->No() . " ] " . $szValue . " / " .  $token->value);
	}

	// ============================================

	public static function docFoo_test_08 ()
	{ docFoo ( Array 
		(
			"Method" => "test_08",
			"Description" => "Confirm Invalid Number error is generated",
		 ) );
	}
	
	function test_08 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		$bSuccess = false;
		
		$szValue = "313152a0";
			
		$objScanner->init ($szValue, $this->objReservedWords);
		if (gblError()->No() != 0)
		{
			$this->log ("Error", "08 - " , gblError()->No() . " - " . gblError()->Description() );
			return;
		}
		
		$token = $objScanner->getToken();
		$bSuccess |= (gblError()->No() == 0);
		
		$this->assert (!$bSuccess, "08 - Invalid Number error confirmed [ " . gblError()->No() . " ] " . $szValue . " / " .  $token->value);
	}

	// ============================================

	public static function docFoo_test_09 ()
	{ docFoo ( Array 
		(
			"Method" => "test_09",
			"Description" => "Confirm Invalid Fraction error is generated",
		 ) );
	}
	
	function test_09 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		$bSuccess = false;
		
		$szValue = "3.13152a0";
			
		$objScanner->init ($szValue, $this->objReservedWords);
		if (gblError()->No() != 0)
		{
			$this->log ("Error", "09 - " , gblError()->No() . " - " . gblError()->Description() );
			return;
		}
		
		$token = $objScanner->getToken();
		$bSuccess |= (gblError()->No() == 0);
		
		$this->assert (!$bSuccess, "09 -  Invalid Fraction error confirmed [ " . gblError()->No() . " ] " . $szValue . " / " .  $token->value);
	}

	// ============================================

	public static function docFoo_test_10 ()
	{ docFoo ( Array 
		(
			"Method" => "test_10",
			"Description" => "Confirm Invalid Exponent error is generated",
		 ) );
	}
	
	function test_10 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		$bSuccess = false;
		
		$szValue = "3.13152a0";
			
		$objScanner->init ($szValue, $this->objReservedWords);
		if (gblError()->No() != 0)
		{
			$this->log ("Error", "10 - " , gblError()->No() . " - " . gblError()->Description() );
			return;
		}
		
		$token = $objScanner->getToken();
		$bSuccess |= (gblError()->No() == 0);
		
		$this->assert (!$bSuccess, "10 -  Invalid Exponent error confirmed [ " . gblError()->No() . " ] " . $szValue . " / " .  $token->value);
	}

	// ============================================

	public static function docFoo_test_11 ()
	{ docFoo ( Array 
		(
			"Method" => "test_11",
			"Description" => "Confirm Invalid Exponent error is generated",
		 ) );
	}
	
	function test_11 ()
	{
		gblError()->Clear();
		$objScanner = new Scanner();
		$bSuccess = false;
		
		$szValue = "3.1ea0";
			
		$objScanner->init ($szValue, $this->objReservedWords);
		if (gblError()->No() != 0)
		{
			$this->log ("Error", "11 - " , gblError()->No() . " - " . gblError()->Description() );
			return;
		}
		
		$token = $objScanner->getToken();
		$bSuccess |= (gblError()->No() == 0);
		if (gblError()->No() != 7)
			$this->log ("Error", "11 - [ " . gblError()->No() . " ] " . $szValue . " / " . $token->value);
		
		$this->assert (!$bSuccess, "11 -  Invalid Exponent error confirmed [ " . gblError()->No() . " ] " . $szValue . " / " . $token->value);
	}

}

test_Scanner::docFoo();

?>
