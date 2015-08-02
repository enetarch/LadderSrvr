<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace Commands;

class cmdItem // Factory Class
{
	static public function  Delete ($szParams)
	{
		$objItem = gblLadder()->getItem ($szParams[0]);
		return ( $objItem->Delete () );
	} 

	static public function  getData ($szParams)
	{
		$objItem = gblLadder()->getItem ($szParams[0]);
		return ( $objItem->getData () );
	} 

	static public function saveData ($szParams)
	{ 
		gblTraceLog()->Log ("cmdItem.saveData", $szParams);
		$rtn = gblLadder()->update_Item ($szParams [0], $szParams [1]) ;
		gblTraceLog()->Log ("cmdItem.rtn", $rtn);
		return ( $rtn ); 
	}
	
	static public function getStructure ($szParams)
	{
		$rtn = gblLadder()->getStructure ($szParams [0]) ;
		return ( $rtn ); 
	}
}
?>
