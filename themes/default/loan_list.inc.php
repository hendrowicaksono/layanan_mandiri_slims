<?php
require_once 'header.inc.php';
?>

<?php
if (isset($_SESSION['flash_messages'])) {
  echo '<div class="uk-width-1-1 uk-alert uk-alert-warning uk-text-center">'.$_SESSION['flash_messages'].'</div>';
}
?>
<div class="uk-grid">
  <div class="uk-width-1-1 uk-text-left uk-block">
  <?php if ($_loanlist) { ?>
    <table class="uk-table">
      <caption><h1 class="uk-text-center">Daftar Peminjaman</h1></caption>
      <tr>
        <th>Item Code</th>
        <th>Judul</th>
        <th>Loan Date</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Bisa diperpanjang</th>
        <!--<th>Debug Info</th>-->
      </tr>
      <?php foreach ($_loanlist as $_k => $_v) { ?>
      <tr>
        <td><?php echo $_v['item_code']; ?></td>
        <td><?php echo $_v['title']; ?></td>
        <td><?php echo $_v['loan_date']; ?></td>
        <td><?php 
        if ( (isset($_SESSION['flash_messages'])) AND (($_SESSION['flash_messages_id']) == $_v['loan_id']) ) 
          {echo '<div class="uk-alert uk-alert-warning">';}
         echo $_v['due_date']; ?></td>
        <?php if ( (isset($_SESSION['flash_messages'])) AND (($_SESSION['flash_messages_id']) == $_v['loan_id']) ) {echo '</div>';} ?>
        <td><?php echo $_v['status']; ?></td>
        <td><?php 
        #echo $_v['extended'];
        if ($_v['extended']) { ?>
          <form id="<?php echo $_v['loan_id']; ?>" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <button class="uk-button uk-button-primary" type="submit" name="loan_id" value="<?php echo $_v['loan_id']; ?>">Extend Loan</button>
          </form>
        <?php
          #echo 'Extendable';
        } else {
          echo '<i>Max Loan extend.</i>';
        }
        ?></td>
        <!--<td>Today: <?php echo $vars['global']['today']; ?> 
        --- Renewed: <?php echo $_v['renewed']; ?><br />
        --- Loan Periode: <?php echo $_v['loan_periode']; ?><br />
        --- Reborrow Limit: <?php echo $_v['reborrow_limit']; ?><br />
          
        </td>-->
      </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <table class="uk-table">
      <caption><h1 class="uk-text-center">Daftar Peminjaman</h1></caption>
      <tr><td><div class="uk-alert uk-text-center">Tidak ada data peminjaman.</div></td></tr>
    </table>
  <?php } ?>
  </div>
</div>

<?php
unset($_SESSION['flash_messages']);
unset($_SESSION['flash_messages_id']);
require_once 'footer.inc.php';
?>
