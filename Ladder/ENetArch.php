<?
include_once ("Common_Error.php");
include_once ("Ladder_Ladder.php");
include_once ("LadderSrvr_Version.php");

Class ENetArch
{
	public static function Common_Error ()
	{
		if (class_exists ("ENetArch_Common_Error") )
			return (new ENetArch_Common_Error ());
		return (null);
	}

	public static function Ladder ()
	{
		if (class_exists ("ENetArch_Ladder") )
			return (new ENetArch_Ladder ());
		return (null);
	}

	public static function Ladder_Server ()
	{
		if (class_exists ("ENetArch_LadderSrvr_Version") )
			return (new ENetArch_LadderSrvr_Version ());
		return (null);
	}
}

?>
