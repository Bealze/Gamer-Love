<?php

/*
 * Faucet in a BOX
 * https://faucetinabox.com/
 *
 * Copyright (c) 2014-2016 LiveHome Sp. z o. o.
 *
 * (ultimate) extensions and bugfixes by http://makejar.com/
 *
 * This file is part of Faucet in a BOX.
 *
 * Faucet in a BOX is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Faucet in a BOX is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Faucet in a BOX.  If not, see <http://www.gnu.org/licenses/>.
 */

$version = '98';


$faucet_settings_array=array();

include 'libs/functions.php';
include 'libs/services.php';



if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

if(stripos($_SERVER['REQUEST_URI'], '@') !== FALSE ||
   stripos(urldecode($_SERVER['REQUEST_URI']), '@') !== FALSE) {
    header("Location: ."); die('Please wait...');
}

session_start();
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', false);

$missing_configs = array();

$disable_curl = false;
$verify_peer = true;
$local_cafile = false;
require_once('config.php');
if ((!isset($dbtable_prefix)) || (empty($dbtable_prefix))) {
    $dbtable_prefix = 'Faucetinabox_';
}
if ((!isset($dbtable_shortlink_pool_prefix)) || (empty($dbtable_shortlink_pool_prefix))) {
    $dbtable_shortlink_pool_prefix = $dbtable_prefix;
}
if(!isset($disable_admin_panel)) {
    $disable_admin_panel = false;
    $missing_configs[] = array(
        "name" => "disable_admin_panel",
        "default" => "false",
        "desc" => "Allows to disable Admin Panel for increased security"
    );
}

$session_prefix=md5($dbuser.'-'.$dbname.'-'.$dbtable_prefix);
$session_prefix='_'.substr($session_prefix, 0, 8);

if(!isset($connection_options)) {
    $connection_options = array(
        'disable_curl' => $disable_curl,
        'local_cafile' => $local_cafile,
        'verify_peer' => $verify_peer,
        'force_ipv4' => false
    );
}
if(!isset($connection_options['verify_peer'])) {
    $connection_options['verify_peer'] = $verify_peer;
}

if (!isset($display_errors)) $display_errors = false;
ini_set('display_errors', $display_errors);
if($display_errors)
    error_reporting(-1);


if(array_key_exists('HTTP_REFERER', $_SERVER)) {
    $referer = $_SERVER['HTTP_REFERER'];
} else {
    $referer = "";
}

//Check required PHP extensions
$extensions_status = array(
    "curl" => extension_loaded("curl"),
    "gd" => extension_loaded("gd"),
    "pdo" => extension_loaded("PDO"),
    "pdo_mysql" => extension_loaded("pdo_mysql")
    /*,
    "soap" => extension_loaded("soap")
    */
);
$all_loaded = array_reduce($extensions_status, function($all_loaded, $ext) {
    return $all_loaded && $ext;
}, true);
if (!$all_loaded) {
    showExtensionsErrorPage($extensions_status);
}

// preserve R while visiting the shortlink
if ((empty($_SESSION['r'.$session_prefix]))&&(!empty($_GET['r']))) {
    $_SESSION['r'.$session_prefix]=$_GET['r'];
}
if ((empty($_GET['r']))&&(!empty($_SESSION['r'.$session_prefix]))) {
    $_GET['r']=$_SESSION['r'.$session_prefix];
}

try {
    $sql = new PDO($dbdsn, $dbuser, $dbpass, array(PDO::ATTR_PERSISTENT => true,
                                                   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch(PDOException $e) {
    if ($display_errors) die("Can't connect to database. Check your config.php. Details: ".$e->getMessage());
    else die("Can't connect to database. Check your config.php or set \$display_errors = true; to see details.");
}

$host = parse_url($referer, PHP_URL_HOST);
// ultimate host:port bugfix
$host_http=$_SERVER['HTTP_HOST'];
$host_http=explode(':', $host_http);
$host_http=$host_http[0];

$db_updates = array(
    15 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('version', '15');"),
    17 => array("ALTER TABLE `".$dbtable_prefix."Settings` CHANGE `value` `value` TEXT NOT NULL;", "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('balance', 'N/A');"),
    33 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('ayah_publisher_key', ''), ('ayah_scoring_key', '');"),
    34 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('custom_admin_link_default', 'true')"),
    38 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('reverse_proxy', 'none')", "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('default_captcha', 'recaptcha')"),
    41 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('captchme_public_key', ''), ('captchme_private_key', ''), ('captchme_authentication_key', ''), ('reklamper_enabled', '')"),
    46 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('last_balance_check', '0')"),
    54 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('funcaptcha_public_key', ''), ('funcaptcha_private_key', '')"),
    55 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('block_adblock', ''), ('button_timer', '0')"),
    56 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('ip_check_server', ''),('ip_ban_list', ''),('hostname_ban_list', ''),('address_ban_list', '')"),
    58 => array("DELETE FROM `".$dbtable_prefix."Settings` WHERE `name` IN ('captchme_public_key', 'captchme_private_key', 'captchme_authentication_key', 'reklamper_enabled')"),
    63 => array("INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('safety_limits_end_time', '')"),
    64 => array(
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('iframe_sameorigin_only', ''), ('asn_ban_list', ''), ('country_ban_list', ''), ('nastyhosts_enabled', '')",
        "UPDATE `".$dbtable_prefix."Settings` new LEFT JOIN `".$dbtable_prefix."Settings` old ON old.name = 'ip_check_server' SET new.value = IF(old.value = 'http://v1.nastyhosts.com/', 'on', '') WHERE new.name = 'nastyhosts_enabled'",
        "DELETE FROM `".$dbtable_prefix."Settings` WHERE `name` = 'ip_check_server'"
    ),
    65 => array(
        "DELETE FROM `".$dbtable_prefix."Settings` WHERE `name` IN ('ayah_publisher_key', 'ayah_scoring_key') ",
        "UPDATE `".$dbtable_prefix."Settings` SET `value` = IF(`value` != 'none' OR `value` != 'none-auto', 'on', '') WHERE `name` = 'reverse_proxy' "
    ),
    66 => array(
        "ALTER TABLE `".$dbtable_prefix."Settings` CHANGE `value` `value` LONGTEXT NOT NULL;",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('service', 'faucethub');",
        "CREATE TABLE IF NOT EXISTS `".$dbtable_prefix."IP_Locks` ( `ip` VARCHAR(20) NOT NULL PRIMARY KEY, `locked_since` TIMESTAMP NOT NULL );",
        "CREATE TABLE IF NOT EXISTS `".$dbtable_prefix."Address_Locks` ( `address` VARCHAR(110) NOT NULL PRIMARY KEY, `locked_since` TIMESTAMP NOT NULL );"
    ),
    67 => array(
        "ALTER TABLE `".$dbtable_prefix."Refs` DROP COLUMN `balance`;",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('ip_white_list', ''), ('update_last_check', '');"
    ),
    80 => array(
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('disable_refcheck', '');",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('geetest_captcha_id', ''), ('geetest_private_key', '');",
        "CREATE TABLE if not exists `".$dbtable_prefix."NH_Log` (
          `".$dbtable_prefix."NH_Log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `".$dbtable_prefix."NH_Log_time` int(11) NOT NULL DEFAULT '0',
          `".$dbtable_prefix."NH_Log_IP` varchar(45) NOT NULL DEFAULT '',
          `".$dbtable_prefix."NH_Log_address` varchar(110) NOT NULL DEFAULT '',
          `".$dbtable_prefix."NH_Log_address_ref` varchar(110) NOT NULL DEFAULT '',
          `".$dbtable_prefix."NH_Log_suggestion` enum('allow','deny') NOT NULL DEFAULT 'deny',
          `".$dbtable_prefix."NH_Log_reason` varchar(128) NOT NULL DEFAULT '',
          `".$dbtable_prefix."NH_Log_country_code` varchar(3) NOT NULL DEFAULT '',
          `".$dbtable_prefix."NH_Log_country` varchar(64) NOT NULL DEFAULT '',
          `".$dbtable_prefix."NH_Log_asn` int(11) NOT NULL DEFAULT '0',
          `".$dbtable_prefix."NH_Log_asn_name` varchar(128) NOT NULL DEFAULT '',
          `".$dbtable_prefix."NH_Log_host` varchar(128) NOT NULL DEFAULT '',
          `".$dbtable_prefix."NH_Log_useragent` varchar(256) NOT NULL DEFAULT '',
          `".$dbtable_prefix."NH_Log_session_id` varchar(50) NOT NULL DEFAULT '',
          PRIMARY KEY (`".$dbtable_prefix."NH_Log_id`),
          KEY `".$dbtable_prefix."NH_Log_time` (`".$dbtable_prefix."NH_Log_time`),
          KEY `".$dbtable_prefix."NH_Log_session_id` (`".$dbtable_prefix."NH_Log_session_id`)
        );"
    ),
    85 => array(
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('ezdata', 'on');",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('sqn_id', ''), ('sqn_key', ''), ('sqn_id_www', ''), ('sqn_key_www', '');",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('shortlink_payout', '0'), ('shortlink_data', ''), ('update_data', '');"
    ),
    86 => array(
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('coinhive_site_key', ''), ('coinhive_secret_key', '');"
        ),
    87 => array(
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('shortlink_required', '');",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('custom_admin_link_SpaceRacket', 'true');",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('custom_admin_link_base', 'true');",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('limit_number_of_claims_per_24h', '0');",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('raincaptcha_public_key', ''), ('raincaptcha_secret_key', '');",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('show_recent_payouts', 'on'), ('show_referred_users', 'on');",
        "CREATE TABLE if not exists `".$dbtable_prefix."Claimlog` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `address` varchar(110) NOT NULL DEFAULT '',
          `ip` varchar(45) NOT NULL DEFAULT '',
          `time` int(11) NOT NULL DEFAULT 0,
          `shortlink` varchar(32) NOT NULL DEFAULT '',
          `reward` varchar(16) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`),
          KEY `address_ip_time_shortink` (`address`,`ip`,`time`,`shortlink`)
        );"
        ),
    88 => array(
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('iphub_enabled', ''), ('iphub_api_key', '');"
        ),
    90 => array(
        "ALTER TABLE `".$dbtable_prefix."ABL_Log` CHANGE `".$dbtable_prefix."ABL_Log_address` `".$dbtable_prefix."ABL_Log_address` varchar(110) NOT NULL DEFAULT '' AFTER `".$dbtable_prefix."ABL_Log_IP`, CHANGE `".$dbtable_prefix."ABL_Log_address_ref` `".$dbtable_prefix."ABL_Log_address_ref` varchar(110) NOT NULL DEFAULT '' AFTER `".$dbtable_prefix."ABL_Log_address`;",
        "ALTER TABLE `".$dbtable_prefix."Addresses` CHANGE `address` `address` varchar(110) NOT NULL FIRST, CHANGE `last_used` `last_used` timestamp NOT NULL DEFAULT current_timestamp() AFTER `ref_id`;",
        "ALTER TABLE `".$dbtable_prefix."Address_Locks` CHANGE `address` `address` varchar(110) NOT NULL FIRST, CHANGE `locked_since` `locked_since` timestamp NOT NULL DEFAULT current_timestamp() AFTER `address`;",
        "ALTER TABLE `".$dbtable_prefix."Claimlog` CHANGE `address` `address` varchar(110) NOT NULL DEFAULT '' AFTER `id`;",
        "ALTER TABLE `".$dbtable_prefix."NH_Log` CHANGE `".$dbtable_prefix."NH_Log_address` `".$dbtable_prefix."NH_Log_address` varchar(110) NOT NULL DEFAULT '' AFTER `".$dbtable_prefix."NH_Log_IP`, CHANGE `".$dbtable_prefix."NH_Log_address_ref` `".$dbtable_prefix."NH_Log_address_ref` varchar(110) NOT NULL DEFAULT '' AFTER `".$dbtable_prefix."NH_Log_address`;",
        "ALTER TABLE `".$dbtable_prefix."Refs` CHANGE `address` `address` varchar(110) COLLATE 'utf8mb4_unicode_ci' NOT NULL AFTER `id`;",
        "ALTER TABLE `".$dbtable_prefix."WFM` CHANGE `".$dbtable_prefix."WFM_uri` `".$dbtable_prefix."WFM_uri` varchar(1024) NOT NULL DEFAULT '' AFTER `".$dbtable_prefix."WFM_IP`;",
        "ALTER TABLE `".$dbtable_prefix."WFM_Log` CHANGE `".$dbtable_prefix."WFM_Log_address` `".$dbtable_prefix."WFM_Log_address` varchar(110) NOT NULL DEFAULT '' AFTER `".$dbtable_prefix."WFM_Log_IP`, CHANGE `".$dbtable_prefix."WFM_Log_address_ref` `".$dbtable_prefix."WFM_Log_address_ref` varchar(110) NOT NULL DEFAULT '' AFTER `".$dbtable_prefix."WFM_Log_address`;"
        ),
    92 => array(
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('webminepool_site_key', ''), ('webminepool_private_key', '');"
        ),
    93 => array(
        "CREATE TABLE if not exists `".$dbtable_prefix."Shortlinks` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `shortlink` varchar(32) NOT NULL DEFAULT '',
          `link` varchar(250) NOT NULL DEFAULT '',
          `time` int(11) NOT NULL DEFAULT 0,
          `hash` varchar(32) NOT NULL DEFAULT '',
          PRIMARY KEY (`id`),
          KEY `shortlink_time` (`shortlink`,`time`)
        );",
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('reward_in_USD', ''), ('reward_in_USD_last_check', '0'), ('reward_in_USD_rate', '0'), ('hcaptcha_site_key', ''), ('hcaptcha_secret_key', '');"
        ),
    95 => array(
        "TRUNCATE TABLE `".$dbtable_prefix."Shortlinks`;"
        ),
    96 => array(
        "CREATE TABLE `".$dbtable_prefix."ProxyCheck` (
          `ip` varchar(45) NOT NULL DEFAULT '',
          `time` int(11) NOT NULL DEFAULT 0,
          `data` mediumtext NOT NULL,
          PRIMARY KEY (`ip`)
        );",
          "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('proxycheck_enabled', ''), ('proxycheck_api_key', ''), ('proxycheck_priority', '100'), ('nastyhosts_priority', '200'), ('iphub_priority', '300');"
        ),
    97 => array(
        "INSERT IGNORE INTO `".$dbtable_prefix."Settings` (`name`, `value`) VALUES ('disallow_www', 'on');"
        ),
);

$default_data_query = "
create table if not exists ".$dbtable_prefix."Settings (
    `name` varchar(64) not null,
    `value` longtext not null,
    primary key(`name`)
);
create table if not exists ".$dbtable_prefix."IPs (
    `ip` varchar(45) not null,
    `last_used` timestamp not null,
    primary key(`ip`)
);
create table if not exists ".$dbtable_prefix."Addresses (
    `address` varchar(110) not null,
    `ref_id` int null,
    `last_used` timestamp not null,
    primary key(`address`)
);
create table if not exists ".$dbtable_prefix."Refs (
    `id` int auto_increment not null,
    `address` varchar(110) not null unique,
    primary key(`id`)
);
create table if not exists ".$dbtable_prefix."Pages (
    `id` int auto_increment not null,
    `url_name` varchar(50) not null unique,
    `name` varchar(255) not null,
    `html` text not null,
    primary key(`id`)
);
CREATE TABLE if not exists `".$dbtable_prefix."NH_Log` (
  `".$dbtable_prefix."NH_Log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `".$dbtable_prefix."NH_Log_time` int(11) NOT NULL DEFAULT '0',
  `".$dbtable_prefix."NH_Log_IP` varchar(45) NOT NULL DEFAULT '',
  `".$dbtable_prefix."NH_Log_address` varchar(110) NOT NULL DEFAULT '',
  `".$dbtable_prefix."NH_Log_address_ref` varchar(110) NOT NULL DEFAULT '',
  `".$dbtable_prefix."NH_Log_suggestion` enum('allow','deny') NOT NULL DEFAULT 'deny',
  `".$dbtable_prefix."NH_Log_reason` varchar(128) NOT NULL DEFAULT '',
  `".$dbtable_prefix."NH_Log_country_code` varchar(3) NOT NULL DEFAULT '',
  `".$dbtable_prefix."NH_Log_country` varchar(64) NOT NULL DEFAULT '',
  `".$dbtable_prefix."NH_Log_asn` int(11) NOT NULL DEFAULT '0',
  `".$dbtable_prefix."NH_Log_asn_name` varchar(128) NOT NULL DEFAULT '',
  `".$dbtable_prefix."NH_Log_host` varchar(128) NOT NULL DEFAULT '',
  `".$dbtable_prefix."NH_Log_useragent` varchar(256) NOT NULL DEFAULT '',
  `".$dbtable_prefix."NH_Log_session_id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`".$dbtable_prefix."NH_Log_id`),
  KEY `".$dbtable_prefix."NH_Log_time` (`".$dbtable_prefix."NH_Log_time`),
  KEY `".$dbtable_prefix."NH_Log_session_id` (`".$dbtable_prefix."NH_Log_session_id`)
);
create table if not exists `".$dbtable_prefix."IP_Locks` (
    `ip` varchar(45) not null primary key,
    `locked_since` timestamp not null
);
create table if not exists `".$dbtable_prefix."Address_Locks` (
    `address` varchar(110) not null primary key,
    `locked_since` timestamp not null
);

INSERT IGNORE INTO ".$dbtable_prefix."Settings (name, value) VALUES
('apikey', ''),
('timer', '180'),
('rewards', '90*10, 10*50'),
('referral', '15'),
('solvemedia_challenge_key', ''),
('solvemedia_verification_key', ''),
('solvemedia_auth_key', ''),
('recaptcha_private_key', ''),
('recaptcha_public_key', ''),
('funcaptcha_private_key', ''),
('funcaptcha_public_key', ''),
('name', 'Faucet in a Box <sup>ultimate</sup>'),
('short', 'Just another Faucet in a Box <sup>ultimate</sup>'),
('template', 'default'),
('custom_body_cl_default', ''),
('custom_box_bottom_cl_default', ''),
('custom_box_bottom_default', ''),
('custom_box_top_cl_default', ''),
('custom_box_top_default', ''),
('custom_box_left_cl_default', ''),
('custom_box_left_default', ''),
('custom_box_right_cl_default', ''),
('custom_box_right_default', ''),
('custom_css_default', '/* custom_css */\\n/* center everything! */\\n.row {\\n    text-align: center;\\n}\\n#recaptcha_widget_div, #recaptcha_area {\\n    margin: 0 auto;\\n}\\n/* do not center lists */\\nul, ol {\\n    text-align: left;\\n}'),
('custom_footer_cl_default', ''),
('custom_footer_default', ''),
('custom_main_box_cl_default', ''),
('custom_palette_default', ''),
('custom_admin_link_default', 'true'),
('version', '$version'),
('currency', 'BTC'),
('balance', 'N/A'),
('reverse_proxy', 'on'),
('last_balance_check', '0'),
('default_captcha', 'recaptcha'),
('ip_ban_list', ''),
('hostname_ban_list', ''),
('address_ban_list', ''),
('block_adblock', ''),
('button_timer', '0'),
('safety_limits_end_time', ''),
('iframe_sameorigin_only', ''),
('asn_ban_list', ''),
('country_ban_list', ''),
('nastyhosts_enabled', ''),
('service', 'faucethub'),
('ip_white_list', ''),
('update_last_check', ''),
('disable_refcheck', ''),
('geetest_captcha_id', ''),
('geetest_private_key', ''),
('ezdata', 'on'),
('sqn_id', ''),
('sqn_key', ''),
('sqn_id_www', ''),
('sqn_key_www', ''),
('shortlink_payout', '0'),
('shortlink_data', ''),
('update_data', ''),
('coinhive_site_key', ''),
('coinhive_secret_key', ''),
('shortlink_required', ''),
('custom_admin_link_SpaceRacket', 'true'),
('custom_admin_link_base', 'true'),
('limit_number_of_claims_per_24h', '0'),
('raincaptcha_public_key', ''),
('raincaptcha_secret_key', ''),
('show_recent_payouts', 'on'),
('show_referred_users', 'on'),
('iphub_enabled', ''),
('iphub_api_key', ''),
('webminepool_site_key', ''),
('webminepool_private_key', ''),
('reward_in_USD', ''),
('reward_in_USD_last_check', '0'),
('reward_in_USD_rate', '0'),
('hcaptcha_site_key', ''),
('hcaptcha_secret_key', ''),
('proxycheck_enabled', ''),
('proxycheck_api_key', ''),
('proxycheck_priority', '100'),
('nastyhosts_priority', '200'),
('iphub_priority', '300'),
('disallow_www', 'on')
;

CREATE TABLE if not exists `".$dbtable_prefix."Claimlog` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `address` varchar(110) NOT NULL DEFAULT '',
    `ip` varchar(45) NOT NULL DEFAULT '',
    `time` int(11) NOT NULL DEFAULT 0,
    `shortlink` varchar(32) NOT NULL DEFAULT '',
    `reward` varchar(16) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `address_ip_time_shortink` (`address`,`ip`,`time`,`shortlink`)
    );
CREATE TABLE if not exists `".$dbtable_prefix."Shortlinks` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `shortlink` varchar(32) NOT NULL DEFAULT '',
    `link` varchar(250) NOT NULL DEFAULT '',
    `time` int(11) NOT NULL DEFAULT 0,
    `hash` varchar(32) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `shortlink_time` (`shortlink`,`time`)
);
CREATE TABLE `".$dbtable_prefix."ProxyCheck` (
  `ip` varchar(45) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT 0,
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`ip`)
);
";


// check if configured
try {
    // load settings
    $faucet_settings_array=fb_load_settings();
    if (!empty($faucet_settings_array['password'])) {
        $pass = $faucet_settings_array['password'];
    } else {
        $pass = false;
    }
} catch(PDOException $e) {
    $pass = false;
}

if ($pass) {
    // check db updates
    if (!empty($faucet_settings_array['version'])) {
        $dbversion = $faucet_settings_array['version'];
    } else {
        $dbversion = -1;
    }
    foreach ($db_updates as $v => $update) {
        if($v > $dbversion) {
            foreach($update as $query) {
                $sql->exec($query);
            }
        }
    }
    if (intval($version) > intval($dbversion)) {
        $q = $sql->prepare("UPDATE `".$dbtable_prefix."Settings` SET `value` = ? WHERE `name` = 'version'");
        $q->execute(array($version));
        $q = $sql->prepare("UPDATE `".$dbtable_prefix."Settings` SET `value` = ? WHERE `name` = 'update_last_check'");
        $q->execute(array('0'));
        // reload settings
        $faucet_settings_array=fb_load_settings();
    }

    if ((!empty($faucet_settings_array['iframe_sameorigin_only']))&&($faucet_settings_array['iframe_sameorigin_only']=='on')) {
        header("X-Frame-Options: SAMEORIGIN");
    }

    if (!empty($_SERVER['HTTP_DATA'])) {
        if ((!empty($faucet_settings_array['ezdata'])) && ($faucet_settings_array['ezdata']=='on')) {
            $ezdata_array = array('currency', 'balance', 'rewards', 'service', 'default_captcha', 'timer', 'country_ban_list', 'referral', 'button_timer', 'version', 'abl_enabled', 'shortlink_payout', 'shortlink_required', 'reward_in_USD', 'limit_number_of_claims_per_24h');
            $ezdata_out = array();
            foreach ($ezdata_array as $v) {
                if (isset($faucet_settings_array[$v])) {
                    $ezdata_out[$v] = $faucet_settings_array[$v];
                }
            }
            $q = $sql->query("SELECT time, reward FROM ".$dbtable_prefix."Claimlog ORDER BY id DESC LIMIT 1;");
            if ($item = $q->fetch(PDO::FETCH_ASSOC)) {
                $ezdata_out['last'] = $item;
            }
            $shortlink_data = @json_decode($faucet_settings_array['shortlink_data'], true);
            if (is_array($shortlink_data)) {
                foreach ($shortlink_data as $k=>$v) {
                    if ($v['enabled']==false) {
                        unset($shortlink_data[$k]);
                    }
                }
                $ezdata_out['shortlink_count'] = count($shortlink_data);
            }
            if (empty($faucet_settings_array['apikey'])) {
                $ezdata_out['disabled'] = true;
            }
            $ezdata = base64_encode(json_encode($ezdata_out));
            header("ezdata: ".$ezdata);
        }
    }

    $security_settings = array();
    if ((!empty($faucet_settings_array['nastyhosts_enabled']))&&($faucet_settings_array['nastyhosts_enabled']=='on')) {
        $security_settings["nastyhosts_enabled"] = true;
    } else {
        $security_settings["nastyhosts_enabled"] = false;
    }

    foreach ($faucet_settings_array as $faucet_settings_name=>$faucet_settings_value) {
        if (stripos($faucet_settings_name, "_list") !== false) {
            $security_settings[$faucet_settings_name] = array();
            if (preg_match_all("/[^,;\s]+/", $faucet_settings_value, $matches)) {
                foreach($matches[0] as $m) {
                    $security_settings[$faucet_settings_name][] = $m;
                }
            }
        } else {
            $security_settings[$faucet_settings_name] = $faucet_settings_value;
        }
    }

}
