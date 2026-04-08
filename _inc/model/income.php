<?php

class ModelIncome extends Model 
{
	public function getTotalIncome($from, $to, $store_id = null) 
	{	
		$store_id = $store_id ? $store_id : store_id();

		// Income
		$where_query = "`bank_transaction_price`.`store_id` = '$store_id' AND `transaction_type` IN ('deposit') AND `income_sources`.`type` = 'credit'";
		if ($from) {
			$where_query .= date_range_accounting_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `income_sources` ON (`bank_transaction_info`.`source_id` = `income_sources`.`source_id`)
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`)
			WHERE  {$where_query}");
		$statement->execute(array());
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		$income = isset($row['total']) ? $row['total'] : 0;
		return $income;
	}

	public function getTotalSubstractIncome($from, $to, $store_id = null) 
	{	
		$store_id = $store_id ? $store_id : store_id();
		$income = $this->getTotalIncome($from, $to, $store_id);

		// Substract
		$where_query = "`bank_transaction_price`.`store_id` = '$store_id' AND `is_substract` = 1";
		if ($from) {
			$where_query .= date_range_accounting_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`)
			WHERE  {$where_query}");
		$statement->execute(array());
		$substract = $statement->fetch(PDO::FETCH_ASSOC);
		$substract = isset($substract['total']) ? $substract['total'] : 0;
		return $income - $substract;
	}

	public function getTotalSourceIncome($source_id, $from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`bank_transaction_price`.`store_id` = '$store_id' AND `transaction_type` IN ('deposit') AND `bank_transaction_info`.`source_id` = '{$source_id}' AND `income_sources`.`type` = 'credit'";
		if ($from) {
			$where_query .= date_range_accounting_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `income_sources` ON (`bank_transaction_info`.`source_id` = `income_sources`.`source_id`)
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`)
			WHERE  {$where_query}");
		$statement->execute(array());
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		$income = isset($row['total']) ? $row['total'] : 0;
		return $income;
	}

	public function getTotalSubstractSourceIncome($source_id, $from, $to, $store_id = null) 
	{	
		$store_id = $store_id ? $store_id : store_id();
		$income = $this->getTotalSourceIncome($source_id, $from, $to, $store_id);

		// Substract
		$where_query = "`bank_transaction_price`.`store_id` = '$store_id' AND `bank_transaction_info`.`source_id` = '{$source_id}' AND `is_substract` = 1";
		if ($from) {
			$where_query .= date_range_accounting_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `income_sources` ON (`bank_transaction_info`.`source_id` = `income_sources`.`source_id`)
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`)
			WHERE  {$where_query}");
		$statement->execute(array());
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		$substract = isset($row['total']) ? $row['total'] : 0;
		return $income - $substract;
	}

	public function getTotalExpense($from, $to, $store_id = null) 
	{	
		$store_id = $store_id ? $store_id : store_id();
		// $where_query = "`bank_transaction_price`.`store_id` = '$store_id' AND `transaction_type` IN ('withdraw') AND `is_substract` != 1";
		$where_query = "`bank_transaction_price`.`store_id` = '$store_id' AND `transaction_type` IN ('withdraw') AND `bank_transaction_info`.`is_hide` != 1";
		if ($from) {
			$where_query .= date_range_accounting_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `expense_categorys` ON (`bank_transaction_info`.`exp_category_id` = `expense_categorys`.`category_id`)
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`)
			WHERE  {$where_query}");
		$statement->execute(array());
		$income = $statement->fetch(PDO::FETCH_ASSOC);
		$total = isset($income['total']) ? $income['total'] : 0;
		return $total;
	}

	public function getTotalCategoryExpense($exp_category_id, $from, $to, $store_id = null) 
	{	
		$store_id = $store_id ? $store_id : store_id();
		// $where_query = "`bank_transaction_price`.`store_id` = '$store_id' AND `transaction_type` IN ('withdraw') AND `bank_transaction_info`.`exp_category_id` = '$exp_category_id' AND `is_substract` != 1";
		$where_query = "`bank_transaction_price`.`store_id` = '$store_id' AND `transaction_type` IN ('withdraw') AND `bank_transaction_info`.`exp_category_id` = '$exp_category_id' AND `bank_transaction_info`.`is_hide` != 1";
		if ($from) {
			$where_query .= date_range_accounting_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `expense_categorys` ON (`bank_transaction_info`.`exp_category_id` = `expense_categorys`.`category_id`)
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`)
			WHERE  {$where_query}");
		$statement->execute(array());
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['total']) ? $row['total'] : 0;
	}

	public function getTotalProfit($from, $to, $store_id = null) 
	{	
		$total = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`bank_transaction_price`.`store_id` = '$store_id' AND `transaction_type` IN ('deposit') AND `income_sources`.`type` = 'credit' AND `income_sources`.`profitable` = 'yes'";
		if ($from) {
			$where_query .= date_range_accounting_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT `income_sources`.`for_sell`, `bank_transaction_price`.`amount` as `total` FROM `bank_transaction_info` 
			LEFT JOIN `income_sources` ON (`bank_transaction_info`.`source_id` = `income_sources`.`source_id`)
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`)
			WHERE  $where_query GROUP BY `income_sources`.`source_id`");
		$statement->execute(array());
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rows as $row) {
			if($row['for_sell'] == 1) {
	          $total += get_profit_amount($from,$to);
	        } else {
	        	$total += $row['total'];
	        }
		}
		return $total;
	}
}