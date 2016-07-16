<?php
use Respect\Validation\Validator as v;
global $vars;

print_r($_POST);
echo '<hr />';
$member = (object) $_POST;
print_r($member);
echo '<hr />';
echo $member->member_name;
echo '<hr />';

$memberValidator = 
  v::attribute('member_name', v::stringType()->length(3,100))
   ->attribute('birth_date', v::date('d/m/Y'))
   ->attribute('member_type_id', v::intVal())
   ->attribute('gender', v::intVal()->between(0, 1))
   ->attribute('member_address', v::stringType())
   ->attribute('postal_code', v::digit()->length(5,5))
   ->attribute('member_mail_address', v::stringType())
   ->attribute('member_phone', v::phone())
   ->attribute('pin', v::stringType())
   ->attribute('member_email', v::email())
   ->attribute('mpasswd', v::stringType())
   ->attribute('rtmpasswd', v::stringType())
   ;


#$userValidator = v::attribute('name', v::stringType()->length(1,32))
#                  ->attribute('birth_date', v::date('d/m/Y');
#$userValidator->validate($user); // true

if ($memberValidator->validate($member)) {
  echo '_Data valid!';
  if ($member->mpasswd === $member->rtmpasswd) {
    $is_member_valid = TRUE;
    $_mpasswd = md5($member->mpasswd);
    $_member_id = 'OL-'.mt_rand(10000,999999999999999);
    $_today = $vars['global']['today'];
    if (isset($member->member_fax)) {
      if (v::phone()->validate($member->member_fax)) {
        $_member_fax = $member->member_fax;
      } else {
        $_member_fax = NULL;
      }
    } else {
      $_member_fax = NULL;
    }
    $_sql_insmember = '
      INSERT INTO member (
      member_id,
      member_name, gender, birth_date, 
      member_type_id, member_address,
      member_mail_address, member_email, 
      postal_code, is_new, pin,
      member_phone, member_fax, 
      member_since_date, register_date,
      expire_date, is_pending, mpasswd, 
      input_date, last_update
      ) VALUES (
      \''.$_member_id.'\',
      \''.$member->member_name.'\', \''.$member->gender.'\', \''.$member->birth_date.'\', 
      \''.$member->member_type_id.'\', \''.$member->member_address.'\',
      \''.$member->member_mail_address.'\', \''.$member->member_email.'\',
      \''.$member->postal_code.'\', \'1\', \''.$member->pin.'\',
      \''.$member->member_phone.'\', \''.$_member_fax.'\',
      \''.$_today.'\', \''.$_today.'\',
      \''.$_today.'\', \'1\', \''.$_mpasswd.'\',
      \''.$_today.'\', \''.$_today.'\'
      )
    ';
    echo '<hr />'.$_sql_insmember;
    $_stm_insmember = $vars['db']->query($_sql_insmember);
  } else {
    $is_member_valid = FALSE;
    #$_SESSION['flash_messages'] = 'Data password yang diinput belum benar. Silahkan <button type="button" onclick="goBack()" class="uk-button uk-margin-small-bottom">diperbaiki kembali.</button>';
    $_SESSION['flash_messages'] = 'Data password yang diinput belum benar. Silahkan <a href="#" onclick="goBack()">diperbaiki kembali</a>.';
    #echo 'Data password yang diinput belum benar. Silahkan <button onclick="goBack()">diperbaiki</button>';
  }

} else {
  #echo 'Data yang diinput belum benar. Silahkan <button onclick="goBack()">diperbaiki</button>';
  $_SESSION['flash_messages'] = 'Data yang diinput belum benar. Silahkan <a href="#" onclick="goBack()">diperbaiki lebih lanjut</a>.';
}

?>
<script>
function goBack() {
    window.history.back();
}
</script>
<?php
require THEMES_DIR.'/'.$vars['conf']['themes'].'/registration_confirmation.inc.php';
?>
