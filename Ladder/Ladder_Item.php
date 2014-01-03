<?
/*	=======================================
	Copyright 1998, 2000, 2003, 2007, 2009 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Class ENetArch_Ladder_Item extends ENetArch_Ladder_Properties
{
	var $objData = null ;
	var $szTableName = "";
	
	Function Create ($thsParent, $thsName, $thsDescription, $thsClass)
	{
		if ($thsParent == null) return;
		
		parent::Create ($thsName, $thsDescription, $thsParent->ID(), $thsClass, ENetArch_Ladder_Globals::cisItem(), 0);
	}

	Function Create2 ($thsParent, $thsName, $thsDescription, $thsClass, $szTableName)
	{
		if ($thsParent == null) return;
		
		$this->szTableName = $szTableName;
		
		parent::Create ($thsName, $thsDescription, $thsParent->ID(), $thsClass, ENetArch_Ladder_Globals::cisItem(), 0);
	}

	// ==================================================

	Function getTableName ()
	{
		if ($this->getClass() == ENetArch_Ladder_Globals::cClass_Ladder_Defination())
		{ $this->szTableName = "Ladder_Defination"; }
		
		if (strLen ($this->szTableName) == 0)
		{
			$objClass = $this->getClassFolder();
			$objDef = $objClass->getItem ("", ENetArch_Ladder_Globals::cClass_Ladder_Defination());
			$this->szTableName = $objDef->Field ("szTable");
		}
		
		return ($this->szTableName);
	}

	// ===================================================
	
	Function Delete ()
	{
		$szSQL = 
			" DELETE " . 
			" FROM " . $this->getTableName() . 
			" WHERE " . 
			 	" ID = " . $this->Leaf();
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . "<P>");
		
		parent::Delete ();
	}
	
	// ===================================================

	Function getStructure () // as Array
	{
      $szSQL = 
         " SHOW COLUMNS " .
         " FROM " . $this->getTableName();
	            
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");	            
      $rs = mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . "<P>");

      $rowCount = mysql_num_rows ($rs);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . "<P>");

		$objStr = Array();
		
      If ($rowCount != 0) 
      {
         For ($t = 0; $t < $rowCount; $t++)
         {
            $row = mysql_fetch_row($rs);
				$objStr [$row [0]] = "";
			}
		}
		
		mysql_free_result($rs);
		$rs = null;

		return ($objStr);
	}
	
	// ===================================================

	Function getData () // as Array
	{
		if ($this->objData != null) return ($this->objData);
		if ($this->Leaf() == 0) 
		{	
			$this->objData = $this->getStructure();
			return ($this->objData);
		}
		
		$szSQL = 
			" SELECT * " . 
			" FROM " . $this->getTableName() .
			" WHERE " . 
				" ID = " . $this->Leaf();

		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . "<P>");

		$rowCount = mysql_num_rows ($rs);
		$row = null;
		
		if ($rowCount == 0)
		{ return ($this->objData); }
		
		$row = mysql_fetch_row($rs);

		// ===========================
		
		$objStr = $this->getStructure();
		$t = 0;
		
		$aryKeys = array_Keys ($objStr);
		
		For ($t=0; $t<Count($aryKeys); $t++)
		{ 
			$Field = $aryKeys[$t];
			$objStr [$Field] = $row[$t];
		}
		
		// ===========================

		$this->objData = $objStr;		
		
		return ($this->objData);
	}
	
	function setData ($thsData)
	{
		if ($thsData == null) return;
		$this->objData = $thsData;
	}
	
	// ===================================================

	Function saveData ($thsData = null)
	{
		if (($this->objData == null) && ($thsData == null)) return;
		
		if ($thsData != null) 
			$this->objData = $thsData;
			
		$aryKeys = array_keys ($this->getStructure());
		$aryValues = $this->objData;
		
		$szSQL = "";

		if ($this->Leaf() == 0) 
		{
			$szSQL = 
				" INSERT INTO " . $this->getTableName() . 
				" ( " ;

			For ($t=1; $t<Count ($aryKeys); $t++)
				$szSQL .= $aryKeys[$t] . ", ";
			
			$szSQL = Left ($szSQL, strLen ($szSQL) -2);
			$szSQL .= " ) ";
			
			$szSQL .= 
				" VALUES " . 
				" ( " ;
			
			For ($t=1; $t<Count ($aryKeys); $t++)
				$szSQL .= " '" .mysql_real_escape_string ($aryValues[$aryKeys[$t]]) . "', ";

			$szSQL = Left ($szSQL, strLen ($szSQL) -2);
			$szSQL .= " ) ";
		}
		else
		{
			$szSQL = 
				" UPDATE " . $this->getTableName() . 
				" SET " ;

			$t = 0;
			For ($t=1; $t<Count ($aryKeys); $t++)
				$szSQL .= $aryKeys[$t] . " = '" . mysql_real_escape_string ($aryValues[$aryKeys[$t]]) . "', ";
			
			$szSQL = Left ($szSQL, strLen ($szSQL) -2);
			$szSQL .= 
				" WHERE " . 
					" ID = " . $this->Leaf();
		}

		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . "<P>");

		if ($this->Leaf() == 0)
		      $this->setLeaf (mysql_insert_id ($this->cn));
	}
	
	// ===================================================

	Function Store ()
	{
		$this->saveData();
		parent::Store();
		return ( $this->getState() );
	}

	// ===================================================

	Function Field ($thsField)
	{
		if (strLen (trim ($thsField)) == 0) return;
		if ($this->objData == null) $this->getData();
		
		return ($this->objData[$thsField]);
	}
	
	Function setField ($thsField, $thsValue)
	{
		if (strLen (trim ($thsField)) == 0) return;
		if ($this->objData == null) $this->getData();
		
		$this->objData[$thsField] = $thsValue;
	}
	
}

function Left ($szStr, $nLen)
{ return ( subStr ($szStr, 0, $nLen)); }

/*
	Version Updates
	2011-05-05 - mjf - changed SaveData ($objData) to SaveData ($thsData)
		to avoid confusion in other languages where objData and this.objData
		can point to the same values.
*/

?>
