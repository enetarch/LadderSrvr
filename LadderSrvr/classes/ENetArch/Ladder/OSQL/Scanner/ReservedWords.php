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

use ENetArch\Ladder\OSQL\Scanner\ReservedWord;
use ENetArch\Ladder\OSQL\Scanner\ErrorMsgs as ErrorMsgs;

docFoo 
( Array
(
	"Class" => "ReservedWords",
	
	"Description" => "A list of keywords for the scanner to look for",

	"Requirements" => Array 
		(
			"Store a list of keywords used by the scanner", 
			"Retrieve token code by keyword", 
			"Retrieve the Reserved Word object by position x",
		),

	"Includes" => Array
		(
			"ErrorMsgs - List of Error Messages used by the ByteCode class",
			"ReservedWord - Contains a reserved word and token pair used by the scanner and parser",
		),

	"Globals" => Array
		(
			"ErrorMsgs - List of Error Messages used by the ByteCode class",
		),

	"Methods" => Array
		(
			"AddItem - Store a keyword / token code value pair as a Reserved Word for retrieval ", 
			"Delete - Remove the a Reserved Word at position X ", 
			"length - Return the number of Reserved Words stored", 
			"clear - Clear all the Reserved Words from the container", 
			"isFound - determines if the Reserved Word is in the container",
			"Find - finds the Reserved Word in the container",
			"clear - Clear all the reserved words from the container", 			
		),
	
	"Properties" => Array
		(
			" PRIVATE INTEGER Instances[] - stored bytecodes",
		),
		
	"Errors" => Array
		(
			"index out of range",
		),
		
	"Tests" => Array
		(
			"Add a keyword / code pair to the container",
			"Find a keyword in the container",
			"Confirm that an empty container doesn't throw an error",
			"Confirm a keyword not in the container doesn't throw an error ",
			"Confirm a blank keyword returns an error",
			"Retrieve a keyword in the container bounds",
			"Retrieve a keyword outside the container bounds",
			"Delete a keyword in the container bounds",
			"Delete a keyword outside teh container bounds",
			"Delete a keyword from an empty container",
			"Confirm that no error arrises when parameters are in the bounds",
			"Confirm an error arrises when a word is 0 length is passed",
			"Confirm an error arrises when a word of spaces is passed ",
			"Confirm an error arrises when 0 value token is passed",
			"Confirm the count matches the number of instances created",
			"Confirm a Reserved Word is found",
			"Confirm a Reserved Word is not found",
		),
));



class ReservedWords
{
	Private $Instances = Array();

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
		
		self::docFoo_AddItem ();
		self::docFoo_Delete  ();
		self::docFoo_Item ();
		self::docFoo_count ();
		self::docFoo_length ();
		self::docFoo_isFound ();
		self::docFoo_Find ();
		
	}

	// ============================================
	
	public static function docFoo_AddItem ()
	{ docFoo ( Array 
		(
		"Method" => "AddItem",
		"Syntax" => "AddItem ()",
		"Description" => "Create a new instance of Reserved Word the scanner will find.",
		"Parameters" => Array
			(
				"thsWord" => "A word that the scanner is looking for",
				"thsToken" => "The token used in a Byte Code object",
			),
		"Validations" => Array
			(
				"if thsWord.length = 0, error - The Word can't be 0 chars long",
				"if the token is found in the container, error - keyword already exists",
				"if thsToken = 0, error - The Token can't be 0 ",
			),
		"Core Code" => "Trim the word passed in and store thsWord and thsToken",		
		"Returns" => "NULL | ReservedWord",
		"Errors" => Array
			(
				"The Word can't be 0 chars long",
				"The Token can't be 0",
			),
		"Tests" => Array
			(
				"Add a keyword / code pair to the container",
				"Confirm that no error arrises when parameters are in the bounds",
				"Confirm an error arrises when a word is 0 length is passed",
				"Confirm an error arrises when a word of spaces is passed ",
				"Confirm an error arrises when 0 value token is passed",
				"Confirm duplicate token causes error to arise",
			),		
		) );
	}

	Public Function AddItem ($thsWord, $thsToken) 
	{
		gblError()->Clear();
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$thsWord = trim ($thsWord);
		// print (count ($this->Instances) . " " . $thsWord . " " . $thsToken . "<BR>");
		
		// =============================
		// Validation
		
		If (strlen($thsWord) == 0) 
		{
			gblError()->init (ErrorMsgs::errorMsg021, 21, "", __METHOD__, $thsToken);
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return (null);
		}
		
		If ($this->isFound($thsWord)) 
		{
			gblError()->init(ErrorMsgs::errorMsg023, 23, "", __METHOD__, $thsWord);
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return (null);
		}

		If ($thsToken == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg022, 22, "", __METHOD__, $thsWord);
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return (null);
		}
		
		// =============================
		// Core Code
		
		$instance = New ReservedWord ();
		$instance->init($thsWord, $thsToken);
		
		If (gblError()->No() != 0) 
			return (null);
		
		$this->Instances[] = $instance;
		
		// =============================
		// Returns
		
		return ($instance);
	}

	// =============================================

	public static function docFoo_Delete ()
	{ docFoo ( Array 
		(
		"Method" => "Delete",
		"Syntax" => "Delete ( nPos )",
		"Parameters" => Array
			(
				"nPos - INTEGER - the position of the  Reserved Word to be deleted",
			),
		"Description" => "delete the keyword at position x",
		"Validations" => Array
			(
				"If (count (Instances) == 0), Error - The Container is Empty",
				"if (0 < nPos >= count (Instances), Error - Index Out of Range",
			),
		"Core Code" => " delete the Instance at position X)",
		"Errors" => Array 
			(
				"The Container is Empty",
				"Index out of Range",
			),
		"Tests" => Array
			(
				"Delete a keyword in the container bounds",
				"Delete a keyword outside teh container bounds",
				"Delete a keyword from an empty container",
			),
	 ) );
	}

	Public Function Delete ($nPos)
	{
		// Delete a Class from the container
		
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		// =============================
		// Validation
		
		$nCount = count ($this->Instances);
		
		If ($nCount == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg031, 31, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return;
		}
		
		If (($nPos < 0) || ($nPos > $nCount))
		{
			gblError()->init(ErrorMsgs::errorMsg032, 32, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return;
		}
		
		// =============================
		// Core Code
		
		unset ($this->Instances[$nPos]);
		$this->Instances = array_values ($this->Instances);

		// =============================
		// Returns
		
		return (True);
	}

	// =============================================

	public static function docFoo_Item ()
	{ docFoo ( Array 
		(
		"Method" => "Item",
		"Syntax" => "Item ( nPos )",
		"Parameters" => Array
			(
				"nPos - INTEGER - the position of the  Reserved Word to be deleted",
			),
		"Description" => "retrieve keyword at position x",
		"Validations" => Array
			(
				"If (count (Instances) == 0), Error - The Container is Empty",
				"if (0 < nPos >= count (Instances), Error - Index Out of Range",
			),
		"Core Code" => " return the Instance at position X",
		"Errors" => Array 
			(
				"The Container is Empty",
				"Index out of Range",
			),
		"Tests" => Array
			(
				"Retrieve a keyword in the container bounds",
				"Retrieve a keyword outside teh container bounds",
			),
	 ) );
	}

	Public Function Item ($nPos) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		// =============================
		// Validation
		
		$nCount = count ($this->Instances);
		
		If ($nCount == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg031, 31, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return (null);
		}
		
		If (($nPos < 0) || ($nPos >= $nCount ) )
		{
			gblError()->init(ErrorMsgs::errorMsg032, 32, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return (null);
		}
		
		// =============================
		// Core Code / Returns
		
		return ($this->Instances[$nPos]);
	}

	// =============================================

	public static function docFoo_Count ()
	{ docFoo ( Array 
		(
		"Method" => "Count",
		"Syntax" => "Count ()",
		"Description" => "return the number of keywords stored in the container",
		"Core Code" => "count (Instances)",
		"Tests" => Array 
			(
				"Confirm the count matches the number of instances created",
			),
	 ) );
	}

	Public Function Count () 
	{	return ( count ( $this->Instances)); }

	// =============================================

	public static function docFoo_isFound ()
	{ docFoo ( Array 
		(
		"Method" => "isFound",
		"Syntax" => "isFound ( nPos )",
		"Parameters" => Array
			(
				"nPos - INTEGER - the position of the  Reserved Word to be deleted",
			),
		"Description" => "is the keyword found in the container",
		"Returns" => "TRUE | FALSE - the word is found",
		"Tests" => Array 
			(
				"Confirm a Reserved Word is found",
				"Confirm a Reserved Word is not found",
			),
	 ) );
	}

	Public Function isFound ($thsWord)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		// =============================
		// Core Code
		
		$objWord = $this->Find ($thsWord);
		If (gblError()->No() != 0) 
			gblError()->Addpath (__METHOD__); 
		
		// =============================
		// Returns
		
		If (null != ($objWord)) return (True);
	}

	// =============================================

	public static function docFoo_Find ()
	{ docFoo ( Array 
		(
		"Method" => "Find",
		"Syntax" => "Find ( nPos )",
		"Parameters" => Array
			(
				"nPos - INTEGER - the position of the  Reserved Word to be deleted",
			),
		"Description" => "Find the keyword if it is in the container",
		"Validations" => Array
			(
				"If (count (Instances) == 0), return NULL",
				"if thsWord.length = 0, error - The Word can't be 0 chars long",
			),
		"Core Code" => "return the reserved word if found in the list",
		"Returns" => "NULL | ReservedWord",
		"Errors" => Array 
			(
				"The Word is NULL",
			),
		"Tests" => Array
			(
				"Find a keyword in the container",
				"Confirm that an empty container doesn't throw an error",
				"Confirm a keyword not in the container doesn't throw an error ",
				"Confirm a blank keyword returns an error",
			),
	 ) );
	}

	Public Function Find ($thsWord) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$thsWord = Trim($thsWord);
		
		// =============================
		// Validation
		
		$nCount = count ($this->Instances);
		
		If ( $nCount == 0) 
			return;
		
		If (strLen($thsWord) == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg021, 21, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return;
		}
		
		// =============================
		// Core Code
		
		For ($t = 0; $t < $nCount; $t++)
			If ($this->Instances[$t]->Word() == $thsWord) 
				return ( $this->Instances[$t] );
		
		return (null);
	}

	// =============================================

	public static function docFoo_getKeyword ()
	{ docFoo ( Array 
		(
		"Method" => "getKeyword",
		"Syntax" => "getKeyword ( nCode )",
		"Parameters" => Array
			(
				"nCode - INTEGER - the code associated with the Reserved Word ",
			),
		"Description" => "Find the keyword by it's code in the container",
		"Validations" => Array
			(
				"If (count (Instances) == 0), return NULL",
				"if thsWord.length = 0, error - The Word can't be 0 chars long",
			),
		"Core Code" => "return the reserved word if found in the list",
		"Returns" => "NULL | ReservedWord",
		"Errors" => Array 
			(
				"The Word is NULL",
			),
		"Tests" => Array
			(
				"Find a keyword in the container",
				"Confirm that an empty container doesn't throw an error",
				"Confirm a keyword not in the container doesn't throw an error ",
				"Confirm a blank keyword returns an error",
				"Confirm the code returns an error",
			),
	 ) );
	}

	Public Function getKeyword ($thsCode) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		// =============================
		// Validation
		
		$nCount = count ($this->Instances);
		
		If ( $nCount == 0) 
			return;
		
		If ($thsCode == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg025, 25, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return;
		}
		
		// =============================
		// Core Code
		
		For ($t = 0; $t < $nCount; $t++)
			If ($this->Instances[$t]->Token() == $thsCode) 
				return ( $this->Instances[$t] );
		
		return (null);
	}

	// ============================================
	
	public static function docFoo_clear ()
	{ docFoo ( Array 
		(
		"Method" => "clear",
		"Syntax" => "clear ()",
		"Description" => "clear the codes from the container",
		"Core Code" => "reset the memory used by the instances to 0",
	 ) );
	}

	Public Function clear()
	{ $this->Instances = Array(); }


}
?>
