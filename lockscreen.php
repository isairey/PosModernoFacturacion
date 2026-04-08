<?php 
ob_start();
session_start();
include ("_init.php");

// Redirect, If User Not Logged In
if (!isset($session->data['username'])) {
  if (!$user->isLogged()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
  }
  $session->data['email'] = user('email');
  $session->data['username'] = user('username');
  $session->data['ref_url'] = isset($session->data['ref_url']) ? $session->data['ref_url'] : '';
  $user->logout();
}

$error = '';
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['password'])) {
  try {

    if (!$request->post['password']) {
      throw new Exception(trans('error_invalid_password'));
    }

    if (!$session->data['username']) {
      throw new Exception(trans('error_invalid_username'));
    }

    $email = $session->data['email'];
    $password = $request->post['password'];

    // Attempt to Log In
    if ($user->login($email, $password)) {
      $url = $session->data['ref_url'] ? $session->data['ref_url'] : root_url() . '/admin/dashboard.php';
      redirect($url);
    } 

    $error = trans('error_invalid_username_or_password');

  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $document->langTag($active_lang);?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo trans('text_lockscreen');?><?php echo ' | '.store('name') ? store('name') : ''; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!--Set Favicon-->
  <?php if ($store->get('favicon')): ?>
      <link rel="shortcut icon" href="assets/itsolution24/img/logo-favicons/<?php echo $store->get('favicon'); ?>">
  <?php else: ?>
      <link rel="shortcut icon" href="assets/itsolution24/img/logo-favicons/nofavicon.png">
  <?php endif; ?>

  <!-- All CSS -->

  <?php if (DEMO || USECOMPILEDASSET) : ?>

    <!-- Login Combined CSS -->
    <link type="text/css" href="assets/itsolution24/cssmin/login.css" rel="stylesheet">

  <?php else : ?>

    <!-- Bootstrap CSS -->
    <link type="text/css" href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Perfect Scroll CSS -->
    <link type="text/css" href="assets/perfectScroll/css/perfect-scrollbar.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link type="text/css" href="assets/toastr/toastr.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link type="text/css" href="assets/itsolution24/css/theme.css" rel="stylesheet">

    <!-- Login CSS -->
    <link type="text/css" href="assets/itsolution24/css/login.css" rel="stylesheet">

  <?php endif; ?>

  <!-- All JS -->

  <script type="text/javascript">
    var baseUrl = "<?php echo root_url(); ?>";
    var adminDir = "<?php echo ADMINDIRNAME; ?>";
    var refUrl = "<?php echo isset($session->data['ref_url']) ? $session->data['ref_url'] : ''?>";
  </script>

  <?php if (DEMO || USECOMPILEDASSET) : ?>

    <!-- Login Combined JS -->
    <script src="assets/itsolution24/jsmin/login.js"></script>

  <?php else : ?>

    <!-- jQuery JS  -->
    <script src="assets/jquery/jquery.min.js" type="text/javascript"></script>

    <!-- Bootstrap JS -->
    <script src="assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- Perfect Scroll JS -->
    <script src="assets/perfectScroll/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>

    <!-- Toastr JS -->
    <script src="assets/toastr/toastr.min.js" type="text/javascript"></script>

    <!-- Common JS -->
    <script src="assets/itsolution24/js/common.js"></script>

    <!-- Login JS -->
    <script src="assets/itsolution24/js/login.js"></script>

  <?php endif; ?>

</head>
<body class="lockscreen">

  <?php if ($error):?>
    <div class="alert alert-danger text-center" style="padding:5px;">
    <?php echo $error;?>
    </div>
  <?php endif;?>

  <div class="lockscreen-wrapper">
    <h4 class="text-center text-green">MODERN POS <small class="text-muted">v<?php echo settings('version');?></small></h4>
    <br>
    <div class="lockscreen-name"><?php echo $session->data['username'];?></div>
    <div class="lockscreen-item">
      <div class="lockscreen-image">
        <?php if (get_the_user(1, 'user_image') && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.get_the_user(1, 'user_image')) && file_exists(FILEMANAGERPATH.get_the_user(1, 'user_image'))) || (is_file(DIR_STORAGE . 'users' . get_the_user(1, 'user_image')) && file_exists(DIR_STORAGE . 'users' . get_the_user(1, 'user_image'))))) : ?>
          <div class="user-thumbnail">
            <img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/users'; ?>/<?php echo get_the_user(1, 'user_image'); ?>" style="max-width:100%;max-height:100%;">
          </div>
        <?php else : ?>
          <img src="<?php echo root_url();?>/assets/itsolution24/img/nopeople.png">
        <?php endif; ?>
      </div>
      <form class="lockscreen-credentials" action="" method="post" autocomplete="off">
        <div class="input-group">
          <input class="form-control" type="password" name="password" placeholder="password" autocomplete="off" autofocus>
          <div class="input-group-btn">
            <button type="submit" class="btn">&rarr;</button>
          </div>
        </div>
      </form>
    </div>
    <div class="help-block text-center">
      Enter your password to retrieve your session
    </div>
    <div class="text-center">
      <a href="index.php" style="font-size:1.8rem;color:green;text-decoration:underline;"><small>or</small> Sign in as a different user</a>
    </div>
    <div class="lockscreen-footer text-center">
      <div class="copyright" style="font-size:1.2rem;">Copyright © <?php echo date('Y'); ?> <a href="#">Shopify EX</a></div>
    </div>
  </div>

<noscript>You need to have javascript enabled in order to use <strong><?php echo store('name');?></strong>.</noscript>
</body>
</html>