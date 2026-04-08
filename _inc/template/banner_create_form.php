<?php
$tab_active = isset($request->get['tab']) ? $request->get['tab'] : 'general';
?>
<style type="text/css">
.image-thumbnail {
  position: relative;
  width: 80px;
  height: 75px;
  overflow: hidden;
  display: inline-block;
}
.image-thumbnail img {
    position: relative;
    width: 100%;
    height: auto;
    max-height: 100%;
}
</style>
<form id="create-banner-form" class="form form-horizontal" action="banner.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="CREATE">
  <div class="box-body">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="<?php echo $tab_active == 'general' ? 'active' : null;?>">
            <a href="#general" data-toggle="tab" aria-expanded="false">
            <?php echo trans('text_general'); ?>
          </a>
        </li>
        <li class="<?php echo $tab_active == 'image' ? 'active' : null;?>">
            <a href="#image" data-toggle="tab" aria-expanded="false">
            <?php echo trans('text_images'); ?>
          </a>
        </li>
      </ul>
      <div class="tab-content">

        <!-- Image Start -->
        <div class="tab-pane<?php echo $tab_active == 'image' ? ' active' : null;?>" id="image">
          <table class="table table-bordered table-condenced table-striped">
            <thead>
              <tr class="bg-gray">
                <td class="w-5 text-center"><?php echo trans('label_serial_no');?></td>
                <td class="w-15 text-center"><?php echo trans('label_image');?></td>
                <td class="w-20"><?php echo trans('label_url');?></td>
                <td class="w-35"><?php echo trans('label_link');?></td>
                <td class="w-15 text-center"><?php echo trans('label_sort_order');?></td>
                <td class="w-10 text-center"><?php echo trans('label_action');?></td>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="img in imgArray" id="{{ $index }}" class="image-item">
                <td class="text-center">{{ img.id }}</td>
                <td class="text-center">
                  <div class="image-thumbnail">
                    <a class="open-filemanager" data-imageid="{{img.id}}" data-toggle="image" id="thumb{{img.id}}" href="#">
                      <img src="../assets/itsolution24/img/noimage.jpg">
                    </a>
                  </div>
                </td>
                <td>
                  <input class="form-control" type="text" name="image[{{img.id}}][url]" id="image{{img.id}}" value="{{ img.url }}" autocomplete="off" readonly>
                </td>
                <td>
                  <input class="form-control" type="text" name="image[{{img.id}}][link]" value="{{ img.link }}" autocomplete="off">
                </td>
                <td>
                  <input type="text" name="image[{{img.id}}][sort_order]" class="form-control sort_order text-center" value="{{ img.sort_order }}" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
                </td>
                <td class="text-center pointer remove-image">
                  <span ng-click="remoteImageItem($index)" class="fa fa-fw fa-close text-red"></span>
                </td>
              </tr>
              <tr>
                <td ng-click="addImageItem(imgSerial);" colspan="6" class="text-center bg-info add-image-row pointer">
                  <span class="fa fa-fw fa-plus text-white"></span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Image End -->

        <!-- General Start -->
        <div class="tab-pane<?php echo $tab_active == 'general' ? ' active' : null;?>" id="general">
          <div class="form-group">
            <label for="name" class="col-sm-3 control-label">
              <?php echo trans('label_name'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-6">
              <input ng-model="bannerName" type="text" class="form-control" id="name" value="" name="name" required>
            </div>
          </div>
          <div class="form-group">
            <label for="slug" class="col-sm-3 control-label">
              <?php echo trans('label_slug'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="slug" value="{{ bannerName | strReplace:' ':'_' | lowercase }}" name="slug" required>
            </div>
          </div>
          <div class="form-group">
          <label class="col-sm-3 control-label">
            <?php echo trans('label_store'); ?><i class="required">*</i>
          </label>
          <div class="col-sm-6 store-selector">
            <div class="checkbox selector">
              <label>
                <input type="checkbox" onclick="$('input[name*=\'banner_store\']').prop('checked', this.checked);"> Select / Deselect
              </label>
            </div>
            <div class="filter-searchbox">
              <input ng-model="search_store" class="form-control" type="text" id="search_store" placeholder="<?php echo trans('search'); ?>">
            </div>
            <div class="well well-sm store-well"> 
              <div filter-list="search_store">
              <?php foreach(get_stores() as $the_store) : ?>                    
                <div class="checkbox">
                  <label>                         
                    <input type="checkbox" name="banner_store[]" value="<?php echo $the_store['store_id']; ?>" <?php echo $the_store['store_id'] == store_id() ? 'checked' : null; ?>>
                    <?php echo $the_store['name']; ?>
                  </label>
                </div>
              <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
          <div class="form-group">
            <label for="status" class="col-sm-3 control-label">
              <?php echo trans('label_status'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-6">
              <select id="status" class="form-control" name="status" >
                <option value="1">
                  <?php echo trans('text_active'); ?>
                </option>
                <option value="0">
                  <?php echo trans('text_inactive'); ?>
                </option>
              </select>
            </div>
          </div>
          <div class="form-group">
          <label for="sort_order" class="col-sm-3 control-label">
            <?php echo trans('label_sort_order'); ?><i class="required">*</i>
          </label>
          <div class="col-sm-6">
            <input type="number" class="form-control" id="sort_order" value="<?php echo isset($request->post['sort_order']) ? $request->post['sort_order'] : 0; ?>" name="sort_order">
          </div>
        </div>
        </div>
        <!-- General End -->

        <div class="form-group">
          <label class="col-sm-3 control-label"></label>
          <div class="col-sm-3">
            <button id="create-banner-submit" data-form="#create-banner-form" data-datatable="#banner-banner-list" class="btn btn-block btn-info" name="btn_edit_banner" data-loading-text="Saving...">
              <span class="fa fa-fw fa-pencil"></span>
              <?php echo trans('button_save'); ?>
            </button>
          </div>
          <div class="col-sm-3">
            <button type="reset" class="btn btn-block btn-danger" id="reset" name="reset"><span class="fa fa-fw fa-circle-o"></span>
              <?php echo trans('button_reset'); ?>
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
  setTimeout(function() {
    $("#random_num").trigger("click");
  }, 1000);
})
</script>