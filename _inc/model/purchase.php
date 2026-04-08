<?php

class ModelPurchase extends Model 
{
	public function getInvoices($type, $store_id = null, $limit = 100000) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT `purchase_info`.*, `purchase_price`.*, `suppliers`.`sup_id`, `suppliers`.`sup_name`, `suppliers`.`sup_mobile`, `suppliers`.`sup_email` FROM `purchase_info` 
			LEFT JOIN `purchase_price` ON `purchase_info`.`invoice_id` = `purchase_price`.`invoice_id` 
			LEFT JOIN `suppliers` ON `purchase_info`.`sup_id` = `suppliers`.`sup_id` 
			WHERE `purchase_info`.`store_id` = ? AND `purchase_info`.`inv_type` = ? ORDER BY `purchase_info`.`created_at` DESC LIMIT $limit");
		$statement->execute(array($store_id, $type));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getInvoiceInfo($invoice_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT `purchase_info`.*, `purchase_price`.*, `suppliers`.`sup_id`, `suppliers`.`sup_name`, `suppliers`.`sup_mobile` AS `mobile_number`, `suppliers`.`sup_email` FROM `purchase_info` 
			LEFT JOIN `purchase_price` ON `purchase_info`.`invoice_id` = `purchase_price`.`invoice_id` 
			LEFT JOIN `suppliers` ON `purchase_info`.`sup_id` = `suppliers`.`sup_id` 
			WHERE `purchase_info`.`store_id` = ? AND (`purchase_info`.`invoice_id` = ? OR (`purchase_info`.`sup_id` = ?) AND `purchase_info`.`inv_type` IN ('purchase','transfer')) ORDER BY `purchase_info`.`invoice_id` DESC");
		$statement->execute(array($store_id, $invoice_id, $invoice_id));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getInvoiceItems($invoice_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `invoice_id` = ?");
		$statement->execute(array($store_id, $invoice_id));
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		$array = array();
		$i = 0;
		foreach ($rows as $row) {
			$array[$i] = $row;
			$array[$i]['unitName'] = get_the_unit(get_the_product($row['item_id'])['unit_id'],'unit_name');
			$i++;
		}
		return $array;
	}

	public function getInvoiceItemsHTML($invoice_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        $html = '<table class="table table-bordered">';
        $html .= '<thead>';
        $html .= '<tr class="bg-gray">';
        $html .= '<td class="text-center" style="padding:0 2px;">Name</td>';
        $html .= '<td class="text-right" style="padding:0 2px;">Sell</td>';
        $html .= '<td class="text-center" style="padding:0 2px;">Qty.</td>';
        $html .= '<td class="text-right" style="padding:0 2px;">Subtotal</td>';
        $html .= '</tr>';
        $html .= '</thead>';
        $sell = 0;
        $qty = 0;
        $total = 0;
        foreach ($rows as $row) {
            $html .= '<tr class="bg-success">';
            $html .= '<td class="text-center" style="padding:0 2px;">' . $row['item_name'] . '</td>';
            $html .= '<td class="text-right" style="padding:0 2px;">' . currency_format($row['item_price']) . '</td>';
            $html .= '<td class="text-center" style="padding:0 2px;">' . currency_format($row['item_quantity']) . ' ' . get_the_unit(get_the_product($row['item_id'])['unit_id'], 'unit_name') . '</td>';
            $html .= '<td class="text-right" style="padding:0 2px;">' . currency_format($row['item_total']) . '</td>';
            $html .= '</tr>';
            $sell += $row['item_price'];
            $qty += $row['item_quantity'];
            $total += $row['item_total'];
        }
        $html .= '<tr class="bg-info">';
        $html .= '<td class="text-center" style="padding:0 2px;">Total</td>';
        $html .= '<td class="text-right" style="padding:0 2px;">' . currency_format($sell) . '</td>';
        $html .= '<td class="text-center" style="padding:0 2px;">' . currency_format($qty) . '</td>';
        $html .= '<td class="text-right" style="padding:0 2px;">' . currency_format($total) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }

	public function getTheInvoiceItem($invoice_id, $item_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `invoice_id` = ? AND `item_id` = ?");
		$statement->execute(array($store_id, $invoice_id, $item_id));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getInvoiceItemCount($invoice_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `invoice_id` = ?");
		$statement->execute(array($store_id, $invoice_id));
		return $statement->rowCount();
	}

	public function getInvoiceItemTaxes($invoice_id) 
	{
		$statement = $this->db->prepare("SELECT SUM(`item_quantity`) as qty, SUM(`tax`) as tax, SUM(`item_total`) as total, item_tax, `taxrates`.`taxrate_name`, `taxrates`.`code_name` FROM `purchase_item` LEFT JOIN `taxrates` ON (`purchase_item`.`taxrate_id` = `taxrates`.`taxrate_id`) WHERE invoice_id = ? GROUP BY `purchase_item`.`taxrate_id`");
		$statement->execute(array($invoice_id));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSellingPrice($invoice_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `purchase_price` WHERE `store_id` = ? AND invoice_id = ?");
		$statement->execute(array($store_id, $invoice_id));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function hasInvoice($invoice_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `purchase_info` WHERE `purchase_info`.`store_id` = ? AND `purchase_info`.`invoice_id` = ?");
		$statement->execute(array($store_id, $invoice_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['invoice_id']);
	}

	public function hasTheInvoiceItem($invoice_id, $item_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `invoice_id` = ? AND `item_id` = ?");
		$statement->execute(array($store_id, $invoice_id, $item_id));
		return $statement->rowCount();

	}

	public function DeleteTheInvoiceItem($invoice_id, $item_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("DELETE FROM `purchase_item` WHERE `store_id` = ? AND `invoice_id` = ? AND `item_id` = ?");
		$statement->execute(array($store_id, $invoice_id, $item_id));
		return true;
	}

	public function isLastInvoice($sup_id, $invoice_id, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$statemtnt = $this->db->prepare("SELECT * FROM `purchase_info` WHERE `store_id` = ? AND `sup_id` = ? AND `inv_type` IN ('purchase','transfer') ORDER BY `info_id` DESC LIMIT 1");
        $statemtnt->execute(array($store_id, $sup_id));
        $row = $statemtnt->fetch(PDO::FETCH_ASSOC);
        return $row['invoice_id'] == $invoice_id;
	}

	public function getLastInvoice($type = 'sell', $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$statemtnt = $this->db->prepare("SELECT * FROM `purchase_info` WHERE `store_id` = ? AND `inv_type` = ? ORDER BY `info_id` DESC");
        $statemtnt->execute(array($store_id, $type));
        return $statemtnt->fetch(PDO::FETCH_ASSOC);
	}

	public function getNextInvoice($sup_id, $invoice_id, $type = 'sell', $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$statemtnt = $this->db->prepare("SELECT * FROM `purchase_info` WHERE `store_id` = ? AND `sup_id` = ? AND `inv_type` = ? ORDER BY `info_id` DESC");
        $statemtnt->execute(array($store_id, $sup_id, $type));
        $rows = $statemtnt->fetchAll(PDO::FETCH_ASSOC);
        $invoice = null;
        foreach ($rows as $r) {
        	if ($r['invoice_id'] == $invoice_id) {
        		break;
        	}
        	$invoice = $r;
        }
        return $invoice;
	}

	public function totalToday($store_id = null)
	{
		$from = date('Y-m-d');
		$to = date('Y-m-d');
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`store_id` = ? AND `inv_type` IN ('purchase','transfer')";
		$where_query .= date_range_filter($from, $to);
		$statement = $this->db->prepare("SELECT * FROM `purchase_info` WHERE $where_query");
		$statement->execute(array(store_id()));
		return $statement->rowCount();
	}

	public function total($from = null, $to = null, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`store_id` = ? AND `inv_type` IN ('purchase','transfer')";
		if ($from) {
			$where_query .= date_range_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT * FROM `purchase_info` WHERE $where_query");
		$statement->execute(array(store_id()));
		return $statement->rowCount();
	}
}