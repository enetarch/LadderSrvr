<?
namespace Tests\Ladder\OSQL\Parser;

use \ENetArch\Common\DocFoo;

use \Tests\Tests;
use \Tests\Test;

use \ENetArch\Ladder\OSQL\Parser\Parser;
use \ENetArch\Ladder\OSQL\Parser\TokenCodes;

include_once ("classes/ENetArch/Ladder/OSQL/Parser/init_Globals.php");

// ============================================

docFoo ( Array 
(
	"Class" => "test_Parser",
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
  
class test_Parser extends \Tests\Test
{
	protected $nLoops = 1;

	protected $aryStatements = Array
	(
		0 => array ("    ", ""),
		1 => array ("isInstalled Ladder", "98, 101"),
		2 => array ("Install Ladder", "99, 101"),
		3 => array ("unInstall Ladder", "100, 101"),
		4 => array ("CREATE CLASS junk_test", "35, 36, 1", array ("junk_test") ),
		5 => array 
		(
			"CREATE CLASS Common_Todo 
			( 
				bCompletd BOOLEAN, 
				dCompletd DATETIME, 
				nPriority INTEGER, 
				nType INTEGER, 
				szMemo VARCHAR (20) 
			)", 
			"35, 36, 1, ", 
			array ("Common_Todo", "bCompleted", "dCompleted", "nPriority", "nType", "szMemo") 
		),
		
		6 => Array
		(
			"CREATE FOLDER Common_Todos
				WITH TEMPLATES
					( 
						Common_Todo -1,
						TaskTypes_Ref 1
					)
				ISROOT = FALSE 
				ALLOW ALL = FALSE",
			"45, 85, 4, 0, 49, 56, 9, 4, 1, 5, 2, 19, 4, 3, 5, 4, 10, 107, 13, 101, 50, 51, 13, 101, 1",
			array ("Common_Todos", "Common_Todo", -1, FALSE, FALSE)
		),
		
		7 => Array
		(
			"CREATE SUBCLASS Common_JustDos
				OF CLASS Common_Todos
				ALLOW ALL = TRUE",
			"45, 48, 4, 0, 84, 46, 4, 1, 50, 51, 13, 100, 1",
			array ("Common_JustDos", "Common_Todos", TRUE)
		),
		
		8 => Array
		(
			"DROP CLASS Common_JustDos",
			"53, 46, 4, 0, 1",
			array ("Common_JustDos")
		),
		
		9 => Array
		(
			"	ALTER CLASS Common_Todo
				ADD STRUCTURE 
				( nTime INTEGER )",
			"72, 46, 4, 0, 98, 57, 9, 4, 1, 4, 2, 10, 1",
			array ("Common_Todo","nTime"), 
		),

		10 => Array
		(
			"	ALTER CLASS Common_Todo
				DELETE STRUCTURE 
				nTime ",
			"72, 46, 4, 0, 69, 57, 4, 1, 1",
			array ("Common_Todo","nTime"), 
		),
		
		11 => Array
		(
			"
				ALTER CLASS Common_Todo
				UPDATE STRUCTURE 
				SET
					'name' = 'nTime' ,
					'type' = 'integer' ,
					length = 4 ,
					'attributes' = 'autoincrement'
			",
			"72, 46, 4, 0, 70, 57, 71, 4, 1, 13, 6, 2, 19, 6, 3, 13, 6, 4, 19, 4, 5, 13, 5, 6, 19, 6, 7, 13, 6, 8, 1",
			array ("Common_Todo", "field", "nTime", "type", "integer", "length", "4", "attributes", "autoincrement"), 
		),

		12 => Array
		(
			"
				ALTER CLASS Common_Todos
				ADD TEMPLATE 
				( Common_TaskType_Ref, 1 )
			",
			"72, 46, 4, 0, 98, 99, 9, 4, 1, 19, 5, 2, 10",
			array ("Common_Todos", "Common_TaskType_Ref", "1"), 
		),

		13 => Array
		(
			"
				ALTER CLASS Common_Todos
					DELETE TEMPLATE 
						Common_TaskType_Ref		
			",
			"72, 46, 4, 0, 69, 99, 4, 1, 1",
			array ("Common_Todos", "Common_TaskType_Ref"), 
		),

		14 => Array
		(
			"
				ALTER CLASS Common_Todos
				UPDATE TEMPLATE
				SET
					'name' = 'Common_TaskType_Ref' ,
					allowed = 5 	
			",
			"72, 46, 4, 0, 70, 99, 71, 6, 1, 13, 6, 2, 19, 4, 3, 13, 5, 4, 1",
			array ("Common_Todos", "Common_TaskType_Ref"), 
		),

		15 => Array
		(
			"
				SELECT CLASS
				USING CLASS Common_Todo
			",
			"54, 46, 58, 46, 4, 0, 1",
			array ("Common_Todo"), 
		),
		
		16 => Array 
		(
			"
				SELECT STRUCTURE
				USING CLASS Common_Todo
			",
			"54, 57, 58, 46, 4, 0, 1",
			array ("Common_Todo"), 
		),

		17 => Array
		(
			"
				SELECT TEMPLATES
				USING CLASS Common_Todos
			",
			"54, 56, 58, 46, 4, 0, 1",
			array ("Common_Todo"), 
		),
		
		18 => Array
		(
			"SELECT FOLDER '\RootFolder\Todos'	",
			"54, 85, 6, 0, 1",
			array ("Common_Todo"), 
		),
		
		19 => Array
		(
			"
				SELECT INSTANCES
				USING CLASS Common_Todo
			",
			"54, 55, 58, 46, 4, 0, 1",
			array ("Common_Todo"), 
		),
		
		20 => Array
		(
			"
				SELECT INSTANCES
				USING CLASS Common_Todo
				FROM '\RootFolder\Todos'
			",
			"54, 55, 58, 46, 4, 0, 59, 6, 1, 1",
			array ("Common_Todo"), 
		),

		21 => Array
		(
			"
				SELECT INSTANCES
				USING CLASS Common_Todo
				FROM '\RootFolder\Todos'
				WHERE
					Common_Todos CONTAINS Common_Todo
			",
			"54, 55, 58, 46, 4, 0, 59, 6, 1, 60, 4, 2, 73, 4, 0, 1",
			array ("Common_Todo"), 
		),

		211 => Array
		(
			"
				SELECT INSTANCES
				USING CLASS Common_Todo
				FROM '\RootFolder\Todos'
				WHERE
					Common_Todos CONTAINS Common_Todo AND
					Common_Todo.dTarget BETWEEEN #2015-01-20# AND #2015-01-21#
			",
			"54, 55, 58, 46, 4, 0, 59, 6, 1, 60, 4, 2, 73, 4, 0, 1",
			array ("Common_Todo"), 
		),
					

		22 => Array
		(
			"
				INSERT INTO '\RootFolder\Todos'
				USING CLASS Common_Todo
				VALUES ( '5', 1, 'ask', 'This is a test', '0' )
			",
			"66, 67, 6, 0, 58, 46, 4, 1, 68, 9, 6, 2, 19, 5, 3, 19, 6, 4, 19, 6, 5, 19, 6, 6, 10, 1",
			array ("Common_Todo"), 
		),

		23 => Array
		(
			"
				DELETE '\RootFolder\Todos'
			",
			"66, 67, 6, 0, 58, 46, 4, 1, 68, 9, 6, 2, 19, 5, 3, 19, 6, 4, 19, 6, 5, 19, 6, 6, 10, 1",
			array ("Common_Todo"), 
		),

		24 => Array
		(
			"
				DELETE 
				FROM '\RootFolder\Todos'
				USING CLASS Common_Todo
			",
			"66, 67, 6, 0, 58, 46, 4, 1, 68, 9, 6, 2, 19, 5, 3, 19, 6, 4, 19, 6, 5, 19, 6, 6, 10, 1",
			array ("Common_Todo"), 
		),

		241 => Array
		(
			"
				DELETE 
				FROM '\RootFolder\Todos'
				USING CLASS Common_Todo
				WHERE
					Common_Todos CONTAINS Common_Todo
			",
			"66, 67, 6, 0, 58, 46, 4, 1, 68, 9, 6, 2, 19, 5, 3, 19, 6, 4, 19, 6, 5, 19, 6, 6, 10, 1",
			array ("Common_Todo"), 
		),

		242 => Array
		(
			"
				DELETE 
				FROM '\RootFolder\Todos'
				USING CLASS Common_Todo
				WHERE
					Common_Todos CONTAINS Common_Todo AND
					Common_Todo.dTarget BETWEEEN #2015-01-20# AND #2015-01-21#
			",
			"66, 67, 6, 0, 58, 46, 4, 1, 68, 9, 6, 2, 19, 5, 3, 19, 6, 4, 19, 6, 5, 19, 6, 6, 10, 1",
			array ("Common_Todo"), 
		),

		25 => Array
		(
			"
				UPDATE folder
				SET assignments
			",
			"66, 67, 6, 0, 58, 46, 4, 1, 68, 9, 6, 2, 19, 5, 3, 19, 6, 4, 19, 6, 5, 19, 6, 6, 10, 1",
			array ("Common_Todo"), 
		),

		26 => Array
		(
			"
				UPDATE 
				IN { ID | UNC Path }
				USING CLASS classname
				SET assignments
				[ 
					[ WHERE filter ] 
					[ DEPTH = n ]
				]
			",
			"66, 67, 6, 0, 58, 46, 4, 1, 68, 9, 6, 2, 19, 5, 3, 19, 6, 4, 19, 6, 5, 19, 6, 6, 10, 1",
			array ("Common_Todo"), 
		),

		27 => Array
		(
			"
				MOVE
				FROM '\RootFolder\Todos'
				TO '\RootFolder\Todos2'
			",
			"66, 67, 6, 0, 58, 46, 4, 1, 68, 9, 6, 2, 19, 5, 3, 19, 6, 4, 19, 6, 5, 19, 6, 6, 10, 1",
			array ("Common_Todo"), 
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
		
		$t = 211;
		
		$szStmnt = $this->aryStatements [$t][0];
		$szByteCode = $this->aryStatements [$t][1];

		$this->log ("statement", $szStmnt);	
		
		$nExit = gblParser()->process ($szStmnt);
		if (gblError()->No() != 0)
		{
			$this->logError (gblError());
			// $this->logTrace (gblTraceLog()->getLog());
			$this->log ("TraceLog", "<BR>\r\n" . implode ("<BR>\r\n", gblTraceLog()->getLog() ) ); 
			// $this->log ("statement", substr ($szStmnt, gblError()->SQL() ) ); 
		}

		$this->log ("TraceLog", "<BR>\r\n" . implode ("<BR>\r\n", gblTraceLog()->getLog() ) ); 

		$szStmnt = trim ( ($szStmnt . " [eof]"), " \t\n\r\0\x0B" );
		
		$this->log ("statement", strtolower($szStmnt));	
		$this->log ("statement", strtolower(gblParser()->genStatement()));
		$bMatches = (strtolower ($szStmnt) == strtolower (gblParser()->genStatement()));
		$this->assert ($bMatches, "01 - the statements match");	
		
		// ===================
		
		$bStatus = (gblError()->No() == 0);
		
		$szCode = gblScanner()->ByteCode()->toString();
		
		$this->log ("Exit Point", $nExit);	
		$this->log ("ByteCoce", $szCode);	
		
		$this->assert ($bStatus, "01 - the Parser ran successfully");	
		$this->assert ($szByteCode == $szCode, "01 - The ByteCode matches");
	}
	
	
}

test_Params::docFoo();

?>
