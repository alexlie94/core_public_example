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

	public function callback_url_post()
	{
		// $this->Auth();
		$this->load->library('queue_berrybenka');

		$jsonArray  = file_get_contents('php://input');
		$data       = (array)json_decode($jsonArray);

		$this->queue_berrybenka->shop_authorization_push($jsonArray);
		$this->response(array(
			"status" => true,
		), 200);

		// if ($data) {
		// 	switch ($data['code']) {

		// 		case 1:
		// 			$this->queue->shop_authorization_push($jsonArray);
		// 			$this->response(array(
		// 				"status" => true,
		// 			), 200);
		// 			break;

		// 		case 12:
		// 			$this->queue->shop_authorization_push($jsonArray);
		// 			$this->response(array(
		// 				"status" => true,
		// 			), 200);
		// 			break;

		// 		case 8:
		// 			$this->queue->reserved_stock_change_push($jsonArray);
		// 			$this->response(array(
		// 				"status" => true,
		// 			), 200);
		// 			break;

		// 		case 3:
		// 			$this->queue->order_status_push($jsonArray);
		// 			$this->response(array(
		// 				"status" => true,
		// 			), 200);
		// 			break;

		// 		case 4:
		// 			$this->queue->order_trackingno_push($jsonArray);
		// 			$this->response(array(
		// 				"status" => true,
		// 			), 200);
		// 			break;

		// 		case 15:
		// 			$this->queue->shipping_document_status_push($jsonArray);
		// 			$this->response(array(
		// 				"status" => true,
		// 			), 200);
		// 			break;

		// 		case 10:
		// 			$this->queue->webchat_push($jsonArray);
		// 			$this->response(array(
		// 				"status" => true,
		// 			), 200);
		// 			break;

		// 		default:
		// 			# code...
		// 			break;
		// 	}
		// } else {
		// 	$this->response(array(
		// 		"status" => false,
		// 	), 500);
		// }
	}

	private function Auth()
	{
		$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
		$pass    = isset($_GET['pass']) ? $_GET['pass'] : '';
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

	public function callback_shopee_get()
	{
		$this->load->library('queue_shopee');
		$xx = $this->queue_shopee->tess();
		echo "asdasd " . $xx;
		die;
	}
}
