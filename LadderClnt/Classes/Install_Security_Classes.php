<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

	function dirPath () { return ("../../"); }
	Include_Once (dirPath() . "Shared/_app.inc");

Function php_Main ()
{
	Include_Once ("Install_Functions.inc");
	Global $gblLadder;

	// ==================================
	//	Security
	//
	//	Security
	//		Groups
	//			Group
	//				User
	//				Policies
	//		Users
	//			User
	//				Policies
	//				ACLs
	//					ACL
	//						User
	//						Group
	//						ACL_Rights
	//				Login

	CreateClass ("Security_Security", ldrGlobals::cisFolder(), 0 , true);
		CreateVerb ("Security_Security", "PHP", "View",  "Security/Security/View.php");
		CreateVerb ("Security_Security", "PHP", "New",  "Security/Security/New.php");
		CreateForm ("Security_Security", "View",  "Security/Security/View.php");
	//->
		CreateClass ("Security_Groups", ldrGlobals::cisFolder(), 0 , true);
		//->
			CreateClass ("Security_Group",  ldrGlobals::cisFolder(), 0 , true);
			//->
				//"Security_UserRef" Reference Class "?"
				CreateClass ("Security_UserRef",  ldrGlobals::cisReference(), 0 , true);

				//"Security_Policies" Folder Class "Site_Policies"
				$clsSite_Policies = $gblLadder->getClass ("Site_Policies")->ID();
				CreateClass ("Security_Policies", ldrGlobals::cisFolder(), $clsSite_Policies , true);

			CreateClass ("Security_Users", ldrGlobals::cisFolder(), 0 , true);
			//->
				CreateClass ("Security_User", ldrGlobals::cisFolder(), 0 , true);
				//->
					//"Security_Policies" Already Created

					CreateClass ("Security_ACLs",  ldrGlobals::cisFolder(), 0 , true);
					//->
						CreateClass ("Security_ACL", ldrGlobals::cisFolder(), 0 , true);
						//->
							//"Security_UserRef" Already Created

							//"Security_GroupRef" Reference Class "?"
							CreateClass ("Security_GroupRef",  ldrGlobals::cisReference(), 0 , true);

							$szStr =
								" bCreate bool, " .
								" bRead bool, " .
								" bUpdate bool, " .
								" bDelete bool, " .
								" bList bool, " .
								" bExec bool ";
							CreateClass ("Security_ACLRights", ldrGlobals::cisItem(), 0 , true, $szStr);

					$szStr =
						" dLogin DateTime, " .
						" szPassword varChar(40), " .
						" bActive Bit, " .
						" dInActive Bit, " .
						" szEmail varChar(250) ";
					CreateClass ("Security_Login", ldrGlobals::cisItem(), 0 , true, $szStr);

return ;
}

?>
