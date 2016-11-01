<?php
if (!defined('MANDIRI')) {
  die(':p');
}

$vars['conf']['themes'] = 'default';
$vars['global']['today'] = date('Y-m-d');
$vars['global']['date']['abb'] = strtolower(date('D'));
$vars['conf']['version'] = 'akasia'; #'cendana' or 'akasia'

#$vars['db'] = new PDO('mysql:host=localhost; dbname=dev_slims7; charset=utf8mb4', 'root', 's0beautifulday');
$vars['db'] = new PDO('mysql:host=localhost; dbname=unj_v2; charset=utf8mb4', 'root', 's0beautifulday');
$vars['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$vars['db']->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

?>