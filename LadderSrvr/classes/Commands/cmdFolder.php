<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace Commands;

class cmdFolder // Factory Class
{
	static public function Count ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		return ( $objFolder->count ($aryParams[1], $aryParams[2], $aryParams[3]) );
	} 
	
	static public function Item ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		$objItem = $objFolder->item ($aryParams[1], $aryParams[2], $aryParams[3]);
		if ($objItem != null) 
			return ( $objItem->getState() );
	} 
	
	static public function Delete ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		return ( $objFolder->Delete() );
	} 

	static public function  getChildren ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		$objAll = $objFolder->getChildren ($aryParams[1], $aryParams[2], $aryParams[3], $aryParams[4], $aryParams[5]);
		return ( $objAll->getState() );
	} 

	static public function  Exists ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		// Function Exists ($szName ="", $aryClasses=Array(), $nBaseType=0)
		return ( $objFolder->exists ($aryParams[1], $aryParams[2], $aryParams[3]) );		
	} 

	// ========================================

	static public function  SubFolders ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		// Function SubFolders ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
		$thsFolders = $objFolder->SubFolders ($aryParams[1], $aryParams[2], $aryParams[3], $aryParams[4]);		
		return ( $thsFolders->getState() );
	} 
	
	
	static public function Items ($aryParams)
	{
		gblTraceLog()->Log ("cmdFolder.Items.aryParams", $aryParams);
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		gblTraceLog()->Log ("cmdFolder.Items.objFolder", $objFolder->GUID());
		// Function Items ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
		$thsItems = $objFolder->Items ($aryParams[1], $aryParams[2], $aryParams[3], $aryParams[4]);		
		gblTraceLog()->Log ("cmdFolder.Items.thsItems", $thsItems);
		return ( $thsItems->getState() );
	} 
	
	static public function References ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		// Function References ($szName ="", $aryClasses=Array(), $nOrderBy=0, $bASC=true)
		$thsReferences = $objFolder->References ($aryParams[1], $aryParams[2], $aryParams[3], $aryParams[4]);		
		return ( $thsReferences->getState() );
	} 
	
	
	// ========================================

	static public function getFolder ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		// Function getFolder ($szName ="", $nClass=0, $thsObject=null)
		$thsFolder = $objFolder->getFolder ($aryParams[1], $aryParams[2]);		
		return ( $thsFolder->getState() );
	} 
	
	static public function  getItem ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		// Function getItem ($szName ="", $nClass=0, $thsObject=null)
		$thsItem = $objFolder->getItem ($aryParams[1], $aryParams[2]);		
		return ( $thsItem->getState() );
	} 
	
	static public function  getReference ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		// Function getReference ($szName ="", $nClass=0, $thsObject=null)
		$thsRef = $objFolder->getReference ($aryParams[1], $aryParams[2]);		
		return ( $thsRef->getState() );
	} 
	
	// ========================================
	
	static public function  Create_Folder ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		// Function Create_Folder ($szName, $szDescription, $nClass, $thsObject=null)
		$thsFldr = $objFolder->Create_Folder ($aryParams[1], $aryParams[2], $aryParams[3]);		
		return ( $thsFldr->getState() );
	} 
	
	static public function  Create_Item ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		// Function Create_Item ($szName, $szDescription, $nClass, $thsObject=null)
		$thsItem = $objFolder->Create_Item ($aryParams[1], $aryParams[2], $aryParams[3]);		
		return ( Array 
		(
			"State" => $thsItem->getState(),
			"Data" => $thsItem->getData(),
		) );
	} 
	
	static public function  Create_Reference ($aryParams)
	{
		$objFolder = gblLadder()->getItem ($aryParams[0]);
		// Function Create_Reference ($szName, $szDescription, $nClass, $thsFolder, $thsObject=null)
		$thsRef = $objFolder->Create_Reference ($aryParams[1], $aryParams[2], $aryParams[3], $aryParams[4]);		
		return ( $thsRef->getState() );
	} 
}
?>
