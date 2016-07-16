<?php
require_once 'header.inc.php';
?>

<?php
if (isset($_SESSION['flash_messages'])) {
  echo '<div class="uk-width-1-1 uk-alert uk-alert-warning uk-text-center">'.$_SESSION['flash_messages'].'</div>';
}
?>


<?php
unset($_SESSION['flash_messages']);
unset($_SESSION['flash_messages_id']);
require_once 'footer.inc.php';
?>
