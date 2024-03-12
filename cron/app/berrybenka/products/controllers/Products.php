<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Guzzle
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
		$this->load->model('Products_model');
		$this->model = $this->Products_model;
		date_default_timezone_set("Asia/Jakarta");

		$this->maxData 		= 5000;
	}


	public function get_product($brandID = null)
	{
		$limit 	= 100;
		$offset = 0;

		try {
			$detailBrand 	= $this->validate_brand($brandID);
			$brand 			= $detailBrand->id;
			$startDate 		= $this->start_date($brand, 'users_ms_products');

			$condition 	= true;
			$nomer 		= 1;

			while ($condition) {

				$parameters = array(
					'limit' 		=> $limit,
					'offset' 		=> $offset,
					'start_date' 	=> $startDate,
					'brand' 		=> $brandID,
				);

				$endPoint 	= '/api/product/all' . '?' . http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
				$options 	= array(
					'headers' => array(
						'Content-Type' 	=> 'application/json',
						'Authorization' => 'Bearer ' . md5(md5(date('Y-m-d'))),
					)
				);

				$data = $this->get($endPoint, $options);

				if ($data['status'] == false) {
					echo "No Data";
					break;
				}
				$totalRows = $data['total_row'];
				$this->db->trans_begin();
				try {
					foreach ($data['data'] as $ky => $val) {
						$whereprod = array(
							'product_code' => $val['product_id'],
						);
						$data_products = $this->model->get($whereprod, 'users_ms_products');

						if (!$data_products) {

							$where = array(
								'brand_code' => $val['brand_id'],
							);
							$data_brand = $this->model->get($where, 'users_ms_brands');

							$whereSupp = array(
								'supplier_code' => $val['product_supplier'],
							);
							$data_supplier = $this->model->get($whereSupp, 'users_ms_suppliers');

							$wherecat = array(
								'categories_code' => $val['category_id'],
							);
							$data_categories = $this->model->get($wherecat, 'users_ms_categories');
							if ($data_categories) {
								$insertData = array(
									'users_ms_companys_id' 			=> 1,
									'product_code' 					=> $val['product_id'],
									'product_name' 					=> $val['product_name'],
									'gender' 						=> $val['gender'] ? $val['gender'] : "undefined",
									'product_price' 				=> $val['product_price'],
									'product_sale_price' 			=> $val['product_sale_price'],
									'users_ms_brands_id' 			=> $data_brand->id,
									'brand_name' 					=> $val['brand_name'],
									'users_ms_suppliers_id' 		=> $data_supplier->id,
									'users_ms_categories_id' 		=> $data_categories->id,
									'category_name' 				=> $val['category_name'] ? $val['category_name'] : $data_categories->categories_name,
									'users_ms_ownership_types_id' 	=> $val['product_ownership_id'],
									'product_description' 			=> $val['product_description'],
									'product_info' 					=> $val['product_info'],
									'product_last_modified' 		=> $val['product_last_modified'],
									'created_by' 					=> 'System',
								);
								$this->db->insert('users_ms_products', $insertData);
								$last_product_id = $this->db->insert_id();
								echo "@@# " . $val['items'][0]['general_color_id'] . "<br>";
								foreach ($val['items'] as $kyitems => $items) {
									echo "@@# " . $items['general_color_id'] . " == " . $items['sku'] . "<br>";
									if ($items['general_color_id'] || $items['general_color_id'] <> '' || $items['general_color_id'] <> 'NULL') {
										$insertDataitems = array(
											'users_ms_companys_id'	=> 1,
											'users_ms_products_id' 	=> $last_product_id,
											'sku' 					=> $items['sku'],
											'general_color_id' 		=> isset($items['general_color_id']) ? $items['general_color_id'] : 0,
											'variant_color_id' 		=> $items['variant_color_id'],
											'variant_color_name' 	=> $items['hexa'] ? $items['hexa'] : "000000",
											'product_size' 			=> $items['product_size'],
											'enabled' 				=> $items['enabled'],
											'created_by' 			=> 'API',
										);
										$this->db->insert('users_ms_product_variants', $insertDataitems);
										$last_variant_id = $this->db->insert_id();

										$insertDatainventory = array(
											'users_ms_companys_id' 	=> 1,
											'users_ms_brands_id' 	=> $data_brand->id,
											'products_id' 			=> $last_product_id,
											'product_variants_id' 	=> $last_variant_id,
											'inventory_id' 			=> $items['inventory_id'] ? $items['inventory_id'] : 0,
											'sku' 					=> $items['sku'],
											'quantity' 				=> $items['quantity'],
											'created_by'			=> 'API',
										);
										$this->db->insert('users_ms_product_bb_inventories', $insertDatainventory);
									}
								}

								foreach ($val['images'] as $kyimage => $image) {
									$insertDataimages = array(
										'users_ms_companys_id' 	=> 1,
										'users_ms_products_id' 	=> $last_product_id,
										'general_color_id' 		=> isset($val['items'][0]['general_color_id']) ? $val['items'][0]['general_color_id'] : 0,
										'variant_color_id' 		=> isset($val['items'][0]['variant_color_id']) ? $val['items'][0]['variant_color_id'] : 0,
										'image_name' 			=> $image['image_name'],
										'image_file' 			=> $image['image_name'],
										'created_by'			=> 'API',
									);
									$this->db->insert('users_ms_product_images', $insertDataimages);
								}
							} else {
								echo "Product telah terdaftar";
							}
						}
						$nomer++;
					}
					$this->db->trans_commit();
					return true;
				} catch (Exception $e) {
					$this->db->trans_rollback();
					return false;
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
		$start_date 	= $this->model->getLastModified($table, $brand);
		if (!$start_date || (isset($start_date->last_modified) && ($start_date->last_modified == null || $start_date->last_modified == '0000-00-00 00:00:00'))) {
			$startDate = '2014-08-23 00:00:00';
		} else {
			$date = date_create($start_date->last_modified);
			date_add($date, date_interval_create_from_date_string('-2 days'));
			$startDate  = date_format($date, 'Y-m-d H:i:s');
		}

		return $startDate;
	}
}
