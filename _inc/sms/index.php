<?php
namespace SMSGateway;
use Exception;

ob_start();
session_start();
include realpath(__DIR__.'/../../').'/_init.php';

$gatewayConfig = include 'config.php';
require_once 'vendor/autoload.php';

$sms_model = registry()->get('loader')->model('sms');

if (!isset($request->get['action_type']) 
	&& !isset($request->post['action_type'])
	&& (!isset($argc) || !isset($argv[1]))) {
	exit();
}

$action_type = '';
if (isset($request->get['action_type'])) {
	$action_type = $request->get['action_type'];
} elseif (isset($request->post['action_type'])) {
	$action_type = $request->post['action_type'];
} elseif (isset($argv[1])) {
	$action_type = $argv[1];
}

if ($action_type == 'UPDATEDELIVERYSTATUS') 
{
    $gateway = get_preference('sms_gateway');
    if (!isset($gatewayConfig['gateways'][$gateway])) {
      die('Gateway setting error!');
    }
    $gw = (new SMSGateway())->initGateway($gateway, sms_setting($gateway));
    $filter_data = array(
    	'filter_total_eqg_try' => 1,
    	'filter_process_status' => 1,
    	'filter_delivery_status' => 'pending',
    	'start' => 0,
    	'limit' => 50,
	);
    $sms_rows = $sms_model->getScheduleSms($filter_data);
    $total = 0;
    if (!empty($sms_rows)) {
	    foreach ($sms_rows as $sms) {
	    	switch ($gateway) {
	    		case 'onnorokomsms':
	    				$response_array = explode('||',$sms['response_text']);
				    	if (!isset($response_array[0]) || $response_array[0] != 1900) {
				    		$sms_model->updateDeliveryStatus($sms['id'], 'failed');
					      	$total++;
					      	continue;
				    	}
				    	$response_id = rtrim($response_array[2],'/');
				    	$response = $gw->deliveryStatus($response_id);
					    if ($response) {
					    	$response = $response == 2 ? 'delivered' : 'failed';
					      	$sms_model->updateDeliveryStatus($sms['id'], $response);
					      	$total++;
					    }
	    			break;

	    		default:
		    			//...
	    			break;
	    	}
	    }
	} else {
		die('No data');
	}
	echo $total;
	exit();
}

if ($action_type == 'PROCEEDSCHEDULESMS') 
{
    $gateway = get_preference('sms_gateway');
    if (!isset($gatewayConfig['gateways'][$gateway])) {
      die('Gateway setting error!');
    }
    $gw = (new SMSGateway())->initGateway($gateway, sms_setting($gateway));
    $sms_model = registry()->get('loader')->model('sms');
    $filter_data = array(
    	'start' => 0,
    	'limit' => 50,
    );
    $sms_rows = $sms_model->getScheduleSms($filter_data);
    $total = 0;
    if (!empty($sms_rows)) {
	    foreach ($sms_rows as $sms) {
	    	switch ($gateway) {
	    		case 'onnorokomsms':
	    				$mobile_number = $sms['mobile_number'];
				    	$message = $sms['sms_text'];
				    	$response = $gw->send($mobile_number, $message);
				    	$response_array = explode('||',$response);
					    if (count($response_array) > 1) {
					      $sms_model->updateStatus($sms['id'], $response);
					      $total++;
					    }
	    			break;

	    		case 'Msg91':
	    				$mobile_number = $sms['mobile_number'];
				    	$message = $sms['sms_text'];
				    	$response = $gw->send($mobile_number, $message);
					    if ($response) {
					      $sms_model->updateStatus($sms['id'], $response);
					      $total++;
					    }
	    		default:
	    				//...
	    			break;
	    	}
	    }
	    
	} else {
		die('No data');
	}
	echo $total;
	exit();
}

// Check, if user logged in or not
// If user is not logged in then return error
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Check, if user has reading permission or not
// If user have not reading permission return error
if (user_group_id() != 1 && !has_permission('access', 'send_sms')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// Validate post data
function validate_request_data($request) {

	// People type validation
	if (!validateString($request->post['people_type'])) {
	  throw new Exception(trans('error_people_type'));
	}

    // Message validation
	if (!validateString($request->post['message'])) {
	  throw new Exception(trans('error_message'));
	}

	// People validation
	if (count($request->post['peoples']) <= 0) {
		throw new Exception(trans('error_people_not_found'));
	}
}

if ($request->server['REQUEST_METHOD'] == 'POST' && $action_type == 'SENDGROUP') 
{
	try {

		if (DEMO) {
			throw new Exception(trans('error_disable_in_demo'));
		}

		validate_request_data($request);
		$gateway = get_preference('sms_gateway');
	    if (!isset($gatewayConfig['gateways'][$gateway])) {
	      throw new Exception(trans('error_invalid_gateway'));
	    }
		$message = $request->post['message'];
		$tc_model = registry()->get('loader')->model('tagconverter');
		$peoples = $request->post['peoples'];
		$msg91_sms = array();
		$gw = (new SMSGateway())->initGateway($gateway, sms_setting($gateway));
		foreach ($peoples as $p) {
			$mobile_number = $p['mobile_number'] ? $p['mobile_number'] : '';
			if (!$mobile_number) {
				continue;
			}
			$cmessage = $tc_model->convert(array('[name]'), array('name' => $p['name']), $message);
			$response = $gw->send($mobile_number, $cmessage);
			switch ($gateway) {
				case 'Msg91':
			           $msg91_sms[] = array(
			                'message' => $cmessage,
			                'to' => array(
			                    $mobile_number,
			                ),
			            );
			           $data = array(
							'schedule_datetime' => date_time(),
							'store_id' => store_id(),
							'people_type' => $request->post['people_type'],
							'mobile_number' => $mobile_number,
							'people_name' => $p['name'],
							'sms_text' => $cmessage,
							'campaign_name' => '',
							'process_status' => 1,
							'total_try' => 1,
							'created_at' => date_time(),
						);
						$sms_model->addSchedule($data);
					break;
				// case 'onnorokomsms':
				// case 'twilio':
				// case 'clicatell':
				default:
					$data = array(
						'schedule_datetime' => date_time(),
						'store_id' => store_id(),
						'people_type' => $request->post['people_type'],
						'mobile_number' => $mobile_number,
						'people_name' => $p['name'],
						'sms_text' => $cmessage,
						'campaign_name' => '',
						'process_status' => 1,
						'total_try' => 0,
						'created_at' => date_time(),
					);
					$sms_model->addSchedule($data);
					break;
			}
		}
		if (!empty($msg91_sms)) {
			$gw = (new SMSGateway())->initGateway($gateway, sms_setting($gateway));
			$response = $gw->send($msg91_sms);
			header('Content-Type: application/json');
			echo json_encode(array('msg' => trans('text_sms_sent')));
			exit();
		}

		header('Content-Type: application/json');
		echo json_encode(array('msg' => trans('text_success_sms_sent')));
		exit();

	} catch (Exception $e) { 
	    
	    header('HTTP/1.1 422 Unprocessable Entity');
	    header('Content-Type: application/json; charset=UTF-8');
	    echo json_encode(array('errorMsg' => $e->getMessage()));
	    exit();
	}
}

if ($request->server['REQUEST_METHOD'] == 'POST' && $action_type == 'SENDINDIVIDUAL') 
{
	try {

		if (DEMO) {
			throw new Exception(trans('error_disable_in_demo'));
		}

		$gateway = get_preference('sms_gateway');
		if (!isset($gatewayConfig['gateways'][$gateway])) {
			throw new Exception(trans('error_gateway'));
		}

		$phone_number = $request->post['phone_number'];
		if (!$phone_number) {
			throw new Exception(trans('error_phone_number'));
		}

		$message = $request->post['message'];
		if (!$message) {
			throw new Exception(trans('error_sms_text'));
		}
		
		$gw = (new SMSGateway())->initGateway($gateway, sms_setting($gateway));
		$response = $gw->send($phone_number, $message);
		switch ($gateway) {
			case 'onnorokomsms':
				$response_array = explode('||',$response);
				if (empty($response_array)) {
					throw new Exception(trans('error_sms_not_send'));
				}
				$data = array(
					'schedule_datetime' => date_time(),
					'store_id' => store_id(),
					'people_type' => 'customer',
					'mobile_number' => $phone_number,
					'people_name' => 'Annonymous',
					'sms_text' => $message,
					'campaign_name' => NULL,
					'process_status' => 1,
					'total_try' => 0,
					'created_at' => date_time(),
				);
				$id = $sms_model->addSchedule($data);
				if (count($response_array) > 1) {
			      $sms_model->updateStatus($id, $response);
			    }
				break;
			case 'Msg91':
				if (empty($response)) {
					throw new Exception(trans('error_sms_not_send'));
				}
				$data = array(
					'schedule_datetime' => date_time(),
					'store_id' => store_id(),
					'people_type' => 'customer',
					'mobile_number' => $phone_number,
					'people_name' => 'Annonymous',
					'sms_text' => $message,
					'campaign_name' => NULL,
					'process_status' => 1,
					'total_try' => 0,
					'created_at' => date_time(),
				);
				$id = $sms_model->addSchedule($data);
				if ($response) {
			      $sms_model->updateStatus($id, $response);
			    }
				break;
			default:
				if (empty($response)) {
					throw new Exception(trans('error_sms_not_send'));
				}
				$data = array(
					'schedule_datetime' => date_time(),
					'store_id' => store_id(),
					'people_type' => 'customer',
					'mobile_number' => $phone_number,
					'people_name' => 'Annonymous',
					'sms_text' => $message,
					'campaign_name' => NULL,
					'process_status' => 1,
					'total_try' => 0,
					'created_at' => date_time(),
				);
				$id = $sms_model->addSchedule($data);
				break;
		}
		header('Content-Type: application/json');
		echo json_encode(array('msg' => trans('text_success_sms_sent')));
		exit();

	} catch (Exception $e) { 
	    
	    header('HTTP/1.1 422 Unprocessable Entity');
	    header('Content-Type: application/json; charset=UTF-8');
	    echo json_encode(array('errorMsg' => $e->getMessage()));
	    exit();
	}
}

if ($request->server['REQUEST_METHOD'] == 'POST' && $action_type == 'RESEND') 
{
	try {

		if (DEMO) {
			throw new Exception(trans('error_disable_in_demo'));
		}

		if (empty($request->post['mobile_number'])) {
			throw new Exception(trans('error_mobile_number'));
		}
		$mobile_number = $request->post['mobile_number'];

		if (empty($request->post['sms_text'])) {
			throw new Exception(trans('error_message'));
		}
		$message = $request->post['sms_text'];

		$id = $request->post['id'];

		$gateway = get_preference('sms_gateway');
		if (!isset($gatewayConfig['gateways'][$gateway])) {
			throw new Exception(trans('error_gateway'));
		}

		$gw = (new SMSGateway())->initGateway($gateway, sms_setting($gateway));

		$response = $gw->send($mobile_number, $message);
		$response_array = explode('||',$response);
		if (empty($response_array)) {
			throw new Exception(trans('error_sms_not_send'));
		}
		$sms_model->updateSchedule($id, $request->post);
		if (count($response_array) > 1) {
	      $sms_model->updateStatus($id, $response);
	    }

		header('Content-Type: application/json');
		echo json_encode(array('msg' => trans('text_success_sms_sent'), 'id' => $id));
		exit();

	} catch (Exception $e) { 
	    
	    header('HTTP/1.1 422 Unprocessable Entity');
	    header('Content-Type: application/json; charset=UTF-8');
	    echo json_encode(array('errorMsg' => $e->getMessage()));
	    exit();
	}
}

if ($request->server['REQUEST_METHOD'] == 'POST' && $action_type == 'SEND') 
{
	try {

		if (DEMO) {
			throw new Exception(trans('error_disable_in_demo'));
		}

	    if (user_group_id() != 1 && !has_permission('access', 'sms_sell_invoice')) {
	      throw new Exception(trans('error_send_sms_permission'));
	    }

		$invoice_id = $request->post['invoice_id'];
		$invoice_model = registry()->get('loader')->model('invoice');
		$invoice = $invoice_model->getInvoiceInfo($invoice_id);

		$gateway = get_preference('sms_gateway');
		if (!isset($gatewayConfig['gateways'][$gateway])) {
			throw new Exception(trans('error_gateway'));
		}

		$phone_number = isset($request->post['phone_number']) ? $request->post['phone_number'] : '';
		if (!$phone_number) {
			$phone_number = $invoice['customer_mobile'] ? $invoice['customer_mobile'] : $invoice['mobile_number'];
		}

		$message = isset($request->post['message']) ? $request->post['message'] : '';
		if (!$message) {
			$message = trans('invoice_sms_text');
		}

		$tc_model = registry()->get('loader')->model('tagconverter');
		$invoice = $invoice_model->getInvoiceInfo($invoice_id);
		$data = array(
			'customer_name' => get_the_customer($invoice['customer_id'],'customer_name'),
			'invoice_id' => $invoice_id,
			'discount' => currency_format($invoice['discount_amount']),
			'payable_amount' => get_currency_code().' '.currency_format($invoice['payable_amount']),
			'paid_amount' => get_currency_code().' '.currency_format($invoice['paid_amount']),
			'due' => get_currency_code().' '.currency_format($invoice['due']),
			'store_name' => store('name'),
			'address' => store('address'),
			'payment_status' => ucfirst($invoice['payment_status']),
			'customer_mobile' => $invoice['customer_mobile'] ? $invoice['customer_mobile'] : $invoice['mobile_number'],
			'payment_method' => get_the_pmethod($invoice['pmethod_id'],'name'),
			'date_time' => format_date($invoice['created_at']),
			'date' => format_only_date($invoice['created_at']),
			'tax' => currency_format($invoice['item_tax']+$invoice['order_tax']),
		);

	  	$tags = array();
		$statement = db()->prepare("SELECT * FROM `mail_sms_tag` WHERE `type` = ?");
		$statement->execute(array('invoice'));
		$array = $statement->fetchAll(\PDO::FETCH_ASSOC);
		foreach ($array as $tag) {
			$tags[] = $tag['tagname'];
		}
		$message = $tc_model->convert($tags, $data, $message);
		$gw = (new SMSGateway())->initGateway($gateway, sms_setting($gateway));
		$response = $gw->send($phone_number, $message);
		switch ($gateway) {
			case 'onnorokomsms':
					$response_array = explode('||',$response);
					if (empty($response_array)) {
						throw new Exception(trans('error_sms_not_send'));
					}
					$data = array(
						'schedule_datetime' => date_time(),
						'store_id' => store_id(),
						'people_type' => 'customer',
						'mobile_number' => $phone_number,
						'people_name' => $invoice['customer_name'],
						'sms_text' => $message,
						'campaign_name' => NULL,
						'process_status' => 1,
						'total_try' => 0,
						'created_at' => date_time(),
					);
					$id = $sms_model->addSchedule($data);
					if (count($response_array) > 1) {
				      $sms_model->updateStatus($id, $response);
				    }
				break;
			case 'Msg91':
					if (empty($response)) {
						throw new Exception(trans('error_sms_not_send'));
					}
					$data = array(
						'schedule_datetime' => date_time(),
						'store_id' => store_id(),
						'people_type' => 'customer',
						'mobile_number' => $phone_number,
						'people_name' => 'Annonymous',
						'sms_text' => $message,
						'campaign_name' => NULL,
						'process_status' => 1,
						'total_try' => 0,
						'created_at' => date_time(),
					);
					$id = $sms_model->addSchedule($data);
					if ($response) {
				      $sms_model->updateStatus($id, $response);
				    }
				break;
			default:
				if (empty($response)) {
					throw new Exception(trans('error_sms_not_send'));
				}
				$data = array(
					'schedule_datetime' => date_time(),
					'store_id' => store_id(),
					'people_type' => 'customer',
					'mobile_number' => $phone_number,
					'people_name' => 'Annonymous',
					'sms_text' => $message,
					'campaign_name' => NULL,
					'process_status' => 1,
					'total_try' => 0,
					'created_at' => date_time(),
				);
				$id = $sms_model->addSchedule($data);
				break;
		}

		header('Content-Type: application/json');
		echo json_encode(array('msg' => trans('text_success_sms_sent')));
		exit();

	} catch (Exception $e) { 
	    
	    header('HTTP/1.1 422 Unprocessable Entity');
	    header('Content-Type: application/json; charset=UTF-8');
	    echo json_encode(array('errorMsg' => $e->getMessage()));
	    exit();
	}
}

// SMS Form
if ($invoice_id=$request->get['invoice_id'] AND $action_type == 'FORM')
{
	$invoice_model = registry()->get('loader')->model('invoice');
	$invoice = $invoice_model->getInvoiceInfo($invoice_id);
	$tags = '';
	$statement = db()->prepare("SELECT * FROM `mail_sms_tag` WHERE `type` = ?");
	$statement->execute(array('invoice'));
	$array = $statement->fetchAll(\PDO::FETCH_ASSOC);
	foreach ($array as $tag) {
		$tags .= ' <kbd>'.$tag['tagname'].'</kbd>';
	}
  	include '../template/invoice_sms_form.php';
  	exit();
}

// Resend SMS Form
if (isset($request->get['id']) AND $action_type == 'RESENDFORM') 
{
  	$row = $sms_model->getScheduleSmsRow($request->get['id']);
  	include ROOT.'/_inc/template/sms_resend_form.php';
  	exit();
}