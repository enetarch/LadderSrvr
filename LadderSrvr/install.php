<?
session_start ();

require_once "classes/ENetArch/Common/docFoo1.php";
require_once "init/error_handler.php";
require_once "classes/ENetArch/Common/Functions.php";
require_once "init/autoloader.php";
require_once "init/global_Error.php";
require_once "init/global_TraceLog.php";

const bDebugging = true;



$service = new \ENetArch\Service\Install ();
print ($service->execute ());

?>
