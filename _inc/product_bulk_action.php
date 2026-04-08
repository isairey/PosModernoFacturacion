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

// LOAD PRODUCT MODEL
$product_model = registry()->get('loader')->model('product');

if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->get['action'])) 
{
  try {

    // Check permission
    if (user_group_id() != 1 && !has_permission('access', 'product_bulk_action') || DEMO) {
      throw new Exception(trans('error_bulk_permission'));
    }

    $action = $request->get['action'];

    // Check, if there has selected item or not
    if (!isset($request->post['selected']) || empty($request->post['selected'])) {
      throw new Exception(trans('error_no_selected'));
    }

    $Hooks->do_action('After_Product_Bulk_Action', $action);

    $ids = $request->post['selected'];
    if (!is_array($ids)) {
      $ids = array($ids);
    }
    
    $id_length = count($ids);

    switch ($action) {
      case 'delete':

          if (user_group_id() != 1 && !has_permission('access', 'delete_all_product')) {
            throw new Exception(sprintf(trans('error_delete_permission'), trans('text_product')));
          }
          for ($i=0; $i < $id_length; $i++) { 
            $id = $ids[$i];
            $belongs_stores = $product_model->getBelongsStore($id);
            foreach ($belongs_stores as $the_store) {
              $store_id = $the_store['store_id'];
              $stock_status = $product_model->isStockAvailable($id, $store_id);
              if ($stock_status) {
                throw new Exception("Oops!, Unbale to delete, Stock available in the " . store_field('name', $store_id));
              }
            }
            $product_model->deleteProduct($id); 
            $message = trans('text_delete');
          }
          $success_message = trans('success_delete_all');

        break;
      case 'restore':
          
          // Check product restore permission
          if (user_group_id() != 1 && !has_permission('access', 'restore_all_product')) {
            throw new Exception(sprintf(trans('error_restore_permission'), trans('text_product')));
          }

          for ($i=0; $i < $id_length; $i++) { 
            $id = $ids[$i];

            if (DEMO && $id == 1) {
              continue;
            }

            // Update product status
            $product_model->updateStatus($id, 1, store_id());
          }

          $success_message = trans('success_restore_all');
        break;
      default:
        # code...
        break;
    }

    $Hooks->do_action('After_Product_Bulk_Action', $action);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => $success_message));
    exit();

  } catch (Exception $e) {

    $error_message = $e->getMessage();
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $error_message));
    exit();
  }
}
