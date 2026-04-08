<?php
include ("../_init.php");

// Product Images
if($request->server['REQUEST_METHOD'] == 'GET' AND $request->get['type'] == 'PRODUCTIMAGES') 
{
	try {
		$p_id = $request->get['p_id'];
		$images = get_product_images($p_id);
	    header('Content-Type: application/json');
	    echo json_encode(array('msg' => trans('text_success'), 'images' => $images));
	    exit();

	  } catch (Exception $e) { 
	    
	    header('HTTP/1.1 422 Unprocessable Entity');
	    header('Content-Type: application/json; charset=UTF-8');
	    echo json_encode(array('errorMsg' => $e->getMessage()));
	    exit();
	  }
}

// Banner Images
if($request->server['REQUEST_METHOD'] == 'GET' AND $request->get['type'] == 'BANNERIMAGES') 
{
	try {
		$id = $request->get['id'];
		$images = get_banner_images($id);
	    header('Content-Type: application/json');
	    echo json_encode(array('msg' => trans('text_banner_images'), 'images' => $images));
	    exit();

	  } catch (Exception $e) { 
	    
	    header('HTTP/1.1 422 Unprocessable Entity');
	    header('Content-Type: application/json; charset=UTF-8');
	    echo json_encode(array('errorMsg' => $e->getMessage()));
	    exit();
	  }
}

// Quotation info
if($request->server['REQUEST_METHOD'] == 'POST' AND $request->get['type'] == 'QUOTATIONINFO') 
{
	try {
		$ref_no = $request->post['ref_no'];
		$quotation_model = registry()->get('loader')->model('quotation');
		$quotation = $quotation_model->getQuotationInfo($ref_no);
		$quotation_items = $quotation_model->getQuotationItems($ref_no);
		$quotation['items'] = $quotation_items;
		header('Content-Type: application/json');
		echo json_encode(array('msg' => trans('text_success'), 'quotation' => $quotation));
		exit();

	} catch (Exception $e) { 

		header('HTTP/1.1 422 Unprocessable Entity');
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode(array('errorMsg' => $e->getMessage()));
		exit();
	}
}

// Update POS tempalte content
if($request->server['REQUEST_METHOD'] == 'POST' AND $request->get['type'] == 'UPDATEPOSTEMPALTECONTENT') 
{
	try {

		if (DEMO || (user_group_id() != 1 && !has_permission('access', 'receipt_template'))) {
	      throw new Exception(trans('error_update_permission'));
	    }

		$template_id = $request->post['template_id'];
		$content = $request->post['content'];
		$statement = db()->prepare("UPDATE `pos_templates` SET `template_content` = ? WHERE `template_id` = ?");
		$statement->execute(array($content, $template_id));

		header('Content-Type: application/json');
		echo json_encode(array('msg' => trans('text_template_content_update_success')));
		exit();

	} catch (Exception $e) { 

		header('HTTP/1.1 422 Unprocessable Entity');
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode(array('errorMsg' => $e->getMessage()));
	exit();
	}
}

// Update POS tempalte CSS
if($request->server['REQUEST_METHOD'] == 'POST' AND $request->get['type'] == 'UPDATEPOSTEMPALTECSS') 
{
	try {
	    
	    if (DEMO || (user_group_id() != 1 && !has_permission('access', 'receipt_template'))) {
	      throw new Exception(trans('error_update_permission'));
	    }
	    
		$template_id = $request->post['template_id'];
		$content = $request->post['content'];
		$statement = db()->prepare("UPDATE `pos_templates` SET `template_css` = ? WHERE `template_id` = ?");
		$statement->execute(array($content, $template_id));

		header('Content-Type: application/json');
		echo json_encode(array('msg' => trans('text_template_css_update_success')));
		exit();

	} catch (Exception $e) { 

		header('HTTP/1.1 422 Unprocessable Entity');
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode(array('errorMsg' => $e->getMessage()));
		exit();
	}
}

// Update opening balance
if($request->server['REQUEST_METHOD'] == 'POST' AND $request->get['type'] == 'UPDATEOPENINGBALANCE') 
{
	try {
		$balance = str_replace(',', '', $request->post['balance']);
		if (!is_numeric($balance)) {
			throw new Exception(trans('error_invalid_balance'));
		}

		// UPDATE OPENING BALANCE
		$from = date('Y-m-d');
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query = " DAY(`pos_register`.`created_at`) = $day";
		$where_query .= " AND MONTH(`pos_register`.`created_at`) = $month";
		$where_query .= " AND YEAR(`pos_register`.`created_at`) = $year";

		// If not exist then insert
		$statement = db()->prepare("SELECT `id` FROM `pos_register` WHERE $where_query AND `store_id` = ?");
		$statement->execute(array(store_id()));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			$statement = db()->prepare("INSERT INTO `pos_register` SET `store_id` = ?, `created_at` = ?");
			$statement->execute(array(store_id(), date_time()));
		}

		$statement = db()->prepare("UPDATE `pos_register` SET `opening_balance` = ? WHERE $where_query AND `store_id` = ?");
		$statement->execute(array($balance, store_id()));

		// UPDATE CLOSING BALANCE
		$date = date('Y-m-d');
		$from = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query = " DAY(`pos_register`.`created_at`) = $day";
		$where_query .= " AND MONTH(`pos_register`.`created_at`) = $month";
		$where_query .= " AND YEAR(`pos_register`.`created_at`) = $year";
		$statement = db()->prepare("UPDATE `pos_register` SET `opening_balance` = ? WHERE $where_query AND `store_id` = ?");
		$statement->execute(array($balance, store_id()));

		header('Content-Type: application/json');
		echo json_encode(array('msg' => trans('text_opening_balance_update_success')));
		exit();

	} catch (Exception $e) { 

		header('HTTP/1.1 422 Unprocessable Entity');
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode(array('errorMsg' => $e->getMessage()));
		exit();
	}
}

if($request->server['REQUEST_METHOD'] == 'POST' AND $request->get['type'] == 'PURCHASEITEM') 
{
	$sup_id = isset($request->post['sup_id']) ? $request->post['sup_id'] : null;
	$type = $request->post['type'];
	$name = $request->post['name_starts_with'];
	$query = "SELECT `p_id`, `p_name`, `p_code`, `category_id`, `unit_id`, `p2s`.`tax_method`, `p2s`.`purchase_price`, `p2s`.`sell_price`, `p2s`.`quantity_in_stock` 
		FROM `products` 
		LEFT JOIN `product_to_store` p2s ON (`products`.`p_id` = `p2s`.`product_id`)
		WHERE `p2s`.`store_id` = ? AND `p2s`.`status` = ? AND `p_type` != 'service'";
	if ($sup_id) {
		$query .= " AND `p2s`.`sup_id` = ?";
	}
	$query .= " AND (UPPER($type) LIKE '" . strtoupper($name) . "%' OR `p_code` = '{$name}') ORDER BY `p_id` DESC LIMIT 10";
	$statement = db()->prepare($query);
	if ($sup_id) {
		$statement->execute(array(store_id(), 1, $sup_id));
	} else {
		$statement->execute(array(store_id(), 1));
	}
	$products = $statement->fetchAll(PDO::FETCH_ASSOC);
	$data = array();
    foreach ($products as $product) {
    	$purchase_price = $product['purchase_price'];
    	$sell_price = $product['sell_price'];
    	$tax_amount = 0;
    	$tax_method = $product['tax_method'] ? $product['tax_method'] : 'exclusive';
    	$taxrate = 0;
    	$product_info = get_the_product($product['p_id']);
    	if ($product_info && $product_info['taxrate']) {
    		$taxrate = $product_info['taxrate']['taxrate'];
    		$tax_amount = ($product_info['taxrate']['taxrate'] / 100 ) * $purchase_price;
    	}
		$name = $product['p_id'].'|'.$product['p_name'].'|'.$product['p_code'].'|'.$product['category_id'].'|'.$product['quantity_in_stock'].'|'.get_the_unit($product['unit_id'],'unit_name').'|'.$purchase_price .'|'.$sell_price.'|'.$tax_amount.'|'.$tax_method.'|'.$taxrate.'|'.$product['quantity_in_stock'];
		array_push($data, $name);
    }
	echo json_encode($data);
	exit();
}

// Product list
if($request->server['REQUEST_METHOD'] == 'POST' AND $request->get['type'] == 'SELLINGITEM') 
{
	$sup_id = isset($request->post['sup_id']) ? $request->post['sup_id'] : null;
	$type = $request->post['type'];
	$name = $request->post['name_starts_with'];
	$query = "SELECT `p_id`, `p_name`, `p_code`, `category_id`, `p2s`.`tax_method`, `p2s`.`purchase_price`, `p2s`.`sell_price`, `p2s`.`quantity_in_stock` 
		FROM `products` 
		LEFT JOIN `product_to_store` p2s ON (`products`.`p_id` = `p2s`.`product_id`)
		WHERE `p2s`.`store_id` = ? AND `p2s`.`status` = ?";
	if ($sup_id) {
		$query .= " AND `p2s`.`sup_id` = ?";
	}
	// $query .= " AND UPPER($type) LIKE '" . strtoupper($name) . "%' ORDER BY `p_id` DESC LIMIT 10";
	$query .= " AND (UPPER($type) LIKE '" . strtoupper($name) . "%' OR `p_code` = '{$name}') ORDER BY `p_id` DESC LIMIT 10";
	$statement = db()->prepare($query);
	if ($sup_id) {
		$statement->execute(array(store_id(), 1, $sup_id));
	} else {
		$statement->execute(array(store_id(), 1));
	}
	$products = $statement->fetchAll(PDO::FETCH_ASSOC);
	$data = array();
    foreach ($products as $product) {
    	$purchase_price = $product['purchase_price'];
    	$sell_price = $product['sell_price'];
    	$tax_amount = 0;
    	$tax_method = $product['tax_method'] ? $product['tax_method'] : 'exclusive';
    	$taxrate = 0;
    	$product_info = get_the_product($product['p_id']);
    	if ($product_info && $product_info['taxrate']) {
    		$taxrate = $product_info['taxrate']['taxrate'];
    		$tax_amount = ($product_info['taxrate']['taxrate'] / 100 ) * $sell_price;
    	}
		$name = $product['p_id'].'|'.$product['p_name'].'|'.$product['p_code'].'|'.$product['category_id'].'|'.$product['quantity_in_stock'].'|'.$purchase_price .'|'.$sell_price.'|'.$tax_amount.'|'.$tax_method.'|'.$taxrate;
		array_push($data, $name);
    }
	echo json_encode($data);
	exit();
}

// StockItems
if($request->server['REQUEST_METHOD'] == 'GET' AND $request->get['type'] == 'STOCKITEMS') 
{
	try {
		$store_id = $request->get['store_id'] ? $request->get['store_id'] : store_id();
		$statement = db()->prepare("SELECT `purchase_item`.*, `purchase_info`.`inv_type` FROM `purchase_item` LEFT JOIN `purchase_info` ON (`purchase_item`.`invoice_id` = `purchase_info`.`invoice_id`) WHERE `purchase_item`.`store_id` = ? AND `purchase_item`.`item_quantity` > `purchase_item`.`total_sell` AND `purchase_item`.`status` IN ('stock','active') AND `purchase_info`.`inv_type` = ?");
	    $statement->execute(array($store_id, 'purchase'));
	    $products = $statement->fetchAll(PDO::FETCH_ASSOC);

	    header('Content-Type: application/json');
	    echo json_encode(array('msg' => trans('text_success'), 'products' => $products));
	    exit();

	  } catch (Exception $e) { 
	    
	    header('HTTP/1.1 422 Unprocessable Entity');
	    header('Content-Type: application/json; charset=UTF-8');
	    echo json_encode(array('errorMsg' => $e->getMessage()));
	    exit();
	  }
}

// StockItem
if($request->server['REQUEST_METHOD'] == 'GET' AND $request->get['type'] == 'STOCKITEM') 
{
	try {
		$id = $request->get['id'];
		$quantity = $request->get['quantity'];
		$statement = db()->prepare("SELECT * FROM `purchase_item` WHERE `id` = ? AND `item_quantity` > `total_sell` AND `status` IN ('stock','active')");
		$statement->execute(array($id));
		$products = $statement->fetch(PDO::FETCH_ASSOC);

		header('Content-Type: application/json');
		echo json_encode(array('msg' => trans('text_success'), 'products' => $products));
		exit();

	} catch (Exception $e) { 

		header('HTTP/1.1 422 Unprocessable Entity');
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode(array('errorMsg' => $e->getMessage()));
		exit();
	}
}