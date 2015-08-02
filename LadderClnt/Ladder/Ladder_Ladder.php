<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

include_once ("rpc.php");
include_once ("functions.php");
include_once ("Ladder_Globals.php");
include_once ("Ladder_Properties.php");
include_once ("Ladder_Links.php");
include_once ("Ladder_Folders.php");
include_once ("Ladder_Folder.php");
include_once ("Ladder_Item.php");
include_once ("Ladder_Reference.php");
include_once ("Ladder_Classes.php");

class ENetArch_Ladder
{
	public $cn = null;
	public $bVerbose = false;
	private $szSessionID = "";
	
	function connect ($szURL, $szSerNo, $szUID, $szPSW) 
	{
		// log into Ladder Server
		$this->cn = new rpc ($szURL);
		
		if ($this->isInstalled() )
			$fldrClasses = $this->getClasses();
	}
		
	function disconnect () 
	{
		// drop the session from the Ladder Server
		$this->cn = null;
	}
	
	// =================================================================
	
	function Version () 
	{
		// confirm that ladder is installed on this server
		$data = array
		(
			"szCommand" => "cmdLadder.Version", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ()
		);
																		
		return ($this->cn->exec ($data));		
	}
	
	// =================================================================

	function isInstalled () 
	{
		// confirm that ladder is installed on this server
		$data = array
		(
			"szCommand" => "cmdLadder.isInstalled", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ()
		);

		return ( $this->cn->exec ($data) );		
	}
	
	function Install () 
	{
		// install Ladder on this server
		$data = array
		(
			"szCommand" => "cmdLadder.Install", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ()
		);
																		
		return ($this->cn->exec ($data));		
	}

	function unInstall () 
	{
		// unInstall Ladder from this server
		$data = array
		(
			"szCommand" => "cmdLadder.unInstall", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ()
		);
																		
		return ($this->cn->exec ($data));		
	}
	
	function getItem ($szGUID)
	{
		$data = array
		(
			"szCommand" => "cmdLadder.getItem", 
			"bVerbose" => true, 
			"szParams" => Array ($szGUID)
		);
																		
		$thsState = $this->cn->exec ($data);
		
		$objItem = null;

		if ($thsState != null) 
		{
			switch ($thsState [5])
			{
				case ENetArch_Ladder_Globals::cisRoot() :
				{
					$objItem = new ENetArch_Ladder_Folder ();
					break;
				}

				case ENetArch_Ladder_Globals::cisFolder() :
				{
					$objItem = new ENetArch_Ladder_Folder ();
					break;
				}

				case ENetArch_Ladder_Globals::cisItem() :
				{
					$objItem = new ENetArch_Ladder_Item ();
					break;
				}

				case ENetArch_Ladder_Globals::cisReference() :
				{
					$objItem = new ENetArch_Ladder_Reference ();
					break;
				}
			}

			if ($objItem != null)
			{
				$objItem->Connect ($this->cn);
				$objItem->setState ($thsState);
			}
		}

		return ($objItem);
	}

	Function getObject ($nID, $thsObject)
	{
		$data = array
		(
			"szCommand" => "cmdLadder.getItem", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ($nID)
		);
		
		$thsState = $this->cn->exec ($data);
		
		$thsObject->Connect ($this->cn);
		$thsObject->setState ($thsState);
		
		return ($thsObject);
	}

	function getRoots ()
	{
		$data = array
		(
			"szCommand" => "cmdLadder.getRoots", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ()
		);
																		
		$thsState = $this->cn->exec ($data);
		$newFolder = new ENetarch_Ladder_Folders ($this->cn, $thsState);
		
		return ($newFolder);	
	}
	
	Function Create_Root ($szName, $szDesc, $nClass)
	{
		$data = array
		(
			"szCommand" => "cmdLadder.Create_Root", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ($szName, $szDesc, $nClass)
		);

		$thsState = $this->cn->exec ($data);
		$newFolder = new ENetArch_Ladder_Folder ($this->cn, $thsState);

		return ($newFolder);
	}	
	
	Function Root_Exists ($thsName, $nClass = 0)
	{
		$data = array
		(
			"szCommand" => "cmdLadder.Root_Exists", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ($thsName, $nClass = 0)
		);
																		
		return ($this->cn->exec ($data));		
	}
	
	// ===================================

	Function getClasses ()
	{
		$data = array
		(
			"szCommand" => "cmdLadder.getClasses", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ()
		);

		$thsState = $this->cn->exec ($data);
		$newFolder = new ENetArch_Ladder_Folder ();
		$newFolder->connect ($this->cn);
		$newFolder->setState ($thsState);

		return ($newFolder);
	}
	
	// ===================================

	Function create_Class ($thsName, $nBaseType, $ofClass, $bAcceptsAll, $szStr="")
	{
		$data = array
		(
			"szCommand" => "cmdLadder.create_Class", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ($thsName, $nBaseType, $ofClass, $bAcceptsAll, $szStr)
		);

		$thsState = $this->cn->exec ($data);
		$newFolder = new ENetArch_Ladder_Folder ($this->cn, $thsState);

		return ($newFolder);		
	}

	// ===================================
	
	Function getClass ($thsName = "", $nClassID = 0)
	{
		$data = array
		(
			"szCommand" => "cmdLadder.getClass", 
			"bVerbose" => $this->bVerbose, 
			"szParams" => Array ( $thsName, $nClassID )
		);
		
		$thsState = $this->cn->exec ($data);
		$newFolder = new ENetArch_Ladder_Folder ($this->cn, $thsState);
		
		return ($newFolder);		
	}	
}
?>
