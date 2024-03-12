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

class Queue_shopee
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
			['host' => 'localhost', 'port' => 5672, 'user' => 'admin', 'password' => 'admin', 'vhost' => '/shopee']
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

	public function tess()
	{
		return 'okeee';
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
