<?php if (!defined("BASEPATH"))
	exit("No direct script access allowed");
/**
 * @name		  CodeIgniter Message Queue Library using PHP PHP-AMQPLib Client
 * @author		Jogi Silalahi
 * @link		  http://jogisilalahi.com
 *
 * This message queue library is a wrapper CodeIgniter library using PHP-AMQPLib
 */

require APPPATH . 'third_party/vendor/autoload.php';

use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\Heartbeat\PCNTLHeartbeatSender;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class Queue
{


	/**
	 * Confirguration
	 * Default configuration intialized from queue config file
	 */
	var $connection = null;

	/**
	 * Channel
	 */
	var $channel = null;

	var $debug_mode = 1;


	/**
	 * Constructor with configuration array
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{

		// $this->_instance = &get_instance();
		// $this->_instance->load->model('consumer/consumer_model', 'm_consumer', TRUE);


		// Configuration
		if (!empty($config)) {
			$this->initialize($config);
		}

		if (!$this->debug_mode) {
			$this->connection = AMQPStreamConnection::create_connection([

				['host' => 'localhost', 'port' => 5672, 'user' => 'publish', 'password' => 'publish', 'vhost' => '/ims'],
				['host' => 'localhost', 'port' => 5672, 'user' => 'publish', 'password' => 'publish', 'vhost' => '/ims']

			], ['keepalive' => true, 'heartbeat' => 30]);
		} else {
			$this->connection = AMQPStreamConnection::create_connection([
				['host' => 'localhost', 'port' => 5672, 'user' => 'publish', 'password' => 'publish', 'vhost' => '/ims'],
				['host' => 'localhost', 'port' => 5672, 'user' => 'publish', 'password' => 'publish', 'vhost' => '/ims']
			], ['keepalive' => true, 'heartbeat' => 30]);
		}


		if ($this->connection->getIO()->check_heartbeat() != NULL) {
			$sender = new PCNTLHeartbeatSender($this->connection);
			$sender->register();
		}
		$this->channel = $this->connection->channel();
	}

	/**
	 * Initialize with configuration array
	 *
	 * @param array $config
	 */
	public function initialize($config = array())
	{
		foreach ($config as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/**
	 * Queuing new message
	 *
	 * @param string $job
	 * @param array $data
	 * @param string $route
	 */
	public function tiktok_order_push($data = array())
	{
		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2,
				// 'message_id' => 'Shopee',
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'ims_exchange', 'tiktok_order');
		return true;
	}

	public function tiktok_order_pull()
	{
		$callback = function ($msg) {
			// echo '<pre>';
			// print_r($msg->get('message_id'));
			// die;
			$array = json_decode($msg->body);
			// print_r($array);
			$process_order = $this->_instance->m_consumer->process_order($array);

			if ($process_order) {
				$msg->ack();
			} else {
				$msg->nack();
			}
		};
		$this->channel->basic_qos(
			null,
			1,
			null
		);
		$this->channel->basic_consume('tiktok_order', '', false, false, false, false, $callback);
		$this->channel->consume();
	}

	public function shopee_order_push($data = array())
	{
		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2,
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'ims_exchange', 'shopee_order');
		return true;
	}

	public function shopee_order_pull()
	{
		$this->_instance = &get_instance();
		$this->_instance->load->library('shopee/orders');

		$callback = function ($msg) {
			$array = json_decode($msg->body);
			$process_order = $this->_instance->orders->process_order($array);

			if ($process_order) {
				$msg->ack();
			} else {
				$msg->nack();
			}
		};
		$this->channel->basic_qos(
			null,
			1,
			null
		);
		$this->channel->basic_consume('shopee_order', '', false, false, false, false, $callback);
		$this->channel->consume();
	}

	public function shipping_document_status_push($data = array())
	{
		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2,
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'ims_exchange', 'shopee_document_status');
		return true;
	}

	public function shipping_document_status_pull()
	{
		$this->_instance = &get_instance();
		$this->_instance->load->library('shopee/orders');

		$callback = function ($msg) {
			$array = json_decode($msg->body);
			$process_order = $this->_instance->orders->get_shipping_document_result_webhook($array);

			if ($process_order) {
				$msg->ack();
			} else {
				$msg->nack();
			}
		};
		$this->channel->basic_qos(
			null,
			1,
			null
		);
		$this->channel->basic_consume('shopee_document_status', '', false, false, false, false, $callback);
		$this->channel->consume();
	}


	public function tokopedia_order_push($data = array())
	{
		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2,
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'ims_exchange', 'tokopedia_order');
		return true;
	}

	public function tokopedia_order_pull()
	{
		$this->_instance = &get_instance();
		$this->_instance->load->library('tokopedia/orders');

		$callback = function ($msg) {
			$array = json_decode($msg->body);

			$process_order = $this->_instance->orders->process_order($array);

			if ($process_order) {
				$msg->ack();
			} else {
				$msg->nack();
			}
		};
		$this->channel->basic_qos(
			null,
			1,
			null
		);
		$this->channel->basic_consume('tokopedia_order', '', false, false, false, false, $callback);
		$this->channel->consume();
	}



	public function inventory_push($data = array())
	{
		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2,
				// 'message_id' => 'Shopee',
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'ims_exchange', 'all_inventory');
		return true;
	}

	/**
	 * Queuing scheduled message
	 *
	 * @param integer $delay
	 * @param array $job
	 * @param array $data
	 * @param string $route
	 */
	public function later($delay, $job, $data, $route = null)
	{
		// TODO: implement scheduled message
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{

		// Channel closing
		if ($this->channel) {
			$this->channel->close();
		}

		// Connection closing
		if ($this->connection) {
			$this->connection->close();
		}
	}

	public function shopee_prodcut_publish_push($data = array())
	{
		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2,
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'ims_exchange', 'product_publish');
		return true;
	}

	public function shopee_product_publish_pull()
	{
		$this->_instance = &get_instance();
		$this->_instance->load->library('shopee/product/Product_push', 'product_push', TRUE);

		$callback = function ($msg) {
			$array = json_decode($msg->body);
			$process_order = $this->_instance->product_push->push_product($array);

			if ($process_order) {
				$msg->ack();
			} else {
				$msg->nack();
			}
		};

		$this->channel->basic_qos(
			null,
			1,
			null
		);
		$this->channel->basic_consume('product_publish', '', false, false, false, false, $callback);
		$this->channel->consume();
	}

	public function prices_push($rows)
	{
		$object	= new stdClass();
		$object->data = $rows;
		$msg = new AMQPMessage(
			json_encode($object),
			array(
				'delivery_mode' => 2,
				'message_id' => $rows->admins_ms_sources_id,
				'application_headers' => new AMQPTable([
					'x-delay' => $rows->time_diff_milliseconds
				])
			)
		);

		$this->channel->basic_publish($msg, 'ims_exchange', 'prices_update');
		return true;
	}

	public function prices_pull()
	{
		$this->_instance = &get_instance();
		$this->_instance->load->library('product_price/prices_update');

		$callback = function ($msg) {
			$array 					= json_decode($msg->body);
			$messageId 				= $msg->get('message_id');
			$process_price_update 	= $this->_instance->prices_update->process_price_update($array, $messageId);

			if ($process_price_update) {
				$msg->ack();
			} else {
				$msg->nack();
			}
		};
		$this->channel->basic_qos(
			null,
			1,
			null
		);
		$this->channel->basic_consume('prices_update', '', false, false, false, false, $callback);
		$this->channel->consume();
	}

	public function single_publish_pull()
	{
		$this->_instance = &get_instance();
		$this->_instance->load->library('shopee/product/Product_push');

		$callback = function ($msg) {
			$decode_data_webhook = json_decode($msg->body);
			$process = $this->_instance->product_push->product_push($decode_data_webhook);

			if ($process) {
				$msg->ack();
			} else {
				$msg->nack();
			}
		};
		$this->channel->basic_qos(
			null,
			1,
			null
		);
		$this->channel->basic_consume('product_publish', '', false, false, false, false, $callback);
		$this->channel->consume();
	}
}
/* End of file queue.php */
/* Location: ./application/libraries/queue.php */
