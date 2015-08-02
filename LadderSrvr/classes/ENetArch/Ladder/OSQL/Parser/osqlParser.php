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

use \ENetArch\Ladder\OSQL\Scanner\Token;
use \ENetArch\Ladder\OSQL\Parser\miniParser;
use \ENetArch\Ladder\OSQL\Parser\TokenCodes;
use \ENetArch\Ladder\OSQL\Parser\Params;
use \ENetArch\Ladder\OSQL\Parser\Expression;

docFoo 
( Array
(
	"Class" => "osqlParser",
	
	"Description" => 
		"Transforms and Executes the ByteCode into PHP code",


	"Requirements" => Array (),

	"Includes" => Array
	(
		"ErrorMsgs - List of Error Messages used by the ByteCode class",
		"miniParser - Checks the syntax of the OSQL command string",
		"TokenCodes - A list of Tokens found in the OSQL command string",
		"Params - A list of Parameters found in the OSQL command string",
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
		"parser" => "",
		"stateData" => "",
		"statePath" => "",
		"currentState" => "",
		"newField" => "",
		"newField" => "",
		"fOPs" => "",
		"nOPs" => "",
		"expr_stack" => "",
		"expr_list" => "",
		"val_list_stack" => "",
		"val_list" => "",
		"classfields" => "",
		"params" => "",
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
	{ "either" : [ "function(s)", ] }, 
	{ "until" : [ "function", [ "token(s)" ] ] } , 
	{ "while" : [ [ "token(s)" ], "function", "exit-token" ] } , 
	{ "param" : [ ] } , 
	{ "error" : [ "message" ] } , 
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
		"until - only consumes tokens that match the conditional clauses",
		"while - only consumes tokens that match the conditional clauses",
		
		"There are two things to account for.  1- how information bubbles
		up the syntax tree. 2- the removal of information from the token
		list.  Item #2 is not as important as item #1."
	),
	
	"Tests" => Array
	(
		"Confirm that a WHERE CLAUSE parses",
		"Confirm that a Equation parse",

		"Confirm that SELECT FIELDS statements parse",
		"Confirm that SELECT INSTANCE statements parse",
		"Confirm that INSERT INSTANCE statements parse",
		"Confirm that UPDATE INSTANCE statements parse",
		"Confirm that DELETE INSTANCE statements parse",

		"Confirm that CREATE CLASS statements parse",
		"Confirm that SELECT CLASS statements parse",
		"Confirm that UPDATE CLASS statements parse",
		"Confirm that DROP CLASS statements parse",
	),
));

class osqlParser
{
	public $parser = null;
	
	public $stateData = [];
	private $statePath = [];
	private $currentState = [];
	
	// =================================================================
	
	function __construct ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->parser = new miniParser ($this);
	}
	
	function process ($szOSQL)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		print ("Entering - " . __METHOD__ . "<BR>");
		
		$this->stateData ["command"] = "";
		$this->stateData ["aliases"] = [];
		$this->stateData ["classes"] = [];
		$this->stateData ["using"] = [];
		$this->stateData ["fields"] = [];
		$this->stateData ["from"] = [];
		$this->stateData ["where"] = [];
		$this->stateData ["orderby"] = [];
		
		$this->parser->process ($szOSQL);
		if ($this->parser->err != 0)
		{
			$parser = $this->parser;
			gblError()->init ($parser->errMsg, $parser->err, "", "", $szOSQL);
		}
		
		return (true);
	}

	// =================================================================

	private $in_Using = false;
	private $in_Path  = false;
	private $in_Contains = false;


	// =================================================================
	/*
		"root" : 
		{
			"switch" : 
			[
				[ "SELECT", "select_clause" ],
				[ "UPDATE", "update_clause" ],
				[ "DELETE", "delete_clause" ],
				[ "INSERT", "insert_clause" ],
				
				[ "CREATE", "create_clause" ],
				[ "ALTER", "alter_clause" ],
				[ "DROP", "drop_clause" ],

				[ [ "MOVE", "COPY", "DUPE" ], "move_clause" ],
				
				[ "error_root_switch" ]
			]
		}
	*/

	private $var_root = [];
	
	function root_in ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->stateData ["command"][] = $token;
		$this->var_root ["command"][] = $token;
	}
	
	function root_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->print_vmVar_Array ("var_root", $this->var_root);
	}


	function root_matched_select_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->print_vmVar_Array ("var_root", $this->var_root);
		$this->print_vmVar_Array ("var_select_clause", $this->var_select_clause);
		
		$this->var_root = array_merge_recursive ($this->var_root, $this->var_select_clause);
	}

	function root_matched_update_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_root = array_merge_recursive ($this->var_root, $this->var_update_clause);
	}

	function root_matched_delete_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_root = array_merge_recursive ($this->var_root, $this->var_delete_clause);
	}

	function root_matched_insert_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_root = array_merge_recursive ($this->var_root, $this->var_insert_clause);
	}


	function root_matched_create_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_root = array_merge_recursive ($this->var_root, $this->var_create_clause);
	}

	function root_matched_alter_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_root = array_merge_recursive ($this->var_root, $this->var_alter_clause);
	}

	function root_matched_drop_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_root = array_merge_recursive ($this->var_root, $this->var_drop_clause);
	}

	
	function root_matched_move_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_root = array_merge_recursive ($this->var_root, $this->var_move_clause);
	}

	// =================================================================
	// "got_it" : { "do" : [] },

	function got_it_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
	}
	

	// "length" : { "if" : [ [ "(", "getNumber", ")" ], "error_length" ] },
	
	private $var_length = [];
	
	function length_in ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_length = [];
	}

	function length_matched_getNumber_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_length[] = array_pop ($this->var_params);
	}
	
	/*
		"fieldtype" : 
		{ 
			"switch" : 
			[ 
				[ "NUMBER", "got_it" ], 
				[ "STRING", "length" ], 
				[ "MEMO", "got_it" ], 
				[ "DATETIME", "got_it" ], 
				[ "error_fieldtypes" ] 
			] 
		}, 
	*/
	
	private $var_fieldtype = [];
	
	function fieldtype_matched_length_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_fieldtype[] = 
		[
			"type" => $token,
			"length" => array_pop ($this->var_length)
		];
	}

	function fieldtype_matched_got_it_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_fieldtype[] = 
		[
			"type" => $token,
			"length" => 0
		];
	}

	
	// "fieldname" : { "do" : [ "getIdentifier" ] }, 

	private $var_fieldname = [];
	
	function fieldname_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_fieldname[] = array_pop ($this->var_params);
	}
	
	// "newField" : { "do" : [ "fieldname", "fieldtype" ] }, 
	
	private $var_newField = [];
	
	function newField_in ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->newField = [];
	}

	function newField_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$type = array_pop ($this->var_fieldtype);
		
		$this->var_newField ["length"] = $type ["length"];
		$this->var_newField ["type"] = $type ["type"];
		$this->var_newField ["name"] = array_pop ($this->var_fieldname);

		$this->stateData ["fields"][] = $this->var_newField;
		
		$this->print_vmVar_Array ("var_newField", $this->var_newField);
	}
	
	// "newFields" : { "until" : [ "newField", "," ] },

	private $var_newFields = [];
	
	function newFields_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_newFields = [];
	}

	function newFields_loop ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_newFields = $this->newField;
		
		$this->print_vmVar_Array ("var_newFields", $this->var_newFields);
	}
	

	// "alter_add_str" : { "if" : [ [ "(", "newField", ")" ], "" ] },

	private $var_alter_add_str = [];
	
	function alter_add_str_matched_newField_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_alter_add_str[] = array_pop ($this->var_newField);
		
		$this->print_vmVar_Array ("var_alter_add_str", $this-> var_alter_add_str);
	}
	
	// "alter_delete_str" : { "do" : [ "getIdentifier" ] },

	private $var_alter_delete_str = "";
	
	function alter_delete_str_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->stateData ["fields"]["name"] = array_pop ($this->var_params);
		
		$this->var_alter_delete_str = $this->var_value;
		
		$this->print_vmVar_Array ("var_alter_delete_str", $this-> var_alter_delete_str);
	}
	
	// "alter_update_str" : { "do" : [] },
	
	private $var_alter_update_str = "";
	
	function alter_update_str_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_alter_update_str = "";
	}
	

	/*
		"alter_add" : 
		{ 
			"switch" : 
			[ 
				["STRUCTURE", "alter_add_str"], 
				[ "error_structure" ] 
			]
		},
	*/
	
	private $var_alter_add = [];
	
	function alter_add_in ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->stateData ["command"][] = $token;
	}
	
	function alter_add_matched_alter_add_str_in ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_alter_add = $this->var_alter_add_str;
		
		$this->print_vmVar_Array ("var_alter_add", $this-> var_alter_add);
	}

	/*
		"alter_delete" : 
		{ 
			"switch" : 
			[ 
				["STRUCTURE", "alter_delete_str"], 
				[ "error_structure" ] 
			] 
		},
	*/
	
	private $var_alter_delete = [];
	
	function alter_delete_in ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->stateData ["command"][] = $token;
	}
	
	function alter_delete_match_alter_delete_str_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_alter_delete = $this->var_alter_delete_str;
		
		$this->print_vmVar_Array ("var_alter_delete", $this->var_alter_delete);
	}

	/*
		"alter_update" : 
		{ 
			"switch" : 
			[ 
				["STRUCTURE", "alter_update_str"], 
				[ "error_structure" ] 
			] 
		},
	*/
	
	private $var_alter_update = [];
	
	function alter_update_in ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->stateData ["command"][] = $token;
	}
	
	function alter_update_matched_alter_update_str_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_alter_update = $this->var_alter_update_str;
		
		$this->print_vmVar_Array ("var_alter_update", $this-> var_alter_update);
	}
	
	/*
		"alter_clause_switch" : 
		{ 
			"switch" : 
			[ 
				[ "ADD", "alter_add" ], 
				[ "DELETE", "alter_delete" ], 
				[ "UPDATE", "alter_update" ], 
				[ "error_alter_swtich" ] 
			] 
		},
	*/
	
	private $var_alter_clause_switch = [];
	
	function alter_clause_switch_in ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->stateData ["command"][] = $token;
	}

	function alter_clause_switch_matched_alter_add_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_alter_clause_switch = $this->var_alter_add;
		
		$this->print_vmVar_Array ("var_alter_clause_switch", $this-> var_alter_clause_switch);
	}

	function alter_clause_switch_matched_alter_delete_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_alter_clause_switch = $this->var_alter_delete;
		
		$this->print_vmVar_Array ("var_alter_clause_switch", $this-> var_alter_clause_switch);
	}

	function alter_clause_switch_matched_alter_update_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_alter_clause_switch = $this->var_alter_update;
		
		$this->print_vmVar_Array ("var_alter_clause_switch", $this-> var_alter_clause_switch);
	}

	// "alter_get_classname" : { "do" : [ "class_name" ] },

	private $var_alter_get_classname = [];
	
	function alter_get_classname_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_alter_get_classname[] = array_pop ($this->var_class_name);
		
		$this->print_vmVar_Array ("var_alter_get_classname", $this-> var_alter_get_classname);
	}
	
	// "alter_clause_class" : { "if" : [ [ "CLASS", "alter_get_classname" ], ""] },

	private $var_alter_clause_class = [];
	
	function alter_clause_class_matched_alter_get_classname_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$szClass = array_pop ($this->var_alter_get_classname);
		$this->stateData ["using"][] = $szClass;

		$this->print_vmVar_Array ("var_alter_clause_class", $this->var_alter_clause_class);
	}
		
	
	// "alter_clause" : { "do" : [ "alter_clause_class", "alter_clause_switch" ] },

	private $var_alter_clause = [];
	
	function alter_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_alter_clause = 
		[
			array_pop ($this->var_alter_clause_class),
			array_pop ($this->var_alter_clause_switch)
		];
		
		$this->print_vmVar_Array ("var_alter_clause", $this->var_alter_clause);
	}
	
	
	// =================================================================
	// =================================================================

	// "create_class_fields" :  { "if" : [ [ "(", "newFields", ")" ], "error_newfields" ] },

	private $var_create_class_fields = [];
	
	function create_class_fields_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->print_vmVar_Array ("var_create_class_fields", $this->var_create_class_fields);
	}
	
	// "create_class" :  { "do" : [ "class_name", "create_class_fields" ] },

	private $var_create_class = [];
	
	function create_class_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$szClass = array_pop ($this->var_class_names);
		$fields = $this->var_create_class_fields;
		
		$this->stateData ["using"][] = $szClass;

		$this->var_create_class[] = 
		[
			"class" => $szClass,
			"fields" => $fields
		];
		
		$this->print_vmVar_Array ("var_create_class", $this->var_create_class);
	}
	
	
	// "create_root" : { "optional" : [ [ "ISROOT", "comparison_test" ] ] },

	private $var_create_root = [];
	
	function create_root_matched_got_it_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_create_root = "TRUE";
		
		$this->print_vmVar_Array ("var_create_root", $this->var_create_root);
	}
	
	// "create_folder" : { "do" : [ "class_name", "create_root" ] },

	private $var_create_folder = [];
	
	function create_folder_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_create_folder[] = 
		[
			"class" => array_pop ($this->var_class_name),
			"isRoot" => $this->var_create_root
		];
		
		$this->print_vmVar_Array ("var_create_folder", $this->var_create_folder);
	}
	
	
	// "class_of_class" : { "if" : [ [ "CLASS", "class_name"], "error_class_of" ] }, 

	private $var_class_of_class = [];
	
	function class_of_class_matched_class_name_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_class_of_class[] = array_pop ($this->var_class_name);
		
		$this->print_vmVar_Array ("var_class_of_class", $this->var_class_of_class);
	}
	
	// "class_of" : { "if" : [ [ "OF", "class_of_class"] ] }, 

	private $var_class_of = [];
	
	function class_of_matched_class_of_class_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_class_of[] = array_pop ($this->var_class_of_class);
		
		$this->print_vmVar_Array ("var_class_of", $this->var_class_of);
	}
	
	// "create_subclass" : { "do" : [ "class_name", "class_of" ] },

	private $var_create_subclass = [];
	
	function create_subclass_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_create_subclass[] = 
		[
			"class" => array_pop ($this->var_class_name),
			"ofClass" => array_pop ($this->var_class_of)
		];
		
		$this->print_vmVar_Array ("var_create_subclass", $this->var_create_subclass);
	}
	
	
	/*
		"create_clause" : 
		{ 
			"switch" : 
			[ 
				[ "CLASS", "create_class" ], 
				[ "FOLDER", "create_folder" ], 
				[ "SUBCLASS", "create_subclass" ], 
				[ "error_create_switch" ] 
			] 
		},
	*/
	
	private $var_create_clause = [];
	
	function create_clause_matched_create_class_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_create_clause[] = array_pop ($this->var_create_class);
		
		$this->print_vmVar_Array ("var_create_clause", $this->var_create_clause);
	}

	function create_clause_matched_create_folder_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_create_clause[] = array_pop ($this->var_create_folder);
		
		$this->print_vmVar_Array ("var_create_clause", $this->var_create_clause);
	}

	function create_clause_matched_create_subclass_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_create_clause[] = array_pop ($this->var_create_subclass);
		
		$this->print_vmVar_Array ("var_create_clause", $this->var_create_clause);
	}
	
	
	// =================================================================
	// =================================================================

	// "drop_clause" : { "if" : [ ["CLASS", "class_name"], "" ] },

	private $var_drop_clause = [];
	
	function drop_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szClass = array_pop ($this->var_class_name);
		
		$this->stateData ["drop"] = $szClass;
		$this->var_drop_clause[] = $szClass;
		
		$this->print_vmVar_Array ("var_drop_clause", $this->var_drop_clause);
	}
	

	// =================================================================
	// =================================================================

	/*
		"delete_elements" : 
		{ 
			"do" : 
			[ 
				"get_path", 
				"get_using", 
				"where_clause_if"
			] 
		},
	*/
	
	private $var_delete_elements = [];

	function delete_elements_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szPath = array_pop ($this->var_get_path);
		$using = array_pop ($this->var_get_using);
		$where = array_pop ($this->var_where_clause_if);
		
		$this->stateData ["from"] = $szPath;
		$this->stateData ["using"] = $using;
		$this->stateData ["where"] = $where;
		
		$this->var_delete_elements[] = 
		[
			"Path" => $szPath,
			"Using" => $using,
			"Where" => $where
		];
			
		$this->print_vmVar_Array ("var_delete_elements", $this->var_delete_elements);
	}
	
	// "delete_element" : { "do" : [ "get_path" ] },

	private $var_delete_element = [];

	function delete_element_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szPath = array_pop ($this->var_get_path);
		
		$this->stateData ["from"] = $szPath;
		
		$this->var_delete_element[] = $szPath;
		
		$this->print_vmVar_Array ("var_delete_element", $this->var_delete_element);
	}

	// "delete_from" : { "if" : [ [ "FROM", "delete_elements"] , "delete_element"] },

	private $var_delete_from = [];

	function delete_from_matched_delete_elements_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);	
		
		$this->var_delete_from[] = array_pop ($this->var_delete_elements);
		
		$this->print_vmVar_Array ("var_delete_from", $this->var_delete_from);
	}
	
	function delete_from_matched_delete_element_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);	
		
		$this->var_delete_from[] = array_pop ($this->var_delete_element);	
		
		$this->print_vmVar_Array ("var_delete_from", $this->var_delete_from);
	}

	// "delete_clause" : { "do" : [ "delete_from" ] }, 

	private $var_delete_clause = [];

	function delete_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_delete_clause[] = array_pop ($this->var_delete_from);
		
		$this->print_vmVar_Array ("var_delete_clause", $this->var_delete_clause);
	}
	
	
	// =================================================================
	// =================================================================
	// "insert_fields" : { "do" : [ "value_list" ] }

	private $var_insert_fields = [];
	
	function insert_fields_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$fields = $this->var_value_list;
		
		$this->stateData ["fields"] = $fields;
		$this->var_insert_fields = $fields;
		
		$this->print_vmVar_Array ("var_insert_fields", $this->var_insert_fields);		
	}

	// "insert_values" : { "if" : [ [ "VALUES", "values_list"] , "error_insert_values"] },
	
	private $var_insert_values = [];

	function insert_values_matched_values_list_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$data = $this->var_values_list;
		 
		$this->stateData ["data"] = $data;
		
		$this->var_insert_values = $data;
		
		$this->print_vmVar_Array ("var_insert_values", $this->var_insert_values);		
	}

	// "insert_into" : { "if" : [ [ "INTO", "get_path"] , "error_insert_into"] },

	private $var_insert_into = [];

	function insert_into_matched_get_path_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szPath = array_pop($this->var_get_path);
		
		$this->stateData ["from"] = $szPath;
		
		$this->var_insert_into = $szPath;
		
		$this->print_vmVar_Array ("var_insert_into", $this->var_insert_into);
	}
	
	// "insert_clause" : { "do" : [ "insert_into", "get_using", "insert_values" ]  }, 

	private $var_insert_clause = [];

	function insert_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_insert_clause = 
		[
			$this->var_insert_into,
			$this->var_get_using,
			$this->var_insert_values
		];
		
		$this->print_vmVar_Array ("var_insert_clause", $this->var_insert_clause);
	}
	

	// =================================================================
	// =================================================================

	// "move_to" : { "if" : [ [ "TO", "get_path"] , "error_move_to"] }, 

	private $var_move_to = [];

	function move_to_matched_get_path_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_move_to[] = array_pop ($this->var_get_path);
		
		$this->print_vmVar_Array ("var_move_to", $this->var_move_to);
	}
	
	// "move_from" : { "if" : [ [ "FROM", "get_path"] , "error_move_from"] },

	private $var_move_from = [];

	function move_from_matched_get_path_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_move_from[] = array_pop ($this->var_get_path);
		
		$this->print_vmVar_Array ("var_move_from", $this->var_move_from);
	}
	
	// "move_clause" : { "do" : [ "move_from", "move_to" ] }, 

	private $var_move_clause = [];

	function move_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$from = array_pop ($this->var_move_from);
		$to = array_pop ($this->var_move_to);

		$this->stateData ["from"] = $from;
		$this->stateData ["to"] = $to;
		
		$this->var_move_clause[] = 
		[
			"from" => $from,
			"to" => $to
		];
		
		$this->print_vmVar_Array ("var_move_clause", $this->var_move_clause);
	}
	
	// =================================================================
	// =================================================================
	// "if_equals" : { "if" : [ ["=", "comparison_test" ], "error_expected_equals" ] },

	private $var_if_equals = [];

	function if_equals_matched_comparison_test_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_if_equals[] = array_pop ($this->var_comparision_test);
		
		$this->print_vmVar_Array ("var_if_equals", $this->var_if_equals);
	}
	

	// =================================================================
	// "set_field" : { "do" : [ "value_or_field", "if_equals" ] },

	private $var_set_field = [];

	function set_field_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_set_field[] = 
		[
			"field" => array_pop ($this->var_value_or_field),
			"value" => array_pop ($this->var_if_equals)
		];
		
		$this->print_vmVar_Array ("var_set_field", $this->var_set_field);
	}

	// =================================================================
	// "set_properties" : { "until" : [ "set_field", "," ] },
	
	private $var_set_properties = [];

	function set_properties_loop ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_set_properties[] = array_pop ($this->var_set_field);
		
		$this->print_vmVar_Array ("var_set_properties", $this->var_set_properties);
	}


	// =================================================================
	// "update_set" : { "if" : [ [ "SET", "set_properties"] , "error_update_set"] }, 
	
	private $var_update_set = [];

	function update_set_matched_set_properties_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$set = $this->var_set_properties;
		
		$this->stateData ["set"] = $set;
		
		$this->var_update_set = $set;
		
		$this->print_vmVar_Array ("var_update_set", $this->var_update_set);
	}
	
	
	// =================================================================
	// "update_element" : { "do" : [ "get_path", "update_set" ] },

	private $var_update_element = [];

	function update_element_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szPath = array_pop ($this->var_get_path);
		$set = $this->var_update_set;
		
		$this->stateData ["from"] = $szPath;
		$this->stateData ["set"] = $set;
		
		
		$this->var_update_element = 
		[
			"Path" => $szPath,
			"Set" => $set
		];
		
		$this->print_vmVar_Array ("var_update_element", $this->var_update_element);
	}
	
	// "update_elements" : { "do" : [ "get_path", "get_using", "update_set", "where_clause_if" ] },	 

	private $var_update_elements = [];

	function update_elements_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szPath = array_pop ($this->var_get_path);
		$using = array_pop ($this->var_get_using);
		$where = array_pop ($this->var_where_clause_if);
		$set = $this->var_update_set;
		
		$this->stateData ["from"] = $szPath;
		$this->stateData ["using"] = $using;
		$this->stateData ["where"] = $where;
		$this->stateData ["set"] = $set;
		
		$this->var_update_elements = 
		[
			"Path" => $szPath,
			"Using" => $using,
			"Set" => $set,
			"Where" => $where
		];
		
		$this->print_vmVar_Array ("var_update_elements", $this->var_update_elements);
	}

	// "update_in" : { "if" : [ [ "IN", "update_elements"] , "update_element"] }, 

	private $var_update_in = [];

	function update_in_matched_update_elements_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->stateData ["command"][] = $token;
		$this->var_update_in = $this->var_update_elements;
		
		$this->print_vmVar_Array ("var_update_in", $this->var_update_in);
	}
	
	function update_in_matched_update_element_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_update_in = $this->var_update_element;
		
		$this->print_vmVar_Array ("var_update_in", $this->var_update_in);
	}

	// "update_clause" : { "do" : [ "update_in"] }, 
	
	private $var_update_clause = [];

	function update_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_update_clause = $this->var_update_in;
		
		$this->print_vmVar_Array ("var_update_clause", $this->var_update_clause);
	}


	// =================================================================
	// =================================================================
	/*
		"select_clause" :
		{
			"switch" : 
			[	
				[ "INSTANCES", "select_instances" ],
				[ "FIELDS", "select_fields" ],
				[ "STRUCTURE", "structure" ],
				
				[ "error_select_required" ]
			]
		},
	*/
	
	private $var_select_clause = [];
	
	function select_clause_matched_select_instances_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->stateData ["command"][] = $token;
		
		$this->var_select_clause = $this->var_select_instances;
		
		$this->print_vmVar_Array ("var_select_clause", $this->var_select_clause);
	}
	 
	function select_clause_matched_select_fields_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->stateData ["command"][] = $token;
		
		$this->var_select_clause = $this->var_select_fields;
		
		$this->print_vmVar_Array ("stateData", $this->stateData);
		$this->print_vmVar_Array ("var_select_clause", $this->var_select_clause);
	}
	 
	function select_clause_matched_structure_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->stateData ["command"][] = $token;
		
		$class = $this->var_structure;
		$this->stateData ["structure"] = $class;
		$this->var_select_clause["structure"] = $class;
		
		$this->print_vmVar_Array ("var_select_clause", $this->var_select_clause);
	}
	
	
	// =================================================================
	// "select_instances" : { "do" : [ "select_required" ] },
	
	private $var_select_instances = [];
	
	function select_instances_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_select_instances = $this->var_select_required;
		$this->print_vmVar_Array ("var_select_instances", $this->var_select_instances);
	}


	// =================================================================
	// "select_fields" : { "do" : [ "class_field_names", "select_required" ] },
	
	private $var_select_fields = [];
	function select_fields_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$fields = $this->var_class_field_names;
		$this->var_class_field_names = [];
		
		$this->classfields = [];
		$this->stateData ["fields"] = $fields;
		
		$this->var_select_fields = $this->var_select_required;
		$this->var_select_fields["fields"] = $fields;
		
		$this->print_vmVar_Array ("var_select_fields", $this->var_select_fields);
	}
	

	// =================================================================

	// "structure" : { "if" : [ ["FOR", "structure_for"], "error_structure_expected_for" ] },
	
	private $var_structure = [];
	function structure_matched_structure_for_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$class = $this->var_structure_for;
		$this->stateData["class"] = $class;
		$this->var_structure = $class;
		$this->print_vmVar_Array ("var_structure", $this->var_structure);
	}

	// "structure_for" : { "if" : [ ["class", "structure_class"], "error_structure_expected_class" ] },
	private $var_structure_for = [];
	function structure_for_matched_structure_class_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_structure_for = $this->var_structure_class;
		$this->print_vmVar_Array ("var_structure_for", $this->var_structure_for);
	}
	
	// "structure_class" : { "do" : [ "getIdentifier" ] },
	private $var_structure_class = [];
	function structure_class_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->var_structure_class = array_pop($this->var_params);
		$this->print_vmVar_Array ("var_structure_class", $this->var_structure_class);
	}
	
	
	// =================================================================
	/*
		"select_required" :
		{ 
			"do" : 
			[ 
				"get_using", 
				"select_from", 
				"select_optionals" 
			] 
		},
	 */

	private $var_select_required = [];
	
	function select_required_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_select_required ["required"] = array_pop ($this->var_select_required);
		$this->var_select_required ["using"] = array_pop ($this->var_get_using);
		$this->var_select_required ["from"] = array_pop ($this->var_select_from);
		
		$optionals = $this->var_select_optionals;
		
		$this->var_select_required ["where"] = ((isset ($optionals ["where"])) ? $optionals ["where"] : "");
		$this->var_select_required ["order"] = ((isset ($optionals ["order"])) ? $optionals ["order"] : "");
		$this->var_select_required ["depth"] = ((isset ($optionals ["depth"])) ? $optionals ["depth"] : "");
		
		$this->var_select_optionals = [];
		
		$this->print_vmVar_Array ("var_select_required", $this->var_select_required);
	}

	 
	// =================================================================
	/*
		"select_optionals" :
		{
			"optional" : 
			[
				[ "WHERE", "where_clause" ],
				[ "ORDER", "order_clause" ],
				[ "DEPTH", "depth_clause" ]
			]
		},
	*/
	
	private $var_select_optionals = [];
	
	function select_optionals_matched_where_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_select_optionals ["where"] = $this->var_where_clause;
		$this->var_where_clause = [];
		
		$this->print_vmVar_Array ("var_select_optionals", $this->var_select_optionals);
	}
	
	function select_optionals_matched_order_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_select_optionals ["order"] = $this->var_order_clause;
		$this->var_order_clause = [];
		
		$this->print_vmVar_Array ("var_select_optionals", $this->var_select_optionals);
	}
	
	function select_optionals_matched_depth_clause_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_select_optionals ["depth"] = $this->var_depth_clause;
		$this->var_depth_clause = [];
		
		$this->print_vmVar_Array ("var_select_optionals", $this->var_select_optionals);
	}
	
	// =================================================================
	/*
		"field_optionals" :
		{
			"optional" : 
			[
				[ "GROUP", "groupby_clause" ],
				[ "HAVING", "having_clause" ]
				
			]
		},
	*/

	private $var_field_optionals = [];
	function field_optionals_matched_groupby_clause_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_field_optionals ["where"] = $this->var_groupby_clause;
		$this->var_groupby_clause = [];
		
		$this->print_vmVar_Array ("var_field_optionals", $this->var_field_optionals);
	}

	function field_optionals_matched_having_clause_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_field_optionals ["where"] = $this->var_having_clause;
		$this->var_having_clause = [];
		
		$this->print_vmVar_Array ("var_field_optionals", $this->var_field_optionals);
	}

	// =================================================================
	// "groupby_clause" : { "if" : [ ["BY", "class_field_names"], "error_group_by" ] },
	
	private $var_groupby_clause = [];
	function groupby_clause_matched_class_field_names_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_groupby_clause = $this->var_class_field_names;
		
		$this->print_vmVar_Array ("var_groupby_clause", $this->var_groupby_clause);
	}

	
	// "having_clause" : { "do" : [ "class_field_names"] },
	
	private $var_having_clause = [];
	function having_clause_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_having_clause = $this->class_field_names;
		
		$this->print_vmVar_Array ("var_having_clause", $this->var_having_clause);
	}



	// =================================================================
	// "select_from" : { "if" : [ ["FROM", "get_path"], "error_select_from" ] },
	
	private $var_select_from = "";
	
	function select_from_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szPath = array_pop ($this->var_get_path);

		$this->stateData ["from"] = $szPath;
		
		$this->var_select_from[] = $szPath;
		
		$this->print_vmVar_Array ("var_select_from", $this->var_select_from);
	}
	
	
	// =================================================================
	// =================================================================
	// "get_path" : { "do" : [ "getString", "get_path_alias" ] },
	
	private $var_get_path = [];
	
	function get_path_in ($token)
	{ $this->in_Path = true; }
	
	function get_path_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szPath = array_pop ($this->var_params);
		$szAlias = array_pop ($this->var_get_path_alias);
		$this->add_alias ($szPath, $szAlias);
		
		$this->var_get_path[] = 
		[
			"Path" => $szPath, 
			"Alias" => $szAlias
		];
		
		$this->in_Path = false; 
		
		$this->print_vmVar_Array ("var_get_path", $this->var_get_path);
	}

	// "get_path_alias" : { "if" : [ ["[", "alias_name", "]"] ] },
	
	private $var_get_path_alias = [];
	
	function get_path_alias_matched_alias_name_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_get_path_alias[] = array_pop ($this->var_alias_name);
		
		$this->print_vmVar_Array ("var_get_path_alias", $this->var_get_path_alias);
	}
	
	// "get_using" : { "if" : [ ["using", "get_using_switch"], "error_get_using"] },
	
	private $var_get_using = [];
	
	function get_using_in ($token)
	{ $this->in_Using = true; }
	
	function get_using_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szClass = array_pop ($this->var_get_using_switch);
		
		$this->stateData ["using"] = $szClass;

		$this->var_get_using[] = $szClass;
		
		$this->print_vmVar_Array ("var_get_using", $this->var_get_using);
		
		$this->in_Using = false;
	}
	
	/*
	   "get_using_switch" : 
		{ 
			"switch" : 
			[ 
				["CLASS", "get_using_class_name" ],
				["CLASSES", "get_using_classes" ],
				
				[ "error_get_using_switch" ]
			]
		},
	*/
	
	private $var_get_using_switch = [];
	
	function get_using_switch_matched_get_using_class_name_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_get_using_switch[] = array_pop ($this->var_get_using_class_name);
		
		$this->print_vmVar_Array ("var_get_using_switch", $this->var_get_using_switch);
	}
	
	function get_using_switch_matched_get_using_classes_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_get_using_switch[] = array_pop ($this->var_get_using_classes);
		
		$this->print_vmVar_Array ("var_get_using_switch", $this->var_get_using_switch);
	}

	// "get_using_classes" : { "if" : [ ["*", "" ], "get_using_class_names" ] },

	private $var_get_using_classes = [];
	
	function get_using_classes_matched_get_using_class_names_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_get_using_classes[] = array_pop ($this->var_get_using_class_names);
		
		$this->print_vmVar_Array ("var_get_using_classes", $this->var_get_using_classes);
	}
	
	// "get_using_class_names" : { "until" : [ "get_using_class_name", "," ] },
	
	private $var_get_using_class_names = [];
	
	function get_using_class_name_loop ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_get_using_class_names[] = array_pop ($this->var_get_using_class_name);
		
		$this->print_vmVar_Array ("var_get_using_class_names", $this->var_get_using_class_names);
	}

	// "get_using_class_name" :	{ "do" : [ "class_name" ] },
	
	private $var_get_using_class_name = "";
	
	function get_using_class_name_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_get_using_class_name[] = array_pop ($this->var_class_name);
		
		$this->print_vmVar_Array ("var_get_using_class_name", $this->var_get_using_class_name);
	}
	
	// =================================================================
	// =================================================================
	// "order_clause" : { "if" : [ [ "BY", "order_by" ], "error_order_by" ] },
	
	private $var_order_clause = [];

	function order_clause_matched_order_by_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$fields = $this->var_order_by;
			
		$this->stateData ["orderby"] = $fields; 

		$this->var_order_clause = $fields;
		
		$this->print_vmVar_Array ("var_order_clause", $this->var_order_clause);
	}
	
	// "order_by" : { "until" : [ "order_by_field", "," ] },

	private $var_order_by = [];

	function order_by_loop ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_order_by[] = $this->var_order_by_field;
		
		$this->print_vmVar_Array ("var_order_by", $this->var_order_by);
	}
	
	// "order_by_field" : { "do" : [ "class_field_name", "order_by_direction" ] },
	
	private $var_order_by_field = [];
	
	function order_by_field_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_order_by_field = 
		[
			"field" => array_pop ($this->var_class_field_name),
			"dir" => array_pop ($this->var_order_by_direction)
		];
		
		$this->print_vmVar_Array ("var_order_by_field", $this->var_order_by_field);
	}
	
	// "order_by_direction" : { "if" : [ ["ASC", "DESC"], "got_it" ] ] },
	
	private $var_order_by_direction = [];
	
	function order_by_direction_matched_got_it_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_order_by_direction[] = $token;
		
		$this->print_vmVar_Array ("var_order_by_direction", $this->var_order_by_direction);
	}
	
	
	
	
	// =================================================================
	// =================================================================
	// "where_clause_if" : { "if" : [ [ "WHERE", "where_clause" ] ] },
	
	private $var_where_clause_if = [];
	
	function where_clause_if_out ($token) 
	{	
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_where_clause_if = $this->var_where_clause;
		
		$this->print_vmVar_Array ("var_where_clause_if", $this->var_where_clause_if);
	}

	// "where_clause" : { "do" : [ "filters" ] },
	
	private $var_where_clause = [];
	
	function where_clause_out ($token) 
	{	
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$where = $this->var_filters;
		
		$this->stateData["where"] = $where;
		
		$this->var_where_clause = $where;
		
		$this->print_vmVar_Array ("stateData [where]", $this->stateData["where"]); 
		
		$this->print_vmVar_Array ("var_where_clause", $this->var_where_clause);
	}
	
	
	// =================================================================
	// "filters" : { "while" : [ [ "AND", "OR" ], "filter" ] },
	
	private $var_filters = [];
	
	function filters_loop ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$rParam = array_pop ($this->var_filter);
		$lParam = array_pop ($this->var_filter);

		$newFilter = 
		[ 
			"nOP" => $token, 
			"lParam" => $lParam,
			"rParam" => $rParam
		];
		
		$this->var_filter[] = $newFilter;
		
		$this->var_filters = $newFilter;
		
		$this->print_vmVar_Array ("var_filters", $this->var_filters);
	}
	
	function filters_exit ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_filters[] = array_pop ($this->var_filter);
		
		$this->print_vmVar_Array ("var_filters", $this->var_filters);
	}
	
	
	// =================================================================
	/*
		"filter" : 
		{
			"switch" : 
			[ 
				[ "NOT", "filter_exists" ], 
				[ "(", "filters", ")" ],
				[ "filter_exists" ]
			]
		},	 
	*/

	private $var_filter = [];
	
	function filter_in ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		// $this->var_filter = [];
	}

	function filter_matched_filter_exists_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		if ($token == "NOT")
		{ 
			$this->var_filter[] = ["NOT", array_pop ($this->var_filter_exists) ]; 
		}
		else
		{ $this->var_filter[] = array_pop ($this->var_filter_exists); }
		
		$this->print_vmVar_Array ("var_filter", $this->var_filter);
	}

	function filter_matched_filters_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_filter[] = array_pop ($this->var_filters);
		
		$this->print_vmVar_Array ("var_filter", $this->var_filter);
	}

	
	// =================================================================
	// =================================================================
	//"filter_exists" : { "if" : [ [ "EXISTS", "select_clause" ], "filter_1st_param" ] },
	
	private $var_filter_exists = [];
	
	function filter_exists_matched_select_clause_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_filter_exists[] = array_pop ($this->var_select_clause);
		
		$this->print_vmVar_Array ("var_filter_exists", $this->var_filter_exists);
	}
	
	function filter_exists_matched_filter_1st_param_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_filter_exists[] = array_pop ($this->var_filter_1st_param);
		
		$this->print_vmVar_Array ("var_filter_exists", $this->var_filter_exists);
	}

	/*
		"filter_1st_param" : 
		{ 
			"either" : 
			[ 
				"filter_contains_left_class", 
				"filter_left_param"
			] 
		},
	*/
	
	private $var_filter_1st_param = [];
	
	function filter_1st_param_matched_filter_contains_left_class_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_filter_1st_param[] = array_pop ($this->var_filter_contains_left_class);
		
		$this->print_vmVar_Array ("var_filter_1st_param", $this->var_filter_1st_param);
	}
	
	function filter_1st_param_matched_filter_left_param_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_filter_1st_param[] = array_pop ($this->var_filter_left_param);
		
		$this->print_vmVar_Array ("var_filter_1st_param", $this->var_filter_1st_param);
	}
	

	// "filter_left_param" : { "do" : [ "expression", "filter_test" ] },

	private $var_filter_left_param = [];
	
	function filter_left_param_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$lParam = array_pop ($this->var_expression);
		$objFilter = array_pop ($this->var_filter_test);
		$nOPs = $objFilter ["nOPs"];
		$rParam = $objFilter ["rParam"];

		$this->var_filter_left_param[] = 
		[ 
			"nOP" => $nOPs, 
			"lParam" => $lParam, 
			"rParam" => $rParam 
		];
		
		$this->print_vmVar_Array ("var_filter_left_param", $this->var_filter_left_param);
	}
	
	
	// "filter_contains_left_class" : { "do" : [ "class_name", "contains_test" ] },
	
	private $var_filter_contains_left_class = [];
	
	function filter_contains_left_class_in ($token)
	{ $this->in_Contains = true; }
	
	function filter_contains_left_class_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$lParam = array_pop ($this->var_class_name);
		$objContains = array_pop ($this->var_contains_test);
		$nOPs = $objContains ["nOP"];
		$rParam = $objContains ["rParam"];

		$contains = 
		[ 
			"nOP" => $nOPs,
			"lParam" => $lParam, 
			"rParam" => $rParam
		];

		$this->stateData ["contains"][] = $contains;
		$this->var_filter_contains_left_class[] = $contains;
		
		$this->print_vmVar_Array ("stateData [contains]", $this->stateData ["contains"]);
		$this->print_vmVar_Array ("var_filter_contains_left_class", $this->var_filter_contains_left_class);
		
		$this->in_Contains = false;
	}
	
	// =================================================================
	/*
		"contains_test" : 
		{ 
			"switch" : 
			[ 
				[ [ "CONTAINS" ], "class_name" ], 
				[ [ "DOES" ], "contain_not" ], 
				[ "error_contains" ]
			] 
		},
	*/
	
	private $var_contains_test = [];
	
	function contains_test_matched_class_name_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_contains_test[] = 
		[
			"nOP" => "CONTAINS", 
			"rParam" => array_pop ($this->var_class_name) 
		];

		$this->print_vmVar_Array ("var_contains_test", $this->var_contains_test);
	}

	function contains_test_matched_contain_not_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_contains_test[] = array_pop ($this->var_contain_not);
		
		$this->print_vmVar_Array ("var_contains_test", $this->var_contains_test);
	}

	// "contain_not" : { "if" : [ [ "NOT", "contain_test" ], "error_does_not_contain" ] },
	
	private $var_contain_not = [];
	
	function contain_not_matched_contain_test_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_contain_not[] = array_pop ($this->var_contain_test);
		
		$this->print_vmVar_Array ("var_contain_not", $this->var_contain_not);
	}
	

	// "contain_test" : { "if" : [ [ "CONTAIN", "class_name" ], "error_does_not_contain" ] },

	private $var_contain_test = [];
	
	function contain_test_matched_class_name_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_contain_test[] = 
		[
			"nOP" => ["NOT", "CONTAINS"], 
			"rParam" => array_pop ($this->var_class_name) 
		];
		
		$this->print_vmVar_Array ("var_contain_test", $this->var_contain_test);
	}
	  
	// =================================================================
	/*
		"filter_test" :
		{
			"switch" :
			[
				[ [ "=", "<>", "<", "<=", ">=",">" ], "comparison_test" ],

				[ "NOT", "not_test" ],
				[ "BETWEEN", "between_test" ],
				[ "LIKE", "like_test" ],
				[ "IS", "not_null_test" ],
				[ "IN", "set_test" ],
				
				[ "error_filter_test" ]
			]
		},	  
	*/
	
	private $nOPs = [];

	private $var_filter_test = [];

	function filter_test_in ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->nOPs [] = $token;
	}

	function filter_test_matched_comparison_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$rParam = array_pop ($this->var_comparision_test);
		$nOPs = array_pop ($this->nOPs);

		$this->var_filter_test[] = 
		[
			"nOPs" => $nOPs,
			"rParam" => $rParam
		];
		
		$this->print_vmVar_Array ("var_filter_test", $this->var_filter_test);
	}
	
	function filter_test_matched_not_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$rParam = array_pop ($this->var_not_test);
		$nOPs = array_pop ($this->nOPs);

		$this->var_filter_test[] = 
		[
			"nOPs" => $nOPs,
			"rParam" => $rParam
		];

		$this->print_vmVar_Array ("var_filter_test", $this->var_filter_test);
	}

	function filter_test_matched_between_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$rParam = array_pop ($this->var_between_test);
		$nOPs = array_pop ($this->nOPs);

		$this->var_filter_test[] = 
		[
			"nOPs" => $nOPs,
			"rParam" => $rParam
		];
		
		$this->print_vmVar_Array ("var_filter_test", $this->var_filter_test);
	}
	
	function filter_test_matched_like_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$rParam = array_pop ($this->var_like_test);
		$nOPs = array_pop ($this->nOPs);

		$this->var_filter_test[] = 
		[
			"nOPs" => $nOPs,
			"rParam" => $rParam
		];
		
		$this->print_vmVar_Array ("var_filter_test", $this->var_filter_test);
	}

	function filter_test_matched_not_null_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$rParam = array_pop ($this->var_not_null_test);
		$nOPs = array_pop ($this->nOPs);

		$this->var_filter_test[] = 
		[
			"nOPs" => $nOPs,
			"rParam" => $rParam
		];
		
		$this->print_vmVar_Array ("var_filter_test", $this->var_filter_test);
	}

	function filter_test_matched_set_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$rParam = array_pop ($this->var_set_test);
		$nOPs = array_pop ($this->nOPs);

		$this->var_filter_test[] = 
		[
			"nOPs" => $nOPs,
			"rParam" => $rParam
		];
		
		$this->print_vmVar_Array ("var_filter_test", $this->var_filter_test);
	}

	
	
	// =================================================================
	/*
	"not_test" : 
	{
		"switch" :
		[
			[ "BETWEEN", "between_test" ],
			[ "LIKE", "like_test" ],
			[ "IN", "set_test" ],
			
			[ "error_not_test" ]
		]
	},
	*/	
	
	private $var_not_test = [];
	
	function not_test_matched_between_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_not_test[] = array_pop ($this->var_between_test);
		
		$this->print_vmVar_Array ("var_not_test", $this->var_not_test);
	}
	
	function not_test_matched_like_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_not_test[] = array_pop ($this->var_like_test);
		
		$this->print_vmVar_Array ("var_not_test", $this->var_not_test);
	}

	function not_test_matched_set_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_not_test[] = array_pop ($this->var_set_test);
		
		$this->print_vmVar_Array ("var_not_test", $this->var_not_test);
	}

	
	// =================================================================
	// "set_test" : { "if" : [ [ "SELECT", "select_clause" ], "value_list" ] },

	private $var_set_test = [];

	function set_test_matched_select_clause_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_set_test[] = $this->var_select_clause;
		
		$this->print_vmVar_Array ("var_set_test", $this->var_set_test);
	}
	
	function set_test_matched_value_list_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_set_test[] = $this->var_value_list;
		
		$this->print_vmVar_Array ("var_set_test", $this->var_set_test);
	}
	
	// =================================================================
	// "not_null_test" : { "if" : [ [ "NOT", "null_test" ], "null_test" ] },

	private $var_not_null_test = [];
	
	function not_null_test_matched_null_test_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		if ($token == "NOT")
		{
			$this->var_not_null_test[] = [$token, array_pop ($this->var_null_test) ];
		}
		else
		{
			$this->var_not_null_test[] = array_pop ($this->var_null_test);
		}
		
		$this->print_vmVar_Array ("var_not_null_test", $this->var_not_null_test);
	}
	
	
	// =================================================================
	// "null_test" : { "if" : [ [ "NULL" ] ] },
	
	private $var_null_test = [];
	
	function null_test_matched_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_null_test[] = "NULL";
		
		$this->print_vmVar_Array ("var_null_test", $this->var_null_test);
	}
	
	
	// =================================================================
	// "like_test" : { "do" : [ "expression" ] },
	
	private $var_like_test = "";
	
	function like_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_like_test[] = array_pop ($this->var_expression);
		
		$this->print_vmVar_Array ("var_like_test", $this->var_like_test);
	}
	
	// =================================================================
	// "between_test" : { "do" : [ "expression", "between-test-and" ] },
	
	private $var_between_test = [];
	
	function between_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$lParam = array_pop ($this->var_expression);
		$rParam = array_pop ($this->var_between_test_and);

		$this->var_between_test[] = 
		[ 
			$lParam, 
			$rParam 
		];
		
		$this->print_vmVar_Array ("var_between_test", $this->var_between_test);
	}
	
	// =================================================================
	// "between_test_and" : { "if" : [ [ "AND", "expression" ], "error-expected-AND" ] },
	
	private $var_between_test_and = [];
	
	function between_test_and_matched_expression_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_between_test_and[] = array_pop ($this->var_expression) ;
		
		$this->print_vmVar_Array ("var_between_test_and", $this->var_between_test_and);
	}
	
	
	// =================================================================
	// "quantified_test" : { "do" : [ "select-clause" ] },
	
	private $var_quantified_test = [];
	
	function quantified_test_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_quantified_test[] = array_pop (var_select_clause);
		
		$this->print_vmVar_Array ("var_quantified_test", $this->var_quantified_test);
	}

	// =================================================================
	// [ [ "=", "<>", "<", "<=", ">=",">" ], "comparison-test" ],
	// "comparison_test" : { "if" : [ [ [ "ALL", "ANY", "SOME" ], "quantified_test" ], "expression" ] },
	
	private $var_comparision_test = [];
	
	function comparison_test_matched_quantified_test_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_comparision_test[] = array_pop (var_quantified_test);
		
		$this->print_vmVar_Array ("var_comparision_test", $this->var_comparision_test);
	}
	
	function comparison_test_matched_expression_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_comparision_test[] = array_pop ($this->var_expression);
		
		$this->print_vmVar_Array ("var_comparision_test", $this->var_comparision_test);
	}
	
	
	// =================================================================
	// =================================================================
	// "expression" : { "do" : [ "addition" ] },
	
	private $var_expression = [];
	
	function expression_out ($token)  
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_expression[] = array_pop ($this->var_addition);
		
		$this->print_vmVar_Array ("var_expression", $this->var_expression);
	}
	
	
	// =================================================================
	// "addition" : { "while" : [ [ "+", "-" ], "multiplication" ] },
	
	private $var_addition = [];
	
	function addition_loop ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$rParam = array_pop ($this->var_multiplication) ;
		$lParam = array_pop ($this->var_multiplication) ;
		
		$this->var_addition[] = 
		[ 
			"nOP" => $token, 
			"lParam" => $lParam,
			"rParam" => $rParam
		];
		
		$this->print_vmVar_Array ("var_addition", $this->var_addition);
	}

	function addition_exit ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_addition[] = array_pop ($this->var_multiplication);
		
		$this->print_vmVar_Array ("var_addition", $this->var_addition);
	}

	// =================================================================
	// "multiplication" : { "while" : [ [ "*", "//" ], "parenthises" ] },
	
	private $var_multiplication = [];
	
	function multiplication_loop ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$rParam = array_pop ($this->var_parenthises);
		$lParam = array_pop ($this->var_parenthises);
		
		$this->var_multiplication[] = 
		[ 
			"nOP" => $token, 
			"lParam" => $lParam,
			"rParam" => $rParam
		];
		
		$this->print_vmVar_Array ("var_multiplication", $this->var_multiplication);
	}

	function multiplication_exit ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_multiplication[] = array_pop ($this->var_parenthises);
		
		$this->print_vmVar_Array ("var_multiplication", $this->var_multiplication);
	}

	
	// =================================================================
	// "parenthises" : { "if" : [ [ "(", "expression", ")" ], "function_call" ] },
	
	private $var_parenthises = [];
	
	function parenthises_matched_expression_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_parenthises[] = array_pop ($this->var_expression);
		
		$this->print_vmVar_Array ("var_parenthises", $this->var_parenthises);
	}
	
	function parenthises_matched_function_call_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_parenthises[] = array_pop ($this->var_function_call);
		
		$this->print_vmVar_Array ("var_parenthises", $this->var_parenthises);
	}
	
	// =================================================================
	// "function_call" : { "do" : [ "distinct_functions" ] }, 
	
	private $var_function_call = [];
	
	function function_call_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_function_call[] = array_pop ($this->var_distinct_functions);
		
		$this->print_vmVar_Array ("var_function_call", $this->var_function_call);
	}
	
	// =================================================================
	// "distinct_functions" : { "if" : [ [ "DISTINCT", "getIdentifier" ], "all_functions" ] },
	
	private $var_distinct_functions = [];
	
	function distinct_functions_matched_getIdentifier_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_distinct_functions[] = array_pop ($this->params);

		$this->print_vmVar_Array ("var_distinct_functions", $this->var_distinct_functions);
	}
	
	function distinct_functions_matched_all_functions_out ($token)  
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_distinct_functions[] = array_pop ($this->var_all_functions);
		
		$this->print_vmVar_Array ("var_distinct_functions", $this->var_distinct_functions);
	}

	// "all_functions" : { "if" : [ [ "ALL", "getIdentifier" ], "base_functions" ] },
	
	private $var_all_functions = [];
	
	function all_functions_matched_getIdentifier_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_all_functions[] = array_pop ($this->params);
		
		$this->print_vmVar_Array ("var_all_functions", $this->var_all_functions);
	}
	
	function all_functions_matched_base_functions_out ($token) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_all_functions[] = array_pop ($this->var_base_functions);
		
		$this->print_vmVar_Array ("var_all_functions", $this->var_all_functions);
	}
	
	/*
		"base_functions" : 
		{ 
			"if" : 
			[ [ ["AVG", "MAX", "MIN", "SUM", "COUNT"], 
				"function_prototype" ], 
				"value_or_field" 
			] 
		},
	*/
	
	private $var_base_functions = [];
	
	function base_functions_matched_function_prototype_out ($token) 
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_base_functions[] = [ $token, array_pop ($this->var_function_prototype) ] ;
		
		$this->print_vmVar_Array ("var_base_functions", $this->var_base_functions);
	}

	function base_functions_matched_value_or_field_out ($token) 
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_base_functions[] = array_pop ($this->var_value_or_field);
		
		$this->print_vmVar_Array ("var_base_functions", $this->var_base_functions);
	}
	// =================================================================
	// "function_prototype" : { "if" : [ [ "(", "expression", ")" ] ] },


	private $var_function_prototype = [];
	
	function function_prototype_matched_expression_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_function_prototype [] = array_pop ($this->var_expression);
		
		$this->print_vmVar_Array ("var_function_prototype", $this->var_function_prototype);
	}

	// =================================================================
	// =================================================================
	// "values_list" : { "until" : [ "value_list", "," ] },
	
	private $var_values_list = [];
	
	function values_list_in ($token)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_values_list = [];
		$this->print_vmVar_Array ("var_values_list", $this->var_values_list);
	}

	function values_list_loop ($token)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_values_list[] = $this->var_value_list;
		
		$this->print_vmVar_Array ("var_values_list", $this->var_values_list);
	}
	

	// =================================================================
	// "value-list" : { "if" : [ [ "(", "values", ")" ] ] },	

	private $var_value_list = [];

	function value_list_matched_values_in ($token)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_value_list = [];
		$this->print_vmVar_Array ("var_value_list", $this->var_value_list);
	}

	function value_list_matched_values_out ($token)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_value_list = $this->var_values;
		
		$this->print_vmVar_Array ("var_value_list", $this->var_value_list);
	}

	// =================================================================
	// "values" : { "until" : [ "value", "," ] },

	private $var_values = [];

	function values_in ($token)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_values = [];		
		$this->print_vmVar_Array ("var_values", $this->var_values);
	}

	function values_loop ($token)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_values[] = array_pop ($this->var_value);
		
		$this->print_vmVar_Array ("var_values", $this->var_values);
	}

	// "value" : { "do" : [ "getValue" ] },
	
	private $var_value = [];
	
	function value_out ($token)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->var_value[] = array_pop ($this->var_params); 
		
		$this->print_vmVar_Array ("var_value", $this->var_value);
	}

	
	// =================================================================
	// =================================================================
	// "class_field_names" : { "until" : [ "class_field_name", "," ] },

	private $var_class_field_names = [];
	
	function class_field_names_loop ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_class_field_names[] = array_pop ($this->var_class_field_name);
		
		$this->print_vmVar_Array ("var_class_field_names", $this->var_class_field_names);
	}
	
	function class_field_names_exit ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_class_field_names[] = array_pop ($this->var_class_field_name);
		
		$this->print_vmVar_Array ("var_class_field_names", $this->var_class_field_names);
	}
	
	// "class_field_name" : { "do": [ "class_name", "dot_test_required" ] },
	// class [alias] . fieldname
	
	private $var_class_field_name = [];
	
	function class_field_name_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$ca = array_pop ($this->var_class_name);
		$this->add_Class ($ca["Class"]);

		$szField = array_pop ($this->var_dot_test_required);
		
		$ca ["Field"] = $szField;
			
		$this->var_class_field_name[] = $ca;
		
		$this->print_vmVar_Array ("classes", $this->stateData ["classes"]);
		$this->print_vmVar_Array ("var_class_field_name", $this->var_class_field_name);
	}

	// =================================================================
	// "value_or_field" : { "do": [ "class_name", "dot_test" ] },
	
	private $var_value_or_field = [];
	
	function value_or_field_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		

		if (count ($this->var_class_name) == 0)
		{
			$value = array_pop ($this->var_params);
			$ca = [ "value" => $value ];
		}
		else
		{
			$ca = array_pop ($this->var_class_name);
			$this->add_Class ($ca["Class"]);

			$szField = array_pop ($this->var_dot_test);

			if (strlen ($szField) != 0)
			{ $ca ["Field"] = $szField; }
			else
			{ 
				$ca ["Field"] = $ca ["Class"];
				$ca ["Class"] = "";
			}
		}
		
		$this->var_value_or_field[] = $ca;
		
		$this->print_vmVar_Array ("var_value_or_field", $this->var_value_or_field);
	}

	// =================================================================
	// =================================================================
	/*
		"field_name" : 
		{ 
			"do": [ "class_name", "dot_test" ], 
			"comment" : "[class][alias].field" 
		},
	*/
	
	private $var_field_name = [];
	
	function field_name_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
	}
	
	// =================================================================
	// =================================================================
	// "dot_test_required" : { "if" : [ [ ".", "getIdentifier"], "error_dot_test" ] },
	
	private $var_dot_test_required = [];

	function dot_test_required_matched_getIdentifier_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_dot_test_required[] = array_pop ($this->var_params);
		
		$this->print_vmVar2 ("var_params");
		$this->print_vmVar_Array ("var_dot_test_required", $this->var_dot_test_required);
	}

	// "dot_test" : { "if" : [ [ ".", "getIdentifier"] ] },

	private $var_dot_test = [];

	function dot_test_matched_getIdentifier_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_dot_test[] = array_pop ($this->var_params);
		
		$this->print_vmVar2 ("var_params");
		$this->print_vmVar_Array ("var_dot_test", $this->var_dot_test);
	}

	
	// =================================================================
	// "class_names" : { "until" : [ "class_name", "," ] },
	
	private $var_class_names = [];
	
	function class_names_loop ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_class_names [] = array_pop (var_class_name);
		
		$this->print_vmVar_Array ("var_class_names", $this->var_class_names);
	}
	
	// =================================================================
	// "class_name" :	{ "do" : [ "getIdentifier", "class_name_alias" ] },
	
	private $var_class_name = [];
	
	function class_name_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szClass = array_pop ($this->var_params);
		$szAlias = array_pop ($this->var_class_name_alias);

		$this->var_class_name[] = 
		[ 
			"Class" => $szClass, 
			"Alias" => $szAlias 
		];
		$this->print_vmVar_Array ("var_class_name", $this->var_class_name);
		
		$this->add_Alias ($szClass, $szAlias);
		$this->add_Class ($szClass);
		
		$this->print_vmVar_Array ("aliases", $this->stateData ["aliases"]);
		$this->print_vmVar_Array ("classes", $this->stateData ["classes"]);
	}

	// =================================================================
	// "class_name_alias" : { "if" : [ ["[", "alias_name", "]"] ] },
	
	private $var_class_name_alias = [];
	
	function class_name_alias_matched_alias_name_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_class_name_alias[] = array_pop ($this->var_alias_name);
		
		$this->print_vmVar2 ("var_params");
		$this->print_vmVar_Array ("var_class_name_alias", $this->var_class_name_alias);
	}


	// =================================================================
	// "alias_name" : { "do": [ "getIdentifier" ] },
	
	private $var_alias_name = "";
	
	function alias_name_out ($token)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->var_alias_name[] = array_pop ($this->var_params);
		
		$this->print_vmVar2 ("var_params");
		$this->print_vmVar_Array ("var_alias_name", $this->var_alias_name);
	}
	
	
	// =================================================================
	// =================================================================
	// "getIdentifier" : { "do": [ "param" ], "comment" : "param.type = 4" },
	function getIdentifier_out ($token)
	{	
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$token = array_pop ($this->var_params_wtokens);
		
		$this->var_params[] = $token ["value"];

		$this->print_vmVar_Array ("var_params", $this->var_params);
	}

	// "getNumber" : { "do": [ "param" ], "comment" : "param.type = 5" },
	function getNumber_out ($token)
	{	
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$token = array_pop ($this->var_params_wtokens);

		$this->var_params[] = $token ["value"];

		$this->print_vmVar_Array ("var_params", $this->var_params);
	}

	// "getString" : { "do": [ "param" ], "comment" : "param.type = 6" },
	function getString_out ($token)
	{	
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$token = array_pop ($this->var_params_wtokens);

		$this->var_params[] = $this->stripQuotes ($token ["value"]);

		$this->print_vmVar_Array ("var_params", $this->var_params);
	}

	// "getValue" : { "do": [ "param" ], "comment" : "param.type = 5 or 6" },
	function getValue_out ($token)
	{	
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$token = array_pop ($this->var_params_wtokens);

		$this->var_params[] = $this->stripQuotes ($token ["value"]);

		$this->print_vmVar_Array ("var_params", $this->var_params);
	}
	

	// =================================================================
	// =================================================================
	// "param" : { "param" : [] },

	private $var_params = [];
	private $var_params_wtokens = [];

	function param_token ($token)
	{	
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->print_vmVar_Array ("token", $token);

		$this->var_params_wtokens[] = 
		[
			"code" => $token->code,
			"value" => $token->value
		];
		
		// $value = $this->stripQuotes ($token->value);
		// $this->var_params[] = $value;
		
		// $this->print_vmVar_Array ("var_params", $this->var_params);
		$this->print_vmVar_Array ("var_params_wtokens", $this->var_params_wtokens);
	}

	
	// =================================================================
	// =================================================================
	
	function add_Class ($szClass)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		if 
			(!	( 
					$this->in_Using || 
					$this->in_Path || 
					$this->in_Contains
			)	) return;
		
		if (! in_array ($szClass, $this->stateData ["classes"]))
			$this->stateData ["classes"][] = $szClass; 
	}
	
	function add_Alias ($szClass, $szAlias)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		if 
			(!	( 
					$this->in_Using || 
					$this->in_Path || 
					$this->in_Contains
			)	) return;

		forEach ($this->stateData ["aliases"] AS $key => $val)
			if ($val["Class"] == $szClass)
				if ($val["Alias"] == $szAlias)
					return;
		
		$this->stateData ["aliases"][] = 
		[
			"Class" => $szClass, 
			"Alias" => $szAlias
		];
	}

	// =================================================================
	
	function print_vmVar ($var)
	{
		print ("<P style='color:green;'> " . $var . " = <pre style='color:green;'>"); 
		print_r($this->$var); 
		print ("</pre></P>");
	}
	
	function print_vmVar2 ($var)
	{
		print ("<P style='color:green;'>" . $var . " = [ ". implode(", ", $this->$var) . " ] </P>");
	}
	
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
	
	// =================================================================

	function stripQuotes ($value)
	{
		$len = strlen ($value);
		
		if (substr ($value, 0, 1) == "\"")
			$value = substr ($value, 1, $len);

		if (substr ($value, -1, 1) == "\"")
			$value = substr ($value, 0, -1);

		if (substr ($value, 0, 1) == "'")
			$value = substr ($value, 1, $len);

		if (substr ($value, -1, 1) == "'")
			$value = substr ($value, 0, -1);

		return ($value);
	}

}

?>
