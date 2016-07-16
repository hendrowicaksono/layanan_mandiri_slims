<?php
session_destroy();
session_unset();
global $vars;

$_membtype = array();
$_sql_membtype = 'SELECT * FROM mst_member_type';
$_stm_membtype = $vars['db']->query($_sql_membtype);
$_cou_membtype = $_stm_membtype->rowCount();
if ($_cou_membtype === 1) {
  while($row_membtype  = $_stm_membtype->fetch(PDO::FETCH_ASSOC)) {
    $_member_type_id = $row_membtype['member_type_id'];
    $_membtype[$_member_type_id] = $row_membtype['member_type_name'];
  }
}

require THEMES_DIR.'/'.$vars['conf']['themes'].'/membership_registration.inc.php';

?>