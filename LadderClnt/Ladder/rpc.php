<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

include_once ("Error.php");

class rpc
{
	private $szURL = "192.168.0.35";
	private $objError = null;

	public function connect ($szURL)
	{
		$this->szURL = $szURL;
	}
		
	public function disconnect () 
	{
		$this->szURL = "";
	}

	public function exec ($data)
	{
		if ($this->szURL == "") 
			return (-1);
			
		$data_string = "szJSON=" . json_encode($data) . "&";
		print ("<P> rpc call = " . $data_string . "</P>");

		$ch = curl_init("http://" . $this->szURL . "/LadderSrvr/tree.php");
		
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		$results = curl_exec($ch);
		curl_close ($ch);
		
		
		print ("<P> rpc results = " . $results . "</P>");
		
		$json = json_decode ($results, true);
		
		$json["szCommand"] = $data;
		print ("<P>rpc = <pre>");
		print_r ($json);
		print ("</pre></P>");

		$this->objError = new Error ( $json ["error"] );
		
		return ( $json ["return"] );
	}
	
	public function getError ()
	{ return ($this->objError); }

}

?>
