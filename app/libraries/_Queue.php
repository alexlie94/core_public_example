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

class Queue
{

	/**
	 * Confirguration
	 * Default configuration intialized from queue config file
	 */
	//  $host = 'localhost';
	// var $port = '1572';
	// var $user = 'admin';
	// var $pass = 'admin';
	var $host 	= 'rabbitmq.berrybenka.biz';
	var $port 	= 1572;
	var $user 	= 'publish';
	var $pass 	= 'publish';
	var $vhost 	= '/shopee';
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

		// Configuration
		if (!empty($config)) {
			$this->initialize($config);
		}

		// Connecting to message server
		$this->connection = AMQPStreamConnection::create_connection([
			['host' => 'rabbit1.internal.berrybenka.com', 'port' => 5672, 'user' => 'publish', 'password' => 'publish', 'vhost' => '/shopee'],
			['host' => 'rabbit2.internal.berrybenka.com', 'port' => 5672, 'user' => 'publish', 'password' => 'publish', 'vhost' => '/shopee']
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

		$this->channel->basic_publish($msg, 'shopee_exchange', 'shopee_auth');
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


	public function pull($job, $data = array(), $route = null)
	{
		$this->channel->queue_declare($route, false, true, false, false);

		$callback = function ($msg) {
			echo " [x] Received ", $msg->body, "\n";
		};

		$this->channel->basic_consume($route, '', false, true, false, false, $callback);
		while (count($this->channel->callbacks)) {
			$this->channel->wait();
		}

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
}
/* End of file queue.php */
/* Location: ./application/libraries/queue.php */
