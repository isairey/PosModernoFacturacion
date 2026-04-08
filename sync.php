<?php
require_once("config.php");
require_once("_inc/helper/common.php");
require_once("_inc/helper/file.php");
require_once("_inc/helper/network.php");

if (isLocalhost()) {
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Invalid Action',
		'for' => 'invalid',
	));
	exit();
};


function validateApiAccess($username, $password) {
    $valid_clients = array(
        'itsolution24' => array(
            'password' => '1993'
        ),
    );
    return isset($valid_clients[$username]) && ($valid_clients[$username]['password'] == $password);
}

$post_data 		= json_decode(file_get_contents('php://input'), true);
$action 		= isset($post_data['action']) ? $post_data['action'] : null;
$query_data 	= isset($post_data['data']) ? json_decode($post_data['data'],true) : null;

if (!isset($post_data['username']) || !isset($post_data['password'])) {
    echo json_encode(array(
		'status' => 'error',
		'message' => 'Invalid Action',
		'for' => 'invalid',
	));
    exit();
}

if (!validateApiAccess($post_data['username'], $post_data['password'])) {
    echo json_encode(array(
		'status' => 'error',
		'message' => 'Invalid Action',
		'for' => 'invalid',
	));
    exit();
}


switch ($action) {
	case 'sync':
	    
		
		try {
    		$db = pdo_start();
    	}
    	catch(PDOException $e) {
    		 echo json_encode(array(
        		'status' => 'error',
        		'message' => 'Database Connection Error: '.$e->getMessage(),
        		'for' => 'invalid',
        	));
            exit();
    	}

	    foreach ($query_data as $sql) {
	      $statement = $db->prepare($sql['sql']);
	      $statement->execute($sql['args']);
	    }

		echo json_encode(array(
			'status' => 'success',
			'message' => 'sync successfully done',
			'for' => 'sync',
		));
		break;

	default:
		echo json_encode(array(
			'status' => 'error',
			'message' => 'Invalid Action',
			'for' => 'invalid',
		));
		break;
}
