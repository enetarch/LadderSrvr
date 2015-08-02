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

Class Folder extends Properties
{
	Function Create ($thsParent = null, $thsName, $thsDescription, $thsClass)
	{
		if ($thsParent == null) 
		{
			parent::Init ($thsName, $thsDescription, 0, $thsClass, Globals::cisRoot());
		}
		else
		{
			parent::Init ($thsName, $thsDescription, $thsParent->GUID(), $thsClass, Globals::cisFolder());
		}
	}
	
	// ===================================================
	
	Function Delete ()
	{
		$aryIDs = $this->getChildren ();
		if ($aryIDs != null);
		{
			$objItem = null;
			For ($t = 1; $t< $aryIDs->Count()+1; $t++)
			{
				$objItem = $aryIDs->getItem ($t);
				if ($objItem != Null)
					$objItem->Delete ();
			}
		}
		
		$szSQL = 
			" DELETE " . 
			" FROM " . parent::cTableName() . 
			" WHERE " . 
			 	" GUID = '" . $this->GUID() . "' ";
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.trace", debug_backtrace ());
			return;
		}
			
		return (true);
	}
	
	// ===================================================
	
	Function Count ($szName ="", $aryClasses=Array(), $nBaseType=0) // Array
	{
		$szSQL = 
			" SELECT COUNT(*) " .
			" FROM " . parent::cTableName() . 
			" WHERE " . 
				" gdParent = '" . $this->GUID() . "' ";

		if (strLen (trim ($szName)) != 0) 
			$szSQL .= " AND szName = '" . $szName . "' ";
		if (count ($aryClasses) != 0) 
			$szSQL .= " AND szClass IN ( '" . implode ("'. '", $aryClasses) . "' ) ";
		if ($nBaseType != 0) 
			$szSQL .= " AND nBaseType = " . $nBaseType . " ";
			
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$stmt = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.trace", debug_backtrace ());
			return;
		}
		
		$rs = $stmt->fetchAll();
		$rowCount = count ($rs);
		
      $row = $rs[0];
      $nCount = $row["0"];

      $rs = null;
      
      return ($nCount);			
	}

	// ===================================================

	Function Item ($nPos, $aryClasses=Array(), $nBaseType=0, $thsObject=null) // as Object
	{
		if ($nPos < 1) return (null);

		$aryChildren = $this->getChildren ("", $aryClasses, $nBaseType);
		
		if ($nPos > $aryChildren->count ()) return (null);
		
		if ($thsObject == null)
		{ $objItem = $aryChildren->getItem ($nPos); }
		else 
		{ $objItem = $aryChildren->getObject ($nPos); }
		
		return ($objItem);
	}
	
	// ===================================================
	
	Function getChildren ($szName ="", $aryClasses=Array(), $nBaseType=0, $nOrderBy=0, $bASC=true) // Array
	{
		gblTraceLog()->Log ("Ladder/Server/Folder.getChildren", "entered");
		
		$szSQL = 
			" SELECT * " .
			" FROM " . parent::cTableName() . 
			" WHERE " . 
				" gdParent = '" . $this->GUID() . "' ";

		if (strLen (trim ($szName)) != 0) 
			$szSQL .= " AND szName = '" . $szName . "' ";
		if (count ($aryClasses) != 0) 
			$szSQL .= " AND szClass IN ( '" . implode ("'. '", $aryClasses) . "' ) ";
		if ($nBaseType != 0) 
			$szSQL .= " AND nBaseType = " . $nBaseType . " ";

		$szSQL .= 	" ORDER BY ";
		switch ($nOrderBy)
		{ 
			case Globals::cOrderBy_ID() : $szSQL .= 	" ID "; break;
			case Globals::cOrderBy_GUID() : $szSQL .= 	" GUIID "; break;
			case Globals::cOrderBy_Name() : $szSQL .= 	" szName "; break;
			case Globals::cOrderBy_Description() : $szSQL .= 	" szDescription "; break;
			case Globals::cOrderBy_Class() : $szSQL .= 	" szClass "; break;
			case Globals::cOrderBy_Date() : $szSQL .= 	" dCreated "; break;
			case Globals::cOrderBy_BaseType() : $szSQL .= 	" nBaseType "; break;
			default : $szSQL .= 	" ID "; break;
		}
		
		$szSQL .= 	(($bASC) ? " ASC " : " DESC ");
		
		gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.szSQL", $szSQL);
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.trace", debug_backtrace ());
			return (null);
		}

		$rowCount = count ($rs);
		gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.rowCount", $rowCount);
		
		$aryIDs = Array ();
		ForEach ($rs AS $key => $row)
			$aryIDs[] = $row;

		$rs = null;
      
		$objLinks = new \ENetArch\Ladder\Server\Links ();
		$objLinks->connect ($this->cn);
		$objLinks->setState ($aryIDs);
		
		return ($objLinks);			
	}

	// ===================================================

	Function Exists ($szName ="", $aryClasses=Array(), $nBaseType=0)
	{
		$nCount = $this->Count ($szName, $aryClasses);
		return ($nCount > 0);
	}
	
	Function SubFolders ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
	{
		$aryIDs = $this->getChildren ($szName, $aryClasses, Globals::cisFolder(), $nOrderBy, $bASC);
		return ($aryIDs);
	}
	
	Function Items ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
	{
		gblTraceLog()->Log ("Ladder/Server/Folder.Items", "entered");
		$aryIDs = $this->getChildren ($szName, $aryClasses, Globals::cisItem(), $nOrderBy, $bASC);
		gblTraceLog()->Log ("Ladder/Server/Folder.Items.aryIDs", "aryIDs");
		return ($aryIDs);
	}
	
	Function References ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
	{
		$aryIDs = $this->getChildren ($szName, $aryClasses, Globals::cisReference(), $nOrderBy, $bASC);
		return ($aryIDs);
	}

	// ===================================================

	Function getFolder ($szName ="", $szClass=0)
	{
		$aryClasses = Array();
		if ($szClass != 0)
			$aryClasses = Array (1=>$szClass);
			
		$aryIDs = $this->getChildren ($szName, $aryClasses, Globals::cisFolder());
		
		$objItem = null;
		if (Count ($aryIDs) == 1)
		{ $objItem = $aryIDs->getItem (1); }
			
		return ($objItem);
	}
	
	Function getItem ($szName ="", $szClass=0)
	{
		$aryClasses = Array();
		if ($szClass != 0)
			$aryClasses = Array (1=>$szClass);

		$aryIDs = $this->getChildren ($szName, $aryClasses, Globals::cisItem());

		$objItem = null;
		if (Count ($aryIDs) == 1)
		{ $objItem = $aryIDs->getItem (1); }
		
		return ($objItem);
	}
	
	Function getReference ($szName ="", $szClass=0)
	{
		$aryClasses = Array();
		if ($szClass != 0)
			$aryClasses = Array (1=>$szClass);

		$aryIDs = $this->getChildren ($szName, $aryClasses, Globals::cisReference());

		$objItem = null;
		if (Count ($aryIDs) == 1)
		{ $objItem = $aryIDs->getItem (1); }
			
		return ($objItem);
	}	

	// ===================================================
	
	Function Create_Folder ($szName, $szDescription, $szClass)
	{
		$newItem = new \ENetArch\Ladder\Server\Folder ();
		
		$newItem->Connect ($this->cn);
		$newItem->Create ($this, $szName, $szDescription, $szClass);
		return ($newItem);
	}
	
	Function Create_Item ($szName, $szDescription, $szClass)
	{
		$newItem = new \ENetArch\Ladder\Server\Item ();
		
		$newItem->Connect ($this->cn);
		$newItem->Create ($this, $szName, $szDescription, $szClass);
		return ($newItem);
	}

	Function Create_Item2 ($szName, $szDescription, $szClass, $szTableName)
	{
		$newItem = new \ENetArch\Ladder\Server\Item ();
		$newItem->Connect ($this->cn);
		$newItem->Create2 ($this, $szName, $szDescription, $szClass, $szTableName);
		return ($newItem);
	}

	Function Create_Reference ($szName, $szDescription, $szClass, $thsFolder, $thsObject=null)
	{
		$newItem = new \ENetArch\Ladder\Server\Reference ();
		
		$newItem->Connect ($this->cn);
		$newItem->Create ($this, $szName, $szDescription, $szClass, $thsFolder);
		return ($newItem);
	}

	// ===================================================
	// Change History
	
	/*
		2009-12-19 - added nOrderBy and bASC to SubFolders, Items, and References.
		
	*/
}

?>
