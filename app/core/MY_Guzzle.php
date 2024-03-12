<?php defined('BASEPATH') or exit('No direct script access allowed');

abstract class MY_Guzzle extends CI_Controller
{

	protected $client;
	protected $channel;
	protected $base_uri;
	protected $active_log 	= true;
	protected $start_log 	= false; //format H:i:s
	protected $duration_log = false; //dalam menit
	protected $marketplace 	= 'Berrybenka';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('guzzle');
		$this->client = new GuzzleHttp\Client(['base_uri' => $this->base_uri]);
	}

	public function get($endpoint, $options = [])
	{
		try {
			$response = count($options) == 0 ? $this->client->get($endpoint) : $this->client->get($endpoint, $options);
			$data = $response->getBody()->getContents();

			$this->logs($endpoint, $data, 'IN');
			if ($response->getStatusCode() == 200) {
				return json_decode($data, true);
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$this->logs($endpoint, json_encode($e->getMessage()), 'IN');
			return $e->getMessage();
		}
	}

	public function post($endpoint, $body)
	{
		try {
			$options = [
				'body' => $body,
			];

			$response = $this->client->post($endpoint, $options);
			$data = $response->getBody()->getContents();
			$this->logs($endpoint, $data, 'OUT');
			if ($response->getStatusCode() == 200) {
				return json_decode($data, true);
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$this->logs($endpoint, json_encode($e->getMessage()), 'OUT');
			return $e->getMessage();
		}
	}

	private function logs($endpoint = null, $data = null, $type = 'OUT')
	{
		if ($this->active_log === true && $endpoint != null && $data != null) {

			$time_log = '';
			if ($this->start_log !== false && $this->duration_log !== false) {
				$time 		= date_create($this->start_log)->format('H:i:s');
				$endTime 	= strtotime("+{$this->duration_log} minutes", strtotime($time));
				$time_log 	= date('H:i:s', $endTime);
			}

			switch ($this->marketplace) {
				case 'Berrybenka':
					if ($time_log == '') {
						cron_log($type, $this->base_uri . '' . $endpoint, $data, $this->channel);
					} else {
						if (date_create('now')->format('H:i:s') <= $time_log && date_create('now')->format('H:i:s') >= date_create($this->start_log)->format('H:i:s')) {
							cron_log($type, $this->base_uri . '' . $endpoint, $data, $this->channel);
						}
					}
					break;
				default:
					return false;
					break;
			}
		}
		return true;
	}
}
