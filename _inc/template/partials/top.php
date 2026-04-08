<header class="main-header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <a href="dashboard.php" class="logo">
    <span class="logo-mini">
      <b title="<?php echo store('name');?>">
        <?php echo store('name')[0]; ?>
      </b>
      <?php echo mb_substr(store('name'), -1); ?>
    </span>
    <span class="logo-lg">
      <b title="<?php echo store('name');?>">
        <?php echo limit_char(store('name'), 20); ?>
      </b>
    </span>
  </a>
  <nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">#</span>
    </a>
    <ul class="nav navbar-nav navbar-left">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="<?php echo $user->getAllPreference()['language'];?>">
          <img src="../assets/itsolution24/img/flags/<?php echo $user->getAllPreference()['language'];?>.png" alt="<?php echo $user->getAllPreference()['language'];?>"></a>
        <ul class="dropdown-menu"> 
          <?php foreach(get_langs() as $the_lang): if($user->getAllPreference()['language'] == $the_lang['slug']) continue; ?>
            <li>
              <a href="<?php echo $_SERVER['PHP_SELF'];?>?lang=<?php echo $the_lang['code'];?>" title="<?php echo trans('text_'.$the_lang['slug']); ?>">
                <img src="../assets/itsolution24/img/flags/<?php echo $the_lang['code'];?>.png" class="language-img"> &nbsp;&nbsp;<?php echo trans('text_'.$the_lang['slug']); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </li>
      <li>
        <a href="#" onClick="return false;" id="live_datetime"></a>
      </li>
    </ul>
    <!-- navbar custome menu start -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <?php if (in_array(current_nav(), array('invoice','product_details','report_collection','sell_return','purchase_return','report_sell_itemwise','report_sell_categorywise','report_sell_supplierwise','report_purchase_itemwise','report_purchase_categorywise','report_purchase_supplierwise','report_customer_due_collection','report_payment','expense','expense_monthwise','income_monthwise','supplier_profile','customer_profile','report_overview', 'report_income_and_expense', 'report_profit_and_loss', 'report_customer_due_collection', 'report_supplier_due_paid', 'analysis','bank_transactions','bank_transfer','sms_report', 'loan', 'loan_summary', 'purchase','report_sell_tax', 'report_sell_payment','report_purchase_payment','report_purchase_tax','report_tax_overview','giftcard_topup','purchase_log','sell_log','customer_transaction','transfer','installment','report_cashbook', 'quotation', 'installment_payment'))) : ?>
          <?php if (user_group_id() == 1 || has_permission('access', 'filtering')) : ?>
            <li class="user user-menu">
              <a id="show-filter-box" href="#">
                <svg class="svg-icon"><use href="#icon-search-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>
        <?php if (user_group_id() == 1 || has_permission('access', 'create_sell_invoice')) : ?>
          <li class="user user-menu<?php echo current_nav() == 'pos' ? ' active' : null; ?> sell-btn">
            <a href="pos.php" title="<?php echo trans('text_pos'); ?>"> 
              <svg class="svg-icon"><use href="#icon-pos-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
              <span class="text">
                <?php echo trans('menu_pos'); ?>
              </span>
            </a>
          </li>
        <?php endif; ?>
        <?php if (current_nav() == 'pos') : ?>
          <li>
            <a id="keyboard-shortcut" ng-click="keyboardShortcutModal()" onClick="return false;" href="#" title="<?php echo trans('text_keyboard_shortcut'); ?>">
              <svg class="svg-icon"><use href="#icon-keyboard-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
            </a>
          </li> 
        <?php endif; ?>
        <?php if (current_nav() == 'pos') : ?>
        <?php if (user_group_id() == 1 || has_permission('access', 'read_holding_order')) : ?>
            <li>
              <a id="holding-order" ng-click="holdingOrderDetailsModal()" onClick="return false;" href="#" title="<?php echo trans('text_holding_order'); ?>">
                <svg class="svg-icon"><use href="#icon-hold-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
                &nbsp;<span class="label label-warning"><?php echo total_holding_order_today();?></span>
              </a>
            </li> 
          <?php endif; ?>
        <?php endif; ?>
        <?php if (device_type() == 'computer'):?>
        <?php if (user_group_id() == 1 || has_permission('access', 'read_cashbook_report')) : ?>
          <li class="user user-menu<?php echo current_nav() == 'report_cashbook' ? ' active' : null; ?>">
            <a href="report_cashbook.php" title="<?php echo trans('text_cashbook_report'); ?>">
              <svg class="svg-icon"><use href="#icon-register-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
                <span class="text">
                  <?php echo trans('menu_cashbook'); ?>
                </span>
            </a>
          </li> 
        <?php endif; ?>
        <?php endif; ?>
        <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_list')) : ?>
          <li class="user user-menu<?php echo current_nav() == 'invoice' ? ' active' : null; ?>">
            <a href="invoice.php" title="<?php echo trans('text_invoice'); ?>">
              <svg class="svg-icon"><use href="#icon-invoice-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
              <span class="text">
                <?php echo trans('menu_invoice'); ?>
              </span>
              &nbsp;<span class="label label-warning"><?php echo total_invoice_today();?></span>
            </a>
          </li>
        <?php endif; ?>
        <?php if (user_group_id() == 1 || has_permission('access', 'read_user_preference')) : ?>
          <li id="user-preference" class="user user-menu<?php echo current_nav() == 'user_preference' ? ' active' : null; ?> sell-btn">
            <a href="user_preference.php?store_id=<?php echo store_id(); ?>" title="<?php echo trans('text_user_preference'); ?>">
              <svg class="svg-icon"><use href="#icon-heart-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
            </a>
          </li>
        <?php endif; ?>
        <?php if (user_group_id() == 1 || has_permission('access', 'read_store')) : ?>
          <li class="user user-menu<?php echo current_nav() == 'store_single' ? ' active' : null; ?> sell-btn">
            <a href="store_single.php" title="<?php echo trans('text_settings'); ?>">
              <svg class="svg-icon"><use href="#icon-settings-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
            </a>
          </li>
        <?php endif; ?>
        <?php if (user_group_id() == 1 || has_permission('access', 'read_stock_alert')) : ?>
          <li class="user user-menu<?php echo current_nav() == 'stock_alert' ? ' active' : null; ?>">
            <a href="stock_alert.php" title="<?php echo trans('text_stock_alert'); ?>">
              <svg class="svg-icon"><use href="#icon-alert-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
              <?php if (total_out_of_stock() > 0) : ?>
                <span class="label label-warning">
                  <?php echo total_out_of_stock(); ?></span>
              <?php endif; ?>
            </a>
          </li>
        <?php endif; ?>

        <?php if (get_preference('expiry_yes') && (user_group_id() == 1 || has_permission('access', 'read_expired_product'))) : ?>
          <li class="user user-menu<?php echo current_nav() == 'expired' ? ' active' : null; ?>">
            <a href="expired.php" title="<?php echo trans('text_expired'); ?>">
              <svg class="svg-icon"><use href="#icon-expired-<?php echo $user->getPreference('base_color', 'black'); ?>"></svg>
              <?php if (total_expired() > 0) : ?>
                <span class="label label-warning">
                  <?php echo total_expired(); ?>
                </span>
              <?php endif; ?>
            </a>
          </li> 
        <?php endif; ?>
        <li class="user user-menu">
     
        </li>
        <li>
          <a id="togglingfullscreen" onClick="toggleFullScreenMode(); return false;" href="#" title="<?php echo trans('text_fullscreen'); ?>">
            <span class="fa fa-fw fa-expand"></span>
          </a>
        </li>
        <li id="scrolling-sidebar" class="user user-menu">
          <a href="#" title="<?php echo trans('text_reports'); ?>" data-toggle="scrolling-sidebar" data-width="350">
            <i class="fa fa-square"></i>
          </a>
        </li> 
        <li id="screen-lock" class="user user-menu">
          <a href="../lockscreen.php" title="<?php echo trans('text_lockscreen'); ?>">
            <i class="fa fa-lock"></i>
          </a>
        </li> 
        <li class="user user-menu">
          <a id="logout" href="logout.php" title="<?php echo trans('text_logout'); ?>">
            <i class="fa fa-sign-out"></i>
          </a>
        </li> 
      </ul>
    </div>
  </nav>
</header>