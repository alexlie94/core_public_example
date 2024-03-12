<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prices_batch extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('queue');
	}

	public function index()
	{
		$sql = $this->db->query("SELECT
										`t0`.`id` AS `batchs_id`,
										`t1`.`id` AS `batchs_detail_id`,
										`t0`.`batch_name` AS `batch_name`,
										`t0`.`users_ms_companys_id` AS `users_ms_companys_id`,
										`t0`.`admins_ms_sources_id` AS `admins_ms_sources_id`,
										`t0`.`end_date_status` AS `end_date_status`,
										`t1`.`users_ms_products_id` AS `users_ms_products_id`,
										`t1`.`price` AS `price`,
										`t1`.`sale_price` AS `sale_price`,
										`t1`.`offline_price` AS `offline_price`,
										`t0`.`start_date` AS `start_date`,
										`t0`.`end_date` AS `end_date`,
										TIMEDIFF(NOW(), `t0`.`start_date`) AS `time_diff`,
										(TIME_TO_SEC(TIMEDIFF(NOW(), `t0`.`start_date`)) * 1000) AS `time_diff_milliseconds`
								FROM
									`ims_live`.`users_ms_batchs` `t0`
								JOIN
									`ims_live`.`users_ms_batchs_detail` `t1` ON
									`t1`.`users_ms_batchs_id` = `t0`.`id`
								WHERE
										`t0`.`start_date` >= NOW() - INTERVAL 1 HOUR
									AND t0.sync_status = 1
								");

		$result = $sql->result();

		if ($result) {
			foreach ($result as $rows) {
				$push = $this->queue->prices_push($rows);
				if ($push) {
					try {
						$this->db->trans_begin();
						$this->db->query("UPDATE users_ms_batchs SET sync_status =2 WHERE id ='$rows->batchs_id' ");
						$this->db->trans_commit();
					} catch (Exception $e) {
						$this->db->trans_rollback();
					}
				}
			}
		} else {
			echo 'Data Not Found';
		}
	}
}
