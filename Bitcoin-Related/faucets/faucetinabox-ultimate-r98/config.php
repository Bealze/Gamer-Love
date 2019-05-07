<?php

$dbhost = "localhost";
$dbuser = "ultimate";
$dbpass = "ultimate";
$dbname = "ultimate";
$dbtable_prefix = ''; // default empty
$dbtable_shortlink_pool_prefix = ''; // default empty
$display_errors = true; // change to false once installed and tested
$disable_admin_panel = false;

$faucethub_ref_url='https://faucethub.io/r/13852';

$connection_options = array(
    'disable_curl' => false,
    'local_cafile' => false,
    'force_ipv4' => false    // cURL only
);

// dsn - Data Source Name
// if you use MySQL, leave it as is
// more information:
// http://php.net/manual/en/pdo.construct.php
$dbdsn = "mysql:host=$dbhost;dbname=$dbname";

// Uncomment the line below if you are getting Warning: strtotime(): It is not safe to rely on the system's timezone settings.
//date_default_timezone_set('UTC');
