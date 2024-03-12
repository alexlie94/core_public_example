<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
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

class Queue_berrybenka
{

	/**
	 * Confirguration
	 * Default configuration intialized from queue config file
	 */
	var $host = 'localhost';
	var $port = 15672;
	var $user = 'guest';
	var $pass = 'guest';

	var $vhost 	= '/berrybenka';
	/**
	 * Connection
	 */
	var $connection = null;

	/**
	 * Channel
	 */
	var $channel = null;


	/**
	 * Constructor with configuration array
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
		$this->_instance = &get_instance();
		$this->_instance->load->model('consumer/consumer_model', 'm_consumer', TRUE);

		// Configuration
		if (!empty($config)) {
			$this->initialize($config);
		}

		// Connecting to message server
		$this->connection = AMQPStreamConnection::create_connection([
			['host' => 'localhost', 'port' => 5672, 'user' => 'guest', 'password' => 'guest', 'vhost' => '/berrybenka']
			// ['host' => 'rabbit2.internal.berrybenka.com', 'port' => 5672, 'user' => 'publish', 'password' => 'publish', 'vhost' => '/shopee']
		], ['keepalive' => true, 'heartbeat' => 30]);

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
	public function shop_authorization_push($data = array())
	{
		if (is_array($data)) {
			$data = json_encode($data);
		}

		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2, # make message persistent
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'berrybenka_exchange', 'po_order');
		return true;
	}

	public function reserved_stock_change_push($data = array())
	{
		if (is_array($data)) {
			$data = json_encode($data);
		}

		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2, # make message persistent
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'shopee_exchange', 'shopee_reserved_stock');
		return true;
	}

	public function order_status_push($data = array())
	{
		if (is_array($data)) {
			$data = json_encode($data);
		}

		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2, # make message persistent
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'shopee_exchange', 'shopee_order');
		return true;
	}


	public function order_trackingno_push($data = array())
	{
		if (is_array($data)) {
			$data = json_encode($data);
		}

		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2, # make message persistent
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'shopee_exchange', 'shopee_order_tracking');
		return true;
	}


	public function shipping_document($data = array())
	{
		if (is_array($data)) {
			$data = json_encode($data);
		}

		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2, # make message persistent
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'shopee_exchange', 'shopee_shipping_document');
		return true;
	}


	public function webchat_push($data = array())
	{
		if (is_array($data)) {
			$data = json_encode($data);
		}

		$msg = new AMQPMessage(
			$data,
			array(
				'delivery_mode' => 2, # make message persistent
				'application_headers' => new AMQPTable([
					'x-delay' => 5000
				])
			)
		);

		$this->channel->basic_publish($msg, 'shopee_webchat_exc', 'shopee_webchat_push');
		return true;
	}


	public function pull_orders()
	{
		$callback = function ($msg) {

			$array 			= json_decode($msg->body);


			$process_order 	=  $this->_instance->m_consumer->process_order($array);

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
		$this->channel->basic_consume('po_order', '', false, false, false, false, $callback);
		$this->channel->consume();
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
}
/* End of file queue.php */
/* Location: ./application/libraries/queue.php */
