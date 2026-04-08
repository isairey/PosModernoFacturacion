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
ALTER TABLE `selling_item` ADD `purchase_invoice_id` varchar(100) DEFAULT NULL;
ALTER TABLE `selling_item` ADD `item_purchase_price` decimal(25,4) NOT NULL DEFAULT '0.0000';
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

ALTER TABLE `suppliers` ADD `code_name` varchar(55) DEFAULT NULL;
ALTER TABLE `suppliers` ADD `gtin` varchar(100) DEFAULT NULL;
ALTER TABLE `units` ADD `code_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE `users` ADD `dob` date DEFAULT NULL;
ALTER TABLE `users` ADD `sex` varchar(10) NOT NULL DEFAULT 'M';
ALTER TABLE `users` ADD `login_try` tinyint(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `users` ADD `last_login` datetime DEFAULT NULL;
ALTER TABLE `users` ADD `address` text;
ALTER TABLE `users` ADD `user_image` varchar(250) DEFAULT NULL;


ALTER TABLE `bank_transaction_info` ADD `is_hide`  TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `categorys` ADD `category_image` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `dob` DATE NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `gtin`VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `password` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `raw_password` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `customers` ADD `updated_at` DATETIME on update CURRENT_TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE `customer_to_store` ADD `due` DECIMAL(25,4) UNSIGNED NOT NULL DEFAULT '0.0000';
ALTER TABLE `customer_transactions` ADD `balance` DECIMAL(25,4) NULL DEFAULT '0.0000';

ALTER TABLE `expenses` ADD `returnable` ENUM('no','yes') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'no';
ALTER TABLE `expenses` ADD `attachment` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `expense_categorys` ADD `sell_return` TINYINT(1) NOT NULL DEFAULT '0', ADD `sell_delete` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `expense_categorys` ADD `loan_delete` TINYINT(1) NOT NULL DEFAULT '0', ADD `loan_payment` TINYINT(1) NOT NULL DEFAULT '0', ADD `giftcard_sell_delete` TINYINT(1) NOT NULL DEFAULT '0', ADD `topup_delete` TINYINT(1) NOT NULL DEFAULT '0', ADD `product_purchase` TINYINT(1) NOT NULL DEFAULT '0', ADD `stock_transfer` TINYINT(1) NOT NULL DEFAULT '0', ADD `due_paid` TINYINT(1) NOT NULL, ADD `is_hide` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `gift_cards` CHANGE `date` `date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `loans` ADD `status` TINYINT(1) NOT NULL DEFAULT '1', ADD `sort_order` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `payments` ADD `is_profit` TINYINT(1) NOT NULL DEFAULT '1', ADD `is_hide` TINYINT(1) NOT NULL DEFAULT '0', ADD `capital`  DECIMAL(25,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `pmethods` ADD `code_name`  VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `products` ADD `p_type`  ENUM('standard','service') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'standard', ADD `barcode_symbology`  VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `product_to_store` ADD `purchase_price` FLOAT NOT NULL DEFAULT '0', ADD `brand_id` INT(10) NULL DEFAULT NULL;
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
	CHANGE `quantity` `item_quanity` decimal(15,4) NOT NULL;

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

ALTER TABLE `purchase_info` CHANGE `updated_at` `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
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

-- -----------------------------------------
-- DELETE INDEX
-- -----------------------------------------

ALTER TABLE `returns` DROP INDEX `id`;
ALTER TABLE `transfers` DROP INDEX `id`;

-- -----------------------------------------
-- ADD TABLES
-- -----------------------------------------

CREATE TABLE `brands` (
  `brand_id` int(10) UNSIGNED NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `code_name` varchar(100) NOT NULL,
  `brand_details` longtext,
  `brand_image` varchar(250) DEFAULT NULL,
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
  `invoice_note` text,
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
  `note` text,
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
  `lang_value` text
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
  `note` text,
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
  `template_css` longtext,
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
  `description` text NOT NULL,
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
  ADD KEY `reference_no_2` (`reference_no`),
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
  `store_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `customer_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `customer_mobile` varchar(20) DEFAULT NULL,
  `status` enum('sent','pending','complete','') NOT NULL DEFAULT 'sent',
  `payment_status` varchar(20) DEFAULT NULL,
  `quotation_note` text,
  `is_installment` tinyint(1) NOT NULL DEFAULT '0',
  `total_items` int(10) NOT NULL DEFAULT '0',
  `address` text,
  `pmethod_details` text,
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
  `description` text NOT NULL,
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

-- -------------------------------------------

--
-- Table structure for table `shortcut_links`
--

CREATE TABLE `shortcut_links` (
  `id` int(11) NOT NULL,
  `type` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `href` text NOT NULL,
  `target` varchar(100) NOT NULL DEFAULT '_self',
  `title` varchar(255) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `thumbnail` text CHARACTER SET utf8,
  `permission_slug` varchar(50) DEFAULT NULL,
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

INSERT INTO `shortcut_links` (`id`, `type`, `href`, `target`, `title`, `icon`, `thumbnail`, `permission_slug`, `created_at`, `updated_at`) VALUES
(1, 'report', '/admin/report_overview.php', '', 'Overview Report', 'fa-link', '', 'read_overview_report', '2019-02-03 12:00:00', '2019-04-26 12:19:05'),
(2, 'report', '/admin/report_collection.php', '', 'Collection Report', 'fa-link', '', 'read_collection_report', '2019-02-03 12:00:00', '2019-04-26 12:19:14'),
(3, 'report', '/admin/report_customer_due_collection.php', '', 'Due Collection Report', 'fa-link', '', 'read_customer_due_collection_report', '2019-02-03 12:00:00', '2019-04-26 12:19:28'),
(4, 'report', '/admin/report_supplier_due_paid.php', '', 'Supplier Due Paid Report', 'fa-link', '', 'read_supplier_due_paid_report', '2019-02-03 12:00:00', '2019-04-26 12:19:35'),
(5, 'report', '/admin/report_sell_itemwise.php', '', 'Sell Report', 'fa-link', '', 'read_sell_report', '2019-02-03 12:00:00', '2019-04-26 12:20:07'),
(6, 'report', '/admin/report_purchase_supplierwise.php', '', 'Purchase Report', 'fa-link', '', 'read_purchase_report', '2019-02-03 12:00:00', '2019-04-26 12:20:22'),
(7, 'report', '/admin/report_sell_payment.php', '', 'Sell Payment Report', 'fa-link', '', 'read_sell_payment_report', '2019-02-03 12:00:00', '2019-04-26 12:20:34'),
(8, 'report', '/admin/report_purchase_payment.php', '', 'Purchase Payment Report', 'fa-link', '', 'read_purchase_payment_report', '2019-02-03 12:00:00', '2019-04-26 12:20:41'),
(9, 'report', '/admin/report_sell_tax.php', '', 'Sell Tax Report', 'fa-link', '', 'read_sell_tax_report', '2019-02-03 12:00:00', '2019-04-26 12:20:46'),
(10, 'report', '/admin/report_purchase_tax.php', '', 'Purchase Tax Report', 'fa-link', '', 'read_purchase_tax_report', '2019-02-03 12:00:00', '2019-04-26 12:20:58'),
(11, 'report', '/admin/report_tax_overview.php', '', 'Tax Overview Report', 'fa-link', '', 'read_tax_overview_report', '2019-02-03 12:00:00', '2019-04-26 12:21:04'),
(12, 'report', '/admin/report_stock.php', '', 'Stock Report', 'fa-link', '', 'read_stock_report', '2019-02-03 12:00:00', '2019-04-26 12:21:14'),
(13, 'report', '/admin/bank_transactions.php', '', 'Bank Transaction', 'fa-file', '', 'read_bank_transactions', '2019-02-03 12:00:00', '2019-04-26 12:21:36'),
(14, 'report', '/admin/bank_account_sheet.php', '', 'Balance Sheet', 'fa-file', '', 'read_bank_account_sheet', '2019-02-03 12:00:00', '2019-04-26 12:21:44'),
(15, 'report', '/admin/income_monthwise.php', '', 'Income Monthwise Report', 'fa-file', '', 'read_income_monthwise', '2019-02-03 12:00:00', '2019-04-26 12:21:54'),
(16, 'report', '/admin/report_income_and_expense.php', '', 'Income & Expense Report', 'fa-file', '', 'read_income_and_expense_report', '2019-02-03 12:00:00', '2019-04-26 12:22:05'),
(17, 'report', '/admin/report_profit_and_loss.php', '', 'Profit & Loss Report', 'fa-file', '', 'read_profit_and_loss_report', '2019-02-03 12:00:00', '2019-04-26 12:22:15'),
(18, 'report', '/admin/report_cashbook.php', '', 'Cashbook', 'fa-file', '', 'read_cashbook_report', '2019-02-03 12:00:00', '2019-04-26 12:22:25');