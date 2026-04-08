<?php 
ob_start();
session_start();
define('START', true);
include ("install/_init.php");

if (!function_exists("checkInternetConnection")) {
    function checkInternetConnection($domain = 'www.google.com')  
    {
        if($socket =@ fsockopen($domain, 80, $errno, $errstr, 30)) {
            fclose($socket);
            return true;
        }
        return false;
    }
}

if (!function_exists("checkValidationServerConnection")) {
    function checkValidationServerConnection($domain = 'www.itsolution24.com')  
    {
        if($socket =@ fsockopen($domain, 80, $errno, $errstr, 30)) {
            fclose($socket);
            return true;
        }
        return false;
    }
}

if (!function_exists("checkEnvatoServerConnection")) {
    function checkEnvatoServerConnection($domain = 'www.envato.com')  
    {
        if($socket =@ fsockopen($domain, 80, $errno, $errstr, 30)) {
            fclose($socket);
            return true;
        }
        return false;
    }
}

$errors = array();
$success = array();
$info = array();

$json = array();

if(!checkInternetConnection() || !checkValidationServerConnection() || !checkEnvatoServerConnection()) {
	if (is_ajax()) {
		$json['redirect'] = root_url().'/index.php';
		echo json_encode($json);
		exit();
	} else {
		$errors['internet_connection'] = 'Need internet connection!';
	}
}

$ecnesil_path = DIR_INCLUDE.'config/purchase.php';
$config_path = ROOT . '/config.php';
function purchase_code_validation() 
{
    global $request, $ecnesil_path, $config_path, $errors, $success, $info;

    if (empty($request->post['purchase_username'])) {
        $errors['purchase_username'] = 'Purchase username is required';
        return false;
    }

    if (empty($request->post['purchase_code'])) {
        $errors['purchase_code'] = 'Purchase code is required';
        return false;
    }

    $file = DIR_INCLUDE.'config/purchase.php';
    if (is_writable($config_path) === false) {
        $errors['config_error'] = 'config.php is not writable!';
        return false;
    }

    if (is_writable($ecnesil_path) === false) {
        $errors['config_error'] = 'Some file unable to write!';
        return false;
    }

    $info['username'] = trim($request->post['purchase_username']);
    $info['purchase_code'] = trim($request->post['purchase_code']);
    $info['domain'] = ROOT_URL;
    $info['action'] = 'validation';
    $apiCall = apiCall($info);
    if (!is_object($apiCall)) {
        $errors['internet_connection'] = 'Validation failed!';
        return false;
    }
    if($apiCall->status == 'error') {
        $errors['purchase_code'] = $apiCall->message;
        return false;
    } else {

        if (generate_ecnesil($request->post['purchase_username'], $request->post['purchase_code'], $ecnesil_path)) {
            return true;
        }
        $errors['preparation'] = 'Problem while generating license!';
        return false;
    }
}

$ALGORITHM = 'AES-128-CBC';
$IV    = '12dasdq3g5b2434b';
$password   = '123456789!@#$%^&*((*&^%$#@!))';
if (isset($request->get['action_type']) && $request->get['action_type'] == 'UPDATESYSTEM')
{
    require_once ROOT."/config.php";
    $host = $sql_details['host'];
    $dbname = $sql_details['db'];
    $user = $sql_details['user'];
    $pass = $sql_details['pass'];
    $port = $sql_details['port'];
    $mysqli = @new mysqli($host, $user, $pass, $dbname, $port);
    if (mysqli_connect_errno()) {
        $json['error'] = 'Oop!, Something went wrong. Please check your input';
    }

    $filepath = DIR_STORAGE.".sql";

    if (!is_file($filepath)) {
        $json['error'] = 'Temporary file is not exist';
    }   

    if (isset($request->get['from'])) {
        $from = $request->get['from']-1;
    } else {
        $from = 0;  
    }

    if (isset($request->get['to'])) {
        $to = $request->get['to'];
    } else {
        $to = 50;   
    }

    if (isset($request->get['line_done'])) {
        $line_done = $request->get['line_done'];
    } else {
        $line_done = 50;    
    }
            
    if (!$json) 
    {
        $data_available = false;
        $templine = '';
        $sql_data = file_get_contents($filepath);
        $sql_data = @openssl_decrypt($sql_data, $ALGORITHM, $password, 0, $IV);
        $temp = tmpfile();
        $temppath = stream_get_meta_data($temp)['uri'];
        fwrite($temp, $sql_data);
        $lines = file($temppath);
        fclose($temp);

        $totalLines = count($lines);
        $line_done = 0;
        $t = 0;
        foreach ($lines as $line) {
            $line_done++;
            if (substr($line, 0, 2) == '--' || substr($line, 0, 2) == '/*' || $line == ''){
                continue;
            }
            if ($t >= $to){
                break;
            }
            if ($t > $from){
                $templine .= $line;
            }
            if (substr(trim($line), -1, 1) == ';') {
                $t++;
                if ($t > $from && $templine){
                    $data_available = true;
                    $mysqli->query($templine);
                    $templine = '';
                }
            }
        }

        $from = $to;
        $to += 50;
        $json['total'] = round(($line_done / $totalLines) * 100);
        if ($data_available) {
            $json['next'] = root_url().'/update.php?from=' . $from . '&to=' . $to . '&line_done=' . $line_done . '&action_type=UPDATESYSTEM';
        } else {
            $mysqli->close();
            unlink($filepath);

            $url = base64_decode('aHR0cDovL29iLml0c29sdXRpb24yNC5jb20vYXBpX3Bvcy5waHA=');
            $data = array(
                'username' => base64_decode('aXRzb2x1dGlvbjI0'),
                'password' => base64_decode('MTk3MQ=='),
                'app_name' => APPNAME,
                'app_id' => APPID,
                'version' => '3.0',
                'files' => array('_init.php'),
                'stock_status' => 'false',
                'timezone' => date_default_timezone_get(),
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
                $errors['preparation'] = 'Problem while preparing files! ';
                return false;
            }


            $json['success'] = 'Database tables successfully imported';
        }
    }
    header('Content-Type: application/json');
    echo json_encode($json);
    exit();
}

function database_import() 
{
    global $session, $request, $errors, $success, $info, $ALGORITHM, $IV, $password;

    $dbhost = trim($request->post['host']);
    $dbname = trim($request->post['database']);
    $dbuser = trim($request->post['user']);
    $dbpass = trim($request->post['password']);
    $dbport = trim($request->post['port']);

    $info['username'] = get_pusername();
    $info['purchase_code'] = get_pcode();
    $info['domain'] = ROOT_URL;
    $info['app_id'] = APPID;
    $info['ip'] = get_real_ip();
    $info['mac'] = json_encode(getMAC());
    $info['version'] = '2.0';
    $info['action'] = 'update';

    $apiCall = apiCall($info);
    if(!$apiCall || !is_object($apiCall)) {
        $errors['dbimport'] = 'An unexpected response from validation server!';
        return false;
    }
    if ($apiCall->status == 'error') {
        $errors['dbimport'] = $apiCall->message;
        return false;
    }
    if(empty($apiCall->schema)) {
        $errors['dbimport'] = 'Sql was not found!';
        return false;
    }
    $sql_data = $apiCall->schema;
    $info = json_decode($apiCall->info,true);
    $version = isset($info['version']) ? $info['version'] : '2.0';
    $link = isset($info['link']) ? $info['link'] : '';    

    $encrypt_data = openssl_encrypt($sql_data, $ALGORITHM, $password, 0, $IV);
    write_file(DIR_STORAGE.'.sql', $encrypt_data);

    $config_path = ROOT . '/config.php';
    @chmod($config_path, FILE_WRITE_MODE);
    if (is_writable($config_path) === false) {
        $errors['database_import'] = 'Config file is un-writable';
        return false;
    } else {
        $file = $config_path;
        $line2      = "define('INSTALLED', true);";
        $line_host  = "'host' => '". $dbhost ."',";
        $line_db    = "'db' => '". $dbname ."',";
        $line_user  = "'user' => '". $dbuser ."',";
        $line_pass  = "'pass' => '". $dbpass ."',";
        $line_port  = "'port' => '". $dbport ."'";
        $fileArray  = array(2 => $line2, 5 => $line_host, 6 => $line_db, 7 => $line_user, 8 => $line_pass, 9 => $line_port);
        replace_lines($file, $fileArray);
        @chmod($config_path, FILE_READ_MODE);
    }

    $db = pdo_start();
    $statement = $db->prepare("SELECT * FROM `settings`");
    $statement->execute(array());
    $settings = $statement->fetch(PDO::FETCH_ASSOC);
    if ($settings['version'] == $version) {
        $errors['error'] = 'System is already up to date!';
        return false;
    }

    $statement = $db->prepare("UPDATE `settings` SET `version` = ?, `is_update_available` = ?, `update_version` = ?, `update_link` = ?");
    $statement->execute(array($version, 0, $version, $link));

    return true;
}

if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'STARTUPDATE') 
{
    $json = array();

    if (!check_pcode()) {
        $errors['purchase_code'] = 'Purchase code is not valid.';
    }

    if (empty($request->post['host'])) {
        $errors['host'] = 'Host field is required.';
    }

    if (empty($request->post['database'])) {
        $errors['database'] = 'Database field is required.';
    }

    if (empty($request->post['user'])) {
        $errors['user'] = 'Username field is required.';
    }

    if (empty($request->post['port'])) {
        $errors['port'] = 'Port field is required.';
    }

    database_import();

    if(empty($errors)) {
        $json['next'] = root_url().'/update.php?action_type=UPDATESYSTEM';
    } else {
        $json = array_filter($errors);
    }

    echo json_encode($json);
    exit();
}

if ($request->server['REQUEST_METHOD'] == 'POST') 
{
    $json = array();

    if (empty($request->post['purchase_code'])) {
        $errors['purchase_code'] = 'Purchase code is not valid.';
    }

    if (empty($request->post['purchase_username'])) {
        $errors['purchase_code'] = 'Purchase username is not valid.';
    }

    purchase_code_validation();

    if(empty($errors)) {
        $json['redirect'] = root_url().'/update.php?update=yes';
    } else {
        $json = array_filter($errors);
    }

    echo json_encode($json);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Update Modern POS from v2.0 - v3.0</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!--Set favicon-->
    <link rel="shortcut icon" href="install/assets/images/favicon.png">
    
    <!-- Style CSS -->
    <link type="text/css" href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="assets/toastr/toastr.min.css" type="text/css" rel="stylesheet">
    <link type="text/css" href="assets/select2/select2.min.css" type="text/css" rel="stylesheet">
    <link type="text/css" href="install/assets/css/style.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="assets/jquery/jquery.min.js"></script> 
    <script src="assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="assets/toastr/toastr.min.js" type="text/javascript"></script>
    <script src="assets/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <script src="assets/select2/select2.min.js" type="text/javascript"></script>
    <script src="install/assets/js/script.js"></script> 
</head>
<body>
<div id="loader-status">
    <span class="text">...</span>
    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100" style="width: 73%;"></div>
    </div>
</div>
<style type="text/css">#its24 {position: fixed;height: 100%;left: 0;bottom: 0;}#its24 .svg {height: 100%;width: auto;}</style>
<div id="its24">
<svg version="1.1" class="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     viewBox="0 0 426 447" style="enable-background:new 0 0 426 447;" xml:space="preserve">
<style type="text/css">
    .st0{opacity:0.1;}
    .st1{fill:url(#XMLID_67_);}
    .st2{fill:url(#XMLID_68_);}
    .st3{fill:url(#XMLID_69_);}
    .st4{fill:url(#XMLID_70_);}
</style>
<g id="XMLID_557_" class="st0">
    <radialGradient id="XMLID_67_" cx="187.164" cy="201.6132" r="180.3211" gradientUnits="userSpaceOnUse">
        <stop  offset="5.376344e-003" style="stop-color:#FFC609"/>
        <stop  offset="1" style="stop-color:#FAAF40"/>
    </radialGradient>
    <path id="XMLID_558_" class="st1" d="M201.5,47.5L363.1,8.2l-40.1,160.9l-29.5-21.8c0,0-163,106.1-151.8,271.2
        c0,0-53.4-26-81.5-61.8c0,0-5.6-155.3,163.7-290.9L201.5,47.5z"/>
    <radialGradient id="XMLID_68_" cx="72.9587" cy="198.6403" r="97.898" gradientUnits="userSpaceOnUse">
        <stop  offset="0" style="stop-color:#009BC9"/>
        <stop  offset="1" style="stop-color:#005D99"/>
    </radialGradient>
    <path id="XMLID_559_" class="st2" d="M85.2,72.9l45.7,45.7c-83.5,96.1-92.8,205.7-92.8,205.7C-18,215.2,39.9,122.7,85.2,72.9z"/>
    <radialGradient id="XMLID_69_" cx="129.2642" cy="74.696" r="37.1617" gradientUnits="userSpaceOnUse">
        <stop  offset="5.376344e-003" style="stop-color:#FFC609"/>
        <stop  offset="1" style="stop-color:#FAAF40"/>
    </radialGradient>
    <path id="XMLID_560_" class="st3" d="M162.2,86.8c-8.9,8.1-17.2,16.3-24.9,24.7L91.7,65.9c10.5-10.9,19.9-19.3,26.4-24.8
        c5.3-4.5,13.2-4.2,18.2,0.6l26.2,25C168.3,72.2,168.1,81.4,162.2,86.8z"/>
    <radialGradient id="XMLID_70_" cx="290.4081" cy="317.6298" r="123.653" gradientUnits="userSpaceOnUse">
        <stop  offset="0" style="stop-color:#009BC9"/>
        <stop  offset="1" style="stop-color:#005D99"/>
    </radialGradient>
    <path id="XMLID_561_" class="st4" d="M280.2,195.8c0,0-116.6,90.6-115.2,236.1c0,0,146.9,47.8,250.9-96.3c0,0-89,84.3-129.3,71.7
        c-24.6-7.7-0.7-94.2,71.7-139.1L280.2,195.8z"/>
</g>
</svg>
</div>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="panel panel-default header" style="">
                <div class="panel-heading text-center">
                    <h2>Update Modern POS from v2.0 to v3.0</h2>
                    <h1>STEP 
                        <?php if (!isset($request->get['update'])):?>
                            1
                        <?php elseif (isset($request->get['update']) && $request->get['update'] == 'yes'):?>
                            2
                        <?php endif;?>
                        of 2
                    </h1>
                </div>
                <?php if (!isset($request->get['update'])):?>
                    <div class="panel-body">
                        <h4>Pre-Requirements:</h4>
                        <p style="color:#ff023b;">Although we have tested several times but we can not give you 100% gurantee that after updated successfully, the system will work properly. So, before proceed to update. Please, read/follow the pre-requirements list. You are only the responsible person while the system does not work after updated successfully.</p>

                        <div class="text-center">
                        <h4 class="bg-info" style="padding:10px;"><b>NEED INTERNET, MAKE SURE YOUR INTERNET CONNECTION IS OK</b></h4>
                        <h4 class="bg-info" style="padding:10px;"><b>TAKE DATABASE BACKUP</b></h4>
                        <h4 class="bg-info" style="padding:10px;"><b>TAKE ALL FILES BACKUP</b></h4>
                        </div>

                        <div class="alert alert-warning highlight-text" style="margin-bottom:0!important;">
                            <p>*** This action may take several minutes. Please keep patience while processing this action and never close the browser. Otherwise system will not work properly. Enjoy a cup of coffee while you are waiting... :)</p>
                        </div>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-sm-8 col-sm-offset-2">  
            <div class="panel panel-default menubar">
                <div class="panel-body ins-bg-col">
                    <?php if(isset($errors['internet_connection'])): ?>
                        <div class="alert alert-danger">
                            <p><?php echo $errors['internet_connection']; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($errors['config_error'])): ?>
                        <div class="alert alert-danger">
                            <p><?php echo $errors['config_error']; ?></p>
                        </div>
                    <?php endif; ?>
                    <br>
                    <form id="updateForm" class="form-horizontal" role="form" action="<?php echo root_url();?>/update.php" method="post">
                        <?php if (!isset($request->get['update'])):
                            if(isset($errors['purchase_username'])) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                            ?>
                                <label for="purchase_username" class="col-sm-3 control-label">
                                    <p>Envato Username <span class="text-aqua">*</span></p>
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="purchase_username" name="purchase_username" value="<?php echo isset($request->post['purchase_username']) ? $request->post['purchase_username'] : null; ?>" autocomplete="off">

                                    <p class="control-label">
                                        <?php echo isset($errors['purchase_username']) ? $errors['purchase_username'] : ''; ?>
                                    </p>
                                </div>
                            </div>
                            <?php 
                            if(isset($errors['purchase_code'])) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                            ?>
                                <label for="purchase_code" class="col-sm-3 control-label">
                                    <p>Purchase Code <span class="text-aqua">*</span></p>
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="purchase_code" name="purchase_code" value="<?php echo isset($request->post['purchase_code']) ? $request->post['purchase_code'] : null; ?>" autocomplete="off">

                                    <p class="control-label">
                                        <?php echo isset($errors['purchase_code']) ? $errors['purchase_code'] : ''; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-7 col-sm-offset-3 text-left">
                                    <button class="btn btn-success btn-block ajaxcall" data-form="updateForm" data-loading-text="Checking...">Next &rarr;</button>
                                </div>
                            </div>
                        <?php endif;?>

                        <?php if (isset($request->get['update']) && $request->get['update'] == 'yes'):
                            if(isset($errors['host'])) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                            ?>
                                <input type="hidden" name="action_type" value="STARTUPDATE">
                                <label for="host" class="col-sm-3 control-label">
                                    <p>Hostname <span class="text-aqua">*</span></p>
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="host" name="host" value="<?php echo isset($request->post['host']) ? $request->post['host'] : 'localhost'; ?>" required>

                                    <p class="control-label">
                                        <?php echo isset($errors['host']) ? $errors['host'] : ''; ?>
                                    </p>
                                </div>
                            </div>

                            <?php 
                            if(isset($errors['database']))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                            ?>
                                <label for="database" class="col-sm-3 control-label">
                                    <p>Database Name <small>of v2.0</small> <span class="text-aqua">*</span></p>
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="database" name="database" value="<?php echo isset($request->post['database']) ? $request->post['database'] : null; ?>" required>

                                    <p class="control-label">
                                        <?php echo isset($errors['database']) ? $errors['database'] : ''; ?>
                                    </p>
                                </div>
                            </div>

                            <?php 
                            if(isset($errors['user'])) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                            ?>
                                <label for="user" class="col-sm-3 control-label">
                                    <p>Database Username <span class="text-aqua">*</span></p>
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="user" name="user" value="<?php echo isset($request->post['user']) ? $request->post['user'] : 'root'; ?>" required>

                                    <p class="control-label">
                                        <?php echo isset($errors['user']) ? $errors['user'] : ''; ?>
                                    </p>
                                </div>
                            </div>

                            <?php 
                            if(isset($errors['password'])) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                            ?>
                                <label for="password" class="col-sm-3 control-label">
                                    <p>Database Password</p>
                                </label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="password" name="password" value="<?php echo isset($request->post['password']) ? $request->post['password'] : null; ?>" required>

                                    <p class="control-label">
                                        <?php echo isset($errors['password']) ? $errors['password'] : ''; ?>
                                    </p>
                                </div>
                            </div>

                            <?php 
                            if(isset($errors['port']))
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                            ?>
                                <label for="port" class="col-sm-3 control-label">
                                    <p>Port (3306) <span class="text-aqua">*</span></p>
                                </label>
                                <div class="col-sm-7">
                                    <input type="port" class="form-control" id="port" name="port" value="<?php echo isset($request->post['port']) ? $request->post['port'] : 3306; ?>" required>

                                    <p class="control-label">
                                        <?php echo isset($errors['port']) ? $errors['port'] : ''; ?>
                                    </p>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="col-sm-7 col-sm-offset-3 text-left">
                                    <button class="btn btn-success btn-block ajaxcall" data-form="updateForm" data-loading-text="Checking...">GO &rarr;</button>
                                    <div class="clearfix text-center" style="margin-top:30px;">
                                        <a href="<?php echo root_url();?>/update.php">&larr; Go Back</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                        
                    </form>
                </div>
            </div>
            <div class="text-center copyright">&copy; <a href="#">Shopify EX</a>, All right reserved.</div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(".ajaxcall").on("click", function(e) {
    e.stopImmediatePropagation();
    e.stopPropagation();
    e.preventDefault();
    var $funcName;
    $(".field-error").remove();
    $(".loader-status").hide();
    var $btn = $(this);
    var $formID = $btn.data("form");
    var $form = $("#"+$formID);
    var $data = $form.serialize();
    var $actionUrl = $form.data("action");
    $.ajax({
        url: $actionUrl,
        type: "POST",
        dataType: 'json',
        data:  $data,
        beforeSend: function() {
            $("body").addClass("overlay-loader");
            $(".btn").attr("disabled", "disabled");
            $(".form-control").attr("disabled", "disabled");
            $btn.button("loading");
            $funcName = $formID+"BeforesendCallback";
            if (eval("typeof "+$funcName) == "function") {
                eval($formID+"BeforeSendCallbackCallback()");
            }
        },
        complete: function() {
            $funcName = $formID+"CompleteCallback";
            if (eval("typeof "+$funcName) == "function") {
                eval($formID+"CompleteCallback()");
            }
        },
        success: function(res) {
            if (res.redirect) {
                window.location = res.redirect;
            } else {
                if (!res["next"]) {
                    $("body").removeClass("overlay-loader");
                    $(".btn").removeAttr("disabled");
                    $(".form-control").removeAttr("disabled", "disabled");
                    $btn.button("reset");
                    $.each(res, function (index, value) {
                        $("#"+index).after("<span class='text-red field-error'><i>"+value+"<i></span>");
                        toastr.error(value);
                    });
                } else {
                    $funcName = $formID+"SuccessCallback";
                    if (eval("typeof "+$funcName) == "function") {
                        eval($formID+"SuccessCallback(res)");
                    }
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $funcName = $formID+"ErrorCallback";
            if (eval("typeof "+$funcName) == "function") {
                eval($formID+"ErrorCallback()");
            }
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            $("body").removeClass("overlay-loader");
            $(".btn").removeAttr("disabled");
            $(".form-control").removeAttr("disabled", "disabled");
            $btn.button("reset");
        }
    });
});
function updateFormSuccessCallback(res)
{
    $("#loader-status").show();
    $("#loader-status .progress").show();
    $("#loader-status .text").text("Updating...");

    $("#loader-status .progress-bar").attr("aria-valuenow", 0);
    $("#loader-status .progress-bar").css("width", "0%");
    
    next(res["next"]);
}
function next(url) {
    $.ajax({
      url: url,
      dataType: "json",
      success: function(json) {
        if (json["error"]) {
            toastr.error(json["error"]);
            $("#loader-status").css('display','none');
            $("body").removeClass("overlay-loader");
            $("#loader-status").remove();
            $(".btn").removeAttr("disabled");
            $(".form-control").removeAttr("disabled", "disabled");
            $('.btn').button("reset");
        }
        if (json["success"]) {
            window.swal({
              title: "Update Successful!",
              text:  "Contratulations, System updated to version 3.0. Please, Delete update.php file for security purpose.",
              type: "success",
              showConfirmButton: false
            })
            .then(function (willDelete) {
                if (willDelete) {
                    window.location = 'index.php';
                }
            });
        }
        if (json["total"]) {
            $("#loader-status .text").text( json["total"]+"%");
            $("#loader-status .progress-bar").attr("aria-valuenow", json["total"]);
            $("#loader-status .progress-bar").css("width", json["total"] + "%");
        }
        if (json["next"]) {
          next(json["next"]);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }  
</script>
</body>
</html>