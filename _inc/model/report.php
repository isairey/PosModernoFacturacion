<?php

class ModelReport extends Model 
{
	public function getTax($type, $from, $to, $store_id = null) 
	{
		$tax = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`{$type}`) as total FROM `selling_info` 
		LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
		WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		if ($invoice) {
			$tax = $invoice['total'];
		}
		return $tax ? $tax : 0;
	}

	public function getInOrExclusiveTax($type, $from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = $store_id AND `selling_item`.`tax_method` = '{$type}'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`selling_item`.`item_tax`) as total FROM `selling_info` 
			LEFT JOIN `selling_item` ON (`selling_info`.`invoice_id` = `selling_item`.`invoice_id`) 
			WHERE $where_query GROUP BY `selling_info`.`invoice_id`");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getPurchaseTax($type, $from, $to, $store_id = null) 
	{
		$tax = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter2($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`{$type}`) as total FROM `purchase_info` 
		LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`) 
		WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		if ($invoice) {
			$tax = $invoice['total'];
		}
		return $tax ? $tax : 0;
	}

	public function getInOrExclusivePurchaseTax($type, $from, $to, $store_id = null) 
	{
		$tax = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_info`.`store_id` = $store_id AND `purchase_item`.`tax_method` = '{$type}'";
		$where_query .= date_range_filter2($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_item`.`item_tax`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_item` ON (`purchase_info`.`invoice_id` = `purchase_item`.`invoice_id`) 
			WHERE $where_query GROUP BY `purchase_info`.`invoice_id`");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		if ($invoice) {
			$tax = $invoice['total'];
		}
		return $tax;
	}

	public function getSellingPrice($from=null, $to=null, $store_id = null) 
	{
		$total = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT `selling_info`.`is_installment`, `selling_price`.`payable_amount` as payable_amount FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE $where_query");

		$statement->execute(array());
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rows as $invoice) {
			$total += $invoice['payable_amount'];
		}
		return $total;
	}

	public function getInterestAmount($from=null, $to=null, $store_id = null, $invoice_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id' AND `is_installment` = 1";
		if ($invoice_id) {
			$where_query .= " AND `selling_info`.`invoice_id` = '$invoice_id'";
		}
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`interest_amount`) as total FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array());
		$rows = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($rows['total']) ? $rows['total'] : 0;
	}

	public function getShippingCharge($from=null, $to=null, $store_id = null, $invoice_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		if ($invoice_id) {
			$where_query .= " AND `selling_info`.`invoice_id` = '$invoice_id'";
		}
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`shipping_amount`) as total FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getPurchaseShippingCharge($from=null, $to=null, $store_id = null, $invoice_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_info`.`store_id` = '$store_id'";
		if ($invoice_id) {
			$where_query .= " AND `purchase_info`.`invoice_id` = '$invoice_id'";
		}
		$where_query .= date_range_filter2($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`shipping_amount`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getOthersCharge($from=null, $to=null, $store_id = null, $invoice_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		if ($invoice_id) {
			$where_query .= " AND `selling_info`.`invoice_id` = '$invoice_id'";
		}
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`others_charge`) as total FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array());
		$rows = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($rows['total']) ? $rows['total'] : 0;
	}

	public function getPurchaseOthersCharge($from=null, $to=null, $store_id = null, $invoice_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_info`.`store_id` = '$store_id'";
		if ($invoice_id) {
			$where_query .= " AND `purchase_info`.`invoice_id` = '$invoice_id'";
		}
		$where_query .= date_range_filter2($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`others_charge`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array());
		$rows = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($rows['total']) ? $rows['total'] : 0;
	}

	public function getPurchasePriceOfSell($from=null, $to=null, $store_id = null) 
	{
		$total = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT `selling_info`.`invoice_id`, `selling_info`.`is_installment`, `selling_price`.`interest_percentage` as interest_percentage, `selling_price`.`paid_amount`, `selling_item`.`item_total` selling_total, `selling_item`.`item_purchase_price` as purchase_total FROM `selling_info` 
			LEFT JOIN `selling_item` ON `selling_info`.`invoice_id` = `selling_item`.`invoice_id`
			LEFT JOIN `selling_price` ON `selling_info`.`invoice_id` = `selling_price`.`invoice_id`
			WHERE $where_query");
		$statement->execute(array());
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rows as $invoice) {
			if ($invoice['is_installment']) {
				$paid_amount = $invoice['paid_amount'] - $this->getInterestAmount($from,$to,$store_id,$invoice['invoice_id']);
				$selling_total = $invoice['selling_total'];
				$purchase_total = $invoice['purchase_total'];
				$total += $selling_total ? (($purchase_total/$selling_total)*$paid_amount) : 0;
			} else {
				$paid_amount = $invoice['paid_amount'];
				$selling_total = $invoice['selling_total'];
				$purchase_total = $invoice['purchase_total'];
				$total += $selling_total ? (($purchase_total/$selling_total)*$paid_amount) : 0;
			}
		}
		return $total;
	}

	public function getReceivedAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		return $this->getPaidAmount($from, $to, $store_id) + $this->getAnothrDayDueCollectionAmount($from, $to, $store_id);
	}

	public function getSellReceivedAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		return $this->getPaidAmount($from, $to, $store_id);
	}

	public function getPaidAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`paid_amount`) as total FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getDiscountAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`discount_amount`) as total FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`)
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getPurchaseDiscountAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter2($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`discount_amount`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`)
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getDueAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`due`) as due FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`)
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['due']) ? $invoice['due'] : 0;
	}

	public function getPurchaseDueAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter2($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`due`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`)
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getPurchasePrice($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter2($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`payable_amount`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getPurchaseTotalPaidAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_info`.`store_id` = ?";
		$where_query .= date_range_filter2($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`paid_amount`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array($store_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getSellingReturnAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`returns`.`store_id` = '$store_id'";
		$where_query .= date_range_selling_return_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`returns`.`total_amount`) as total FROM `returns` 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getTaxReturnAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`returns`.`store_id` = '$store_id'";
		$where_query .= date_range_selling_return_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`returns`.`item_tax`) as total, SUM(`returns`.`cgst`) as cgst, SUM(`returns`.`sgst`) as sgst, SUM(`returns`.`igst`) as igst FROM `returns` 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return $invoice && $invoice['cgst'] <= 0 && $invoice['sgst'] <= 0 && $invoice['igst'] <= 0 ? $invoice['total'] : 0;
	}

	public function getGSTReturnAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`returns`.`store_id` = '$store_id'";
		$where_query .= date_range_selling_return_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`returns`.`cgst`) as cgst, SUM(`returns`.`sgst`) as sgst, SUM(`returns`.`igst`) as igst FROM `returns` 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return $invoice ? $invoice['cgst'] + $invoice['sgst'] + $invoice['igst'] : 0;
	}

	public function getPurchaseReturnAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_returns`.`store_id` = '$store_id'";
		$where_query .= date_range_purchase_return_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_returns`.`total_amount`) as total FROM `purchase_returns` 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getPurchaseTaxReturnAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_returns`.`store_id` = '$store_id'";
		$where_query .= date_range_purchase_return_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_returns`.`item_tax`) as total, SUM(`purchase_returns`.`cgst`) as cgst, SUM(`purchase_returns`.`sgst`) as sgst, SUM(`purchase_returns`.`igst`) as igst FROM `purchase_returns` 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return $invoice && $invoice['cgst'] <= 0 && $invoice['sgst'] <= 0 && $invoice['igst'] <= 0 ? $invoice['total'] : 0;
	}

	public function getPurchaseGSTReturnAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_returns`.`store_id` = '$store_id'";
		$where_query .= date_range_purchase_return_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_returns`.`cgst`) as cgst, SUM(`purchase_returns`.`sgst`) as sgst, SUM(`purchase_returns`.`igst`) as igst FROM `purchase_returns` 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return $invoice ? $invoice['cgst'] + $invoice['sgst'] + $invoice['igst'] : 0;
	}

	public function getExpenseAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`purchase_info`.`inv_type` = 'expense' AND `purchase_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter2($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`paid_amount`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array());
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getDueCollectionAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`type` = ? AND `store_id` = ?";
		$where_query .= date_range_sell_payments_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`amount`) as total FROM `payments` 
			WHERE $where_query");
		$statement->execute(array('due_paid', $store_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getAnothrDayDueCollectionAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`type` = ? AND `store_id` = ?";
		$where_query .= date_range_sell_payments_reverse_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`amount`) as total FROM `payments` 
			WHERE $where_query");
		$statement->execute(array('due_paid', $store_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getAnothrDayDuePaidAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`type` = ? AND `store_id` = ?";
		$where_query .= date_range_purchase_payments_reverse_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`amount`) as total FROM `purchase_payments` 
			WHERE $where_query");
		$statement->execute(array('due_paid', $store_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getPurchaseDuePaidAmount($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`type` IN ('due_paid','transfer') AND `store_id` = ?";
		$where_query .= date_range_purchase_payments_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`amount`) as total FROM `purchase_payments` 
			WHERE $where_query");
		$statement->execute(array($store_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getTopProducts($from, $to, $limit = 3, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT `selling_info`.`store_id`, `selling_info`.`created_at`, `selling_item`.`item_name`, SUM(`selling_item`.`item_quantity`) as quantity FROM `selling_info` 
			LEFT JOIN `selling_item` ON (`selling_info`.`invoice_id` = `selling_item`.`invoice_id`)
			WHERE $where_query
			GROUP BY `selling_item`.`item_id` ORDER BY `quantity` 
			DESC LIMIT $limit");
		$statement->execute(array());
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTopCustomers($from, $to, $limit = 3, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`payable_amount`) AS total, `selling_info`.`store_id`, `selling_info`.`created_at`, `selling_info`.`customer_id` FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`)
			WHERE $where_query
			GROUP BY `customer_id` ORDER BY `total` 
			DESC LIMIT $limit");
		$statement->execute(array());
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTopSuppliers($from, $to, $limit = 3, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id' AND `selling_item`.`sup_id` IS NOT NULL AND `selling_item`.`sup_id` != '0'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT `selling_info`.`store_id`, `selling_info`.`created_at`, `selling_item`.`sup_id`, SUM(`selling_item`.`item_quantity`) as quantity FROM `selling_info`
			LEFT JOIN `selling_item` ON (`selling_info`.`invoice_id` = `selling_item`.`invoice_id`)
			WHERE $where_query
			GROUP BY `selling_item`.`sup_id` ORDER BY `quantity` 
			DESC LIMIT $limit");
		$statement->execute(array());
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTopBrands($from, $to, $limit = 3, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id' AND `selling_item`.`brand_id` IS NOT NULL AND `selling_item`.`sup_id` != '0'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT `selling_info`.`store_id`, `selling_info`.*, `selling_item`.`brand_id`, SUM(`selling_item`.`item_quantity`) as quantity FROM `selling_info` 
			LEFT JOIN `selling_item` ON (`selling_info`.`invoice_id` = `selling_item`.`invoice_id`)
			WHERE $where_query
			GROUP BY `selling_item`.`brand_id` ORDER BY `quantity` 
			DESC LIMIT $limit");
		$statement->execute(array());
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function totalOutOfStock($store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement =  $this->db->prepare("SELECT * FROM `products` 
			LEFT JOIN `product_to_store` p2s ON (`products`.`p_id` = `p2s`.`product_id`) 
			WHERE `p2s`.`store_id` = ? AND (`p2s`.`quantity_in_stock` <= `alert_quantity`) AND `p2s`.`status` = 1");
		$statement->execute(array($store_id));
		return $statement->rowCount();
	}

	public function totalExpired($store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement =  $this->db->prepare("SELECT * FROM `products` LEFT JOIN `product_to_store` p2s ON (`products`.`p_id` = `p2s`.`product_id`) WHERE `p2s`.`store_id` = ? AND `e_date` <= CURDATE() AND `p2s`.`status` = 1");
		$statement->execute(array($store_id));
		return $statement->rowCount();
	}

	public function userTotalInvoiceCount($user_id = null, $from = null, $to = null, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = ? AND `selling_info`.`status` = 1 AND `created_by` = $user_id AND `inv_type` = 'sell'";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT * FROM `selling_info` WHERE $where_query");
		$statement->execute(array($store_id));
		return $statement->rowCount();
	}

	public function getOrderTaxAmountDaywise($year, $month = null, $day = null, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id'";
		if ($day) {
		  $where_query .= " AND DAY(`selling_info`.`created_at`) = $day";
		}
		if ($month) {
		  $where_query .= " AND MONTH(`selling_info`.`created_at`) = $month";
		}
		if ($year) {
		  $where_query .= " AND YEAR(`selling_info`.`created_at`) = $year";
		}

		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`order_tax`) as total FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array());
		$order_tax = $statement->fetch(PDO::FETCH_ASSOC);

		return $order_tax['total'];
	}

	public function getTotalCashReceivedBy($type, $type_id, $from = null, $to = null, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = ? AND `selling_info`.`status` = 1";
		$where_query .= date_range_filter($from, $to);
		switch ($type) {
			case 'userwise':
				$where_query .= " AND `selling_info`.`inv_type` = 'sell' AND `selling_info`.`created_by` = $type_id";
				$statement = $this->db->prepare("SELECT SUM(`selling_price`.`paid_amount`) as total FROM `selling_info` 
					LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
					WHERE $where_query");
				$statement->execute(array($store_id));
				$invoice = $statement->fetch(PDO::FETCH_ASSOC);
				$total = isset($invoice['total']) ? (float)$invoice['total'] : 0;
				$prev_due_collection = $this->getTotalPrevDueCollectionBy($type_id, $from, $to);
				$total = $total+$prev_due_collection;
				break;
			case 'invoicewise':
				$where_query .= " AND `selling_info`.`inv_type` = 'sell' AND `selling_info`.`invoice_id` = '$type_id'";
				$statement = $this->db->prepare("SELECT SUM(`selling_price`.`paid_amount`) as total FROM `selling_info` 
					LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
					WHERE $where_query");
				$statement->execute(array($store_id));
				$invoice = $statement->fetch(PDO::FETCH_ASSOC);
				$total = isset($invoice['total']) ? (float)$invoice['total'] : 0;
				break;
		}	
		return $total;
	}

	public function getTotalDueAmountBy($type, $type_id, $from = null, $to = null, $store_id = null) 
	{
		$edited_invoice_amount = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = ? AND `selling_info`.`status` = 1";
		$where_query .= date_range_filter($from, $to);
		switch ($type) {
			case 'invoicewise':
				$where_query .= " AND `selling_info`.`invoice_id` = '$type_id'";
				$statement = $this->db->prepare("SELECT SUM(`selling_price`.`due`) as total FROM `selling_info` 
					LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
					WHERE $where_query");
				$statement->execute(array($store_id));
				$invoice = $statement->fetch(PDO::FETCH_ASSOC);
				$total = isset($invoice['total']) ? (float)$invoice['total'] : 0;
				break;
			case 'userwise':
				$where_query .= " AND `inv_type` = 'sell' AND `selling_info`.`created_by` = $type_id";
				$statement = $this->db->prepare("SELECT SUM(`selling_price`.`due`) as total FROM `selling_info` 
					LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
					WHERE $where_query");
				$statement->execute(array($store_id));
				$invoice = $statement->fetch(PDO::FETCH_ASSOC);
				$total = isset($invoice['total']) ? (float)$invoice['total'] : 0;
				break;
		}	
		return $total + $edited_invoice_amount;
	}

	public function getTotalDueCollectionBy($user_id, $from = null, $to = null) 
	{
		$total = 0;
		$from = $from ? $from : date('Y-m-d');
		$to = $to ? $to : date('Y-m-d');
		$where_query = "`payments`.`type`='due_paid' AND `payments`.`created_by` = ?";
		$where_query .= date_range_sell_payments_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`payments`.`amount`) as total, `created_at` FROM `payments` 
			WHERE $where_query");
		$statement->execute(array($user_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return $invoice['total'];
	}

	public function getTotalPrevDueCollectionBy($user_id, $from = null, $to = null) 
	{
		$total = 0;
		$from = $from ? $from : date('Y-m-d');
		$to = $to ? $to : date('Y-m-d');
		$where_query = "`payments`.`type`='due_paid' AND `payments`.`created_by` = ?";
		$where_query .= date_range_sell_payments_reverse_filter($from, $to);
		$statement = $this->db->prepare("SELECT SUM(`payments`.`amount`) as total, `created_at` FROM `payments` 
			WHERE $where_query");
		$statement->execute(array($user_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function getTotalTaxAmountBy($type, $type_id, $from = null, $to = null, $store_id = null) 
	{
		$total = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`inv_type` = 'sell' AND `selling_info`.`store_id` = ? AND `selling_info`.`status` = 1";
		$where_query .= date_range_filter($from, $to);
		switch ($type) {
			case 'invoicewise':
				$where_query .= " AND `selling_info`.`invoice_id` = '$type_id'";
				$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.`order_tax` as order_tax, `selling_price`.`item_tax` as item_tax 
				FROM `selling_info` 
				LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
				WHERE $where_query");
				break;
			case 'userwise':
				$where_query .= " AND `selling_info`.`created_by` = $type_id";
				$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.`order_tax` as order_tax, `selling_price`.`item_tax` as item_tax 
				FROM `selling_info` 
				LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
				WHERE $where_query");
				break;		
		}
		$statement->execute(array($store_id));
		$invoices = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($invoices as $inv) {
			$total += $inv['order_tax'] + $inv['item_tax'];
		}
		return $total;
	}

	public function getTotalShippingChargeBy($type, $type_id, $from = null, $to = null, $store_id = null) 
	{
		$total = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`inv_type` = 'sell' AND `selling_info`.`store_id` = ? AND `selling_info`.`status` = 1";
		$where_query .= date_range_filter($from, $to);
		switch ($type) {
			case 'invoicewise':
				$where_query .= " AND `selling_info`.`invoice_id` = '$type_id'";
				$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.`shipping_amount` as shipping_charge 
				FROM `selling_info` 
				LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
				WHERE $where_query");
				break;
			case 'userwise':
				$where_query .= " AND `selling_info`.`created_by` = $type_id";
				$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.`shipping_amount` as shipping_charge  
				FROM `selling_info` 
				LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
				WHERE $where_query");
				break;		
		}
		$statement->execute(array($store_id));
		$invoices = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($invoices as $inv) {
			$total += $inv['shipping_charge'];
		}
		return $total;
	}

	public function getTotalOthersChargeBy($type, $type_id, $from = null, $to = null, $store_id = null) 
	{
		$total = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`inv_type` = 'sell' AND `selling_info`.`store_id` = ? AND `selling_info`.`status` = 1";
		$where_query .= date_range_filter($from, $to);
		switch ($type) {
			case 'invoicewise':
				$where_query .= " AND `selling_info`.`invoice_id` = '$type_id'";
				$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.`others_charge` as others_charge 
				FROM `selling_info` 
				LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
				WHERE $where_query");
				break;
			case 'userwise':
				$where_query .= " AND `selling_info`.`created_by` = $type_id";
				$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.`others_charge` as others_charge  
				FROM `selling_info` 
				LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
				WHERE $where_query");
				break;		
		}
		$statement->execute(array($store_id));
		$invoices = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($invoices as $inv) {
			$total += $inv['others_charge'];
		}
		return $total;
	}

	public function getTotalDiscountAmountBy($type, $type_id, $from = null, $to = null, $store_id = null) 
	{
		$total = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`inv_type` = 'sell' AND `selling_info`.`store_id` = ? AND `selling_info`.`status` = 1";
		$where_query .= date_range_filter($from, $to);
		switch ($type) {
			case 'invoicewise':
				$where_query .= " AND `selling_info`.`invoice_id` = '$type_id'";
				$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.`discount_amount` as discount_amount 
				FROM `selling_info` 
				LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
				WHERE $where_query");
				break;
			case 'userwise':
				$where_query .= " AND `selling_info`.`created_by` = $type_id";
				$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.`discount_amount` as discount_amount  
				FROM `selling_info` 
				LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
				WHERE $where_query");
				break;	
			case 'itemwise':
				$where_query .= " AND `selling_info`.`invoice_id` = '$type_id'";
				$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.`discount_amount` as discount_amount  
				FROM `selling_info` 
				LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
				WHERE $where_query");
				break;	
		}
		$statement->execute(array($store_id));
		$invoices = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($invoices as $inv) {
			$total += $inv['discount_amount'];
		}
		return $total;
	}

	// public function getTotalDiscountAmountBy($type, $type_id, $from = null, $to = null, $store_id = null) 
	// {
	// 	$total = 0;
	// 	$store_id = $store_id ? $store_id : store_id();
	// 	$where_query = "`type` = 'discount' AND `store_id` = ?";
	// 	$where_query .= date_range_sell_payments_filter($from, $to);
	// 	switch ($type) {
	// 		case 'invoicewise':
	// 			$where_query .= " AND `invoice_id` = '$type_id'";
	// 			$statement = $this->db->prepare("SELECT SUM(`amount`) as total 
	// 			FROM `payments` 
	// 			WHERE $where_query");
	// 			$statement->execute(array($store_id));
	// 			$row = $statement->fetch(PDO::FETCH_ASSOC);
	// 			$total = isset($row['total']) ? $row['total'] : 0;
	// 			break;
	// 		case 'userwise':
	// 			$where_query .= " AND `created_by` = $type_id";
	// 			$statement = $this->db->prepare("SELECT SUM(`amount`) as total 
	// 			FROM `payments` 
	// 			WHERE $where_query");
	// 			$statement->execute(array($store_id));
	// 			$row = $statement->fetch(PDO::FETCH_ASSOC);
	// 			$total = isset($row['total']) ? $row['total'] : 0;
	// 			break;
	// 		case 'itemwise':
	// 			$item_count = count(get_invoice_items($type_id, $store_id));
	// 			$where_query .= " AND `invoice_id` = '$type_id'";
	// 			$statement = $this->db->prepare("SELECT SUM(`amount`) as total 
	// 			FROM `payments` 
	// 			WHERE $where_query");
	// 			$statement->execute(array($store_id));
	// 			$row = $statement->fetch(PDO::FETCH_ASSOC);
	// 			$total = isset($row['total']) ? $row['total'] / $item_count : 0;
	// 			break;			
	// 	}
	// 	return $total;
	// }

	public function getTotalInvoiceAmountBy($type, $type_id, $from = null, $to = null, $store_id = null) 
	{
		$total = 0;
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = ? AND `selling_info`.`status` = 1";
		$where_query .= date_range_filter($from, $to);
		switch ($type) {
			case 'invoicewise':
				$where_query .= " AND `inv_type` = 'sell' AND `selling_info`.`invoice_id` = '$type_id'";
				$statement = $this->db->prepare("SELECT SUM(`selling_price`.`subtotal`) as total FROM `selling_info` 
					LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
					WHERE $where_query");
				$statement->execute(array($store_id));
				$invoice = $statement->fetch(PDO::FETCH_ASSOC);
				$total = isset($invoice['total']) ? (float)$invoice['total'] : 0;
				break;
			case 'userwise':
				$where_query .= " AND `inv_type` = 'sell' AND `selling_info`.`created_by` = $type_id";
				$statement = $this->db->prepare("SELECT SUM(`selling_price`.`subtotal`) as total FROM `selling_info` 
					LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
					WHERE $where_query");
				$statement->execute(array($store_id));
				$invoice = $statement->fetch(PDO::FETCH_ASSOC);
				$total = isset($invoice['total']) ? (float)$invoice['total'] : 0;
				break;
		}	
		return $total;
	}
}