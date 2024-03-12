<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends MY_Guzzle
{

	private $maxData;

	public function __construct()
	{
		$this->base_uri 	= 'http://wms.benka.co';
		$this->active_log 	= true;
		$this->start_log 	= '00:00:00';
		$this->duration_log = '10';
		$this->channel 		= 1;
		$this->marketplace 	= 'Berrybenka';

		parent::__construct();
		$this->load->model('Inventory_model');
		$this->model = $this->Inventory_model;
		date_default_timezone_set("Asia/Jakarta");

		$this->maxData 		= 5000;
	}

	private function validate_brand($brand = null)
	{
		try {
			if ($brand == null) {
				throw new Exception("Brand ID must be required", 1);
			}

			$getBrand    = getBrand($brand);

			if (!$getBrand) {
				throw new Exception("Brand ID not found", 1);
			}

			return $getBrand;
		} catch (Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}

	private function start_date($brand, $table)
	{
		$start_date = $this->model->getLastModified($table, $brand);
		$time_default = '2014-08-23 00:00:00';
		if (!$start_date || (isset($start_date->last_modified) && ($start_date->last_modified === null || $start_date->last_modified === '0000-00-00 00:00:00'))) {
			$startDate = $time_default;
		} else {
			if ($start_date->last_modified !== null) {
				$date = date_create($start_date->last_modified);
				date_add($date, date_interval_create_from_date_string('-2 days'));
				$startDate = date_format($date, 'Y-m-d H:i:s');
			} else {
				$startDate = $time_default;
			}
		}

		return $startDate;
	}

	public function update_inventory($brandID = null)
	{
		$limit = 50;
		$offset = 0;
		try {

			$detailBrand = $this->validate_brand($brandID);
			$brand = $detailBrand->id;

			//check data variant 
			$check = $this->model->get(array('users_ms_brands_id' => $brand), 'users_ms_products');
			if (!$check) {
				echo "No Data in Product Inventory";
				exit();
			}

			$startDate = $this->start_date($brand, 'users_ms_product_bb_inventories');

			$condition = true;
			$nomer = 1;


			while ($condition) {
				$parameters = array(
					'limit' => $limit,
					'offset' => $offset,
					'start_date' => $startDate,
					'brand' => $brand,
				);

				$endPoint = '/api/product/update_inventory' . '?' . http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
				$options = array(
					'headers' => array(
						'Content-Type' => 'application/json',
						'Authorization' => 'Bearer ' . md5(md5(date('Y-m-d'))),
					)
				);
				$data = $this->get($endPoint, $options);

				if ($data['status'] == false) {
					echo "No Data";
					break;
				}
				$totalRows = $data['total_row'];

				foreach ($data['data'] as $ky => $val) {

					$where = array(
						'inventory_id' => $val['inventory_id'],
						'sku' => $val['sku'],
					);

					$processData = array(
						'quantity' => $val['quantity'],
						'last_modified' => $val['last_modified'],
					);

					$check = $this->model->get($where, 'users_ms_product_bb_inventories');
					if ($check) {

						$msg = '';
						$result = $this->model->processUpdate($where, $processData, 'users_ms_product_bb_inventories', $msg);
						if ($result == false) {
							throw new Exception($msg, 1);
						}
						$msgResult = "berhasil di update";
					} else {

						$msgResult = 'belum terdaftar';
					}

					echo $nomer . ". " . "SKU : " . $val['sku'] . " " . $msgResult . " <br>";
					$nomer++;
				}

				if ($nomer > $this->maxData) {
					echo "Break karna akan melampaui maksimal Data <br>";
					break;
				}

				$offset += $limit;
				$positionBreakTotal = $totalRows - $offset;
				if ($positionBreakTotal <= 0) {
					echo "Break karna Data Sudah ditarik semua";
					break;
				}
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
}
