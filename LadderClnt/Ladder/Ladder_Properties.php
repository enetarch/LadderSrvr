<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */


Class ENetArch_Ladder_Properties
{
	Function cTableName () { return (ENetArch_Ladder_Globals::cLadder_Table_Links()); }
	
	// ===================================
	// Structure
	/*
				01	ID INTEGER
				02	GUID varChar (40)
				02	szName varChar (40)
				03	szDescription varChar (250)
				04	gdParent varChar (40)
				05	nBaseType Integer
				06	szClass varChar (40)
				07	nLeaf
				09	gdReference varChar (40)
	*/
	// ===================================

	var $cn = null;
	
	var $nID = 0;
	var $szGUID = "";
	var $szName = "";
	var $szDescription = "";
	var $gdParent = "";
	var $nBaseType = 0;
	var $szClass = "";
	var $nLeaf = 0;
	var $dCreated = "";
	var $gdReference = "";

	Function __Construct ()
	{	$this->dCreated = Now(); }
	
	Function ENetArch_Ladder_Properties () { ENetArch_Ladder_Properties::__Construct(); }
	
	Function Connect ($cn) { $this->cn = $cn; }
	
	// ==================================================

	Function isFolder () 
	{ return 
		(
			($this->nBaseType == ENetArch_Ladder_Globals::cisRoot()) ||
			($this->nBaseType == ENetArch_Ladder_Globals::cisFolder()) 
		);
	}
	
	Function isItem () {  return ($this->nBaseType == ENetArch_Ladder_Globals::cisItem()); }
	Function isReference () {  return ($this->nBaseType == ENetArch_Ladder_Globals::cisReference()); }
	
	// ==================================================

	Function setState ($row)
	{
	 	$this->nID = $row[0];
	 	$this->szGUID = $row[1];
	 	$this->szName = $row[2];
	 	$this->szDescription = $row[3];
	 	$this->gdParent = $row[4];
	 	$this->nBaseType = $row[5];
	 	$this->szClass = $row[6];
	 	$this->nLeaf = $row[7];
	 	$this->dCreated = $row[8];
	 	$this->gdReference = $row[9];
	}
	
	Function getState () // as row
	{
		$row = Array();
		
		$row[0] =  $this->nID;
		$row[1] =  $this->szGUID;
		$row[2] =  $this->szName;
		$row[3] =  $this->szDescription;
		$row[4] =  $this->gdParent;
		$row[5] =  $this->nBaseType;
		$row[6] =  $this->szClass;
		$row[7] =  $this->nLeaf;	
		$row[8] =  $this->dCreated;
		$row[9] =  $this->gdReference;
		
		return ($row);
	}
	
	// ==================================================
	
	Function Create ($szName, $szDescription, $gdParent = 0, $szClass, $nBaseType, $nLeaf=0)
	{
		if (strLen (trim ($szName)) == 0) return;
		if (strLen (trim ($szClass)) == 0) return;
		if ($nBaseType == 0) return;		
	
		$this->szGUID = getGUID();
	 	$this->szName = $szName;
	 	$this->szDescription = $szDescription;
	 	$this->gdParent = $gdParent;
	 	$this->nBaseType = $nBaseType;
	 	$this->szClass = $szClass;
	 	$this->nLeaf = $nLeaf;
	 	$this->dCreated = Now();
	 	$this->gdReference = "";
	}
	
	// ===================================================
	
	Function Delete ()
	{
		$data = array
		(
			"szCommand" => "cmdLadder.Delete", 
			"bVerbose" => true, 
			"szParams" => Array ($this->ID())
		);
	}
	
	// ==================================================
	
	// Function ID()	{ return ($this->nID); }

	Function GUID()	{ return ($this->szGUID); }
	Function setGUID($thsGUID)	
	{ 
		// allow changes to the GUID until this element has been stored.
		if ($this->nID == 0)
		$this->szGUID = $thsGUID; 
	}
	

	Function Name()	{ return ($this->szName); }
	Function setName($thsName)	{ $this->szName = $thsName; }
	
	Function Description()	{ return ($this->szDescription); }
	Function setDescription($thsDesc)	{ $this->szDescription = $thsDesc; }
	
	Function Parent()	{ return ($this->gdParent); }
	Function setParent($thsParent)	{ $this->gdParent = $thsParent; }

	Function getClass()	{ return ($this->nClass); }
	Function ClassID()	{ return ($this->nClass); }
	Function BaseType()	{ return ($this->nBaseType); }

	Function Leaf()	{ return ($this->nLeaf); }
	Function setLeaf($thsLeaf)	{ $this->nLeaf = $thsLeaf; }
	
	Function getReference()	{ return ($this->gdReference); }
	Function setReference($thsFolder)	{ $this->gdReference = $thsFolder; }
	
	Function Created()	{ return ($this->dCreated); }

	// ==================================================
	
	Function getClassFolder ()
	{
		$objClass = gblLadder()->getClass ($this->getClass(), "");

		return ($objClass);
	}

	// ==================================================
	
	Function Store ()
	{
		$row = $this->getState();
		
		$data = array
		(
			"szCommand" => "cmdProperties.Store", 
			"bVerbose" => true, 
			"szParams" => Array ($row)
		);

		$nID = $this->cn->exec ($data);

		if ($this->nID == 0)
			$this->nID = $nID;
		
		return ($nID);
	}	

	// ==================================================

	Function getParent ()
	{
		if ($this->gdParent == "") return;
		
		$objParent = gblLadder()->getItem ($this->gdParent);
		return ($objParent);
	}

	// ==================================================

	Function Path () // as Array
	{
		$aryPath = Array();
		$aryPath[0] = $this->nID;
		
		if ($this->gdParent == "") return ($aryPath);
		
		$objParent = $this->getParent();
		$t = 1;
		while ($objParent != null)
		{
			$aryPath[$t] = $objParent->GUID();
			$t++;
			$objParent = $objParent->getParent();
		}
		
		$rvrs = array_reverse ($aryPath);
		return ($rvrs);
	}
	
}
?>
