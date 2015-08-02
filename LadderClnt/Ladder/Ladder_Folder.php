<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Class ENetArch_Ladder_Folder extends ENetArch_Ladder_Properties
{
	Function Create ($thsParent = null, $thsName, $thsDescription, $thsClass)
	{
		if ($thsParent == null) 
		{
			parent::Create ($thsName, $thsDescription, 0, $thsClass, ENetArch_Ladder_Globals::cisRoot());
		}
		else
		{
			parent::Create ($thsName, $thsDescription, $thsParent->GUID(), $thsClass, ENetArch_Ladder_Globals::cisFolder());
		}
	}
	
	// ===================================================
	
	Function Delete ()
	{
		$data = array
		(
			"szCommand" => "cmdFolder.Delete", 
			"bVerbose" => true, 
			"szParams" => Array ($this->ID())
		);

		return ( $this->cn->exec ($data) );
	}
	
	// ===================================================
	
	Function Count ($szName ="", $aryClasses=Array(), $nBaseType=0) // Array
	{
		// confirm that ladder is installed on this server
		$data = array
		(
			"szCommand" => "cmdFolder.Count", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID(), $szName, $aryClasses, $nBaseType)
		);
																		
		return ($this->cn->exec ($data));		
	}

	// ===================================================

	Function Item ($nPos, $aryClasses=Array(), $nBaseType=0, $thsObject=null) // as Object
	{
		// print("Ladder_Folder . Item ( $nPos , $aryClasses, $nBaseType, " . ($thsObject != null)*1 . " )<BR>");
		
		$data = array
		(
			"szCommand" => "cmdFolder.Item", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID(), $nPos, $aryClasses, $nBaseType)
		);
																		
		$row = $this->cn->exec ($data);
		
		$objItem = null;
		
		switch ($row [5])
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
			$objItem->setState ($row);
		}

		return ($objItem);
	}
	
	// ===================================================
	
	Function getChildren ($szName ="", $aryClasses=Array(), $nBaseType=0, $nOrderBy=0, $bASC=true) // Array
	{
		$data = array
		(
			"szCommand" => "cmdFolder.getChildren", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID(), $szName, $aryClasses, $nBaseType, $nOrderBy, $bASC)
		);

		$rows = $this->cn->exec ($data);
		
		$objLinks = new ENetArch_Ladder_Links ($this->cn, $rows);

		return ($objLinks);			
	}

	// ===================================================

	Function Exists ($szName ="", $aryClasses=Array(), $nBaseType=0)
	{
		$data = array
		(
			"szCommand" => "cmdFolder.Exists", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID(), $szName, $aryClasses, $nBaseType)
		);

		return ( $this->cn->exec ($data) );
	}
	
	Function SubFolders ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
	{
		$data = array
		(
			"szCommand" => "cmdFolder.SubFolders", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID(), $szName, $aryClasses, $nOrderBy, $bASC)
		);

		$rows = $this->cn->exec ($data);
		
		$objLinks = new ENetArch_Ladder_Links ($this->cn, $rows);

		return ($objLinks);			
	}
	
	Function Items ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
	{
		$data = array
		(
			"szCommand" => "cmdFolder.Items", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID(), $szName, $aryClasses, $nOrderBy, $bASC)
		);

		$rows = $this->cn->exec ($data);
		
		$objLinks = new ENetArch_Ladder_Links ($this->cn, $rows);

		return ($objLinks);			
	}
	
	Function References ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
	{
		$data = array
		(
			"szCommand" => "cmdFolder.References", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID(), $szName, $aryClasses, $nOrderBy, $bASC)
		);

		$rows = $this->cn->exec ($data);
		
		$objLinks = new ENetArch_Ladder_Links ($this->cn, $rows);

		return ($objLinks);			
	}

	// ===================================================

	Function getFolder ($szName ="", $aryClasses=null, $thsObject=null)
	{
		$data = array
		(
			"szCommand" => "cmdFolder.getFolder", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID(), $szName, $aryClasses, $nBaseType)
		);

		$row = $this->cn->exec ($data);

		$objItem = new ENetArch_Ladder_Folder ();
		$objItem->Connect ($this->cn);
		$objItem->setState ($row);

		return ($objItem);
	}
	
	Function getItem($szName ="", $aryClasses=null, $thsObject=null)
	{
		$data = array
		(
			"szCommand" => "cmdFolder.getFolder", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID(), $szName, $aryClasses, $nBaseType)
		);

		$row = $this->cn->exec ($data);

		$objItem = new ENetArch_Ladder_Folder ();
		$objItem->Connect ($this->cn);
		$objItem->setState ($row);

		return ($objItem);
	}
	
	Function getReference ($szName ="", $aryClasses=null, $thsObject=null)
	{
		$data = array
		(
			"szCommand" => "cmdFolder.getFolder", 
			"bVerbose" => true, 
			"szParams" => Array ($this->ID(), $szName, $aryClasses, $nBaseType)
		);

		$row = $this->cn->exec ($data);

		$objItem = new ENetArch_Ladder_Folder ();
		$objItem->Connect ($this->cn);
		$objItem->setState ($row);

		return ($objItem);
	}	

	// ===================================================
	
	Function Create_Folder ($szName, $szDescription, $thsClass, $thsObject=null)
	{
		if ($thsObject == null)
		{ $newItem = new ENetArch_Ladder_Folder (); }
		else
		{ $newItem = $thsObject; }
		
		$newItem->Connect ($this->cn);
		$newItem->Create ($this, $szName, $szDescription, $thsClass);
		
		return ($newItem);
	}
	
	Function Create_Item ($szName, $szDescription, $thsClass, $thsObject=null)
	{
		if ($thsObject == null)
		{ $newItem = new ENetArch_Ladder_Item (); }
		else
		{ $newItem = $thsObject; }
		
		$newItem->Connect ($this->cn);
		$newItem->Create ($this, $szName, $szDescription, $thsClass);
		
		return ($newItem);
	}

	Function Create_Item2 ($szName, $szDescription, $thsClass, $szTableName)
	{
		$newItem = new ENetArch_Ladder_Item ();
		$newItem->Connect ($this->cn);
		$newItem->Create2 ($this, $szName, $szDescription, $thsClass, $szTableName);
		
		return ($newItem);
	}

	Function Create_Reference ($szName, $szDescription, $thsClass, $thsFolder, $thsObject=null)
	{
		if ($thsObject == null)
		{ $newItem = new ENetArch_Ladder_Reference (); }
		else
		{ $newItem = $thsObject; }
		
		$newItem->Connect ($this->cn);
		$newItem->Create ($this, $szName, $szDescription, $thsClass, $thsFolder);
		
		return ($newItem);
	}

	// ===================================================
	// Change History
	
	/*
		2009-12-19 - added nOrderBy and bASC to SubFolders, Items, and References.
		
	*/
}

?>
