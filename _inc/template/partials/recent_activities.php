<div class="nav-tabs-custom mb-0">
  <ul class="nav nav-tabs store-m15">
    <?php $active_activity_tab = false;?>
    <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_list')) : ?>
    <li class="<?php if(!$active_activity_tab) { echo 'active'; $active_activity_tab = true;}?>">
        <a href="#sales" data-toggle="tab" aria-expanded="false">
        <?php echo trans('text_sales'); ?>
      </a>
    </li>
    <?php endif;?>
    <?php if (user_group_id() == 1 || has_permission('access', 'read_quotation')) : ?>
    <li class="<?php if(!$active_activity_tab) { echo 'active'; $active_activity_tab = true;}?>">
        <a href="#quotations" data-toggle="tab" aria-expanded="false">
        <?php echo trans('text_quotations'); ?>
      </a>
    </li>
     <?php endif;?>
    <?php if (user_group_id() == 1 || has_permission('access', 'read_purchase_list')) : ?>
    <li class="<?php if(!$active_activity_tab) { echo 'active'; $active_activity_tab = true;}?>">
        <a href="#purchases" data-toggle="tab" aria-expanded="false">
        <?php echo trans('text_purchases'); ?>
      </a>
    </li>
     <?php endif;?>
    <?php if (user_group_id() == 1 || has_permission('access', 'read_transfer')) : ?>
    <li class="<?php if(!$active_activity_tab) { echo 'active'; $active_activity_tab = true;}?>">
        <a href="#transfers" data-toggle="tab" aria-expanded="false">
        <?php echo trans('text_transfers'); ?>
      </a>
    </li>
     <?php endif;?>
    <?php if (user_group_id() == 1 || has_permission('access', 'read_customer')) : ?>
    <li class="<?php if(!$active_activity_tab) { echo 'active'; $active_activity_tab = true;}?>">
        <a href="#customers" data-toggle="tab" aria-expanded="false">
        <?php echo trans('text_customers'); ?>
      </a>
    </li>
     <?php endif;?>
    <?php if (user_group_id() == 1 || has_permission('access', 'read_supplier')) : ?>
    <li class="<?php if(!$active_activity_tab) { echo 'active'; $active_activity_tab = true;}?>">
        <a href="#suppliers" data-toggle="tab" aria-expanded="false">
        <?php echo trans('text_suppliers'); ?>
      </a>
    </li>
  <?php endif;?>
  </ul>
  <div class="tab-content">
    
    <?php $active_activity_content = false;?>
    <!-- Sales Start -->
    <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_list')) : ?>
    <div class="tab-pane<?php if(!$active_activity_content) { echo ' active'; $active_activity_content = true;}?>" id="sales">
      <div class="row">
        <div class="col-lg-8 col-xs-12">
          <div class="box box-default banking-box mb-0">
            <div class="box-body">
              <div class="table-responsive" style="min-height:166px">
                <?php include ROOT.'/_inc/template/partials/activities/sells.php';?>
              </div>
            </div>
            <div class="box-footer clearfix">
              <?php if (user_group_id() == 1 || has_permission('access', 'create_sell_invoice')) : ?>
                <a href="<?php echo root_url();?>/admin/pos.php" class="btn btn-xs btn-info btn-flat"><span class="fa fa-fw fa-plus"></span> <?php echo trans('button_add_sales'); ?></a>
              <?php endif; ?>
              <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_list')) : ?>
                <a href="<?php echo root_url();?>/admin/invoice.php" class="btn btn-xs btn-success btn-flat"><span class="fa fa-fw fa-list"></span> <?php echo trans('button_list_sales'); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-xs-12" style="padding-right: 15px">
          <?php include ROOT.'/_inc/template/partials/progress_group.php';?>
        </div>
      </div>
    </div>
    <?php endif;?>
    <!-- Sales End -->

    <!-- Quotations Start -->
    <?php if (user_group_id() == 1 || has_permission('access', 'read_quotation')) : ?>
    <div class="tab-pane<?php if(!$active_activity_content) { echo ' active'; $active_activity_content = true;}?>" id="quotations">
      <div class="row">
        <div class="col-lg-12 col-xs-12">
          <div class="box box-default banking-box mb-0">
            <div class="box-body">
              <div class="table-responsive" style="min-height:166px">
                <?php include ROOT.'/_inc/template/partials/activities/quotations.php';?>
              </div>
            </div>
            <div class="box-footer clearfix">
              <?php if (user_group_id() == 1 || has_permission('access', 'add_quotation')) : ?>
                <a href="<?php echo root_url();?>/admin/quotation.php?box_state=open" class="btn btn-xs btn-info btn-flat"><span class="fa fa-fw fa-plus"></span> <?php echo trans('button_add_quotations'); ?></a>
              <?php endif; ?>
              <?php if (user_group_id() == 1 || has_permission('access', 'read_quotation')) : ?>
                <a href="<?php echo root_url();?>/admin/quotation.php" class="btn btn-xs btn-success btn-flat"><span class="fa fa-fw fa-list"></span> <?php echo trans('button_list_quotations'); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <!-- Quotations End -->

    <!-- Purchases Start -->
    <?php if (user_group_id() == 1 || has_permission('access', 'read_purchase_list')) : ?>
    <div class="tab-pane<?php if(!$active_activity_content) { echo ' active'; $active_activity_content = true;}?>" id="purchases">
      <div class="row">
        <div class="col-lg-12 col-xs-12">
          <div class="box box-default banking-box mb-0">
            <div class="box-body">
              <div class="table-responsive" style="min-height:166px">
                <?php include ROOT.'/_inc/template/partials/activities/purchases.php';?>
              </div>
            </div>
            <div class="box-footer clearfix">
              <?php if (user_group_id() == 1 || has_permission('access', 'add_purchase')) : ?>
                <a href="<?php echo root_url();?>/admin/purchase.php?box_state=open" class="btn btn-xs btn-info btn-flat"><span class="fa fa-fw fa-plus"></span> <?php echo trans('button_add_purchases'); ?></a>
              <?php endif; ?>
              <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_list')) : ?>
                <a href="<?php echo root_url();?>/admin/purchase.php" class="btn btn-xs btn-success btn-flat"><span class="fa fa-fw fa-list"></span> <?php echo trans('button_list_purchases'); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <!-- Purchases End -->

    <!-- Transfers Start -->
    <?php if (user_group_id() == 1 || has_permission('access', 'read_transfer')) : ?>
    <div class="tab-pane<?php if(!$active_activity_content) { echo ' active'; $active_activity_content = true;}?>" id="transfers">
      <div class="row">
        <div class="col-lg-12 col-xs-12">
          <div class="box box-default banking-box mb-0">
            <div class="box-body">
              <div class="table-responsive" style="min-height:166px">
                <?php include ROOT.'/_inc/template/partials/activities/transfers.php';?>
              </div>
            </div>
            <div class="box-footer clearfix">
              <?php if (user_group_id() == 1 || has_permission('access', 'add_purchase')) : ?>
                <a href="<?php echo root_url();?>/admin/transfer.php?box_state=open" class="btn btn-xs btn-info btn-flat"><span class="fa fa-fw fa-plus"></span> <?php echo trans('button_add_transfers'); ?></a>
              <?php endif; ?>
              <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_list')) : ?>
                <a href="<?php echo root_url();?>/admin/transfer.php" class="btn btn-xs btn-success btn-flat"><span class="fa fa-fw fa-list"></span> <?php echo trans('button_list_transfers'); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
     <?php endif;?>
    <!-- Transfers End -->

    <!-- Customers Start -->
    <?php if (user_group_id() == 1 || has_permission('access', 'read_customer')) : ?>
    <div class="tab-pane<?php if(!$active_activity_content) { echo ' active'; $active_activity_content = true;}?>" id="customers">
      <div class="row">
        <div class="col-lg-12 col-xs-12">
          <div class="box box-default banking-box mb-0">
            <div class="box-body">
              <div class="table-responsive" style="min-height:166px">
                <?php include ROOT.'/_inc/template/partials/activities/customers.php';?>
              </div>
            </div>
            <div class="box-footer clearfix">
              <?php if (user_group_id() == 1 || has_permission('access', 'create_customer')) : ?>
                <a href="<?php echo root_url();?>/admin/customer.php?box_state=open" class="btn btn-xs btn-info btn-flat"><span class="fa fa-fw fa-plus"></span> <?php echo trans('button_add_customer'); ?></a>
              <?php endif; ?>
              <?php if (user_group_id() == 1 || has_permission('access', 'read_customer')) : ?>
                <a href="<?php echo root_url();?>/admin/customer.php" class="btn btn-xs btn-success btn-flat"><span class="fa fa-fw fa-list"></span> <?php echo trans('button_list_customers'); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <!-- Customers End -->

    <!-- Suppliers Start -->
    <?php if (user_group_id() == 1 || has_permission('access', 'read_supplier')) : ?>
    <div class="tab-pane<?php if(!$active_activity_content) { echo ' active'; $active_activity_content = true;}?>" id="suppliers">
      <div class="row">
        <div class="col-lg-12 col-xs-12">
          <div class="box box-default banking-box mb-0">
            <div class="box-body">
              <div class="table-responsive" style="min-height:166px">
                <?php include ROOT.'/_inc/template/partials/activities/suppliers.php';?>
              </div>
            </div>
            <div class="box-footer clearfix">
              <?php if (user_group_id() == 1 || has_permission('access', 'create_supplier')) : ?>
                <a href="<?php echo root_url();?>/admin/supplier.php?box_state=open" class="btn btn-xs btn-info btn-flat"><span class="fa fa-fw fa-plus"></span> <?php echo trans('button_add_supplier'); ?></a>
              <?php endif; ?>
              <?php if (user_group_id() == 1 || has_permission('access', 'read_supplier')) : ?>
                <a href="<?php echo root_url();?>/admin/supplier.php" class="btn btn-xs btn-success btn-flat"><span class="fa fa-fw fa-list"></span> <?php echo trans('button_list_suppliers'); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <!-- Suppliers End -->

  </div>
</div>