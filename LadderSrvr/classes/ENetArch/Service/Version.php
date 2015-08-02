<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

Namespace ENetArch\Ladder

class Version
{
   const szVersion = "1.0.0";
   const szCompiled = "2008-03-12 - 1:00 pm";
   const szCompiledBy = "Michael J. Fuhrman";

   function Version() 
   {  return self::szVersion; }

   function Compiled() 
   {  return self::szCompiled; }

   function CompiledBy() 
   {   return self::szCompiledBy; }


   // =============================================

   // Version History
/*
   2008-03-12 - began the process of converting Ladder v3.0 VB
      to Ladder PHP

*/

}

?>
