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

if(isset($_FILES["file"]["type"]))
{
	if (DEMO) {
      echo trans('text_disabled_in_demo');
      exit();
    }
    
	// Check Permission
	if (user_group_id() != 1 && !has_permission('access', 'upload_logo')) {
      echo trans('error_upload_logo_permission');
      exit();
    }

    // Validate store id
    if (!validateInteger($request->post['store_id'])) {
    	echo trans('error_store_id');
    	 exit();
    }
    
    $store_id = $request->post['store_id'];

    $Hooks->do_action('Before_Upload_Logo', $request);
    
	$validextensions = array("jpeg", "jpg", "png");
	$temporary = explode(".", $_FILES["file"]["name"]);
	$file_extension = end($temporary);
	if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
	) && ($_FILES["file"]["size"] < 102400) // 100 kb
	&& in_array($file_extension, $validextensions)) {

		if ($_FILES["file"]["error"] > 0) {
			echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
			exit();
		} else {

			$temp = explode(".", $_FILES["file"]["name"]);
			$newfilename = $store_id . '_logo.' . end($temp);
			$sourcePath = $_FILES["file"]["tmp_name"];
			$targetPath = "../assets/itsolution24/img/logo-favicons/".$newfilename;
			if(move_uploaded_file($sourcePath,$targetPath)) {
				$statement = db()->prepare("UPDATE `stores` SET `logo` = ? WHERE `store_id` = ?");
				$statement->execute(array($newfilename, $store_id));
			}; 
			echo "<span class='success'>Logo Successfully Uploaded...!!</span><br/>";
		}
		$Hooks->do_action('After_Upload_Logo', $request);

	} else {

		echo "<span class='invalid'>***Invalid file Size or Type***<span>";
	}
}