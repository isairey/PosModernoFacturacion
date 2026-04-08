<?php
function  get_invoices($type, $store_id = null, $limit = 100000)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getInvoices($type, $store_id, $limit);
}

function  get_due_invoices($customer_id, $store_id = null)
{
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getDueInvoices($customer_id, $store_id);
}

function get_the_invoice($invoice_id)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getInvoiceInfo($invoice_id);
}

function get_invoice_items($invoice_id, $store_id = null)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getInvoiceItems($invoice_id, $store_id);
}

function get_invoice_items_html($invoice_id, $store_id = null)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getInvoiceItemsHTML($invoice_id, $store_id);
}

function get_invoice_due_amount($invoice_id)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getDueAmount($invoice_id);
}

function get_invoice_last_edited_versiont_id($invoice_id)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getInvoiceLastEditedVersionId($invoice_id);
}

function get_invoice_due_paid_amount($invoice_id)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getDuePaidAmount($invoice_id);
}

function get_invoice_due_paid_discount_amount($invoice_id)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getDuePaidDiscountAmount($invoice_id);
}

function get_invoice_due_paid_amount_rows($invoice_id)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->getDuePaidAmountRows($invoice_id);
}

function total_holding_order_today($store_id = null)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->totalHoldingOrderToday($store_id);
}

function total_holdeing_invoice($from = null, $to = null, $store_id = null)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->totalHoldingOrder($from, $to, $store_id);
}

function total_invoice_today($store_id = null)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->totalToday($store_id);
}

function total_invoice($from = null, $to = null, $store_id = null)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->total($from, $to, $store_id);
}

function unique_invoice_id()
{ 
    $statement = db()->prepare("SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE `table_name` = 'selling_info'");
    $statement->execute(array());
    $invoice_id = $statement->fetch(PDO::FETCH_ASSOC)["auto_increment"];
    $invoice_id += 100000; 
    return $invoice_id;
}

function unique_transaction_ref_no($type='deposit')
{
    if ($type=='deposit') {
        $prefix = 'D';
    } elseif ($type=='withdraw') {
        $prefix = 'W';
    } else {
        $prefix = 'OT';
    }
    
    $statement = db()->prepare("SELECT `info_id` as `total` FROM `bank_transaction_info`");
    $statement->execute(array());
    $inc = (int)$statement->rowCount() + 1; 
    return $prefix.$inc;
}

function get_invoice_prefix($type='sell')
{
    global $invoice_init_prefix;
    return isset($invoice_init_prefix[$type]) ? $invoice_init_prefix[$type] : 'P';
}

function get_reference_format($sequence)
{
    $format = get_preference('reference_format');
    switch ($format) {
        case 'year_sequence':
            return date('Y').'/'.$sequence;
            break;
        case 'year_month_sequence':
            return date('Y/m').'/'.$sequence;
            break;
        case 'sequence':
            return $sequence;
            break;
        case 'random':
            return unique_id(8);
            break;
        default:
            return $sequence;
            break;
    }
}

function generate_invoice_id($type = 'sell', $invoice_id = null)
{
    $store_id = store_id();
    $prefix = get_preference('sales_reference_prefix') ? get_preference('sales_reference_prefix').'/' : '';
    $invoice_model = registry()->get('loader')->model('invoice');
    if (!$invoice_id) {
        $last_invoice = $invoice_model->getLastInvoice($type);
        $invoice_id = isset($last_invoice['invoice_id']) ? $last_invoice['invoice_id'] : '1';
    }
    if ($invoice_model->hasInvoice($invoice_id)) {
        $invoice_id = str_replace(array('A','B','C','D','E','F','G','H','I','G','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'), '', $invoice_id);
        $sequence = (int)(substr($invoice_id,-8)) + 1;
        $temp_invoice_id = $sequence;
        $zero_length = 8 - strlen($temp_invoice_id);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $sequence = $zeros.$sequence;
        $sequence_format = get_reference_format($sequence);
        $invoice_id = $prefix.$store_id.$sequence_format;
        generate_invoice_id($type, $invoice_id);
    } else {
        $zero_length = 8 - strlen($invoice_id);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $sequence = $zeros.'1';
        $sequence_format = get_reference_format($sequence);
        $invoice_id = $prefix.$store_id.$sequence_format;
    }
    return $invoice_id;
}

function generate_customer_transacton_ref_no($type = 'purchase', $reference_no = null)
{  
    $store_id = store_id();
    $prfix = 'CT';
    
    $invoice_model = registry()->get('loader')->model('invoice');
    if (!$reference_no) {

        $store_id = $store_id ? $store_id : store_id();
        $statemtnt = registry()->get('db')->prepare("SELECT * FROM `customer_transactions` WHERE `store_id` = ? AND `type` = ? ORDER BY `id` DESC");
        $statemtnt->execute(array($store_id, $type));
        $last_transaction = $statemtnt->fetch(PDO::FETCH_ASSOC);

        $reference_no = isset($last_transaction['reference_no']) ? $last_transaction['reference_no'] : date('y').date('m').date('d').'1';
    }
    $statement = registry()->get('db')->prepare("SELECT * FROM `customer_transactions` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));
    $invoice = $statement->fetch(PDO::FETCH_ASSOC);

    if (isset($invoice['reference_no'])) {
        $reference_no = str_replace(array('A','B','C','D','E','F','G','H','I','G','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'), '', $reference_no);
        $reference_no = (int)(substr($reference_no,-4)) + 1;
        $temp_reference_no = $prfix.date('y').date('m').date('d').$reference_no;
        $zero_length = 11 - strlen($temp_reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.$reference_no;
        generate_customer_transacton_ref_no($type, $reference_no);
    } else {
        $zero_length = 11 - strlen($reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.'1';
    }
    return $reference_no;
}

function generate_purchase_log_ref_no($type = 'purchase', $reference_no = null)
{
    $store_id = store_id();
    $prfix = 'CT';
    
    $invoice_model = registry()->get('loader')->model('invoice');
    if (!$reference_no) {

        $store_id = $store_id ? $store_id : store_id();
        $statemtnt = registry()->get('db')->prepare("SELECT * FROM `purchase_logs` WHERE `store_id` = ? AND `type` = ? ORDER BY `id` DESC");
        $statemtnt->execute(array($store_id, $type));
        $last_transaction = $statemtnt->fetch(PDO::FETCH_ASSOC);

        $reference_no = isset($last_transaction['reference_no']) ? $last_transaction['reference_no'] : date('y').date('m').date('d').'1';
    }
    $statement = registry()->get('db')->prepare("SELECT * FROM `purchase_logs` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));
    $invoice = $statement->fetch(PDO::FETCH_ASSOC);

    if (isset($invoice['reference_no'])) {
        $reference_no = str_replace(array('A','B','C','D','E','F','G','H','I','G','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'), '', $reference_no);
        $reference_no = (int)(substr($reference_no,-4)) + 1;
        $temp_reference_no = $prfix.date('y').date('m').date('d').$reference_no;
        $zero_length = 11 - strlen($temp_reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.$reference_no;
        generate_purchase_log_ref_no($type, $reference_no);
    } else {
        $zero_length = 11 - strlen($reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.'1';
    }
    return $reference_no;
}

function generate_sell_log_ref_no($type = 'sell', $reference_no = null)
{
    $store_id = store_id();
    $prfix = 'CT';
    
    $invoice_model = registry()->get('loader')->model('invoice');
    if (!$reference_no) {

        $store_id = $store_id ? $store_id : store_id();
        $statemtnt = registry()->get('db')->prepare("SELECT * FROM `sell_logs` WHERE `store_id` = ? AND `type` = ? ORDER BY `id` DESC");
        $statemtnt->execute(array($store_id, $type));
        $last_transaction = $statemtnt->fetch(PDO::FETCH_ASSOC);

        $reference_no = isset($last_transaction['reference_no']) ? $last_transaction['reference_no'] : date('y').date('m').date('d').'1';
    }
    $statement = registry()->get('db')->prepare("SELECT * FROM `sell_logs` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));
    $invoice = $statement->fetch(PDO::FETCH_ASSOC);

    if (isset($invoice['reference_no'])) {
        $reference_no = str_replace(array('A','B','C','D','E','F','G','H','I','G','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'), '', $reference_no);
        $reference_no = (int)(substr($reference_no,-4)) + 1;
        $temp_reference_no = $prfix.date('y').date('m').date('d').$reference_no;
        $zero_length = 11 - strlen($temp_reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.$reference_no;
        generate_sell_log_ref_no($type, $reference_no);
    } else {
        $zero_length = 11 - strlen($reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.'1';
    }
    return $reference_no;
}

function generate_return_reference_no($reference_no = null)
{ 
    $store_id = store_id();
    $prfix = 'R';
    
    $invoice_model = registry()->get('loader')->model('invoice');
    if (!$reference_no) {

        $store_id = $store_id ? $store_id : store_id();
        $statemtnt = registry()->get('db')->prepare("SELECT * FROM `returns` WHERE `store_id` = ?  ORDER BY `id` DESC");
        $statemtnt->execute(array($store_id));
        $last_transaction = $statemtnt->fetch(PDO::FETCH_ASSOC);

        $reference_no = isset($last_transaction['reference_no']) ? $last_transaction['reference_no'] : date('y').date('m').date('d').'1';
    }
    $statement = registry()->get('db')->prepare("SELECT * FROM `returns` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));
    $invoice = $statement->fetch(PDO::FETCH_ASSOC);

    if (isset($invoice['reference_no'])) {
        $reference_no = str_replace(array('A','B','C','D','E','F','G','H','I','G','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'), '', $reference_no);
        $reference_no = (int)(substr($reference_no,-4)) + 1;
        $temp_reference_no = $prfix.date('y').date('m').date('d').$reference_no;
        $zero_length = 11 - strlen($temp_reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.$reference_no;
        generate_return_reference_no($reference_no);
    } else {
        $zero_length = 11 - strlen($reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.'1';
    }
    return $reference_no;
}

function generate_purchase_return_reference_no($reference_no = null)
{
    $store_id = store_id();
    $prfix = 'R';
    
    if (!$reference_no) {

        $store_id = $store_id ? $store_id : store_id();
        $statemtnt = registry()->get('db')->prepare("SELECT * FROM `purchase_returns` WHERE `store_id` = ?  ORDER BY `id` DESC");
        $statemtnt->execute(array($store_id));
        $last_transaction = $statemtnt->fetch(PDO::FETCH_ASSOC);

        $reference_no = isset($last_transaction['reference_no']) ? $last_transaction['reference_no'] : date('y').date('m').date('d').'1';
    }
    $statement = registry()->get('db')->prepare("SELECT * FROM `purchase_returns` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));
    $invoice = $statement->fetch(PDO::FETCH_ASSOC);

    if (isset($invoice['reference_no'])) {
        $reference_no = str_replace(array('A','B','C','D','E','F','G','H','I','G','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'), '', $reference_no);
        $reference_no = (int)(substr($reference_no,-4)) + 1;
        $temp_reference_no = $prfix.date('y').date('m').date('d').$reference_no;
        $zero_length = 11 - strlen($temp_reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.$reference_no;
        generate_purchase_return_reference_no($reference_no);
    } else {
        $zero_length = 11 - strlen($reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.'1';
    }
    return $reference_no;
}

function generate_transfer_reference_no($reference_no = null)
{    
    $store_id = store_id();
    $prfix = 'R';
    
    $invoice_model = registry()->get('loader')->model('invoice');
    if (!$reference_no) {

        $store_id = $store_id ? $store_id : store_id();
        $statemtnt = registry()->get('db')->prepare("SELECT * FROM `returns` WHERE `store_id` = ?  ORDER BY `id` DESC");
        $statemtnt->execute(array($store_id));
        $last_transaction = $statemtnt->fetch(PDO::FETCH_ASSOC);

        $reference_no = isset($last_transaction['reference_no']) ? $last_transaction['reference_no'] : date('y').date('m').date('d').'1';
    }
    $statement = registry()->get('db')->prepare("SELECT * FROM `returns` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));
    $invoice = $statement->fetch(PDO::FETCH_ASSOC);

    if (isset($invoice['reference_no'])) {
        $reference_no = str_replace(array('A','B','C','D','E','F','G','H','I','G','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'), '', $reference_no);
        $reference_no = (int)(substr($reference_no,-4)) + 1;
        $temp_reference_no = $prfix.date('y').date('m').date('d').$reference_no;
        $zero_length = 11 - strlen($temp_reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.$reference_no;
        generate_transfer_reference_no($reference_no);
    } else {
        $zero_length = 11 - strlen($reference_no);
        $zeros = '';
        for ($i=0; $i < $zero_length; $i++) { 
            $zeros .= '0';
        }
        $reference_no = $prfix.date('y').date('m').date('d').store_id().$zeros.'1';
    }
    return $reference_no;
}

function total_trash_invoice($from, $to)
{    
    $invoice_model = registry()->get('loader')->model('invoice');
    return $invoice_model->totalTrash($from, $to);
}

function invoice_edit_lifespan()
{
    $lifespan = get_preference('invoice_edit_lifespan');
    $lifespan_unit = get_preference('invoice_edit_lifespan_unit');
    switch ($lifespan_unit) {
        case 'minute':
            $lifespan =  time() - ($lifespan * 60);
            break;
        case 'second':
                $lifespan =  time() - $lifespan;
            break;
        default:
            $lifespan = time()-(60*60*24);
            break;
    }
    return $lifespan;
}

function invoice_delete_lifespan()
{
    $lifespan = get_preference('invoice_delete_lifespan');
    $lifespan_unit = get_preference('invoice_delete_lifespan_unit');
    switch ($lifespan_unit) {
        case 'minute':
            $lifespan =  time() - ($lifespan * 60);
            break;
        case 'second':
                $lifespan =  time() - $lifespan;
            break;
        default:
            $lifespan = time()-(60*60*24);
            break;
    }
    return $lifespan;
}

function get_postemplate_data($invoice_id)
{
    $invoice_model = registry()->get('loader')->model('invoice');
    $return_model = registry()->get('loader')->model('sellreturn');
    $payment_model = registry()->get('loader')->model('payment');

    $invoice_info = $invoice_model->getInvoiceInfo($invoice_id);
    $invoice_items = $invoice_model->getInvoiceItems($invoice_id);
    $return_items = $return_model->getInvoiceItems($invoice_id);
    $payments = $payment_model->getPayments($invoice_id);
    $taxes = $invoice_model->getInvoiceItemTaxes($invoice_id);

    $customer_name = get_the_customer($invoice_info['customer_id'],'customer_name');
    if ($invoice_info['customer_mobile']) {
      $customer_contact = $invoice_info['customer_mobile'];
    } else  {
      $customer_contact = $invoice_info['mobile_number'] ? $invoice_info['mobile_number'] : $invoice_info['customer_email'];
    }
    // Qrcode
    $qrcode_text = 'InvoiceID: ' . $invoice_id . ', Name: ' .$customer_name;
    include(DIR_VENDOR.'/phpqrcode/qrlib.php');
    QRcode::png($qrcode_text, ROOT.'/storage/qrcode.png', 'L', 3, 1);

    // Barcode
    $generator = barcode_generator();
    $barcode_generator = barcode_generator();
    $symbology = barcode_symbology($generator, 'code_39');

    $data = array(
      'logo' => '<img src="'.root_url().'/assets/itsolution24/img/logo-favicons/'.store('logo').'">',
      'logo_url' => root_url().'/assets/itsolution24/img/logo-favicons/'.store('logo'),
      'store_name' => store('name'),
      'store_address' => store('address'),
      'store_phone' => store('mobile'),
      'store_email' => store('email'),
      'store_contact' => store('mobile'),
      'gst_reg' => get_preference('gst_reg_no'),
      'vat_reg' => store('vat_reg_no'),
      'invoice_id' => $invoice_id,
      'date' => date('Y-m-d', strtotime($invoice_info['created_at'])),
      'time' => date('H:i:s', strtotime($invoice_info['created_at'])),
      'date_time' => format_date($invoice_info['created_at']),
      'customer_name' => $customer_name,
      'customer_address' => '',
      'customer_phone' => $invoice_info['customer_mobile'] ? $invoice_info['customer_mobile'] : $invoice_info['mobile_number'],
      'customer_email' => $invoice_info['customer_email'],
      'customer_contact' => $customer_contact,
      'gtin' => get_the_customer($invoice_info['customer_id'],'gtin'),
      'total_items' => $invoice_info['total_items'],
      'is_installment' => $invoice_info['is_installment'] ? 'Yes' : 'No',
      'payment_status' => ucfirst($invoice_info['payment_status']),
      'checkout_status' => $invoice_info['checkout_status'] ? 'Yes' : 'No',
      'created_by' => get_the_user($invoice_info['created_by'],'username'),
      'invoice_note' => $invoice_info['invoice_note'],
      'footer_text' => get_preference('invoice_footer_text'),

      'subtotal' => currency_format($invoice_info['subtotal'] - $invoice_info['item_tax']),
      'discount_type' => ucfirst($invoice_info['discount_type']),
      'discount_amount' => currency_format($invoice_info['discount_amount']),
      'interest_amount' => currency_format($invoice_info['interest_amount']),
      'interest_percentage' => currency_format($invoice_info['interest_percentage']),
      'item_tax' => currency_format($invoice_info['item_tax']),
      'order_tax' => currency_format($invoice_info['order_tax']),
      'total_tax' => currency_format($invoice_info['item_tax']+$invoice_info['order_tax']),
      'cgst' => currency_format($invoice_info['cgst']),
      'sgst' => currency_format($invoice_info['sgst']),
      'igst' => currency_format($invoice_info['igst']),
      'total_purchase_price' => currency_format($invoice_info['total_purchase_price']),
      'shipping_type' => '',
      'shipping_amount' => currency_format($invoice_info['shipping_amount']),
      'others_charge' => currency_format($invoice_info['others_charge']),
      'payable_amount' => currency_format($invoice_info['payable_amount']+$invoice_info['previous_due']),
      'paid_amount' => currency_format($invoice_info['paid_amount']+$invoice_info['prev_due_paid']+($invoice_info['balance']-$invoice_info['return_amount'])),
      'due' => currency_format(($invoice_info['due']+$invoice_info['previous_due'])-$invoice_info['prev_due_paid']),
      'due_paid' => currency_format($invoice_info['due_paid']),
      'previous_due' => currency_format($invoice_info['previous_due']),
      'prev_due_paid' => currency_format($invoice_info['prev_due_paid']),
      'profit' => currency_format($invoice_info['profit']),
      'return_amount' => currency_format($invoice_info['return_amount']),
      'change' => currency_format($invoice_info['balance']),
      'amount_in_text' => convert_number_to_word($invoice_info['payable_amount']),

      'qrcode' => '<img src="../storage/qrcode.png" alt="" class="img-responsive" style="display:inline-block" width="50" height="50">',
      'qrcode_url' => '../storage/qrcode.png',
      'barcode' => '<img class="bcimg" src="data:image/png;base64,'.base64_encode($generator->getBarcode($invoice_id, $symbology, 1)).'" height="20">',
      'barcode_url' => 'data:image/png;base64,'.base64_encode($generator->getBarcode($invoice_id, $symbology, 1)),

      'cashier_name' => '',
      'printed_on' => format_date(date_time()),
      'invoice_view' => get_preference('invoice_view'),
    );

    $item_array = array();
    $inc = 0;
    foreach ($invoice_items as $item) {
      $item['sl'] = $inc+1;
      $new_item = array(); 
      foreach ($item as $key => $val) {
        if (in_array($key, array('sl'))) {
          $new_item[$key] = $val;
        } else if(in_array($key, array('item_price'))) {
            $new_item[$key] = currency_format($val - $item['item_tax']);
        } else if(in_array($key, array('item_total'))) {
            $new_item[$key] = currency_format($val - ($item['item_tax']) * $item['item_quantity']);
        } else {
          $new_item[$key] = currency_format($val);
        }
      }
      $new_item['hsn_code'] = get_the_product($item['item_id'],'hsn_code');
      $item_array[] = $new_item;
      $inc++;
    }
    $data['items'] = $item_array;

    $item_array = array();
    $inc = 0;
    foreach ($return_items as $item) {
      $item['sl'] = $inc+1;
      $new_item = array(); 
      foreach ($item as $key => $val) {
        if (in_array($key, array('sl'))) {
          $new_item[$key] = $val;
        } elseif ($key == 'created_at') {
            $new_item[$key] = format_date($val);
        } else {
          $new_item[$key] = currency_format($val);
        } 
      }
      $item_array[] = $new_item;
      $inc++;
    }
    $data['return_items'] = $item_array;

    $payment_array = array();
    $inc = 0;
    foreach ($payments as $payment) {
      $payment['sl'] = $inc+1;
      $new_payment = array(); 
      foreach ($payment as $key => $val) {
        if (in_array($key, array('sl'))) {
          $new_payment[$key] = $val;
        } elseif ($key == 'amount') {
            $new_payment[$key] = $payment['total_paid'] > 0 ? currency_format($payment['total_paid']) : currency_format($payment['amount']);
        } elseif ($key == 'name') {
            $new_payment[$key] = $payment['name'] ? $payment['name'] : ucfirst($payment['type']);
        } elseif ($key == 'details') {
            $o = '<b>'.$payment['name'].'</b>';
            $details = unserialize($payment['details']);
            if (!empty($details)) {
                $o .= '<ul>';
                foreach ($details as $k => $val) {
                  $o .= '<li>'. str_replace('_',' ', strtoupper($k)) . ' = '.$val.'</li>';
                }
                $o .= '</ul>';
            }
            $new_payment[$key] = $o;
        } elseif ($key == 'created_by') {
            $new_payment[$key] = get_the_user($val,'username');
        } elseif ($key == 'created_at') {
            $new_payment[$key] = format_date($val);
        } else {
          $new_payment[$key] = currency_format($val);
        }
      }
      $payment_array[] = $new_payment;
      $inc++;
    }
    $data['payments'] = $payment_array;


    $tax_array = array();
    $inc = 0;
    foreach ($taxes as $tax) {
      $tax['sl'] = $inc+1;
      $new_tax = array(); 
      foreach ($tax as $key => $val) {
        if (in_array($key, array('sl'))) {
          $new_tax[$key] = $val;
        } else {
          $new_tax[$key] = currency_format($val);
        }
      }
      $tax_array[] = $new_tax;
      $inc++;
    }
    $data['taxes'] = $tax_array;
    return $data;
}

function get_postemplate_empty_data()
{
    $data = array(
      'logo' => '<img src="'.root_url().'/assets/itsolution24/img/logo-favicons/'.store('logo').'">',
      'logo_url' => root_url().'/assets/itsolution24/img/logo-favicons/'.store('logo'),
      'store_name' => store('name'),
      'store_address' => store('address'),
      'store_phone' => store('mobile'),
      'store_email' => store('email'),
      'store_contact' => store('mobile'),
      'gst_reg' => get_preference('gst_reg_no'),
      'vat_reg' => store('vat_reg_no'),
      'invoice_id' => 'INV1234565677',
      'date' => date('Y-m-d', strtotime(date_time())),
      'time' => date('H:i:s', strtotime(date_time())),
      'date_time' => format_date(date_time()),
      'customer_name' => 'Customer Name Here',
      'customer_address' => '',
      'customer_phone' => '+8801337476533',
      'customer_email' => 'customer@email.com',
      'customer_contact' => '01336456533',
      'gtin' => '123456',
      'total_items' => 10,
      'is_installment' => 'No',
      'payment_status' => 'Due',
      'checkout_status' => 'Yes',
      'created_by' => 'Creator Name Here',
      'invoice_note' => 'This is invoice note',
      'footer_text' => 'This is a footer note',

      'subtotal' => currency_format(600),
      'discount_type' => 'Percentage',
      'discount_amount' => currency_format(10),
      'interest_amount' => currency_format(40),
      'interest_percentage' => currency_format(10),
      'item_tax' => currency_format(30),
      'order_tax' => currency_format(100),
      'total_tax' => currency_format(20),
      'cgst' => currency_format(30),
      'sgst' => currency_format(10),
      'igst' => currency_format(18),
      'total_purchase_price' => currency_format(500),
      'shipping_type' => '',
      'shipping_amount' => currency_format(50),
      'others_charge' => currency_format(50),
      'payable_amount' => currency_format(800),
      'paid_amount' => currency_format(500),
      'due' => currency_format(50),
      'due_paid' => currency_format(100),
      'previous_due' => currency_format(100),
      'prev_due_paid' => currency_format(100),
      'profit' => currency_format(10),
      'return_amount' => currency_format(10),
      'change' => currency_format(50),
      'amount_in_text' => 'TK Two Thousands Fifty Only',

      'payment_list' => '',
      'tax_summary' => '',

      'qrcode' => '<img src="../storage/qrcode.png" alt="" class="img-responsive" style="display:inline-block" width="50" height="50">',
      'qrcode_url' => '../storage/qrcode.png',
      'barcode' => '',
      'barcode_url' => '',
      'cashier_name' => '',
      'printed_on' => format_date(date_time()),
      'invoice_view' => get_preference('invoice_view'),
      'items' => array(
        array(
           'id' => 1.00,
           'invoice_id' => 'INV100000001',
           'category_id' => 9.00,
           'sup_id' => 7.00,
           'store_id' => 1.00,
           'item_id' => 52.00,
           'item_name' => 'Product1',
           'hsn_code' => '1234',
           'item_price' => 200.00,
           'item_discount' => 0.00,
           'item_tax' => 0.00,
           'tax_method' => 'Exclusive',
           'taxrate_id' => 3.00,
           'tax' => '0.00',
           'gst' => '0.00',
           'cgst' => '0.00',
           'sgst' => '0.00',
           'igst' => '0.00',
           'item_quantity' => 1.00,
           'item_purchase_price' => 100.00,
           'item_total' => 200.00,
           'purchase_invoice_id' => 'B221',
           'print_counter' => '0.00',
           'printed_by' => '',
           'print_counter_time' => '',
           'created_at' => '19 May 2019 3:29 PM',
           'unitName' => '',
           'sl' => 1,
        ),
      ),
      'return_items' => array(
        array(
           'id' => '1.00',
           'store_id' => '1.00',
           'invoice_id' => 'INV/2019/05/00000001',
           'item_id' => '6.00',
           'item_name' => ' সিলন চা 200.gm',
           'item_quantity' => '1.00',
           'item_purchase_price' => '90.00',
           'item_price' => '100.00',
           'item_tax' => '0.00',
           'cgst' => '0.00',
           'sgst' => '0.00',
           'igst' => '0.00',
           'item_total' => '100.00',
           'created_at' => '19 May 2019 3:29 PM',
           'sl' => '1',
        ),
      ),
      'payments' => array(
        array(
            'id' => 1.00,
            'type' => 'Sell',
            'store_id' => 1.00,
            'invoice_id' => 'INV100000001',
            'reference_no' => '',
            'pmethod_id' => 1.00,
            'transaction_id' => '',
            'capital' => 50.00,
            'amount' => 100.00,
            'details' => '',
            'attachment' => '',
            'note' => '',
            'total_paid' => 100.00,
            'pos_balance' => 0.00,
            'created_by' => 1.00,
            'created_at' => '19 May 2019 3:29 PM',
            'name' => 'Cash on Delivery',
            'by' => 'NAME HERE',
            'sl' => 1,
        ),
      ),
      'taxes' => array(
        array (
            'qty' => 1.00,
            'tax' => 0.00,
            'item_tax' => 0.00,
            'taxrate_name' => 'No Tax',
            'code_name' => 'NNX',
            'sl' => 1,
        )
      ),
    );
    return $data;
}