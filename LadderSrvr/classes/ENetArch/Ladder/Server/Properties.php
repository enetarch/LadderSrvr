<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace ENetArch\Ladder\Server;

use \ENetArch\Ladder\Server\Globals;
use \ENetArch\Common\Functions;

Class Properties
{
	static Function cTableName () { return (Globals::cLadder_Table_Links()); }
	
	// ===================================
	// Structure
	/*
				01	ID INTEGER
				02	GUID varChar (40)
				03	szName varChar (40)
				04	szDescription varChar (250)
				05	gdParent GUID
				06	nBaseType Integer
				07	gdClass GUID
				08	nLeaf
				09  dCreated Date
				10  dUpdated Date
				11  gdReference
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
	var $dUpdated = "";
	var $gdReference = "";

	Function __Construct ()
	{	$this->dCreated = Now(); }
	
	Function Properties () { self::__Construct(); }
	
	Function Connect ($cn) { $this->cn = $cn; }
	
	// ==================================================

	Function isFolder () 
	{ return 
		(
			($this->nBaseType == Globals::cisRoot()) ||
			($this->nBaseType == Globals::cisFolder()) 
		);
	}
	
	Function isItem () {  return ($this->nBaseType == Globals::cisItem()); }
	Function isReference () {  return ($this->nBaseType == Globals::cisReference()); }
	
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
	 	$this->dUpdated = $row[9];
	 	$this->gdReference = $row[10];
	}
	
	Function getState () // as row
	{
		$row = Array();
		
		$row[0] =  $this->nID;
		$row[1] =  $this->szGUID;
		$row[9] =  $this->dUpdated;

		$row[2] =  $this->szName;
		$row[3] =  $this->szDescription;
		$row[4] =  $this->gdParent;
		$row[5] =  $this->nBaseType;
		$row[6] =  $this->szClass;
		$row[7] =  $this->nLeaf;	
		$row[8] =  $this->dCreated;
		$row[10] =  $this->gdReference;
		
		return ($row);
	}
	
	// ==================================================
	
	Function Init ($szName, $szDescription, $gdParent = 0, $szClass, $nBaseType, $nLeaf=0, $gdReference="")
	{
		if (strLen (trim ($szName)) == 0) return;
		if (strLen (trim ($szClass)) == 0) return;
		if ($nBaseType == 0) return;		
	
		$this->szGUID = getGUID();
		$this->dUpdated = Now();
		
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
		$szSQL = 
			" DELETE " . 
			" FROM " . self::cTableName() . 
			" WHERE " . 
			 	" GUID = '" . $this->GUID() . "' ";
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.getChildren.trace", debug_backtrace ());
		}
	}
	
	// ==================================================
	
	// Function ID() { return ($this->nID); }

	Function GUID()	{ return ($this->szGUID); }
	Function getGUID()	{ return ($this->szGUID); }
	Function setGUID($thsGUID)	
	{ 
		// allow changes to the GUID until this element has been stored.
		if ($this->nID == 0)
		$this->szGUID = $thsGUID; 
	}

	Function Name()	{ return ($this->szName); }
	Function getName()	{ return ($this->szName); }
	Function setName($thsName)	{ $this->szName = $thsName; }
	
	Function Description()	{ return ($this->szDescription); }
	Function getDescription()	{ return ($this->szDescription); }
	Function setDescription($thsDesc)	{ $this->szDescription = $thsDesc; }
	
	Function Parent()	{ return ($this->gdParent); }
	Function setParent($thsParent)	{ $this->gdParent = $thsParent; }

	Function getClass()	{ return ($this->szClass); }
	Function ClassID()	{ return ($this->szClass); }
	Function ClassName()	{ return ($this->szClass); }
	
	Function BaseType()	{ return ($this->nBaseType); }
	Function getBaseType()	{ return ($this->nBaseType); }

	Function Leaf()	{ return ($this->nLeaf); }
	Function setLeaf($thsLeaf)	{ $this->nLeaf = $thsLeaf; }
	
	Function getReference()	{ return ($this->gdReference); }
	Function setReference($thsFolder)	{ $this->gdReference = $thsFolder; }

	Function Created()	{ return ($this->dCreated); }
	Function getCreated()	{ return ($this->dCreated); }
	Function Updated()	{ return ($this->dUpdated); }
	Function getUpdated()	{ return ($this->dUpdated); }
	Function setUpdated($thsDate)	{ $this->dUpdated = $thsDate; }
	
	// ==================================================
	
	Function getClassFolder ()
	{
		$objClass = gblLadder()->getClass ($this->getClass(), "");

		return ($objClass);
	}

	// ==================================================
	
	Function Store ()
	{
		$szSQL = "";
		
		if ($this->nID == 0)
		{
			$szSQL = 
			" INSERT INTO " . self::cTableName() . 
			" ( GUID, szName, szDescription, gdParent, szClass, nBaseType, nLeaf, dUpdated, gdReference ) " .
			" VALUES " . 
			" ( " .
			" '". $this->szGUID . "', " . 
			" '". $this->szName . "', " . 
			" '". $this->szDescription . "', " . 
			" '". $this->gdParent . "', " . 
			" '". $this->szClass . "', " . 
			" '". $this->nBaseType . "', " . 
			" '". $this->nLeaf . "', " . 
			" '". $this->dUpdated . "', " . 
			" '". $this->gdReference. "' " . 
			" ) ";
		}
		else
		{
			$szSQL = 
			"UPDATE " . self::cTableName() . 
			" SET " . 
				" GUID = '" . sqlEncode ($this->szGUID) . "' , " . 
				" szName = '" . sqlEncode ($this->szName) . "' , " . 
				" szDescription = '" . sqlEncode ($this->szDescription) . "' , " . 
				" gdParent = '" . sqlEncode ($this->gdParent) . "' , " . 
				" szClass = '" . sqlEncode ($this->szClass) . "' , " . 
				" nBaseType = " . $this->nBaseType . " , " . 
				" nLeaf = " . $this->nLeaf . ",  " . 
				" dCreated = dCreated, " . 
				" dUpdated = '" .  $this->dUpdated . "',  " . 
				" gdReference = '" . sqlEncode ($this->gdReference) . "'  " . 
			" WHERE " .
				" ID = " . $this->nID;
		}
		
		gblTraceLog()->Log ("Ladder/Server/Properties.store.SQL", $szSQL);
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/Folder.Properties.SQL", debug_backtrace ());
			return;
		}
		
		if ($this->nID == 0)
		      $this->nID = $this->cn->lastInsertId ($szSQL);
		
		return ($this->nID);
	}	

	// ==================================================

	Function getParent ()
	{
		Global $gblLadder;
		
		if ($this->gdParent == "0") return;
		
		$objParent = $gblLadder->getItem ($this->gdParent);
		return ($objParent);
	}

	// ==================================================

	function getPath () { return ($this->Path ()); }
	Function Path () // as Array
	{
		$aryPath = Array();
		$aryPath[0] = 
		[
			$this->GUID(),
			$this->Name()
		];
		
		
		if ($this->gdParent == "") return ($aryPath);
		
		$objParent = $this->getParent();
		$t = 1;
		While ($objParent != null)
		{
			$aryPath[$t] = 
			[
				$objParent->GUID(),
				$objParent->Name()
			];
			$t++;
			$objParent = $objParent->getParent();
		}
		
		$rvrs = array_reverse ($aryPath);
		return ($rvrs);
	}
	
}
?>
