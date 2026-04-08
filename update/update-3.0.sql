ALTER TABLE `buying_info` CHANGE `inv_type` `inv_type` ENUM('purchase','transfer') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'purchase';
ALTER TABLE `buying_info` CHANGE `status` `status` ENUM('stock','active','sold') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'stock';

ALTER TABLE `buying_item` CHANGE `tax_method` `tax_method` ENUM('exclusive','inclusive') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'exclusive';
ALTER TABLE `buying_item` CHANGE `status` `status` ENUM('stock','active','sold') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'stock';

ALTER TABLE `selling_item` CHANGE `tax_method` `tax_method` ENUM('exclusive','inclusive') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'exclusive';
ALTER TABLE `selling_price` CHANGE `discount_type` `discount_type` ENUM('plain','percentage') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'plain';
ALTER TABLE `selling_price` CHANGE `shipping_type` `shipping_type` ENUM('plain','percentage') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'plain';


-- -----------------------------------------
-- RENAME TABLE
-- -----------------------------------------
RENAME TABLE `buying_info` TO `purchase_info`;
RENAME TABLE `buying_item` TO `purchase_item`;
RENAME TABLE `buying_payments` TO `purchase_payments`;
RENAME TABLE `buying_price` TO `purchase_price`;
RENAME TABLE `buying_returns` TO `purchase_returns`;
RENAME TABLE `buying_return_items` TO `purchase_return_items`;


-- -----------------------------------------
-- ADD A COLUMN TO A TABLE
-- -----------------------------------------
ALTER TABLE `bank_transaction_info` ADD  `is_substract` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `bank_transaction_info` ADD `source_id` int(10) DEFAULT NULL;
ALTER TABLE `bank_transaction_info` ADD `exp_category_id` int(10) DEFAULT NULL;
ALTER TABLE `bank_transaction_info` ADD `invoice_id` varchar(100) DEFAULT NULL;
ALTER TABLE `bank_transaction_price` ADD `info_id` int(11) DEFAULT NULL;
ALTER TABLE `boxes` ADD `code_name` varchar(55) DEFAULT NULL;
ALTER TABLE `purchase_item` ADD `brand_id` int(10) DEFAULT NULL;
ALTER TABLE `purchase_item` ADD `return_quantity` decimal(25,4) NOT NULL DEFAULT '0.0000';

ALTER TABLE `purchase_payments` ADD `is_hide` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `purchase_price` ADD `subtotal` decimal(25,4) DEFAULT NULL;
ALTER TABLE `purchase_price` ADD `discount_type` enum('percentage','plain') NOT NULL DEFAULT 'plain';
ALTER TABLE `purchase_price` ADD `discount_amount` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `purchase_price` ADD `shipping_type` enum('plain','percentage') NOT NULL DEFAULT 'plain';
ALTER TABLE `purchase_price` ADD `shipping_amount` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `purchase_price` ADD `others_charge` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `purchase_price` ADD `return_amount` decimal(25,4) UNSIGNED NOT NULL DEFAULT '0.0000';
ALTER TABLE `purchase_price` ADD `balance` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `purchase_info` ADD `shipping_status` enum('received','pending','ordered') NOT NULL DEFAULT 'received';

ALTER TABLE `purchase_returns` ADD `subtotal` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `purchase_returns` ADD `item_tax` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `purchase_returns` ADD `cgst` decimal(25,4) DEFAULT NULL;
ALTER TABLE `purchase_returns` ADD `sgst` decimal(25,4) DEFAULT NULL;
ALTER TABLE `purchase_returns` ADD `igst` decimal(25,4) DEFAULT NULL;
ALTER TABLE `purchase_returns` ADD `profit` INT NOT NULL;
ALTER TABLE `purchase_returns` ADD `updated_at` INT NOT NULL;

ALTER TABLE `returns` ADD `subtotal` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `returns` ADD `item_tax` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `returns` ADD `cgst` decimal(25,4) DEFAULT NULL;
ALTER TABLE `returns` ADD `sgst` decimal(25,4) DEFAULT NULL;
ALTER TABLE `returns` ADD `igst` decimal(25,4) DEFAULT NULL;
ALTER TABLE `returns` ADD `total_purchase_price` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `returns` ADD `profit` decimal(25,4) UNSIGNED NOT NULL DEFAULT '0.0000';
ALTER TABLE `returns` ADD `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `return_items` ADD `item_purchase_price` decimal(25,4) DEFAULT NULL;
ALTER TABLE `return_items` ADD `item_price` decimal(25,4) NOT NULL;
ALTER TABLE `return_items` ADD `item_tax` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `return_items` ADD `cgst` decimal(25,4) DEFAULT NULL;
ALTER TABLE `return_items` ADD `sgst` decimal(25,4) DEFAULT NULL;
ALTER TABLE `return_items` ADD `igst` decimal(25,4) DEFAULT NULL;
ALTER TABLE `return_items` ADD `item_total` decimal(25,4) NOT NULL;
ALTER TABLE `return_items` ADD `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `selling_info` ADD `is_installment` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `selling_item` ADD `return_quantity` decimal(25,4) DEFAULT '0.0000';
ALTER TABLE `selling_price` ADD `interest_amount` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `selling_price` ADD `interest_percentage` int(10) NOT NULL DEFAULT '0';
ALTER TABLE `selling_price` ADD `total_purchase_price` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `selling_price` ADD `others_charge` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `selling_price` ADD `return_amount` decimal(25,4) UNSIGNED NOT NULL DEFAULT '0.0000';
ALTER TABLE `selling_price` ADD `balance` decimal(25,4) DEFAULT '0.0000';

ALTER TABLE `selling_price` ADD `profit` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `selling_price` ADD `previous_due` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `selling_price` ADD `prev_due_paid` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `sms_schedule` ADD `people_sms_type` varchar(50) DEFAULT NULL;
ALTER TABLE `sms_schedule` ADD `total_try` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `sms_setting` ADD `unicode` varchar(20) DEFAULT NULL;
ALTER TABLE `sms_setting` ADD `country_code` varchar(20) DEFAULT NULL;
ALTER TABLE `stores` ADD `code_name` varchar(55) DEFAULT NULL;
ALTER TABLE `stores` ADD `deposit_account_id` int(11) DEFAULT NULL;
ALTER TABLE `stores` ADD `email` varchar(100) DEFAULT NULL;
ALTER TABLE `stores` ADD `thumbnail` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `suppliers` ADD `code_name` varchar(55) DEFAULT NULL;
ALTER TABLE `suppliers` ADD `gtin` varchar(100) DEFAULT NULL;
ALTER TABLE `units` ADD `code_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE `users` ADD `dob` date DEFAULT NULL;
ALTER TABLE `users` ADD `sex` varchar(10) NOT NULL DEFAULT 'M';
ALTER TABLE `users` ADD `login_try` tinyint(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `users` ADD `last_login` datetime DEFAULT NULL;
ALTER TABLE `users` ADD `address` text CHARACTER SET utf8,
ALTER TABLE `users` ADD `user_image` varchar(255) DEFAULT NULL;

ALTER TABLE `product_to_store` ADD `preference` longtext CHARACTER SET utf8;

ALTER TABLE `bank_transaction_info` ADD `is_hide`  TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `categorys` ADD `category_image` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `dob` DATE NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `gtin`VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `password` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `raw_password` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `updated_at` DATETIME on update CURRENT_TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE `customer_to_store` ADD `due` DECIMAL(25,4) UNSIGNED NOT NULL DEFAULT '0.0000';
ALTER TABLE `customer_transactions` ADD `balance` DECIMAL(25,4) NULL DEFAULT '0.0000';
ALTER TABLE `customer_transactions` ADD `notes` text CHARACTER SET utf8;

ALTER TABLE `expenses` ADD `returnable` ENUM('no','yes') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'no';
ALTER TABLE `expenses` ADD `attachment` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `expense_categorys` ADD `sell_return` TINYINT(1) NOT NULL DEFAULT '0', ADD `sell_delete` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `expense_categorys` ADD `loan_delete` TINYINT(1) NOT NULL DEFAULT '0', ADD `loan_payment` TINYINT(1) NOT NULL DEFAULT '0', ADD `giftcard_sell_delete` TINYINT(1) NOT NULL DEFAULT '0', ADD `topup_delete` TINYINT(1) NOT NULL DEFAULT '0', ADD `product_purchase` TINYINT(1) NOT NULL DEFAULT '0', ADD `stock_transfer` TINYINT(1) NOT NULL DEFAULT '0', ADD `due_paid` TINYINT(1) NOT NULL, ADD `is_hide` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `gift_cards` CHANGE `date` `date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `loans` ADD `status` TINYINT(1) NOT NULL DEFAULT '1', ADD `sort_order` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `payments` ADD `is_profit` TINYINT(1) NOT NULL DEFAULT '1', ADD `is_hide` TINYINT(1) NOT NULL DEFAULT '0', ADD `capital`  DECIMAL(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `pmethods` ADD `code_name`  VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `products` ADD `p_type`  ENUM('standard','service') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'standard', ADD `barcode_symbology`  VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `product_to_store` CHANGE `buy_price` `purchase_price` FLOAT NOT NULL DEFAULT '0', ADD `brand_id` INT(10) NULL DEFAULT NULL;
ALTER TABLE `purchase_return_items` ADD `item_price` DECIMAL(25,4) NOT NULL, ADD `item_tax`DECIMAL(25,4) NOT NULL DEFAULT '0.0000', ADD `cgst` DECIMAL(25,4) NULL DEFAULT NULL, ADD `sgst`  DECIMAL(25,4) NULL DEFAULT NULL, ADD `igst`  DECIMAL(25,4) NULL DEFAULT NULL, ADD `item_total`  DECIMAL(25,4) NOT NULL, ADD `created_at`  DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- -------------------------------------------
-- CHANGE COLUMN NAME
-- ------------------------------------------
ALTER TABLE `purchase_item` CHANGE `item_buying_price` `item_purchase_price` decimal(25,4) NOT NULL;
ALTER TABLE `purchase_return_items` CHANGE `product_id` `item_id` int(11) NOT NULL,
	CHANGE `product_name` `item_name` varchar(255) NOT NULL,
	CHANGE `quantity` `item_quantity` decimal(15,4) NOT NULL;
ALTER TABLE `return_items` CHANGE `product_id` `item_id` int(11) NOT NULL,
	CHANGE `product_name` `item_name` varchar(255) NOT NULL,
	CHANGE `quantity` `item_quantity` decimal(15,4) NOT NULL;

-- -----------------------------------------
-- CHANGE COLUMN DATATYPE
-- -----------------------------------------
ALTER TABLE `purchase_payments` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `customer_transactions` CHANGE `type` `type` enum('purchase','add_balance','substract_balance','due_paid','others') NOT NULL;
ALTER TABLE `pmethods` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `bank_accounts` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `bank_accounts` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `bank_accounts` CHANGE `opening_date` `opening_date` datetime DEFAULT NULL;
ALTER TABLE `bank_transaction_info` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `bank_transaction_info` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `purchase_info` CHANGE `inv_type` `inv_type` ENUM('purchase','transfer') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'purchase';
UPDATE `purchase_info` SET `inv_type` = 'purchase' WHERE `inv_type` = '';
ALTER TABLE `purchase_info` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `purchase_info` CHANGE `invoice_note` `purchase_note` text CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE `purchase_info` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `purchase_payments` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `purchase_returns` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `categorys` CHANGE `update_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `categorys` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `customer_transactions` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `customer_transactions` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `expenses` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `expenses` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `expense_categorys` CHANGE `update_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `expense_categorys` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `gift_card_topups` CHANGE `date` `date` datetime DEFAULT NULL;
ALTER TABLE `loans` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `loans` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `loan_payments` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `loan_payments` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `payments` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `printers` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `returns` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `selling_info` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `selling_info` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `selling_item` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `settings` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `settings` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `sms_schedule` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `sms_setting` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `sms_setting` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `sms_setting` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `transfers` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `users` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `users` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `supplier_transactions` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `supplier_transactions` CHANGE `created_at` `created_at` datetime DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `selling_item` CHANGE `total_buying_price` `item_purchase_price` decimal(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `selling_item` CHANGE `buying_invoice_id` `purchase_invoice_id` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `purchase_info` CHANGE `buy_date` `purchase_date` DATETIME NULL;
ALTER TABLE `selling_item` ADD `brand_id` INT(10) NULL DEFAULT NULL;

ALTER TABLE `taxrates` CHANGE `taxrate_code` `code_name` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

-- -----------------------------------------
-- DELETE COLUMN
-- -----------------------------------------
ALTER TABLE `purchase_returns` DROP COLUMN `profit`;
ALTER TABLE `purchase_return_items` DROP COLUMN `amount`;
ALTER TABLE `return_items` DROP COLUMN `amount`;

-- -----------------------------------------
-- DELETE TABLES
-- -----------------------------------------
DROP TABLE `pos_receipt_template`; 
DROP TABLE `loan_to_store`;
DROP TABLE `supplier_transactions`;
DROP TABLE `sync`;

-- -----------------------------------------
-- ADD INDEX IN TABLES COLUMN
-- -----------------------------------------
ALTER TABLE `bank_account_to_store` ADD INDEX(`account_id`);
ALTER TABLE `category_to_store` ADD INDEX(`ccategory_id`);
ALTER TABLE `currency_to_store` ADD INDEX(`currency_id`);
ALTER TABLE `customer_transactions` ADD INDEX(`customer_id`);
ALTER TABLE `loan_payments` ADD INDEX(`lloan_id`);
ALTER TABLE `payments` ADD INDEX(`invoice_id`);
ALTER TABLE `pmethod_to_store` ADD INDEX(`ppmethod_id`);
ALTER TABLE `printer_to_store` ADD INDEX(`pprinter_id`);
ALTER TABLE `product_to_store` ADD INDEX(`product_id`);
ALTER TABLE `product_to_store` ADD INDEX(`p_date`);
ALTER TABLE `purchase_info` ADD INDEX(`created_at`);
ALTER TABLE `purchase_info` ADD INDEX(`invoice_id`);
ALTER TABLE `purchase_item` ADD INDEX(`invoice_id`);
ALTER TABLE `purchase_item` ADD INDEX(`item_id`);
ALTER TABLE `purchase_payments` ADD INDEX(`invoice_id`);
ALTER TABLE `purchase_price` ADD INDEX(`invoice_id`);
ALTER TABLE `purchase_return_items` ADD INDEX(`item_id`);
ALTER TABLE `purchase_return_items` ADD INDEX(`invoice_id`);
ALTER TABLE `returns` ADD INDEX(`invoice_id`);
ALTER TABLE `selling_info` ADD INDEX(`created_at`);
ALTER TABLE `selling_info` ADD INDEX(`invoice_id`);
ALTER TABLE `selling_item` ADD INDEX(`invoice_id`);
ALTER TABLE `selling_item` ADD INDEX(`item_id`);
ALTER TABLE `selling_price` ADD INDEX(`invoice_id`);
ALTER TABLE `sms_schedule` ADD INDEX(`store_id`);
ALTER TABLE `sms_schedule` ADD INDEX(`people_type`);
ALTER TABLE `supplier_to_store` ADD INDEX(`sup_id`);
ALTER TABLE `unit_to_store` ADD INDEX(`uunit_id`);
ALTER TABLE `users` ADD INDEX(`group_id`);
ALTER TABLE `user_to_store` ADD INDEX(`user_id`);


ALTER TABLE `bank_transaction_info` ADD INDEX(`created_at`);
ALTER TABLE `bank_transaction_info` ADD INDEX(`ref_no`);
ALTER TABLE `bank_transaction_price` ADD INDEX(`ref_no`);
ALTER TABLE `purchase_returns` ADD INDEX(`invoice_id`);

-- -----------------------------------------
-- DELETE INDEX
-- -----------------------------------------

ALTER TABLE `returns` DROP INDEX `id`;
ALTER TABLE `transfers` DROP INDEX `id`;
ALTER TABLE `purchase_return_items` DROP INDEX `product_id`;
ALTER TABLE `purchase_return_items` DROP INDEX `transfer_id`;

-- -----------------------------------------
-- ADD TABLES
-- -----------------------------------------

CREATE TABLE `brands` (
  `brand_id` int(10) UNSIGNED NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `code_name` varchar(100) NOT NULL,
  `brand_details` longtext CHARACTER SET utf8,
  `brand_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

-- -------------
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;


--
-- Table structure for table `brand_to_store`
--

CREATE TABLE `brand_to_store` (
  `b2s_id` int(10) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `brand_to_store`
--
ALTER TABLE `brand_to_store`
  ADD PRIMARY KEY (`b2s_id`),
  ADD KEY `brand_id` (`brand_id`);
--
-- AUTO_INCREMENT for table `brand_to_store`
--
ALTER TABLE `brand_to_store`
  MODIFY `b2s_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

-- -------------------------------------------

--
-- Table structure for table `holding_info`
--

CREATE TABLE `holding_info` (
  `info_id` int(10) NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `order_title` varchar(255) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `customer_mobile` varchar(20) DEFAULT NULL,
  `invoice_note` text CHARACTER SET utf8,
  `total_items` smallint(6) DEFAULT NULL,
  `created_by` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `holding_info`
--
ALTER TABLE `holding_info`
  ADD PRIMARY KEY (`info_id`);
--
-- AUTO_INCREMENT for table `holding_info`
--
ALTER TABLE `holding_info`
  MODIFY `info_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

-- -------------------------------------------

--
-- Table structure for table `holding_item`
--

CREATE TABLE `holding_item` (
  `id` int(10) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `item_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `brand_id` int(10) DEFAULT NULL,
  `sup_id` int(10) NOT NULL DEFAULT '0',
  `item_name` varchar(100) NOT NULL,
  `item_price` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `item_discount` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `item_tax` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `tax_method` enum('inclusive','exclusive') NOT NULL DEFAULT 'exclusive',
  `taxrate_id` int(10) UNSIGNED NOT NULL,
  `tax` varchar(20) DEFAULT NULL,
  `gst` varchar(20) DEFAULT NULL,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `item_quantity` int(10) UNSIGNED NOT NULL,
  `item_total` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `holding_item`
--
ALTER TABLE `holding_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_no` (`ref_no`),
  ADD KEY `item_id` (`item_id`);
--
-- AUTO_INCREMENT for table `holding_item`
--
ALTER TABLE `holding_item`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

-- -------------------------------------------

--
-- Table structure for table `holding_price`
--

CREATE TABLE `holding_price` (
  `price_id` int(10) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `subtotal` decimal(25,4) DEFAULT '0.0000',
  `discount_type` enum('plain','percentage') NOT NULL DEFAULT 'plain',
  `discount_amount` decimal(25,4) DEFAULT '0.0000',
  `item_tax` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `order_tax` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `shipping_type` enum('plain','percentage') NOT NULL DEFAULT 'plain',
  `shipping_amount` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `others_charge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `payable_amount` decimal(25,4) DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `holding_price`
--
ALTER TABLE `holding_price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `ref_no` (`ref_no`);
--
-- AUTO_INCREMENT for table `holding_price`
--
ALTER TABLE `holding_price`
  MODIFY `price_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

-- -------------------------------------------

--
-- Table structure for table `income_sources`
--

CREATE TABLE `income_sources` (
  `source_id` int(10) UNSIGNED NOT NULL,
  `source_name` varchar(60) CHARACTER SET utf8 NOT NULL,
  `type` enum('credit','debit') NOT NULL DEFAULT 'credit',
  `source_slug` varchar(60) CHARACTER SET utf8 NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `source_details` longtext CHARACTER SET utf8,
  `for_sell` tinyint(1) NOT NULL DEFAULT '0',
  `for_purchase_return` tinyint(1) NOT NULL DEFAULT '0',
  `for_due_collection` tinyint(1) NOT NULL DEFAULT '0',
  `for_loan` tinyint(1) NOT NULL DEFAULT '0',
  `for_giftcard_sell` tinyint(1) NOT NULL DEFAULT '0',
  `for_topup` tinyint(1) NOT NULL DEFAULT '0',
  `for_stock_transfer` tinyint(1) NOT NULL DEFAULT '0',
  `for_purchase_delete` tinyint(1) NOT NULL DEFAULT '0',
  `for_expense_delete` tinyint(1) NOT NULL DEFAULT '0',
  `profitable` enum('yes','no') NOT NULL DEFAULT 'yes',
  `show_in_income` enum('yes','no') NOT NULL DEFAULT 'yes',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_hide` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Indexes for table `income_sources`
--
ALTER TABLE `income_sources`
  ADD PRIMARY KEY (`source_id`);
--
-- AUTO_INCREMENT for table `income_sources`
--
ALTER TABLE `income_sources`
  MODIFY `source_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

-- -------------------------------------------

--
-- Table structure for table `installment_orders`
--

CREATE TABLE `installment_orders` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `invoice_id` varchar(100) NOT NULL,
  `duration` int(11) NOT NULL,
  `interval_count` int(11) NOT NULL,
  `installment_count` int(11) NOT NULL,
  `interest_percentage` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `interest_amount` decimal(25,2) NOT NULL DEFAULT '0.00',
  `initial_amount` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `payment_status` enum('paid','due') NOT NULL DEFAULT 'due',
  `last_installment_date` datetime DEFAULT NULL,
  `installment_end_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `installment_orders`
--
ALTER TABLE `installment_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`);
--
-- AUTO_INCREMENT for table `installment_orders`
--
ALTER TABLE `installment_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

-- -------------------------------------------

--
-- Table structure for table `installment_payments`
--

CREATE TABLE `installment_payments` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `invoice_id` varchar(100) NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `pmethod_id` int(11) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `note` text CHARACTER SET utf8,
  `capital` decimal(25,4) NOT NULL,
  `interest` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `payable` decimal(25,4) NOT NULL,
  `paid` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `due` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `payment_status` enum('paid','due','pending','cancel') NOT NULL DEFAULT 'due'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `installment_payments`
--
ALTER TABLE `installment_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);
--
-- AUTO_INCREMENT for table `installment_payments`
--
ALTER TABLE `installment_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

-- -------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `slug`, `code`) VALUES
(1, 'English', 'english', 'en');

-- -------------------------------------------

--
-- Table structure for table `language_translations`
--

CREATE TABLE `language_translations` (
  `id` int(10) NOT NULL,
  `lang_id` int(10) NOT NULL,
  `lang_key` varchar(100) NOT NULL,
  `key_type` enum('specific','default') NOT NULL DEFAULT 'specific',
  `lang_value` text CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `language_translations`
--
ALTER TABLE `language_translations`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for table `language_translations`
--
ALTER TABLE `language_translations`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9745;

-- -------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `status` enum('success','error') NOT NULL DEFAULT 'success',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);
--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

-- -------------------------------------------

--
-- Table structure for table `mail_sms_tag`
--

CREATE TABLE `mail_sms_tag` (
  `tag_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `tagname` varchar(128) NOT NULL,
  `cteated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `mail_sms_tag`
--
ALTER TABLE `mail_sms_tag`
  ADD PRIMARY KEY (`tag_id`);
--
-- AUTO_INCREMENT for table `mail_sms_tag`
--
ALTER TABLE `mail_sms_tag`
  MODIFY `tag_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

-- -------------------------------------------

--
-- Table structure for table `pos_register`
--

CREATE TABLE `pos_register` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `opening_balance` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `closing_balance` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `note` text CHARACTER SET utf8,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `pos_register`
--
ALTER TABLE `pos_register`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for table `pos_register`
--
ALTER TABLE `pos_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- -------------------------------------------

--
-- Table structure for table `pos_templates`
--

CREATE TABLE `pos_templates` (
  `template_id` int(10) NOT NULL,
  `template_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `template_content` longtext CHARACTER SET ucs2 NOT NULL,
  `template_css` longtext CHARACTER SET utf8,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Indexes for table `pos_templates`
--
ALTER TABLE `pos_templates`
  ADD PRIMARY KEY (`template_id`);
--
-- AUTO_INCREMENT for table `pos_templates`
--
ALTER TABLE `pos_templates`
  MODIFY `template_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- -------------------------------------------

--
-- Table structure for table `pos_template_to_store`
--

CREATE TABLE `pos_template_to_store` (
  `pt2s` int(10) NOT NULL,
  `store_id` int(10) NOT NULL,
  `ttemplate_id` int(10) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `pos_template_to_store`
--
ALTER TABLE `pos_template_to_store`
  ADD PRIMARY KEY (`pt2s`),
  ADD KEY `ttemplate_id` (`ttemplate_id`);
--
-- AUTO_INCREMENT for table `pos_template_to_store`
--
ALTER TABLE `pos_template_to_store`
  MODIFY `pt2s` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

-- -------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);
--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

-- -------------------------------------------

--
-- Table structure for table `purchase_logs`
--

CREATE TABLE `purchase_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `sup_id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(55) DEFAULT NULL,
  `ref_invoice_id` varchar(55) DEFAULT NULL,
  `type` varchar(55) NOT NULL,
  `pmethod_id` int(11) DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `amount` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `store_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Indexes for table `purchase_logs`
--
ALTER TABLE `purchase_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sup_id` (`sup_id`),
  ADD KEY `reference_no` (`reference_no`),
  ADD KEY `ref_invoice_id` (`ref_invoice_id`);
--
-- AUTO_INCREMENT for table `purchase_logs`
--
ALTER TABLE `purchase_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

-- -------------------------------------------
--
-- Table structure for table `quotation_info`
--

CREATE TABLE `quotation_info` (
  `info_id` int(10) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `invoice_id` varchar(100) DEFAULT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `customer_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `customer_mobile` varchar(20) DEFAULT NULL,
  `status` enum('sent','pending','complete','canceled') NOT NULL DEFAULT 'sent',
  `payment_status` varchar(20) DEFAULT NULL,
  `quotation_note` text CHARACTER SET utf8,
  `is_installment` tinyint(1) NOT NULL DEFAULT '0',
  `is_order` tinyint(1) NOT NULL DEFAULT '0',
  `total_items` int(10) NOT NULL DEFAULT '0',
  `address` text CHARACTER SET utf8,
  `pmethod_details` text CHARACTER SET utf8,
  `created_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `quotation_info`
--
ALTER TABLE `quotation_info`
  ADD PRIMARY KEY (`info_id`);
--
-- AUTO_INCREMENT for table `quotation_info`
--
ALTER TABLE `quotation_info`
  MODIFY `info_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

-- -------------------------------------------

--
-- Table structure for table `quotation_item`
--

CREATE TABLE `quotation_item` (
  `id` int(10) NOT NULL,
  `reference_no` varchar(100) NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `sup_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(10) DEFAULT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_price` decimal(25,4) NOT NULL,
  `item_discount` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `item_tax` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `tax_method` enum('exclusive','inclusive') NOT NULL DEFAULT 'exclusive',
  `taxrate_id` int(10) UNSIGNED DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `gst` varchar(20) DEFAULT NULL,
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `item_quantity` decimal(25,4) NOT NULL,
  `item_purchase_price` decimal(25,4) DEFAULT NULL,
  `item_total` decimal(25,4) NOT NULL,
  `purchase_invoice_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `quotation_item`
--
ALTER TABLE `quotation_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reference_no` (`reference_no`);
--
-- AUTO_INCREMENT for table `quotation_item`
--
ALTER TABLE `quotation_item`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

-- -------------------------------------------

--
-- Table structure for table `quotation_price`
--

CREATE TABLE `quotation_price` (
  `price_id` int(10) NOT NULL,
  `reference_no` varchar(100) NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `subtotal` decimal(25,4) DEFAULT NULL,
  `discount_type` enum('plain','percentage') NOT NULL DEFAULT 'plain',
  `discount_amount` decimal(25,4) DEFAULT '0.0000',
  `interest_amount` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `interest_percentage` int(10) NOT NULL DEFAULT '0',
  `item_tax` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `order_tax` decimal(25,4) DEFAULT '0.0000',
  `cgst` decimal(25,4) DEFAULT NULL,
  `sgst` decimal(25,4) DEFAULT NULL,
  `igst` decimal(25,4) DEFAULT NULL,
  `total_purchase_price` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `shipping_type` enum('plain','percentage') NOT NULL DEFAULT 'plain',
  `shipping_amount` decimal(25,4) DEFAULT '0.0000',
  `others_charge` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `payable_amount` decimal(25,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `quotation_price`
--
ALTER TABLE `quotation_price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `reference_no` (`reference_no`);
--
-- AUTO_INCREMENT for table `quotation_price`
--
ALTER TABLE `quotation_price`
  MODIFY `price_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

-- -------------------------------------------

--
-- Table structure for table `sell_logs`
--

CREATE TABLE `sell_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(55) DEFAULT NULL,
  `ref_invoice_id` varchar(55) DEFAULT NULL,
  `type` varchar(55) NOT NULL,
  `pmethod_id` int(11) DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `amount` decimal(25,4) NOT NULL DEFAULT '0.0000',
  `store_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Indexes for table `sell_logs`
--
ALTER TABLE `sell_logs`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for table `sell_logs`
--
ALTER TABLE `sell_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

-- --------------------------------------------------------

--
-- Table structure for table `shortcut_links`
--

CREATE TABLE `shortcut_links` (
  `id` int(11) NOT NULL,
  `type` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `href` text CHARACTER SET utf8,
  `target` varchar(100) NOT NULL DEFAULT '_self',
  `title` varchar(255) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `thumbnail` text CHARACTER SET utf8,
  `permission_slug` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Indexes for table `shortcut_links`
--
ALTER TABLE `shortcut_links`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for table `shortcut_links`
--
ALTER TABLE `shortcut_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Dumping data for table `shortcut_links`
--

INSERT INTO `shortcut_links` (`id`, `type`, `href`, `target`, `title`, `icon`, `thumbnail`, `permission_slug`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'report', '/admin/report_overview.php', '', 'Overview Report', 'fa-angle-right', '', 'read_overview_report', 1, 1, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(2, 'report', '/admin/report_collection.php', '', 'Collection Report', 'fa-angle-right', '', 'read_collection_report', 1, 2, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(3, 'report', '/admin/report_customer_due_collection.php', '', 'Due Collection Report', 'fa-angle-right', '', 'read_customer_due_collection_report', 1, 3, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(4, 'report', '/admin/report_supplier_due_paid.php', '', 'Supplier Due Paid Report', 'fa-angle-right', '', 'read_supplier_due_paid_report', 1, 4, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(5, 'report', '/admin/report_sell_itemwise.php', '', 'Sell Report', 'fa-angle-right', '', 'read_sell_report', 1, 5, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(6, 'report', '/admin/report_purchase_supplierwise.php', '', 'Purchase Report', 'fa-angle-right', '', 'read_purchase_report', 1, 6, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(7, 'report', '/admin/report_sell_payment.php', '', 'Sell Payment Report', 'fa-angle-right', '', 'read_sell_payment_report', 1, 7, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(8, 'report', '/admin/report_purchase_payment.php', '', 'Purchase Payment Report', 'fa-angle-right', '', 'read_purchase_payment_report', 1, 8, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(9, 'report', '/admin/report_sell_tax.php', '', 'Sell Tax Report', 'fa-angle-right', '', 'read_sell_tax_report', 1, 9, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(10, 'report', '/admin/report_purchase_tax.php', '', 'Purchase Tax Report', 'fa-angle-right', '', 'read_purchase_tax_report', 1, 10, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(11, 'report', '/admin/report_tax_overview.php', '', 'Tax Overview Report', 'fa-angle-right', '', 'read_tax_overview_report', 1, 11, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(12, 'report', '/admin/report_stock.php', '', 'Stock Report', 'fa-angle-right', '', 'read_stock_report', 1, 12, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(13, 'report', '/admin/bank_transactions.php', '', 'Bank Transaction', 'fa-angle-right', '', 'read_bank_transactions', 1, 13, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(14, 'report', '/admin/bank_account_sheet.php', '', 'Balance Sheet', 'fa-angle-right', '', 'read_bank_account_sheet', 1, 14, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(15, 'report', '/admin/income_monthwise.php', '', 'Income Monthwise Report', 'fa-angle-right', '', 'read_income_monthwise', 1, 15, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(16, 'report', '/admin/report_income_and_expense.php', '', 'Income vs Expense Report', 'fa-angle-right', '', 'read_income_and_expense_report', 1, 16, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(17, 'report', '/admin/report_profit_and_loss.php', '', 'Profit vs Loss Report', 'fa-angle-right', '', 'read_profit_and_loss_report', 1, 17, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(18, 'report', '/admin/report_cashbook.php', '', 'Cashbook', 'fa-angle-right', '', 'read_cashbook_report', 1, 18, '2019-02-03 12:00:00', '2019-06-30 23:59:30'),
(19, 'report', '/admin/expense_monthwise.php', '', 'Expense Monthwise Report', 'fa-angle-right', '', 'read_income_monthwise', 1, 15, '2019-02-03 12:00:00', '2019-06-30 23:59:30');


--
-- LANGAUGE TRANSLATIONS
--


INSERT INTO `language_translations` (`id`, `lang_id`, `lang_key`, `key_type`, `lang_value`) VALUES
(1, 1, 'title_dashboard', 'specific', 'Dashboard'),
(2, 1, 'text_arabic', 'specific', 'Arabic'),
(3, 1, 'text_french', 'specific', 'French'),
(4, 1, 'text_germany', 'specific', 'Germany'),
(5, 1, 'text_spanish', 'specific', 'Spanish'),
(6, 1, 'text_pos', 'specific', 'POS'),
(7, 1, 'menu_pos', 'specific', 'POS'),
(8, 1, 'text_cashbook_report', 'specific', 'Cashbook Report'),
(9, 1, 'menu_cashbook', 'specific', 'CASHBOOK'),
(10, 1, 'text_invoice', 'specific', 'Invoice'),
(11, 1, 'menu_invoice', 'specific', 'INVOICE'),
(12, 1, 'text_user_preference', 'specific', 'User Preference'),
(13, 1, 'text_settings', 'specific', 'Settings'),
(14, 1, 'text_stock_alert', 'specific', 'Stock Alert'),
(15, 1, 'text_expired', 'specific', 'Expired'),
(16, 1, 'text_itsolution24', 'specific', 'ITsolution24'),
(17, 1, 'text_reports', 'specific', 'Reports'),
(18, 1, 'text_lockscreen', 'specific', 'Lockscreen'),
(19, 1, 'text_logout', 'specific', 'Logout'),
(20, 1, 'menu_dashboard', 'specific', 'DASHBOARD'),
(21, 1, 'menu_point_of_sell', 'specific', 'POINT OF SELL'),
(22, 1, 'menu_sell', 'specific', 'SELL'),
(23, 1, 'menu_sale_list', 'specific', 'SELL LIST'),
(24, 1, 'menu_return_list', 'specific', 'RETURN LIST'),
(25, 1, 'menu_giftcard', 'specific', 'GIFTCARD'),
(26, 1, 'menu_add_giftcard', 'specific', 'ADD GIFTCARD'),
(27, 1, 'menu_giftcard_list', 'specific', 'GIFTCARD LIST'),
(28, 1, 'menu_giftcard_topup', 'specific', 'GIFTCARD TOPUP'),
(29, 1, 'menu_quotation', 'specific', 'QUOTATION'),
(30, 1, 'menu_add_quotation', 'specific', 'ADD QUOTATION'),
(31, 1, 'menu_quotation_list', 'specific', 'QUOTATION LIST'),
(32, 1, 'menu_installment', 'specific', 'INSTALLMENT'),
(33, 1, 'menu_installment_list', 'specific', 'INSTALLMENT LIST'),
(34, 1, 'menu_payment_list', 'specific', 'PAYMENT LIST'),
(35, 1, 'menu_payment_due_today', 'specific', 'PAYMENT DUE TODAY'),
(36, 1, 'menu_payment_due_all', 'specific', 'PAYMENT DUE ALL'),
(37, 1, 'menu_payment_due_expired', 'specific', 'PAYMENT DUE EXP.'),
(38, 1, 'menu_overview_report', 'specific', 'OVERVIEW REPORT'),
(39, 1, 'menu_purchase', 'specific', 'PURCHASE'),
(40, 1, 'menu_add_purchase', 'specific', 'ADD PURCHASE'),
(41, 1, 'menu_purchase_list', 'specific', 'PURCHASE LIST'),
(42, 1, 'menu_due_invoice', 'specific', 'DUE INVOICE'),
(43, 1, 'menu_transfer', 'specific', 'STOCK TRANSFER'),
(44, 1, 'menu_add_transfer', 'specific', 'ADD TRANSFER'),
(45, 1, 'menu_transfer_list', 'specific', 'TRANSFER LIST'),
(46, 1, 'menu_product', 'specific', 'PRODUCT'),
(47, 1, 'menu_product_list', 'specific', 'PRODUCT LIST'),
(48, 1, 'menu_add_product', 'specific', 'ADD PRODUCT'),
(49, 1, 'menu_barcode_print', 'specific', 'BARCODE PRINT'),
(50, 1, 'menu_category', 'specific', 'CATEGORY LIST'),
(51, 1, 'menu_add_category', 'specific', 'ADD CATEGORY'),
(52, 1, 'menu_product_import', 'specific', 'IMPORT'),
(53, 1, 'menu_stock_alert', 'specific', 'STOCK ALERT'),
(54, 1, 'menu_expired', 'specific', 'EXPIRED'),
(55, 1, 'menu_customer', 'specific', 'CUSTOMER'),
(56, 1, 'menu_add_customer', 'specific', 'ADD CUSTOMER'),
(57, 1, 'menu_customer_list', 'specific', 'CUSTOMER LIST'),
(58, 1, 'menu_transaction_list', 'specific', 'TRANSACTION LIST'),
(59, 1, 'menu_supplier', 'specific', 'SUPPLIER'),
(60, 1, 'menu_add_supplier', 'specific', 'ADD SUPPLIER'),
(61, 1, 'menu_supplier_list', 'specific', 'SUPPLIER LIST'),
(62, 1, 'menu_accounting', 'specific', 'ACCOUNTING'),
(63, 1, 'menu_new_deposit', 'specific', 'DEPOSIT'),
(64, 1, 'menu_new_withdraw', 'specific', 'WIDTHDRAW'),
(65, 1, 'menu_new_transfer', 'specific', 'ADD TRANSFER '),
(66, 1, 'menu_list_transfer', 'specific', 'TRANSFER LIST'),
(67, 1, 'menu_add_bank_account', 'specific', 'ADD BANK ACCOUNT'),
(68, 1, 'menu_bank_accounts', 'specific', 'BANK ACCOUNT LIST'),
(69, 1, 'menu_income_source', 'specific', 'INCOME SOURCE'),
(70, 1, 'menu_list_transactions', 'specific', 'TRANSACTION LIST'),
(71, 1, 'menu_balance_sheet', 'specific', 'BALANCE SHEET'),
(72, 1, 'menu_income_monthwise', 'specific', 'INCOME MONTHWISE'),
(73, 1, 'menu_income_and_expense', 'specific', 'INCOME VS EXPENSE'),
(74, 1, 'menu_profit_and_loss', 'specific', 'PROFIT VS LOSS'),
(75, 1, 'menu_expenditure', 'specific', 'EXPANDITURE'),
(76, 1, 'menu_create_expense', 'specific', 'ADD EXPENSE'),
(77, 1, 'menu_expense_list', 'specific', 'EXPENSE LIST'),
(78, 1, 'menu_expense_monthwise', 'specific', 'EXPENSE MONTHWISE'),
(79, 1, 'menu_summary', 'specific', 'SUMMARY'),
(80, 1, 'menu_loan_manager', 'specific', 'LOAN MANAGER'),
(81, 1, 'menu_take_loan', 'specific', 'TAKE LOAN'),
(82, 1, 'menu_loan_list', 'specific', 'LOAN LIST'),
(83, 1, 'menu_loan_summary', 'specific', 'SUMMARY'),
(84, 1, 'menu_reports', 'specific', 'REPORTS'),
(85, 1, 'menu_report_overview', 'specific', 'OVERVIEW REPORT'),
(86, 1, 'menu_report_collection', 'specific', 'COLLECTION REPORT'),
(87, 1, 'menu_report_due_collection', 'specific', 'DUE COLLECTION RPT'),
(88, 1, 'menu_report_due_paid', 'specific', 'DUE PAID RPT'),
(89, 1, 'menu_sell_report', 'specific', 'SELL REPORT'),
(90, 1, 'menu_purchase_report', 'specific', 'PURCHASE REPORT'),
(91, 1, 'menu_sell_payment_report', 'specific', 'SELL PAYMENT REPORT'),
(92, 1, 'menu_purchase_payment_report', 'specific', 'PUR. PAYMENT RPT.'),
(93, 1, 'menu_tax_report', 'specific', 'SELL TAX REPORT'),
(94, 1, 'menu_purchase_tax_report', 'specific', 'PURCHASE TAX RPT.'),
(95, 1, 'menu_tax_overview_report', 'specific', 'TAX OVERVIEW RPT.'),
(96, 1, 'menu_report_stock', 'specific', 'STOCK REPORT'),
(97, 1, 'menu_analytics', 'specific', 'ANALYTICS'),
(98, 1, 'menu_sms', 'specific', 'SMS'),
(99, 1, 'menu_send_sms', 'specific', 'SEND SMS'),
(100, 1, 'menu_sms_report', 'specific', 'SMS REPORT'),
(101, 1, 'menu_sms_setting', 'specific', 'SMS SETTING'),
(102, 1, 'menu_user', 'specific', 'USER'),
(103, 1, 'menu_add_user', 'specific', 'ADD USER'),
(104, 1, 'menu_user_list', 'specific', 'USER LIST'),
(105, 1, 'menu_add_usergroup', 'specific', 'ADD USERGROUP'),
(106, 1, 'menu_usergroup_list', 'specific', 'USERGROUP LIST'),
(107, 1, 'menu_password', 'specific', 'PASSWORD'),
(108, 1, 'menu_filemanager', 'specific', 'FILEMANAGER'),
(109, 1, 'menu_system', 'specific', 'SYSTEM'),
(110, 1, 'menu_store', 'specific', 'STORE'),
(111, 1, 'menu_create_store', 'specific', 'STORE CREATE'),
(112, 1, 'menu_store_list', 'specific', 'STORE LIST'),
(113, 1, 'menu_store_setting', 'specific', 'STORE SETTING'),
(114, 1, 'menu_receipt_template', 'specific', 'RECEIPT TEMPLATE'),
(115, 1, 'menu_user_preference', 'specific', 'USER PREFERENCE'),
(116, 1, 'menu_brand', 'specific', 'BRAND'),
(117, 1, 'menu_add_brand', 'specific', 'ADD BRAND'),
(118, 1, 'menu_brand_list', 'specific', 'BRAND LIST'),
(119, 1, 'menu_currency', 'specific', 'CURRENCY'),
(120, 1, 'menu_pmethod', 'specific', 'PAYMENT METHOD'),
(121, 1, 'menu_unit', 'specific', 'UNIT'),
(122, 1, 'menu_taxrate', 'specific', 'TAXRATE'),
(123, 1, 'menu_box', 'specific', 'BOX'),
(124, 1, 'menu_printer', 'specific', 'PRINTER'),
(125, 1, 'menu_backup_restore', 'specific', 'BACKUP/RESTORE'),
(126, 1, 'menu_store_change', 'specific', 'STORE CHANGE'),
(127, 1, 'text_dashboard', 'specific', 'Dashboard'),
(128, 1, 'text_old_dashboard', 'specific', 'Old Dashboard'),
(129, 1, 'button_pos', 'specific', 'POS'),
(130, 1, 'button_invoice', 'specific', 'Invoice'),
(131, 1, 'button_overview_report', 'specific', 'Overview Report'),
(132, 1, 'button_sell_report', 'specific', 'Sell Report'),
(133, 1, 'button_purchase_report', 'specific', 'Purchase Report'),
(134, 1, 'button_stock_alert', 'specific', 'Stock Alert'),
(135, 1, 'button_expired_alert', 'specific', 'Expired'),
(136, 1, 'button_backup_restore', 'specific', 'Backup/Restore'),
(137, 1, 'button_stores', 'specific', 'Stores'),
(138, 1, 'text_total_invoice', 'specific', 'TOTAL INVOICE'),
(139, 1, 'text_total_invoice_today', 'specific', 'TOTAL INVOICE TODAY'),
(140, 1, 'text_details', 'specific', 'Details'),
(141, 1, 'text_total_customer', 'specific', 'TOTAL CUSTOMER'),
(142, 1, 'text_total_customer_today', 'specific', 'TOTAL CUSTOMER TODAY'),
(143, 1, 'text_total_supplier', 'specific', 'TOTAL SUPPLIER'),
(144, 1, 'text_total_supplier_today', 'specific', 'TOTAL SUPPLIER TODAY'),
(145, 1, 'text_total_product', 'specific', 'TOTAL PRODUCT'),
(146, 1, 'text_total_product_today', 'specific', 'TOTAL PRODUCT TODAY'),
(147, 1, 'text_deposit_today', 'specific', 'Deposit Today'),
(148, 1, 'text_withdraw_today', 'specific', 'Widthdraw Today'),
(149, 1, 'text_total_deposit', 'specific', 'Total Deposit'),
(150, 1, 'text_total_withdraw', 'specific', 'Total Widthdraw'),
(151, 1, 'text_recent_deposit', 'specific', 'Recent Deposit'),
(152, 1, 'label_date', 'specific', 'Date'),
(153, 1, 'label_description', 'specific', 'Description'),
(154, 1, 'label_amount', 'specific', 'Amount'),
(155, 1, 'button_view_all', 'specific', 'View All'),
(156, 1, 'text_recent_withdraw', 'specific', 'Recent Withdraw'),
(157, 1, 'text_collection_report', 'specific', 'Collection Report'),
(158, 1, 'label_serial_no', 'specific', 'SL'),
(159, 1, 'label_username', 'specific', 'Username'),
(160, 1, 'label_total_inv', 'specific', 'Total Inv'),
(161, 1, 'label_net_amount', 'specific', 'Net Amount'),
(162, 1, 'label_prev_due_collection', 'specific', 'Prev. Due Col.'),
(163, 1, 'label_due_collection', 'specific', 'Due Collection'),
(164, 1, 'label_due_given', 'specific', 'Due GIven'),
(165, 1, 'label_received', 'specific', 'Received'),
(166, 1, 'text_sells_analytics', 'specific', 'Sell Analytics'),
(167, 1, 'text_version', 'specific', 'Version'),
(168, 1, 'button_today', 'specific', 'Today'),
(169, 1, 'button_last_7_days', 'specific', 'Last 7 Days'),
(170, 1, 'button_last_30_days', 'specific', 'Last 30 Days'),
(171, 1, 'button_last_365_days', 'specific', 'Last 365 Days'),
(172, 1, 'button_filter', 'specific', 'Filter'),
(173, 1, 'title_language', 'specific', 'Language'),
(174, 1, 'text_language_title', 'specific', 'Language'),
(175, 1, 'text_language_list_title', 'specific', 'Language Translate'),
(176, 1, 'label_key', 'specific', 'Key'),
(177, 1, 'label_value', 'specific', 'Value'),
(178, 1, 'label_translate', 'specific', 'Translate'),
(179, 1, 'button_translate', 'specific', 'Translate'),
(180, 1, 'title_quotation', 'specific', 'Quotation'),
(181, 1, 'text_quotation_title', 'specific', 'Quotation'),
(182, 1, 'text_add', 'specific', 'Add'),
(183, 1, 'text_new_quotation_title', 'specific', 'Add New Quotation'),
(184, 1, 'label_reference_no', 'specific', 'Ref. No.'),
(185, 1, 'label_note', 'specific', 'Notes'),
(186, 1, 'label_status', 'specific', 'Status'),
(187, 1, 'text_sent', 'specific', 'Sent'),
(188, 1, 'text_pending', 'specific', 'Pending'),
(189, 1, 'text_complete', 'specific', 'Complete'),
(190, 1, 'label_customer', 'specific', 'Customer'),
(191, 1, 'text_select', 'specific', '--- Please Select ---'),
(192, 1, 'label_supplier', 'specific', 'Supplier'),
(193, 1, 'text_all_suppliers', 'specific', 'All Supplier'),
(194, 1, 'label_add_product', 'specific', 'Add Product'),
(195, 1, 'placeholder_search_product', 'specific', 'Search Product'),
(196, 1, 'label_product', 'specific', 'Product'),
(197, 1, 'label_available', 'specific', 'Available'),
(198, 1, 'label_quantity', 'specific', 'Quantity'),
(199, 1, 'label_sell_price', 'specific', 'Sell Price'),
(200, 1, 'label_item_tax', 'specific', 'Item Tax'),
(201, 1, 'label_subtotal', 'specific', 'Subtotal'),
(202, 1, 'label_order_tax', 'specific', 'Order Tax'),
(203, 1, 'label_shipping_charge', 'specific', 'Shipping Charge'),
(204, 1, 'label_others_charge', 'specific', 'Other Charge'),
(205, 1, 'label_discount_amount', 'specific', 'Discount'),
(206, 1, 'label_payable_amount', 'specific', 'Payable Amount'),
(207, 1, 'button_save', 'specific', 'Save'),
(208, 1, 'button_reset', 'specific', 'Reset'),
(209, 1, 'text_quotation_list_title', 'specific', 'Quotation List'),
(210, 1, 'button_all', 'specific', 'All'),
(211, 1, 'button_sent', 'specific', 'Sent'),
(212, 1, 'button_pending', 'specific', 'Pending'),
(213, 1, 'button_complete', 'specific', 'Complete'),
(214, 1, 'label_biller', 'specific', 'Biller'),
(215, 1, 'label_total', 'specific', 'Total'),
(216, 1, 'label_action', 'specific', 'Action'),
(217, 1, 'text_translation_success', 'specific', 'Translation Successfull'),
(218, 1, 'title_purchase', 'specific', 'Purchase'),
(219, 1, 'text_purchase_title', 'specific', 'Purchase'),
(220, 1, 'text_new_purchase_title', 'specific', 'Add New Purchase'),
(221, 1, 'text_received', 'specific', 'Received'),
(222, 1, 'text_ordered', 'specific', 'Ordered'),
(223, 1, 'label_attachment', 'specific', 'Attachment'),
(224, 1, 'label_cost', 'specific', 'Cost'),
(225, 1, 'label_item_total', 'specific', 'Item Total'),
(226, 1, 'label_payment_method', 'specific', 'Payment Method'),
(227, 1, 'label_paid_amount', 'specific', 'Paid Amount'),
(228, 1, 'label_due_amount', 'specific', 'Due Amount'),
(229, 1, 'label_change_amount', 'specific', 'Change Amount'),
(230, 1, 'button_submit', 'specific', 'Submit'),
(231, 1, 'text_purchase_list_title', 'specific', 'Purchase List'),
(232, 1, 'button_today_invoice', 'specific', 'Today Invoice'),
(233, 1, 'button_all_invoice', 'specific', 'All Invoice'),
(234, 1, 'button_due_invoice', 'specific', 'Due Invoice'),
(235, 1, 'button_all_due_invoice', 'specific', 'All Due Invoice'),
(236, 1, 'button_paid_invoice', 'specific', 'Paid Invoice'),
(237, 1, 'button_inactive_invoice', 'specific', 'Inactive Invoice'),
(238, 1, 'label_datetime', 'specific', 'Date Time'),
(239, 1, 'label_invoice_id', 'specific', 'Invoice Id'),
(240, 1, 'label_creator', 'specific', 'Creator'),
(241, 1, 'label_invoice_paid', 'specific', 'Paid Amount'),
(242, 1, 'label_due', 'specific', 'Due'),
(243, 1, 'label_pay', 'specific', 'Pay'),
(244, 1, 'label_return', 'specific', 'Return'),
(245, 1, 'label_view', 'specific', 'View'),
(246, 1, 'label_edit', 'specific', 'Edit'),
(247, 1, 'title_language_translation', 'specific', 'Language Translate'),
(248, 1, 'title_invoice', 'specific', 'Invoice'),
(249, 1, 'text_invoice_title', 'specific', 'Invoice'),
(250, 1, 'text_invoice_sub_title', 'specific', 'Invoice List'),
(251, 1, 'label_customer_name', 'specific', 'Customer Name'),
(252, 1, 'label_invoice_amount', 'specific', 'Inv. Amount'),
(253, 1, 'label_invoice_due', 'specific', 'Invoice Due'),
(254, 1, 'title_transfer', 'specific', 'Transfer'),
(255, 1, 'text_transfer_title', 'specific', 'Transfer'),
(256, 1, 'text_add_transfer_title', 'specific', 'Add New Transfer'),
(257, 1, 'label_ref_no', 'specific', 'Ref. No.'),
(258, 1, 'label_from', 'specific', 'From'),
(259, 1, 'label_to', 'specific', 'To'),
(260, 1, 'text_stock_list', 'specific', 'Stock List'),
(261, 1, 'search', 'specific', 'Search'),
(262, 1, 'text_invoice_id', 'specific', 'Invoice Id'),
(263, 1, 'text_stock', 'specific', 'Stock'),
(264, 1, 'text_transfer_list', 'specific', 'Transfer List'),
(265, 1, 'label_item_name', 'specific', 'Item Name'),
(266, 1, 'label_transfer_qty', 'specific', 'Transfer Qty.'),
(267, 1, 'button_transfer_now', 'specific', 'Transfer Now'),
(268, 1, 'text_list_transfer_title', 'specific', 'Transfer List'),
(269, 1, 'label_from_store', 'specific', 'From Store'),
(270, 1, 'label_to_store', 'specific', 'To Store'),
(271, 1, 'label_total_item', 'specific', 'Total Item'),
(272, 1, 'label_total_quantity', 'specific', 'Total Quantity'),
(273, 1, 'text_success', 'specific', 'Successfully Created'),
(274, 1, 'title_product', 'specific', 'Product'),
(275, 1, 'text_products', 'specific', 'Products'),
(276, 1, 'text_add_new', 'specific', 'Add New Product'),
(277, 1, 'text_product', 'specific', 'Product'),
(278, 1, 'text_general', 'specific', 'General'),
(279, 1, 'text_image', 'specific', 'Image'),
(280, 1, 'label_image', 'specific', 'Image'),
(281, 1, 'label_url', 'specific', 'Url'),
(282, 1, 'label_sort_order', 'specific', 'Order'),
(283, 1, 'label_thumbnail', 'specific', 'Thumbnail'),
(284, 1, 'label_product_type', 'specific', 'Product Type'),
(285, 1, 'text_standard', 'specific', 'Standard'),
(286, 1, 'text_service', 'specific', 'Service'),
(287, 1, 'label_name', 'specific', 'Name'),
(288, 1, 'label_pcode', 'specific', 'P. Code'),
(289, 1, 'label_category', 'specific', 'Category'),
(290, 1, 'label_brand', 'specific', 'Brand'),
(291, 1, 'label_barcode_symbology', 'specific', 'Barcode Symbiology'),
(292, 1, 'label_box', 'specific', 'Box'),
(293, 1, 'label_expired_date', 'specific', 'Expired Date'),
(294, 1, 'label_unit', 'specific', 'Unit'),
(295, 1, 'label_product_cost', 'specific', 'Product Cost'),
(296, 1, 'label_product_price', 'specific', 'Product Price'),
(297, 1, 'label_product_tax', 'specific', 'Product Tax'),
(298, 1, 'label_tax_method', 'specific', 'Tax Method'),
(299, 1, 'text_inclusive', 'specific', 'Inclusive'),
(300, 1, 'text_exclusive', 'specific', 'Exclusive'),
(301, 1, 'label_store', 'specific', 'Store'),
(302, 1, 'label_alert_quantity', 'specific', 'Alert Quantity'),
(303, 1, 'text_active', 'specific', 'Active'),
(304, 1, 'text_inactive', 'specific', 'Inactive'),
(305, 1, 'text_view_all', 'specific', 'View All'),
(306, 1, 'label_all_product', 'specific', 'All Product'),
(307, 1, 'button_trash', 'specific', 'Trash'),
(308, 1, 'button_bulk', 'specific', 'Bulk Action'),
(309, 1, 'button_delete_all', 'specific', 'Delete All'),
(310, 1, 'label_stock', 'specific', 'Stock'),
(311, 1, 'label_purchase_price', 'specific', 'Purchase Price'),
(312, 1, 'label_selling_price', 'specific', 'Selling Price'),
(313, 1, 'label_purchase', 'specific', 'Purchase'),
(314, 1, 'label_print_barcode', 'specific', 'Print Barcode'),
(315, 1, 'button_view', 'specific', 'View'),
(316, 1, 'button_edit', 'specific', 'Edit'),
(317, 1, 'button_purchase_product', 'specific', 'Purchase Product'),
(318, 1, 'button_barcode', 'specific', 'Barcode'),
(319, 1, 'button_delete', 'specific', 'Delete'),
(320, 1, 'error_no_selected', 'specific', 'Not Selected'),
(321, 1, 'success_delete_all', 'specific', 'Successfully Deleted'),
(322, 1, 'text_language_translation_title', 'specific', 'Language Translate'),
(323, 1, 'title_user_preference', 'specific', 'User Preference'),
(324, 1, 'text_user_preference_title', 'specific', 'User Preference'),
(325, 1, 'text_language_preference_title', 'specific', 'Language Translate'),
(326, 1, 'label_select_language', 'specific', 'Select Language'),
(327, 1, 'text_english', 'specific', 'English'),
(328, 1, 'text_color_preference_title', 'specific', 'Color Preference'),
(329, 1, 'label_base_color', 'specific', 'Base color'),
(330, 1, 'text_color_black', 'specific', 'Black'),
(331, 1, 'text_color_blue', 'specific', 'Blue'),
(332, 1, 'text_color_green', 'specific', 'Green'),
(333, 1, 'text_color_red', 'specific', 'Red'),
(334, 1, 'text_color_yellow', 'specific', 'Yellow'),
(335, 1, 'text_pos_side_panel_position_title', 'specific', 'POS Side Panel Position'),
(336, 1, 'label_pos_side_panel_position', 'specific', 'Pos Side Panel Position'),
(337, 1, 'text_right', 'specific', 'Right'),
(338, 1, 'text_left', 'specific', 'Left'),
(339, 1, 'text_pos_pattern_title', 'specific', 'POS Pattern'),
(340, 1, 'label_select_pos_pattern', 'specific', 'Select POS Pattern'),
(341, 1, 'button_update', 'specific', 'Update'),
(342, 1, 'text_login_title', 'specific', 'Login'),
(343, 1, 'text_login', 'specific', 'Login'),
(344, 1, 'button_sign_in', 'specific', 'Sign In'),
(345, 1, 'text_forgot_password', 'specific', 'Forgot Password?'),
(346, 1, 'title_forgot_password', 'specific', 'Forgot Password'),
(347, 1, 'text_forgot_password_instruction', 'specific', 'Please type your email.  We will send you a link just follow the steps.'),
(348, 1, 'button_close', 'specific', 'Close'),
(349, 1, 'title_receipt_template', 'specific', 'Receipt Template'),
(350, 1, 'text_receipt_tempalte_title', 'specific', 'Receipt Template'),
(351, 1, 'title_pos_setting', 'specific', 'POS Settings'),
(352, 1, 'text_receipt_template', 'specific', 'Receipt Template'),
(353, 1, 'text_receipt_tempalte_sub_title', 'specific', 'Receipt Template'),
(354, 1, 'button_preview', 'specific', 'Preview'),
(355, 1, 'text_tempalte_content_title', 'specific', 'Template Content'),
(356, 1, 'text_tempalte_css_title', 'specific', 'Template CSS'),
(357, 1, 'text_template_tags', 'specific', 'Template Tags'),
(358, 1, 'text_translations', 'specific', 'Translations'),
(359, 1, 'text_bangla', 'specific', 'Bangla'),
(360, 1, 'menu_language', 'specific', 'LANGUAGE'),
(361, 1, 'button_default', 'specific', 'Default'),
(362, 1, 'text_paid', 'specific', 'Paid'),
(363, 1, 'button_return', 'specific', 'Return'),
(364, 1, 'button_view_receipt', 'specific', 'View Receipt'),
(365, 1, 'label_delete', 'default', 'Delete'),
(366, 1, 'button_dublicate_entry', 'specific', 'Dublicate Entry'),
(367, 1, 'text_delete_success', 'specific', 'Successfully Deleted'),
(368, 1, 'label_delete', 'specific', 'Delete'),
(369, 1, 'text_customer_list_title', 'specific', 'Customer List'),
(370, 1, 'text_customer_title', 'specific', 'Customer'),
(371, 1, 'text_new_customer_title', 'specific', 'Add New Customer'),
(372, 1, 'label_phone', 'specific', 'Phone'),
(373, 1, 'label_date_of_birth', 'specific', 'Date of Birth'),
(374, 1, 'label_email', 'specific', 'Email'),
(375, 1, 'label_sex', 'specific', 'Sex'),
(376, 1, 'label_male', 'specific', 'Male'),
(377, 1, 'label_female', 'specific', 'Female'),
(378, 1, 'label_others', 'specific', 'Others'),
(379, 1, 'label_age', 'specific', 'Age'),
(380, 1, 'label_address', 'specific', 'Address'),
(381, 1, 'label_city', 'specific', 'City'),
(382, 1, 'label_state', 'specific', 'State'),
(383, 1, 'label_country', 'specific', 'Country'),
(384, 1, 'label_id', 'specific', 'Id'),
(385, 1, 'label_balance', 'specific', 'Balance'),
(386, 1, 'label_sell', 'specific', 'Sell'),
(387, 1, 'button_sell', 'specific', 'Sell'),
(388, 1, 'button_view_profile', 'specific', 'View Profile'),
(389, 1, 'login_success', 'specific', 'Login Successfull'),
(390, 1, 'title_installment_payment', 'specific', 'Installment Payment'),
(391, 1, 'text_installment_payment_title', 'specific', 'Installment Payment'),
(392, 1, 'text_installment', 'specific', 'Installment'),
(393, 1, 'text_installment_payment_list_title', 'specific', 'Installment Payment List'),
(394, 1, 'text_all_payment', 'specific', 'All Payment'),
(395, 1, 'button_all_payment', 'specific', 'All Payment'),
(396, 1, 'button_todays_due_payment', 'specific', 'Todays Due Payment'),
(397, 1, 'button_all_due_payment', 'specific', 'All Due Payment'),
(398, 1, 'button_expired_due_payment', 'specific', 'Expired Due Payment'),
(399, 1, 'label_payment_date', 'specific', 'Payment Date'),
(400, 1, 'label_payable', 'specific', 'Payable'),
(401, 1, 'label_paid', 'specific', 'Paid'),
(402, 1, 'button_payment', 'specific', 'Payment'),
(403, 1, 'title_backup_restore', 'specific', 'Backup/Restore'),
(404, 1, 'text_backup_restore_title', 'specific', 'Backup/Restore'),
(405, 1, 'text_backup', 'specific', 'Backup'),
(406, 1, 'text_restore', 'specific', 'Restore'),
(407, 1, 'label_databases', 'specific', 'Databases'),
(408, 1, 'label_select_all', 'specific', 'Select All'),
(409, 1, 'label_unselect_all', 'specific', 'Unselect All'),
(410, 1, 'button_export', 'specific', 'Export'),
(411, 1, 'label_progress', 'specific', 'Progress'),
(412, 1, 'button_select_sql_file', 'specific', 'Select .sql File'),
(413, 1, 'title_printer', 'specific', 'Printer'),
(414, 1, 'text_printer_title', 'specific', 'Printer'),
(415, 1, 'text_new_printer_title', 'specific', 'Add New Printer'),
(416, 1, 'label_title', 'specific', 'Title'),
(417, 1, 'label_type', 'specific', 'Type'),
(418, 1, 'label_char_per_line', 'specific', 'Char Per Line'),
(419, 1, 'label_path', 'specific', 'Path'),
(420, 1, 'label_ip_address', 'specific', 'Ip Address'),
(421, 1, 'label_port', 'specific', 'Port'),
(422, 1, 'text_printer_list_title', 'specific', 'Printer List'),
(423, 1, 'title_box', 'specific', 'Box'),
(424, 1, 'text_box_title', 'specific', 'Box'),
(425, 1, 'text_box_box_title', 'specific', 'Box '),
(426, 1, 'label_box_name', 'specific', 'Box Name'),
(427, 1, 'label_code_name', 'specific', 'Code Name'),
(428, 1, 'label_box_details', 'specific', 'Box Details'),
(429, 1, 'text_in_active', 'specific', 'Inactive'),
(430, 1, 'text_box_list_title', 'specific', 'Box List'),
(431, 1, 'title_taxrate', 'specific', 'Taxrate'),
(432, 1, 'text_taxrate_title', 'specific', 'Taxrate'),
(433, 1, 'text_new_taxrate_title', 'specific', 'Add New Taxrate'),
(434, 1, 'label_taxrate_name', 'specific', 'Taxrate Name'),
(435, 1, 'label_taxrate', 'specific', 'Taxrate'),
(436, 1, 'text_taxrate_list_title', 'specific', 'Taxrate List'),
(437, 1, 'title_unit', 'specific', 'Unit'),
(438, 1, 'text_unit_title', 'specific', 'Unit'),
(439, 1, 'text_new_unit_title', 'specific', 'Add New Unit'),
(440, 1, 'label_unit_name', 'specific', 'Unit Name'),
(441, 1, 'label_unit_details', 'specific', 'Unit Details'),
(442, 1, 'text_unit_list_title', 'specific', 'Unit List'),
(443, 1, 'title_pmethod', 'specific', 'Payment Mehtod'),
(444, 1, 'text_pmethod_title', 'specific', 'Payment Method'),
(445, 1, 'text_new_pmethod_title', 'specific', 'Add New Payment Method'),
(446, 1, 'label_details', 'specific', 'Details'),
(447, 1, 'text_pmethod_list_title', 'specific', 'Payment Method List'),
(448, 1, 'title_currency', 'specific', 'Currency'),
(449, 1, 'text_currency_title', 'specific', 'Currency'),
(450, 1, 'text_new_currency_title', 'specific', 'Add New Currency'),
(451, 1, 'label_code', 'specific', 'Code'),
(452, 1, 'hint_code', 'specific', 'Code name here'),
(453, 1, 'label_symbol_left', 'specific', 'Symbol Left'),
(454, 1, 'hint_symbol_left', 'specific', 'It will display in Left side'),
(455, 1, 'label_symbol_right', 'specific', 'Symbol Right'),
(456, 1, 'hint_symbol_right', 'specific', 'It will display in right  side'),
(457, 1, 'label_decimal_place', 'specific', 'Decimal  Place'),
(458, 1, 'hint_decimal_place', 'specific', 'It indicates number of digit after ponts. I.E.  100.00'),
(459, 1, 'text_currency_list_title', 'specific', 'Currency List'),
(460, 1, 'text_enabled', 'specific', 'Enabled'),
(461, 1, 'button_activate', 'specific', 'Active'),
(462, 1, 'button_activated', 'specific', 'Activated'),
(463, 1, 'text_brand_list_title', 'specific', 'Brand List'),
(464, 1, 'text_brand_title', 'specific', 'Brand'),
(465, 1, 'text_new_brand_title', 'specific', 'Brand'),
(466, 1, 'label_total_product', 'specific', 'Total Product'),
(467, 1, 'title_create_store', 'specific', 'Create Store'),
(468, 1, 'text_create_store_title', 'specific', 'Create Store'),
(469, 1, 'text_stores', 'specific', 'Stores'),
(470, 1, 'text_currency', 'specific', 'Currency'),
(471, 1, 'text_payment_method', 'specific', 'Paymen Method'),
(472, 1, 'text_printer', 'specific', 'Printer'),
(473, 1, 'text_email_setting', 'specific', 'Email Setting'),
(474, 1, 'text_ftp_setting', 'specific', 'FTP Setting'),
(475, 1, 'label_mobile', 'specific', 'Mobile'),
(476, 1, 'label_zip_code', 'specific', 'Zip Code'),
(477, 1, 'label_vat_reg_no', 'specific', 'VAT Reg. No.'),
(478, 1, 'label_cashier_name', 'specific', 'Cashier Name'),
(479, 1, 'label_timezone', 'specific', 'Timezone'),
(480, 1, 'label_invoice_edit_lifespan', 'specific', 'Invoice Edit Lifespan'),
(481, 1, 'hint_invoice_edit_lifespan', 'specific', 'After this time you won\'t be able to edit invoice.'),
(482, 1, 'text_minute', 'specific', 'Minute'),
(483, 1, 'text_second', 'specific', 'Second'),
(484, 1, 'label_invoice_delete_lifespan', 'specific', 'Invoice Delete Lifespan'),
(485, 1, 'hint_invoice_delete_lifespan', 'specific', 'After this time you won\'t be able to delete invoice.'),
(486, 1, 'label_after_sell_page', 'specific', 'After Sell Page'),
(487, 1, 'hint_after_sell_page', 'specific', 'After Sell Page'),
(488, 1, 'label_pos_printing', 'specific', 'POS Printing'),
(489, 1, 'label_receipt_printer', 'specific', 'Receipt Printer'),
(490, 1, 'label_auto_print_receipt', 'specific', 'Auto Print Receipt'),
(491, 1, 'label_deposit_account', 'specific', 'Deposit Account'),
(492, 1, 'label_tax', 'specific', 'TAX'),
(493, 1, 'hint_tax', 'specific', 'Tax'),
(494, 1, 'label_stock_alert_quantity', 'specific', 'Stock Alert Quantity'),
(495, 1, 'hint_stock_alert_quantity', 'specific', 'If quantity reach this value so it will be alert as stock low alert'),
(496, 1, 'label_datatable_item_limit', 'specific', 'Datatable Item Limit'),
(497, 1, 'hint_datatable_item_limit', 'specific', 'It indicates how many row you will show in any table'),
(498, 1, 'label_invoice_footer_text', 'specific', 'Invoice Footer Text'),
(499, 1, 'hint_invoice_footer_text', 'specific', 'This will display in footer of invoice'),
(500, 1, 'label_sound_effect', 'specific', 'Sound Effect'),
(501, 1, 'label_email_from', 'specific', 'Email From'),
(502, 1, 'hint_email_from', 'specific', 'Email From'),
(503, 1, 'label_email_address', 'specific', 'Email Address'),
(504, 1, 'hint_email_address', 'specific', 'Email Addrress'),
(505, 1, 'label_email_driver', 'specific', 'Email Driver'),
(506, 1, 'hint_email_driver', 'specific', 'Email Driver'),
(507, 1, 'label_smtp_host', 'specific', 'SMTP Host'),
(508, 1, 'hint_smtp_host', 'specific', 'SMTP Host'),
(509, 1, 'label_smtp_username', 'specific', 'SMTP Username'),
(510, 1, 'hint_smtp_username', 'specific', 'SMTP Username'),
(511, 1, 'label_smtp_password', 'specific', 'SMTP Password'),
(512, 1, 'hint_smtp_password', 'specific', 'SMTP Password'),
(513, 1, 'label_smtp_port', 'specific', 'SMTP Port'),
(514, 1, 'hint_smtp_port', 'specific', 'SMTP Port'),
(515, 1, 'label_ssl_tls', 'specific', 'SSL/TLS'),
(516, 1, 'hint_ssl_tls', 'specific', 'SSL/TLS'),
(517, 1, 'label_ftp_hostname', 'specific', 'FTP Hostname'),
(518, 1, 'label_ftp_username', 'specific', 'FTP Username'),
(519, 1, 'label_ftp_password', 'specific', 'FTP Password'),
(520, 1, 'button_back', 'specific', 'Back'),
(521, 1, 'title_store', 'specific', 'Store'),
(522, 1, 'text_store_title', 'specific', 'Store'),
(523, 1, 'text_store_list_title', 'specific', 'Store List'),
(524, 1, 'label_created_at', 'specific', 'Created At'),
(525, 1, 'title_edit_store', 'specific', 'Edit Store'),
(526, 1, 'text_title', 'specific', 'Title'),
(527, 1, 'text_pos_setting', 'specific', 'POS Setting'),
(528, 1, 'label_gst_reg_no', 'specific', 'GST Reg. No.'),
(529, 1, 'label_sms_gateway', 'specific', 'SMS Gateway'),
(530, 1, 'hint_sms_gateway', 'specific', 'SMS Gateway like clickatell, 91sms'),
(531, 1, 'label_sms_alert', 'specific', 'SMS Alert'),
(532, 1, 'hint_sms_alert', 'specific', 'SMS Alert'),
(533, 1, 'text_yes', 'specific', 'Yes'),
(534, 1, 'text_no', 'specific', 'No.'),
(535, 1, 'label_auto_sms', 'specific', 'Auto SMS'),
(536, 1, 'text_sms_after_creating_invoice', 'specific', 'SMS After Create Invoice'),
(537, 1, 'label_expiration_system', 'specific', 'Expiration System'),
(538, 1, 'label_invoice_prefix', 'specific', 'Invoive Prefix'),
(539, 1, 'label_receipt_template', 'specific', 'Receipt Template'),
(540, 1, 'label_invoice_view', 'specific', 'Invoice View'),
(541, 1, 'hint_invoice_view', 'specific', 'Invoice View'),
(542, 1, 'text_tax_invoice', 'specific', 'Tax Invoice'),
(543, 1, 'text_indian_gst', 'specific', 'Indian GST'),
(544, 1, 'label_change_item_price_while_billing', 'specific', 'Change Price when Billing'),
(545, 1, 'hint_change_item_price_while_billing', 'specific', 'You will be able to edit sell price when you will create a invoice.'),
(546, 1, 'label_pos_product_display_limit', 'specific', 'POS Product Display Limit'),
(547, 1, 'hint_pos_product_display_limit', 'specific', 'Number of product that will display in POS'),
(548, 1, 'label_send_mail_path', 'specific', 'Send Mail Path'),
(549, 1, 'hint_send_mail_path', 'specific', 'Type send mail path here'),
(550, 1, 'text_logo', 'specific', 'Logo'),
(551, 1, 'label_logo_size', 'specific', 'Logo Size'),
(552, 1, 'button_upload', 'specific', 'Upload'),
(553, 1, 'text_favicon', 'specific', 'Favicon'),
(554, 1, 'label_favicon_size', 'specific', 'Favicon Size'),
(555, 1, 'title_user', 'specific', 'User'),
(556, 1, 'text_user_title', 'specific', 'User'),
(557, 1, 'text_new_user_title', 'specific', 'Add New User'),
(558, 1, 'label_password', 'specific', 'Password'),
(559, 1, 'label_password_retype', 'specific', 'Retype Password'),
(560, 1, 'label_group', 'specific', 'Group'),
(561, 1, 'hint_group', 'specific', 'Group name here'),
(562, 1, 'text_user_list_title', 'specific', 'User List'),
(563, 1, 'label_profile', 'specific', 'Profile'),
(564, 1, 'title_user_group', 'specific', 'Usergroup'),
(565, 1, 'text_group_title', 'specific', 'User Group'),
(566, 1, 'text_new_group_title', 'specific', 'Add New Usergroup'),
(567, 1, 'label_slug', 'specific', 'Slug'),
(568, 1, 'text_group_list_title', 'specific', 'User Group List'),
(569, 1, 'label_total_user', 'specific', 'Total User'),
(570, 1, 'title_password', 'specific', 'Password'),
(571, 1, 'text_password_title', 'specific', 'Password'),
(572, 1, 'text_password_box_title', 'specific', 'Password'),
(573, 1, 'label_password_user', 'specific', 'Password'),
(574, 1, 'label_password_new', 'specific', 'New Password'),
(575, 1, 'label_password_confirm', 'specific', 'Password Confirm'),
(576, 1, 'title_send_sms', 'specific', 'Send SMS'),
(577, 1, 'text_sms_title', 'specific', 'SMS'),
(578, 1, 'text_send_sms', 'specific', 'Send SMS'),
(579, 1, 'text_send_sms_title', 'specific', 'Send SMS'),
(580, 1, 'text_event_sms', 'specific', 'Event SMS'),
(581, 1, 'text_single', 'specific', 'Single'),
(582, 1, 'text_group', 'specific', 'User Group'),
(583, 1, 'label_sms_for', 'specific', 'SMS For'),
(584, 1, 'text_birthday', 'specific', 'Birthday'),
(585, 1, 'label_people_type', 'specific', 'People Type'),
(586, 1, 'text_all_customer', 'specific', 'All Customer'),
(587, 1, 'text_all_user', 'specific', 'All User'),
(588, 1, 'label_people', 'specific', 'People'),
(589, 1, 'label_message', 'specific', 'Messeage'),
(590, 1, 'button_send', 'specific', 'Send'),
(591, 1, 'label_phone_number', 'specific', 'Phone Number'),
(592, 1, 'title_sms_report', 'specific', 'SMS Report'),
(593, 1, 'text_sms_report_title', 'specific', 'SMS Report'),
(594, 1, 'text_sms_list_title', 'specific', 'SMS List'),
(595, 1, 'text_all', 'specific', 'All'),
(596, 1, 'button_delivered', 'specific', 'Delivered'),
(597, 1, 'button_failed', 'specific', 'Failed'),
(598, 1, 'label_schedule_at', 'specific', 'Schedule At'),
(599, 1, 'label_campaign_name', 'specific', 'Campaign Name'),
(600, 1, 'label_people_name', 'specific', 'People Name'),
(601, 1, 'label_mobile_number', 'specific', 'Mobile Number'),
(602, 1, 'label_process_status', 'specific', 'Process Status'),
(603, 1, 'label_response_text', 'specific', 'Response Text'),
(604, 1, 'label_delivered', 'specific', 'Delivered'),
(605, 1, 'label_resend', 'specific', 'Resend'),
(606, 1, 'title_sms_setting', 'specific', 'SMS Setting'),
(607, 1, 'text_sms_setting_title', 'specific', 'SMS Setting'),
(608, 1, 'text_sms_setting', 'specific', 'SMS Setting'),
(609, 1, 'text_clickatell', 'specific', 'clickatell'),
(610, 1, 'text_twilio', 'specific', 'twilio'),
(611, 1, 'text_msg91', 'specific', 'msg91'),
(612, 1, 'text_onnorokomsms', 'specific', 'Onnorokom SMS'),
(613, 1, 'label_api_key', 'specific', 'API Key'),
(614, 1, 'label_sender_id', 'specific', 'Sender Id'),
(615, 1, 'label_auth_key', 'specific', 'Auth Key'),
(616, 1, 'label_contact', 'specific', 'Contact'),
(617, 1, 'label_country_code', 'specific', 'Country Code'),
(618, 1, 'label_maskname', 'specific', 'Maskname'),
(619, 1, 'label_optional', 'specific', 'Optional'),
(620, 1, 'label_campaignname', 'specific', 'Campaign Name'),
(621, 1, 'title_analytics', 'specific', 'Analytics'),
(622, 1, 'text_analytics_title', 'specific', 'Analytics'),
(623, 1, 'text_report_title', 'specific', 'Report'),
(624, 1, 'text_best_customer', 'specific', 'Best Customer'),
(625, 1, 'text_purchase_amount', 'specific', 'Purchase Amount'),
(626, 1, 'text_top_product', 'specific', 'Top Product'),
(627, 1, 'title_overview', 'specific', 'Overview Report'),
(628, 1, 'text_overview_title', 'specific', 'Overview Report'),
(629, 1, 'text_sell_overview', 'specific', 'Sell Overview'),
(630, 1, 'text_purchase_overview', 'specific', 'Purchase Overview'),
(631, 1, 'text_title_sells_overview', 'specific', 'Sell Overview'),
(632, 1, 'text_invoice_amount', 'specific', 'Invoice Amount'),
(633, 1, 'button_details', 'specific', 'Details'),
(634, 1, 'text_discount_amount', 'specific', 'Discount'),
(635, 1, 'text_due_given', 'specific', 'Due Given'),
(636, 1, 'text_due_collection', 'specific', 'Due Collection'),
(637, 1, 'text_others', 'specific', 'Others'),
(638, 1, 'text_shipping_charge', 'specific', 'Shipping Charge'),
(639, 1, 'text_others_charge', 'specific', 'Others Charge'),
(640, 1, 'text_profit_or_loss', 'specific', 'Profit or Loss'),
(641, 1, 'text_order_tax', 'specific', 'Order Tax'),
(642, 1, 'text_item_tax', 'specific', 'Item Tax'),
(643, 1, 'text_total_tax', 'specific', 'Total Tax'),
(644, 1, 'text_title_purchase_overview', 'specific', 'Purchase Overview'),
(645, 1, 'text_due_taken', 'specific', 'Due Taken'),
(646, 1, 'text_due_paid', 'specific', 'Due Paid'),
(647, 1, 'text_total_paid', 'specific', 'Total Paid'),
(648, 1, 'title_collection_report', 'specific', 'Collection Report'),
(649, 1, 'text_collection_report_title', 'specific', 'Collection Report'),
(650, 1, 'title_due_collection', 'specific', 'Due Collection'),
(651, 1, 'text_due_collection_title', 'specific', 'Due Collection'),
(652, 1, 'text_due_collection_sub_title', 'specific', 'Due Collection List'),
(653, 1, 'label_pmethod_name', 'specific', 'Payment Mehtod'),
(654, 1, 'label_created_by', 'specific', 'Created By'),
(655, 1, 'title_supplier_due_paid', 'specific', 'Supplier Due Paid'),
(656, 1, 'text_supplier_due_paid_title', 'specific', 'Supplier Due Paid'),
(657, 1, 'text_supplier_due_paid_sub_title', 'specific', 'Supplier Due Paid'),
(658, 1, 'title_sell_report', 'specific', 'Sell Report'),
(659, 1, 'text_selling_report_title', 'specific', 'Selling Report'),
(660, 1, 'text_selling_report_sub_title', 'specific', 'Selling Report'),
(661, 1, 'button_itemwise', 'specific', 'Itemwise'),
(662, 1, 'button_categorywise', 'specific', 'Categorywise'),
(663, 1, 'button_supplierwise', 'specific', 'Supplierwise'),
(664, 1, 'label_product_name', 'specific', 'Product Name'),
(665, 1, 'label_selling_price_tax', 'specific', 'Selling Price Tax'),
(666, 1, 'label_profit', 'specific', 'Profit'),
(667, 1, 'title_purchase_report', 'specific', 'Purchase Report'),
(668, 1, 'text_purchase_report_title', 'specific', 'Purchase Report'),
(669, 1, 'text_purchase_report_sub_title', 'specific', 'Purchase Report'),
(670, 1, 'label_sup_name', 'specific', 'Sup Name'),
(671, 1, 'title_sell_payment_report', 'specific', 'Sell Payment Report'),
(672, 1, 'text_sell_payment_report_title', 'specific', 'Sell Payment Report'),
(673, 1, 'title_purchase_payment_report', 'specific', 'Purchase Payment Report'),
(674, 1, 'text_purchase_payment_report_title', 'specific', 'Purchase Payment Report'),
(675, 1, 'title_sell_tax_report', 'specific', 'Sell Tax Report'),
(676, 1, 'text_sell_tax_report_title', 'specific', 'Sell Tax Report '),
(677, 1, 'text_sell_amount', 'specific', 'Sell Amount'),
(678, 1, 'text_product_tax_amount', 'specific', 'Product Tax'),
(679, 1, 'text_order_tax_amount', 'specific', 'Order Tax'),
(680, 1, 'text_tax_report_sub_title', 'specific', 'Tax Report '),
(681, 1, 'label_total_amount', 'specific', 'Total Amount'),
(682, 1, 'title_purchase_tax_report', 'specific', 'Purchase Tax Report'),
(683, 1, 'text_purchase_tax_report_title', 'specific', 'Purchase Tax Report'),
(684, 1, 'text_total_tax_amount', 'specific', 'Total Tax'),
(685, 1, 'text_purchase_tax_report_sub_title', 'specific', 'Purcahse Tax Report'),
(686, 1, 'label_tax_amount', 'specific', 'Tax Amount'),
(687, 1, 'title_tax_overview_report', 'specific', 'Tax Overview Report'),
(688, 1, 'text_tax_overview_report_title', 'specific', 'Tax Overview Report'),
(689, 1, 'text_sell_tax', 'specific', 'Sell Tax'),
(690, 1, 'text_purchase_tax', 'specific', 'Purchase Tax'),
(691, 1, 'label_tax_percent', 'specific', 'Tax Percent'),
(692, 1, 'label_count', 'specific', 'Count'),
(693, 1, 'title_stock_report', 'specific', 'Stock Report'),
(694, 1, 'text_stock_report_title', 'specific', 'Stock Report'),
(695, 1, 'text_stock_report', 'specific', 'Stock Report'),
(696, 1, 'supplier_name', 'specific', 'Supplier Name'),
(697, 1, 'title_filemanager', 'specific', 'Filemanager'),
(698, 1, 'title_loan', 'specific', 'Loan'),
(699, 1, 'text_loan_title', 'specific', 'Loan'),
(700, 1, 'text_take_loan_title', 'specific', 'Take Loan'),
(701, 1, 'label_loan_from', 'specific', 'Loan From'),
(702, 1, 'label_interest', 'specific', 'Interest'),
(703, 1, 'label_loan_for', 'specific', 'Loan For'),
(704, 1, 'text_loan_list_title', 'specific', 'Loan List'),
(705, 1, 'button_paid', 'specific', 'Paid'),
(706, 1, 'button_due', 'specific', 'Due'),
(707, 1, 'button_disabled', 'specific', 'Disabled'),
(708, 1, 'label_basic_amount', 'specific', 'Basic Amount'),
(709, 1, 'title_loan_summary', 'specific', 'Loan Summary'),
(710, 1, 'text_loan_summary_title', 'specific', 'Loan Summary'),
(711, 1, 'text_summary_title', 'specific', 'Summary'),
(712, 1, 'text_total_loan', 'specific', 'Total Loan'),
(713, 1, 'text_total_due', 'specific', 'Total Due'),
(714, 1, 'text_recent_payments', 'specific', 'Recent Payments'),
(715, 1, 'title_expense', 'specific', 'Expense'),
(716, 1, 'text_expense_title', 'specific', 'Expense'),
(717, 1, 'text_new_expense_title', 'specific', 'Add New Expense'),
(718, 1, 'label_returnable', 'specific', 'Returnable?'),
(719, 1, 'label_notes', 'specific', 'Notes'),
(720, 1, 'text_expense_list_title', 'specific', 'Expense Category List'),
(721, 1, 'label_category_name', 'specific', 'Category Name'),
(722, 1, 'title_expense_category', 'specific', 'Expense Category'),
(723, 1, 'text_expense_category_title', 'specific', 'Expense Category'),
(724, 1, 'text_new_expense_category_title', 'specific', 'Add New Expense Category'),
(725, 1, 'label_category_slug', 'specific', 'Category Slug'),
(726, 1, 'label_parent', 'specific', 'Parent'),
(727, 1, 'label_category_details', 'specific', 'Category Details'),
(728, 1, 'text_category_list_title', 'specific', 'Category List'),
(729, 1, 'title_expense_monthwise', 'specific', 'Expense Monthwise'),
(730, 1, 'text_expense_monthwise_title', 'specific', 'Expense Monthwise'),
(731, 1, 'text_print', 'specific', 'Print'),
(732, 1, 'text_supplier_list_title', 'specific', 'Supplier List'),
(733, 1, 'text_supplier_title', 'specific', 'Supplier '),
(734, 1, 'text_new_supplier_title', 'specific', 'Add New Supplier'),
(735, 1, 'title_purchase_transaction', 'specific', 'Purchase Transaction'),
(736, 1, 'text_purchase_transaction_title', 'specific', 'Purchase Transaction'),
(737, 1, 'text_transaction_title', 'specific', 'Transaction'),
(738, 1, 'text_transaction_list_title', 'specific', 'Transaction List'),
(739, 1, 'label_pmethod', 'specific', 'Payment Method'),
(740, 1, 'title_sell_transaction', 'specific', 'Sell Transaction'),
(741, 1, 'text_sell_transaction_title', 'specific', 'Sell Transaction'),
(742, 1, 'text_sell_transaction_list_title', 'specific', 'Sell Transaction List'),
(743, 1, 'title_barcode', 'specific', 'Barcode'),
(744, 1, 'text_barcode_title', 'specific', 'Barcode'),
(745, 1, 'text_barcode_generate_title', 'specific', 'Barcode Generate'),
(746, 1, 'label_product_name_with_code', 'specific', 'Product Name with Code'),
(747, 1, 'text_no_product', 'specific', 'No. Proudct'),
(748, 1, 'label_page_layout', 'specific', 'Page Layout'),
(749, 1, 'label_fields', 'specific', 'Fileds'),
(750, 1, 'button_generate', 'specific', 'Generate'),
(751, 1, 'title_category', 'specific', 'Category'),
(752, 1, 'text_category_title', 'specific', 'Category'),
(753, 1, 'text_new_category_title', 'specific', 'Add New Category'),
(754, 1, 'text_product_import_alert', 'specific', 'Product Import Alert'),
(755, 1, 'title_import_product', 'specific', 'Import Product'),
(756, 1, 'text_import_title', 'specific', 'Import'),
(757, 1, 'text_download', 'specific', 'Download'),
(758, 1, 'button_download', 'specific', 'Download'),
(759, 1, 'text_select_xls_file', 'specific', 'Select .xls File'),
(760, 1, 'button_import', 'specific', 'Import'),
(761, 1, 'title_stock_alert', 'specific', 'Stock Alert'),
(762, 1, 'text_stock_alert_title', 'specific', 'Stock Alert'),
(763, 1, 'text_stock_alert_box_title', 'specific', 'Stock Alert'),
(764, 1, 'title_expired', 'specific', 'Expired'),
(765, 1, 'text_expired_title', 'specific', 'Expired'),
(766, 1, 'text_expired_box_title', 'specific', 'Expired'),
(767, 1, 'button_expired', 'specific', 'Expired'),
(768, 1, 'button_expiring_soon', 'specific', 'Comming Soon'),
(769, 1, 'text_due', 'specific', 'Due'),
(770, 1, 'title_purchase_return', 'specific', 'Purchase Return'),
(771, 1, 'text_purchase_return_title', 'specific', 'Purchase Return'),
(772, 1, 'text_return_list_title', 'specific', 'Return List'),
(773, 1, 'text_purchase_return_list_title', 'specific', 'Purchase Return List'),
(774, 1, 'title_sell_return', 'specific', 'Sell Return'),
(775, 1, 'text_sell_return_title', 'specific', 'Sell Return'),
(776, 1, 'text_sell_return_list_title', 'specific', 'Sell Return List'),
(777, 1, 'title_giftcard', 'specific', 'Giftcard'),
(778, 1, 'text_giftcard_title', 'specific', 'Giftcard'),
(779, 1, 'text_new_giftcard_title', 'specific', 'Add New Giftcard'),
(780, 1, 'label_card_no', 'specific', 'Card No.'),
(781, 1, 'label_giftcard_value', 'specific', 'GIftcard Value'),
(782, 1, 'label_expiry_date', 'specific', 'Expiry Date'),
(783, 1, 'button_create_giftcard', 'specific', 'Create GIftcard'),
(784, 1, 'text_giftcard_list_title', 'specific', 'Giftcard List'),
(785, 1, 'label_expiry', 'specific', 'Expiry'),
(786, 1, 'label_topup', 'specific', 'Topup'),
(787, 1, 'title_giftcard_topup', 'specific', 'Giftcard Topup'),
(788, 1, 'text_giftcard_topup_title', 'specific', 'Giftcard Topup'),
(789, 1, 'text_topup_title', 'specific', 'Topup'),
(790, 1, 'text_giftcard_topup_list_title', 'specific', 'Giftcard Popup List'),
(791, 1, 'title_pos', 'specific', 'POS'),
(792, 1, 'text_gift_card', 'specific', 'Giftcard'),
(793, 1, 'button_sell_gift_card', 'specific', 'Giftcard'),
(794, 1, 'text_keyboard_shortcut', 'specific', 'Keyboard Shortcut	'),
(795, 1, 'text_holding_order', 'specific', 'Holding Order'),
(796, 1, 'text_search_product', 'specific', 'Search/Barcode Scan'),
(797, 1, 'button_add_product', 'specific', 'Add Product'),
(798, 1, 'button_purchase_now', 'specific', 'Purchase Now'),
(799, 1, 'label_add_to_cart', 'specific', 'Add To Cart'),
(800, 1, 'text_add_note', 'specific', 'Add Note'),
(801, 1, 'label_price', 'specific', 'Price'),
(802, 1, 'label_total_items', 'specific', 'Total Item'),
(803, 1, 'label_discount', 'specific', 'Discount'),
(804, 1, 'label_total_payable', 'specific', 'Total Payable'),
(805, 1, 'button_pay', 'specific', 'Pay Now'),
(806, 1, 'button_hold', 'specific', 'Hold'),
(807, 1, 'text_update_title', 'specific', 'Update '),
(808, 1, 'text_male', 'specific', 'Male'),
(809, 1, 'text_female', 'specific', 'Female'),
(810, 1, 'text_thumbnail', 'specific', 'Thumbnail'),
(811, 1, 'text_update_success', 'specific', 'Successfully Updated'),
(812, 1, 'title_user_profile', 'specific', 'User Profile'),
(813, 1, 'text_profile_title', 'specific', 'Profile'),
(814, 1, 'text_users', 'specific', 'Users'),
(815, 1, 'text_since', 'specific', 'Since'),
(816, 1, 'text_contact_information', 'specific', 'Contact Information'),
(817, 1, 'label_collection', 'specific', 'Collection'),
(818, 1, 'text_sell_report', 'specific', 'Sell Report'),
(819, 1, 'text_purchase_report', 'specific', 'Purchase Report'),
(820, 1, 'text_payment_report', 'specific', 'Payment Report'),
(821, 1, 'text_login_log', 'specific', 'Login History'),
(822, 1, 'button_collection_report', 'specific', 'Collection Report'),
(823, 1, 'button_log', 'specific', 'Log'),
(824, 1, 'text_invoice_list', 'specific', 'Invoice List'),
(825, 1, 'label_items', 'specific', 'Items'),
(826, 1, 'label_time', 'specific', 'Time'),
(827, 1, 'title_bank_transactions', 'specific', 'Bank Transactions'),
(828, 1, 'text_bank_transaction_title', 'specific', 'Bank Transaction'),
(829, 1, 'text_bank_account_title', 'specific', 'Bank Account'),
(830, 1, 'text_bank_transaction_list_title', 'specific', 'Bank Transaction List'),
(831, 1, 'button_filtering', 'specific', 'Filtering'),
(832, 1, 'text_view_all_transactions', 'specific', 'View All Transactions'),
(833, 1, 'label_account', 'specific', 'Account'),
(834, 1, 'label_credit', 'specific', 'Credit'),
(835, 1, 'label_debit', 'specific', 'Debit'),
(836, 1, 'text_unpaid', 'specific', 'Unpaid'),
(837, 1, 'title_income_source', 'specific', 'Income Source'),
(838, 1, 'text_income_source_title', 'specific', 'Income Source'),
(839, 1, 'text_new_income_source_title', 'specific', 'Add New Income Source'),
(840, 1, 'label_source_name', 'specific', 'Source Name'),
(841, 1, 'label_source_slug', 'specific', 'Source Slug'),
(842, 1, 'label_source_details', 'specific', 'Source Details'),
(843, 1, 'text_income_source_sub_title', 'specific', 'Income Source List'),
(844, 1, 'title_income_monthwise', 'specific', 'Income Monthwise'),
(845, 1, 'text_income_monthwise_title', 'specific', 'Income Monthwise'),
(846, 1, 'label_capital', 'specific', 'Capital'),
(847, 1, 'title_bank_transfer', 'specific', 'Bank Transfer'),
(848, 1, 'text_bank_transfer_title', 'specific', 'Bank Transfer'),
(849, 1, 'text_banking_title', 'specific', 'Banking'),
(850, 1, 'text_list_bank_transfer_title', 'specific', 'Bank Transfer List'),
(851, 1, 'label_from_account', 'specific', 'From Account'),
(852, 1, 'label_to_account', 'specific', 'To Account'),
(853, 1, 'title_income_and_expense', 'specific', 'Income '),
(854, 1, 'text_income_and_expense_title', 'specific', 'Income vs Expense'),
(855, 1, 'text_date', 'specific', 'Date'),
(856, 1, 'title_income', 'specific', 'Income'),
(857, 1, 'label_this_month', 'specific', 'This Month'),
(858, 1, 'label_this_year', 'specific', 'This Year'),
(859, 1, 'label_till_now', 'specific', 'Till Now'),
(860, 1, 'error_currency_title', 'specific', 'Currency Tittle is not valid'),
(861, 1, 'text_pmethod', 'specific', 'Payment Method'),
(862, 1, 'button_full_payment', 'specific', 'FULL PAYMENT'),
(863, 1, 'button_full_due', 'specific', 'FULL DUE'),
(864, 1, 'button_sell_with_installment', 'specific', 'Sell With Installment'),
(865, 1, 'text_pay_amount', 'specific', 'Pay Amount'),
(866, 1, 'placeholder_input_an_amount', 'specific', 'Input Amount'),
(867, 1, 'placeholder_note_here', 'specific', 'Note Here'),
(868, 1, 'title_installment_details', 'specific', 'Installment Details'),
(869, 1, 'label_duration', 'specific', 'Duration'),
(870, 1, 'text_days', 'specific', 'Days'),
(871, 1, 'label_interval', 'specific', 'Interval'),
(872, 1, 'label_total_installment', 'specific', 'Total Installment'),
(873, 1, 'label_interest_percentage', 'specific', 'Interest Percentage'),
(874, 1, 'label_interest_amount', 'specific', 'Interest Amount'),
(875, 1, 'text_order_details', 'specific', 'Order Details'),
(876, 1, 'error_reference_no', 'specific', 'Reference no. is not valid'),
(877, 1, 'error_date', 'specific', 'Date is not valid'),
(878, 1, 'error_total_amount', 'specific', 'Total amount is not valid'),
(879, 1, 'error_customer', 'specific', 'Customer is not valid'),
(880, 1, 'text_quotation_create_success', 'specific', 'Successfully Created'),
(881, 1, 'button_action', 'specific', 'Action'),
(882, 1, 'button_create_sell', 'specific', 'Create Sell'),
(883, 1, 'title_installment_overview', 'specific', 'Installment Overview');
INSERT INTO `language_translations` (`id`, `lang_id`, `lang_key`, `key_type`, `lang_value`) VALUES
(884, 1, 'text_installment_overview_title', 'specific', 'Installment Overview Report'),
(885, 1, 'text_installment_overview', 'specific', 'Installment Overview'),
(886, 1, 'text_invoice_count', 'specific', 'Invoice Count'),
(887, 1, 'text_interest_amount', 'specific', 'Interest Amount'),
(888, 1, 'text_amount_received', 'specific', 'Amount Received'),
(889, 1, 'text_amount_due', 'specific', 'Amount Due'),
(890, 1, 'text_expired_due_payment', 'specific', 'Expired Due Payment'),
(891, 1, 'text_all_due_payment', 'specific', 'All Due Payment'),
(892, 1, 'text_todays_due_payment', 'specific', 'Todays Due Payment'),
(893, 1, 'title_installment', 'specific', 'Installment'),
(894, 1, 'text_installment_title', 'specific', 'Installment'),
(895, 1, 'text_installment_sub_title', 'specific', 'Installment List'),
(896, 1, 'button_all_installment', 'specific', 'All Installment'),
(897, 1, 'button_due_installment', 'specific', 'Due Installment'),
(898, 1, 'button_paid_installment', 'specific', 'Paid Installment'),
(899, 1, 'label_total_ins', 'specific', 'Total Ins.'),
(900, 1, 'text_order_summary', 'specific', 'Order Summary'),
(901, 1, 'label_previous_due', 'specific', 'Previous Due'),
(902, 1, 'text_return_item', 'specific', 'Return Item'),
(903, 1, 'label_return_quantity', 'specific', 'Return Quantity'),
(904, 1, 'placeholder_type_any_note', 'specific', 'Type Note'),
(905, 1, 'error_quantity_exceed', 'specific', 'Quantity Exceed'),
(906, 1, 'text_return_success', 'specific', 'Return Successfull'),
(907, 1, 'label_purchase_note', 'specific', 'Purchase Note'),
(908, 1, 'text_purchase_update_success', 'specific', 'Successfully Updated'),
(909, 1, 'error_items', 'specific', 'Items is not valid'),
(910, 1, 'error_store_id', 'specific', 'Store is not valid'),
(911, 1, 'text_transfer_success', 'specific', 'Successfully Transfered'),
(912, 1, 'button_transfer_edit', 'specific', 'Transfer Edit'),
(913, 1, 'text_update_transfer_status_success', 'specific', 'Successfully Updated'),
(914, 1, 'label_transferred_to', 'specific', 'Transfered To'),
(915, 1, 'text_product_list', 'specific', 'Product List'),
(916, 1, 'error_category_name', 'specific', 'Category name is not valid'),
(917, 1, 'error_sup_name', 'specific', 'Suppllier name is not valid'),
(918, 1, 'error_supplier_email_or_mobile', 'specific', 'Supplier email or mobile is not valid'),
(919, 1, 'error_sup_address', 'specific', 'Supplier Address is not valid'),
(920, 1, 'error_unit_name', 'specific', 'Unit name is not valid'),
(921, 1, 'error_product_name', 'specific', 'Product name is not valid'),
(922, 1, 'error_sup_id', 'specific', 'Supplier is not valid'),
(923, 1, 'text_product_name', 'specific', 'Product Name'),
(924, 1, 'text_quantity', 'specific', 'Quantity'),
(925, 1, 'button_print', 'specific', 'Print'),
(926, 1, 'error_walking_customer_can_not_craete_due', 'specific', 'Walking Customer Can\'t to Create a Due'),
(927, 1, 'error_stock', 'specific', 'Stock amount is not valid'),
(928, 1, 'error_installment_count', 'specific', 'Installment count is not valid'),
(929, 1, 'title_bank_account', 'specific', 'Bank Account'),
(930, 1, 'text_new_bank_account_title', 'specific', 'Add New Bank Account'),
(931, 1, 'label_account_name', 'specific', 'Account Name'),
(932, 1, 'label_account_details', 'specific', 'Account Details'),
(933, 1, 'label_account_no', 'specific', 'Account No.'),
(934, 1, 'label_contact_person', 'specific', 'Contact Person'),
(935, 1, 'label_internal_banking_url', 'specific', 'Internal Banking Url'),
(936, 1, 'text_bank_account_list_title', 'specific', 'Bank Account List'),
(937, 1, 'label_account_description', 'specific', 'Account Description'),
(938, 1, 'title_bank_account_sheet', 'specific', 'Balance Sheet'),
(939, 1, 'text_bank_account_sheet_title', 'specific', 'Balance Sheet'),
(940, 1, 'text_bank_account_sheet_list_title', 'specific', 'Balance Sheet Details'),
(941, 1, 'label_account_id', 'specific', 'Account Id'),
(942, 1, 'label_transfer_to_other', 'specific', 'Transfer To Other'),
(943, 1, 'label_transfer_from_other', 'specific', 'Transfer From Other'),
(944, 1, 'label_deposit', 'specific', 'Deposit'),
(945, 1, 'label_withdraw', 'specific', 'Widthdraw'),
(946, 1, 'text_select_store', 'specific', 'Select Store'),
(947, 1, 'text_activate_success', 'specific', 'Successfully Activated'),
(948, 1, 'text_template_content_update_success', 'specific', 'Successfully updated'),
(949, 1, 'text_template_css_update_success', 'specific', 'Successfully updated'),
(950, 1, 'title_cashbook', 'specific', 'Cashbook'),
(951, 1, 'text_cashbook_title', 'specific', 'Cashbook'),
(952, 1, 'text_cashbook_details_title', 'specific', 'Cashbook Details'),
(953, 1, 'label_opening_balance', 'specific', 'Opening Balance'),
(954, 1, 'label_today_income', 'specific', 'Today Income'),
(955, 1, 'label_total_income', 'specific', 'Total Income'),
(956, 1, 'label_today_expense', 'specific', 'Today Expense'),
(957, 1, 'label_cash_in_hand', 'specific', 'Cash In Hand'),
(958, 1, 'label_today_closing_balance', 'specific', 'Today Closing Balance'),
(959, 1, 'text_balance_update_success', 'specific', 'Successfully Updated'),
(960, 1, 'title_profit_and_loss', 'specific', 'Profit vs Loss'),
(961, 1, 'text_profit_and_loss_title', 'specific', 'Profit vs Loss'),
(962, 1, 'text_profit_and_loss_details_title', 'specific', 'Profit vs Loss Details'),
(963, 1, 'text_loss_title', 'specific', 'Loss'),
(964, 1, 'text_profit_title', 'specific', 'Profit'),
(965, 1, 'label_total_profit', 'specific', 'Total Profit'),
(966, 1, 'label_total_loss', 'specific', 'Total Loss'),
(967, 1, 'label_net_profit', 'specific', 'Net Profit'),
(968, 1, 'label_source', 'specific', 'Source'),
(969, 1, 'label_slip_no', 'specific', 'Slip No.'),
(970, 1, 'label_by', 'specific', 'By'),
(971, 1, 'label_exp_category', 'specific', 'Expense Category'),
(972, 1, 'label_about', 'specific', 'About'),
(973, 1, 'button_withdraw_now', 'specific', 'Widthdraw Now'),
(974, 1, 'label_income_source', 'specific', 'Income Source'),
(975, 1, 'button_deposit_now', 'specific', 'Deposit Now'),
(976, 1, 'text_deposit_success', 'specific', 'Deposit Successfull'),
(977, 1, 'text_delete_title', 'specific', 'Delete'),
(978, 1, 'text_delete_instruction', 'specific', 'What should be done with data belong to the content?'),
(979, 1, 'label_insert_content_to', 'specific', 'Insert Content To'),
(980, 1, 'button_add_language', 'specific', 'Add Language'),
(981, 1, 'code', 'specific', 'code'),
(982, 1, 'error_code', 'specific', 'Code is not valid'),
(983, 1, 'text_uppdate_success', 'specific', 'Successfully Updated'),
(984, 1, 'error_name', 'specific', 'Name is not valid'),
(985, 1, 'text_hindi', 'specific', 'Hindi'),
(986, 1, 'text_create_success', 'specific', 'Successfully Created'),
(987, 1, 'text_gremany', 'specific', 'Germany'),
(988, 1, 'button_add_new_language', 'specific', 'Add New Language'),
(989, 1, 'text_fullscreen', 'specific', 'Fullscreen'),
(990, 1, 'text_sales', 'specific', 'Sells'),
(991, 1, 'text_quotations', 'specific', 'Quotations'),
(992, 1, 'text_purchases', 'specific', 'Purchases'),
(993, 1, 'text_transfers', 'specific', 'Transfers'),
(994, 1, 'text_customers', 'specific', 'Customers'),
(995, 1, 'text_suppliers', 'specific', 'Suppliers'),
(996, 1, 'label_payment_status', 'specific', 'Payment Status'),
(997, 1, 'button_add_sales', 'specific', 'Add Sell'),
(998, 1, 'button_list_sales', 'specific', 'SELL LIST'),
(999, 1, 'text_sales_amount', 'specific', 'Sell Amount'),
(1000, 1, 'text_discount_given', 'specific', 'Discount Given'),
(1001, 1, 'text_received_amount', 'specific', 'Received Amount'),
(1002, 1, 'button_add_quotations', 'specific', 'Add Quotation'),
(1003, 1, 'button_list_quotations', 'specific', 'Quotation List'),
(1004, 1, 'label_supplier_name', 'specific', 'Supplier Name'),
(1005, 1, 'button_add_purchases', 'specific', 'Add Purchase'),
(1006, 1, 'button_list_purchases', 'specific', 'Purchase List'),
(1007, 1, 'button_add_transfers', 'specific', 'Add Transfer'),
(1008, 1, 'button_list_transfers', 'specific', 'Transfer List'),
(1009, 1, 'button_add_customer', 'specific', 'Add Customer'),
(1010, 1, 'button_list_customers', 'specific', 'Customer List'),
(1011, 1, 'button_add_supplier', 'specific', 'Add Supplier'),
(1012, 1, 'button_list_suppliers', 'specific', 'Supplier List'),
(1013, 1, 'text_permission', 'specific', 'Permission'),
(1014, 1, 'text_recent_activities', 'specific', 'Recent Activities'),
(1015, 1, 'text_top_products', 'specific', 'Top Products'),
(1016, 1, 'text_top_customers', 'specific', 'Top Customers'),
(1017, 1, 'text_top_suppliers', 'specific', 'Top Suppliers'),
(1018, 1, 'text_top_brands', 'specific', 'Top Brands'),
(1019, 1, 'text_amount', 'specific', 'Amount'),
(1020, 1, 'text_purchase', 'specific', 'Purchase'),
(1021, 1, 'title_login_logs', 'specific', 'Login Logs'),
(1022, 1, 'title_activity_logs', 'specific', 'Activity Logs'),
(1023, 1, 'text_birthday_today', 'specific', 'Birthday Today'),
(1024, 1, 'text_birthday_coming', 'specific', 'Birthday Coming'),
(1025, 1, 'title_income_vs_expense', 'specific', 'Income vs Expense'),
(1026, 1, 'text_download_as_jpg', 'specific', 'Download as PNG'),
(1027, 1, 'error_disabled_by_default', 'specific', 'Disabled By Default'),
(1028, 1, 'button_empty_value', 'specific', 'Empty Value'),
(1029, 1, 'text_invoice_create_success', 'specific', 'Successfully Created'),
(1030, 1, 'button_send_sms', 'specific', 'Send SMS'),
(1031, 1, 'button_email', 'specific', 'Email'),
(1032, 1, 'button_back_to_pos', 'specific', 'Back to POS'),
(1033, 1, 'error_status', 'specific', 'Status is not valid'),
(1034, 1, 'error_reference_no_exist', 'specific', 'Ref. no. is not valid'),
(1035, 1, 'text_view_invoice_title', 'specific', 'View Invoice Title'),
(1036, 1, 'text_new_dashboard', 'specific', 'New Dashboard'),
(1037, 1, 'text_recent_customer_box_title', 'specific', 'Recent Customers'),
(1038, 1, 'label_customer_mobile', 'specific', 'Customer Mobile'),
(1039, 1, 'label_invoice_note', 'specific', 'Invoice Note'),
(1040, 1, 'text_sell_update_success', 'specific', 'Successfully Updated'),
(1041, 1, 'label_customer_id', 'specific', 'Customer Id'),
(1042, 1, 'label_returened_by', 'specific', 'Returned By'),
(1043, 1, 'text_return_products', 'specific', 'Return Products'),
(1044, 1, 'button_topup', 'specific', 'Topup'),
(1045, 1, 'button_topup_now', 'specific', 'Topup Now'),
(1046, 1, 'error_amount', 'specific', 'Amount is not Valid'),
(1047, 1, 'error_expiry_date', 'specific', 'Expiry Date'),
(1048, 1, 'text_topup_success', 'specific', 'Topup Successfull'),
(1049, 1, 'label_vat_number', 'specific', 'VAT Number'),
(1050, 1, 'label_unit_price', 'specific', 'Unit Price'),
(1051, 1, 'label_shipping', 'specific', 'Shipping'),
(1052, 1, 'label_stamp_and_signature', 'specific', 'Stamp and Signature'),
(1053, 1, 'title_quotation_edit', 'specific', 'Quotation Edit'),
(1054, 1, 'text_quotation_edit_title', 'specific', 'Quotation Edit'),
(1055, 1, 'text_quotation_update_success', 'specific', 'Successfully Updated'),
(1056, 1, 'error_product_not_found', 'specific', 'Product Not Found'),
(1057, 1, 'error_invoice_product_type', 'specific', 'Invoice product type is not valid'),
(1058, 1, 'label_checkout_status', 'specific', 'Checkout Status'),
(1059, 1, 'label_sub_total', 'specific', 'Sub Total'),
(1060, 1, 'text_payments', 'specific', 'Payments'),
(1061, 1, 'error_select_at_least_one_item', 'specific', 'Select at least one product'),
(1062, 1, 'button_pay_now', 'specific', 'Pay Now'),
(1063, 1, 'text_billing_details', 'specific', 'Billing Details'),
(1064, 1, 'Print Barcode', 'specific', 'undefined'),
(1065, 1, 'error_new_category_name', 'specific', 'Please select a category'),
(1066, 1, 'error_customer_name', 'specific', 'Customer name is not valid'),
(1067, 1, 'error_expired_date_below', 'specific', 'Expired date is not valid'),
(1068, 1, 'label_insert_invoice_to', 'specific', 'Insert Invoice To'),
(1069, 1, 'error_new_customer_name', 'specific', 'Please select a customer'),
(1070, 1, 'title_customer_profile', 'specific', 'Customer Profile'),
(1071, 1, 'text_total_purchase', 'specific', 'Total Purchase'),
(1072, 1, 'label_mobile_phone', 'specific', 'Mobile Phone'),
(1073, 1, 'button_transaction_list', 'specific', 'Transaction List'),
(1074, 1, 'label_ref_invoice_Id', 'specific', 'Ref. Invoice Id'),
(1075, 1, 'error_code_name', 'specific', 'Code name is not valid'),
(1076, 1, 'title_supplier_profile', 'specific', 'Supplier Profile'),
(1077, 1, 'text_supplier_profile_title', 'specific', 'Supplier Profile'),
(1078, 1, 'text_supplier_products', 'specific', 'Supplier Products'),
(1079, 1, 'button_products', 'specific', 'Products'),
(1080, 1, 'text_balance_amount', 'specific', 'Balance Amount'),
(1081, 1, 'text_sells', 'specific', 'Sells'),
(1082, 1, 'text_chart', 'specific', 'Chart'),
(1083, 1, 'text_purchase_invoice_list', 'specific', 'Purchase Invoice List'),
(1084, 1, 'button_all_purchase', 'specific', 'All Purchase'),
(1085, 1, 'button_due_purchase', 'specific', 'Due Purchase'),
(1086, 1, 'button_paid_purchase', 'specific', 'Paid Purchase'),
(1087, 1, 'button_stock_transfer', 'specific', 'Stock Transfer'),
(1088, 1, 'text_selling_invoice_list', 'specific', 'Selling Invoice List'),
(1089, 1, 'error_account', 'specific', 'Account is not valid'),
(1090, 1, 'error_ref_no', 'specific', 'Ref. no. is not valid'),
(1091, 1, 'error_about', 'specific', 'About is not valid'),
(1092, 1, 'error_title', 'specific', 'Title is not valid'),
(1093, 1, 'text_withdraw_success', 'specific', 'successfully created'),
(1094, 1, 'error_from_account', 'specific', 'From account is not valid'),
(1095, 1, 'error_to_account', 'specific', 'To account is not valid'),
(1096, 1, 'error_same_account', 'specific', 'Receiver and sender can\'t be same'),
(1097, 1, 'error_insufficient_balance', 'specific', 'Insufficient balance'),
(1098, 1, 'error_ref_no_exist', 'specific', 'Ref. no. already exists'),
(1099, 1, 'error_account_name', 'specific', 'Account name is not valid'),
(1100, 1, 'error_account_no', 'specific', 'Account no. is not valid'),
(1101, 1, 'error_contact_person', 'specific', 'Contact person is not valid'),
(1102, 1, 'error_phone_number', 'specific', 'Phone number is not valid'),
(1103, 1, 'text_income', 'specific', 'Income'),
(1104, 1, 'text_expense', 'specific', 'Expense'),
(1105, 1, 'text_update_income_source_success', 'specific', 'Successfully updated'),
(1106, 1, 'error_new_source_name', 'specific', 'Please select a source'),
(1107, 1, 'text_delete_income_source_success', 'specific', 'Successfully deleted'),
(1108, 1, 'label_day', 'specific', 'Day'),
(1109, 1, 'error_category_id', 'specific', 'Category id is not valid'),
(1110, 1, 'button_viefw', 'specific', 'View'),
(1111, 1, 'label_summary', 'specific', 'Summary'),
(1112, 1, 'label_grand_total', 'specific', 'Grand Total'),
(1113, 1, 'label_this_week', 'specific', 'This Week'),
(1114, 1, 'error_loan_from', 'specific', 'Loan from is not valid'),
(1115, 1, 'error_loan_headline', 'specific', 'Loan headline is not valid'),
(1116, 1, 'error_loan_amount', 'specific', 'Loan amount is not valid'),
(1117, 1, 'text_take_loan_success', 'specific', 'Successfully created'),
(1118, 1, 'error_paid_amount', 'specific', 'Paid amount is not valid'),
(1119, 1, 'error_pay_amount_greater_than_due_amount', 'specific', 'Pay amount can\'t be greater than due amount'),
(1120, 1, 'text_loan_paid_success', 'specific', 'Successfully paid'),
(1121, 1, 'error_sms_text', 'specific', 'SMS text is not valid'),
(1122, 1, 'text_success_sms_sent', 'specific', 'SMS successfully sent'),
(1123, 1, 'error_user_name', 'specific', 'user name is not valid'),
(1124, 1, 'error_user_email_or_mobile', 'specific', 'Email or mobile is not valid'),
(1125, 1, 'error_user_group', 'specific', 'User Group is not valid'),
(1126, 1, 'error_user_password_match', 'specific', 'Retype password didn\'t matched'),
(1127, 1, 'error_user_password_length', 'specific', 'User password length is not valid'),
(1128, 1, 'text_income_vs_expense', 'specific', 'Income vs Expense'),
(1129, 1, 'error_mobile', 'specific', 'Mobile number is not valid'),
(1130, 1, 'error_email', 'specific', 'Email is not valid'),
(1131, 1, 'error_zip_code', 'specific', 'Zip code is not valid'),
(1132, 1, 'error_addreess', 'specific', 'Address is not valid'),
(1133, 1, 'error_preference_receipt_template', 'specific', 'Receipt template is not valid'),
(1134, 1, 'error_currency', 'specific', 'Currency is not valid'),
(1135, 1, 'error_brand_name', 'specific', 'Please select a brand name'),
(1136, 1, 'title_brand_profile', 'specific', 'Brand Profile'),
(1137, 1, 'text_brand_profile_title', 'specific', 'Brand Profile'),
(1138, 1, 'text_brands', 'specific', 'Brands'),
(1139, 1, 'text_brand_products', 'specific', 'Brand Products'),
(1140, 1, 'button_all_products', 'specific', 'All Products'),
(1141, 1, 'text_total_sell', 'specific', 'Total Sell'),
(1142, 1, 'label_brand_name', 'specific', 'Brand Name'),
(1143, 1, 'label_insert_product_to', 'specific', 'Insert Product To'),
(1144, 1, 'error_currency_code', 'specific', 'Currency code is not valid'),
(1145, 1, 'error_currency_symbol', 'specific', 'Currency symbol is not valid'),
(1146, 1, 'error_currency_decimal_place', 'specific', 'Decimal number is not valid'),
(1147, 1, 'error_pmethod_name', 'specific', 'Payment method is not valid'),
(1148, 1, 'label_invoice_to', 'specific', 'Invoice To'),
(1149, 1, 'error_delete_unit_name', 'specific', 'Please select a unit'),
(1150, 1, 'label_ip', 'specific', 'Ip'),
(1151, 1, 'error_taxrate_name', 'specific', 'Taxrate name is not valid'),
(1152, 1, 'error_taxrate', 'specific', 'Taxrate is not valid'),
(1153, 1, 'error_delete_taxrate_name', 'specific', 'Please select a taxrate'),
(1154, 1, 'error_box_name', 'specific', 'Box name is not valid'),
(1155, 1, 'error_delete_box_name', 'specific', 'Please select a box'),
(1156, 1, 'label_success', 'specific', 'Success'),
(1157, 1, 'title_customer_analysis', 'specific', 'Customer Analysis'),
(1158, 1, 'title_customer_analytics', 'specific', 'Customer Analytics'),
(1159, 1, 'error_not_found', 'specific', 'Not Found'),
(1160, 1, 'menu_sell_list', 'specific', 'SELL LIST'),
(1161, 1, 'menu_sell_log', 'specific', 'SELL LOG'),
(1162, 1, 'menu_purchase_logs', 'specific', 'PURCHASE LOG'),
(1163, 1, 'menu_receive_list', 'specific', 'RECEIVE LIST'),
(1164, 1, 'menu_statements', 'specific', 'STATEMENTS'),
(1165, 1, 'menu_data_reset', 'specific', 'DATA RESET'),
(1166, 1, 'placeholder_search_here', 'specific', 'Search Here...'),
(1167, 1, 'text_sell_list_title', 'specific', 'Sell List'),
(1168, 1, 'text_invoices', 'specific', 'Invoices'),
(1169, 1, 'placeholder_input_discount_amount', 'specific', 'Input Discount Amount'),
(1170, 1, 'label_previous_due_paid', 'specific', 'Prev. Due Paid'),
(1171, 1, 'button_add_purchase', 'specific', 'Add Purchase'),
(1172, 1, 'text_selling_tax', 'specific', 'Selling Tax'),
(1173, 1, 'text_igst', 'specific', 'IGST'),
(1174, 1, 'text_cgst', 'specific', 'CGST'),
(1175, 1, 'text_sgst', 'specific', 'SGST'),
(1176, 1, 'text_return_amount', 'specific', 'Return Amount'),
(1177, 1, 'text_sell_due_paid_success', 'specific', 'Due successfully paid'),
(1178, 1, 'text_images', 'specific', 'Images'),
(1179, 1, 'label_hsn_code', 'specific', 'HSN Code'),
(1180, 1, 'label_select', 'specific', '-- Please Select --'),
(1181, 1, 'label_sold', 'specific', 'Sold'),
(1182, 1, 'button_view_details', 'specific', 'View'),
(1183, 1, 'text_installment_details', 'specific', 'Installment Details'),
(1184, 1, 'label_initial_payment', 'specific', 'Initial Payment'),
(1185, 1, 'label_interval_count', 'specific', 'Internal Count'),
(1186, 1, 'label_installment_count', 'specific', 'Installment Count'),
(1187, 1, 'label_last_installment_date', 'specific', 'Last Installment Date'),
(1188, 1, 'label_installment_end_date', 'specific', 'Installment End Date'),
(1189, 1, 'label_sl', 'specific', 'SL'),
(1190, 1, 'text_update_installment_payment_success', 'specific', 'Installment payment successfull'),
(1191, 1, 'error_amount_exceed', 'specific', 'Amount Exceed'),
(1192, 1, 'text_expiry', 'specific', 'Expiry'),
(1193, 1, 'text_opening_balance_update_success', 'specific', 'Opening balance successfully updated'),
(1194, 1, 'title_reset_your_system', 'specific', 'Reset your system'),
(1195, 1, 'title_sell_log', 'specific', 'Sell Log'),
(1196, 1, 'text_sell_log_title', 'specific', 'Sell Log'),
(1197, 1, 'text_sell_title', 'specific', 'Sell'),
(1198, 1, 'label_gtin', 'specific', ''),
(1199, 1, 'button_add_balance', 'specific', 'Add Balance'),
(1200, 1, 'button_statement', 'specific', 'Statement'),
(1201, 1, 'text_all_invoice', 'specific', 'All Invoice'),
(1202, 1, 'label_prev_due', 'specific', 'Prev. Due'),
(1203, 1, 'error_invoice_id', 'specific', 'Invoice id is not valid'),
(1204, 1, 'title_settings', 'specific', ''),
(1205, 1, 'text_cronjob', 'specific', ''),
(1208, 1, 'button_due_invoice_list', 'specific', ''),
(1209, 1, 'text_substract_amount', 'specific', ''),
(1210, 1, 'text_balance_added', 'specific', ''),
(1211, 1, 'button_substract_balance', 'specific', ''),
(1212, 1, 'title_purchase_log', 'specific', 'Purchase log'),
(1213, 1, 'text_purchase_log_title', 'specific', 'Purchase Log'),
(1214, 1, 'title_log_in', 'specific', ''),
(1215, 1, 'text_demo', 'specific', 'This is a demo version. Data will be reset in every 6 hours interval. &lt;a style=&quot;font-weight:bold&quot; href=&quot;http://docs.itsolution24.com/pos/&quot;&gt;Online Documentation&lt;/a&gt;  |  &lt;a style=&quot;color:#aafff0;font-weight:bold&quot; href=&quot;https://codecanyon.net/cart/configure_before_adding/22702683&quot;&gt;Buy Now&lt;/a&gt;'),
(1216, 1, 'error_disabled_in_demo', 'specific', 'This feature is disable in error!'),
(1217, 1, 'text_order_title', 'specific', ''),
(1218, 1, 'error_purchase_price', 'specific', ''),
(1219, 1, 'text_list__transfer__title', 'specific', ''),
(1220, 1, 'text_download_sample_format_file', 'specific', ''),
(1221, 1, 'text_barcode_print', 'specific', ''),
(1222, 1, 'button_semd_email', 'specific', ''),
(1223, 1, 'button_send_email', 'specific', 'Send Email'),
(1224, 1, 'error_email_not_sent', 'specific', ''),
(1225, 1, 'text_success_email_sent', 'specific', ''),
(1226, 1, 'button_installment_payment', 'specific', ''),
(1227, 1, 'text_sell_log_list_title', 'specific', 'Sell Log Details'),
(1228, 1, 'text_purchase_log_list_title', 'specific', 'Purchase Log Details'),
(1229, 1, 'text_stock_transfer_title', 'specific', ''),
(1230, 1, 'title_receive', 'specific', 'Receive'),
(1231, 1, 'text_stock_receive_title', 'specific', 'Stock Receive'),
(1232, 1, 'text_receive_title', 'specific', 'Receive'),
(1233, 1, 'text_list__receive__title', 'specific', 'Receive Details'),
(1234, 1, 'label_what_for', 'specific', ''),
(1235, 1, 'error_out_of_stock', 'specific', ''),
(1236, 1, 'xxx', 'specific', ''),
(1237, 1, 'error_login', 'specific', ''),
(1238, 1, 'text_purchase_due_paid_success', 'specific', ''),
(1239, 1, 'text_trash', 'specific', ''),
(1240, 1, 'button_restore_all', 'specific', ''),
(1241, 1, 'success_restore_all', 'specific', ''),
(1242, 1, 'title_customer_statement', 'specific', ''),
(1243, 1, 'text_statement_title', 'specific', ''),
(1244, 1, 'title_profit', 'specific', ''),
(1245, 1, 'error_return_quantity_exceed', 'specific', ''),
(1246, 1, 'label_transferred_from', 'specific', ''),
(1247, 1, 'label_member_since', 'specific', ''),
(1248, 1, 'text_not_found', 'specific', ''),
(1249, 1, 'label_logged_in', 'specific', ''),
(1250, 1, 'text_disabled', 'specific', ''),
(1251, 1, 'text_gtin', 'specific', ''),
(1252, 1, 'text_balance', 'specific', ''),
(1253, 1, 'error_invalid_username_password', 'specific', ''),
(1254, 1, 'error_installment_interest_percentage', 'specific', ''),
(1255, 1, 'error_installment_interest_amount', 'specific', ''),
(1256, 1, 'button_resend', 'specific', ''),
(1257, 1, 'error_sms_not_sent', 'specific', ''),
(1258, 1, 'text_sms_logs_title', 'specific', ''),
(1259, 1, 'text_sms_history_title', 'specific', ''),
(1260, 1, 'error_mobile_exist', 'specific', ''),
(1261, 1, 'text_success_sms_schedule', 'specific', ''),
(1262, 1, 'text_success_sms_added_to_schedule', 'specific', ''),
(1263, 1, 'text_mimsms', 'specific', ''),
(1264, 1, 'label_api_token', 'specific', ''),
(1265, 1, 'error_gateway', 'specific', ''),
(1266, 1, 'error_sms_not_send', 'specific', ''),
(1267, 1, 'invoice_sms_text', 'specific', 'Dear [customer_name],  Invoice ID: [invoice_id],  Payable: [payable_amount],  Paid: [paid_amount] ,  Due: [due]. Purchase at- [date_time]. Regards, [store_name],  [address]'),
(1268, 1, 'text_stock_register', 'specific', ''),
(1269, 1, 'text_urdu', 'specific', ''),
(1270, 1, 'error_default_language', 'specific', ''),
(1271, 1, 'error_active_or_sold', 'specific', ''),
(1272, 1, 'title_home', 'specific', 'Home'),
(1273, 1, 'error_supplier_name', 'specific', ''),
(1274, 1, 'error_expired_date_belowx', 'specific', ''),
(1275, 1, 'title_categories', 'specific', ''),
(1276, 1, 'title_products', 'specific', 'Products'),
(1277, 1, 'title_shop_on_sale', 'specific', ''),
(1278, 1, 'title_cart', 'specific', 'Cart'),
(1279, 1, 'title_wishlist', 'specific', ''),
(1280, 1, 'title_account', 'specific', ''),
(1281, 1, 'title_contact', 'specific', ''),
(1282, 1, 'title_contact_us', 'specific', ''),
(1283, 1, 'title_return_refund', 'specific', ''),
(1284, 1, 'title_faq', 'specific', ''),
(1285, 1, 'title_terms_condition', 'specific', ''),
(1286, 1, 'title_support', 'specific', ''),
(1287, 1, 'title_login', 'specific', ''),
(1288, 1, 'title_about', 'specific', ''),
(1289, 1, 'text_restore_completed', 'specific', 'Restored successfully completed'),
(1290, 1, 'error_receipt_printer', 'specific', 'Receipt printer is not valid'),
(1291, 1, 'title_checkout', 'specific', 'Checkout'),
(1292, 1, 'label_credit_balance', 'specific', 'Credit Balance'),
(1293, 1, 'label_giftcard_taken', 'specific', 'Gift Card taken'),
(1294, 1, 'text_are_you_sure', 'specific', 'Are you sure?'),
(1295, 1, 'text_information', 'specific', 'Information'),
(1296, 1, 'text_store_access_success', 'specific', 'Store  successfully activated'),
(1297, 1, 'title_cart_empty', 'specific', 'Cart is empty'),
(1298, 1, 'title_payment', 'specific', 'Payment'),
(1299, 1, 'error_installment_duration', 'specific', 'Installment duration is not valid'),
(1300, 1, 'error_password_mismatch', 'specific', 'Confirm password did\'t match'),
(1301, 1, 'error_email_exist', 'specific', 'Email already exists!');

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `code_name`, `brand_details`, `brand_image`, `created_at`, `updated_at`) VALUES
(1, 'No brand', 'no_brand', '', '/products/trip_teak_small_345x345-2x.jpg', '2019-03-31 03:36:47', '2019-06-21 09:58:09');

--
-- Dumping data for table `brand_to_store`
--

INSERT INTO `brand_to_store` (`b2s_id`, `brand_id`, `store_id`, `status`, `sort_order`) VALUES
(1, 1, 1, 1, 0);


--
-- Dumping data for table `income_sources`
--

INSERT INTO `income_sources` (`source_id`, `source_name`, `type`, `source_slug`, `parent_id`, `source_details`, `for_sell`, `for_purchase_return`, `for_due_collection`, `for_loan`, `for_giftcard_sell`, `for_topup`, `for_stock_transfer`, `for_purchase_delete`, `for_expense_delete`, `profitable`, `show_in_income`, `status`, `is_hide`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Sell', 'credit', 'sell', 0, '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 'yes', 'no', 1, 1, 0, '2019-02-27 04:54:07', '2019-04-29 01:58:28'),
(2, 'Purchase Return', 'credit', 'purchase_return', 0, '', 0, 1, 0, 0, 0, 0, 0, 0, 0, 'no', 'yes', 1, 1, 0, '2019-02-27 05:17:43', '2019-04-29 01:58:31'),
(3, 'Due Collection', 'credit', 'due_collection', 0, '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 'yes', 'no', 1, 1, 0, '2019-03-01 08:04:58', '2019-04-29 01:58:34'),
(10, 'Krishan', 'credit', 'krishan', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 'no', 'yes', 1, 0, 0, '2019-02-17 05:46:44', '2019-03-09 05:16:40'),
(12, 'Others', 'credit', 'others', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 'yes', 'yes', 1, 1, 0, '2019-02-17 06:22:46', '2019-04-29 01:58:42'),
(13, 'Loan Taken', 'credit', 'loan_taken', 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 'no', 'yes', 1, 1, 0, '2019-03-06 06:04:54', '2019-04-29 01:58:43'),
(14, 'Giftcard Sell', 'credit', 'giftcard_sell', 0, '', 0, 0, 0, 0, 1, 0, 0, 0, 0, 'no', 'yes', 1, 1, 0, '2019-03-07 12:53:43', '2019-04-29 01:58:45'),
(15, 'Giftcard Topup', 'credit', 'giftcard_topup', 0, '', 0, 0, 0, 0, 0, 1, 0, 0, 0, 'no', 'yes', 1, 1, 0, '2019-03-07 12:53:55', '2019-04-29 01:58:46'),
(16, 'Stock Transfer', 'credit', 'stock_transfer', 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 'no', 'yes', 1, 1, 0, '2019-03-08 04:14:39', '2019-04-29 01:58:48'),
(17, 'Purchase Delete', 'credit', 'purchase_delete', 0, '', 0, 0, 0, 0, 0, 0, 0, 1, 0, 'no', 'yes', 1, 1, 0, '2019-03-08 04:14:39', '2019-06-29 13:00:13'),
(18, 'Expense Delete', 'credit', 'expense_delete', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 1, 'no', 'yes', 1, 1, 0, '2019-03-08 04:14:39', '2019-04-29 01:58:48');

--
-- Dumping data for table `pos_templates`
--

INSERT INTO `pos_templates` (`template_id`, `template_name`, `template_content`, `template_css`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'Default', '&lt;section class=&quot;receipt-template&quot;&gt;\n\n        &lt;header class=&quot;receipt-header&quot;&gt;\n            &lt;div class=&quot;logo-area&quot;&gt;\n                &lt;img class=&quot;logo&quot; src=&quot;{{ logo_url }}&quot;&gt;\n            &lt;/div&gt;\n            &lt;h2 class=&quot;store-name&quot;&gt;{{ store_name }}&lt;/h2&gt;\n            &lt;div class=&quot;address-area&quot;&gt;\n                &lt;span class=&quot;info address&quot;&gt;{{ store_address }}&lt;/span&gt;\n                &lt;div class=&quot;block&quot;&gt;\n                    &lt;span class=&quot;info phone&quot;&gt;Mobile: {{ store_phone }}&lt;/span&gt;, &lt;span class=&quot;info email&quot;&gt;Email: {{ store_email }}&lt;/span&gt;\n                &lt;/div&gt;\n            &lt;/div&gt;\n        &lt;/header&gt;\n        \n        &lt;section class=&quot;info-area&quot;&gt;\n            &lt;table&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;&lt;span&gt;Invoice ID:&lt;/td&gt;\n                    &lt;td&gt;{{ invoice_id }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;VAT-Reg:&lt;/td&gt;\n                    &lt;td&gt;{{ vat_reg }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;&lt;span&gt;Date:&lt;/td&gt;\n                    &lt;td&gt;{{ date_time }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;&lt;span&gt;GST Reg:&lt;/td&gt;\n                    &lt;td&gt;{{ gst_reg }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;Customer Name:&lt;/td&gt;\n                    &lt;td&gt;{{ customer_name }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;Customer Phone:&lt;/td&gt;\n                    &lt;td&gt;{{ customer_phone }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-30&quot;&gt;GTIN:&lt;/td&gt;\n                    &lt;td&gt;{{ gtin }}&lt;/td&gt;\n                &lt;/tr&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;h4 class=&quot;main-title&quot;&gt;INVOICES&lt;/h4&gt;\n        \n        {{ if items }}\n        &lt;section class=&quot;listing-area item-list&quot;&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;w-10 text-center&quot;&gt;Sl.&lt;/td&gt;\n                        &lt;td class=&quot;w-40 text-center&quot;&gt;Name&lt;/td&gt;\n                        &lt;td class=&quot;w-15 text-center&quot;&gt;Qty&lt;/td&gt;\n                        &lt;td class=&quot;w-15 text-right&quot;&gt;Price&lt;/td&gt;\n                        &lt;td class=&quot;w-20 text-right&quot;&gt;Amount&lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{ items }}\n                        &lt;tr&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ sl }}&lt;/td&gt;\n                            &lt;td&gt;{{ item_name }} \n                                {{ if invoice_view == indian_gst }} \n                                    &lt;small&gt;[HSN-{{ hsn_code }}]&lt;/small&gt;\n                                {{ endif }}\n                            &lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ item_quantity }} {{ unitName }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_price }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_total }}&lt;/td&gt;   \n                        &lt;/tr&gt;\n                    {{ /items }}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        {{ endif }}\n        \n        &lt;section class=&quot;info-area calculation-area&quot;&gt;\n            &lt;table&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Total Amt:&lt;/td&gt;\n                    &lt;td&gt;{{ subtotal }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Order Tax:&lt;/td&gt;\n                    &lt;td&gt;{{ order_tax }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Discount:&lt;/td&gt;\n                    &lt;td&gt;{{ discount_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Shipping Chrg:&lt;/td&gt;\n                    &lt;td&gt;{{ shipping_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Others Chrg:&lt;/td&gt;\n                    &lt;td&gt;{{ others_charge }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Previous Due:&lt;/td&gt;\n                    &lt;td&gt;{{ previous_due }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Total Due:&lt;/td&gt;\n                    &lt;td&gt;{{ payable_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Amount Paid:&lt;/td&gt;\n                    &lt;td&gt;{{ paid_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Change:&lt;/td&gt;\n                    &lt;td&gt;{{ change }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Due:&lt;/td&gt;\n                    &lt;td&gt;{{ final_due }}&lt;/td&gt;\n                &lt;/tr&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;info-area italic&quot;&gt;\n            &lt;span class=&quot;text-small&quot;&gt;&lt;b&gt;In Text:&lt;/b&gt; {{ amount_in_text }}&lt;/span&gt;\n        &lt;/section&gt;\n\n        {{ if return_items }}\n        &lt;section class=&quot;listing-area payment-list&quot;&gt;\n            &lt;div class=&quot;heading&quot;&gt;\n                &lt;h2 class=&quot;sub-title&quot;&gt;Returns&lt;/h2&gt;\n            &lt;/div&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;td class=&quot;w-5 text-center&quot;&gt;Sl.&lt;/td&gt;\n                    &lt;td class=&quot;w-25 text-center&quot;&gt;Returned at&lt;/td&gt;\n                    &lt;td class=&quot;w-30 text-center&quot;&gt;Item Name&lt;/td&gt;\n                    &lt;td class=&quot;w-20 text-right&quot;&gt;Qty&lt;/td&gt;\n                    &lt;td class=&quot;w-20 text-right&quot;&gt;Amt&lt;/td&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{return_items}}\n                        &lt;tr&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ sl }}&lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ created_at }}&lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ item_name }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_quantity }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_total }}&lt;/td&gt; \n                        &lt;/tr&gt;\n                    {{/return_items}}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        {{ endif }}\n        \n        {{ if payments }}\n        &lt;section class=&quot;listing-area payment-list&quot;&gt;\n            &lt;div class=&quot;heading&quot;&gt;\n                &lt;h2 class=&quot;sub-title&quot;&gt;Payments&lt;/h2&gt;\n            &lt;/div&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;td class=&quot;w-10 text-center&quot;&gt;Sl.&lt;/td&gt;\n                    &lt;td class=&quot;w-50 text-center&quot;&gt;Payment Method&lt;/td&gt;\n                    &lt;td class=&quot;w-20 text-right&quot;&gt;Amount&lt;/td&gt;\n                    &lt;td class=&quot;w-20 text-right&quot;&gt;Balance&lt;/td&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{ payments }}\n                        &lt;tr&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ sl }}&lt;/td&gt;\n                            &lt;td&gt;{{ name }} by {{ by }} on {{ created_at }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ amount }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ pos_balance }}&lt;/td&gt; \n                        &lt;/tr&gt;\n                    {{ /payments }}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        {{ endif }}\n           \n        {{ if invoice_view != \'standard\' }}\n        {{ if taxes }}\n        &lt;section class=&quot;listing-area payment-list&quot;&gt;\n            &lt;div class=&quot;heading&quot;&gt;\n                &lt;h2 class=&quot;sub-title&quot;&gt;Tax Information&lt;/h2&gt;\n            &lt;/div&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;td class=&quot;w-25&quot;&gt;Name&lt;/td&gt;\n                    &lt;td class=&quot;w-25 text-center&quot;&gt;Code&lt;/td&gt;\n                    &lt;td class=&quot;w-25 text-right&quot;&gt;Qty&lt;/td&gt;\n                    &lt;td class=&quot;w-25 text-right&quot;&gt;Tax Amt.&lt;/td&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{ taxes }}\n                        &lt;tr&gt;\n                            &lt;td&gt;{{ taxrate_name }}&lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ code_name }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ qty }}&lt;/td&gt; \n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_tax }}&lt;/td&gt; \n                        &lt;/tr&gt;\n                    {{ /taxes }}\n                    {{ if invoice_view == \'indian_gst\' }}\n                        &lt;tr class=&quot;bg-gray&quot;&gt;\n                            &lt;td&gt;Order Tax: {{ order_tax }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;CGST: {{ cgst }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;SGST: {{ sgst }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;IGST: {{ igst }}&lt;/td&gt;\n                        &lt;/tr&gt;\n                     {{ endif }}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        {{ endif }}\n        {{ endif }}\n        \n        {{ if barcode }}\n        &lt;section class=&quot;info-area barcode-area&quot;&gt;\n            {{ barcode }}\n        &lt;/section&gt;\n        {{ endif }}\n        \n        &lt;section class=&quot;info-area align-center footer-area&quot;&gt;\n            &lt;span class=&quot;block&quot;&gt;Sold product No Claim. No Change, New product One Month Warranty.&lt;/span&gt;\n            &lt;span class=&quot;block bold&quot;&gt;Thank You for Choosing Us.&lt;/span&gt;\n        &lt;/section&gt;\n        \n &lt;/section&gt;', '/*Common CSS*/\n        .receipt-template {\n            width:302px;\n            margin:0 auto;\n        }\n        .receipt-template .text-small {\n            font-size: 10px;\n        }\n        .receipt-template .block {\n            display: block;\n        }\n        .receipt-template .inline-block {\n            display: inline-block;\n        }\n        .receipt-template .bold {\n            font-weight: 700;\n        }\n        .receipt-template .italic {\n            font-style: italic;\n        }\n        .receipt-template .align-right {\n            text-align: right;\n        }\n        .receipt-template .align-center {\n            text-align: center;\n        }\n        .receipt-template .main-title {\n            font-size: 14px;\n            font-weight: 700;\n            text-align: center;\n            margin: 10px 0 5px 0;\n            padding: 0;\n        }\n        .receipt-template .heading {\n            position: relation;\n        }\n        .receipt-template .title {\n            font-size: 16px;\n            font-weight: 700;\n            margin: 10px 0 5px 0;\n        }\n        .receipt-template .sub-title {\n            font-size: 12px;\n            font-weight: 700;\n            margin: 10px 0 5px 0;\n        }\n        .receipt-template table {\n            width: 100%;\n        }\n        .receipt-template td,\n        .receipt-template th {\n            font-size:12px;\n        }\n        .receipt-template .info-area {\n            font-size: 12px;      \n            line-height: 1.222;  \n        }\n        .receipt-template .listing-area {\n            line-height: 1.222;\n        }\n        .receipt-template .listing-area table {}\n        .receipt-template .listing-area table thead tr {\n            border-top: 1px solid #000;\n            border-bottom: 1px solid #000;\n            font-weight: 700;\n        }\n        .receipt-template .listing-area table tbody tr {\n            border-top: 1px dashed #000;\n            border-bottom: 1px dashed #000;\n        }\n        .receipt-template .listing-area table tbody tr:last-child {\n            border-bottom: none;\n        }\n        .receipt-template .listing-area table td {\n            vertical-align: top;\n        }\n        .receipt-template .info-area table {}\n        .receipt-template .info-area table thead tr {\n            border-top: 1px solid #000;\n            border-bottom: 1px solid #000;\n        }\n    \n /*Receipt Heading*/\n        .receipt-template .receipt-header {\n            text-align: center;\n        }\n        .receipt-template .receipt-header .logo-area {\n            width: 80px;\n            height: 80px;\n            margin: 0 auto;\n        }\n        .receipt-template .receipt-header .logo-area img.logo {\n            display: inline-block;\n            max-width: 100%;\n            max-height: 100%;\n        }\n        .receipt-template .receipt-header .address-area {\n            margin-bottom: 5px;\n            line-height: 1;\n        }\n        .receipt-template .receipt-header .info {\n            font-size: 12px;\n        }\n        .receipt-template .receipt-header .store-name {\n            font-size: 24px;\n            font-weight: 700;\n            margin: 0;\n            padding: 0;\n        }\n        \n        \n/*Invoice Info Area*/ \n    .receipt-template .invoice-info-area {}\n    \n/*Customer Customer Area*/\n    .receipt-template .customer-area {\n        margin-top:10px;\n    }\n\n/*Calculation Area*/\n    .receipt-template .calculation-area {\n        border-top: 2px solid #000;\n        font-weight: bold;\n    }\n    .receipt-template .calculation-area table td {\n        text-align: right;\n    }\n    .receipt-template .calculation-area table td:nth-child(2) {\n        border-bottom: 1px dashed #000;\n    }\n\n/*Item Listing*/\n    .receipt-template .item-list table tr {\n    }\n    \n/*Barcode Area*/\n    .receipt-template .barcode-area {\n        margin-top: 10px;\n        text-align: center;\n    }\n    .receipt-template .barcode-area img {\n        max-width: 100%;\n        display: inline-block;\n    }\n    \n/*Footer Area*/\n    .receipt-template .footer-area {\n        line-height: 1.222;\n        font-size: 10px;\n    }\n \n/*Media Query*/\n    @media print {\n        .receipt-template {\n            width: 100%;\n        }\n    }\n    @media all and (max-width: 215px) {}', '2019-05-10 12:35:05', '2019-06-08 06:49:37', 1),
(2, 'Classic', '&lt;div class=&quot;width&quot;&gt;\n&lt;table class=&quot;table&quot;&gt;\n&lt;tbody&gt;\n    &lt;tr class=&quot;invoice-header&quot;&gt;\n        &lt;td class=&quot;text-center&quot; colspan=&quot;2&quot;&gt;\n            &lt;div class=&quot;invoice-header-info&quot;&gt;\n                &lt;div class=&quot;logo&quot;&gt;{{logo}}&lt;/div&gt;\n                &lt;h2 class=&quot;invoice-title&quot;&gt;{{store_name}}&lt;/h2&gt;\n                &lt;div&gt;GST Reg: {{gst_reg}}&lt;/div&gt;\n                &lt;div&gt;VAT Reg: {{vat_reg}}&lt;/div&gt;\n                &lt;h6 class=&quot;font-size18&quot;&gt;Invoice ID: {{invoice_id}}&lt;/h6&gt;\n                &lt;h5&gt;Date: {{date_time}}&lt;/h5&gt;\n                &lt;h4&gt;&lt;strong&gt;Tax Invoice&lt;/strong&gt;&lt;/h4&gt;\n            &lt;/div&gt;\n        &lt;/td&gt;\n    &lt;/tr&gt;\n    &lt;tr class=&quot;invoice-address&quot;&gt;\n        &lt;td class=&quot;w-50 invoice-from&quot;&gt;\n            &lt;div&gt;\n                &lt;div class=&quot;com-details&quot;&gt;\n                &lt;div&gt;&lt;strong&gt; From &lt;/strong&gt;&lt;/div&gt;\n                &lt;span class=&quot;invoice-address&quot;&gt; {{store_address}} &lt;/span&gt; &lt;br /&gt;Mobile: {{store_contact}}&lt;/div&gt;\n            &lt;/div&gt;\n        &lt;/td&gt;\n        &lt;td class=&quot;text-right invoice-to w-50&quot;&gt;\n            &lt;div&gt;&lt;strong&gt; To &lt;/strong&gt;&lt;/div&gt;\n            &lt;div&gt;Customer: {{customer_name}}&lt;/div&gt;\n            &lt;div&gt;Customer Contact: {{customer_contact}}&lt;/div&gt;\n        &lt;/td&gt;\n    &lt;/tr&gt;\n&lt;/tbody&gt;\n&lt;/table&gt;\n&lt;div class=&quot;table-responsive invoice-items&quot;&gt;\n    &lt;table class=&quot;table table-bordered table-striped table-hover mb-0&quot;&gt;\n        &lt;thead&gt;\n            &lt;tr class=&quot;active&quot;&gt;\n                &lt;th class=&quot;w-5 text-center&quot;&gt;Sl.No.&lt;/th&gt;\n                &lt;th class=&quot;w-50&quot;&gt;Product Name&lt;/th&gt;\n                &lt;th class=&quot;w-10&quot;&gt;Quantity&lt;/th&gt;\n                &lt;th class=&quot;text-right w-15&quot;&gt;Price&lt;/th&gt;\n                &lt;th class=&quot;text-right w-20&quot;&gt;Total&lt;/th&gt;\n            &lt;/tr&gt;\n        &lt;/thead&gt;\n        &lt;tbody&gt;\n            &lt;tr&gt;\n                &lt;td class=&quot;text-center&quot; data-title=&quot;Sl.&quot;&gt;#1&lt;/td&gt;\n                &lt;td data-title=&quot;Product Name&quot;&gt;ajke 2&lt;/td&gt;\n                &lt;td data-title=&quot;Quantity&quot;&gt;x 1 box&lt;/td&gt;\n                &lt;td class=&quot;text-right&quot; data-title=&quot;Price&quot;&gt;120.00&lt;/td&gt;\n                &lt;td class=&quot;text-right&quot; data-title=&quot;Total&quot;&gt;120.00&lt;/td&gt;\n            &lt;/tr&gt;\n        &lt;/tbody&gt;\n    &lt;/table&gt;\n&lt;/div&gt;\n&lt;div class=&quot;table-responsive bt-1&quot;&gt;\n    &lt;table id=&quot;selling_bill&quot; class=&quot;table&quot;&gt;\n        &lt;tbody&gt;\n            &lt;tr class=&quot;active&quot;&gt;\n                &lt;td class=&quot;w-80 text-right&quot;&gt;Sub Total&lt;/td&gt;\n                &lt;td class=&quot;w-20 text-right&quot;&gt;120.00&lt;/td&gt;\n            &lt;/tr&gt;\n            &lt;tr class=&quot;active&quot;&gt;\n                &lt;td class=&quot;w-80 text-right&quot;&gt;Discount&lt;/td&gt;\n                &lt;td class=&quot;w-20 text-right&quot;&gt;0.00&lt;/td&gt;\n            &lt;/tr&gt;\n            &lt;tr class=&quot;active&quot;&gt;\n                &lt;td class=&quot;w-80 text-right&quot;&gt;Order Tax&lt;/td&gt;\n                &lt;td class=&quot;w-20 text-right&quot;&gt;0.00&lt;/td&gt;\n            &lt;/tr&gt;\n            &lt;tr class=&quot;active&quot;&gt;\n                &lt;td class=&quot;w-80 text-right&quot;&gt;Payable Amount&lt;/td&gt;\n                &lt;td class=&quot;w-20 text-right&quot;&gt;120.00&lt;/td&gt;\n            &lt;/tr&gt;\n            &lt;tr class=&quot;active&quot;&gt;\n                &lt;td class=&quot;w-80 text-right&quot;&gt;Paid&lt;/td&gt;\n                &lt;td class=&quot;w-20 text-right&quot;&gt;120.00&lt;/td&gt;\n            &lt;/tr&gt;\n            &lt;tr class=&quot;active&quot;&gt;\n                &lt;td class=&quot;w-80 text-right&quot;&gt;Due&lt;/td&gt;\n                &lt;td class=&quot;w-20 text-right&quot;&gt;0.00&lt;/td&gt;\n            &lt;/tr&gt;\n        &lt;/tbody&gt;\n    &lt;/table&gt;\n&lt;/div&gt;\n&lt;div class=&quot;table-responsive&quot;&gt;\n    &lt;table class=&quot;table table-striped&quot;&gt;\n        &lt;tbody&gt;\n            &lt;tr class=&quot;bt-1 success&quot;&gt;\n                &lt;td class=&quot;w-40 text-right&quot;&gt;&lt;small&gt;&lt;em&gt;Paid on&lt;/em&gt;&lt;/small&gt; 2019-01-30 11:47:18 (via Cash on Delivery) by Admin&lt;/td&gt;\n                &lt;td class=&quot;w-30 text-right&quot;&gt;Amount:  120.00&lt;/td&gt;\n                &lt;td class=&quot;w-30 text-right&quot;&gt; &lt;/td&gt;\n            &lt;/tr&gt;\n        &lt;/tbody&gt;\n    &lt;/table&gt;\n&lt;/div&gt;\n&lt;div class=&quot;text-center&quot;&gt;\n&lt;h5&gt;&lt;strong&gt;Tax Summary&lt;/strong&gt;&lt;/h5&gt;\n&lt;/div&gt;\n&lt;table class=&quot;table table-bordered table-striped print-table order-table table-condensed mb-0&quot;&gt;\n&lt;thead&gt;\n&lt;tr class=&quot;active&quot;&gt;\n&lt;th class=&quot;w-35 text-center&quot;&gt;Name&lt;/th&gt;\n&lt;th class=&quot;w-20 text-center&quot;&gt;Code&lt;/th&gt;\n&lt;th class=&quot;w-15 text-center&quot;&gt;Qty&lt;/th&gt;\n&lt;th class=&quot;w-15 text-right&quot;&gt;Tax Excl&lt;/th&gt;\n&lt;th class=&quot;w-15 text-right&quot;&gt;Tax Amount&lt;/th&gt;\n&lt;/tr&gt;\n&lt;/thead&gt;\n&lt;tbody&gt;\n&lt;tr&gt;\n&lt;td class=&quot;text-center&quot;&gt;Tax @20%&lt;/td&gt;\n&lt;td class=&quot;text-center&quot;&gt;TTX&lt;/td&gt;\n&lt;td class=&quot;text-center&quot;&gt;1&lt;/td&gt;\n&lt;td class=&quot;text-right&quot;&gt;120.00&lt;/td&gt;\n&lt;td class=&quot;text-right&quot;&gt;0.00&lt;/td&gt;\n&lt;/tr&gt;\n&lt;/tbody&gt;\n&lt;tfoot&gt;\n&lt;tr class=&quot;active&quot;&gt;\n&lt;th class=&quot;text-right&quot; colspan=&quot;4&quot;&gt;Total Tax Amount&lt;/th&gt;\n&lt;th class=&quot;text-right&quot;&gt;0.00&lt;/th&gt;\n&lt;/tr&gt;\n&lt;/tfoot&gt;\n&lt;/table&gt;\n&lt;p class=&quot;text-center&quot;&gt;&lt;em&gt;Thank you for choosing us!&lt;/em&gt;&lt;/p&gt;\n&lt;div class=&quot;invoice-header-info barcodes&quot;&gt;{{qrcode}}&lt;/div&gt;\n&lt;div class=&quot;table-responsive&quot;&gt;\n    &lt;table class=&quot;table&quot;&gt;\n        &lt;tbody&gt;\n            &lt;tr class=&quot;invoice-authority-cotainer&quot;&gt;\n                &lt;td class=&quot;w-50&quot;&gt;\n                    &lt;div class=&quot;invoice_authority invoice_created_by&quot;&gt;\n                        &lt;div class=&quot;name&quot;&gt;Admin&lt;/div&gt;\n                        &lt;div&gt;Created By&lt;/div&gt;\n                    &lt;/div&gt;\n                &lt;/td&gt;\n                &lt;td class=&quot;w-50&quot;&gt;\n                    &lt;div class=&quot;invoice_authority invoice_created_by pull-right&quot;&gt;\n                        &lt;div class=&quot;name&quot;&gt;Cashier&lt;/div&gt;\n                        &lt;div&gt;Cashier&lt;/div&gt;\n                    &lt;/div&gt;\n                &lt;/td&gt;\n            &lt;/tr&gt;\n        &lt;/tbody&gt;\n    &lt;/table&gt;\n&lt;/div&gt;\n&lt;/div&gt;', '.width{\n    width:550px;\n    margin:0 auto;\n}\n#invoice {\n    font-size: 90%;\n    padding: 20px 50px;\n}\n#invoice .table {\n    border: none;\n}\n#invoice .table td {\n    padding: 2px;\n    border: none;\n}\n#invoice .logo img{\n    width: 60px;\n}\n.invoice-from{\n    vertical-align: top !important;\n}\n.invoice-to{\n    vertical-align: top!important; \n}\n/* Start Cashier */\n#invoice .invoice_authority {\n    position: relative;\n    text-align: center;\n    margin-top: 50px;\n}\n#invoice .invoice_authority .name {\n    padding: 0;\n    font-weight: 700;\n    font-size: 1.2rem;\n    text-transform: uppercase;\n}\n#invoice .invoice_authority .name span {\n    display: inline-block;\n    border-bottom: 1px solid #dddddd;\n    padding: 5px;\n}\n.buy-invoice{\n    margin-bottom: 0;\n}\n.invoice-header-info.barcodes {\n    text-align: center;\n}\n.invoice-header-info.barcodes img {\n    display: inline-block;\n}\n.font-size18{\n    font-size: 18px;\n}\n\n', '2019-03-25 13:17:50', '2019-05-29 16:51:13', 1),
(3, 'Minimal', '&lt;section class=&quot;receipt-template&quot;&gt;\n\n        &lt;header class=&quot;receipt-header&quot;&gt;\n            &lt;div class=&quot;logo-area&quot;&gt;\n                &lt;img class=&quot;logo&quot; src=&quot;{{  logo_url  }}&quot;&gt;\n            &lt;/div&gt;\n            &lt;h2 class=&quot;store-name&quot;&gt;{{ store_name }}&lt;/h2&gt;\n            &lt;div class=&quot;address-area&quot;&gt;\n                &lt;span class=&quot;info address&quot;&gt;{{ store_address }}&lt;/span&gt;\n                &lt;div class=&quot;block&quot;&gt;\n                    &lt;span class=&quot;info phone&quot;&gt;Mobile: {{ store_phone }}&lt;/span&gt;, &lt;span class=&quot;info email&quot;&gt;Email: {{ store_email }}&lt;/span&gt;\n                &lt;/div&gt;\n            &lt;/div&gt;\n        &lt;/header&gt;\n        \n        &lt;div class=&quot;heading&quot;&gt;\n            &lt;h2 class=&quot;title text-center zero-around title-lg&quot;&gt;INVOICE&lt;/h2&gt;\n        &lt;/div&gt;\n        \n        &lt;section class=&quot;info-area&quot;&gt;\n            &lt;table&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-10&quot;&gt;&lt;span&gt;Bill #:&lt;/td&gt;\n                    &lt;td class=&quot;w-40 text-center sinfo billno&quot;&gt;&lt;span&gt;{{ invoice_id }}&lt;/span&gt;&lt;/td&gt;\n                    &lt;td class=&quot;w-10 text-right&quot;&gt;&lt;span&gt;Date:&lt;/td&gt;\n                    &lt;td class=&quot;w-40 text-center sinfo date&quot;&gt;&lt;span&gt;{{ date_time }}&lt;/span&gt;&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-10&quot;&gt;M/S:&lt;/td&gt;\n                    &lt;td class=&quot;w-90&quot; colspan=&quot;3&quot;&gt;&lt;span class=&quot;text-lg&quot;&gt;{{ customer_name }}&lt;/span&gt;&lt;/td&gt;\n                &lt;/tr&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;listing-area item-list&quot;&gt;\n            &lt;table&gt;\n                &lt;thead&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;w-40 text-center&quot;&gt;DESC.&lt;/td&gt;\n                        &lt;td class=&quot;w-15 text-center&quot;&gt;Qty&lt;/td&gt;\n                        &lt;td class=&quot;w-15 text-right&quot;&gt;Price&lt;/td&gt;\n                        &lt;td class=&quot;w-20 text-right&quot;&gt;AMT&lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/thead&gt;\n                &lt;tbody&gt;\n                    {{ items }}\n                        &lt;tr&gt;\n                            &lt;td&gt;{{ item_name }}&lt;/td&gt;\n                            &lt;td class=&quot;text-center&quot;&gt;{{ item_quantity }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_price }}&lt;/td&gt;\n                            &lt;td class=&quot;text-right&quot;&gt;{{ item_total }}&lt;/td&gt;   \n                        &lt;/tr&gt;\n                    {{ /items }}\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;info-area calculation-area&quot;&gt;\n            &lt;table&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Subtotal:&lt;/td&gt;\n                    &lt;td&gt;{{ subtotal }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Order Tax:&lt;/td&gt;\n                    &lt;td&gt;{{ order_tax }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Discount:&lt;/td&gt;\n                    &lt;td&gt;{{ discount_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Shipping Chrg:&lt;/td&gt;\n                    &lt;td&gt;{{ shipping_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Others Chrg:&lt;/td&gt;\n                    &lt;td&gt;{{ others_charge }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Previous Due:&lt;/td&gt;\n                    &lt;td&gt;{{ previous_due }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Amount Total:&lt;/td&gt;\n                    &lt;td&gt;{{ payable_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Amount Paid:&lt;/td&gt;\n                    &lt;td&gt;{{ paid_amount }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Due:&lt;/td&gt;\n                    &lt;td&gt;{{ due }}&lt;/td&gt;\n                &lt;/tr&gt;\n                &lt;tr&gt;\n                    &lt;td class=&quot;w-70&quot;&gt;Change:&lt;/td&gt;\n                    &lt;td&gt;{{ change }}&lt;/td&gt;\n                &lt;/tr&gt;\n            &lt;/table&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;stylish-footer text-center&quot;&gt;\n            &lt;span&gt;Printed On: {{ printed_on }}&lt;/span&gt;\n        &lt;/section&gt;\n        \n        &lt;section class=&quot;info-area align-center footer-area&quot;&gt;\n            &lt;span class=&quot;block&quot;&gt;Sold product No Claim. No Change, New product One Month Warranty.&lt;/span&gt;\n            &lt;span class=&quot;block bold&quot;&gt;Thank You for Choosing Us.&lt;/span&gt;\n        &lt;/section&gt;\n        \n &lt;/section&gt;', '/*Common CSS*/\n        .receipt-template {\n            width:302px;\n            margin:0 auto;\n        }\n        .receipt-template .text-small {\n            font-size: 10px;\n        }\n        .receipt-template .block {\n            display: block;\n        }\n        .receipt-template .inline-block {\n            display: inline-block;\n        }\n        .receipt-template .bold {\n            font-weight: 700;\n        }\n        .receipt-template .italic {\n            font-style: italic;\n        }\n        .receipt-template .align-right {\n            text-align: right;\n        }\n        .receipt-template .align-center {\n            text-align: center;\n        }\n        .receipt-template .heading {\n            position: relation;\n        }\n        .receipt-template .title {\n            font-size: 16px;\n            font-weight: 700;\n            margin: 10px 0 5px 0;\n        }\n        .receipt-template .sub-title {\n            font-size: 12px;\n            font-weight: 700;\n            margin: 10px 0 5px 0;\n        }\n        .receipt-template table {\n            width: 100%;\n        }\n        .receipt-template td,\n        .receipt-template th {\n            font-size:10px;\n        }\n        .receipt-template .info-area {\n            font-size: 12px;      \n            line-height: 1.222;  \n        }\n        .receipt-template .listing-area {\n            line-height: 1.222;\n        }\n        .receipt-template .listing-area table {}\n        .receipt-template .listing-area table thead tr {\n            border-top: 1px solid #000;\n            border-bottom: 1px solid #000;\n            font-weight: 700;\n        }\n        .receipt-template .listing-area table tbody tr {\n            border-top: 1px dashed #000;\n            border-bottom: 1px dashed #000;\n        }\n        .receipt-template .listing-area table tbody tr:last-child {\n            border-bottom: none;\n        }\n        .receipt-template .listing-area table td {\n            vertical-align: top;\n        }\n        .receipt-template .info-area table {}\n        .receipt-template .info-area table thead tr {\n            border-top: 1px solid #000;\n            border-bottom: 1px solid #000;\n        }\n\n /*Receipt Heading*/\n        .receipt-template .receipt-header {\n            text-align: center;\n        }\n        .receipt-template .receipt-header .logo-area {\n            width: 80px;\n            height: 80px;\n            margin: 0 auto;\n        }\n        .receipt-template .receipt-header .logo-area img.logo {\n            display: inline-block;\n            max-width: 100%;\n            max-height: 100%;\n        }\n        .receipt-template .receipt-header .address-area {\n            margin-bottom: 5px;\n            line-height: 1;\n        }\n        .receipt-template .receipt-header .info {\n            font-size: 12px;\n        }\n        .receipt-template .receipt-header .store-name {\n            font-size: 28px;\n            font-weight: 700;\n            margin: 0;\n            padding: 0;\n        }\n        \n        \n/*Invoice Info Area*/ \n    .receipt-template .invoice-info-area {}\n    \n/*Customer Info Area*/\n    .receipt-template .customer-area {\n        margin-top:10px;\n    }\n\n/*Calculation Area*/\n    .receipt-template .calculation-area {\n        border-top: 2px solid #000;\n    }\n    .receipt-template .calculation-area table td {\n        text-align: right;\n    }\n    .receipt-template .calculation-area table td:nth-child(2) {\n        border-bottom: 1px dashed #000;\n    }\n\n/*Item Listing*/\n    .receipt-template .item-list table tr {\n    }\n    \n/*Barcode Area*/\n    .receipt-template .barcode-area {\n        margin-top: 10px;\n        text-align: center;\n    }\n    .receipt-template .barcode-area img {\n        max-width: 100%;\n        display: inline-block;\n    }\n    \n/*Footer Area*/\n    .receipt-template .footer-area {\n        line-height: 1.222;\n        font-size: 10px;\n    }\n \n/*Media Query*/\n    @media print {\n        .receipt-template {\n            width: 100%;\n        }\n    }\n    @media all and (max-width: 215px) {}\n    \n    \n/* Additional */\n        .receipt-template .zero-around {\n            margin:0;\n            padding: 0;\n        }\n        .receipt-template .title-lg {\n            font-size: 18px!important;\n            margin-bottom: 5px;\n         }\n         .receipt-template .text-lg {\n             font-size: 18px;\n             font-weight: 700;\n         }\n         .receipt-template .info-area td {\n             vertical-align: center;\n         }\n         .receipt-template .info-area td.sinfo {\n             padding: 1px!important;\n         }\n         .receipt-template .info-area td.sinfo span {\n             display: block;\n             font-weight: 700;\n             border: 1px solid #000;\n             padding: 2px;\n         }\n         .receipt-template .listing-area td, .receipt-template .listing-area th, .receipt-template .calculation-area table td {\n             font-size: 13px;\n             font-weight: 700;\n         }\n         .receipt-template .item-list table thead td {\n             text-align: center;\n             padding: 3px;\n             border: 2px solid #000;\n          }\n          .receipt-template .stylish-footer {\n              margin: 10px 0 5px 0;\n          }\n          .receipt-template .stylish-footer span {\n              display: inline-block;\n              font-size: 12px;\n              border-top: 1px dashed #000;\n              border-bottom: 1px dashed #000; \n          }\n', '2019-05-14 19:02:11', '2019-06-04 09:37:24', 1);

--
-- Dumping data for table `pos_template_to_store`
--

INSERT INTO `pos_template_to_store` (`pt2s`, `store_id`, `ttemplate_id`, `is_active`, `status`, `sort_order`) VALUES
(1, 1, 1, 1, 1, 1),
(2, 1, 2, 1, 0, 3),
(3, 1, 3, 1, 1, 2);

--
-- Dumping data for table `expense_categorys`
--

TRUNCATE TABLE `expense_categorys`;

INSERT INTO `expense_categorys` (`category_name`, `category_slug`, `parent_id`, `category_details`, `sell_return`, `sell_delete`, `loan_delete`, `loan_payment`, `giftcard_sell_delete`, `topup_delete`, `product_purchase`, `stock_transfer`, `due_paid`, `status`, `is_hide`, `sort_order`, `created_at`, `updated_at`) VALUES
('Employee Salary', 'employee_salary', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:21:53', '2019-02-16 17:21:53'),
('Showroom Rent', 'showroom_rent', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:22:13', '2019-02-16 17:22:13'),
('Electricity Bill + Dish', 'electricity_bill_+_dish', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:22:29', '2019-02-16 17:22:29'),
('Advertisements', 'advertisements', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:23:16', '2019-02-16 17:23:16'),
('Bonous', 'bonous', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:23:52', '2019-02-16 17:23:52'),
('Others', 'others', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-02-16 17:24:23', '2019-02-16 17:24:23'),
('Returnable', 'returnable', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, '2019-03-02 05:58:46', '2019-03-02 05:58:46'),
('Sell Return', 'sell_return', 0, '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, '2019-03-06 07:35:14', '2019-04-29 01:57:52'),
('Product Purchase', 'product_purchase', 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, '2019-03-06 07:35:38', '2019-04-29 01:56:37'),
('Sell Delete', 'sell_delete', 0, '', 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, '2019-03-06 09:54:14', '2019-04-29 01:56:41'),
('Loan Delete', 'loan_delete', 0, '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, '2019-03-06 07:02:41', '2019-04-29 01:56:43'),
('Giftcard Topup Delete', 'giftcard_topup_delete', 0, '', 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 1, 0, '2019-03-07 12:54:16', '2019-04-29 01:56:46'),
('Giftcard Sell Delete', 'giftcard_sell_delete', 0, '', 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 1, 0, '2019-03-07 13:03:24', '2019-04-29 01:56:48'),
('Loan Payment', 'loan_payment', 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 1, 0, '2019-03-08 06:43:35', '2019-04-29 01:56:51'),
('Due Paid to Supplier', 'due_paid_to_supplier', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, '2019-03-08 06:43:35', '2019-04-29 01:56:52');