<?php
define('MANDIRI', '1');
define('BASE_PATH', __DIR__);
define('THEMES_DIR', BASE_PATH.'/themes');
define('HTTP_PATH', dirname($_SERVER['SCRIPT_NAME']));
define('HTTP_THEMES_DIR', HTTP_PATH.'/themes');

include BASE_PATH.'/bootstrap/variables.inc.php';
session_start();

require 'router/src/Nanite.php';
require 'vendor/autoload.php';

// Use / for the main/index page.
Nanite::get('/', function(){
  #echo "Front page";
  global $vars;
  require THEMES_DIR.'/'.$vars['conf']['themes'].'/frontpage.inc.php';
});

Nanite::get('/debug', function(){
  #echo "Front page";
  global $vars;
  require THEMES_DIR.'/'.$vars['conf']['themes'].'/debug.inc.php';
});

// All routes start with /
Nanite::get('/about', function(){
    echo "About page";
});

Nanite::get('/layanan_mandiri', function(){
  include_once BASE_PATH.'/Controllers/layanan_mandiri/get_layanan_mandiri.inc.php';
});

Nanite::post('/layanan_mandiri', function(){
  include_once BASE_PATH.'/Controllers/layanan_mandiri/post_layanan_mandiri.inc.php';
});

Nanite::get('/membership/registration', function(){
  include_once BASE_PATH.'/Controllers/layanan_mandiri/get_membership_registration.inc.php';
});

Nanite::post('/membership/registration', function(){
  #echo "Process registrtation here";
  include_once BASE_PATH.'/Controllers/layanan_mandiri/post_membership_registration.inc.php';

});

// Regex enabled, groups get passed to the function.
Nanite::get('/projects/([a-zA-Z0-9\-_]+)', function($project){
    echo "Project page for {$project}";
});

// Handle a POST request
Nanite::post('/contact', function(){
    // Handle submitted contact form.
});