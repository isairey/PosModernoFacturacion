<?php

class ModelInvoice extends Model
{
    public function putOrderOnHold($request, $ref_no, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $user_id = user_id();
        $created_at = date_time();

        $order_title = $request->post['order-title'];
        $product_items = $request->post['product-item'];
        $total_items = count($request->post['product-item']);
        $invoice_note = $request->post['invoice-note'];
        $customer_id = $request->post['customer-id'];
        $customer_mobile = $request->post['customer-mobile-number'];
        $subtotal = $request->post['sub-total'];
        $discount_type = $request->post['discount-type'];
        $discount_amount = $request->post['discount-amount'];
        $shipping_type = $request->post['shipping-type'] ? $request->post['shipping-type'] : 'plain';
        $shipping_amount = $request->post['shipping-amount'] ? $request->post['shipping-amount'] : 0;
        $others_charge = $request->post['others-charge'] ? $request->post['others-charge'] : 0;
        $order_tax = $request->post['tax-amount'];
        $payable_amount = $request->post['payable-amount'];
        $product_discount = $discount_amount / $total_items;
        $product_tax = $order_tax / $total_items;

        $item_tax = 0;
        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        $tgst = 0;
        $tigst = 0;
        $tcgst = 0;
        $tsgst = 0;
        $total_purchase_price = 0;

        foreach ($product_items as $product) 
        {
            $product_id = $product['item_id'];
            $product_info = get_the_product($product_id);
            $category_id = $product['category_id'];
            $brand_id = $product_info['brand_id'];
            $sup_id = $product['sup_id'];
            $product_name = $product['item_name'];
            $product_quantity = $product['item_quantity'];
            $product_price = $product['item_price'];
            $product_total = $product['item_total'];
            $purchase_invoice_id = NULL;
            $item_purchase_price = 0;
            $tax_method = $product_info['tax_method'];
            $taxrate_id = $product_info['taxrate_id'];
            $taxrate = 0;
            $tax = 0;
            if ($product_info['taxrate']) {
                $taxrate = $product_info['taxrate']['taxrate'];
                $tax = $taxrate / 100 * ($product_info['sell_price'] * $product_quantity);
            }
            $item_tax = $item_tax + $tax;
            if (get_the_customer($customer_id, 'customer_state') == get_preference('business_state')) {
                $cgst = $tax / 2;
                $sgst = $tax / 2;
                $tcgst += $tax / 2;
                $tsgst += $tax / 2;
            } else {
                $igst = $tax;
                $tigst += $tax;
            }
            $statement = $this->db->prepare("INSERT INTO `holding_item` (ref_no, store_id, item_id, category_id, brand_id, sup_id, item_name, item_price, item_discount, item_tax, tax_method, taxrate_id, tax, gst, cgst, sgst, igst, item_quantity, item_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array($ref_no, $store_id, $product_id, $category_id, $brand_id, $sup_id, $product_name, $product_price, $product_discount, $tax, $tax_method, $taxrate_id, $taxrate, $taxrate, $cgst, $sgst, $igst, $product_quantity, $product_total));
        }
        $statement = $this->db->prepare("INSERT INTO `holding_info` (store_id, order_title, ref_no, customer_id, customer_mobile, invoice_note, total_items, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->execute(array($store_id, $order_title, $ref_no, $customer_id, $customer_mobile, $invoice_note, $total_items, $user_id, $created_at));
        $statement = $this->db->prepare("INSERT INTO `holding_price` (ref_no, store_id, subtotal, discount_type, discount_amount, item_tax, order_tax, cgst, sgst, igst, shipping_type, shipping_amount, others_charge, payable_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->execute(array($ref_no, $store_id, $subtotal, $discount_type, $discount_amount, $item_tax, $order_tax, $tcgst, $tsgst, $tigst, $shipping_type, $shipping_amount, $others_charge, $payable_amount));
        return $ref_no;
    }
    
    public function createInvoice($request, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $user_id = $request->post['salesman-id'] ? $request->post['salesman-id'] : user_id();
        $created_at = date_time();
        $product_items = $request->post['product-item'];
        $total_items = count($request->post['product-item']);
        $invoice_note = $request->post['invoice-note'];
        $customer_id = $request->post['customer-id'];
        $customer_mobile = $request->post['customer-mobile-number'];
        $pmethod_id = $request->post['pmethod-id'];
        $pmethod_code = get_the_pmethod($pmethod_id,'code_name');
        $subtotal = $request->post['sub-total'];
        $discount_type = $request->post['discount-type'];
        $discount_amount = $request->post['discount-amount'];
        $shipping_type = $request->post['shipping-type'] ? $request->post['shipping-type'] : 'plain';
        $shipping_amount = $request->post['shipping-amount'] ? $request->post['shipping-amount'] : 0;
        $others_charge = $request->post['others-charge'] ? $request->post['others-charge'] : 0;
        $previous_due = $request->post['previous-due'] ? $request->post['previous-due'] : 0;
        $order_tax = $request->post['tax-amount'];
        $payable_amount = $request->post['payable-amount'] - $previous_due;
        $paid_amount = $request->post['paid-amount'] ? $request->post['paid-amount'] : 0;
        $total_paid = $paid_amount;
        $qref = $request->post['qref'];
        $is_installment_order = $request->post['is_installment_order'];
        $installment_interest_percentage = 0;
        $installment_interest_amount = 0;
        if (INSTALLMENT && $is_installment_order) {
            $installment_initial_amount = $paid_amount;
            $installment_duration = (int)$request->post['installment_duration'];
            $installment_interval_count = $request->post['installment_interval_count'];
            $installment_count = (int)$request->post['installment_count'];
            $installment_interest_percentage = $request->post['installment_interest_percentage'];
            $installment_interest_amount = $request->post['installment_interest_amount'];
            $installmentEndDate = date('Y-m-d H:i:s', strtotime(' + ' . $installment_duration . ' days'));
        }
        $details_raw = isset($request->post['payment_details']) ? $request->post['payment_details'] : array();
        $details = serialize($details_raw);

        // Card Payment
        $is_card_payments = false;
        $card_no = '';
        if (isset($details_raw['card_no'])) {
            $card_no = $details_raw['card_no'];
            $statement = $this->db->prepare("SELECT * FROM `gift_cards` WHERE `customer_id` = ? AND `card_no` = ? AND `balance` >= ? AND `expiry` > NOW()");
            $statement->execute(array($customer_id, $card_no, $payable_amount));
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $is_card_payments = true;
                $paid_amount = $row['balance'];
            } else {
                throw new Exception(trans('error_not_found_or_insufficient_balance'));
            }
        }

        // Credit Payment
        if ($pmethod_code == 'credit') {
            if ($payable_amount > get_customer_balance($customer_id)) {
                throw new Exception(trans('error_insufficient_balance'));
            }
        }

        // Previous Due Paid
        $prev_due_paid = 0;
        if ($paid_amount > 0 && $previous_due > 0) 
        {
            $due_paid_amount = $paid_amount;
            foreach ($this->getDueInvoices($customer_id) as $inv) 
            {
              if ($paid_amount <= 0) break;
              if ($inv['due'] < $paid_amount) {
                $due_paid_amount = $inv['due'];
              }
              $prev_due_paid += $due_paid_amount;
              $data = array(
                'invoice-id' => $inv['invoice_id'],
                'customer-id' => $customer_id,
                'pmethod-id' => 1,
                'discount-amount' => 0,
                'note' => '',
                'paid-amount' => $due_paid_amount,
              );
              $this->duePaid($data, store_id());
              $paid_amount -= $due_paid_amount;
            }
        }

        $balance = 0;
        $due = ($payable_amount - $paid_amount) > 0 ? $payable_amount - $paid_amount : 0;
        if ($paid_amount > $payable_amount) {
            $due = 0;
            $balance = $paid_amount - $payable_amount;
            $paid_amount = $payable_amount;
        }
        
        $product_discount = $discount_amount / $total_items;
        $product_tax = $order_tax / $total_items;
        $payment_status = $due > 0 ? 'due' : 'paid';
        if ($customer_id == 1 && $due > 0) {
            throw new Exception(trans('error_walking_customer_can_not_craete_due'));
        }
        $invoice_id = generate_invoice_id('sell');
        $item_tax = 0;
        $igst = 0;
        $cgst = 0;
        $sgst = 0;
        $tgst = 0;
        $tigst = 0;
        $tcgst = 0;
        $tsgst = 0;
        $total_purchase_price = 0;
        foreach ($product_items as $product) 
        {
            $product_id = $product['item_id'];
            $product_info = get_the_product($product_id);
            $category_id = $product['category_id'];
            $brand_id = $product_info['brand_id'];
            $sup_id = $product['sup_id'];
            $product_name = $product['item_name'];
            $product_quantity = $product['item_quantity'];
            $quantity_substract = $product_quantity;
            if ($product['p_type'] == 'service') {
                $quantity_substract = 0;
                $total_purchase_price += ($product_info['purchase_price']*$product_quantity);
            }
            $product_price = $product['item_price'];
            $product_total = $product['item_total'];
            $purchase_invoice_id = NULL;
            $item_purchase_price = 0;
            $tax_method = $product_info['tax_method'];
            $taxrate_id = $product_info['taxrate_id'];
            $taxrate = 0;
            $tax = 0;
            if ($product_info['taxrate']) {
                $taxrate = $product_info['taxrate']['taxrate'];
                $tax = $taxrate / 100 * ($product_info['sell_price'] * $product_quantity);
            }
            $item_tax += $tax;
            $quantity_exist = $product_quantity;
            $sell_quantity = $product_quantity;
            $inc = 1;
            while ($quantity_exist > 0) {
                
                if ($quantity_substract == 0) break;

                $statement = $this->db->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `item_id` = ? AND `status` = ? AND `item_quantity` > `total_sell`");
                $statement->execute(array($store_id, $product_id, 'active'));
                $purchase_item = $statement->fetch(PDO::FETCH_ASSOC);
                if (!$purchase_item) {
                    $statement = $this->db->prepare("UPDATE `purchase_item` SET `status` = ? WHERE `store_id` = ? AND `item_id` = ? AND `status` = ?");
                    $statement->execute(array('active', $store_id, $product_id, 'stock'));
                }
                $statement = $this->db->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `item_id` = ? AND `status` = ? AND `item_quantity` > `total_sell`");
                $statement->execute(array($store_id, $product_id, 'active'));
                $purchase_item = $statement->fetch(PDO::FETCH_ASSOC);
                if (!$purchase_item) {
                    $statement = $this->db->prepare("UPDATE `product_to_store` SET `quantity_in_stock` = ? WHERE `store_id` = ? AND `product_id` = ?");
                    $statement->execute(array(0, $store_id, $product_id));
                    throw new Exception('The product: '.$product_info['p_name'].' was out of stock, system has updated the product quantity as 0');
                }
                $purchase_invoice_id = $purchase_item['invoice_id'];
                $stock = $purchase_item['item_quantity'] - $purchase_item['total_sell'];
                if (number_format($stock, 3, '.', '') < number_format($quantity_exist, 3, '.', '')) {
                    $sell_quantity = $stock;
                    $quantity_exist = $quantity_exist - $stock;
                } else {
                    $sell_quantity = $quantity_exist;
                    $quantity_exist = 0;
                }

                $statement = $this->db->prepare("UPDATE `purchase_item` SET `total_sell` = `total_sell` + {$sell_quantity} WHERE `store_id` = ? AND `item_id` = ? AND `status` = ?");
                $statement->execute(array($store_id, $product_id, 'active'));
                $statement = $this->db->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `item_id` = ? AND `status` = ?");
                $statement->execute(array($store_id, $product_id, 'active'));
                $purchase_item = $statement->fetch(PDO::FETCH_ASSOC);
                $item_purchase_price += $purchase_item['item_purchase_price'] * $sell_quantity;
                if ($purchase_item['item_quantity'] <= $purchase_item['total_sell']) {
                    $statement = $this->db->prepare("UPDATE `purchase_item` SET `status` = ? WHERE `store_id` = ? AND `item_id` = ? AND `status` = ?");
                    $statement->execute(array('sold', $store_id, $product_id, 'active'));
                    $statement = $this->db->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `item_id` = ? AND `status` = ? ORDER BY `id` ASC LIMIT 1");
                    $statement->execute(array($store_id, $product_id, 'stock'));
                    $purchase_item = $statement->fetch(PDO::FETCH_ASSOC);
                    $statement = $this->db->prepare("UPDATE `purchase_item` SET `status` = ? WHERE `id` = ?");
                    $statement->execute(array('active', $purchase_item['id']));
                }
                $total_purchase_price += $item_purchase_price;
                $inc++;
                if ($inc > 1000) {
                    throw new Exception(trans('error_stock'));
                }
            }
            if (get_the_customer($customer_id, 'customer_state') == get_preference('business_state')) {
                $cgst = $tax / 2;
                $sgst = $tax / 2;
                $tcgst += $tax / 2;
                $tsgst += $tax / 2;
            } else {
                $igst = $tax;
                $tigst += $tax;
            }
            $statement = $this->db->prepare("INSERT INTO `selling_item` (invoice_id, store_id, item_id, category_id, brand_id, sup_id, item_name, item_purchase_price, item_price, item_discount, item_tax, tax_method, taxrate_id, tax, gst, cgst, sgst, igst, item_quantity, item_total, purchase_invoice_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array($invoice_id, $store_id, $product_id, $category_id, $brand_id, $sup_id, $product_name, $item_purchase_price, $product_price, $product_discount, $tax, $tax_method, $taxrate_id, $taxrate, $taxrate, $cgst, $sgst, $igst, $product_quantity, $product_total, $purchase_invoice_id));
            $statement = $this->db->prepare("UPDATE `product_to_store` SET `quantity_in_stock` = `quantity_in_stock` - {$quantity_substract} WHERE `store_id` = ? AND `product_id` = ?");
            $statement->execute(array($store_id, $product_id));
        }

        if (get_the_customer($customer_id, 'customer_state') == get_preference('business_state')) {
            $tcgst += ($order_tax / 2);
            $tsgst += ($order_tax / 2);
        } else {
            $tigst += $order_tax;
        }

        $capital = ($total_purchase_price / ($subtotal -$discount_amount)) * ($paid_amount - $shipping_amount - $others_charge);
        $profit = ($subtotal -$discount_amount) - $total_purchase_price;
        
        $statement = $this->db->prepare("INSERT INTO `selling_info` (invoice_id, store_id, customer_id, customer_mobile, invoice_note, total_items, payment_status, is_installment, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->execute(array($invoice_id, $store_id, $customer_id, $customer_mobile, $invoice_note, $total_items, $payment_status, $is_installment_order, $user_id, $created_at));
        
        $statement = $this->db->prepare("INSERT INTO `selling_price` (invoice_id, store_id, subtotal, discount_type, discount_amount, interest_amount, interest_percentage, item_tax, order_tax, cgst, sgst, igst, total_purchase_price, shipping_type, shipping_amount, others_charge, previous_due, payable_amount, paid_amount, due, prev_due_paid, profit, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->execute(array($invoice_id, $store_id, $subtotal, $discount_type, $discount_amount, $installment_interest_amount, $installment_interest_percentage, $item_tax, $order_tax, $tcgst, $tsgst, $tigst, $total_purchase_price, $shipping_type, $shipping_amount, $others_charge, $previous_due, $payable_amount, $paid_amount, $due, $prev_due_paid, $profit, $balance));
        
        if ($paid_amount > 0) {
            $statement = $this->db->prepare("INSERT INTO `payments` (store_id, invoice_id, pmethod_id, capital, amount, details, note, total_paid, pos_balance, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array($store_id, $invoice_id, $pmethod_id, $capital, $paid_amount, $details, $invoice_note, $total_paid, $balance, $user_id, $created_at));
            $statement = $this->db->prepare("UPDATE `selling_info` SET `pmethod_id` = ?, `checkout_status` = ? WHERE `invoice_id` = ? AND `store_id` = ?");
            $statement->execute(array($pmethod_id, 1, $invoice_id, $store_id));
        }

        if ($pmethod_code == 'credit' && $paid_amount > 0) {
            $statement = $this->db->prepare("UPDATE `customer_to_store` SET `balance` = `balance` - {$paid_amount} WHERE `store_id` = ? AND `customer_id` = ?");
            $statement->execute(array($store_id, $customer_id));

            $customer_balance = get_customer_balance($customer_id);
            $reference_no = generate_customer_transacton_ref_no('substract');
            $statement = $this->db->prepare("INSERT INTO `customer_transactions` (customer_id, reference_no, type, pmethod_id, notes, amount, balance, store_id, ref_invoice_id, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array($customer_id, $reference_no, 'substract_balance', $pmethod_id, 'Substract while selling', $paid_amount, $customer_balance, $store_id, $invoice_id, $user_id, $created_at));
        }

        $reference_no = generate_sell_log_ref_no('sell');
        $statement = db()->prepare("INSERT INTO `sell_logs` (customer_id, reference_no, type, pmethod_id, description, amount, store_id, ref_invoice_id, created_by, created_at) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array($customer_id, $reference_no, 'sell', $pmethod_id,  'Paid while selling', $paid_amount, $store_id, $invoice_id, $user_id, $created_at));
        
        if ($due > 0) {
            $statement = $this->db->prepare("UPDATE `customer_to_store` SET `due` = `due` + {$due}  WHERE `store_id` = ? AND `customer_id` = ?");
            $statement->execute(array($store_id, $customer_id));
        }

        if ($is_card_payments && $card_no) {
            $statement = $this->db->prepare("UPDATE `gift_cards` SET `balance` = `balance` - {$paid_amount}  WHERE `card_no` = ?");
            $statement->execute(array($card_no));
        }
        
        if (($account_id = store('deposit_account_id')) && $paid_amount > 0) {
            $ref_no = unique_transaction_ref_no();
            $statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_sell` = ?");
            $statement->execute(array(1));
            $source = $statement->fetch(PDO::FETCH_ASSOC);
            $source_id = $source['source_id'];
            $title = 'Deposit for selling';
            $details = 'Customer name: ' . get_the_customer($customer_id, 'customer_name');
            $image = 'NULL';
            $deposit_amount = $paid_amount;
            $transaction_type = 'deposit';
			
            $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array($store_id, $account_id, $source_id, $ref_no, $invoice_id, $transaction_type, $title, $details, $image, $user_id, $created_at));
            $info_id = $this->db->lastInsertId();
						
			$statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
            $statement->execute(array($store_id, $info_id, $ref_no, $deposit_amount));
            
            $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + {$deposit_amount} WHERE `store_id` = ? AND `account_id` = ?");
            $statement->execute(array($store_id, $account_id));
            $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + {$deposit_amount} WHERE `id` = ?");
            $statement->execute(array($account_id));
        }

        if ($qref) {
            $statement = $this->db->prepare("UPDATE `quotation_info` SET `status` = ?, `invoice_id` = ? WHERE `reference_no` = ?");
            $statement->execute(array('complete', $invoice_id, $qref));
        }

        if (INSTALLMENT && $request->post['is_installment_order']) {
            $statement = $this->db->prepare("INSERT INTO `installment_orders` (store_id, invoice_id, duration, interval_count, installment_count, interest_percentage, interest_amount, initial_amount, last_installment_date, installment_end_date, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $statement->execute(array($store_id, $invoice_id, $installment_duration, $installment_interval_count, $installment_count, $installment_interest_percentage, $installment_interest_amount, $installment_initial_amount, $created_at, $installmentEndDate, $created_at));
            $initInterestAmount = ($installment_interest_percentage / 100) * ($paid_amount-$installment_interest_amount);
            $eachInstallmentAmount = $due / $installment_count;
            $eachInstallmentInterestAmount = ($installment_interest_amount - $initInterestAmount) / $installment_count;
            if ($paid_amount > 0) {
                $capital = ($total_purchase_price / $subtotal) * ($paid_amount - $initInterestAmount);
                $statement = $this->db->prepare("INSERT INTO `installment_payments` (store_id, invoice_id, payment_date, created_by, note, capital, interest, payable, paid, payment_status) VALUES (?,?,?,?,?,?,?,?,?,?)");
                $statement->execute(array($store_id, $invoice_id, date_time(), $user_id, 'Initial Payment', $capital, $initInterestAmount, $paid_amount, $paid_amount, 'paid'));
            }
            $interval = $installment_interval_count;
            for ($i = 0; $i < $installment_count; $i++) {
                $capital = $eachInstallmentAmount > 0 ? ($total_purchase_price / $subtotal) * ($eachInstallmentAmount - $eachInstallmentInterestAmount) : 0;
                $payment_date = date('Y-m-d', strtotime(' + ' . $interval . ' days'));
                $payment_status = $eachInstallmentAmount > 0 ? 'due' : 'paid';
                $statement = $this->db->prepare("INSERT INTO `installment_payments` (store_id, invoice_id, payment_date, created_by, capital, interest, payable, due, payment_status) VALUES (?,?,?,?,?,?,?,?,?)");
                $statement->execute(array($store_id, $invoice_id, $payment_date, $user_id, $capital, $eachInstallmentInterestAmount, $eachInstallmentAmount, $eachInstallmentAmount, $payment_status));
                $interval += $installment_interval_count;
            }
        }
        return $invoice_id;
    }

    function duePaid($data, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $user_id = user_id();
        $created_at = date_time();
        $invoice_id = $data['invoice-id'];
        $customer_id = $data['customer-id'];
        $pmethod_id = $data['pmethod-id'];
        $pmethod_code = get_the_pmethod($pmethod_id,'code_name');
        $invoice_price = $this->getSellingPrice($invoice_id, $store_id);
        // $discount_amount = $data['discount-amount'] ? $data['discount-amount'] : 0;
        $discount_amount = 0;
        $payable_amount = $invoice_price['payable_amount'] - ($invoice_price['paid_amount'] + $invoice_price['return_amount'] );
        $paid_amount = $data['paid-amount'] ? $data['paid-amount'] : 0;
        if ($discount_amount > $payable_amount) {
          throw new Exception(trans('error_discount_amount_exceed'));
        }
        $total_paid = $paid_amount;
        $note = $data['note'];

        $details_raw = isset($data['payment_details']) ? $data['payment_details'] : array();
        $details = serialize($details_raw);

        // Cart Payment
        $is_card_payments = false;
        $card_no = '';
        if (isset($details_raw['card_no'])) {
          $card_no = $details_raw['card_no'];
          $statement = db()->prepare("SELECT * FROM `gift_cards` WHERE `customer_id` = ? AND `card_no` = ? AND `balance` >= ? AND `expiry` > NOW()");
          $statement->execute(array($customer_id, $card_no, $payable_amount));
          $row = $statement->fetch(PDO::FETCH_ASSOC);
          if ($row) {
            $is_card_payments = true;
            $paid_amount = $row['balance'];
          } else {
            throw new Exception(trans('error_not_found_or_insufficient_balance'));
          }
        }

        // Credit Payment
        if ($pmethod_code == 'credit') {
          if ($payable_amount > get_customer_balance($customer_id)) {
              throw new Exception(trans('error_insufficient_balance'));
          }
          $paid_amount = $payable_amount;
        }

        $due = $invoice_price['due'] - ($paid_amount+$discount_amount);
        $due = $due > 0 ? $due : 0;
        $balance = 0;
        if ($paid_amount > $payable_amount) {
          $due = 0;
          $balance = $paid_amount - $payable_amount;
          $paid_amount = $payable_amount;
        }

        if ($paid_amount <= 0 && $discount_amount <= 0) {
          throw new Exception(trans('error_paid_amount'));
        }

        // Credit Payment
        if ($pmethod_code == 'credit') {
            $statement = db()->prepare("UPDATE `customer_to_store` SET `balance` = `balance` - {$paid_amount} WHERE `store_id` = ? AND `customer_id` = ?");
            $statement->execute(array($store_id, $customer_id));
        }

        $partial_shipping_charge = ($invoice_price['shipping_amount'] / $invoice_price['payable_amount']) * $paid_amount;
        $partial_others_charge = ($invoice_price['others_charge'] / $invoice_price['payable_amount']) * $paid_amount;
        $partial_order_tax = ($invoice_price['order_tax'] / $invoice_price['payable_amount']) * $paid_amount;
        $capital = (($invoice_price['total_purchase_price'] / $invoice_price['payable_amount']) * $paid_amount) + $partial_order_tax + $partial_shipping_charge + $partial_others_charge;

        if ($paid_amount > 0 && $discount_amount <= 0) {
          $statement = db()->prepare("INSERT INTO `payments` (type, store_id, invoice_id, pmethod_id, capital, amount, details, note, total_paid, pos_balance, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $statement->execute(array('due_paid', $store_id, $invoice_id, $pmethod_id, $capital, $paid_amount, $details, $note, $total_paid, $balance, $user_id, $created_at));
        }
        if ($discount_amount > 0) {
          $statement = db()->prepare("INSERT INTO `payments` (type, store_id, invoice_id, amount, details, note, total_paid, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $statement->execute(array('discount', $store_id, $invoice_id, $discount_amount, $details, 'discount_whilte_due_paid', $discount_amount, $user_id, $created_at));
          
          $statement = db()->prepare("UPDATE `selling_price` SET `discount_amount` = `discount_amount`+$discount_amount, `payable_amount` = `payable_amount`-$discount_amount WHERE `invoice_id` = ? AND `store_id` = ?");
          $statement->execute(array($invoice_id, $store_id));
        }

        // Checkout status
        $statement = db()->prepare("UPDATE `selling_info` SET `checkout_status` = ? WHERE `invoice_id` = ? AND `store_id` = ?");
        $statement->execute(array(1, $invoice_id, $store_id));

        // Add Paid Amount
        $statement = db()->prepare("UPDATE `selling_price` SET `paid_amount` = `paid_amount`+$paid_amount, `due_paid` = `due_paid`+$paid_amount, `due` = ? WHERE `invoice_id` = ? AND `store_id` = ?");
        $statement->execute(array($due, $invoice_id, $store_id));

        // Update payment status
        if ($due <= 0) {
          $statement = db()->prepare("UPDATE `selling_info` SET `payment_status` = ? WHERE `invoice_id` = ? AND `store_id` = ?");
          $statement->execute(array('paid', $invoice_id, $store_id));
        }

        // Update customer due
        $statement = db()->prepare("UPDATE `customer_to_store` SET `due` = `due` - {$paid_amount}  WHERE `store_id` = ? AND `customer_id` = ?");
        $statement->execute(array($store_id, $customer_id));

        // Decrease card balance
        if ($paid_amount > 0 && $is_card_payments && $card_no) {
            $statement = db()->prepare("UPDATE `gift_cards` SET `balance` = `balance` - $paid_amount  WHERE `card_no` = ?");
            $statement->execute(array($card_no));
        }

        // Add customer transaction
        if ($pmethod_code == 'credit' && $paid_amount > 0) {
          $customer_balance = get_customer_balance($customer_id)-$paid_amount;
          $reference_no = generate_customer_transacton_ref_no('due_paid');
          $statement = db()->prepare("INSERT INTO `customer_transactions` (customer_id, reference_no, type, pmethod_id, notes, amount, balance, store_id, ref_invoice_id, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $statement->execute(array($customer_id, $reference_no, 'due_paid', $pmethod_id, 'Substract while due paid', $paid_amount, $customer_balance, $store_id, $invoice_id, $user_id, $created_at));
        }

        // Deposit
        if (($account_id = store('deposit_account_id')) && $paid_amount > 0) {
          $ref_no = unique_transaction_ref_no();
          $statement = db()->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_due_collection` = ?");
          $statement->execute(array(1));
          $source = $statement->fetch(PDO::FETCH_ASSOC);
          $source_id = $source['source_id'];
          $title = 'Deposit for due collection';
          $details = 'Customer name: ' . get_the_customer($customer_id, 'customer_name');
          $image = 'NULL';
          $deposit_amount = $paid_amount;
          $transaction_type = 'deposit';

          $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $statement->execute(array($store_id, $account_id, $source_id, $ref_no, $invoice_id, $transaction_type, $title, $details, $image, $user_id, $created_at));
            $info_id = db()->lastInsertId();
            
          $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
          $statement->execute(array($store_id, $info_id, $ref_no, $deposit_amount));

          $statement = db()->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
          $statement->execute(array($store_id, $account_id));

          $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
          $statement->execute(array($account_id));
        }

        if ($paid_amount > 0) {
          $reference_no = generate_sell_log_ref_no('due_paid');
          $statement = db()->prepare("INSERT INTO `sell_logs` (customer_id, reference_no, type, pmethod_id, description, amount, store_id, ref_invoice_id, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $statement->execute(array($customer_id, $reference_no, 'due_paid', $pmethod_id, 'Due paid', $paid_amount, $store_id, $invoice_id, $user_id, $created_at));
        }

        if ($balance > 0) {
          $statement = db()->prepare("UPDATE `selling_price` SET `balance` = ? WHERE `store_id` = ? AND `invoice_id` = ?");
          $statement->execute(array($balance, $store_id, $invoice_id));
        }

        return $invoice_id;
    }

    public function getDueInvoices($customer_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.*, `customers`.`customer_id`, `customers`.`customer_name`, `customers`.`customer_mobile`, `customers`.`customer_email` FROM `selling_info` LEFT JOIN `selling_price` ON `selling_info`.`invoice_id` = `selling_price`.`invoice_id` LEFT JOIN `customers` ON `selling_info`.`customer_id` = `customers`.`customer_id` WHERE `selling_info`.`store_id` = ? AND `selling_info`.`customer_id` = ? AND `selling_price`.`due` > 0 AND `selling_info`.`inv_type` = ?");
        $statement->execute(array($store_id, $customer_id, 'sell'));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getInvoices($type, $store_id = null, $limit = 100000)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.*, `customers`.`customer_id`, `customers`.`customer_name`, `customers`.`customer_mobile`, `customers`.`customer_email` FROM `selling_info` LEFT JOIN `selling_price` ON `selling_info`.`invoice_id` = `selling_price`.`invoice_id` LEFT JOIN `customers` ON `selling_info`.`customer_id` = `customers`.`customer_id` WHERE `selling_info`.`store_id` = ? AND `selling_info`.`inv_type` = ? ORDER BY `selling_info`.`created_at` DESC LIMIT {$limit}");
        $statement->execute(array($store_id, $type));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getInvoiceInfo($invoice_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.*, `customers`.`customer_id`, `customers`.`customer_name`, `customers`.`customer_mobile` AS `mobile_number`, `customers`.`customer_email` FROM `selling_info` LEFT JOIN `selling_price` ON `selling_info`.`invoice_id` = `selling_price`.`invoice_id` LEFT JOIN `customers` ON `selling_info`.`customer_id` = `customers`.`customer_id` WHERE `selling_info`.`store_id` = ? AND (`selling_info`.`invoice_id` = ? OR (`selling_info`.`customer_id` = ?) AND `selling_info`.`inv_type` = 'sell') ORDER BY `selling_info`.`invoice_id` DESC");
        $statement->execute(array($store_id, $invoice_id, $invoice_id));
        $invoice = $statement->fetch(PDO::FETCH_ASSOC);
        if ($invoice) {
            $invoice['by'] = get_the_user($invoice['created_by'], 'username');
        }
        return $invoice;
    }
    
    public function getInvoiceItems($invoice_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT * FROM `selling_item` WHERE `store_id` = ? AND `invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $array = array();
        $i = 0;
        foreach ($rows as $row) {
            $array[$i] = $row;
            $array[$i]['unitName'] = get_the_unit(get_the_product($row['item_id'])['unit_id'], 'unit_name');
            $i++;
        }
        return $array;
    }
    
    public function getInvoiceItemsHTML($invoice_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT * FROM `selling_item` WHERE `store_id` = ? AND `invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        $html = '<table class="table table-bordered mb-0">';
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
        $html .= '<tr class="bg-warning">';
        $html .= '<td class="text-right" style="padding:0 2px;">Total</td>';
        $html .= '<td class="text-right" style="padding:0 2px;">' . currency_format($sell) . '</td>';
        $html .= '<td class="text-center" style="padding:0 2px;">' . currency_format($qty) . '</td>';
        $html .= '<td class="text-right" style="padding:0 2px;">' . currency_format($total) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }

    public function getInvoiceItemInfo($invoice_id, $item_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT * FROM `selling_item` WHERE `store_id` = ? AND  `invoice_id` = ? AND `item_id` = ?");
        $statement->execute(array($store_id, $invoice_id, (int) $item_id));
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getInvoiceItemTaxes($invoice_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT SUM(`item_quantity`) as qty, SUM(`tax`) as tax, item_tax, `taxrates`.`taxrate_name`, `taxrates`.`code_name` FROM `selling_item` LEFT JOIN `taxrates` ON (`selling_item`.`taxrate_id` = `taxrates`.`taxrate_id`) WHERE `store_id` = ? AND invoice_id = ? GROUP BY `selling_item`.`taxrate_id`");
        $statement->execute(array($store_id, $invoice_id));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getSellingPrice($invoice_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT * FROM `selling_price` WHERE `store_id` = ? AND invoice_id = ?");
        $statement->execute(array($store_id, $invoice_id));
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function hasInvoice($invoice_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT * FROM `selling_info` WHERE `selling_info`.`store_id` = ? AND `selling_info`.`invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));
        $invoice = $statement->fetch(PDO::FETCH_ASSOC);
        return isset($invoice['invoice_id']);
    }
    
    public function isLastInvoice($customer_id, $invoice_id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statemtnt = $this->db->prepare("SELECT * FROM `selling_info` WHERE `store_id` = ? AND `customer_id` = ? AND `inv_type` = ? AND info_id=(SELECT max(`info_id`) FROM `selling_info`)");
        $statemtnt->execute(array($store_id, $customer_id, 'sell'));
        $row = $statemtnt->fetch(PDO::FETCH_ASSOC);
        return $row['invoice_id'] == $invoice_id;
    }
    
    public function getLastInvoice($type = 'sell', $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statemtnt = $this->db->prepare("SELECT * FROM `selling_info` WHERE `store_id` = ? AND `inv_type` = ? ORDER BY `info_id` DESC LIMIT 0,1");
        $statemtnt->execute(array($store_id, $type));
        return $statemtnt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getNextInvoice($customer_id, $invoice_id, $type = 'sell', $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statemtnt = $this->db->prepare("SELECT * FROM `selling_info` WHERE `store_id` = ? AND `customer_id` = ? AND `inv_type` = ? AND info_id=(SELECT max(`info_id`) FROM `selling_info`)");
        $statemtnt->execute(array($store_id, $customer_id, $type));
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
        $where_query = "`store_id` = ? AND `inv_type` = 'sell'";
        $where_query .= date_range_filter($from, $to);
        $statement = $this->db->prepare("SELECT * FROM `selling_info` WHERE {$where_query}");
        $statement->execute(array($store_id));
        return $statement->rowCount();
    }
    
    public function total($from = null, $to = null, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $where_query = "`store_id` = ? AND `inv_type` = 'sell'";
        if ($from) {
            $where_query .= date_range_filter($from, $to);
        }
        $statement = $this->db->prepare("SELECT * FROM `selling_info` WHERE {$where_query}");
        $statement->execute(array($store_id));
        return $statement->rowCount();
    }

    public function totalHoldingOrderToday($store_id = null)
    {
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        $store_id = $store_id ? $store_id : store_id();
        $where_query = "`store_id` = ?";
        $where_query .= date_range_holding_order_filter($from, $to);
        $statement = $this->db->prepare("SELECT * FROM `holding_info` WHERE {$where_query}");
        $statement->execute(array($store_id));
        return $statement->rowCount();
    }
    
    public function totalHoldingOrder($from = null, $to = null, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $where_query = "`store_id` = ?";
        if ($from) {
            $where_query .= date_range_holding_order_filter($from, $to);
        }
        $statement = $this->db->prepare("SELECT * FROM `holding_info` WHERE {$where_query}");
        $statement->execute(array($store_id));
        return $statement->rowCount();
    }
}