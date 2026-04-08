<?php 
ob_start();
session_start();
include ("../_init.php");
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

if(isset($_FILES["faviconFile"]["type"]))
{	
    if (DEMO) {
      echo trans('text_disabled_in_demo');
      exit();
    }
    
	// Check permission
	if (user_group_id() != 1 && !has_permission('access', 'upload_favicon')) {
      echo trans('error_upload_favicon_permission');
      exit();
    }

    // Validate store id
    if (!validateInteger($request->post['store_id'])) {
    	echo trans('error_store_id');
    	exit();
    }

    $store_id = $request->post['store_id'];

    $Hooks->do_action('Before_Upload_Favicon', $request);

	$validextensions = array("ico", "png");
	$temporary = explode(".", $_FILES["faviconFile"]["name"]);
	$file_extension = end($temporary);
	
	if ((($_FILES["faviconFile"]["type"] == "image/png") || ($_FILES["faviconFile"]["type"] == "image/ico")) && ($_FILES["faviconFile"]["size"] < 51200) // 50 kb
	&& in_array($file_extension, $validextensions)) {
		
		if ($_FILES["faviconFile"]["error"] > 0) {
			echo "Return Code: " . $_FILES["faviconFile"]["error"] . "<br/><br/>";
			exit();
		} else {
			$temp = explode(".", $_FILES["faviconFile"]["name"]);
			$newfilename = $store_id . '_favicon.' . end($temp);
			$sourcePath = $_FILES["faviconFile"]["tmp_name"]; //Storing source path of the file in a variable
			$targetPath = "../assets/itsolution24/img/logo-favicons/".$newfilename; //Target path where file is to be stored
			if(move_uploaded_file($sourcePath,$targetPath)) {

				$statement = db()->prepare("UPDATE `stores` SET `favicon` = ? WHERE `store_id` = ?");
				$statement->execute(array($newfilename, $store_id));
			}; 
			echo "<span class='success'>Favicon Successfully Uploaded...!!</span><br/>";
		}

		$Hooks->do_action('After_Upload_Favicon', $request);

	} else {
		echo "<span class='invalid'>***Invalid file Size or Type***<span>";
	}
}