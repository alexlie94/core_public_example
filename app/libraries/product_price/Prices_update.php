<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Prices_update
{
	protected $ci;
	protected $db;
	protected $queue;

	public function __construct()
	{
		$this->ci 		= &get_instance();
		$this->ci->load->library('queue');
		$this->db 		= $this->ci->db;
		$this->queue 	= $this->ci->queue;

		date_default_timezone_set('UTC');
	}

	function process_price_update($result, $messageId, $show_error = 1)
	{
		try {
			switch ($messageId) {
				case '99':
					echo "## " . $messageId . "<br>";
					pre($result);
					break;

				default:
					# code...
					break;
			}
		} catch (Exception $e) {
			if ($show_error) {
				echo $e->getMessage();
			}
			return false;
		}
	}
}
