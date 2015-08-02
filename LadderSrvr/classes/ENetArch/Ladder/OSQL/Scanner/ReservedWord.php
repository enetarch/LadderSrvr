<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Ladder\OSQL\Scanner;

use \ENetArch\Common\DocFoo;


use ENetArch\Ladder\OSQL\Scanner\ErrorMsgs as ErrorMsgs;

docFoo 
( Array
(
	"Class" => "ReservedWord",
	
	"Description" => 
		"Contains a reserved word used by the scanner and parser",

	"Requirements" => Array 
	(
		"Store a word and token pair used by the scanner", 
		"Retrieve the word, word length and token when needed", 
	),

	"Includes" => Array
		(
			"ErrorMsgs - List of Error Messages used by the ByteCode class",
		),

	"Globals" => Array
		(
			"ErrorMsgs - List of Error Messages used by the ByteCode class",
		),

	"Variables" => Array
		(
			"PRIVATE STRING szWord - stores the Word",
			"PRIVATE INTEGER nToken - stores the byte code",
		),

	"Methods" => Array
		(
			"init - initalizes the token instance", 
		),
	
	"Properties" => Array
		(
			"PUBLIC STRING Word - GET the token word used ", 
			"PUBLIC STRING Token - GET the token used", 
			"PUBLIC STRING length - GET the token word length used", 
		),
		
	"Errors" => Array
		(
			"The WORD cannot be 0 length long",
			"The TOKEN cannot be a 0 value"
		),
		
	"Tests" => Array
		(
			"Confirm that no error arrises when parameters are in the bounds",
			"Confirm an error arrises when a word is 0 length is passed",
			"Confirm an error arrises when a word of spaces is passed ",
			"Confirm an error arrises when 0 value token is passed",
			"Confirm the Word passed in is the same",
			"Confirm the Token passed in is the same",
			"Confirm the Word length passed in is the same",
		),
));


class ReservedWord
{
	Private $szWord = "";
	Private $nToken = "";

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
		
		self::docFoo_init ();
		self::docFoo_Word  ();
		self::docFoo_Token ();
		self::docFoo_length ();
	}

	// ============================================
	
	public static function docFoo_init ()
	{ docFoo ( Array 
		(
		"Method" => "init",
		"Syntax" => "init ()",
		"Description" => "Name / Value pair - the keyword and token code",
		"Requirements" => Array 
			(
				"Store the Name and Value of the keyword and token code as pair",
			),
		"Parameters" => Array
			(
				"thsWord" => "A word that the scanner is looking for",
				"thsToken" => "The token used in a Byte Code object",
			),
		"Validations" => Array
			(
				"if thsWord.length = 0, error - The Word can't be 0 chars long",
				"if thsToken = 0, error - The Token can't be 0 ",
			),
		"Core Code" => "Trim the word passed in and store thsWord and thsToken",		
		"Errors" => Array
		(
			"The Word can't be 0 chars long",
			"The Token can't be 0",
		),
		"Tests" => Array
			(
				"Confirm that no error arrises when parameters are in the bounds",
				"Confirm an error arrises when a word is 0 length is passed",
				"Confirm an error arrises when a word of spaces is passed ",
				"Confirm an error arrises when 0 value token is passed",
			),		
		) );
	}

	Public Function init($thsWord, $thsToken)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$thsWord = Trim($thsWord);

		// =============================
		// Validation
		
		If (strLen($thsWord) == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg021, 21, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return (false);
		}
		
		If ($thsToken == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg022, 22, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return (false);
		}

		// =============================
		// Core Code

		$this->szWord = $thsWord;
		$this->nToken = $thsToken;
	}

	// ============================================
	
	public static function docFoo_Word ()
	{ docFoo ( Array 
		(
		"Property" => "Word",
		"Syntax" => "Word ()",
		"Description" => "GET the token word used",
		"Tests" => Array 
			(
				"Confirm the Word passed in is the same",
			),
		) );
	}

	Public Function Word()
	{ return ($this->szWord); }

	// ============================================
	
	public static function docFoo_Token ()
	{ docFoo ( Array 
		(
		"Property" => "Token",
		"Syntax" => "Token ()",
		"Description" => "GET the token value used to be stored in the ByteCode",
		"Tests" => Array 
			(
				"Confirm the Token passed in is the same",
			),
		) );
	}

	Public Function Token()
	{ return ($this->nToken); }

	// ============================================
	
	public static function docFoo_length ()
	{ docFoo ( Array 
		(
		"Property" => "length",
		"Syntax" => "length ()",
		"Description" => "GET the length of the word the scanner is looking for",
		"Tests" => Array 
			(
				"Confirm the length of the Word passed in is the same",
			),
		) );
	}

	Public Function length()
	{ return (strLen($szWord)); }
}
?>
