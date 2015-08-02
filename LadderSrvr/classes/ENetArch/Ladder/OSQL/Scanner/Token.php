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
( Array
(
	"Class" => "Token",
	
	"Description" => 
		"Token is used by the Parser when passing values ",

	"Requirements" => Array 
		(
			"Store byte codes for later retrieval", 
			"Store a string value for later retrieval", 
		),

	"Properties" => Array
		(
			" PRIVATE INTEGER Instances[] - stored bytecodes",
		),
		
	"Tests" => Array
		(
			"Confirm byte code stored matches later",
			"Confirm the value stored matches later",
		),
));



class Token 
{
	Public $code  = 0;    //* code of current token
	Public $value = ""; 	//* token string
	
	public function __construct ($code=0, $value="")
	{
		$this->code = $code;
		$this->value = $value;
	}
}

?>
