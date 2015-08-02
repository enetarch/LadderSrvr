<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace ENetArch\Ladder\Server;

use \ENetArch\Ladder\Server\Globals;
use \ENetArch\Ladder\Server\Folder;
use \ENetArch\Ladder\Server\Item;
use \ENetArch\Ladder\Server\Reference;

class Links
{
	private $cn = null;
	private $aryLinks = null;

	function Connect ($cn) { $this->cn = $cn; }

	function setState ($rows) { $this->aryLinks = $rows; }
	function getState () { return ($this->aryLinks); }
	function getItems ()
	{
		$rtn = [];
		
		for ($t = 0; $t < count ($this->aryLinks); $t++)
			$rtn[] = $this->getItem ($t+1);
		
		return ($rtn);
	}

	function count ()
	{	return (count ($this->aryLinks) );	}

	function getItem ($nPos)
	{
		if (count ($this->aryLinks) < $nPos) 
			return (null);

		$row = $this->aryLinks [$nPos-1];
		$objItem = null;
		
		switch ($row [5])
		{
			case Globals::cisRoot() :
			{
				$objItem = new \ENetArch\Ladder\Server\Folder ();
				break;
			}

			case Globals::cisFolder() :
			{
				$objItem = new \ENetArch\Ladder\Server\Folder ();
				break;
			}

			case Globals::cisItem() :
			{
				$objItem = new \ENetArch\Ladder\Server\Item ();
				break;
			}

			case Globals::cisReference() :
			{
				$objItem = new \ENetArch\Ladder\Server\Reference ();
				break;
			}
		}

		if ($objItem != null)
		{
			$objItem->Connect ($this->cn);
			$objItem->setState ($row);
		}

		return ($objItem);
	}

	function getObject ($nPos, $thsObject)
	{

		if (count ($this->aryLinks) < $nPos) 
			return (null);
			
		$row = $this->aryLinks [$nPos-1];
			
		$thsObject->Connect ($this->cn);
		$thsObject->setState ($row);

		return ($thsObject);
	}

}

?>
