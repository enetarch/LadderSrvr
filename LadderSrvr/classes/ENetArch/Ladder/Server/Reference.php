<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace ENetArch\Ladder\Server;

use \ENetArch\Ladder\Server\Properties;

Class Reference extends Properties
{
	Function Create ($thsParent, $thsName, $thsDescription, $thsClass, $LinkTo)
	{
		if ($thsParent == null) return;
		if ($LinkTo == null) return;
		
		parent::Init ($thsName, $thsDescription, $thsParent->GUID(), $thsClass, Globals::cisReference(), "", $LinkTo->GUID());
	}
	
	Function getFolder ()
	{
		Global $gblLadder;
		
		return ($gblLadder->getItem ($this->nLeaf));
	}
	
	Function setFolder ($thsFolder)
	{
		Global $gblLadder;
		
		$gblLadder->setLeaf ($thsFolder->ID());
	}
}

	// ================================
	// Updates
	/*
		2009-10-01 	added setFolder
		
	*/
	
	
?>
