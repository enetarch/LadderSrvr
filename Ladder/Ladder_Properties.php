<?
/*	=======================================
	Copyright 1998, 2000, 2003, 2007, 2009 - E Net Arch
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
				02	Name varChar (40)
				03	Description varChar (250)
				04	Parent Integer
				05	BaseType Integer
				06	Class Integer
				07	Leaf
	*/
	// ===================================

	var $cn = null;
	
	var $nID = 0;
	var $szName = "";
	var $szDescription = "";
	var $nParent = 0;
	var $nBaseType = 0;
	var $nClass = 0;
	var $nLeaf = 0;
	var $dCreated = "";

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
	 	$this->szName = $row[1];
	 	$this->szDescription = $row[2];
	 	$this->nParent = $row[3];
	 	$this->nBaseType = $row[4];
	 	$this->nClass = $row[5];
	 	$this->nLeaf = $row[6];
	 	$this->dCreated = $row[7];
	}
	
	Function getState () // as row
	{
		$row = Array();
		
		$row[0] =  $this->nID;
		$row[1] =  $this->szName;
		$row[2] =  $this->szDescription;
		$row[3] =  $this->nParent;
		$row[4] =  $this->nBaseType;
		$row[5] =  $this->nClass;
		$row[6] =  $this->nLeaf;	
		$row[7] =  $this->dCreated;
		
		return ($row);
	}
	
	// ==================================================
	
	Function Create ($szName, $szDescription, $nParent = 0, $nClass, $nBaseType, $nLeaf=0)
	{
		if (strLen (trim ($szName)) == 0) return;
		if ($nClass == 0) return;
		if ($nBaseType == 0) return;		
	
	 	$this->szName = $szName;
	 	$this->szDescription = $szDescription;
	 	$this->nParent = $nParent;
	 	$this->nBaseType = $nBaseType;
	 	$this->nClass = $nClass;
	 	$this->nLeaf = $nLeaf;
	 	$this->dCreated = Now();
	}
	
	// ===================================================
	
	Function Delete ()
	{
		$szSQL = 
			" DELETE " . 
			" FROM " . ENetArch_Ladder_Properties::cTableName() . 
			" WHERE " . 
			 	" ID = " . $this->ID();
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . " <BR>" . $szSQL);

	}
	
	// ==================================================
	
	Function ID()	{ return ($this->nID); }

	Function Name()	{ return ($this->szName); }
	Function setName($thsName)	{ $this->szName = $thsName; }
	
	Function Description()	{ return ($this->szDescription); }
	Function setDescription($thsDesc)	{ $this->szDescription = $thsDesc; }
	
	Function Parent()	{ return ($this->nParent); }
	Function setParent($thsParent)	{ $this->nParent = $thsParent; }

	Function getClass()	{ return ($this->nClass); }
	Function ClassID()	{ return ($this->nClass); }
	Function BaseType()	{ return ($this->nBaseType); }

	Function Leaf()	{ return ($this->nLeaf); }
	Function setLeaf($thsLeaf)	{ $this->nLeaf = $thsLeaf; }
	
	Function Created()	{ return ($this->dCreated); }

	// ==================================================
	Function getClassFolder ()
	{
		Global $gblLadder;
		
		$objClass = $gblLadder->getClass ("", $this->getClass());

		return ($objClass);
	}

	// ==================================================
	
	Function Store ()
	{
		$szSQL = "";
		
		if ($this->nID == 0)
		{
			$szSQL = 
			" INSERT INTO " . ENetArch_Ladder_Properties::cTableName() . 
			" ( Name, Description, Parent, Class, BaseType, Leaf ) " .
			" VALUES " . 
			" ( " .
			" '". $this->szName . "', " . 
			" '". $this->szDescription . "', " . 
			" '". $this->nParent . "', " . 
			" '". $this->nClass . "', " . 
			" '". $this->nBaseType . "', " . 
			" '". $this->nLeaf . "' " . 
			" ) ";
		}
		else
		{
			$szSQL = 
			"UPDATE " . ENetArch_Ladder_Properties::cTableName() . 
			" SET " . 
				" Name = '" . sqlEncode ($this->szName) . "' , " . 
				" Description = '" . sqlEncode ($this->szDescription) . "' , " . 
				" Parent = " . $this->nParent . " , " . 
				" Class = " . $this->nClass . " , " . 
				" BaseType = " . $this->nBaseType . " , " . 
				" Leaf = " . $this->nLeaf . "  " . 
			" WHERE " .
				" ID = " . $this->nID;
		}
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . " <BR>" . $szSQL);
		
		if ($this->nID == 0)
		      $this->nID = mysql_insert_id ($this->cn);
		
		return ($this->nID);
	}	

	// ==================================================

	Function getParent ()
	{
		Global $gblLadder;
		
		if ($this->nParent == 0) return;
		
		$objParent = $gblLadder->getItem ($this->nParent);
		return ($objParent);
	}

	// ==================================================

	Function Path () // as Array
	{
		$aryPath = Array();
		$aryPath[0] = $this->nID;
		
		if ($this->nParent == 0) return ($aryPath);
		
		$objParent = $this->getParent();
		$t = 1;
		While ($objParent != null)
		{
			$aryPath[$t] = $objParent->ID();
			$t++;
			$objParent = $objParent->getParent();
		}
		
		$rvrs = array_reverse ($aryPath);
		return ($rvrs);
	}
	
}
?>
