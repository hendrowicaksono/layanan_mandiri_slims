<?php
  session_destroy();
  session_unset();
  global $vars;
  require THEMES_DIR.'/'.$vars['conf']['themes'].'/contact.inc.php';

?>