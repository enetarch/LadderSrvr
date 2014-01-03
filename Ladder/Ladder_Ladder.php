<?
/*	=======================================
	Copyright 1998 - 2013 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

   Include_Once ("Ladder_Globals.php");
   Include_Once ("Ladder_Properties.php");
   Include_Once ("Ladder_Links.php");
   Include_Once ("Ladder_Folders.php");
   Include_Once ("Ladder_Folder.php");
	Include_Once ("Ladder_Item.php");
   Include_Once ("Ladder_Reference.php");
   Include_Once ("Ladder_Version.php");

Class ENetArch_Ladder
{

	// ===================================
	// Structure
	/*
				01	ID INTEGER
				02	Name varChar (40)
				03	Description varChar (250)
				04	Parent Integer
				05	BaseType Integer
				06	Class Integer
				07	Leaf Integer
				08  dCreated Date
	*/
	
	// ===================================
	// Constants
	 
	Function cTableName () { return (ENetArch_Ladder_Globals::cLadder_Table_Links() ); }

	// ===================================
	// Variables
	
	var $cn = null;  // link to database
	var $fldrClasses = null ; // Location of Classes

	// ===================================
	// Functions

	Function __Construct ()
	{	}

	Function ENetArch_Ladder () { ENetArch_Ladder::__Construct(); }
	
	
	Function Connect ($szODBC)
	{
		$szODBC2 = str_replace (";" , "&", $szODBC);
		parse_str($szODBC2, $aryODBC);
      $this->cn = mysql_connect ($aryODBC['HOST'], $aryODBC['UID'], $aryODBC['PSW']);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn));
      
      mysql_select_db ($aryODBC['DBF'], $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn));
	}

	Function Disconnect ()
	{
		mysql_close ($this->cn);
		$this->cn = null;
	}

	// ====================================
	
	function isInstalled ()
	{
		$szSQL = "SHOW TABLES";

		$rs = mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn));
		
		$bFound = false;
		while (( $row = mysql_fetch_row($rs) ) && ( ! $bFound) )
		{
			if ($row [0] == ENetArch_Ladder::cTableName() )
				$bFound = true;
		}
		
		mysql_free_result($rs);
		
		return ($bFound);
	}
	
	Function Install ()
	{
		$szSQL =
			" CREATE TABLE IF NOT EXISTS " . ENetArch_Ladder::cTableName() .
			" ( " .

				" ID Integer AUTO_INCREMENT, " .		// 0
				" Primary Key (ID), " .
				" Name varChar (40), " .				// 1
				" Description varChar (250), " .	// 2
				" Parent Integer, " .  					// 3
				" BaseType Integer, " . 				// 4
				" Class Integer, " .  						// 5
				" Leaf Integer, " . 							// 6
				" dCreated TimeStamp, " .			// 7
				" INDEX (Parent, Class) " .
			" ) ";

		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn));
		
		$this->Create_Roots();
		$this->Install_Classes();
		
		return (true);
	}

	function unInstall ()
	{
		$szSQL = "DROP TABLE " . ENetArch_Ladder::cTableName();

		mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn));
		
		return (true);
	}
	

	// ====================================

	Function Create_Roots ()
	{
		Global $gblLadder;

		// =======================
		// create root folder

		if (! $gblLadder->Root_Exists ("Root Folder", ENetArch_Ladder_Globals::cClass_RootFolder()))
		{
			$objFolder = $gblLadder->Create_Root
				("Root Folder", "This is the Root of my Site", 
					ENetArch_Ladder_Globals::cClass_RootFolder());
			$objFolder->Store();
		}

		// =======================
		// create root classes folder

		if (! $gblLadder->Root_Exists ("Root Classes", ENetArch_Ladder_Globals::cClass_RootClasses()))
		{
			$objFolder = $gblLadder->Create_Root
				("Root Classes", "This stores the Names of my Classes", 
					ENetArch_Ladder_Globals::cClass_RootClasses());
			$objFolder->Store();
		}
	}

	Function Install_Classes()
	{
		// ==================================
		// Base Classes

		$szStr =
			" nClassID Integer, " .
			" nBaseType Integer, " .
			" szTable varChar (40), " .
			" ofClass Integer, " .
			" bAcceptsAll BIT " ;

		$this->create_Class ("Ladder_Defination", ENetArch_Ladder_Globals::cisItem(), 0 , true, $szStr);

		$this->create_Class ("Ladder_Folder",  ENetArch_Ladder_Globals::cisFolder(), 0 , true);
		$this->create_Class ("Ladder_Class", ENetArch_Ladder_Globals::cisFolder(), 0 , true);

		$this->create_Class ("RootFolder", ENetArch_Ladder_Globals::cisRoot(), 0 , true);
		$this->create_Class ("RootClasses", ENetArch_Ladder_Globals::cisRoot(), 0 , true);

	}

	// ====================================
	
	Function CreateTable ($thsName, $szStr)
	{
		$szSQL =
			" CREATE TABLE IF NOT EXISTS " . $thsName .
			" ( " .
				" ID Integer AUTO_INCREMENT, " .		// 0
				" Primary Key (ID), " .
				$szStr .
			" ) ";
			
			if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
			mysql_query ($szSQL, $this->cn);
			If (mysql_errno ($this->cn) != 0) 
				print(mysql_error ($this->cn) . "<P>");
	}

	// ====================================
	
	Function getItem ($nID)
	{
		// print("Ladder_Ladder . getItem . ($nID) <BR>");
		
		$szSQL =
			" SELECT * " .
			" FROM "  . ENetArch_Ladder::cTableName() .
			" WHERE " .
				" ID = " . $nID;
		
		if ($this->cn == null) 
			print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		
		$rs = mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print (mysql_error ($this->cn) . "<P>");

		$rowCount = mysql_num_rows ($rs);

		$objItem = null;

		if ($rowCount > 0) 
		{
			$row = mysql_fetch_row($rs);

			switch ($row [4])
			{
				case ENetArch_Ladder_Globals::cisRoot() :
				{	$objItem = new ENetArch_Ladder_Folder ();
				} break;

				case ENetArch_Ladder_Globals::cisFolder() :
				{	$objItem = new ENetArch_Ladder_Folder ();
				} break;

				case ENetArch_Ladder_Globals::cisItem() :
				{	$objItem = new ENetArch_Ladder_Item ();
				} break;

				case ENetArch_Ladder_Globals::cisReference() :
				{	$objItem = new ENetArch_Ladder_Reference ();
				}	break;
			}

			if ($objItem != null)
			{
				$objItem->Connect ($this->cn);
				$objItem->setState ($row);
			}
		}

		mysql_free_result($rs);
		
		return ($objItem);
	}

	// ====================================
	
	Function getObject ($nID, $thsObject)
	{
		$szSQL =
			" SELECT * " .
			" FROM "  . ENetArch_Ladder::cTableName() .
			" WHERE " .
				" ID = " . $nID;
		
		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . "<P>");

		$rowCount = mysql_num_rows ($rs);

		if ($rowCount > 0) 
		{
			$row = mysql_fetch_row($rs);

			$thsObject->Connect ($this->cn);
			$thsObject->setState ($row);
		}

		mysql_free_result($rs);
		
		return ($thsObject);
	}

	// ===================================

	function update_Link ($thsState)
	{
		$objProperties = new ENetArch_Ladder_Properties ();
		$objProperties->connect ($this->cn);
		$objProperties->setState ($thsState);
		$nID = $objProperties->Store ();
		
		return ( $nID );
	}
	
	// ===================================

	function update_Item ($thsState, $thsData)
	{
		$objItem= new ENetArch_Ladder_Item ();
		$objItem->connect ($this->cn);
		$objItem->setState ($thsState);
		$objItem->setData ($thsData);
		$nID = $objItem->Store ();
		
		return ( $nID );
	}
	
	// ===================================

	Function getRoots ($thsName = "", $nClass = 0) // as Array
	{
		$szSQL =
			" SELECT * " .
			" FROM "  . ENetArch_Ladder::cTableName() .
			" WHERE " .
				" BaseType = " . ENetArch_Ladder_Globals::cisRoot() ;
		
		if (strLen (trim ($thsName)) != 0)
			$szSQL .= " AND Name = '" . $thsName . "' ";

		if ($nClass != 0)
			$szSQL .= " AND Class = " . $nClass . " ";

		if ($this->cn == null) print ("CN == NULL, object = " . $this->ID() . " - " . $this->Name() . "<BR>");
		$rs = mysql_query ($szSQL, $this->cn);
		If (mysql_errno ($this->cn) != 0) 
			print(mysql_error ($this->cn) . "<P>");


		$rowCount = mysql_num_rows ($rs);
      $aryIDs = Array ();
      for ($t=0; $t<$rowCount; $t++)
      {
         $row = mysql_fetch_row ($rs);
         // $aryIDs[$t+1] = $row["0"]; --> original row
         $aryIDs[] = $row;
      }

      mysql_free_result($rs);
      $rs = null;
      
      $rootFolders = new ENetArch_Ladder_Folders ();
      $rootFolders->connect ($this->cn);
      $rootFolders->setState ($aryIDs);
      
      return ($rootFolders);
	}

	// ===================================

	Function Create_Root ($szName, $szDesc, $nClass)
	{
		$objItem = new ENetArch_Ladder_Folder ();
		$objItem->Connect ($this->cn);
		$objItem->Create (null, $szName, $szDesc, $nClass);

		return ($objItem);
	}
	
	// ===================================

	Function Root_Exists ($thsName, $nClass = 0)
	{
		$rootFolders = $this->getRoots ($thsName, $nClass);
		
		return ( $rootFolders->count () > 0);
	}
	
	// ===================================

	Function getClasses ()
	{
		Global $gblLadder;
		Global $fldrClasses;

		if ($fldrClasses == null)
		{
			$aryRoots = $gblLadder->getRoots();
			$fldrClasses = $aryRoots->getItem (2);
		}
		
		return ($fldrClasses);
	}
	
	// ===================================

	Function create_Class ($thsName, $nBaseType, $ofClass, $bAcceptsAll, $szStr="")
	{
		Global $gblLadder;
		$objClasses = $this->getClasses();

		$szTableName = $thsName;
		
		if ($nBaseType == ENetArch_Ladder_Globals::cisItem())
			if ($ofClass == 0)
			{ $this->CreateTable ($thsName, $szStr); }
			else
			{
				$clsOfClass = $gblLadder->getItem ($ofClass);
				$defOfClass = $clsOfClass->getItem ("", ENetArch_Ladder_Globals::cClass_Ladder_Defination());
				$szTableName = $defOfClass->Field ("szTable");
			}

		$aryClasses = Array (1=>ENetArch_Ladder_Globals::cClass_Ladder_Class());
		if (! $objClasses->Exists ($thsName, $aryClasses))
		{
			$objClass = $objClasses->Create_Folder
				($thsName, "Class", ENetArch_Ladder_Globals::cClass_Ladder_Class());
			$objClass->Store();

			$objDef = $objClass->Create_Item2 ($thsName, "Defination", ENetArch_Ladder_Globals::cClass_Ladder_Defination(), "Ladder_Defination");
			$objDef->setField ("nClassID", $objClass->ID());
			$objDef->setField ("nBaseType", $nBaseType);
			$objDef->setField ("szTable", $szTableName);
			$objDef->setField ("ofClass", $ofClass);
			$objDef->setField ("bAcceptsAll", $bAcceptsAll);
			$objDef->Store();

			return ($objClass);
		}

		return (false);
	}

	// ===================================
	
	Function getClass ($thsName = "", $nClassID = 0)
	{
		Global $gblLadder;
		$objClasses = $this->getClasses();
		
		if ($nClassID <> 0)
		{
			$objClass = $this->getItem ($nClassID);
			return ($objClass);
		}
		
		if (strLen ($thsName) <> 0)
		{
			$objClass = $objClasses->getFolder ($thsName, ENetArch_Ladder_Globals::cClass_Ladder_Class());
			return ($objClass);
		}	
	}

	// ===================================
	
	Function Version ()
	{		return ( new ENetArch_Ladder_Version () );	}
}


	// ================================
	// Updates
	/*
		2009-10-01 	added getObject
		
	*/

?>
