<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Ladder\OSQL\Parser;

//***************************************************************
//*
//*      T O K E N S
//*
//***************************************************************

DocFoo (Array (
	"Class" => "TokenCodes",
	"Description" => "A list of tokens that the scanner is searching for.",
));

class TokenCodes extends \ENetArch\Ladder\OSQL\Scanner\TokenCodes
{
	const tcAND = 40;
	const tcOR = 41;
	const tcLIKE = 42;
	const tcNOT = 43;
	const tcNULL = 44;

	const tcCREATE = 45;
	const tcCLASS = 46;
	const tcCLASSES = 47;
	const tcSUBCLASS = 48;
	const tcWITH = 49;
	const tcALLOW = 50;
	const tcALL = 51;
	const tcDISTINCT = 52;
		
	const tcDROP = 53;

	const tcSELECT = 54;
	const tcINSTANCES = 55;
	const tcFIELDS = 56;
	const tcTEMPLATES = 57;
	const tcSTRUCTURE = 58;
		
	const tcUSING = 59;
	const tcFROM = 60;
	const tcWHERE = 61;

	const tcORDER = 62;
	const tcBY = 63;
	const tcASC = 64;
	const tcDESC = 65;
	const tcDEPTH = 66;

	const tcINSERT = 67;
	const tcINTO = 68;
	const tcVALUES = 69;

	const tcDELETE = 70;

	const tcUPDATE = 71;
	const tcSET = 72;
		
	const tcALTER = 73;
		
	const tcCONTAINS = 74;
	const tcDOES = 75;
	const tcCONTAIN = 76;
	const tcID = 77;
	const tcNAME = 78;
	const tcDESCRIPTION = 79;
	const tcPARENT = 80;
	const tcTYPE = 81;
	const tcATTRIBUTES = 82;
		
	const tcIS = 83;
	const tcIN = 84;
	const tcOF = 85;
	const tcFOLDER = 86;
	const tcITEM = 87;
	const tcROOT = 88;
	const tcBETWEEN = 89;
	const tcESCAPE = 90;
		
	const tcEXISTS = 91;
	const tcCOUNT = 92;
	const tcAVG = 93;
	const tcMAX = 94;
	const tcMIN = 95;
	const tcSUM = 96;
		
	const tcANY = 97;
	const tcSOME = 98;
	const tcADD = 99;
		
	const tcTEMPLATE = 100;
		
	const tcTRUE = 101;
	const tcFALSE = 102;

	const tcMOVE = 103;
	const tcCOPY = 104;
	const tcDUPLICATE = 105;
	const tcLINK = 106;
	const tcTO = 107;

	const tcISROOT = 108;
	const tcISINSTALLED = 109;
	const tcINSTALL = 110;
	const tcUNINSTALL = 111;
	const tcLADDER = 112;
	const tcRESULTS = 113;

	const tcDateCreated = 114;
	const tcDateAccessed = 115;
	const tcDateModified = 116;

	const tcUIDCreated = 117;
	const tcUIDAccessed = 118;
	const tcUIDModified = 119;

	const tcIPCreated = 120;
	const tcIPAccessed = 121;
	const tcIPModified = 122;

	const tcFUNCTION = 123;
}
?>
