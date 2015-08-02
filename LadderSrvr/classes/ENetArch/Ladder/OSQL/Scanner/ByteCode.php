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

use ENetArch\Ladder\OSQL\Scanner\ErrorMsgs;

docFoo 
( Array
(
	"Class" => "ByteCode",
	
	"Description" => 
		"Manages the storage, update and retrieval of byte codes identified during the 
		scanning process.",

	"Requirements" => Array 
		(
			"Store byte codes for later retrieval", 
			"return position of added code ", 
			"backup / remove last code entered ", 
			"updated a code as position x ", 
			"retrieve code at position x ", 
			"return the number of codes stored in the container ", 
			"return a space seperated string of codes ", 
			"clear the codes from the container ", 
		),

	"Includes" => Array
		(
			"ErrorMsgs - List of Error Messages used by the ByteCode class",
		),

	"Globals" => Array
		(
			"ErrorMsgs - List of Error Messages used by the ByteCode class",
		),

	"Methods" => Array
		(
			"AddItem - Store a byte code for later retrieval ", 
			"backup - Remove the last byte code added ", 
			"update - Update a byte code at position x", 
			"code - Retrieve the byte code at position x", 
			"length - Return the number of byte codes stored", 
			"toArray - Return an array of the byte codes", 
			"toString - Return a string seperated list of the byte codes", 
			"clear - Clear all the byte codes from storage", 			
		),
	
	"Properties" => Array
		(
			" PRIVATE INTEGER Instances[] - stored bytecodes",
		),
		
	"Errors" => Array
		(
			"The Index out of range",
			"The Container is Empty",
		),
		
	"Tests" => Array
		(
			"Confirm stored byte codes can be retrieved later",
			"Confirm a random number of codes match their stored values",
			"Confirm error messages are returned during retrieval boundary violations",
			"Compare byte codes added and stored from position of added code",
			"Compare codes updated at position x match",
			"Confirm that the container can be cleared of all byte codes",
			"Confirm that codes can be remove from the end of the stored list ",
			"Confirm that a space seperated string of codes is returned",
			"Confirm error messages are returned during update boundary violations",
			"Confirm an array is returned when requested",
			"Confirm that the Private Instances Array is not changed when a copy is requested"
		),
));

class ByteCode 
{
	private $Instances = Array ();

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
		
		self::docFoo_AddItem ();
		self::docFoo_backup ();
		self::docFoo_update ();
		self::docFoo_code ();
		self::docFoo_length ();
		self::docFoo_toString ();
		self::docFoo_clear ();
	}

	// ============================================
	
	public static function docFoo_AddItem ()
	{ docFoo ( Array 
		(
		"Method" => "AddItem",
		"Syntax" => "docFoo ()",
		"Description" => "Store byte codes for later retrieval",
		"Core Code" => "push code onto array",
		"Errors" => Array ("",),
	 ) );
	}


	Public Function AddItem ($thsToken)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->Instances[] = $thsToken;
		return ( count ($this->Instances)-1 );
	}

	// ============================================
	
	public static function docFoo_backup ()
	{ docFoo ( Array 
		(
		"Method" => "setup",
		"Syntax" => "backup ()",
		"Description" => "backup / remove last code entered",
		"Core Code" => "pop code from array",
		"Errors" => Array ("",),
	 ) );
	}

	Public Function backup()
	{	array_pop ($this->Instances); }

	// ============================================
	
	public static function docFoo_update ()
	{ docFoo ( Array 
		(
		"Method" => "setup",
		"Syntax" => "update ( nPos )",
		"Parameters" => Array
			(
				"nPos - INTEGER - the position of the byte code to be retrieved",
			),
		"Description" => "prepare tests to be run in an existing environment",
		"Validations" => Array
			(
				"if (0 < nPos >= count (Instances), Error - Index Out of Range",
			),
		"Core Code" => "replace code at index X with new code",
		"Errors" => Array 
			(
				"The Container is Empty",
				"Index out of Range",
			),
	 ) );
	}

	Public Function update ($nPos, $thsToken)
	{	
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$nCount = count ($this->Instances);

		If ($nCount == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg031, 31, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return (null);
		}
		
		if (($nPos <0) || ($nPos >= $nCount))
		{
			gblError()->init (ErrorMsgs::errorMsg040, 1, "", __METHOD__, $nPos);
			return (-1);
		}
				
		$this->Instances [$nPos] = $thsToken; 
	}

	// ============================================
	
	public static function docFoo_code ()
	{ docFoo ( Array 
		(
		"Method" => "code",
		"Syntax" => "code ( nPos )",
		"Parameters" => Array
			(
				"nPos - INTEGER - the position of the byte code to be retrieved",
			),
		"Description" => "retrieve code at position x",
		"Validations" => Array
			(
				"if (0 < nPos >= count (Instances), Error - Index Out of Range",
			),
		"Core Code" => " return ( Instance as position X)",
		"Errors" => Array 
			(
				"The Container is Empty",
				"The Index out of Range",
			),
	 ) );
	}

	Public Function code($nPos)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$nCount = count ($this->Instances);
		If ($nCount == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg031, 31, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return (null);
		}
		
		if (($nPos <0) || ($nPos >= $nCount))
		{
			gblError()->init (ErrorMsgs::errorMsg040, 1, "", __METHOD__, $nPos);
			return (-1);
		}
		
		return ( $this->Instances[$nPos] ); 
	}

	// ============================================
	
	public static function docFoo_length ()
	{ docFoo ( Array 
		(
		"Method" => "length",
		"Syntax" => "length ()",
		"Description" => "return the number of codes stored in the container",
		"Core Code" => "count (Instances)",
	 ) );
	}

	Public Function length()
	{ return (count ($this->Instances) ); }

	// ============================================

	public static function docFoo_toArray()
	{ docFoo ( Array 
		(
		"Method" => "toArray",
		"Syntax" => "toArray ()",
		"Description" => "return a array of byte codes",
		"Core Code" => "create an array of byte codes",
	 ) );
	}

	Public Function toArray()
	{
		$szRtn = $this->Instances;

		return ($szRtn);
	}

	// ============================================

	public static function docFoo_reset()
	{ docFoo ( Array 
		(
		"Method" => "reset",
		"Syntax" => "reset (aryByteCodes)",
		"Description" => "set the bytecode to a previous state",
		"Parameters" => Array
		(
			"" => "",
			
		),
		"Core Code" => "set the internal array to the given array",
	 ) );
	}

	Public Function reset($aryByteCodes)
	{ $this->Instances = $aryByteCodes; }

	// ============================================
	
	public static function docFoo_toString()
	{ docFoo ( Array 
		(
		"Method" => "toString",
		"Syntax" => "toString ()",
		"Description" => "return a space seperated string of codes",
		"Core Code" => "string integer byte code values",
	 ) );
	}

	Public Function toString()
	{
		$szRtn = implode (", ", $this->Instances);

//		$nCount = count ($this->Instances);
		
//		For ($t = 0;  $t <$nCount; $t++)
//			$szRtn .= $this->Instances[$t] . " ";
		
		return ($szRtn);
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

ByteCode::docFoo();
?>
