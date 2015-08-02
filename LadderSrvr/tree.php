<?
session_start ();

require_once "classes/ENetArch/Common/docFoo1.php";
require_once "init/error_handler.php";
require_once "classes/ENetArch/Common/Functions.php";
require_once "init/autoloader.php";
require_once "init/global_Error.php";
require_once "init/global_TraceLog.php";

use \ENetArch\Common\RPC;
use \ENetArch\Service\Service;

const bDebugging = true;

$szJSON = (isset ($_POST ["szJSON"]) ? $_POST ["szJSON"] : "{}");

// print ("_POST = " . $szJSON . "<BR>");

$rpc = new RPC ();
$rpc->process ( $szJSON, new \ENetArch\Service\Service () );
print ($rpc->getResults());

?>
