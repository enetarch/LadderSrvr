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


docFoo 
(Array 
(
	"FileName" => "TokenCodes",
	"Description" => "A list of Tokens that the scanner is searching for.",
));


//*--------------------------------------------------------------
//*  Token codes
//*--------------------------------------------------------------

class TokenCodes
{
	const tcEOF = 1;
	const tcERROR = 2;
	const tcNOTOKEN = 3;
	
	const tcIDENTIFIER = 4;
	const tcNUMBER = 5;
	const tcSTRING = 6;
		
	const tcUPARROW = 7;
	const tcASTERICK = 8;
	const tcLPAREN = 9;
	const tcRPAREN = 10;
	const tcMINUS = 11;
	const tcPLUS = 12;
	const tcEQUAL = 13;
	const tcLBRACKET = 14;
	const tcRBRACKET = 15;
	const tcLBRACE = 16;
	const tcRBRACE = 17;
	const tcCOLON = 18;
	const tcCOMMA = 19;
	const tcLT = 20;
	const tcGT = 21;
	const tcPERIOD = 22;
	const tcSLASH = 23;
	const tcLE = 24;
	const tcGE = 25;
	const tcNE = 26;
	const tcSEMICOLON = 27;
	
	const tcEXCLAMATION = 28;
	const tcATSIGN = 29;
	const tcDOLLARSIGN = 30;
	const tcPERCENTSIGN = 31;
	const tcCARROT = 32;
	const tcAMPERSAND = 33;
	
	const tcQUOTE2 = 34;
	const tcTILDEE = 35;
	const tcBACKSLASH = 36;
	const tcQUESTIONMARK = 37;
	
}


?>
