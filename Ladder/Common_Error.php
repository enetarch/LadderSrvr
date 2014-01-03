<?
class ENetArch_Common_Error
{
   Private $errObjectName = "Error";
   
   Private $szDescription = "";
   Private $nNumber = 0;
   Private $szSource  = "";
   Private $szCallPath  = "";
   Private $szSQL  = "";
   Private $bCleared = true;

   // =============================================

   Public function Clear()
   {
      If ($this->bCleared) return (false);
   
      $this->szDescription = "";
      $this->nNumber = 0;
      $this->szSource = "";
      $this->szCallPath = "";
      $this->szSQL = "";
   
      $this->bCleared = True;
   }

   //  ===========================================

   function init($szDesc, $nNo, $szSrc, $szPath, $szSQLc)
   {   
      $this->szDescription = $szDesc;
      $this->nNumber = $nNo;
      $this->szSource = $szSrc;
      $this->szCallPath = $szPath;
      $this->szSQL = $szSQLc;
   
      $this->bCleared = False;
   }

	function toArray ()
	{
		$aryError = Array ();
		
		$aryError ["nNumber"] = $this->nNumber;
		$aryError ["szDescription"] = $this->szDescription;
		$aryError ["szSource"] = $this->szSource;
		$aryError ["szCallPath"] = $this->szCallPath;
		$aryError ["szSQL"] = $this->szSQL;
		
		return ($aryError);
	}

   //  ===========================================

   function CopyErr()
   {   
      $this->szDescription = $errstr;
      $this->nNumber = $errno;
      $this->szSource = $errfile . " " . errline;
      $this->szCallPath = "";
      $this->szSQL = "";

      $this->bCleared = False;
   }

   function Addpath ($newPath)
   {
      If (strlen(trim($newPath)) == 0) return (false);
      If ($this->bCleared) return (true);
   
      $this->szCallPath .= " " . $newPath;
      return (true);
   }

   //  ===========================================

   function No()
   { return $this->nNumber; }

   function Description()
   { return $this->szDescription; }

   function Number()
   { return $this->nNumber; }

   function Source()
   { return $this->szSource; }

   function CallPath()
   { return $this->szCallPath; }

   function SQL() 
   { return $this->szSQL; }
}
?>
