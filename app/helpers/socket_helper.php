<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require '../../vendor/autoload.php';


use ElephantIO\Client, ElephantIO\Engine\SocketIO\Version2X;

if (!function_exists("socket_io")) {
	function socket_io($host)
	{
		$client = new Client(new Version2X($host));

		return $client;
	}
}
