<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace Commands;

class cmdLadder // Factory Class
{
	// =================================
	
	static public function Version ($aryParams)	
	{ return (gblLadder()->Version ()->Version ()); }
	
	// =================================
	
	static public function isInstalled ($aryParams)	
	{ return ( gblLadder()->isInstalled () ); }
	
	static public function Install ($aryParams)	
	{ return ( gblLadder()->Install () ); }
	
	static public function unInstall ($aryParams)	
	{ return ( gblLadder()->unInstall () ); }
	
	// =================================
	
	static public function getRoots ($aryParams)	
	{ return ( gblLadder()->getRoots ()->getState() ); }
	
	static public function getRoot ($aryParams)	
	{ return ( gblLadder()->getRoot ($aryParams[0]) ); }

	// =================================
	
	static public function getPath ($aryParams)
	{ return ( gblLadder()->getPath ($aryParams[0]) ); }
	
	static public function getFolder  ($aryParams)	
	{ return ( gblLadder()->getFolder ($aryParams[0]) ); }
	
	static public function getItem ($aryParams)	
	{ 
		$itm = gblLadder()->getItem ($aryParams[0]);
		
		$rtn = null;
		if ($itm != null)
			$rtn = $itm->getState();
			
		return ( $rtn ); 
	} 
	
	static public function getReference ($aryParams)	
	{ return ( gblLadder()->getReference ($aryParams[0]) ); } 

	// =================================

	static public function create_Class  ($aryParams)
	{
		$fldrClass = gblLadder()->create_Class ($aryParams[0], $aryParams[1], $aryParams[2], $aryParams[3], $aryParams[4] );
		return ( $fldrClass->getState() );
	} 

	// ========================================

	static public function  getClasses ($aryParams)
	{
		$fldrClasses = gblLadder()->getClasses ();
		return ( $fldrClasses->getState() );
	} 

	static public function  getClass ($aryParams)
	{
		$fldrClass = gblLadder()->getClass ($aryParams[0], $aryParams[1]); 
		return ( $fldrClass->getState() );
	} 
}
?>
