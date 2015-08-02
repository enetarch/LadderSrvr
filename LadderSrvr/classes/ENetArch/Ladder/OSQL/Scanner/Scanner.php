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
use ENetArch\Ladder\OSQL\Scanner\ByteCode;
use ENetArch\Ladder\OSQL\Scanner\TokenCode;
use ENetArch\Ladder\OSQL\Scanner\LiteralType;
use ENetArch\Ladder\OSQL\Scanner\Literal;
use ENetArch\Ladder\OSQL\Scanner\CharCode;
use ENetArch\Ladder\OSQL\Scanner\ReservedWords;

include_once ("ErrorMsgs.php");

docFoo 
( Array
(
	"Class" => "Scanner",
	
	"Description" => 
		"Reads through a text document, identifies keywords, numbers, strings, and other
		special characters, placing them into a byte code string and a parameter list.",


	"Requirements" => Array 
		(
		),

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
			"Syntax Error",
			"TOO MANY SYNTAX ERRORS",
			"The Reserved Word List is NULL",
			"The Buffer is NULL",
			"INVALID NUMBER ",
			"INVALID FRACTION ",
			"INVALID EXPONENT ",
			"TOO MANY DIGITS ",
			"REAL OUT OF RANGE ",
			"INTEGER OUT OF RANGE ",
			"Unrecognized Character",
			"The first digit is not a Number",
		),
		
	"Tests" => Array
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
			"Confirm the bytecode is correct",
			"Confirm that all objects are initialized",
			"Confirm reserved words are case insensitive", 
		),
));



class Scanner
{
	Const EOFCHAR = 255;

	Const MAXINTEGER = 32767;
	Const MAXDIGITCOUNT = 20;
	Const MAXEXPONENT = 37;

	//*--------------------------------------------------------------
	//*  Globals
	//*--------------------------------------------------------------

	Public $token = null;   //* Token

	private $ch = "";        //* current input character
	
	private $sourceBuffer = "";      //* source file buffer
	private $sourceBufferLen = 0;
	private $bufferp = 0;              //* source buffer position ptr
	private $objRWords = null; // As RWordscntr //* List of Reserved Words
	private $objByteCode = null; // As New ByteCodecntr  //* List of tokens found

	private $wordstring = "";	//* downshifted
	private $digitcount = 0;           //* total no-> of digits in number
	private $counterror = false;           //* too many digits in number?

	private $chartable = Array(); // char table
	

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
		self::docFoo_charcode ();
		self::docFoo_Position ();
		self::docFoo_init ();
		self::docFoo_getChar ();
		self::docFoo_skipBlanks ();
		self::docFoo_getToken ();
		self::docFoo_getWord ();
		self::docFoo_getNumber ();
		self::docFoo_getString ();
		self::docFoo_getSpecial ();
		self::docFoo_accumulateValue ();
		self::docFoo_isReservedWord ();
	}

	// ============================================
	
	public static function docFoo_construct ()
	{ docFoo ( Array 
		(
		"Method" => "__construct",
		"Syntax" => "__construct ()",
		"Description" => "Initialize an instance of the Scanner",
		"Core Code" => "Initialize the token, reserved words, bytecodes, and literal",
		"Tests" => Array 
			(
				"Confirm that all objects are initialized",
			),
	 ) );
	}
	
	public function __construct ()
	{
		$this->token = New Token();
		$this->objRWords = new ReservedWords(); 
		$this->objByteCode = new ByteCode(); 

		$this->ch="";

		For ($ch = 0; $ch< 256; $ch++) $this->chartable[$ch] = CharCode::cSPECIAL;
		For ($ch = ord("0"); $ch < ord("9") +1; $ch++) $this->chartable[$ch] = CharCode::cDIGIT;
		For ($ch = ord("A"); $ch < ord("Z") +1; $ch++) $this->chartable[$ch] = CharCode::cLETTER;
		For ($ch = ord("a"); $ch < ord("z") +1; $ch++) $this->chartable[$ch] = CharCode::cLETTER;
		
		$this->chartable[ord("_")] = CharCode::cLETTER;
		$this->chartable[ord("'")] = CharCode::cQUOTE;
		$this->chartable[ord('"')] = CharCode::cQUOTE;
		$this->chartable[ord("#")] = CharCode::cQUOTE;
		$this->chartable[self::EOFCHAR] = CharCode::cEOFCODE;
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

	Public Function ByteCode() 
	{ return ($this->objByteCode); }

	// ============================================

	public static function docFoo_reset ()
	{ docFoo ( Array 
		(
		"Property" => "ByteCode",
		"Syntax" => "ByteCode ()",
		"Description" => "Initialize an instance of the Scanner",
		"Parameters" => Array
		(
			"position" => "Where to restart the scanner from",
			"bytecode" => "The code that had been previous codified",
		),
		"Returns" => "The byte code container",
		"Comments" => 
		" reset should probaby be a getState / setState pair.
		",
		"Tests" => Array
			(
				"Confirm the position is correct",
				"Confirm the bytecode is correct",
			),
		) );
	}

	Public Function getState ()
	{
		$thsState = [];
		
		$thsState ["bufferp"] = $this->bufferp;
		$thsState ["bytecode"] = $this->objByteCode->toArray ();
		$thsState ["ch"] = $this->ch;
		$thsState ["token"] = clone ($this->token);
		
		return ($thsState);
	}
	
	Public Function setState($thsState) 
	{ 
		$this->bufferp = $thsState ["bufferp"];
		$this->objByteCode->reset ($thsState ["bytecode"]);
		$this->ch = $thsState ["ch"];
		$this->token = clone ($thsState ["token"]);
	}

	// ============================================

	public static function docFoo_CharCode ()
	{ docFoo ( Array 
		(
		"Property" => "CharCode",
		"Syntax" => "CharCode ()",
		"Description" => "Return the charcode for the character passed in.",
		"Returns" => "CHARCODE",
		"Tests" => Array
			(
				"Confirm inner boundaries of the charcodes",
				"Confirm outer boundaries of the charcodes",
			),
		) );
	}

	Private Function CharCode ($ch)
	{ return ($this->chartable [ ord ($ch) ] ); }


	// ============================================

	public static function docFoo_Position ()
	{ docFoo ( Array 
		(
		"Property" => "Position",
		"Syntax" => "Position ()",
		"Description" => "The positio nof the buffer pointer",
		"Returns" => "The position of the buffer pointer",
		"Tests" => Array
			(
				"Confirm position of buffer pointers during the scanning process",
			),
		) );
	}

	Public Function Position() 
	{ return ($this->bufferp); }

	// ============================================

	public static function docFoo_init ()
	{ docFoo ( Array 
		(
		"Method" => "init",
		"Syntax" => "init ( thsBuffer, thsRsrvWords )",
		"Parameters" => Array
			(
				"thsBuffer - The text string to scan for tokens",
				"thsRsrvWords - The array of reserved words the scanner is looking for.",
			),
		"Description" => "Initialize an instance of the Scanner",
		"Validations" => Array
			(
				"if thsBuffer.length = 0, then error - Buffer is empty",
				"if thsRsrvWords.length = 0, then error - Reserved Words is empty",
			),
		"Core Code" => 
			" 
				Reset the ByteCode object, 
				Store teh Buffer and Reserved Word List
				Initialize a Character Table to identify Alphabet, Digit and Special Chars
				Get the first character from the Buffer
			",
		"Errors" => Array
			(
				"The Reserved Words are NULL",
				"The Buffer is empty",
			),
		"Tests" => Array
			(
				"Confirm that the text buffer length 0 returns an error",
				"Confirm that a null Reserved Word list returns an error",
				"Confirm that the scanner can find specific reserved words"
			),
		) );
	}

	Public Function init ($thsBuffer, ReservedWords $thsRsrvWords)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->bufferp = 0;
		// =============================
		// Validation
		
		If (null == ($thsRsrvWords)) 
		{
			gblError()->init(ErrorMsgs::errorMsg003, 3, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return;
		}
		
		If (strlen($thsBuffer) == 0) 
		{
			gblError()->init(ErrorMsgs::errorMsg004, 4, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return;
		}
		
		// =============================
		// Core Code
		
		$this->objByteCode->clear();
		
		$this->objRWords = $thsRsrvWords;
		$this->sourceBufferLen = strlen ($thsBuffer);
		$this->sourceBuffer = $thsBuffer;
		$this->token = new Token();

		$this->getChar();
	}

	//*--------------------------------------------------------------
	//*  getChar      $ch to the next character from the
	//*       source buffer->
	//*--------------------------------------------------------------

	// ============================================

	public static function docFoo_getChar ()
	{ docFoo ( Array 
		(
		"Method" => "getChar",
		"Syntax" => "getChar ()",
		"Description" => "Get the next character from the Buffer",
		"Validations" => Array
			(
				"if buffer position > buffer.length, then return EOF",
			),
		"Core Code" => 
			"
				Get the next char in the Buffer. 
				Convert white space chars to spaces.
				Return the resulting character
			",
		"Returns" => "The character retrieved from the buffer",
		"Tests" => Array
			(
				"Confirm Buffer returns EOF when end of Buffer is reached",
				"Confirm characters returned are in order of those put into the Buffer",
				"Confirm TAB characters are turned to spaces",
				"Confirm LINE FEED characters are turned to spaces",
				"Confirm CARRIAGE RETURN characters are turned to spaces",
			),
		) );
	}

	Private Function getChar()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		 // --  If at end of current source line, read another line->
		 // --  If at end of file, set ch to the EOF character and return->
		 
		If ($this->bufferp == $this->sourceBufferLen) 
		{
			$this->ch = chr(self::EOFCHAR);
			return ($this->ch);
		}
		
		//* get the next character in the buffer
		$this->ch = substr ($this->sourceBuffer, $this->bufferp, 1);     
		$this->bufferp++;
		 
		If ($this->bufferp > $this->sourceBufferLen + 1) 
		{
			$this->ch = chr(self::EOFCHAR);
			return ($this->ch);
		}

		 //*
		 //--  White Space character processing
		 //--
		 //--      tab       replace ch with blank
		 //--      new-line    Replace ch with a blank.
		 //--      carriage-return    Replace ch with a blank.
		 //--
		 
		Switch ($this->ch)
		{
			Case Chr(9): // Tab
				$this->ch = " ";
			
			Case Chr(10): // Line Feed
				$this->ch = " ";

			Case Chr(13): // Carriage Return
				$this->ch = " ";
			
			Default:
		 }

		 return ($this->ch);
	}


	// ============================================

	public static function docFoo_skipBlanks ()
	{ docFoo ( Array 
		(
		"Method" => "skipBlanks",
		"Syntax" => "skipBlanks ()",
		"Description" => 
			"Skip all white space characters found in the Buffer, until a non-white space character 
			is found, or the EOF is reached",
		"Core Code" => "Get the next character until it is no longer a white space character or EOF",
		"Tests" => Array
			(
				"Confirm that preceeding white space characters are ignored",
				"Confirm that inline white space characters are ignored",
				"Confirm that ending white space characters are ignored",
			),
		) );
	}

	Private Function skipBlanks()
	{
		While ($this->ch == " ")
			$this->getChar();
	}

	// ============================================

	public static function docFoo_getToken ()
	{ docFoo ( Array 
		(
		"Method" => "getToken",
		"Syntax" => "getToken ()",
		"Description" => "Extract the next token from the buffer",
		"Core Code" => 
			"
				Reset the global token
				Skip all the blanks in the buffer
				If EOF is found, return EOF
				If the character found is a word, number, quote or special character
					process them respectively
				Add the Token Code to the Byte Code array
			",
		"Returns" => "Token Code",
		"Tests" => Array
			(
				"Confirm the tokens returned match the buffer string",
				"Confirm that if a previous request for a token found EOF that it returns EOF from that point on",
			),
		) );
	}

	Public Function getToken()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		// $this->token->code = TokenCodes::tcNOTOKEN;
		$this->token->value = "";
		$this->wordstring = "";
		$this->skipBlanks();
		
		If ($this->token->code == TokenCodes::tcEOF) 
			return;
		
		Switch ($this->CharCode($this->ch))
		{
			Case CharCode::cLETTER: $this->getWord(); break;
			Case CharCode::cDIGIT:    $this->getNumber(); break;
			Case CharCode::cQUOTE:  $this->getString($this->ch); break;
			Case CharCode::cEOFCODE:  $this->token->code = TokenCodes::tcEOF; break;
			Default:  $this->getSpecial();
		}

		$this->objByteCode->AddItem ($this->token->code);
		
		return ($this->token);
	}

	// ============================================

	public static function docFoo_getWord ()
	{ docFoo ( Array 
		(
		"Method" => "getWord",
		"Syntax" => "getWord ()",
		"Description" => "Retrieve a word from buffer",
		"Globals" => Array 
			(
				"charcode(s) - ",
				"TokenCode(s) - ",
			),
		"Core Code" =>
			"
				Accumulate the characters from the buffer as long as its a letter or digit.
				Set the token to IDENTIFIER if its a reserved word.
			",
		"Testing" => Array 
			(
				"Confirm that a coma or space seperated string returns x words",
				"Confirm reserved words are properly identified",
				"Confirm reserved words are case insensitive", 
				"Confirm non-reserved words maintain case", 
			),
		) );
	}

	Private Function getWord()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szWord = "";
		
		While 
		(
			($this->charcode($this->ch) == CharCode::cLETTER) || 
			($this->charcode($this->ch) == CharCode::cDIGIT)
		)
		{
			$szWord .= $this->ch;
			$this->getChar();
		}
		
		$this->token->value = $szWord;
		$szWord = strtolower ($szWord);

		If (! $this->isReservedWord ($szWord)) 
			$this->token->code = TokenCodes::tcIDENTIFIER;
	}

	// ============================================

	public static function docFoo_getNumber ()
	{ docFoo ( Array 
		(
		"Method" => "getNumber",
		"Syntax" => "getNumber ()",
		"Globals" => Array 
			(
				"token - ",
				"litteral - ",
				"TokenCodes - ",
				"MAXEXPONENT - "
			),
		"Description" => "parse a number from the buffer",
		"Validations" => Array (),
		"Core Code" => 
		"
			Extract the number from the buffer using the following format 
				number := [ d | number [d] | number (e [+ | - ] number) ]. 
			Store it in the token. 
			Set the literal value to NUMBER
		",
		"Errors" => Array 
			(
				"",
			),
		"Testing" => Array 
			(
				"Confirm alphanumeric characters cause an error",
				"Confirm numeric pattern [ x ]",
				"Confirm numeric pattern [ x. ]",
				"Confirm numeric pattern [ x.x ]",
				"Confirm numeric pattern [ x.e+x ]",
				"Confirm numeric pattern [ x.e-x ]",
				"Confirm the errors are thrown",
			),
		) );
	}

	Private Function getNumber()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$wholecount = 0;     //* no-> digits in whole part
		$decimaloffset = 0;     //* no-> digits to move decimal
		$exponentsign = 0;
		$exponent  = 0;       //* value of exponent
		$nValue = 0; // As Double    //* value of number
		$evalue = 0; // As Double   //* value of exponent
		$sawdotdot = False; //* TRUE if encounter ->

		$digitcount = 0;
		$counterror = False;
		$exponentsign = "+";

		$this->token->code = TokenCodes::tcNOTOKEN;

		 //--  Extract the whole part of the number by accumulating
		 //--  the values of its digits into nValue->  wholecount keeps
		 //--  track of the number of digits in this part->
		 
		$nValue = $this->accumulatevalue (ErrorMsgs::errorMsg005, 5);
		If ($this->token->code == TokenCodes::tcERROR) 
			return;
		
		$wholecount = strlen ($nValue);

		//--  If the current character is a dot, { either we have a
		//--  fraction part or we are seeing the first character of a .
		//--  $this->token.  To find out, we must fetch the next character.
		 
		If ($this->ch == ".") 
		{
			$this->getChar();

			$this->token->value .=  ".";

			//--  We have a fraction part->  Accumulate it into nValue->
			//--  decimaloffset keeps track of how many digits to move
			//--  the decimal point back->
			 
			$nValue = $this->accumulateValue(ErrorMsgs::errorMsg006, 6);
			If ($this->token->code == TokenCodes::tcERROR) 
				return;
				
			$decimaloffset = strlen ($nValue);
		}

		 // --  Extract the exponent part, if any-> There cannot be an
		 // --  exponent part if the ->-> token has been seen->
		 
		If (($this->ch == "E") || ($this->ch == "e")) 
		{
			$this->token->value .= $this->ch;
			$this->getChar();

			// --  Fetch the exponent//s sign, if any->
		
			If (($this->ch == "+") || ($this->ch == "-")) 
			{
				$this->token->value .= $this->ch;
				$exponentsign = $this->ch;
				$this->getChar();
			}

			// --  Extract the exponent->  Accumulate it into evalue->
		
			$evalue = $this->accumulateValue(ErrorMsgs::errorMsg007, 7);
			If ($this->token->code == TokenCodes::tcERROR) 
				return;
			
			If ($exponentsign == "-") 
				$evalue = -$evalue;		
		}

		 // --  Adjust the number//s value using
		 // --  decimaloffset and the exponent->
		 
		 $exponent = $evalue + $decimaloffset;
		 If 
		 (
			($exponent + $wholecount < -self::MAXEXPONENT) || 
			($exponent + $wholecount > self::MAXEXPONENT)
		) 
		{
			$this->token->code = TokenCodes::tcERROR;
			gblError()->init(ErrorMsgs::errorMsg009, 9, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			return;
		 }
		 
		 If ($exponent != 0) 
			$nValue = $nValue * 10 ^ $exponent;

		If 
		(
			($nValue < -self::MAXINTEGER) || 
			($nValue > self::MAXINTEGER)
		) 
		{
			gblError()->init(ErrorMsgs::errorMsg010, 10, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			$this->token->code = TokenCodes::tcERROR;
			return;
		}
		
		 $this->token->code = TokenCodes::tcNUMBER;
	}


	// ============================================

	public static function docFoo_getString ()
	{ docFoo ( Array 
		(
		"Method" => "getString",
		"Syntax" => "getString (szEndChar)",
		"Globals" => Array
			(
				"ch - ",
				"Literal - ",
				"Token - ",
			),
		"Parameters" => Array 
			(
				"szEndChar - The quotation character to find that ends the string"
			),
		"Description" => 
			"Extract a string to Token.Value.  Note that the quotes are stored as part of 
				this->token->value but not literal->value->string.",
		"Core Code" => 
			"Accumulate the characters in the string until the END QUOTE character or EOF is found.",
		"Testing" => Array
			(
				"Confirm that the string end character is found",
				"Confirm that an EOF terminates the function",
			)
		) );
	}

	Private Function getString ($szEndChar)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		 
		 $sp = "";

		 $this->token->value = $szEndChar;
		 $this->getChar();

		$bRun = True;
		While ((ord($this->ch) != self::EOFCHAR) && ($bRun))
		{
			$bRun = ($this->ch != $szEndChar);
			$this->token->value .= $this->ch;
			$this->getChar();
		}

		$this->token->code = TokenCodes::tcSTRING;
	}

	// ============================================

	public static function docFoo_getSpecial ()
	{ docFoo ( Array 
		(
		"Method" => "getSpecial",
		"Syntax" => "getSpecial ()",
		"Globals" => Array 
			(
				"TokenCode(s) - Codes used to set the Token.code value",
				"Token - ",
			),
		"Description" => 
			"Extract a special character.  Most are single-character, however, a few are 
				double-characters",
		"Validations" => Array 
			(
				"If the special character is not recognized, then error - Unrecognized special character",
			),
		"Core Code" => 
			" identify the special character and set the Token.Code to the appropriate
					TokenCode.  If the special character is a double-character, then get the
					next character from the buffer and determine what Token.Code this
					should be set to.
			",
		"Comments" =>
			" most special characters are used in single and double character combos for
				math and syntax strings.  Such as ( < = > ).  These can be either 
				< << <= = == === => >> >.  
			",
		"Errors" => Array 
			(
				"Unrecognized special character",
			),
		"Testing" => Array 
			(
				"Confirm special characters are recognized",
				"Confirm non-special characters throw errors",
			),
		) );
	}

	Private Function getSpecial()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$this->token->value = $this->ch;
		
		Switch ($this->ch)
		{
			Case "^":  $this->token->code = TokenCodes::tcUPARROW;    $this->getChar(); break;
			Case "*":  $this->token->code = TokenCodes::tcASTERICK;       $this->getChar(); break;
			Case "(":  $this->token->code = TokenCodes::tcLPAREN;    $this->getChar(); break;
			Case ")":  $this->token->code = TokenCodes::tcRPAREN;     $this->getChar(); break;
			Case "-":  $this->token->code = TokenCodes::tcMINUS;      $this->getChar(); break;
			Case "+": $this->token->code = TokenCodes::tcPLUS;      $this->getChar(); break;
			Case "=": $this->token->code = TokenCodes::tcEQUAL;      $this->getChar(); break;
			Case "[":  $this->token->code = TokenCodes::tcLBRACKET; $this->getChar(); break;
			Case "]":  $this->token->code = TokenCodes::tcRBRACKET; $this->getChar(); break;
			Case "{":  $this->token->code = TokenCodes::tcLBRACE; $this->getChar(); break;
			Case "}":  $this->token->code = TokenCodes::tcRBRACE; $this->getChar(); break;
			Case ";":  $this->token->code = TokenCodes::tcSEMICOLON;  $this->getChar(); break;
			Case ",":  $this->token->code = TokenCodes::tcCOMMA;     $this->getChar(); break;
			Case "/":  $this->token->code = TokenCodes::tcSLASH;      $this->getChar(); break;
			Case ".":  $this->token->code = TokenCodes::tcPERIOD;        $this->getChar(); break;
			Case "!":  $this->token->code = TokenCodes::tcEXCLAMATION;        $this->getChar(); break;
			Case "@":  $this->token->code = TokenCodes::tcATSIGN;        $this->getChar(); break;

			Case "$":  $this->token->code = TokenCodes::tcDOLLARSIGN;        $this->getChar(); break;
			Case "%":  $this->token->code = TokenCodes::tcPERCENTSIGN;        $this->getChar(); break;
			Case "^":  $this->token->code = TokenCodes::tcCARROT;        $this->getChar(); break;
			Case "&":  $this->token->code = TokenCodes::tcAMPERSAND;        $this->getChar(); break;

			Case "`":  $this->token->code = TokenCodes::tcQUOTE2;        $this->getChar(); break;
			Case "~":  $this->token->code = TokenCodes::tcTILDEE;        $this->getChar(); break;
			Case ":":  $this->token->code = TokenCodes::tcCOLON;        $this->getChar(); break;
			Case "\\":  $this->token->code = TokenCodes::tcBACKSLASH;        $this->getChar(); break;
			Case "/":  $this->token->code = TokenCodes::tcSLASH;        $this->getChar(); break;
			Case "?":  $this->token->code = TokenCodes::tcQUESTIONMARK;        $this->getChar(); break;
			
			Case "<":
				$this->getChar();        //* < or <= or !=
				If ($this->ch == "=") 
				{
					$this->token->value .= "=";
					$this->token->code = TokenCodes::tcLE;
					$this->getChar();
				} 
				Else If ($this->ch == ">") 
				{
					$this->token->value .= ">";
					$this->token->code = TokenCodes::tcNE;
					$this->getChar();
				} Else 
				{ $this->token->code = TokenCodes::tcLT; }
				break;

			Case ">":
				$this->getChar();         //* > or >=
				If ($this->ch == "=") 
				{
					$this->token->value .= "=";
					$this->token->code = TokenCodes::tcGE;
					$this->getChar();
				} 
				Else 
				{ $this->token->code = TokenCodes::tcGT; }
				break;

			Default:
				$this->token->code = TokenCodes::tcERROR;
				gblError()->init(ErrorMsgs::errorMsg011, 11, "", __METHOD__, "");
				If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
				$this->getChar();
		 }

	}

	// ============================================

	public static function docFoo_accumulateValue ()
	{ docFoo ( Array 
		(
		"Method" => "accumulateValue",
		"Syntax" => "accumulateValue ()",
		"Globals" => Array
			(
				"CharCode::cDIGIT - the character is a digit",
				"MAXDIGITCOUNT - the max number of digits a number can be",
				"Token - ",
			),
		"Description" => 
			"Extract a number part and accumulate its value.  Flag the error if the first character 
			is not a digit.",
		"Validations" => Array
			(
				"if 1st character retrieved from buffer is a non-digit, 
					then throw error - Non-Digit Character Found",
			),
		"Core Code" => "Retrieve characters from the buffer until a non-digit character is found.",
		"Returns" => "INTEGER",
		"Errors" => Array
			(
				"Non-Digit Character Found",
			),
		"Testing" => Array
			(
				"Confirm that a string starting with a non-digit value throws an error",
				"Confirm an alphanumeric string stops accumulating digits at the first non-digit character",
				"Confirm an all digit string throws no error",
			),
		) );
	}


	Private Function accumulateValue ($thsMsg, $thsError)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		 
		$value = "";
		 
		 //--  Error if the first character is not a digit.
		 
		 //	While this is a sanity check, accumulate is only called
		 //	through the getNumber function, thus CH must be a
		 //	digit to be called.
		 
		 If ($this->charcode($this->ch) != CharCode::cDIGIT) 
		 {
			gblError()->init($thsMsg, $thsError, "", __METHOD__, "");
			If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
			$this->token->code = TokenCodes::tcERROR;
			return (0);
		 }

		 //--  Accumulate the value as long as the total allowable
		 //--  number of digits has not been exceeded.
		 
		 $countError = false;
		 $digitcount = 0;
		 
		 Do
		 {
			$this->token->value .= $this->ch;
			$digitcount ++;
			
			If ($digitcount <= self::MAXDIGITCOUNT) 
			{	$value .= $this->ch; } 
			Else 
			{ 
				gblError()->init(ErrorMsgs::errorMsg008, 8, "", __METHOD__, "");
				If (bDebugging) gblTraceLog()->ErrorMsg (gblError());
				$this->token->code = TokenCodes::tcERROR;
				return (0);
			}

			$this->getChar();
			
		}	While ($this->charcode($this->ch) == CharCode::cDIGIT);
		
		return ($value);
	}


	// ============================================

	public static function docFoo_isReservedWord ()
	{ docFoo ( Array 
		(
		"Method" => "isReservedWord",
		"Syntax" => "isReservedWord ( szWord )",
		"Parameters" => Array
			(
				"szWord - "
			),
		"Description" => "Determine if the word passed in is a reserved word",
		"Core Code" => 
			"
				If the word passed in is a reserved word, then
					update the token with the reserved word's byte code value
			",
		"Returns" => "TRUE | FALSE",
		"Testing" => Array
			(
				"Confirm that a reserved word is found",
				"Confirm that a reserved word is not found",
				"Confirm that the Token is updated correctly",
			),
		) );
	}

	Private Function isReservedWord ($szWord)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__ . "( " . $szWord . ")");

		// =============================
		// Core Code
		 
		$objWord = $this->objRWords->Find ($szWord);
		
		If (gblError()->No() != 0) 
		{
			gblError()->Addpath (__METHOD__);
			return (false);
		}
			
		If (null == $objWord) 
			return (false);	

		$this->token->code = $objWord->Token();
		return (true);
		
	}
}

Scanner::docFoo();
?>
