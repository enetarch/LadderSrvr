<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace ENetArch\Ladder\Server;

use \ENetArch\Common\Functions;

use \ENetArch\Ladder\Server\Globals;
use \ENetArch\Ladder\Server\Properties;
use \ENetArch\Ladder\Server\Links;
use \ENetArch\Ladder\Server\Folders;
use \ENetArch\Ladder\Server\Folder;
use \ENetArch\Ladder\Server\Item;
use \ENetArch\Ladder\Server\Reference;
use \ENetArch\Ladder\Server\Version;

use \ENetArch\Ladder\OSQL\VM;

Class Ladder
{

	// ===================================
	// Structure
	/*
				01	ID INTEGER
				02	GUID varChar (40)
				03	szName varChar (40)
				04	szDescription varChar (250)
				05	gdParent GUID
				06	BaseType Integer
				07	szClass varChar (40)
				08	Leaf Integer
				09  dCreated Date
				10  dUpdated Date
				11  gdReference
	*/
	
	// ===================================
	// Constants
	 
	static Function cTableName_Links () { return (Globals::cLadder_Table_Links() ); }
	static Function cTableName_Definitions () { return (Globals::cLadder_Table_Defination () ); }

	// ===================================
	// Variables
	
	var $cn = null;  // link to database
	var $fldrClasses = null ; // Location of Classes

	// ===================================
	// Functions

	Function __Construct ()
	{	}

	Function Ladder () { ENetArch_Ladder::__Construct(); }
	
	
	Function Connect ($szODBC)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$szODBC2 = str_replace (";" , "&", $szODBC);
		parse_str($szODBC2, $aryODBC);

		$this->cn = new \PDO("mysql:host=" . $aryODBC['SERVER'] . ";dbname=" . $aryODBC['DSN']. ";", $aryODBC['UID'], $aryODBC['PSW']);
	}

	Function Disconnect ()
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$this->cn = null; 
	}

	// ====================================

	public function Version () 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$rtn = new \ENetArch\Ladder\Server\Version ();
		return ($rtn);
	}

	// ====================================
	
	function isInstalled ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$szSQL = "SHOW TABLES";

		$rs = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/ldrLadder.isInstalled.trace", debug_backtrace ());
			return;
		}
		
		forEach ($rs AS $row)
			if ($row [0] == self::cTableName_Links () )
				return (true);
		
		return (false);
	}
	
	Function Install ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$szSQL =
			" CREATE TABLE IF NOT EXISTS " . self::cTableName_Links () .
			" ( " .
				" ID Integer AUTO_INCREMENT, " .		// 0
				" GUID varChar (40) , " .		// 1
				" szName varChar (40), " .				// 2
				" szDescription varChar (250), " .	// 3
				" gdParent varChar (40), " .  					// 4
				" nBaseType Integer, " . 				// 5
				" szClass varChar (40), " .  						// 6
				" nLeaf Integer, " . 							// 7
				" dCreated TimeStamp, " .			// 8
				" dUpdated TimeStamp, " .			// 9
				" gdReference varChar (40), " .			// 10

				" Primary Key (ID), " .
				" UNIQUE INDEX ndxGUID (GUID), " .
				" INDEX ndxParentClass (gdParent, szClass) " .
			" ) ";

		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/ldrLadder.Install.trace", debug_backtrace ());
			return;
		}
		
		$this->Create_Roots();
		$this->Install_Classes();
		
		return (true);
	}

	function unInstall ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		// drop tables in classes, newest to oldest classes
		
		$szSQL = "DROP TABLE IF EXISTS " . self::cTableName_Definitions();

		$this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/ldrLadder.unInstall.trace", debug_backtrace ());
			return;
		}
		
		$szSQL = "DROP TABLE IF EXISTS " . self::cTableName_Links();

		$this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/ldrLadder.unInstall.trace", debug_backtrace ());
			return;
		}
		
		return (true);
	}
	

	// ====================================

	Function Create_Roots ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		// =======================
		// create root folder

		if (! $this->Root_Exists ("Root Folder", Globals::cClass_RootFolder()))
		{
			$objFolder = $this->Create_Root
				("Root Folder", "This is the Root of my Site", 
					Globals::cClass_RootFolder());
			$objFolder->Store();
		}

		// =======================
		// create root classes folder

		if (! $this->Root_Exists ("Root Classes", Globals::cClass_RootClasses()))
		{
			$objFolder = $this->Create_Root
				("Root Classes", "This stores the Names of my Classes", 
					Globals::cClass_RootClasses());
			$objFolder->Store();
		}
	}

	Function Install_Classes()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		// ==================================
		// Base Classes

		$szStr =
			" szClass varChar (40), " .
			" nBaseType Integer, " .
			" szTable varChar (40), " .
			" ofClass varChar (40), " .
			" bAcceptsAll BIT " ;

		$this->create_Class ("Ladder_Defination", Globals::cisItem(), "" , true, $szStr);

		$this->create_Class ("Ladder_Folder",  Globals::cisFolder(), "" , true);
		$this->create_Class ("Ladder_Class", Globals::cisFolder(), "" , true);

		$this->create_Class ("RootFolder", Globals::cisRoot(), "" , true);
		$this->create_Class ("RootClasses", Globals::cisRoot(), "" , true);

	}

	// ====================================
	
	Function CreateTable ($thsName, $szStr)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$szSQL =
			" CREATE TABLE IF NOT EXISTS " . $thsName .
			" ( " .
				" ID Integer AUTO_INCREMENT, " .		// 0
				" Primary Key (ID), " .
				$szStr .
			" ) ";
			
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/ldrFolder.CreateTable.trace", debug_backtrace ());
			return;
		}
	}

	// ====================================
	
	function getPath ($szPath)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		If (bDebugging) gblTraceLog()->Log ("value.szPath", $szPath);

		$len = strlen ($szPath);
			
		while (substr ($szPath, 0, 1) == "\\")
			$szPath = substr ($szPath, 1, $len);

		$aryPaths = explode ("\\", $szPath);
		$nCount = count ($aryPaths);
		
		print ("aryPaths = <pre>"); print_r ($aryPaths); print ("</pre>");

		If (bDebugging) gblTraceLog()->Log ("value.szPath", implode ("<BR>\r\n", $aryPaths));
		
		$rtn = null;
		
		for ($t=0; $t<$nCount; $t++)
		{
			$szName = $aryPaths[$t];
			
			if ($t == 0)
			{ $rtn = $this->getRoot ($szName); }
			else
			{ 
				if ($rtn->isFolder())
				{ 
					print ("count = " . $rtn->Count ($szName) . "<BR>");
					$children = $rtn->getChildren ($szName); 
					$rtn = $children->getItem (1);
				}
				else
				{ return ($rtn); }
			}
			
			print ("t = " . $t . "<BR>");
			
			if ($rtn == null) 
				return (null);
				
			print ("found = " . $rtn->Name() . "<BR>");
		}
		
		return ($rtn);
	}
	
	// ====================================
	
	Function getItem ($szGUID)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		// print("Ladder_Ladder . getItem_By_GUID . ($szGUID) <BR>");
		
		$szSQL =
			" SELECT * " .
			" FROM "  . self::cTableName_Links () .
			" WHERE " .
				" GUID = '" . $szGUID . "' ";
		
		gblTraceLog()->Log ("Ladder/Server/ldrLadder.getItem.szSQL", $szSQL);
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			print ("ERROR: " . $this->cn->errorInfo() . " - " . $this->cn->errorCode() .  " in file " . __FILE__ . " " . $szSQL . "<BR>");
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/ldrLadder.getItem.trace", debug_backtrace ());
			return;
		}

		$rowCount = count ($rs);
		if ($rowCount == 0)
			gblTraceLog()->Log ("Ladder/Server/ldrLadder.getItem.rowCount", $rowCount);
			
		$objItem = null;

		
		if ($rowCount > 0) 
		{
			$row = $rs->fetch();

			switch ($row [5])
			{
				case Globals::cisRoot() :
				{
					$objItem = new \ENetArch\Ladder\Server\Folder ();
					break;
				}

				case Globals::cisFolder() :
				{
					$objItem = new \ENetArch\Ladder\Server\Folder ();
					break;
				}

				case Globals::cisItem() :
				{
					$objItem = new \ENetArch\Ladder\Server\Item ();
					break;
				}

				case Globals::cisReference() :
				{
					$objItem = new \ENetArch\Ladder\Server\Reference ();
					break;
				}
			}

			if ($objItem != null)
			{
				$objItem->Connect ($this->cn);
				$objItem->setState ($row);
			}
		}
		
		return ($objItem);
	}

	// ====================================
	
	Function getObject ($szGUID, $thsObject)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$szSQL =
			" SELECT * " .
			" FROM "  . self::cTableName_Links () .
			" WHERE " .
				" GUID = '" . $szGUID . "' ";
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log ("Ladder/Server/ldrLadder.getObject.trace", debug_backtrace ());
			return;
		}

		$rowCount = count ($rs);

		if ($rowCount > 0) 
		{
			$row = $rs->fectch ();

			$thsObject->Connect ($this->cn);
			$thsObject->setState ($row);
		}

		return ($thsObject);
	}

	// ===================================

	function update_Link ($thsState)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$objProperties = new \ENetArch\Ladder\Server\Properties ();
		$objProperties->connect ($this->cn);
		$objProperties->setState ($thsState);
		$nID = $objProperties->Store ();
		
		return ( $nID );
	}
	
	// ===================================

	function update_Item ($thsState, $thsData)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		gblTraceLog()->Log (__METHOD__ . ".thsState = ", $thsState);
		gblTraceLog()->Log (__METHOD__ . ".thsData = ", $thsData);
		
		$objItem= new \ENetArch\Ladder\Server\Item ();
		$objItem->connect ($this->cn);
		$objItem->setState ($thsState);
		$objItem->setData ($thsData);
		$newState = $objItem->Store ();
		
		gblTraceLog()->Log (__METHOD__ . ".newState = ", $newState);
		
		return ( $newState );
	}
	
	Function getStructure ($szClass) // as Array
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		// confirm that this is a ITEM class
		
		$szSQL = 
			" SHOW COLUMNS " .
			" FROM " . $szClass;
         
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		
		$rs = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log (__METHOD__ . ".trace", debug_backtrace ());
			return;
		}

		$rowCount = count ($rs);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log (__METHOD__ . ".trace", debug_backtrace ());
			return;
		}

		$objStr = Array();
		
		If ($rowCount != 0) 
			ForEach ($rs AS $key => $row)
				$objStr [$row [0]] = "";


		$rs = null;

		return ($objStr);
	}

	// ===================================

	Function getRoots ($thsName = "", $szClass = "") // as Array
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		If (bDebugging) gblTraceLog()->Log ("thsName", $thsName);
		If (bDebugging) gblTraceLog()->Log ("szClass", $szClass);
		
		$szSQL =
			" SELECT * " .
			" FROM "  . self::cTableName_Links () .
			" WHERE " .
				" nBaseType = " . Globals::cisRoot() ;
		
		if (strLen (trim ($thsName)) != 0)
			$szSQL .= " AND szName = '" . $thsName . "' ";

		if (strLen (trim ($szClass)) != 0)
			$szSQL .= " AND szClass = '" . $szClass . "' ";

		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), "szSrc", "", $szSQL);
			gblTraceLog()->Log (__METHOD__ . ".trace", debug_backtrace ());
			return;
		}

		$rowCount = count ($rs);
      	$aryIDs = Array ();
		ForEach ($rs AS $key => $row)
		{
		 // $aryIDs[$t+1] = $row["0"]; --> original row
		 $aryIDs[] = $row;
		}

		$rs->closeCursor();

		// print_ary ($aryIDs);

		$rootFolders = new \ENetArch\Ladder\Server\Folders ();
		$rootFolders->connect ($this->cn);
		$rootFolders->setState ($aryIDs);

		return ($rootFolders);
	}

	// ===================================
	
	function getRoot ($thsName)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		If (bDebugging) gblTraceLog()->Log ("thsName", $thsName);
		
		$szSQL =
			" SELECT * " .
			" FROM "  . self::cTableName_Links () .
			" WHERE " .
				" nBaseType = " . Globals::cisRoot() . " AND " .
				" szName = '" . $thsName . "' ";

		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = $this->cn->query ($szSQL);
		If ($this->cn->errorCode() != 0) 
		{
			gblError()->init ($this->cn->errorInfo(), $this->cn->errorCode(), __METHOD__, "", $szSQL);
			gblTraceLog()->Log (__METHOD__ . ".trace", debug_backtrace ());
			return;
		}

		$rowCount = $rs->rowCount();
		
		$row = $rs->fetch();
			// print_ary ($nID);

		$rootFolder = new \ENetArch\Ladder\Server\Folder ();
		$rootFolder->connect ($this->cn);
		$rootFolder->setState ($row);
		
		$rs->closeCursor();

		return ($rootFolder);		
	}
	
	// ===================================

	Function Create_Root ($szName, $szDesc, $szClass)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$objItem = new \ENetArch\Ladder\Server\Folder ();
		$objItem->Connect ($this->cn);
		$objItem->Create (null, $szName, $szDesc, $szClass);

		return ($objItem);
	}
	
	// ===================================

	Function Root_Exists ($thsName, $szClass = 0)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$rootFolders = $this->getRoots ($thsName, $szClass);
		
		return ( $rootFolders->count () > 0);
	}
	
	// ===================================

	Function getClasses ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		if ($this->fldrClasses == null)
		{
			$aryRoots = $this->getRoots();
			$this->fldrClasses = $aryRoots->getItem (2);
		}
		
		return ($this->fldrClasses);
	}
	
	// ===================================

	Function create_Class ($thsName, $nBaseType, $ofClass="", $bAcceptsAll, $szStr="")
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$objClasses = $this->getClasses();

		$szTableName = $thsName;
		
		if ($nBaseType == Globals::cisItem())
			if (strlen (trim ($ofClass)) == 0)
			{ $this->CreateTable ($thsName, $szStr); }
			else
			{
				$clsOfClass = $this->getClass ($ofClass);
				if ($clsOfClass == null)
				{
					gblError()->init ("ofClass not found!", -1, __File__, "", $ofClass);
					return (false);
				}
				
				$defOfClass = $clsOfClass->getItem ("", Globals::cClass_Ladder_Defination());
				$szTableName = $defOfClass->Field ("szTable");
			}

		$aryClasses = Array (1=>Globals::cClass_Ladder_Class());
		if (! $objClasses->Exists ($thsName, $aryClasses))
		{
			$objClass = $objClasses->Create_Folder
				($thsName, "Class", Globals::cClass_Ladder_Class());
			$objClass->Store();

			$objDef = $objClass->Create_Item2 ($thsName, "Defination", Globals::cClass_Ladder_Defination(), "Ladder_Defination");
			$objDef->setField ("szClass", $thsName);
			$objDef->setField ("nBaseType", $nBaseType);
			$objDef->setField ("szTable", ($nBaseType == Globals::cisItem()) ? $szTableName : "");
			$objDef->setField ("ofClass", $ofClass);
			$objDef->setField ("bAcceptsAll", $bAcceptsAll);
			$objDef->Store();

			return ($objClass);
		}

		return (false);
	}

	// ===================================
	
	Function getClass ($thsName = "", $szGUID="")
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$objClasses = $this->getClasses();
		
		if (strLen ($szGUID) <> 0)
		{
			$objClass = $this->getItem ($szGUID);
			return ($objClass);
		}
		
		if (strLen ($thsName) <> 0)
		{
			$objClass = $objClasses->getFolder ($thsName, Globals::cClass_Ladder_Class());
			return ($objClass);
		}	
	}
	
	Function OSQL ($szOSQL, $bVerbose)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		$query = new \ENetArch\Ladder\OSQL\vm ();
		reutrn ($query->exec ($szOSQL, $bVerbose));
	}
}


	// ================================
	// Updates
	/*
		2009-10-01 	added getObject
		
	*/

?>
