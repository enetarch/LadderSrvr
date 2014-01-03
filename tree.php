<?
/*	=======================================
	Copyright 1998 - 2013 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

// ====================================================================
/*

Commands
	Ladder
		getRoots ()
		getRoot  (id)
		
		getPath (szPath)
		getObject (id, path)

		xmlExport (id)
		xmlImport (id, data)

		query (n, szOSQL)

		install ()
		uninstall ()
		isInstalled ()

		Delete (obj)

		create_Folder ()
		create_Item ()
		create_Reference ()

		create_Class (szClass, basetype, )
		 
		duplicate (obj, folder)
		copy (obj, folder)
		move (obj, folder)
		
		exists (szPath)
		
		getClassRoot ()
		getClass (id, name)
		 
		getFolder (id)
		getItem (id)
		getReference (id)

		find (name, description, filter, orderby)
		
		
		
	Folders
		Delete (nPos)
		DeleteAll ()
		Item (nPos)
		Count (name, class, basetype)
		Find (name, class, basetype)

		copyAll (folder)
		moveAll (folder)
		duplicateAll (folder)
		
	Folder
		ID ()
		Name ()
		Description ()
		Parent ()
		getClassID ()
		getClassName ()
		getProperties ()
		getPath ()
		
		Delete ()
		copyTo (folder)
		moveTo (folder)
		duplicateTo (folder)
		
		Overloads ()
		addOverload (class) 
		removeOverload (class)
		
		
		IDs (name, class, basetype, orderby)
		Count (name, class, basetype, orderby)
		
		Children (class, basetype, orderby)
		ChildrenIDs (class, basetype, orderby)
		hasChildren (class, basetype)
		
		Item (n, class, basetype, orderby)
		
		create_Folder (name, description, class)
		Folders
		Folder (n, class)
		
		create_Item (name, description, class, data)
		Items
		Item (n, class)
		
		create_Reference (name, description, class, folder)
		References
		Reference (n, class)

	Items
		Delete (nPos)
		DeleteAll ()
		Item (nPos)
		Count (name, class, basetype)
		Find (name, class, basetype)

		copyAll (folder)
		moveAll (folder)
		duplicateAll (folder)

	Item
		ID ()
		Name ()
		Description ()
		Parent ()
		getClassID ()
		getClassName ()
		getProperties ()
		getPath ()
		
		Delete ()
		copyTo (folder)
		moveTo (folder)
		duplicateTo (folder)

		xmlExport
		xmlImport

	References
		Delete (nPos)
		DeleteAll ()
		Item (nPos)
		Count (name, class, basetype)
		Find (name, class, basetype)

		copyAll (folder)
		moveAll (folder)
		duplicateAll (folder)

	Reference
		ID ()
		Name ()
		Description ()
		Parent ()
		getClassID ()
		getClassName ()
		getProperties ()
		getPath ()
		
		getFolder ()
		update (folder)
		
		Delete ()
		copyTo (folder)
		moveTo (folder)
		duplicateTo (folder)

		xmlExport
		xmlImport
	

	Classes
	Class
	 
	
	
	

*/
// ====================================================================

include_once ("shared/_app.php");
include_once ("Ladder/ENetArch.php");

$aryResults = Array 
(
	"return" => "false",
	"error" => null
);

if (! isset ($_POST ["json"]) )
{
	$gblError->init ("tree requires POST", -1, "tree.php", "tree", "");
	exitTree ($aryResults);
}

// ====================================================================

$szJSON  = (isset ($_POST["json"]) ) ? $_POST["json"] : "";

$aryPOST = json_decode ($szJSON, true);

// ====================================================================

$szCommand = $aryPOST ["command"];
$bVerbose = $aryPOST ["verbose"];
$szParams = $aryPOST ["params"];

// ====================================================================

$gblError = ENetArch::Common_Error ();

$gblLadder = ENetArch::Ladder();
if ($gblError->No() != 0)
	exitTree ($aryResults);

$gblLadder->Connect ($szODBC);
if ($gblError->No() != 0)
	exitTree ($aryResults);
	
// ====================================================================

$rtn = 0;

switch ($szCommand)
{
	case "Ladder:Version" : $rtn = $gblLadder->Version ()->VersionNo (); break;
	
	case "Ladder:isInstalled" : $rtn = $gblLadder->isInstalled (); break;
	case "Ladder:Install" : $rtn = $gblLadder->Install (); break;
	case "Ladder:unInstall" : $rtn = $gblLadder->unInstall (); break;
	
	// ========================================
	
	case "Ladder:getItem" : 
	{
		$objItem = $gblLadder->getItem ($szParams[0]); 
		$rtn = $objItem->getState();
	} break;

	
	case "Ladder:getRoots" : 
	{
		$rootFolders = $gblLadder->getRoots (); 
		$rtn = $rootFolders->getState();
	} break;
	
	case "Ladder:getRoot" : 
	{
		$rootFolder = $gblLadder->getRoot ($szParams[0]); 
		$rtn = $rootFolder->getState();
	} break;

	case "Ladder:getFolder" : $rtn = $gblLadder->getFolder ($szParams[0]); break;
	case "Ladder:getItem" : $rtn = $gblLadder->getItem ($szParams[0]); break;
	case "Ladder:getReference" : $rtn = $gblLadder->getReference ($szParams[0]); break;

	// ========================================

	case "Ladder:create_Class" : 
	{
		$fldrClass = $gblLadder->create_Class ($szParams[0], $szParams[1], $szParams[2], $szParams[3], $szParams[4] );
		$rtn = $fldrClass->getState();
	} break;


	// ========================================

	case "Ladder:getClasses" : 
	{
		$fldrClasses = $gblLadder->getClasses ();
		$rtn = $fldrClasses->getState();
	} break;

	case "Ladder:getClass" : 
	{
		$fldrClass = $gblLadder->getClass ($szParams[0], $szParams[1]); 
		$rtn = $fldrClass->getState();
	} break;
	
	// ========================================
	
	case "Folder:Count" : 
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		$rtn = $objFolder->count($szParams[1], $szParams[2], $szParams[3]);
	} break;
	
	case "Folder:Item" : 
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		$objItem = $objFolder->item($szParams[1], $szParams[2], $szParams[3]);
		if ($objItem != null) 
			$rtn = $objItem->getState();
	} break;
	
	case "Folder:Delete" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		$rtn = $objFolder->Delete();
	} break;

	case "Folder:getChildren" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		$objAll = $objFolder->getChildren($szParams[1], $szParams[2], $szParams[3], $szParams[4], $szParams[5]);
		$rtn = $objAll->getState();
	} break;

	case "Folder:Exists" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function Exists ($szName ="", $aryClasses=Array(), $nBaseType=0)
		$rtn = $objFolder->exists($szParams[1], $szParams[2], $szParams[3]);		
	} break;

	// ========================================

	case "Folder:getFolders" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function getFolders ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
		$thsFolder = $objFolder->getFolders ($szParams[1], $szParams[2], $szParams[3], $szParams[4]);		
		$rtn = $thsFolder->getState();
	} break;
	
	
	case "Folder:getItems" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function getItems ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
		$thsFolder = $objFolder->getItems ($szParams[1], $szParams[2], $szParams[3], $szParams[4]);		
		$rtn = $thsFolder->getState();
	} break;
	
	case "Folder:getReferences" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function getReferences ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
		$thsFolder = $objFolder->getReferences ($szParams[1], $szParams[2], $szParams[3], $szParams[4]);		
		$rtn = $thsFolder->getState();
	} break;
	
	
	// ========================================

	case "Folder:getFolder" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function getFolder ($szName ="", $nClass=0, $thsObject=null)
		$thsFolder = $objFolder->getFolder ($szParams[1], $szParams[2]);		
		$rtn = $thsFolder->getState();
	} break;
	
	case "Folder:getItem" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function getItem ($szName ="", $nClass=0, $thsObject=null)
		$thsItem = $objFolder->getItem ($szParams[1], $szParams[2]);		
		$rtn = $thsItem->getState();
	} break;
	
	case "Folder:getReference" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function getReference ($szName ="", $nClass=0, $thsObject=null)
		$thsRef = $objFolder->getReference ($szParams[1], $szParams[2]);		
		$rtn = $thsRef->getState();
	} break;
	
	// ========================================
	
	case "Folder:Create_Folder" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function Create_Folder ($szName, $szDescription, $nClass, $thsObject=null)
		$thsFldr = $objFolder->Create_Folder ($szParams[1], $szParams[2], $szParams[3]);		
		$rtn = $thsFldr->getState();
	} break;
	
	case "Folder:Create_Item" :
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function Create_Item ($szName, $szDescription, $nClass, $thsObject=null)
		$thsItem = $objFolder->Create_Item ($szParams[1], $szParams[2], $szParams[3]);		
		$rtn = Array 
		(
			"State" => $thsItem->getState(),
			"Data" => $thsItem->getData(),
		);
		
	} break;
	
	case "Folder:Create_Reference" : break;
	{
		$objFolder = $gblLadder->getItem ($szParams[0]);
		// Function Create_Reference ($szName, $szDescription, $nClass, $thsFolder, $thsObject=null)
		$thsRef = $objFolder->Create_Reference ($szParams[1], $szParams[2], $szParams[3], $szParams[4]);		
		$rtn = $thsRef->getState();
	} break;
	
	
	// ========================================

	case "Item:Delete" :
	{
		$objItem = $gblLadder->getItem ($szParams[0]);
		$rtn = $objItem->Delete();
	} break;

	case "Item:getData" : 
	{
		$objItem = $gblLadder->getItem ($szParams[0]);
		$rtn = $objItem->getData();
	} break;

	case "Item:saveData" : $rtn = $gblLadder->update_Item ($szParams [0], $szParams [1]); break;
	
	// ========================================
	
	case "Properties:Store" : $rtn = $gblLadder->update_Link ($szParams [0]); break;

	// ========================================
	
	default :
		$gblError->Clear();
		$gblError->init ("No Such Command", -1, "tree.php", "tree", $szCommand);
}

$aryResults ["return"] = $rtn;

exitTree ($aryResults);

// ==========================================

function exitTree ($aryResults)
{
	global $gblError;
	
	$aryResults ["error"] = $gblError->toArray();

	$szReturn = json_encode ($aryResults, JSON_FORCE_OBJECT);

	print ($szReturn);

	exit ();
}
?>
