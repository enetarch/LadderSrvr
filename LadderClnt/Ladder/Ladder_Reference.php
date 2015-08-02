<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Class ENetArch_Ladder_Reference extends ENetArch_Ladder_Properties
{
	Function Create ($thsParent, $thsName, $thsDescription, $thsClass, $LinkTo)
	{
		if ($thsParent == null) return;
		if ($LinkTo == null) return;
		
		parent::Create ($thsName, $thsDescription, $thsParent->GUID(), $thsClass, ENetArch_Ladder_Globals::cisReference(), 0, $LinkTo->GUID());
	}
	
	Function getFolder ()
	{
		return (gblLadder()->getItem ($this->getReference()));
	}
	
	Function setFolder ($thsFolder)
	{
		gblLadder()->setReference ($thsFolder->GUID());
	}
}

	// ================================
	// Updates
	/*
		2009-10-01 	added setFolder
		
	*/
	
	
?>
