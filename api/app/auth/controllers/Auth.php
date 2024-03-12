<?php
require_once APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'third_party/vendor/autoload.php';
class Auth extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('api');
		$this->load->model('Auth_model');
		$this->model = $this->Auth_model;
	}

	public function index_get()
	{
		echo "tes";
	}

	public function tiktok_get()
	{
		$auth = $this->model->auth_tiktok();
		// print_r($auth);
		if ($auth['success'] === true) {
			redirect(BASE_URL . '/integrations/success_page');
		} else {
			print_r($auth);
		}
	}

	public function shopee_get($channel_id)
	{
		$auth = $this->model->auth_shopee($channel_id);
		// print_r($auth);
		if ($auth['success'] === true) {
			redirect(BASE_URL . '/integrations/success_page');
		} else {
			redirect(BASE_URL . '/integrations/error_page');
		}
	}

	public function socket_io_get()
	{
		$this->load->helper('socket');
		try {
			$client = socket_io('https://websocket.onedeca.com');
			$client->initialize();
			$client->emit("new_order", ["test" => "test", "test1" => "test1"]);
			$client->close();
		} catch (ElephantIO\Exception\ServerConnectionFailureException $e) {
			echo $e->getErrorMessage();
		}
	}

	public function tes_order_toped_get()
	{
		$this->load->library('toped_decrypt');
		$secret 	= "DXtg0w8HfIZDHRTnn6OYMfhhIkgqH9OAhwLMlVNscH3J6criMYDqM+8KkDnFlK1VsSWf97RWGvdOdJ+Fl8aD1J1MrNm3tgKm25crsUcYr9nAPM2hE8WtB9Z70S5EKtHbDvglSwIUwpU02lIxwC8LtJWbcHkFuv6t3V7VLaZw33COhdDJtrw7jO1NLzzyWxPUSfps87vPy7RikTG7NrYh8K1BGdOAhJUgFqMKZxQSH6d2X8dRqijCIUoEMcA2d+Gj5S19w/jX3cvO/xM8uwhG1pjz6BKp5sLpes6Whk/zm6uGFsI24wUQA0SNo0AsonEe9fLKHGPNVrRIZv2bbwnpTw==";
		$content 	= "/msWvDpgCYAkvwiJW1bRdehziiHeyAQfRVZuh+et1AotAEWO5l4fKFM3kEBxzReE7GoilPNSeWL9IRaL9EcG2RX30J3Arpk+a1Lgy+ZPgebO+salq1HtiX8hD6VSyas5GkqHd8GpQa9UaVqBkWwaah7OgEQNkdCL1ZE69gfuoEfOELjbKQwdf/yYTVZVyivDBpVxulft7fWnUpptwLQnafXjZC9rm57ezuO4KTqpvd8IHyJdXNofVbzCsebRLjOPKEPutWUOZYt+G53HOpjJgR6pAYGUCfom/6e8aeHXbVGET8NKtXd3Wh2MCqXoUMsceezXrn3fdZ3G27UOyo9yfVN+JkO7YILT+AccTkXZKK14rN4wW0n5f61KB+SufgOTAgotF0BfQYspm9/XTD2+5qsrMIHa+fNAU6DACrtsfIJxmC709ahMTCBTWgsF8HnMSqAgfFhIKKt5Mynel4u6IZZvckIZlUon/2MOPAk5Eme+cm7VU2u7sKOHetvF1eFyovb6cWCDMhKi/F5yGPAofIKcxg+1bIsq5b6Y6nFm+Qi3EMKw5hnYFHDIUOojhN3oCyhbRZnQ0NWpamnWDWs8Q+8V2BwyWGDX0A9ygDdpjHpddVEFl55M1NVu2J59QSO5yMVif/NOIwxa3uqbkv2VaHEBFKdLH1Rbt06FF1R5ZLE3t06cNxkBAFnuyoAS7iLnQzHvQ8/FCrb8eE50kXmM26bmMa1fB/c1X+PnZuO0SG9Msw93T5SbFRqxQeV2jRL/pZG0wyDsCoFnLaqX8YeGEX0y6h6/wBtuNI57a67lj0pZb6yVnPwwxDcHo8E9Vpp8d9E8bQBmQnI4ta36pFPn6osU4o0qJfG4W+5IbOOR3Y6fpQ==";
		$jsonString = $this->toped_decrypt->getContent($secret, $content);
		pre($jsonString);
	}
}
