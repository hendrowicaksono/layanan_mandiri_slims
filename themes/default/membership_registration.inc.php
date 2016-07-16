<?php
require_once 'header.inc.php';

if (isset($_SESSION['flash_messages'])) {
  echo '<div class="uk-width-1-1 uk-alert uk-alert-warning uk-text-center">'.$_SESSION['flash_messages'].'</div>';
  unset($_SESSION['flash_messages']);
}
?>

<div class="uk-grid">
    <div class="uk-width-1-2">...</div>
    <div class="uk-width-1-2">...</div>
</div>

      <div class="uk-grid">
        <div class="uk-width-1-2">...</div>
        <div class="uk-width-1-2">
          ...
        </div>
      </div>

<form class="uk-form uk-form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <div class="uk-grid">
    <div class="uk-width-1-1">

      <div class="uk-grid">
        <div class="uk-width-1-1">
          <h2 class="">Registrasi Anggota</h2>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Member name</label>
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <i class="uk-icon-male"></i>
          <input type="text" id="member_name" name="member_name" placeholder="Nama calon anggota perpustakaan" class="uk-form-width-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Tanggal lahir</label>
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <i class="uk-icon-calendar"></i>
          <input type="text" id="birth_date" name="birth_date" data-uk-datepicker="{format:'DD/MM/YYYY'}" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Kategori</label>
        </div>
        <div class="uk-width-8-10">
          <select id="member_type_id" name="member_type_id" required>
          <?php
          foreach ($_membtype as $key => $value) {
          ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
          <?php
          }
          ?>
          </select>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Jenis Kelamin</label>
        </div>
        <div class="uk-width-8-10">
          <label><input type="radio" name="gender" value="1"> Laki-laki</label><br />
          <label><input type="radio" name="gender" value="0" checked> Perempuan</label>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Alamat</label>
        </div>
        <div class="uk-width-8-10">
          <textarea id="member_address" name="member_address" cols="70" rows="5" placeholder="Alamat" required></textarea>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Kodepos</label>
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <i class="uk-icon-calendar"></i>
          <input type="text" id="postal_code" name="postal_code" placeholder="Kodepos" class="">
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Alamat surat menyurat</label>
        </div>
        <div class="uk-width-8-10">
          <textarea id="member_mail_address" name="member_mail_address" cols="70" rows="5" placeholder="Alamat surat menyurat" required></textarea>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">No. Telepon</label>
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <i class="uk-icon-phone"></i>
          <input type="text" id="member_phone" name="member_phone" placeholder="Nomor telepon" class="uk-form-width-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Nomor Faksimili</label>
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <i class="uk-icon-fax"></i>
          <input type="text" id="member_fax" name="member_fax" placeholder="Nomor fax" class="uk-form-width-large">
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Nomor Identitas</label>
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <i class="uk-icon-sort-numeric-asc"></i>
          <input type="text" id="pin" name="pin" placeholder="Nomor identitas" class="uk-form-width-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Email</label>
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <i class="uk-icon-envelope-o"></i>
          <input type="text" id="member_email" name="member_email" placeholder="Alamat email" class="uk-form-width-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Password</label>
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <i class="uk-icon-user-secret"></i>
          <input type="password" id="mpasswd" name="mpasswd" placeholder="Password" class="uk-form-width-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10 uk-text-right">
          <label class="uk-form-label">Retype Password</label>
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <i class="uk-icon-user-secret"></i>
          <input type="password" id="rtmpasswd" name="rtmpasswd" placeholder="Retype Password" class="uk-form-width-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-2-10">&nbsp;
        </div>
        <div class="uk-width-8-10 uk-form-icon">
          <button type="submit" class="uk-button uk-margin-small-bottom">Kirim Registrasi</button>
        </div>
      </div>


    </div>
  </div>
</form>
<!-- ---------------------------------------------------------- -->

<?php
require_once 'footer.inc.php';
?>
