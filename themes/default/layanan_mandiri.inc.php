<?php
require_once 'header.inc.php';

if (isset($_SESSION['flash_messages'])) {
  echo '<div class="uk-width-1-1 uk-alert uk-alert-warning uk-text-center">'.$_SESSION['flash_messages'].'</div>';
  unset($_SESSION['flash_messages']);
}
?>

  <div class="uk-width-1-1 uk-text-center uk-block">
    <form class="uk-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <fieldset data-uk-margin="">
        <legend><h2 class="uk-h2">Cek Peminjaman Mandiri</h2></legend>
        <input type="text" placeholder="No Anggota" name="member_id" class="uk-margin-small-top uk-form-large" required>
        <input type="password" placeholder="Password input" name="member_password" class="uk-margin-small-top uk-form-large" required>

        <button class="uk-button uk-button-primary uk-button-large">Library Member Login</button>

    </fieldset>
    </form>
  </div>


<?php
require_once 'footer.inc.php';
?>
