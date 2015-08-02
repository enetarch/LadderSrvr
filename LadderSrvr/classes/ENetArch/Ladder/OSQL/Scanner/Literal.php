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
use ENetArch\Ladder\OSQL\Scanner\LiteralType;

docFoo 
(Array 
(
	"Class" => "Literal",
	"Description" => "The value and type.",
	"Requirements" => Array 
		(
			"Provides a means to pass the literal value found to other parts of code",
		),
	"Properties" => Array
		(
			"type - the type of literal contained in the value",
			"value - the value found during the text",
		)
));


class Literal
{
   public $type = null;
   public $value = "";
   
   public function __construct ()
   { $this->type = new LiteralType (); }
}

?>
