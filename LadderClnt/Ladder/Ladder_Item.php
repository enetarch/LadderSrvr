<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
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
		
		print ("<BR>");
		print ("thsClass = [ " . $thsClass . " ]<BR>");
		
		parent::Create ($thsName, $thsDescription, $thsParent->GUID(), $thsClass, ENetArch_Ladder_Globals::cisItem(), 0);
	}

	Function Create2 ($thsParent, $thsName, $thsDescription, $thsClass, $szTableName)
	{
		if ($thsParent == null) return;
		
		$this->szTableName = $szTableName;
		
		parent::Create ($thsName, $thsDescription, $thsParent->GUID(), $thsClass, ENetArch_Ladder_Globals::cisItem(), 0);
	}

	// ==================================================

	Function getTableName ()
	{
		Global $gblLadder;

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
		$data = array
		(
			"szCommand" => "cmdItem.Delete", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID() )
		);

		return ($this->cn->exec ($data));
	}
	
	// ===================================================

	Function getStructure () // as Array
	{
		$data = array
		(
			"szCommand" => "cmdItem.getStructure", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID() )
		);

		return ($this->cn->exec ($data));
	}
	
	// ===================================================

	Function getData () // as Array
	{
		if ($this->objData != null) return ($this->objData);

		$data = array
		(
			"szCommand" => "cmdItem.getData", 
			"bVerbose" => true, 
			"szParams" => Array ($this->GUID() )
		);
		
		$this->objData = $this->cn->exec ($data);
		
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

		print ("---------------------------------------------------------------<BR>");
		print ("ldrItem.getData.objData = ");
		print_r ($this->objData);
		print ("<BR>");
		
		if ($thsData != null) 
			$this->objData = $thsData;
			
		$data = array
		(
			"szCommand" => "cmdItem.saveData", 
			"bVerbose" => true, 
			"szParams" => array (0 =>$this->getState(), 1=>$this->objData)
		);
		
		$thsState = $this->cn->exec ($data);
		
		print ("---------------------------------------------------------------<BR>");
		print ("ldrItem.getData.thsState = ");
		print_r ($thsState);
		print ("<BR>");
		
		$this->setState ($thsState);
			
		return (true);
	}
	
	// ===================================================

	Function Store ()
	{
		$this->saveData();
		// parent::Store();
	}

	// ===================================================

	Function Field ($thsField)
	{
		// print("Ladder_Item . Field () <BR>");
		
		if (strLen (trim ($thsField)) == 0) return;
		$objData = $this->getData();
		
		return ($objData [$thsField]);
	}
	
	Function setField ($thsField, $thsValue)
	{
		// print("Ladder_Item . setField () <BR>");
		
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
