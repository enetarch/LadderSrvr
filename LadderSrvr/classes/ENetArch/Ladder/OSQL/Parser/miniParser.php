<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Ladder\OSQL\Parser;

use \ENetArch\Common\DocFoo;

use \ENetArch\Ladder\OSQL\Parser\ErrorMsgs as ErrorMsgs;

use \ENetArch\Ladder\OSQL\Parser\TokenCodes;
use \ENetArch\Ladder\OSQL\Scanner\ReservedWords;
use \ENetArch\Ladder\OSQL\Scanner\Scanner;

docFoo 
( Array
(
	"Class" => "miniParser",
	
	"Description" => 
		"Confirm the OSQLs syntax correctness",


	"Requirements" => Array (),

	"Includes" => Array
	(
		"ByteCode - Manages the storage, update and retrieval of byte codes identified during the scanning process.",
		"ErrorMsgs - List of Error Messages used by the ByteCode class",
		"ReservedWords - A list of keywords for the scanner to look for",
		"Token - Token is used by the Parser when passing values ",
		"TokenCodes - A list of Tokens that the scanner is searching for.",
	),

	"Globals" => Array
	(
		"ErrorMsgs - List of Error Messages used by the ByteCode class",
	),

	"Methods" => Array
	(
	),
	
	"Properties" => Array
	(
	),
		
	"Errors" => Array
	(
	),

	"Usage" =>
<<<HEREDOC

"template" :
{ 
	{ "do" : [ "function(s)", ] }, 
	{ "if" : [ [ [ "token(s)", ], "function", "exit-token" ], "else-function" ] } , 
	{ "select" : [ [ [ "token(s)", ], "function", "exit-token" ], "default-function" ] } ,
	{ "optional" : [ [ "token", function ], ... ] } ,
	{ "either" : [ [ "token", function ], ... ] } ,
	{ "until" : [ "function", [ "token(s)" ] ] } , 
	{ "while" : [ [ "token(s)" ], "function", "exit-token" ] } , 
	{ "param" : [ ] } , 
	{ "error" : [ "error message" ] } , 
}

HEREDOC
,	
	"Comments" => Array
	(
		"do - does not consume tokens",
		"optional - does not consume tokens", 
		
		"param - consumes a token", 
		"if - only consumes tokens that match the conditional clause",
		"select - only consumes tokens that match the conditional clauses",
		"optional - only consumes tokens that match the conditional clauses",
		"either - only consumes tokens that match the conditional clauses",
		"until - only consumes tokens that match the conditional clauses",
		"while - only consumes tokens that match the conditional clauses",
		
		"error - reports an error in the syntax submitted",
	),
	
	"Tests" => Array
	(
	),
));




class miniParser
{
	public $scanner = null;
	public $tree = null;
	public $params = array ();
	public $RsvrWords = null; 
	private $szOSQL = "";
	private $cb = null;
	
	public $err = 0;
	public $errmsg = "";
	
	private $statePath = [];
	private $tokenValues = [];
	private $state = [];
	
	// ============================================
	
	public static function docFoo_docFoo ()
	{ docFoo ( Array 
		(
			"Method" => "docFoo ()",
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
		
		self::docFoo_construct ();
		self::docFoo_ByteCode ();
		self::docFoo_getToken ();
		self::docFoo_tokenConsumed ();
		self::docFoo_process ();
		self::docFoo_parse ();
		self::docFoo_call_cb_foo ();
		self::docFoo_execDo ();
		self::docFoo_execIf ();
		self::docFoo_execSwitch ();
		self::docFoo_execOptional ();
		self::docFoo_execEither ();
		self::docFoo_save_state ();
		self::docFoo_commit_state ();
		self::docFoo_reset_state ();
		self::docFoo_execUntil ();
		self::docFoo_execWhile ();
		self::docFoo_getParam ();
		self::docFoo_execError ();
		self::docFoo_getBranch ();
		self::docFoo_genError ();
		self::docFoo_genError_NotFound_IF ();
		self::docFoo_genError_NotFound_Switch ();
		self::docFoo_init_Tokens ();
		self::docFoo_addToken ();
	}

	// ============================================
	
	public static function docFoo_construct ()
	{ docFoo ( Array 
		(
		"Method" => "__construct",
		"Syntax" => "__construct (cb)",
		"Parameters" => Array 
		(
			"cb" => "The callback object is used to transform the OSQL 
					statement into another form for execution. It will 
					recieve messages when the parser enters, exits and 
					loops through various language syntax structures",
		),
		"Description" => "Initialize an instance of the Parser",
		"Core Code" => 
			"
				Save the Call Back object.
				Retrieve the language syntax from OSQL_syntax.json.
				Store the json syntax into a local tree.
				Initialize the Reserved Words.
				Initialize the Scanner.
			",
		"Tests" => Array 
			(
				"Confirm that all objects are initialized",
			),
	 ) );
	}
	
	function __construct ($cb = null)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->cb = $cb;
		
		$syntax = file_get_contents ("OSQL_syntax.json", true);
		
		$tree = json_decode ($syntax, true);
		if (json_last_error())
		{
			print ("json error (" . json_last_error() . ") " . json_last_error_msg () . "<P>");
			
			print ("osql = ");
			print_r ($syntax);
			print ("<BR>");

			print ("tree = <pre>");
			print_r ($tree);
			print ("</pre><BR>");
		}

		$this->tree = $tree;
		$this->init_Tokens();

		$this->scanner = New \ENetArch\Ladder\OSQL\Scanner\Scanner ();
	}
	
	// ============================================

	public static function docFoo_ByteCode ()
	{ docFoo ( Array 
		(
		"Property" => "ByteCode",
		"Syntax" => "ByteCode ()",
		"Description" => "Initialize an instance of the Scanner",
		"Returns" => "The byte code container",
		"Tests" => Array
			(
				"Confirm the bytecode is correct",
			),
		) );
	}

	function ByteCode ()
	{ return ($this->scanner->ByteCode()); }
	
	// ============================================

	public static function docFoo_getToken ()
	{ docFoo ( Array 
		(
		"Method" => "getToken",
		"Syntax" => "getToken ()",
		"Description" => "retrieves a token from the Scanner and places 
			it in the tokens values array as long as the parser is not 
			in an Either Statement",
		"Core Code" => 
			"
				get token from scanner
				if not in an Either Statement
					place token in tokens array
			",
		"Returns" => "Nothing",
		"Tests" => Array
			(
				"Confirm the token is correct",
				"Confirm the token is added to the tokens array when not in an either statement",
				"Confirm the token is not added to the tokens array when in an either statement",
			),
		) );
	}

	function getToken ()
	{
		$this->scanner->getToken();
		$token = $this->scanner->token;

		if (!$this->inEither())
			$this->tokenValues[] = $token->value;
	}
	
	// ============================================

	public static function docFoo_tokenConsumed ()
	{ docFoo ( Array 
		(
		"Method" => "tokenConsumed",
		"Syntax" => "tokenConsumed ()",
		"Description" => "Pop the last token off the token values array",
		"Core Code" => 	"Pop the last token off the token values array",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the token is removed",
			),
		) );
	}

	function tokenConsumed () { array_pop ($this->tokenValues); }
	
	// ============================================

	public static function docFoo_process ()
	{ docFoo ( Array 
		(
		"Method" => "process",
		"Syntax" => "process (szOSQL)",
		"Description" => "Initialize an instance of the Scanner",
		"Parameters" => Array
		(
			"szOSQL" => "The string to parse",
		),
		"Core Code" => 
			"
				Save the OSQL string.
				Initialize the Scanner.
				Get the first token from the scanner.
				Start at the root of the Syntax Tree and parse the 
					OSQL statement.
			",
		"Returns" => "true - that the string was successfully parsed",
		"Tests" => Array
			(
				"Parse correct OSQL strings",
				"Parse incorrect OSQL strings",
			),
		) );
	}

	function process ($szOSQL)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->szOSQL = $szOSQL;
		
		$this->scanner->init ($szOSQL, $this->RsvrWords );
		
		$this->getToken();
		
		$this->parse ("root");
		
		return (true);
	}
	
	// =================================================================
	// =================================================================

	public static function docFoo_parse ()
	{ docFoo ( Array 
		(
		"Method" => "parse",
		"Syntax" => "parse (branch)",
		"Parameters" => Array
		(
			"branch" => "the branch in the syntax tree to work on",
		),
		"Description" => "Initialize an instance of the Scanner",
		"Core Code" => 
			"
				Get the branch from the tree
				Get the current token from the Scanner
				Call the Callback before entering the node
				Execute the nodes function 
				Call the Callback after exiting the node
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the branch is parsed",
				"Confirm callback of in and out methods",
				"Confirm control functions are called",
			),
		) );
	}

	
	function parse ($branch)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$node = $this->getBranch ($branch);

		$token = $this->scanner->token;
		$tokenCode = $token->value;
		
		$json = json_encode ($node, JSON_PRETTY_PRINT);
		print ("<p> ==> " . $branch . " ( " . $token->value . " ) := " . $json . "</p>");
		
		$this->call_cb_in ($branch, $tokenCode);
		
		$key = array_keys ($node)[0];
		switch ($key)
		{
			case "do" : $this->execDo ($node); break;
			case "if" : $this->execIf ($node, $branch); break;
			case "switch" : $this->execSwitch ($node, $branch); break;
			case "optional" : $this->execOptional ($node, $branch); break;
			case "until" : $this->execUntil ($node, $branch); break;
			case "while" : $this->execWhile ($node, $branch); break;
			case "either" : $this->execEither ($node, $branch); break;
			
			case "param" : $this->getParam (); break;

			case "getIdentifier" : $this->getIdentifier ($node); break;
			case "getNumber" : $this->getPbyType ($node, [TokenCodes::tcNUMBER]); break;
			case "getString" : $this->getPbyType ($node, [TokenCodes::tcSTRING]); break;
			case "getValue" : $this->getPbyType ($node, [TokenCodes::tcNUMBER, TokenCodes::tcSTRING]); break;

			case "error" : $this->execError ($node); break;
			
			default : $this->genError();
		}
		
		$this->call_cb_out ($branch, $tokenCode);
		
		print ("<p> <== " . $branch . " ( " . $token->value . " ) := " . $json . "<p>");
	}

	// =================================================================

	public static function docFoo_call_cb_foo ()
	{ docFoo ( Array 
		(
		"Method" => "call_cb_foo",
		"Syntax" => "call_cb_foo (branch, tokenCode)",
		"Parameters" => Array
		(
			"branch" => "the current branch in the syntax tree being worked on",
			"tokenCode" => "the current token from the scanner being processed",
		),
		"Description" => "Call the callback object to consume the current 
			state of the parser",
		"Core Code" => 
			"
				Build a method name based on branch and state [ in, out, process, loop, exit ]
				Confirm the method exists in the callback object
				Call the method, passing tokenvalues and parameters
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the correct callback is called",
				"Confirm the callbacks are called in order",
			),
		) );
	}


	function call_cb_in ($branch, $tokenCode)
	{ $this->call_cb_foo ($branch, $tokenCode, "_in"); }

	function call_cb_pre_in ($branch, $tokenCode)
	{ $this->call_cb_foo ($branch, $tokenCode, "_pre_in"); }

	function call_cb_matched_in ($branch, $function, $tokenCode)
	{ $this->call_cb_foo ($branch, $tokenCode, "_matched_" . $function . "_in"); }

	function call_cb_matched_out ($branch, $function, $tokenCode)
	{ $this->call_cb_foo ($branch, $tokenCode, "_matched_" . $function . "_out"); }

	function call_cb_pre_out ($branch, $tokenCode)
	{ $this->call_cb_foo ($branch, $tokenCode, "_pre_out"); }

	function call_cb_out ($branch, $tokenCode)
	{ $this->call_cb_foo ($branch, $tokenCode, "_out"); }

	function call_cb ($branch, $tokenCode)
	{ $this->call_cb_foo ($branch, $tokenCode, ""); }

	function call_cb_loop ($branch, $tokenCode)
	{ $this->call_cb_foo ($branch, $tokenCode, "_loop"); }

	function call_cb_exit ($branch, $tokenCode)
	{ $this->call_cb_foo ($branch, $tokenCode, "_exit"); }
	
	function call_cb_param ($token)
	{
		if (!$this->inEither())
		if ($this->cb != null)
			if (method_exists ($this->cb, "param_token"))
				$this->cb->param_token ($token);
	}
	
	function call_cb_foo ($branch, $tokenCode, $imo)
	{
		$branch = $branch . $imo;
		
		if (!$this->inEither())
		if ($this->cb != null)
			if (method_exists ($this->cb, $branch))
			{
				print ("<P style='color:red;'>calling - " .	$branch . " ( <BR>");
				print ("tokenCode = " . $tokenCode . " <BR>"); 
				print ("tokenCodes = [ " . implode (", ", $this->tokenValues) . " ] <BR>");
				print ("params = [ " . implode (", ", $this->params) . " ] ) </P>");
				
				$this->cb->$branch ($tokenCode);
			}
	}


	// =================================================================
	// do : { function(s) }
	
	public static function docFoo_execDo ()
	{ docFoo ( Array 
		(
		"Method" => "execDo",
		"Syntax" => "ByteCode ()",
		"Description" => "Initialize an instance of the Scanner",
		"Core Code" => 
			"
				get token from scanner
				if not in an Either Statement
					place token in tokens array
			",
		"Returns" => "The byte code container",
		"Tests" => Array
			(
				"Confirm the bytecode is correct",
			),
		) );
	}

	function execDo ($node)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$do = $node["do"];
		if (!is_array ($do))
			$do = Array ($do);

		forEach ($do AS $key => $value)
			$this->parse ($value);
	}
	
	// =================================================================
	// if : { { { token(s) }, function, exit-token }, else-function } , 

	public static function docFoo_execIf ()
	{ docFoo ( Array 
		(
		"Method" => "execIf",
		"Syntax" => "ByteCode ()",
		"Description" => "Initialize an instance of the Scanner",
		"Core Code" => 
			"
				get token from scanner
				if not in an Either Statement
					place token in tokens array
			",
		"Returns" => "The byte code container",
		"Tests" => Array
			(
				"Confirm the bytecode is correct",
			),
		) );
	}

	function execIf ($node, $branch)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$token = $this->scanner->token;
		
		$do = $node["if"];

		$match = $do[0][0];
		$function = $do[0][1];
		$exitmatch = isset ($do[0][2]) ? $do[0][2] : "";
		$else = isset ($do[1]) ? $do[1] : "";
		
		if (!is_array ($match))
			$match = Array ($match);

		$match = array_map ("strtoupper", $match);
		$savedToken = $token->value;

		if (in_array (strtoupper($token->value), $match))
		{ 
			$this->call_cb_matched_in ($branch, $function, $savedToken);
			$this->call_cb_pre_in ($function, $savedToken);
			
			$this->getToken();
			$this->parse ($function); 
			
			if (strlen ($exitmatch) > 0)
			{
				$token = $this->scanner->token;
				print ("exitmatch [ " . $exitmatch . " ] == token.value [ " . $token->value . " ]<BR>");
				
				if ($token->value != $exitmatch)
					$this->genError_NotFound_IF ($node);

				$this->call_cb_exit ($branch, $token->value);
				$this->getToken();
			}

			$this->call_cb_pre_out ($function, $savedToken);
			$this->call_cb_matched_out ($branch, $function, $savedToken);
		}
		else
		{ 
			if (strlen ($else) > 0)
			{
				$this->call_cb_matched_in ($branch, $else, $savedToken);
				$this->call_cb_pre_in ($function, $savedToken);
				
				$this->parse ($else); 
				
				$this->call_cb_pre_out ($function, $savedToken);
				$this->call_cb_matched_out ($branch, $else, $savedToken);
			}
		}
	}

	// =================================================================
	// select : { { { token(s) }, function, { exit token } }, else-case function } ,

	public static function docFoo_execSwitch ()
	{ docFoo ( Array 
		(
		"Method" => "execSwitch",
		"Syntax" => "ByteCode ()",
		"Description" => "Initialize an instance of the Scanner",
		"Core Code" => 
			"
				get token from scanner
				if not in an Either Statement
					place token in tokens array
			",
		"Returns" => "The byte code container",
		"Tests" => Array
			(
				"Confirm the bytecode is correct",
			),
		) );
	}

	function execSwitch ($node, $branch)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$token = $this->scanner->token;
		$savedToken = $token->value;
		
		$do = $node["switch"];

		for ($t=0; $t<Count($do); $t++)
		{
			if (count ($do[$t]) > 1 )
			{
				$match = $do[$t][0];

				if (!is_array ($match))
					$match = Array ($match);

				$match = array_map ("strtoupper", $match);
				
				$function = $do[$t][1];
				
				if (in_array (strtoupper($token->value), $match))
				{ 
					$this->call_cb_matched_in ($branch, $function, $savedToken);
					$this->call_cb_pre_in ($function, $savedToken);
					
					$token = $this->getToken();
					$this->parse ($function); 
					
					$this->call_cb_pre_out ($function, $savedToken);
					$this->call_cb_matched_out ($branch, $function, $savedToken);
					return;
				}
			}
			else
			{ 
				$function = $do[$t][0];
				
				$this->call_cb_matched_in ($branch, $function, $savedToken);
				$this->call_cb_pre_in ($function, $savedToken);
				
				$this->parse ($function); 
				
				$this->call_cb_pre_out ($function, $savedToken);
				$this->call_cb_matched_out ($branch, $function, $savedToken);

				return;
			}
		}
		
		$this->genError_NotFound_Switch ($node);
	}
	
	// =================================================================
	// optional : { { token, function, exit-token }, ... } ,
	
	public static function docFoo_execOptional ()
	{ docFoo ( Array 
		(
		"Method" => "execOptional",
		"Syntax" => "execOptional (node)",
		"Description" => "Some parts of the syntax are optional if present",
		"Core Code" => 
			"
				for each KeyWord
					if a match is found
						parse this OSQL command string 
					else
						parse the last path 
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm multiple optional paths",
				"Confirm no path works",
				"Confirm the string contains non of the optional keywords",
			),
		) );
	}

	function execOptional ($node, $branch)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$do = $node["optional"];
		
		do
		{
			$matched = false;
			$token = $this->scanner->token;
			$savedToken = $token->value;

			for ($t=0; $t<Count($do); $t++)
			{
				$match = strtoupper ($do[$t][0]);
				
				if (isset ($do[$t][1]))
				{
					$function = $do[$t][1];

					if (strtoupper($token->value) == $match)
					{ 
						$this->call_cb_matched_in ($branch, $function, $savedToken);
						$this->call_cb_pre_in ($function, $savedToken);

						$bmatched = true;
						$this->getToken();
						$this->call_cb ($function, $token->value);
						$this->parse ($function); 
						$token = $this->scanner->token;

						$this->call_cb_pre_out ($function, $savedToken);
						$this->call_cb_matched_out ($branch, $function, $savedToken);
					}
				}
				else
				{
					$this->call_cb_matched_in ($branch, $function, $savedToken);
					$this->call_cb_pre_in ($function, $savedToken);

					$bmatched = false;
					$function = $do[$t][0];
					$this->call_cb ($function, $token->value);
					$this->parse ($function); 
					$token = $this->scanner->token;

					$this->call_cb_pre_out ($function, $savedToken);
					$this->call_cb_matched_out ($branch, $function, $savedToken);					
				}
			}			
		} while ($matched);
	}
	
	// =================================================================
	// either : { function, ... } ,
	
	public static function docFoo_inEither ()
	{ docFoo ( Array 
		(
		"Property" => "inEither",
		"Description" => "used to indicate when the parser is in an EITHER statement.",
		));
	}
	private $inEither = false;
	
	// ============================================

	public static function docFoo_execEither ()
	{ docFoo ( Array 
		(
		"Method" => "execEither",
		"Syntax" => "execEither (node)",
		"Description" => "Either allows a command string to flow down
			multiple paths until a path is found, or an error is reached",
		"Core Code" => 
			"
				Get the current token from the Scanner.
				For Each Path
					Tell the object that we are in EITHER
					Save the current state
					Parse the Command String
					Get the current token
					If an error occures, reset the state
					If no error is found, break from the loop
				Clear EITHER
				Tell the Callback Object that we are in EITHER
				Parse the Command string based on teh correct path
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm that the correct path can be found",
				"Confirm that multiple paths can be parsed",
				"Confirm that an error is generated if no path is found",
			),
		) );
	}

	function execEither ($node, $branch)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$token = $this->scanner->token;
		$json = json_encode ($node, JSON_PRETTY_PRINT);
		$do = $node["either"];
		
		if (!is_array ($do))
			$do = Array ($do);

		forEach ($do AS $key => $value)
		{
			$node = $value;
			print ("<h2 style='color:blue;'>" . $branch . " .. saving state ( " . count ($this->state) . " )</h2>");
			print ("<p style='color:blue;'>===> " . $json . " </p>");
			print ("<p style='color:blue;'>===> " . $value . " </p>");
			
			$this->save_state ();
			
			$this->parse ($value);

			$token = $this->scanner->token;
			
			print ("<h2 style='color:blue;'>" . $branch . " .. returning " . $json . " </h2>");
			print ("<p style='color:blue;'>either-returned err = " . $this->err . " </p>");
			print ("<p style='color:blue;'>either-err msg = " . $this->errmsg . " </p>");
			print ("<p style='color:blue;'>either-returned Token = " . $token->code . " '" . $token->value . "' </p>");
			
			if ($this->err == 0)
			{ 
				print ("<h2 style='color:blue;'>" . $branch . " .. committing state ( " . count ($this->state) . " )</h2>");
				
				if (count ($this->state) > 1)
				{ $this->commit_state(); }
				else
				{ $this->reset_state(); }
				break;
			}
			else
			{ 
				print ("<h2 style='color:blue;'>" . $branch . " .. resetting state ( " . count ($this->state) . " )</h2>");
				$this->reset_state();
				$this->err = 0;
			}

			print ("<p style='color:blue;'>" . $branch . " .. reset Token = " . $token->code . " '" . $token->value . "' </p>");
		}
		
		print ("<p style='color:magenta;'>" . $branch . " .. state.count = " . count ($this->state) . "' </p>");
		
		if (count ($this->state) == 0)
		if ($this->err == 0)
		{
			print ("<p style='color:orange;'>executing branch = " . $node . "' </p>");
			print ("<p style='color:orange;'>state.count = " . count ($this->state) . "' </p>");
			
			$savedToken = $token->value;
			$function = $node;
			
			$this->call_cb_matched_in ($branch, $function, $savedToken);
			$this->call_cb_pre_in ($function, $savedToken);
				
			$this->call_cb ($node, $token->value);
			$this->parse ($node);

			$this->call_cb_pre_out ($function, $savedToken);
			$this->call_cb_matched_out ($branch, $function, $savedToken);
		}
	}

	// ============================================

	public static function docFoo_save_state ()
	{ docFoo ( Array 
		(
		"Method" => "save_state",
		"Syntax" => "save_state ()",
		"Description" => "Copies the state of the scanner and params to state array",
		"Core Code" => 
			"
				Copy the state of the scanner and params to state array
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the states are copied",
			),
		) );
	}

	function save_state () 
	{
		$this->state[] = 
		[
			"scanner" => $this->scanner->getState(),
			"params" => $this->params,
		];

		$this->print_vmVar_Var ("===> state.nPos " . count ($this->state), $this->scanner->Position());
		$this->print_vmVar_Array ("===> state.Token ", $this->scanner->token);
	}

	function inEither ()
	{ return (count ($this->state) > 0); }

	// ============================================

	public static function docFoo_commit_state ()
	{ docFoo ( Array 
		(
		"Method" => "commit_state",
		"Syntax" => "commit_state ()",
		"Description" => "This discards any saved states for ByteCode and 
		Params thus making their current state the existing state.",
		"Core Code" => 
			"pop the last saved state from the state array",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the state is removed",
			),
		) );
	}

	function commit_state () 
	{ 
		array_pop ($this->state); 

		$this->print_vmVar_Var ("<=<=>=> state.nPos " . count ($this->state), $this->scanner->Position());
		$this->print_vmVar_Array ("===> state.Token ", $this->scanner->token);
	}

	// ============================================

	public static function docFoo_reset_state ()
	{ docFoo ( Array 
		(
		"Method" => "reset_state",
		"Syntax" => "reset_state ()",
		"Description" => "Pops the curent state from the Array of States",
		"Globals" => Array
		(
			"State" => "The state of the scanner and parameters",
			"ByteCode" => "The scanner's bytecode object",
			"Params" => "The parser's parameters"
		),
		"Core Code" => 
			"
				Pop the last state off the State stack.
				Update teh Scanner's ByteCode object.
				Update the Parser's Params object.
			",
		"Returns" => "nothing",
		"Comments" => "From time to time it is necessary to traverse 
			multiple - optional - paths in order to determine which 
			syntax path is correct.  When a path doesn't match, it is 
			errored out, and these variables are reset. If none of the 
			paths match, then an error is generated.",
		"Tests" => Array
			(
				"Confirm the bytecode is correct",
			),
		) );
	}

	function reset_state () 
	{
		$this->print_vmVar_Var ("<=== state.nPos " . count ($this->state), $this->scanner->Position());

		$aryState = array_pop ($this->state);
		
		$this->scanner->setState ($aryState ["scanner"]);
		$this->params = $aryState ["params"];
		
		print ("Scanner state reset!<BR>");
		$this->print_vmVar_Var ("===> state.nPos " . count ($this->state), $this->scanner->Position());
		$this->print_vmVar_Array ("===> state.Token ", $this->scanner->token);
	}

	// =================================================================
	// until : { function, { token(s) }, { exit token } } , 

	public static function docFoo_execUntil ()
	{ docFoo ( Array 
		(
		"Method" => "execUntil",
		"Syntax" => "execUntil (node, branch)",
		"Parameters" => Array
		(
			"node" => "The current node in the Syntax Tree being processed",
			"branch" => "The current branch in the Syntax Tree that called the Node",
		),
		"Description" => "Loop through they OSQL command string as long as the
			syntax matches the WHILE node being executed.",
		"Core Code" => 
			"
				Get the current Token from the Scanner.
				Get the details of the WHILE node.
					What should the WHILE match?
					Which NODE should the WHILE call in it's loops?
				
				Loop .. 
					if the current token matches the MATCH value
						get the next token from the Scanner.
					Call the NODE identified above.
					Update the Token from the scanner.
					Call the CB object's matching node and loop method.
					.. While the current Token match the MATCH value

			",
		"Comment" => "I'm not sure when to use a WHILE over an UNTIL loop
			at the moment.  I'm sure there's a syntax difference.",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the UNTIL loop syntax works correctly",
				"Confirm the callback loop's method is called.",
				"Confirm errors in the OSQL command are caught",
			),
		) );
	}

	function execUntil ($node, $branch)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$token = $this->scanner->token;
		
		$do = $node["until"];

		$function = $do[0];
		$match = $do[1];

		if (!is_array ($match))
			$match = Array ($match);
		
		$match = array_map ("strtoupper", $match);
			
		do
		{ 
			if (in_array (strtoupper ($token->value), $match))
				$this->getToken();
			
			$this->parse ($function);

			$token = $this->scanner->token;
			$savedToken = $token->value;
			
			$this->call_cb_loop ($branch, $token->value);

		} while (in_array (strtoupper ($token->value), $match));
	}
	

	// =================================================================
	// while : { { token(s) }, function, { exit token } } , 

	public static function docFoo_execWhile ()
	{ docFoo ( Array 
		(
		"Method" => "execWhile",
		"Syntax" => "execWhile (node, branch)",
		"Parameters" => Array
		(
			"node" => "The current node in the Syntax Tree being processed",
			"branch" => "The current branch in the Syntax Tree that called the Node",
		),
		"Description" => "Loop through they OSQL command string as long as the
			syntax matches the WHILE node being executed.",
		"Core Code" => 
			"
				Get the current Token from the Scanner.
				Get the details of the WHILE node.
					What should the WHILE match?
					Which NODE should the WHILE call in it's loops?
				
				Call the NODE identified above.
				Update the Token from the scanner.
				While the current Token matches the MATCH value ..
					Call the CB object's matching node and loop method.
					Call the NODE identified above.
					Update the Token from the scanner.
					.. Loop
			",
		"Returns" => "nothing",
		"Comments" =>  "Node and Branch are synanyms for the same type of
			element in the syntax tree. Their difference is that they 
			point to two different points in the syntax tree.",
		"Tests" => Array
			(
				"Confirm the WHILE loop syntax works correctly",
				"Confirm the callback loop's method is called.",
				"Confirm errors in the OSQL command are caught",
			),
		) );
	}

	function execWhile ($node, $branch)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$token = $this->scanner->token;
		$do = $node["while"];

		$match = $do[0];

		if (!is_array ($match))
			$match = Array ($match);

		$match = array_map ("strtoupper", $match);

		$function = $do[1];

		// $this->call_cb ($function, $token->value);
		$this->parse ($function); 
		$token = $this->scanner->token;
		$savedToken = $token->value;
		
		if (! (in_array (strtoupper ($savedToken), $match)) )
			$this->call_cb_exit ($branch, $savedToken);

		while (in_array (strtoupper ($token->value), $match))
		{ 
			$savedToken = $token->value;
			
			$this->getToken();
			$this->parse ($function); 
			$this->call_cb_loop ($branch, $savedToken);
			
			$token = $this->scanner->token;
		} 
	}
	
	// =================================================================
	// =================================================================

	// "getIdentifier" : { "do": [ "param" ], "comment" : "param.type = 4" },

	public static function docFoo_getIdentifier ()
	{ docFoo ( Array 
		(
		"Method" => "getIdentifier",
		"Syntax" => "getIdentifier ($node)",
		"Description" => "Retrieves a parameter from the OSQL command string, 
		and confirms that it is an IDENTIFIER or a keyword",
		"Globals" => Array
		(
			"params" => "A list of Parameters identified by the OSQL 
				syntax in the OSQL command string",
		),
		"Core Code" => 
			"
				Get the current Token from the Scanner.
				Confirm it is the correct type .. IDENTIFY, NUMBER, or STRING
				if incorrect type
					Call execError 
				else
					Call getParam
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the parameter is added to the Parameters List",
				"Confirm the parameter is added to the ByteCode",
				"Confirm the token being access is a parameter, based on the OSQL syntax",
				"Confirm the next token is retrieved",
			),
		) );
	}

	function getIdentifier ($node)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$token = $this->scanner->token;
		
		if 
		(
			($token->code != TokenCodes::tcIDENTIFIER) &&
			($token->code < 10)
		)
			$this->execError ($node);
		
		$this->getParam();
	}

	// "getNumber" : { "do": [ "param" ], "comment" : "param.type = 5" },
	// "getString" : { "do": [ "param" ], "comment" : "param.type = 6" },
	// "getValue" : { "do": [ "param" ], "comment" : "param.type = 5 or 6" },

	public static function docFoo_getPbyType ()
	{ docFoo ( Array 
		(
		"Method" => "getPbyType",
		"Syntax" => "getPbyType ($node, $types)",
		"Description" => "Retrieves a parameter from the OSQL command string, 
		and confirms that it is the correct type .. IDENTIFIER, NUMBER or STRING",
		"Globals" => Array
		(
			"params" => "A list of Parameters identified by the OSQL 
				syntax in the OSQL command string",
		),
		"Core Code" => 
			"
				Get the current Token from the Scanner.
				Confirm it is the correct type .. IDENTIFY, NUMBER, or STRING
				if incorrect type
					Call execError 
				else
					Call getParam
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the parameter is added to the Parameters List",
				"Confirm the parameter is added to the ByteCode",
				"Confirm the token being access is a parameter, based on the OSQL syntax",
				"Confirm the next token is retrieved",
			),
		) );
	}

	function getPbyType ($node, $types)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$token = $this->scanner->token;
		
		if (!in_array ($token->code, $types))
			$this->execError ($node);
		
		$this->getParam();
	}


	// =================================================================
	// param : {}
	
	public static function docFoo_getParam ()
	{ docFoo ( Array 
		(
		"Method" => "getParam",
		"Syntax" => "getParam ()",
		"Description" => "Retrieves a parameter from the OSQL command string",
		"Globals" => Array
		(
			"params" => "A list of Parameters identified by the OSQL 
				syntax in the OSQL command string",
		),
		"Core Code" => 
			"
				Get the current Token from the Scanner.
				Add the Token to the Parameters List.
				Add the Token's place in the Parameters List to the ByteCode.
				Get the next Token.
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the parameter is added to the Parameters List",
				"Confirm the parameter is added to the ByteCode",
				"Confirm the token being access is a parameter, based on the OSQL syntax",
				"Confirm the next token is retrieved",
			),
		) );
	}

	function getParam ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$token = $this->scanner->token;
		
		$nCount = count ($this->params);
		$this->params [] = $token->value;
		$this->scanner->ByteCode()->AddItem($nCount);
		$this->call_cb_param ($token);
		
		$this->getToken();
	}
	
	// =================================================================
	// error : {}
	
	public static function docFoo_execError ()
	{ docFoo ( Array 
		(
		"Method" => "execError",
		"Syntax" => "execError (node)",
		"Description" => "Set the Error Object with an error message.",
		"Core Code" => 
			"
				Get the current token value.
				Create an error message concerning the token, what was 
					expected, and where it was found.
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the error in syntax logic is reached",
			),
		) );
	}

	function execError ($node)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$token = $this->scanner->token;
		
		$this->err = -1;
		$this->errMsg = 
			"<P>" . 
			"Found token " . $token->value . "<BR>" . 
			"Error " . $this->scanner->Position() . "<BR>" . 
			"SQL = " . $this->szOSQL . "<BR>" . 
			"stopped = " . substr ($this->szOSQL, 0, $this->scanner->Position()) . "<BR>" . 
			"error .. " . $node["error"][0] .
			"<P>";

		print ("<P style='color:red;'>execError <BR>");
		print ("tokenCodes = [ " . implode (", ", $this->tokenValues) . " ] <BR>");
		print ("params = [ " . implode (", ", $this->params) . " ] ) </P>");
	}
	
	
	// =================================================================

	public static function docFoo_getBranch ()
	{ docFoo ( Array 
		(
		"Method" => "getBranch",
		"Syntax" => "getBranch (key)",
		"Parameters" => Array
		(
			"key" => "the branch being saught",
		),
		"Description" => "return the branch",
		"Globals" => Array
		(
			"tree" => "",
		),
		"Core Code" => 
			"
				Determine if the branch exists in the tree.
				return teh branch if found
				if not found
					generate an error message
					stop execution
			",
		"Returns" => "the branch",
		"Tests" => Array
			(
				"Confirm the branches are found and returned",
				"Confirm that branches not found generate errors",
			),
		) );
	}

	function getBranch ($key)
	{
		gblTraceLog()->Log ("calling ", $key);
		$token = $this->scanner->token;
		// print ("calling - " . $key . " - @ " . $this->scanner->Position() .", token = " . $token->value ."<BR>");
		
		if (isset ($this->tree [$key]) )
		{
			// print ($key . " = " . json_encode ($this->tree [$key]) . "<BR>");
			return ($this->tree [$key]);
		}
	
		print ("<div style='color:red;'>");

		print ("Error " . $this->scanner->Position() . "<p>");
		print ("Branch Not Found = " . $key . "<p>");
		print ("stopped = " . substr ($this->szOSQL, 0, $this->scanner->Position()) . "<p>");

		print ("tokenCodes = [ " . implode (", ", $this->tokenValues) . " ] <p>");
		print ("params = [ " . implode (", ", $this->params) . " ] ) <p>");
		print ("</div>");
		
		exit (); 		
	}

	// ============================================

	public static function docFoo_genError ()
	{ docFoo ( Array 
		(
		"Method" => "genError",
		"Syntax" => "genError ()",
		"Description" => "Generate an error and stop execution",
		"Core Code" => 
			"
				Print an error message.
				STOP execution
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm an error is generated, when there is an error 
					in the syntax tree - ex, an invalid control command 
					is used.",
			),
		) );
	}

	function genError ()
	{ 
		print ("<div style='color:red;'>");

		print ("Error " . $this->scanner->Position() . "<p>");
		print ("SQL = " . $this->szOSQL . "<p>");
		print ("Stopped = " . substr ($this->szOSQL, 0, $this->scanner->Position()) . "<p>");

		print ("tokenCodes = [ " . implode (", ", $this->tokenValues) . " ] <p>");
		print ("params = [ " . implode (", ", $this->params) . " ] ) <p>");
		print ("</div>");
		
		// exit (); 
	}

	// ============================================

	public static function docFoo_genError_NotFound_IF ()
	{ docFoo ( Array 
		(
		"Method" => "genError_NotFound_IF",
		"Syntax" => "genError_NotFound_IF (node)",
		"Parameters" => Array
		(
			"node" => "the name of the node being saught",
		),
		"Description" => "Generate an error message and stop execution",
		"Core Code" => 
			"
				Print an error message.
				Print a core dump of the last node being processed.
				STOP execution
			",
		"Returns" => "noting",
		"Tests" => Array
			(
				"Confirm the right error is produced for missing nodes",
			),
		) );
	}

	function genError_NotFound_IF ($node)
	{ 
		$token = $this->scanner->token;
		
		print ("<div style='color:red;'>");
		
		print ("Found token " . $token->value . "<p>");
		print ("Error " . $this->scanner->Position() . "<p>");
		print ("SQL = " . $this->szOSQL . "<p>");
		print ("Stopped = " . substr ($this->szOSQL, 0, $this->scanner->Position()) . "<p>");

		print ("tokenCodes = [ " . implode (", ", $this->tokenValues) . " ] <p>");
		print ("params = [ " . implode (", ", $this->params) . " ] ) <P>");
		
		print ("Expecting .. ");
		
		$match = (isset ($node["if"])) ? $node["if"][0][0] : "";
		$match = (isset ($node["while"])) ? $node["while"][0] : $match;
		$match = (isset ($node["until"])) ? $node["until"][1] : $match;
		
		$exitmatch = (isset ($node["if"][0][2])) ? $node["if"][0][2] : "";
		
		if (!is_array ($match))
			$match = Array ($match);
				
		print ("[ " . implode (", ", $match) . " ],  ");
		print ("[ " . $exitmatch . " ]");
		
		print ("</div>");
		
		// exit (); 
	}

	// ============================================

	public static function docFoo_genError_NotFound_Switch ()
	{ docFoo ( Array 
		(
		"Method" => "genError_NotFound_Switch",
		"Syntax" => "genError_NotFound_Switch (node)",
		"Parameters" => Array
		(
			"node" => "the name of the node being saught",
		),
		"Description" => "generate an error message for the missing node 
			and stop execution",
		"Core Code" => 
			"
				Print an error message.
				Print a core dump of the last node being processed.
				STOP execution
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Confirm the right error is produced for missing nodes",
			),
		) );
	}

	function genError_NotFound_Switch ($node)
	{ 
		$token = $this->scanner->token;
		
		print ("<div style='color:red;'>");
		print ("Found token " . $token->value . "<p>");
		print ("Error " . $this->scanner->Position() . "<p>");
		print ("SQL = " . $this->szOSQL . "<p>");
		print ("Stopped = " . substr ($this->szOSQL, 0, $this->scanner->Position()) . "<p>");
		
		print ("tokenCodes = [ " . implode (", ", $this->tokenValues) . " ] <p>");
		print ("params = [ " . implode (", ", $this->params) . " ] ) <P>");

		print ("Expecting .. ");
		
		$do = $node["switch"];
		
		forEach ($do AS $key => $value)
		{
			if (count ($value) > 1 )
			{
				$match = $value[0];

				if (!is_array ($match))
					$match = Array ($match);
				
				print (implode (", ", $match) . ", ");
			}
			else
			{
				print (", or something else");
			}
		}
		
		print ("</div>");
		
		// exit (); 
	}


	//*--------------------------------------------------------------
	//*  Initializers
	//*--------------------------------------------------------------

	// ============================================

	public static function docFoo_init_Tokens ()
	{ docFoo ( Array 
		(
		"Method" => "init_Tokens",
		"Syntax" => "init_Tokens ()",
		"Description" => "Initialize the Reserved Word List used by the Scanner",
		"Globals" => Array
		(
			"RsvrWords" => "The list of Reserved Words",
		),
		"Core Code" => 
			"
				Create a new Reserved Word List
				Add each name value pair to the Reserved Word list.
			",
		"Returns" => "nothing",
		"Comments" => "In working with this project I found that it may 
			not be necessary to use a name value pair, but instead just
			use the value, as all the tokens are beig processed by value
			at this time.",
		"Tests" => Array
			(
				"Confirm the name value pairs are added to the Reserved List",
			),
		) );
	}

	function init_Tokens()
	{
		$this->RsvrWords = new \ENetArch\Ladder\OSQL\Scanner\ReservedWords();

		$this->addToken ("[eof]", TokenCodes::tcEOF);
		$this->addToken ("[error]", TokenCodes::tcERROR);
		$this->addToken ("[no token]", TokenCodes::tcNOTOKEN);
		
		$this->addToken ("^", TokenCodes::tcUPARROW);
		$this->addToken ("*", TokenCodes::tcASTERICK);
		$this->addToken ("(", TokenCodes::tcLPAREN);
		$this->addToken (")", TokenCodes::tcRPAREN);
		$this->addToken ("-", TokenCodes::tcMINUS);
		$this->addToken ("+", TokenCodes::tcPLUS);
		$this->addToken ("=", TokenCodes::tcEQUAL);
		$this->addToken ("[", TokenCodes::tcLBRACKET);
		$this->addToken ("]", TokenCodes::tcRBRACKET);
		$this->addToken ("{", TokenCodes::tcLBRACE);
		$this->addToken ("}", TokenCodes::tcRBRACE);
		$this->addToken (":", TokenCodes::tcCOLON);
		$this->addToken (",", TokenCodes::tcCOMMA);
		$this->addToken ("<", TokenCodes::tcLT);
		$this->addToken (">", TokenCodes::tcGT);
		$this->addToken (".", TokenCodes::tcPERIOD);
		$this->addToken ("/", TokenCodes::tcSLASH);
		$this->addToken ("<=", TokenCodes::tcLE);
		$this->addToken (">=", TokenCodes::tcGE);
		$this->addToken ("!=", TokenCodes::tcNE);
		$this->addToken (";", TokenCodes::tcSEMICOLON);
		
		$this->addToken ("!", TokenCodes::tcEXCLAMATION);
		$this->addToken ("@", TokenCodes::tcATSIGN);
		$this->addToken ("$", TokenCodes::tcDOLLARSIGN);
		$this->addToken ("%", TokenCodes::tcPERCENTSIGN);
		// $this->addToken ("^", TokenCodes::tcCARROT);
		$this->addToken ("&", TokenCodes::tcAMPERSAND);
		
		$this->addToken ("`", TokenCodes::tcQUOTE2);
		$this->addToken ("~", TokenCodes::tcTILDEE);
		$this->addToken ("\\", TokenCodes::tcBACKSLASH);
		$this->addToken ("?", TokenCodes::tcQUESTIONMARK);


		$this->addToken ("in", TokenCodes::tcIN);
		$this->addToken ("of", TokenCodes::tcOF);
		$this->addToken ("or", TokenCodes::tcOR);
		$this->addToken ("by", TokenCodes::tcBY);
		$this->addToken ("id", TokenCodes::tcID);
		$this->addToken ("is", TokenCodes::tcIS);
		$this->addToken ("to", TokenCodes::tcTO);

		$this->addToken ("and", TokenCodes::tcAND);
		$this->addToken ("all", TokenCodes::tcALL);
		$this->addToken ("not", TokenCodes::tcNOT);
		$this->addToken ("set", TokenCodes::tcSET);
		$this->addToken ("asc", TokenCodes::tcASC);
		$this->addToken ("avg", TokenCodes::tcAVG);
		$this->addToken ("max", TokenCodes::tcMAX);
		$this->addToken ("min", TokenCodes::tcMIN);
		$this->addToken ("sum", TokenCodes::tcSUM);
		$this->addToken ("add", TokenCodes::tcADD);
		$this->addToken ("any", TokenCodes::tcANY);

		$this->addToken ("like", TokenCodes::tcLIKE);
		$this->addToken ("null", TokenCodes::tcNULL);
		$this->addToken ("with", TokenCodes::tcWITH);
		$this->addToken ("drop", TokenCodes::tcDROP);
		$this->addToken ("from", TokenCodes::tcFROM);
		$this->addToken ("desc", TokenCodes::tcDESC);
		$this->addToken ("into", TokenCodes::tcINTO);
		$this->addToken ("does", TokenCodes::tcDOES);
		$this->addToken ("name", TokenCodes::tcNAME);
		$this->addToken ("type", TokenCodes::tcTYPE);
		$this->addToken ("item", TokenCodes::tcITEM);
		$this->addToken ("root", TokenCodes::tcROOT);
		$this->addToken ("some", TokenCodes::tcSOME);
		$this->addToken ("true", TokenCodes::tcTRUE);
		$this->addToken ("move", TokenCodes::tcMOVE);
		$this->addToken ("copy", TokenCodes::tcCOPY);
		$this->addToken ("link", TokenCodes::tcLINK);

		$this->addToken ("class", TokenCodes::tcCLASS);
		$this->addToken ("allow", TokenCodes::tcALLOW);
		$this->addToken ("using", TokenCodes::tcUSING);
		$this->addToken ("where", TokenCodes::tcWHERE);
		$this->addToken ("order", TokenCodes::tcORDER);
		$this->addToken ("depth", TokenCodes::tcDEPTH);
		$this->addToken ("alter", TokenCodes::tcALTER);
		$this->addToken ("count", TokenCodes::tcCOUNT);
		$this->addToken ("false", TokenCodes::tcFALSE);

		$this->addToken ("select", TokenCodes::tcSELECT);
		$this->addToken ("insert", TokenCodes::tcINSERT);
		$this->addToken ("values", TokenCodes::tcVALUES);
		$this->addToken ("delete", TokenCodes::tcDELETE);
		$this->addToken ("update", TokenCodes::tcUPDATE);
		$this->addToken ("parent", TokenCodes::tcPARENT);
		$this->addToken ("folder", TokenCodes::tcFOLDER);
		$this->addToken ("escape", TokenCodes::tcESCAPE);
		$this->addToken ("create", TokenCodes::tcCREATE);
		$this->addToken ("exists", TokenCodes::tcEXISTS);
		$this->addToken ("isroot", TokenCodes::tcISROOT);
		$this->addToken ("ladder", TokenCodes::tcLADDER);
		$this->addToken ("fields", TokenCodes::tcFIELDS);

		$this->addToken ("classes", TokenCodes::tcCLASSES);
		$this->addToken ("contain", TokenCodes::tcCONTAIN);
		$this->addToken ("between", TokenCodes::tcBETWEEN);
		$this->addToken ("install", TokenCodes::tcINSTALL);
		$this->addToken ("results", TokenCodes::tcRESULTS);

		$this->addToken ("subclass", TokenCodes::tcSUBCLASS);
		$this->addToken ("contains", TokenCodes::tcCONTAINS);
		$this->addToken ("template", TokenCodes::tcTEMPLATE);
		$this->addToken ("distinct", TokenCodes::tcDISTINCT);
		$this->addToken ("duplicate", TokenCodes::tcDUPLICATE);

		$this->addToken ("templates", TokenCodes::tcTEMPLATES);
		$this->addToken ("structure", TokenCodes::tcSTRUCTURE);
		$this->addToken ("instances", TokenCodes::tcINSTANCES);
		$this->addToken ("uninstall", TokenCodes::tcUNINSTALL);

		$this->addToken ("isinstalled", TokenCodes::tcISINSTALLED);

		$this->addToken ("identifier", TokenCodes::tcIDENTIFIER);
		$this->addToken ("attributes", TokenCodes::tcATTRIBUTES);

		$this->addToken ("datecreated", TokenCodes::tcDateCreated);
		$this->addToken ("dateaccessed", TokenCodes::tcDateAccessed);
		$this->addToken ("datemodified", TokenCodes::tcDateModified);

		$this->addToken ("uidcreated", TokenCodes::tcUIDCreated);
		$this->addToken ("uidaccessed", TokenCodes::tcUIDAccessed);
		$this->addToken ("uidmodified", TokenCodes::tcUIDModified);

		$this->addToken ("ipcreated", TokenCodes::tcIPCreated);
		$this->addToken ("ipaccessed", TokenCodes::tcIPAccessed);
		$this->addToken ("ipmodified", TokenCodes::tcIPModified);		
		
		If (gblError()->No() != 0) 
			gblError()->Addpath (__METHOD__);

	}

	// ============================================

	public static function docFoo_addToken ()
	{ docFoo ( Array 
		(
		"Method" => "addToken",
		"Syntax" => "addToken (szWord, nToken)",
		"Parameters" => Array
		(
			"szWord" => "The word associated with the token",
			"nToken" => "The value ",
		),
		"Description" => "Adds a word to the reserved list",
		"Core Code" => 
			"
				Add a token word pair to the Reserved List
				Confirm no errors occured
				If an error occured, update global error
			",
		"Returns" => "nothing",
		"Tests" => Array
			(
				"Adds a token to the Reserved Word List",
			),
		) );
	}

	function addToken ($szWord, $nToken)
	{
		$objWord = $this->RsvrWords->AddItem ($szWord, $nToken);
		If (gblError()->No() != 0) 
			gblError()->Addpath (__METHOD__ . " ( [$szWord], [$nToken] ) " );

	}
	
	// =================================================================
	
	function print_vmVar_Array ($name, $var)
	{
		print ("<P style='color:green;'>" . $name . " = <pre style='color:green;'>"); 
		print_r($var); 
		print ("</pre></P>");
	}

	function print_vmVar_Var ($name, $var)
	{
		print ("<P style='color:green;'>" . $name . " = ". $var . "</P>");
	}
	
	
}

miniParser::docFoo();

docFoo 
( Array
( 
	"Function" => "json_last_error_msg",
	"Description" => "returns a string message concerning the json error found",
	"Core Code" => 
		"
			determine if the function exists
			initialize the function
			create a name value pair of error messages
			retrieve the error message from the json interpretor
			return a formatted error message
		",
	"Returns" => "a formatted string error message",
	"Comments" => "This function should be moved into initialization code
		so that it can be used globally",
	"Tests" => Array
	(
		"Confirm no error is return from JSON on correct JSON string",
		"Confirm an error is return from JSON when an error is present",
	),
));

if (!function_exists('json_last_error_msg')) 
{
    function json_last_error_msg() 
    {
        static $errors = array
        (
            JSON_ERROR_NONE             => null,
            JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH   => 'Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR        => 'Unexpected control character found',
            JSON_ERROR_SYNTAX           => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded'
        );
        
        $error = json_last_error();
        return array_key_exists($error, $errors) ? $errors[$error] : "Unknown error ({$error})";
    }
}

?>
