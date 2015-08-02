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

Class Item extends Properties
{
	var $objData = null ;
	var $szTableName = "";
	
	Function Create ($thsParent, $thsName, $thsDescription, $thsClass)
	{
		if ($thsParent == null) return;
		
		parent::Init ($thsName, $thsDescription, $thsParent->GUID(), $thsClass, Globals::cisItem(), 0);
	}

	Function Create2 ($thsParent, $thsName, $thsDescription, $thsClass, $szTableName)
	{
		if ($thsParent == null) return;
		
		$this->szTableName = $szTableName;
		
		parent::Init ($thsName, $thsDescription, $thsParent->GUID(), $thsClass, Globals::cisItem(), 0);
	}

	// ==================================================

	Function getTableName ()
	{
		if ($this->getClass() == Globals::cClass_Ladder_Defination())
		{ $this->szTableName = "Ladder_Defination"; }
		
		if (strLen ($this->szTableName) == 0)
		{
			$objClass = $this->getClassFolder();
			$objDef = $objClass->getItem ("", Globals::cClass_Ladder_Defination());
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
		$this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.trace", debug_backtrace ());
			return;
		}
		
		parent::Delete ();
	}
	
	// ===================================================

	Function getStructure () // as Array
	{
      $szSQL = 
         " SHOW COLUMNS " .
         " FROM " . $this->getTableName();
	            
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");	            
      $rs = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.trace", debug_backtrace ());
			return;
		}

      $rowCount = count ($rs);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.trace", debug_backtrace ());
			return;
		}

		$objStr = Array();
		
      If ($rowCount != 0) 
      {
         ForEach ($rs AS $key => $row)
			$objStr [$row [0]] = "";
		}
		
		$rs = null;

		return ($objStr);
	}
	
	// ===================================================

	Function getData () // as Array
	{
//		gblTraceLog ()->Log ("ldrItem.getData.objData = ", $this->objData);
//		gblTraceLog ()->Log ("ldrItem.getData.Leaf = ", $this->Leaf());
		
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
		$rs = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.trace", debug_backtrace ());
			return;
		}

		$rowCount = count ($rs);
		$row = null;
		
		if ($rowCount == 0)
		{ return ($this->objData); }
		
		
		$row = $rs->fetch();

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
				$szSQL .= " '" .sqlEncode ($aryValues[$aryKeys[$t]]) . "', ";

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
				$szSQL .= $aryKeys[$t] . " = '" . sqlEncode ($aryValues[$aryKeys[$t]]) . "', ";
			
			$szSQL = Left ($szSQL, strLen ($szSQL) -2);
			$szSQL .= 
				" WHERE " . 
					" ID = " . $this->Leaf();
		}

		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.trace", debug_backtrace ());
			return;
		}

		if ($this->Leaf() == 0)
		      $this->setLeaf ($this->cn->LastInsertId ());
	}
	
	// ===================================================

	Function Store ()
	{
		$this->saveData();
		parent::Store();
		return ( $this->getState() );
	}

	// ===================================================

	Function getField ($thsField) { return ($this->Field ($thsField)); }
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
