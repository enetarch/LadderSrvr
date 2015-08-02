<?
namespace Tests\Ladder\OSQL\Parser;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use ENetArch\Ladder\OSQL\Scanner\LiteralType;
use \ENetArch\Ladder\OSQL\Parser\Param;
use \ENetArch\Ladder\OSQL\Parser\Params;


// ============================================

docFoo ( Array 
(
	"Class" => "test_Params",
	"Description" => "Test the Reserved Words class ",
	"Requirements" => Array 
		(
		"Confirm parameters can be added",
		"Confirm parameters can be found",
		"Confirm a parameter is not found",
		"Confirm that parameters are stored in order added",
		"Confirm that the number of parameters added matches",
		"Confirm that an empty container doesn't throw an error",
		"Confirm a parameters not in the container doesn't throw an error ",
		"Confirm a blank parameter returns an error",
		"Retrieve a parameter in the container bounds",
		"Retrieve a parameter outside the container bounds",
		),
	"Include" => Array 
		(
			"\Tests\Tests",
			"\Tests\Test",
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
		),	
	"Methods" => Array
		(
		),		
	"Comment" => Array 
		(
			"Tests confirming boundaries for adding a keyword and token code to the 
			container, are duplicated in the test_ReservedWord ",
		),
) );
  
class test_Params extends \Tests\Test
{
	protected $nLoops = 1;

	protected $aryParams = Array 
	(
		Array ( "Ladder_Folder", "e008", "Name", LiteralType::cSTRING, "RootClasses" ),
		Array ( "Ladder_Definition", "d009", "Parent", LiteralType::cSTRING, "1234-1234-1234-1234-1234" ),
		Array ( "Ladder_Class", "c001", "ID", LiteralType::cINTEGER, "123" ),
		Array ( "Common_Todo", "b002", "bComplete", LiteralType::cINTEGER, "1" ),
		Array ( "Common_Address", "a003", "szStreet1", LiteralType::cSTRING, "PO Box 43766" ),
	);

	protected $aryNotFound = Array 
	(
		Array ( "Ladder_Item", "e008", "Name", LiteralType::cSTRING, "RootClasses" ),
		Array ( "Store_LineItem", "d009", "Parent", LiteralType::cSTRING, "1234-1234-1234-1234-1234" ),
		Array ( "Site_App", "c001", "ID", LiteralType::cINTEGER, "123" ),
		Array ( "Security_Group", "b002", "bComplete", LiteralType::cINTEGER, "1" ),
		Array ( "Common_Name", "a003", "szStreet1", LiteralType::cSTRING, "PO Box 43766" ),
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
		
		return (true);
	}
	
	
	
	// ============================================
	
	public static function docFoo_test_01 ()
	{ docFoo ( Array 
		(
			"Method" => "test_01",
			"Description" => Array
			(
				"Confirm parameters can be added",
				"Confirm the count matches the number of instances created",
			),
		 ) );
	}
	
	function test_01 ()
	{
		gblError()->Clear();
		$objParams = new Params();
		
		$bStatus = true;
		
		ForEach ($this->aryParams AS $key => $val)
		{
			gblError()->Clear();
			$nPos = $objParams->AddItem ($val[0], $val[1], $val[2], $val[3], $val[4]);

			if (gblError()->No() != 0)
				$this->assert (false, "01 - initializing Params with a keyword succeeded");

			if ($nPos == -1)
				$this->log ("Not Added", $val[0]);

			$bStatus |= (gblError()->No() == 0);			
		}
		
		$this->assert ($bStatus, "01 - initializing Params with keywords succeeded");	
		
		$nLen = count ($this->aryParams);
		$nCount = $objParams->Length(); 
		$this->assert (($nCount == $nLen), "01 - the number of Params match ($nLen == $nCount)");	
	}
	
	// ============================================
	
	public static function docFoo_test_02 ()
	{ docFoo ( Array 
		(
			"Method" => "test_02",
			"Description" => Array
			(
				"Confirm parameters can be found",
			),
		 ) );
	}
	
	function test_02 ()
	{
		gblError()->Clear();
		$objParams = new Params();
		
		$bStatus = true;
		
		ForEach ($this->aryParams AS $key => $val)
		{
			gblError()->Clear();
			$nPos = $objParams->AddItem ($val[0], $val[1], $val[2], $val[3], $val[4]);

			if (gblError()->No() != 0)
				$this->assert (false, "02 - initializing Params with a keyword succeeded");

			if ($nPos == -1)
				$this->log ("Not Added", $val[0]);

			$bStatus |= (gblError()->No() == 0);
		}
		
		$bStatus = true;
		ForEach ($this->aryParams AS $key => $val)
		{
			gblError()->Clear();
			$objParam = new Param ();
			$objParam->init ($val[0], $val[1], $val[2], $val[3], $val[4]);
			if (gblError()->No() != 0)
				$this->assert (false, "02 - initializing Params with a keyword succeeded");
			
			$nPos = $objParams->Find ($objParam);
			if (gblError()->No() != 0)
				$this->assert (false, "02 - initializing Params with a keyword succeeded");

			if ($nPos == -1)
				$this->log ("Not Found", $val[0]);

			$bStatus &= ($nPos != -1);
		}
		
		$this->assert ($bStatus, "02 - found all Params in the container");	
		if (!$bStatus)
		{
			print ("<pre>");
			print_r ($objParams);
			print ("</pre>");
		}
		
		$nLen = count ($this->aryParams);
		$nCount = $objParams->Length(); 
		$this->assert (($nCount == $nLen), "02 - the number of Params match ($nLen == $nCount)");	
	}
	
	// ============================================
	
	public static function docFoo_test_03 ()
	{ docFoo ( Array 
		(
			"Method" => "test_03",
			"Description" => Array
			(
				"Confirm parameters can not be found",
				"Confirm a parameters not in the container doesn't throw an error ",
			),
		 ) );
	}
	
	function test_03 ()
	{
		gblError()->Clear();
		$objParams = new Params();
		
		$bStatus = true;
		
		ForEach ($this->aryParams AS $key => $val)
		{
			gblError()->Clear();
			$nPos = $objParams->AddItem ($val[0], $val[1], $val[2], $val[3], $val[4]);

			if (gblError()->No() != 0)
				$this->assert (false, "03 - initializing Params with a keyword succeeded");

			if ($nPos == -1)
				$this->log ("Not Added", $val[0]);

			$bStatus |= (gblError()->No() == 0);
		}
		
		$bStatus = true;
		ForEach ($this->aryNotFound AS $key => $val)
		{
			gblError()->Clear();
			$objParam = new Param ();
			$objParam->init ($val[0], $val[1], $val[2], $val[3], $val[4]);
			if (gblError()->No() != 0)
				$this->assert (false, "03 - initializing Params with a keyword succeeded");
			
			$nPos = $objParams->Find ($objParam);
			if (gblError()->No() != 0)
				$this->assert (false, "03 - initializing Params with a keyword succeeded");
			
			if ($nPos != -1)
				$this->log ("Found", $val[0] . " found at position [ " . $nPos . " ]");
				
			$bStatus &= ($nPos == -1);
		}
		
		$this->assert ($bStatus, "03 - all Params cannot be found in the container");	
		
		$nLen = count ($this->aryParams);
		$nCount = $objParams->Length(); 
		$this->assert (($nCount == $nLen), "03 - the number of Params match ($nLen == $nCount)");	
	}
	
	// ============================================
	
	public static function docFoo_test_04 ()
	{ docFoo ( Array 
		(
			"Method" => "test_04",
			"Description" => Array
			(
				"Confirm order parameters are added",
				"Retrieve a parameter in the container bounds",
			),
		 ) );
	}
	
	function test_04 ()
	{
		gblError()->Clear();
		$objParams = new Params();
		
		$bStatus = true;
		
		$aryKeys = range (0, count ($this->aryParams) -1);
		shuffle ($aryKeys);
		
		ForEach ($aryKeys AS $key => $key2)
		{
			gblError()->Clear();
			$val = $this->aryParams[$key2];
			$nPos = $objParams->AddItem ($val[0], $val[1], $val[2], $val[3], $val[4]);

			if (gblError()->No() != 0)
				$this->assert (false, "04 - initializing Params with a keyword succeeded");

			if ($nPos == -1)
				$this->log ("Not Added", $val[0]);

			$bStatus |= (gblError()->No() == 0);
		}
		
		$bStatus = true;
		ForEach ($aryKeys AS $key => $key2)
		{
			gblError()->Clear();
			$val = $this->aryParams[$key2];
			
			$objParam = new Param ();
			$objParam->init ($val[0], $val[1], $val[2], $val[3], $val[4]);
			if (gblError()->No() != 0)
				$this->assert (false, "04 - initializing Params with a keyword succeeded");
			
			$nPos = $objParams->Find ($objParam);
			if (gblError()->No() != 0)
				$this->assert (false, "04 - initializing Params with a keyword succeeded");

			if ($nPos == -1)
				$this->log ("Not Found", $val[0]);

			if ($nPos != $key)
				$this->log ("Mismatch", "Postion / Key mismatch - ( $nPos / $key ) " . $val[0] );

			$bStatus &= ($nPos == $key);
		}
		
		$this->assert ($bStatus, "04 - found all Params in the container");	
		if (!$bStatus)
		{
			print ("<pre>");
			print_r ($objParams);
			print ("</pre>");
		}
		
		$nLen = count ($this->aryParams);
		$nCount = $objParams->Length(); 
		$this->assert (($nCount == $nLen), "04 - the number of Params match ($nLen == $nCount)");	
	}
	
	// ============================================
	
	public static function docFoo_test_05 ()
	{ docFoo ( Array 
		(
			"Method" => "test_05",
			"Description" => Array
			(
				"Confirm that an empty container doesn't throw an error",
			),
		 ) );
	}
	
	function test_05 ()
	{
		gblError()->Clear();
		$objParams = new Params();
		
		$bStatus = true;
		ForEach ($this->aryNotFound AS $key => $val)
		{
			gblError()->Clear();
			$objParam = new Param ();
			$objParam->init ($val[0], $val[1], $val[2], $val[3], $val[4]);
			if (gblError()->No() != 0)
				$this->assert (false, "05 - initializing Params with a keyword succeeded");
			
			$nPos = $objParams->Find ($objParam);
			if (gblError()->No() != 0)
				$this->assert (false, "05 - initializing Params with a keyword succeeded");
			
			if ($nPos != -1)
				$this->log ("Found", $val[0] . " found at position [ " . $nPos . " ]");
				
			$bStatus &= ($nPos == -1);
		}
		
		$this->assert ($bStatus, "05 - all Params cannot be found in the container");	
		
		$nLen = 0;
		$nCount = $objParams->Length(); 
		$this->assert (($nCount == $nLen), "05 - the number of Params match ($nLen == $nCount)");	
	}
	
	// ============================================
	
	public static function docFoo_test_06 ()
	{ docFoo ( Array 
		(
			"Method" => "test_06",
			"Description" => Array
			(
				"Retrieve a parameter in the container bounds",
			),
		 ) );
	}
	
	function test_06 ()
	{
		gblError()->Clear();
		$objParams = new Params();
		
		$bStatus = true;
		
		$aryKeys = range (0, count ($this->aryParams) -1);
		shuffle ($aryKeys);
		
		ForEach ($aryKeys AS $key => $key2)
		{
			gblError()->Clear();
			$val = $this->aryParams[$key2];
			$nPos = $objParams->AddItem ($val[0], $val[1], $val[2], $val[3], $val[4]);

			if (gblError()->No() != 0)
				$this->assert (false, "06 - initializing Params with a keyword succeeded");

			if ($nPos == -1)
				$this->log ("Not Added", $val[0]);

			$bStatus |= (gblError()->No() == 0);
		}

		
		$bStatus = true;
		$nLen = count ($this->aryParams);
		For ($t =0; $t < $nLen; $t++)
		{
			gblError()->Clear();
			$val = $this->aryParams[$aryKeys[$t]];
			
			$objParam = new Param ();
			$objParam->init ($val[0], $val[1], $val[2], $val[3], $val[4]);
			if (gblError()->No() != 0)
				$this->assert (false, "06 - initializing Params with a keyword succeeded");
			
			$objParam2 = $objParams->Item ($t);
			if (gblError()->No() != 0)
				$this->assert (false, "06 - retrieving Params from position [ $t ] succeeded");

			if ($objParam->szClass != $objParam2->szClass)
				$this->log ("Not Found", $val[0]);

			$bStatus &= ($objParam->szClass == $objParam2->szClass);
		}
		
		$this->assert ($bStatus, "06 - found all Params in the container");	
		if (!$bStatus)
		{
			print ("<pre>");
			print_r ($objParams);
			print ("</pre>");
		}
		
		
		$nCount = $objParams->Length(); 
		$this->assert (($nCount == $nLen), "06 - the number of Params match ($nLen == $nCount)");	
	}

	// ============================================
	
	public static function docFoo_test_07 ()
	{ docFoo ( Array 
		(
			"Method" => "test_07",
			"Description" => Array
			(
				"Retrieve a parameter outside the container bounds",
			),
		 ) );
	}
	
	function test_07 ()
	{
		gblError()->Clear();
		$objParams = new Params();
		
		$bStatus = true;
		
		$aryKeys = range (0, count ($this->aryParams) -1);
		shuffle ($aryKeys);
		
		ForEach ($aryKeys AS $key => $key2)
		{
			gblError()->Clear();
			$val = $this->aryParams[$key2];
			$nPos = $objParams->AddItem ($val[0], $val[1], $val[2], $val[3], $val[4]);

			if (gblError()->No() != 0)
				$this->assert (false, "07 - initializing Params with a keyword succeeded");

			if ($nPos == -1)
				$this->log ("Not Added", $val[0]);

			$bStatus |= (gblError()->No() == 0);
		}

		// ==============
		
		$bStatus = true;
		$nLen = count ($this->aryParams);

		$t = -1;
		$objParam = $objParams->Item ($t);
		if (gblError()->No() == 0)
			$this->assert (false, "07 - retrieving Params from position [ $t ] succeeded");

		if ($objParam != null)
			$this->log ("Found", "Param at position [ $t ] found - " . gettype ($objParam) );

		$bStatus &= ($objParam == null);
		
		// ==============
		
		$t = $nLen;
		$objParam = $objParams->Item ($t);
		if (gblError()->No() == 0)
			$this->assert (false, "07 - retrieving Params from position [ $t ] succeeded");

		if ($objParam != null)
			$this->log ("Found", "Param at position [ $t ] found - " . gettype ($objParam) );

		$bStatus &= ($objParam == null);
		
		// ==============

		$this->assert ($bStatus, "07 - found all Params in the container");	
		
		$nCount = $objParams->Length(); 
		$this->assert (($nCount == $nLen), "07 - the number of Params match ($nLen == $nCount)");	
	}
}

test_Params::docFoo();

?>
