<?php

require_once APPPATH . '/libraries/REST_Controller.php';
class Publisher extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index_get()
	{
		$this->Auth();
		echo "Publisher....";
		die;
	}

	// public function callback_url_post()
	// {
	// 	$this->Auth();
	// 	$this->load->library('queue');

	// 	$jsonArray = file_get_contents('php://input');
	// 	$data = (array) json_decode($jsonArray);

	// 	if ($data) {
	// 		switch ($data['code']) {

	// 			case 3:
	// 				$this->queue->shopee_order_push($jsonArray);
	// 				$this->response(
	// 					array(
	// 						"status" => true,
	// 					),
	// 					200
	// 				);
	// 				break;

	// 			default:
	// 				# code...
	// 				break;
	// 		}
	// 	} else {
	// 		$this->response(
	// 			array(
	// 				"status" => false,
	// 			),
	// 			500
	// 		);
	// 	}
	// }

	private function Auth()
	{
		$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
		$pass = isset($_GET['pass']) ? $_GET['pass'] : '';
		$my_user = 'oneNewapp';
		$my_pass = 'EFX23009DSSDYUIIUI';

		if ($user_id <> $my_user || $my_pass <> $pass) {
			$response = array(
				'message' => 'error authorization.. ',
				'error' => true,
			);
			$this->response($response, 403);
			die;
		}
	}
}