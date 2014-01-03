<?
/*	=======================================
	Copyright 1998, 2000, 2003, 2007, 2009 - E Net Arch
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
			parent::Create ($thsName, $thsDescription, $thsParent->ID(), $thsClass, ENetArch_Ladder_Globals::cisFolder());
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
			 	" ID = " . $this->ID();
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . " <BR>" . $szSQL);
			
		return (true);
	}
	
	// ===================================================
	
	Function Count ($szName ="", $aryClasses=Array(), $nBaseType=0) // Array
	{
		$szSQL = 
			" SELECT COUNT(*) " .
			" FROM " . parent::cTableName() . 
			" WHERE " . 
				" parent = " . $this->ID();

		if (strLen (trim ($szName)) != 0) 
			$szSQL .= " AND name = '" . $szName . "' ";
		if (count ($aryClasses) != 0) 
			$szSQL .= " AND Class IN (" . stringIDs ($aryClasses) . ") ";
		if ($nBaseType != 0) 
			$szSQL .= " AND BaseType = " . $nBaseType . " ";
			
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . " <BR>" . $szSQL);
				
		$rowCount = mysql_num_rows ($rs);
		
      $row = mysql_fetch_row ($rs);
      $nCount = $row["0"];

      mysql_free_result($rs);
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
		$szSQL = 
			" SELECT * " .
			" FROM " . parent::cTableName() . 
			" WHERE " . 
				" parent = " . $this->ID();

		if (strLen (trim ($szName)) != 0) 
			$szSQL .= " AND name = '" . $szName . "' ";
		if (count ($aryClasses) != 0) 
			$szSQL .= " AND Class IN (" . stringIDs ($aryClasses) . ") ";
		if ($nBaseType != 0) 
			$szSQL .= " AND BaseType = " . $nBaseType . " ";

		$szSQL .= 	" ORDER BY ";
		switch ($nOrderBy)
		{ 
			case ENetArch_Ladder_Globals::cOrderBy_ID() : $szSQL .= 	" ID "; break;
			case ENetArch_Ladder_Globals::cOrderBy_Name() : $szSQL .= 	" Name "; break;
			case ENetArch_Ladder_Globals::cOrderBy_Description() : $szSQL .= 	" Description "; break;
			case ENetArch_Ladder_Globals::cOrderBy_Class() : $szSQL .= 	" Class "; break;
			case ENetArch_Ladder_Globals::cOrderBy_Date() : $szSQL .= 	" dCreated "; break;
			case ENetArch_Ladder_Globals::cOrderBy_BaseType() : $szSQL .= 	" BaseType "; break;
			default : $szSQL .= 	" ID "; break;
		}
		
		$szSQL .= 	(($bASC) ? " ASC " : " DESC ");
			
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
		{
			print(mysql_error ($this->cn) . " <BR>" . $szSQL);
			print ("<pre>");
			print_r (debug_backtrace ());
			print ("</pre>");
		}

		$rowCount = mysql_num_rows ($rs);
		
      $aryIDs = Array ();
      for ($t=0; $t<$rowCount; $t++)
      {
         $row = mysql_fetch_row ($rs);
         $aryIDs[] = $row;
      }

      mysql_free_result($rs);
      $rs = null;
      
		$objLinks = new ENetArch_Ladder_Links ();
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
	
	Function getFolders ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
	{
		$aryIDs = $this->getChildren ($szName, $aryClasses, ENetArch_Ladder_Globals::cisFolder(), $nOrderBy, $bASC);
		return ($aryIDs);
	}
	
	Function getItems ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
	{
		$aryIDs = $this->getChildren ($szName, $aryClasses, ENetArch_Ladder_Globals::cisItem(), $nOrderBy, $bASC);
		return ($aryIDs);
	}
	
	Function getReferences ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
	{
		$aryIDs = $this->getChildren ($szName, $aryClasses, ENetArch_Ladder_Globals::cisReference(), $nOrderBy, $bASC);
		return ($aryIDs);
	}

	// ===================================================

	Function getFolder ($szName ="", $nClass=0)
	{
		$aryClasses = Array();
		if ($nClass != 0)
			$aryClasses = Array (1=>$nClass);
			
		$aryIDs = $this->getChildren ($szName, $aryClasses, ENetArch_Ladder_Globals::cisFolder());
		
		$objItem = null;
		if (Count ($aryIDs) == 1)
		{ $objItem = $aryIDs->getItem (1); }
			
		return ($objItem);
	}
	
	Function getItem ($szName ="", $nClass=0)
	{
		$aryClasses = Array();
		if ($nClass != 0)
			$aryClasses = Array (1=>$nClass);

		$aryIDs = $this->getChildren ($szName, $aryClasses, ENetArch_Ladder_Globals::cisItem());

		$objItem = null;
		if (Count ($aryIDs) == 1)
		{ $objItem = $aryIDs->getItem (1); }
		
		return ($objItem);
	}
	
	Function getReference ($szName ="", $nClass=0)
	{
		$aryClasses = Array();
		if ($nClass != 0)
			$aryClasses = Array (1=>$nClass);

		$aryIDs = $this->getChildren ($szName, $aryClasses, ENetArch_Ladder_Globals::cisReference());

		$objItem = null;
		if (Count ($aryIDs) == 1)
		{ $objItem = $aryIDs->getItem (1); }
			
		return ($objItem);
	}	

	// ===================================================
	
	Function Create_Folder ($szName, $szDescription, $nClass)
	{
		$newItem = new ENetArch_Ladder_Folder ();
		
		$newItem->Connect ($this->cn);
		$newItem->Create ($this, $szName, $szDescription, $nClass);
		return ($newItem);
	}
	
	Function Create_Item ($szName, $szDescription, $nClass)
	{
		$newItem = new ENetArch_Ladder_Item ();
		
		$newItem->Connect ($this->cn);
		$newItem->Create ($this, $szName, $szDescription, $nClass);
		return ($newItem);
	}

	Function Create_Item2 ($szName, $szDescription, $nClass, $szTableName)
	{
		$newItem = new ENetArch_Ladder_Item ();
		$newItem->Connect ($this->cn);
		$newItem->Create2 ($this, $szName, $szDescription, $nClass, $szTableName);
		return ($newItem);
	}

	Function Create_Reference ($szName, $szDescription, $nClass, $thsFolder, $thsObject=null)
	{
		$newItem = new ENetArch_Ladder_Reference ();
		
		$newItem->Connect ($this->cn);
		$newItem->Create ($this, $szName, $szDescription, $nClass, $thsFolder);
		return ($newItem);
	}

	// ===================================================
	// Change History
	
	/*
		2009-12-19 - added nOrderBy and bASC to SubFolders, Items, and References.
		
	*/
}

?>
