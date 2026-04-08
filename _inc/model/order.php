<?php

class ModelOrder extends Model 
{
    public function updateOrder($reference_no, $request, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $user_id = user_id();
        $created_at   = date_time();

        $product_items  = $request->post['products'];
        $total_items  = count($product_items);
        $quotation_note   = $request->post['quotation-note'];
        $customer_id    = $request->post['customer_id'];
        $customer_mobile  = isset($request->post['customer-mobile']) ? $request->post['customer-mobile'] : '';
        $subtotal      = $request->post['total-amount'];
        $discount_type  = isset($request->post['discount-type']) ? $request->post['discount-type'] : 'plain';;
        $discount_amount= $request->post['discount-amount'] ? $request->post['discount-amount'] : 0;
        $shipping_type  = isset($request->post['shipping-type']) ? $request->post['shipping-type'] : 'plain';
        $shipping_amount= $request->post['shipping-amount'] ? $request->post['shipping-amount'] : 0;
        $others_charge = $request->post['others-charge'] ? $request->post['others-charge'] : 0;
        $order_tax     = $request->post['order-tax'] ? $request->post['order-tax'] : 0; 
        $item_tax     = $request->post['total-tax'] ? $request->post['total-tax'] : 0;
        $payable_amount = $request->post['payable-amount'];
        $status = $request->post['status'];

        $is_installment_order = isset($request->post['is_installment_order']) ? $request->post['is_installment_order'] : '';
        $installment_interest_percentage = 0;
        $installment_interest_amount = 0;
        if (INSTALLMENT && $is_installment_order) {
            $installment_initial_amount = $paid_amount;
            $installment_duration = $request->post['installment_duration'];
            $installment_interval_count = $request->post['installment_interval_count'];
            $installment_count = $request->post['installment_count'];
            $installment_interest_percentage = $request->post['installment_interest_percentage'];
            $installment_interest_amount = $request->post['installment_interest_amount'];
            $installmentEndDate = date('Y-m-d H:i:s', strtotime(' + '.$installment_duration.' days'));
        }

        $product_discount = $discount_amount / $total_items;
        $payment_status = 'due';

        $subtotal = 0;
        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        $tgst = 0;
        $tigst = 0;
        $tcgst = 0;
        $tsgst = 0;
        $total_purchase_price = 0;

        $statement = $this->db->prepare("DELETE FROM `quotation_item` WHERE `reference_no` = ? AND `store_id` = ?");
        $statement->execute(array($reference_no, $store_id));

        foreach ($product_items as $product) 
        {
            $product_id         = $product['item_id'];
            $product_info       = get_the_product($product_id);
            $product_name       = $product['item_name'];
            $category_id        = $product['category_id'];
            $brand_id           = $product_info['brand_id'];
            $sup_id             = $product_info['sup_id'];
            $product_quantity   = $product['quantity'];
            $product_price      = $product['unit_price'];
            $taxrate            = $product['taxrate'];
            $tax                = $product['tax_amount'];
            $product_total      = ($product['unit_price']*$product_quantity)+$tax;
            $purchase_invoice_id  = NULL;
            $item_purchase_price = 0;

            $subtotal += $product['unit_price']*$product_quantity;
            $taxrate_id = $product_info['taxrate_id'];
            $quantity_exist = $product_quantity;
            $sell_quantity = $product_quantity;

            if (get_the_customer($customer_id, 'customer_state') == get_preference('business_state')) 
            {
              $cgst = $tax / 2;
              $sgst = $tax / 2;
              $tcgst += $tax / 2;
              $tsgst += $tax / 2;
            } else {
              $igst = $tax;
              $tigst += $tax;
            }

            $statement = $this->db->prepare("INSERT INTO `quotation_item` (reference_no, store_id, sup_id, category_id, brand_id, item_id, item_name, item_purchase_price, item_price, item_discount, item_tax, taxrate_id, tax, gst, cgst, sgst, igst, item_quantity, item_total, purchase_invoice_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array($reference_no, $store_id, $sup_id, $category_id, $brand_id, $product_id, $product_name, $item_purchase_price, $product_price, $product_discount, $tax, $taxrate_id, $taxrate, $taxrate, $cgst, $sgst, $igst, $product_quantity, $product_total, $purchase_invoice_id));

        }

        $statement = $this->db->prepare("UPDATE `quotation_info` SET `customer_id` = ?, `customer_mobile` = ?, `quotation_note` = ?, `total_items` = ?, `status` = ?, `payment_status` = ?, `is_installment` = ? WHERE `reference_no` = ? AND `store_id` = ?");
        $statement->execute(array($customer_id, $customer_mobile, $quotation_note, $total_items, $status, $payment_status, $is_installment_order, $reference_no, $store_id));

        $statement = $this->db->prepare("UPDATE `quotation_price` SET `subtotal` = ?, `discount_type` = ?, `discount_amount` = ?, `interest_amount` = ?, `interest_percentage` = ?, `item_tax` = ?, `order_tax` = ?, `cgst` = ?, `sgst` = ?, `igst` = ?, `total_purchase_price` = ?, `shipping_type` = ?, `shipping_amount` = ?, `others_charge` = ?, `payable_amount` = ? WHERE `reference_no` = ? AND `store_id` = ?");
        $statement->execute(array($subtotal, $discount_type, $discount_amount, $installment_interest_amount, $installment_interest_percentage, $item_tax, $order_tax, $tcgst, $tsgst, $tigst, $total_purchase_price, $shipping_type, $shipping_amount, $others_charge, $payable_amount, $reference_no, $store_id));

        return $reference_no;
    }

    public function getOrders($store_id = null, $limit = 100000, $customer_id = null) 
    {
        $store_id = $store_id ? $store_id : store_id();
        $where_query = "`quotation_info`.`store_id` = {$store_id} ORDER BY `quotation_info`.`created_at`";
        if ($customer_id) {
            $where_query .= " AND `quotation_info`.`customer_id` = {$customer_id}";
        }
        $statement = $this->db->prepare("SELECT `quotation_info`.*, `quotation_price`.*, `customers`.`customer_id`, `customers`.`customer_name`, `customers`.`customer_mobile`, `customers`.`customer_email` FROM `quotation_info` 
            LEFT JOIN `quotation_price` ON `quotation_info`.`reference_no` = `quotation_price`.`reference_no` 
            LEFT JOIN `customers` ON `quotation_info`.`customer_id` = `customers`.`customer_id` 
            WHERE {$where_query} DESC LIMIT $limit");
        $statement->execute(array());
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderInfo($reference_no, $store_id = null) 
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT `quotation_info`.*, `quotation_price`.*, `customers`.`customer_id`, `customers`.`customer_name`, `customers`.`customer_mobile` AS `mobile_number`, `customers`.`customer_email` FROM `quotation_info` 
            LEFT JOIN `quotation_price` ON `quotation_info`.`reference_no` = `quotation_price`.`reference_no` 
            LEFT JOIN `customers` ON `quotation_info`.`customer_id` = `customers`.`customer_id` 
            WHERE `quotation_info`.`store_id` = ? AND (`quotation_info`.`reference_no` = ? OR `quotation_info`.`customer_id` = ?) ORDER BY `quotation_info`.`reference_no` DESC");
        $statement->execute(array($store_id, $reference_no, $reference_no));
        $quotation = $statement->fetch(PDO::FETCH_ASSOC);
        if ($quotation) {
            $quotation['by'] = get_the_user($quotation['created_by'], 'username');
            $quotation['date'] = date('Y-m-d', strtotime($quotation['created_at']));
        }
        return $quotation;
    }

    public function getOrderItems($reference_no, $store_id = null) 
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT * FROM `quotation_item` WHERE `store_id` = ? AND `reference_no` = ?");
        $statement->execute(array($store_id, $reference_no));
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $array = array();
        $i = 0;
        foreach ($rows as $row) {
            $array[$i] = $row;
            $array[$i]['p_type'] = get_the_product($row['item_id'],'p_type');
            $array[$i]['unitName'] = get_the_unit(get_the_product($row['item_id'])['unit_id'],'unit_name');
            $array[$i]['item_code'] = get_the_product($row['item_id'],'p_code');
            $i++;
        }
        return $array;
    }
}