<?php 
ob_start();
session_start();
include ("../_init.php");

// Check, if your logged in or not
// If user is not logged in then return an alert message
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Update store
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    if (DEMO) {
      throw new Exception(trans('error_disabled_in_demo'));
    }

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_sms_setting')) {
      throw new Exception(trans('error_update_permission'));
    }

    $Hooks->do_action('Before_Update_SMS_Setting', $request);

    // Clickatell
    $setting = $request->post['setting']['clickatell'];
    $statement = db()->prepare("UPDATE `sms_setting` SET `username` = ?, `password` = ?, `api_id` = ? WHERE `type` = ?");
    $statement->execute(array($setting['username'], $setting['password'], $setting['api_id'], 'Clickatell'));

    // Twilio
    $setting = $request->post['setting']['twilio'];
    $statement = db()->prepare("UPDATE `sms_setting` SET `username` = ?, `password` = ?, `api_id` = ? WHERE `type` = ?");
    $statement->execute(array($setting['sender_id'], $setting['auth_key'], $setting['contact'], 'Twilio'));

    // Msg91
    $setting = $request->post['setting']['msg91'];
    $statement = db()->prepare("UPDATE `sms_setting` SET `auth_key` = ?, `sender_id` = ?, `country_code` = ? WHERE `type` = ?");
    $statement->execute(array($setting['auth_key'], $setting['sender_id'], $setting['country_code'], 'Msg91'));

    // MimSMS
    $setting = $request->post['setting']['mimsms'];
    $statement = db()->prepare("UPDATE `sms_setting` SET `api_id` = ?, `auth_key` = ?, `sender_id` = ? WHERE `type` = ?");
    $statement->execute(array($setting['api_id'], $setting['auth_key'], $setting['sender_id'], 'mimsms'));

    // OnnorokomSMs
    $setting = $request->post['setting']['onnorokomsms'];
    $statement = db()->prepare("UPDATE `sms_setting` SET `username` = ?, `password` = ?, `maskname` = ?, `campaignname` = ? WHERE `type` = ?");
    $statement->execute(array($setting['username'], $setting['password'], $setting['maskname'], $setting['campaignname'], 'Onnorokomsms'));

    $Hooks->do_action('After_Update_SMS_Setting', $request);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_update_success')));
    exit();

  } catch (Exception $e) {

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();

  }
}