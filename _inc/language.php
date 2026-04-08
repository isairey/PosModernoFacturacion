<?php 
ob_start();
session_start();
include ("../_init.php");

// Check, if user logged in or not
// If user is not logged in then return an alert message
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Check, if user has reading permission or not
// If user have not reading permission return an alert message
if (user_group_id() != 1 && !has_permission('access', 'language_translation')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

require_once DIR_INCLUDE.'/vendor/gtranslator/autoload.php';
use Stichoza\GoogleTranslate\GoogleTranslate;

if ($request->server['REQUEST_METHOD'] == 'POST' AND isset($request->get['action_type']) AND $request->get['action_type'] == 'TRANSLATE')
{
  try {

    if (DEMO) {
      throw new Exception(trans('error_disabled_in_demo'));
    }

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'language_translation')) {
      throw new Exception(trans('error_delete_language_key'));
    }

    $id = $request->post['id'];
    $lang_id = $request->post['lang_id'];
    $lang_key = $request->post['lang_key'];
    $lang_value = $request->post['lang_value'];

    $statement = db()->prepare("SELECT `code` FROM `languages` WHERE `id` = ?");
    $statement->execute(array($lang_id));
    $lang = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$lang_value) {
      $statement = db()->prepare("SELECT `lang_value` FROM `language_translations` WHERE `lang_id` = ? AND `lang_key` = ?");
      $statement->execute(array(1, $lang_key));
      $lang_translation = $statement->fetch(PDO::FETCH_ASSOC);
      $lang_value = $lang_translation['lang_value'];
      if (!$lang_value) {
        throw new Exception('error_language_value');
      }
      if ($lang['code'] != 'en' && checkInternetConnection()) {
        $tr = new GoogleTranslate();
        $tr->setSource('en');
        // $tr->setSource();
        $tr->setTarget($lang['code']);
        try{
           $lang_value = $tr->translate($lang_value);
        } catch (Exception $e) {
          throw new Exception($e->getMessage());
        }
      }
    }

    $statement = db()->prepare("UPDATE `language_translations` SET `lang_value` = ? WHERE `lang_id` = ? AND `lang_key` = ?");
    $statement->execute(array($lang_value, $lang_id, $lang_key));

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_translation_success'), 'id' => $id));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Delete language Key
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    if (DEMO) {
      throw new Exception(trans('error_disabled_in_demo'));
    }

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_language_key')) {
      throw new Exception(trans('error_delete_language_key'));
    }

    $lang_key = $request->post['lang_key'];
    $statement = db()->prepare("DELETE FROM `language_translations` WHERE `lang_key` = ?");
    $statement->execute(array($lang_key));
    
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_delete_success'), 'lang_key' => $lang_key));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Validate post data
function validate_request_data($request) {

  // Name validation
  if (!validateString($request->post['lang_name'])) {
      throw new Exception(trans('error_name'));
  }

  // Slug validation
  if (!validateString($request->post['slug'])) {
      throw new Exception(trans('error_slug'));
  }

  // Code validation
  if (!validateString($request->post['code'])) {
    throw new Exception(trans('error_code'));
  }
}

// Check giftcard existance by id
function validate_existance($request, $id = 0)
{
  $statement = db()->prepare("SELECT * FROM `languages` WHERE `slug` = ? AND `code` = ? AND `id` != ?");
  $statement->execute(array($request->post['slug'], $request->post['code'], $id));
  if ($statement->rowCount() > 0) {
    throw new Exception(trans('error_language_exist'));
  }
}

// Create New Language
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'create_language')) {
      throw new Exception(trans('error_language_create_permission'));
    }

    validate_request_data($request);

    $name = $request->post['lang_name'];
    $slug = $request->post['slug'];
    $code = $request->post['code'];

    $statement = db()->prepare("INSERT INTO `languages` (name, slug, code) VALUES(?, ?, ?)");
    $statement->execute(array($name, $slug, $code));
    $id = db()->lastInsertId();
    
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_create_success'), 'id' => $id));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Edit Language
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'update_language')) {
      throw new Exception(trans('error_language_update_permission'));
    }

    $id = $request->post['lang_id'];

    validate_request_data($request);
    validate_existance($request, $id);

    $name = $request->post['lang_name'];
    $slug = $request->post['slug'];
    $code = $request->post['code'];

    $statement = db()->prepare("UPDATE `languages` SET `name` = ?, `slug` = ?, `code` = ? WHERE `id` = ?");
    $statement->execute(array($name, $slug, $code, $id));
    
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_uppdate_success'), 'id' => $id));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Delete language
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETELANGUAGE') 
{
  try {

    if (DEMO) {
      throw new Exception(trans('error_disabled_in_demo'));
    }

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_language')) {
      throw new Exception(trans('error_delete_permission'));
    }
    $lang_id = $request->post['id'];
    if ($lang_id == 1) {
      throw new Exception(trans('error_default_language'));
    }
    $statement = db()->prepare("DELETE FROM `languages` WHERE `id` = ?");
    $statement->execute(array($lang_id));
    $statement = db()->prepare("DELETE FROM `language_translations` WHERE `lang_id` = ?");
    $statement->execute(array($lang_id));
    
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_delete_success')));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Language create form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') {
    include 'template/language_create_form.php';
    exit();
}

// Language edit form
if (isset($request->get['lang_id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    $lang_id = $request->get['lang_id'];
    $statement = db()->prepare("SELECT `name`, `slug`, `code` FROM `languages` WHERE `id` = ?");
    $statement->execute(array($lang_id));
    $lang = $statement->fetch(PDO::FETCH_ASSOC);
    include 'template/language_edit_form.php';
    exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Language_List');

$where_query = "1=1";
$code = isset($request->get['lang']) && $request->get['lang'] ? $request->get['lang'] : 'en';
$statement = db()->prepare("SELECT `id` FROM `languages` WHERE `code` = ?");
$statement->execute(array($code));
$lang = $statement->fetch(PDO::FETCH_ASSOC);
if ($lang) {
  $lang_id = $lang['id'];
  $where_query .= " AND L.lang_id = $lang_id";
}
if (isset($request->get['key_type']) && $request->get['key_type'] == 'default') {
  $where_query .= " AND L.key_type = 'default'";
}
if (isset($request->get['action_type']) && $request->get['action_type'] == 'empty_value') {
  $where_query .= " AND L.lang_value IS NULL";
}
// DB table to use
$table = "(SELECT * FROM language_translations L WHERE {$where_query}) as language_translations";

if (isset($request->get['action_type']) && $request->get['action_type'] == 'dublicate_entry') {
  $table = "(SELECT L.id as id,  L.lang_id as lang_id, L.lang_key as lang_key, L.key_type as key_type, L.lang_value as lang_value FROM language_translations L WHERE {$where_query} GROUP BY L.lang_key HAVING COUNT(lang_key) > 1) as language_translations";
}
 
// Table's primary key
$primaryKey = 'id';
 
$columns = array(
  array( 'db' => 'id', 'dt' => 'id' ),
  array( 'db' => 'lang_id', 'dt' => 'lang_id' ),
  array( 'db' => 'key_type', 'dt' => 'key_type' ),
  array( 
    'db' => 'lang_key',   
    'dt' => 'lang_key' ,
    'formatter' => function($d, $row) {
      $key_type = $row['key_type'] == 'default' ? ' <span class="text-blue">(default)</span>' : '';
      return $row['lang_key'].$key_type;
    }
  ),
  array(
      'db' => 'id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$row['id'];
      }
  ),
  array( 
    'db' => 'lang_value',   
    'dt' => 'lang_value' ,
    'formatter' => function($d, $row) {
        return '<input id="value'.$row['id'].'" class="form-control" type="text" style="width:100%;max-width:100%;padding:2px 4px;" name="lang_value['.$row['lang_id'].']['.$row['id'].']" value="'.trim($row['lang_value']).'">';
    }
  ),
  array( 
    'db' => 'id',   
    'dt' => 'btn_translate' ,
    'formatter' => function($d, $row) {
      return '<button id="transbtn'.$row['id'].'" class="btn btn-sm btn-block btn-primary transbtn" data-id="'.$row['id'].'" data-langid="'.$row['lang_id'].'" data-key="'.$row['lang_key'].'" type="button" data-loading-text="Translating..."><i class="fa fa-fw fa-pencil"></i> '.trans('button_translate').'</button>';
    }
  ),
  array( 
    'db' => 'id',   
    'dt' => 'btn_delete' ,
    'formatter' => function($d, $row) {
      return '<button id="deletebtn'.$row['id'].'" class="btn btn-sm btn-block btn-danger deletebtn" data-id="'.$row['id'].'" data-langid="'.$row['lang_id'].'" data-key="'.$row['lang_key'].'" type="button" data-loading-text="Deleting..."><i class="fa fa-fw fa-trash"></i></button>';
    }
  ),
);

echo json_encode(
  SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Language_List');

/**
 *===================
 * END DATATABLE
 *===================
 */