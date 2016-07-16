<?php
#echo dirname($_SERVER['SCRIPT_NAME']).'<hr />';
#echo getcwd().'<hr />';
session_start();

#default value
define('BASE_PATH', __DIR__);
define('THEMES_DIR', BASE_PATH.'/themes');

define('HTTP_PATH', dirname($_SERVER['SCRIPT_NAME']));
define('HTTP_THEMES_DIR', HTTP_PATH.'/themes');

$vars['conf']['themes'] = 'default';

$vars['global']['today'] = date('Y-m-d');

#$_tomorrow = date_create($vars['global']['today']);
#date_add($_tomorrow, date_interval_create_from_date_string('1 days'));
#$_tomorrow = date_format($_tomorrow, 'Y-m-d');
#$_tomorrow = date('Y-m-d', strtotime('+1 day'));
#$vars['global']['tomorrow']['date'] = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
#echo $_tomorrow; die();
#$vars['global']['tomorrow']['abb'] = date('Y-m-d');


$vars['global']['date']['abb'] = strtolower(date('D'));


$vars['db'] = new PDO('mysql:host=localhost; dbname=dev_slims7; charset=utf8mb4', 'root', 's0beautifulday');
$vars['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$vars['db']->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


require 'router/src/Nanite.php';

// Use / for the main/index page.
Nanite::get('/', function(){
    echo "Front page";
});

// All routes start with /
Nanite::get('/about', function(){
    echo "About page";
});

Nanite::get('/layanan_mandiri', function(){
  #echo "Layanan Mandiri";
  session_destroy();
  session_unset();
  global $vars;
  require THEMES_DIR.'/'.$vars['conf']['themes'].'/layanan_mandiri.inc.php';
});

Nanite::post('/layanan_mandiri', function(){
  #echo "Naaah Layanan Mandiri";
  global $vars;

  if (isset($_SESSION['member_id']) AND isset($_SESSION['member_name'])) {
    if (isset($_POST['loan_id'])) {
      echo 'extending nih<hr />';
      #$_sql = 'UPDATE loan SET due_date=\'\'';
      #if ( ($row['renewed'] < $row['reborrow_limit']) AND ($vars['global']['today'] < $row['expire_date']) ) {
      #$_sql = 'SELECT * FROM loan WHERE ';
      #$_sql .= 'loan_id=\''.$_POST['loan_id'];
      #$_sql .= '\' AND member_id=\''.$_SESSION['member_id'].'\' ';
      #$_sql .= 'AND is_lent=1 AND is_return=0';
      #echo $_sql;
      $_sql_llist = ' SELECT l.*, m.*, i.*, b.*, mlr.* 
        FROM loan AS l, member AS m, item AS i, biblio AS b, mst_loan_rules AS mlr
        WHERE
        l.member_id=m.member_id
        AND l.item_code=i.item_code 
        AND i.biblio_id=b.biblio_id
        AND l.loan_rules_id=mlr.loan_rules_id
        AND is_lent=1
        AND is_return=0
        AND l.loan_id=\''.$_POST['loan_id'].'\'
        AND l.renewed < mlr.reborrow_limit 
        AND m.expire_date > '.$vars['global']['today'].'
        AND l.member_id=\''.$_SESSION['member_id'].'\'';
      echo $_sql_llist.'<hr />';
      $_stmt_llist = $vars['db']->query($_sql_llist);
      $_count_llist = $_stmt_llist->rowCount();
      if ($_count_llist === 1) {
        while($row = $_stmt_llist->fetch(PDO::FETCH_ASSOC)) {
          $_old_dd = $row['due_date'];
          $_loan_periode = $row['loan_periode'];

        }
        echo '_old_dd: '.$_old_dd.'--- _loan_periode: '.$_loan_periode.'<hr />';

        $_new_dd = date_create($vars['global']['today']);
        date_add($_new_dd, date_interval_create_from_date_string($_loan_periode.' days'));
        $_new_dd = date_format($_new_dd, 'Y-m-d');

        
        do {
          $_sql_holiday = 'SELECT * FROM holiday WHERE holiday_date=\''.$_new_dd.'\'';
          $_stmt_holiday = $vars['db']->query($_sql_holiday);
          $_counter = $_stmt_holiday->rowCount();
          echo $_counter.' niiih<hr />';
          if ($_counter > 0) {
            $_loan_periode = $_loan_periode + 1;
            $_new_dd = date_create($vars['global']['today']);
            date_add($_new_dd, date_interval_create_from_date_string($_loan_periode.' days'));
            $_new_dd = date_format($_new_dd, 'Y-m-d');
          }
          #if ($_counter == 0) {
          #  $_sql_holiday = 'SELECT * FROM holiday WHERE holiday_date IS NULL';
            #$_loan_periode = $_loan_periode + 1;
            #$_new_dd = date_create($vars['global']['today']);
            #date_add($_new_dd, date_interval_create_from_date_string($_loan_periode.' days'));
            #$_new_dd = date_format($_new_dd, 'Y-m-d');
          #}

          
          $i = 0;
        } while ($_counter > 0);
        
        echo $_loan_periode.'<hr />';
        echo 'Temporary due date: '.$_new_dd.'<hr />';


        #while ($_counter > 0) {
        #  $_loan_periode = $_loan_periode + 1;
        #  echo $_loan_periode.'___<hr />';
        #  echo $new_dd.'yyy<hr />';
        #  $new_dd = date_create($_old_dd);
        #  date_add($new_dd, date_interval_create_from_date_string($_loan_periode.' days'));        #  echo 'OLD NEH: '.$_old_dd."<hr />";
        #  $new_dd = date_format($_old_dd, 'Y-m-d');
        #  $_sql_holiday = 'SELECT * FROM holiday WHERE holiday_date=\''.$new_dd.'\'';
        #  $_stmt_holiday = $vars['db']->query($_sql_holiday);
        #  $_counter = $_stmt_holiday->rowCount();
        #}

      }

    }
  } else {
    $_POST['member_id'] = trim($_POST['member_id']);
    $_POST['member_password'] = trim($_POST['member_password']);
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
      $_SESSION['flash_messages'] = 'Username dan password salah!';
      header("location:layanan_mandiri");
    }
  }

  if (isset($_SESSION['member_id']) AND isset($_SESSION['member_name'])) {

    $_sql_loan = ' SELECT l.*, m.*, i.*, b.*, mlr.* 
      FROM loan AS l, member AS m, item AS i, biblio AS b, mst_loan_rules AS mlr
      WHERE
      l.member_id=m.member_id
      AND l.item_code=i.item_code 
      AND i.biblio_id=b.biblio_id
      AND l.loan_rules_id=mlr.loan_rules_id
      AND is_lent=1
      AND is_return=0
      AND l.member_id=\''.$_SESSION['member_id'].'\'';
    $_stmt_loan = $vars['db']->query($_sql_loan);
    $_count_loan = $_stmt_loan->rowCount();
    if ($_count_loan > 0) {
      $_c = 0;
      #$_today = date('Y-m-d');
      while($row = $_stmt_loan->fetch(PDO::FETCH_ASSOC)) {
        $_loanlist[$_c]['item_code'] = $row['item_code'];
        $_loanlist[$_c]['title'] = $row['title'];
        $_loanlist[$_c]['loan_date'] = $row['loan_date'];
        $_loanlist[$_c]['loan_id'] = $row['loan_id'];
        $_loanlist[$_c]['due_date'] = $row['due_date'];
        #$_loanlist[$_c]['today'] = $_today;
        if ($vars['global']['today'] > $_loanlist[$_c]['due_date']) {
          $_loanlist[$_c]['status'] = 'Terlambat';
        } else {
          $_loanlist[$_c]['status'] = 'Tidak terlambat';
        }

        $_loanlist[$_c]['renewed'] = $row['renewed'];
        $_loanlist[$_c]['loan_periode'] = $row['loan_periode'];
        $_loanlist[$_c]['reborrow_limit'] = $row['reborrow_limit'];

        if ( ($row['renewed'] < $row['reborrow_limit']) AND ($vars['global']['today'] < $row['expire_date']) ) {
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

  /**
  $_POST['member_id'] = trim($_POST['member_id']);
  $_POST['member_password'] = trim($_POST['member_password']);
  $_password = md5($_POST['member_password']);
  $_sql = 'SELECT * FROM member WHERE member_id=\''.$_POST['member_id'].'\' AND mpasswd=\''.$_password.'\'';
  $stmt = $vars['db']->query($_sql);
  $_member_count = $stmt->rowCount();
  if ($_member_count === 1) {
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $_SESSION['member_id'] = $row['member_id'];
      $_SESSION['member_name'] = $row['member_name'];
    }

    $_sql_loan = ' SELECT l.*, m.*, i.*, b.*, mlr.* 
      FROM loan AS l, member AS m, item AS i, biblio AS b, mst_loan_rules AS mlr
      WHERE
      l.member_id=m.member_id
      AND l.item_code=i.item_code 
      AND i.biblio_id=b.biblio_id
      AND l.loan_rules_id=mlr.loan_rules_id
      AND is_lent=1
      AND is_return=0
      AND l.member_id=\''.$_SESSION['member_id'].'\'';
    $_stmt_loan = $vars['db']->query($_sql_loan);
    $_count_loan = $_stmt_loan->rowCount();
    if ($_count_loan > 0) {
      $_c = 0;
      $_today = date('Y-m-d');
      while($row = $_stmt_loan->fetch(PDO::FETCH_ASSOC)) {
        $_loanlist[$_c]['item_code'] = $row['item_code'];
        $_loanlist[$_c]['title'] = $row['title'];
        $_loanlist[$_c]['loan_date'] = $row['loan_date'];
        $_loanlist[$_c]['loan_id'] = $row['loan_id'];
        $_loanlist[$_c]['due_date'] = $row['due_date'];
        $_loanlist[$_c]['today'] = $_today;
        if ($_today > $_loanlist[$_c]['due_date']) {
          $_loanlist[$_c]['status'] = 'Terlambat';
        } else {
          $_loanlist[$_c]['status'] = 'Tidak terlambat';
        }
        $_loanlist[$_c]['renewed'] = $row['renewed'];
        $_loanlist[$_c]['loan_periode'] = $row['loan_periode'];
        $_loanlist[$_c]['reborrow_limit'] = $row['reborrow_limit'];
        if ($row['renewed'] < $row['reborrow_limit']) {
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
  } else {
    $_SESSION['flash_messages'] = 'Username dan password salah!';
    header("location:layanan_mandiri");
  }
  **/

});


// Regex enabled, groups get passed to the function.
Nanite::get('/projects/([a-zA-Z0-9\-_]+)', function($project){
    echo "Project page for {$project}";
});

// Handle a POST request
Nanite::post('/contact', function(){
    // Handle submitted contact form.
});