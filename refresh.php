<?php
ob_start();
session_start();
define('START', true);
define('REFRESH', true);
include ("install/_init.php");

function checkInternetConnection($domain = 'www.google.com')  
{
  if($socket =@ fsockopen($domain, 80, $errno, $errstr, 30)) {
    fclose($socket);
    return true;
  }
  return false;
}

// function checkValidationServerConnection($domain = '')  
// {
//   if($socket =@ fsockopen($domain, 80, $errno, $errstr, 30)) {
//     fclose($socket);
//     return true;
//   }
//   return false;
// }

function url_exists($url) {
    $ch = @curl_init($url);
    @curl_setopt($ch, CURLOPT_HEADER, TRUE);
    @curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $status = array();
    preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($ch) , $status);
    curl_close($ch);
    return ($status[1] == 200 || $status[1] == 422);
}

function checkValidationServerConnection($url = 'http://tracker.itsolution24.com/pos30/check.php')  
{
    if(url_exists($url)) {
        return true;
    }
    return false;
}

function checkEnvatoServerConnection($domain = 'www.envato.com')  
{
  if($socket =@ fsockopen($domain, 80, $errno, $errstr, 30)) {
    fclose($socket);
    return true;
  }
  return false;
}

if (isset($_GET['APPID']) && $_GET['APPID'] == APPID) {
  if(!checkInternetConnection() || !checkValidationServerConnection() || !checkEnvatoServerConnection()) {
  	die('Need internet connection!');
  }

  $url = base64_decode('aHR0cDovL29iLml0c29sdXRpb24yNC5jb20vYXBpX3Bvcy5waHA=');
  $data = array(
      'username' => base64_decode('aXRzb2x1dGlvbjI0'),
      'password' => base64_decode('MTk3MQ=='),
      'app_name' => APPNAME,
      'app_id' => APPID,
      'version' => '3.0',
      'files' => array('_init.php','network.php','ecnesil.php','revalidate.php'),
      // 'files' => array('_init.php','network.php','ecnesil.php'),
      'stock_status' => 'false',
  ); 
  $data_string = json_encode($data);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
  curl_setopt($ch, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data_string)]
  );
  $result = json_decode(curl_exec($ch),true);
  if (isset($result['contents'])) {
    foreach ($result['contents'] as $filename => $content) {
      switch ($filename) {
        case '_init.php':
            $file_path = ROOT.DIRECTORY_SEPARATOR.'_init.php';
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
            $fp = fopen($file_path, 'wb');
            fwrite($fp, $content);
            fclose($fp);
          break;
        case 'network.php':
            $file_path = DIR_HELPER.DIRECTORY_SEPARATOR.'network.php';
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
            $fp = fopen($file_path, 'wb');
            fwrite($fp, $content);
            fclose($fp);
          break;
        case 'ecnesil.php':
            $file_path = DIR_INCLUDE.DIRECTORY_SEPARATOR.'ecnesil.php';
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
            $fp = fopen($file_path, 'wb');
            fwrite($fp, $content);
            fclose($fp);
          break;
        case 'revalidate.php':
            $file_path = ROOT.DIRECTORY_SEPARATOR.'revalidate.php';
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
            $fp = fopen($file_path, 'wb');
            fwrite($fp, $content);
            fclose($fp);
          break;
        default:
          # code...
          break;
      }
    }
  } else {
      die("No internet connection / Validation server is down!!!");
      return false;
  }

  redirect('index.php');

} else {

    die('Invalid Action. Required Valid APPID.');
}