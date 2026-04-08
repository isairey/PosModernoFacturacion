<?php
session_start();
include ("../_init.php");
$user->logout();

// REDIRECT IF USER LOGGED IN
if (!is_loggedin()) {
  redirect(root_url());
}