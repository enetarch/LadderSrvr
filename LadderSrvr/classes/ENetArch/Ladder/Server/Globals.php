<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace ENetArch\Ladder\Server;

Class Globals
{
	static function cName_Length () { return (40);}
	static function cDescription_Length () { return ( 250);}

	static function cisRoot () { return ( 1);}
	static function cisFolder () { return ( 2);}
	static function cisItem () { return ( 3);}
	static function cisReference () { return ( 4);}

	static function cOrderBy_ID () { return ( 0);}
	static function cOrderBy_GUID () { return ( 1);}
	static function cOrderBy_Name () { return ( 2);}
	static function cOrderBy_Description () { return ( 3);}
	static function cOrderBy_Size () { return ( 4);}
	static function cOrderBy_Date () { return ( 5);}
	static function cOrderBy_BaseType () { return ( 6);}
	static function cOrderBy_Class () { return ( 7);}

	static function cLadder_Table_Links () { return ( "Ladder_Links");}
	static function cLadder_Table_Defination () { return ( "Ladder_Defination");}

	// ==================================
	// Classes

	static function cClass_Ladder_Defination () { return ("Ladder_Defination"); }
	static function cClass_Ladder_Folder () { return ("Ladder_Folder"); }
	static function cClass_Ladder_Class () { return ("Ladder_Class"); }
	static function cClass_RootFolder () { return ("RootFolder"); }
	static function cClass_RootClasses () { return ("RootClasses"); }

	// ==================================
	// Versioning
}

?>
