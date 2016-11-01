<?php
require_once 'header.inc.php';

if (isset($_SESSION['flash_messages'])) {
  echo '<div class="uk-width-1-1 uk-alert uk-alert-warning uk-text-center">'.$_SESSION['flash_messages'].'</div>';
  unset($_SESSION['flash_messages']);
}
?>


<div class="uk-grid">
  <div class="uk-width-1-1 uk-text-center uk-block">


<form class="uk-form uk-form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<!--
  <div class="uk-grid">
    <div class="uk-width-1-1">
-->
      <div class="uk-grid">
        <div class="uk-width-1-1">
          <h2 class="">Registrasi Anggota</h2>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Member name
        </div>
        <div class="uk-width-6-10 uk-form-icon uk-text-left">
          <i class="uk-icon-male"></i>
          <input type="text" id="member_name" name="member_name" placeholder="Nama calon anggota perpustakaan" class="uk-form-width-large uk-text-left uk-form-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Tanggal lahir
        </div>
        <div class="uk-width-6-10 uk-form-icon uk-text-left">
          <i class="uk-icon-calendar"></i>
          <input type="text" id="birth_date" name="birth_date" data-uk-datepicker="{format:'DD/MM/YYYY'}" class=" uk-text-left uk-form-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Kategori
        </div>
        <div class="uk-width-6-10 uk-text-left">
          <select id="member_type_id" name="member_type_id" class="uk-form-large" required>
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
        <div class="uk-width-4-10 uk-text-right">
          Jenis Kelamin
        </div>
        <div class="uk-width-6-10 uk-text-left uk-form-large">
          <label><input type="radio" name="gender" value="1"> Laki-laki</label><br />
          <label><input type="radio" name="gender" value="0" checked> Perempuan</label>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Alamat
        </div>
        <div class="uk-width-6-10 uk-text-left">
          <textarea id="member_address" name="member_address" cols="70" rows="5" placeholder="Alamat" required></textarea>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Kodepos
        </div>
        <div class="uk-width-6-10 uk-form-icon uk-text-left">
          <i class="uk-icon-calendar"></i>
          <input type="text" id="postal_code" name="postal_code" placeholder="Kodepos" class=" uk-form-large">
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Alamat surat menyurat
        </div>
        <div class="uk-width-6-10 uk-text-left">
          <textarea id="member_mail_address" name="member_mail_address" cols="70" rows="5" placeholder="Alamat surat menyurat" class=" uk-form-large" required></textarea>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          No. Telepon
        </div>
        <div class="uk-width-6-10 uk-form-icon uk-text-left">
          <i class="uk-icon-phone"></i>
          <input type="text" id="member_phone" name="member_phone" placeholder="Nomor telepon" class="uk-form-width-large uk-form-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Nomor Faksimili
        </div>
        <div class="uk-width-6-10 uk-form-icon uk-text-left">
          <i class="uk-icon-fax"></i>
          <input type="text" id="member_fax" name="member_fax" placeholder="Nomor fax" class="uk-form-width-large uk-form-large">
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Nomor Identitas
        </div>
        <div class="uk-width-6-10 uk-form-icon uk-text-left">
          <i class="uk-icon-sort-numeric-asc"></i>
          <input type="text" id="pin" name="pin" placeholder="Nomor identitas" class="uk-form-width-large uk-form-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Email
        </div>
        <div class="uk-width-6-10 uk-form-icon uk-text-left">
          <i class="uk-icon-envelope-o"></i>
          <input type="text" id="member_email" name="member_email" placeholder="Alamat email" class="uk-form-width-large uk-form-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Password
        </div>
        <div class="uk-width-6-10 uk-form-icon uk-text-left">
          <i class="uk-icon-user-secret"></i>
          <input type="password" id="mpasswd" name="mpasswd" placeholder="Password" class="uk-form-width-large uk-form-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-4-10 uk-text-right">
          Retype Password
        </div>
        <div class="uk-width-6-10 uk-form-icon uk-text-left">
          <i class="uk-icon-user-secret"></i>
          <input type="password" id="rtmpasswd" name="rtmpasswd" placeholder="Retype Password" class="uk-form-width-large uk-form-large" required>
        </div>
      </div>

      <div class="uk-grid">
        <div class="uk-width-10-10 uk-form-icon">
        <button class="uk-button uk-button-primary uk-button-large">Kirim Registrasi</button>
        </div>
      </div>

<!--
    </div>
  </div>
-->
</form>

</div>
</div>

<!-- ---------------------------------------------------------- -->

<?php
require_once 'footer.inc.php';
?>
