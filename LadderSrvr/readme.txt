/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */


== What is provided: ==

Ladder Server is 90% functional.  This solution serves as a demonstration
server at this time.  A full fledge server will be published when 
applications are written for Ladder.

== Configuration: ==

This version of Ladder works in a LAMP environment.  
First create a database for Ladder to work in.  
The default is "ENetArch_Ladder"

Browse to "Classes / Config / mysql_resource.php".  Adjust the settings
as needed:
	Server Host
	User Name
	Password
	DataBase Name

== Executing Ladder Commands / Queries: ==

You can communicate with Ladder using two interfaces.  The first is an 
API, and the second is through a 4th generation language - OSQL.

=== Ladder API Library ===

Ladder can receive API calls via RPC calls from another server or from 
another client.  Ladder Client, the API RPC interface provided in PHP,
will be distributed in Java, JavaScript, PHP, and C# in the coming 
revisions.  Calls made by this API library are sent to "tree.php", using 
a JSON format:

{
	"szCommand" : "cmdLadder.isInstalled",
	"szParams" :  [],
	"bVerbose" : false
}

To issue these commnands manual, for testing purposes, use "tree.html"

For a list of commands supported, browse to: "Classes / Commands"

=== OSQL Interface ===

Ladder uses an OSQL Interface to execute queries through the Ladder Server
Library at this time.  Coming is an OSQL to SQL version library, as well
as additional libraries to map Ladder objects into a Memory File, vs using
a SQL server for storage.

This library is not complete.  Many of the features have yet to completely
coded.  What was insured to function were simple AND queries in the WHERE
clause.

Future versions will migrate the debugging code to debug statements, as 
applications are built on top of Ladder.

== Documentation ==

At this time, DocFoo is not complete.  My goal is to complete DocFoo
within a month of this publication.

== Testing == 

At this time, Testing is not complete.  My goal is to provide detailed
tests by January 2016.


== Supporting This Project ==

If you appreciate the work being put into this project and want to help 
support it. Please contact E Net Arch via info@enetarch.net, 248-686-1407,
or via Skype as ENetArch.

