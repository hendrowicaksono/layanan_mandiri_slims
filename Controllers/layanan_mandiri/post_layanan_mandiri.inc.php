<?php
ini_set('display_errors', '0');
global $vars;

if (isset($_SESSION['member_id']) AND isset($_SESSION['member_name'])) {
  if (isset($_POST['loan_id'])) {
    #echo 'extending nih<hr />';
    $_sql_loaninfo = 'SELECT l.* FROM loan AS l
      WHERE loan_id=\''.$_POST['loan_id'].'\'
      ';
    $_stmt_loaninfo = $vars['db']->query($_sql_loaninfo);
    $_count_loaninfo = $_stmt_loaninfo->rowCount();
    if ($_count_loaninfo === 1) {
      while($row_loaninfo = $_stmt_loaninfo->fetch(PDO::FETCH_ASSOC)) {
        $_loan_rules_id = $row_loaninfo['loan_rules_id'];
      }
    }

    if ($_loan_rules_id === 0) {
      $_sql_llist = ' SELECT l.*, m.*, i.*, b.*, mmt.* 
        FROM loan AS l, member AS m, item AS i, biblio AS b, mst_member_type AS mmt
        WHERE
        l.member_id=m.member_id
        AND l.item_code=i.item_code 
        AND i.biblio_id=b.biblio_id
        AND m.member_type_id=mmt.member_type_id
        AND is_lent=1
        AND is_return=0
        AND l.loan_id=\''.$_POST['loan_id'].'\'
        AND l.renewed < mmt.reborrow_limit 
        AND m.expire_date > '.$vars['global']['today'].'
        AND l.member_id=\''.$_SESSION['member_id'].'\'';

    } else {
      $_sql_llist = ' SELECT l.*, m.*, i.*, b.*, mlr.* 
        FROM loan AS l, member AS m, item AS i, biblio AS b, mst_loan_rules AS mlr
        WHERE
        l.member_id=m.member_id
        AND l.item_code=i.item_code 
        AND i.biblio_id=b.biblio_id
        AND is_lent=1
        AND is_return=0
        AND l.loan_id=\''.$_POST['loan_id'].'\'
        AND l.renewed < mlr.reborrow_limit 
        AND m.expire_date > '.$vars['global']['today'].'
        AND l.member_id=\''.$_SESSION['member_id'].'\'';
    }

    #echo $_sql_llist.'<hr />';
    $_stmt_llist = $vars['db']->query($_sql_llist);
    $_count_llist = $_stmt_llist->rowCount();
    if ($_count_llist === 1) {
      while($row = $_stmt_llist->fetch(PDO::FETCH_ASSOC)) {
        $_old_dd = $row['due_date'];
        $_loan_periode = $row['loan_periode'];
        $_renewed = $row['renewed'];
        $_fine_each_day = $row['fine_each_day'];
        $_member_id = $row['member_id'];
        $_title = $row['title'];
        $_item_code = $row['item_code'];
      }

      #echo '_old_dd: '.$_old_dd.'--- _loan_periode: '.$_loan_periode.'<hr />';

      $_new_dd = date('Y-m-d', strtotime('+'.$_loan_periode.' day'));
      $_new_dd_name = strtolower(date('D', strtotime('+'.$_loan_periode.' day')));
        
      do {
        $_sql_holiday = 'SELECT * FROM holiday WHERE holiday_date=\''.$_new_dd.'\'';
        $_stmt_holiday = $vars['db']->query($_sql_holiday);
        $_counter = $_stmt_holiday->rowCount();
        #echo 'Counter date: '.$_counter.'<hr />';

        $_sql_aholiday = 'SELECT * FROM holiday WHERE holiday_date IS NULL AND holiday_dayname=\''.$_new_dd_name.'\'';
        $_stmt_aholiday = $vars['db']->query($_sql_aholiday);
        $_countera = $_stmt_aholiday->rowCount();
        #echo 'Counter date name: '.$_countera.'<hr />';

        if ( ($_counter > 0) OR ($_countera > 0) ){
          $_loan_periode = $_loan_periode + 1;

          $_new_dd = date('Y-m-d', strtotime('+'.$_loan_periode.' day'));
          $_new_dd_name = strtolower(date('D', strtotime('+'.$_loan_periode.' day')));
        }
          
        $i = 0;
      } while ( ($_counter > 0) OR ($_countera > 0) );
      
      #echo $_old_dd; die(); 
      #echo $_loan_periode.'<hr />';
      #echo 'Temporary due date: '.$_new_dd.'<hr />';
      $_renewed = $_renewed + 1;
      $_sql_doextend = 'UPDATE loan SET due_date=\''.$_new_dd.'\', renewed=\''.$_renewed.'\' WHERE loan_id='.$_POST['loan_id'];
      #$_sql_doextend = 'UPDATE loan SET due_date=\''.$_new_dd.'\', renewed=\''.$_renewed.'\'';
      #echo $_sql_doextend.'<hr />';
      $_stmt_doextend = $vars['db']->query($_sql_doextend);
      $_SESSION['flash_messages'] = 'Peminjaman koleksi berhasil diperpanjang.';
      $_SESSION['flash_messages_id'] = $_POST['loan_id'];

      if ($vars['global']['today'] > $_old_dd) {
        $_uts_duedate = DateTime::createFromFormat('Y-m-d', $_old_dd);
        $uts_duedate = (int) $_uts_duedate->format('U');
        $_uts_today = DateTime::createFromFormat('Y-m-d', $vars['global']['today']);
        $uts_today = (int) $_uts_today->format('U');
        $late_days = ($uts_today - $uts_duedate) / 86400;
        $total_fines = $late_days * $_fine_each_day;
        $_sql_fines = 'INSERT INTO fines VALUES (NULL, \''.$vars['global']['today'].'\', \''.$_member_id.'\', \''.$total_fines.'\', \'0\', \'Overdue fines for item '.$_item_code.'\')';
        #echo $_sql_fines; die();
        $_stmt_fines = $vars['db']->query($_sql_fines);

      }





    }








  }
} else {
  $_POST['member_id'] = trim($_POST['member_id']);
  $_POST['member_password'] = trim($_POST['member_password']);

  if ($vars['conf']['version'] === 'cendana') {
    $_password = md5($_POST['member_password']);
    $_sql = 'SELECT * FROM member WHERE member_id=\''.$_POST['member_id'].'\' AND mpasswd=\''.$_password.'\'';
    $stmt = $vars['db']->query($_sql);
    $_member_count = $stmt->rowCount();
    if ($_member_count === 1) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['member_id'] = $row['member_id'];
        $_SESSION['member_name'] = $row['member_name'];
      }
    } else {
      $_SESSION['flash_messages'] = 'Username atau password salah!';
      header("location:layanan_mandiri");
    }
  } elseif ($vars['conf']['version'] === 'akasia') {
    $_sql = 'SELECT * FROM member WHERE member_id=\''.$_POST['member_id'].'\'';
    $stmt = $vars['db']->query($_sql);
    $_member_count = $stmt->rowCount();
    if ($_member_count === 1) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($_POST['member_password'], $row['mpasswd'])) {
          $_SESSION['member_id'] = $row['member_id'];
          $_SESSION['member_name'] = $row['member_name'];
        } else {
          $_SESSION['flash_messages'] = '1. Username atau password salah!';
          header("location:layanan_mandiri");
        }
      }
    } else {
      $_SESSION['flash_messages'] = '2. Username atau password salah!';
      header("location:layanan_mandiri");
    }
  }
}

if (isset($_SESSION['member_id']) AND isset($_SESSION['member_name'])) {
  $_sql_loan = ' SELECT l.*, m.*, i.*, b.* 
    FROM loan AS l, member AS m, item AS i, biblio AS b
    WHERE
    l.member_id=m.member_id
    AND l.item_code=i.item_code 
    AND i.biblio_id=b.biblio_id
    AND is_lent=1
    AND is_return=0
    AND l.member_id=\''.$_SESSION['member_id'].'\'';
  $_stmt_loan = $vars['db']->query($_sql_loan);
  $_count_loan = $_stmt_loan->rowCount();
  #echo $_count_loan.'<hr />'; die('sdsdsd');
  if ($_count_loan > 0) {
    $_c = 0;
    #$_today = date('Y-m-d');
    while($row = $_stmt_loan->fetch(PDO::FETCH_ASSOC)) {
      $_loanlist[$_c]['loan_id'] = $row['loan_id'];
      $_loanlist[$_c]['item_code'] = $row['item_code'];
      $_loanlist[$_c]['title'] = $row['title'];
      $_loanlist[$_c]['loan_date'] = $row['loan_date'];
      #$_loanlist[$_c]['loan_id'] = $row['loan_id'];
      $_loanlist[$_c]['due_date'] = $row['due_date'];
      #$_loanlist[$_c]['today'] = $_today;
      if ($vars['global']['today'] > $_loanlist[$_c]['due_date']) {
        $_uts_duedate = DateTime::createFromFormat('Y-m-d', $row['due_date']);
        $uts_duedate = (int) $_uts_duedate->format('U');
        $_uts_today = DateTime::createFromFormat('Y-m-d', $vars['global']['today']);
        $uts_today = (int) $_uts_today->format('U');
        $late_days = ($uts_today - $uts_duedate) / 86400;
        $_loanlist[$_c]['status'] = 'Terlambat selama: '.$late_days.' hari.';
      } else {
        $_loanlist[$_c]['status'] = 'Tidak terlambat';
      }

      $_loanlist[$_c]['renewed'] = $row['renewed'];

      if ($row['loan_rules_id'] === 0) {
      #echo $row['loan_rules_id'].'<hr />';
        $_sql_getrules = 'SELECT mmt.*, l.*, m.*
          FROM mst_member_type AS mmt, loan AS l, member AS m
          WHERE
          mmt.member_type_id=m.member_type_id
          AND l.member_id=m.member_id
          ';
      } else {
        $_sql_getrules = 'SELECT mlr.*, l.*
          FROM mst_loan_rules AS mlr, loan AS l
          WHERE
          mlr.loan_rules_id=l.loan_rules_id
          ';
      }
      $_stmt_getrules = $vars['db']->query($_sql_getrules);
      $_count_getrules = $_stmt_getrules->rowCount();#echo $_count_getrules;
      if ($_count_getrules > 0) {
        $_loan_id = $row['loan_id'];
        $_SESSION[$_loan_id]['fine_each_day'] = false;
        while($row_getrules = $_stmt_getrules->fetch(PDO::FETCH_ASSOC)) {
          $_loanlist[$_c]['loan_periode'] = $row_getrules['loan_periode'];#echo 'neh: '.$row_getrules['loan_periode'];
          $_loanlist[$_c]['reborrow_limit'] = $row_getrules['reborrow_limit'];
          $_loanlist[$_c]['fine_each_day'] = $row_getrules['fine_each_day'];
          $_SESSION[$_loan_id]['fine_each_day'] = $row_getrules['fine_each_day'];
        }
      }

      if ( ($row['renewed'] < $_loanlist[$_c]['reborrow_limit']) AND ($vars['global']['today'] < $row['expire_date']) ) {
        $_loanlist[$_c]['extended'] = TRUE;
      } else {
        $_loanlist[$_c]['extended'] = FALSE;
      }
      $_c++;
    }
  } else {
    $_loanlist = FALSE;
  }
  require THEMES_DIR.'/'.$vars['conf']['themes'].'/loan_list.inc.php';


}


?>