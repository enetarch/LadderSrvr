<?
session_start ();

require_once "classes/ENetArch/Common/docFoo1.php";
require_once "init/error_handler.php";
require_once "classes/ENetArch/Common/Functions.php";
require_once "init/autoloader.php";
require_once "init/global_Error.php";
require_once "init/global_TraceLog.php";

const bDebugging = true;

$szOSQL = (isset ($_POST ["szOSQL"]) ? $_POST ["szOSQL"] : "");
$bVerbose = 
	isset ($_POST ["bVerbose"]) 
	? ($_POST ["bVerbose"] == "1") 
	: false ;

$bVerbose = true;

use \ENetArch\Service\Query;

$query = new \ENetArch\Service\Query ();
print_r ($query->execute ($szOSQL, $bVerbose));

print ("<p>=============================</p>");

print ("[ " . gblError()->No() . " ] " . gblError()->Description() . " @ ". gblError()->Source() . "<p> SQL = " . gblError()->SQL() . "<p>");

// print (implode ("<BR>\r\n", gblTraceLog()->getLog ()));

?>
