<?php
include ("_init.php");

if (DEMO) {
	die(trans('text_disabled_in_demo'));
}

$cronModel = registry()->get('loader')->model('cron');

if (!isset($request->get['action']) 
	&& !isset($request->post['action']) 
		&& (!isset($argc) || !isset($argv[1]))) {

	exit();
}

$action = '';
if (isset($request->get['action'])) {
	$action = $request->get['action'];
} elseif (isset($request->post['action'])) {
	$action = $request->post['action'];
} elseif (isset($argv[1])) {
	$action = $argv[1];
}
$log->write('Cron: '.$action.' Starting...');


if ($action == 'CHECKFORUPDATE') {
	$cronModel->CheckForUpdate($action);
}



if ($action == 'DBBACKUP') {
	$cronModel->DBBackup($action);
}


if ($action == 'SENDCUSTOMERBIRTHDAYSMS') {
	$cronModel->SendCustomerBirthDaySMS($action);
}



if ($action == 'PUSHSQLTOREMOTESERVER') {
	$cronModel->PushSqlToRemoteServer($action);
}



if ($action == 'RUNALLJOBS') {
	$cronModel->Run($action);
}

if (is_cli()) {
	foreach ($cronModel->err as $err) {
		echo '---'.$err . PHP_EOL;
	}
	foreach ($cronModel->msg as $msg) {
		echo '---'.$msg . PHP_EOL;
	}

} else {
	foreach ($cronModel->err as $err) {
		echo '---'.$err .'</br>';
	}
	foreach ($cronModel->msg as $msg) {
		echo '---'.$msg .'</br>';
	}
}


/*
----------------------------------------
| Usages
----------------------------------------
|   Cron Job (run at 1:00 AM daily):
|   0 1 * * * wget -qO- http://pos/admin/cron.php >/dev/null 2>&1
*/