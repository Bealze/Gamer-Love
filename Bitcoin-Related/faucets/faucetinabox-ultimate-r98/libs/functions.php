<?php

function fb_load_settings() {
    global $sql, $dbtable_prefix;

    $faucet_settings_array=array();

    $faucet_settings_quey = $sql->query("SELECT `name`, `value` FROM `".$dbtable_prefix."Settings`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($faucet_settings_quey as $faucet_settings_row) {
        $faucet_settings_array[$faucet_settings_row['name']]=$faucet_settings_row['value'];
    }
    return $faucet_settings_array;
}

function getUniqueRequestID() {
    global $unique_request_id;

    if (!empty($unique_request_id)) {
        return $unique_request_id;
    } else {
        return '';
    }
}

function showExtensionsErrorPage($extensions_status) {
    global $version;
    require_once("script/admin_templates.php");
    
    $page = str_replace("<:: content ::>", $extensions_error_template, $master_template);
    
    foreach ($extensions_status as $ext => $status) {
        $page = str_replace("<:: {$ext}_color ::>", ($status ? "success" : "danger"), $page);
        $page = str_replace("<:: {$ext}_glyphicon ::>", ($status ? $extensions_ok_glyphicon : $extensions_error_glyphicon), $page);
    }
    
    die($page);
}

function randHash($length) {
    $hash = '';
    if ($length>2) {
      $alphabet = str_split('qwertyuiopasdfghjklzxcvbnm');
      for($i = 0; $i < 2; $i++) {
          $hash .= $alphabet[array_rand($alphabet)];
      }
      $length-=2;
    }
    $alphabet = str_split('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890');
    for($i = 0; $i < $length; $i++) {
        $hash .= $alphabet[array_rand($alphabet)];
    }
    return $hash;
}

function getNastyHostsServer() {
    return "http://v1.nastyhosts.com/";
}

function checkRevProxyIp($file, $remote_addr) {
    require_once('libs/http-foundation/IpUtils.php');
    return IpUtils::checkIp($remote_addr, array_map(function($v) { return trim($v); }, file($file)));
}

function detectRevProxyProvider() {
    $remote_addr = $_SERVER['REMOTE_ADDR'];
    if (($remote_addr==$_SERVER['SERVER_ADDR']) && (isset($_SERVER['HTTP_X_REAL_IP']))) {
        $remote_addr = $_SERVER['HTTP_X_REAL_IP'];
    }
    if(checkRevProxyIp("libs/ips/cloudflare.txt", $remote_addr)) {
        return "CloudFlare";
    } elseif(checkRevProxyIp("libs/ips/incapsula.txt", $remote_addr)) {
        return "Incapsula";
    }
    return "none";
}

function getIP() {
    global $sql, $faucet_settings_array;
    static $cache_ip;
    if ($cache_ip) {
        return $cache_ip;
    }
    $ip = null;
    $remote_addr = $_SERVER['REMOTE_ADDR'];
    if (($remote_addr==$_SERVER['SERVER_ADDR']) && (isset($_SERVER['HTTP_X_REAL_IP']))) {
        $remote_addr = $_SERVER['HTTP_X_REAL_IP'];
    }
    if ((!empty($faucet_settings_array['reverse_proxy'])) && $faucet_settings_array['reverse_proxy'] == 'on') {
        if (checkRevProxyIp("libs/ips/cloudflare.txt", $remote_addr)) {
            $ip = array_key_exists('HTTP_CF_CONNECTING_IP', $_SERVER) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : null;
        } elseif (checkRevProxyIp("libs/ips/incapsula.txt", $remote_addr)) {
            $ip = array_key_exists('HTTP_INCAP_CLIENT_IP', $_SERVER) ? $_SERVER['HTTP_INCAP_CLIENT_IP'] : null;
        }
    }
    if (empty($ip)) {
        $ip = $remote_addr;
    }
    $cache_ip = $ip;
    return $ip;
}

function is_ssl() {
    if ((isset($_SERVER['HTTPS'])) && ('on' == strtolower($_SERVER['HTTPS']))) {
        return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == '443')) {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') {
        return true;
    }
    return false;
}

function ipSubnetCheck($ip, $network) {
    $network = explode("/", $network);
    $net = $network[0];

    if(count($network) > 1) {
        $mask = $network[1];
    } else {
        $mask = 32;
    }

    $net = ip2long ($net);
    $mask = ~((1 << (32 - $mask)) - 1);

    $ip_net = $ip & $mask;

    return ($ip_net == $net);
}

function nastyhosts_log($suggestion, $reason='', $response=array()) {
    global $sql, $dbtable_prefix, $session_prefix;
    if (empty($_SESSION['address_input_name'.$session_prefix])) {
        return;
    }
    if (empty($_POST[$_SESSION['address_input_name'.$session_prefix]])) {
        return;
    }
    // Delete the log that is older than a day - for better performance execute every ~20 requests
    if (mt_rand(0, 20)==5) {
        $sql->exec("DELETE FROM `".$dbtable_prefix."NH_Log` WHERE ".$dbtable_prefix."NH_Log_time<".(time()-86400).";");
    }
    $q=$sql->prepare("INSERT INTO `".$dbtable_prefix."NH_Log` SET
                      ".$dbtable_prefix."NH_Log_time=?,
                      ".$dbtable_prefix."NH_Log_IP=?,
                      ".$dbtable_prefix."NH_Log_address=?,
                      ".$dbtable_prefix."NH_Log_address_ref=?,
                      ".$dbtable_prefix."NH_Log_suggestion=?,
                      ".$dbtable_prefix."NH_Log_reason=?,
                      ".$dbtable_prefix."NH_Log_country_code=?,
                      ".$dbtable_prefix."NH_Log_country=?,
                      ".$dbtable_prefix."NH_Log_asn=?,
                      ".$dbtable_prefix."NH_Log_asn_name=?,
                      ".$dbtable_prefix."NH_Log_host=?,
                      ".$dbtable_prefix."NH_Log_useragent=?,
                      ".$dbtable_prefix."NH_Log_session_id=?
                    ;");
    $q->execute(array(
                      time(),
                      trim(getIP()),
                      trim(!empty($_POST[$_SESSION['address_input_name'.$session_prefix]])?$_POST[$_SESSION['address_input_name'.$session_prefix]]:''),
                      trim(!empty($_GET['r'])?$_GET['r']:''),
                      trim(!empty($suggestion)?$suggestion:''),
                      trim(!empty($reason)?$reason:''),
                      trim(!empty($response['country']['code'])?$response['country']['code']:''),
                      trim(!empty($response['country']['country'])?$response['country']['country']:''),
                      trim(!empty($response['asn']['asn'])?$response['asn']['asn']:'0'),
                      trim(!empty($response['asn']['name'])?substr($response['asn']['name'], 0, 128):''),
                      trim(!empty($response['hostnames'][0])?$response['hostnames'][0]:''),
                      trim(!empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:''),
                      session_id().'-'.getUniqueRequestID()
                      ));
}


function regenerate_csrf_token() {
    global $session_prefix;
    $_SESSION['csrftoken'.$session_prefix] = base64_encode(openssl_random_pseudo_bytes(20));
}

function get_csrf_token() {
    global $session_prefix;
    return '<input type="hidden" name="csrftoken" value="'. $_SESSION['csrftoken'.$session_prefix]. '">';
}



function setNewPass() {
    global $sql, $dbtable_prefix;
    $alphabet = str_split('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890');
    $password = '';
    for($i = 0; $i < 15; $i++) {
        $password .= $alphabet[array_rand($alphabet)];
    }
    // ultimate - bugfix
    $hash = crypt($password, md5_file('./config.php'));
    $sql->query("REPLACE INTO ".$dbtable_prefix."Settings VALUES ('password', '$hash')");
    return $password;
}



// Check functions

function checkTimeForIP($ip, &$time_left = NULL) {
    global $sql, $data, $dbtable_prefix;
    $q = $sql->prepare("SELECT TIMESTAMPDIFF(MINUTE, last_used, CURRENT_TIMESTAMP()) FROM ".$dbtable_prefix."IPs WHERE ip = ?");
    $q->execute([$ip]);
    if ($time = $q->fetch()) {
        $time = intval($time[0]);
        $required = intval($data["timer"]);
        
        $time_left = $required-$time;
        return $time >= intval($data["timer"]);
    } else {
        $time_left = 0;
        return true;
    }
}

function checkTimeForAddress($address, &$time_left = NULL) {
    global $sql, $data, $dbtable_prefix;
    $q = $sql->prepare("SELECT TIMESTAMPDIFF(MINUTE, last_used, CURRENT_TIMESTAMP()) FROM ".$dbtable_prefix."Addresses WHERE `address` LIKE ?");
    $q->execute([$address]);
    if ($time = $q->fetch()) {
        $time = intval($time[0]);
        $required = intval($data["timer"]);

        $time_left = $required-$time;
        return $time >= intval($data["timer"]);
    } else {
        $time_left = 0;
        return true;
    }
}

function checkAddressValidity($address) {
    global $data;

    return (preg_match("/^[0-9A-Za-z]{26,110}$/", $address) === 1);
}

function checkAddressBlacklist($address) {
    global $security_settings;
    return !in_array($address, $security_settings["address_ban_list"]);
}

function checkIPIsWhitelisted() {
    global $security_settings;
    $ip = ip2long(getIP());
    if ($ip) { // only ipv4 supported here
        foreach ($security_settings["ip_white_list"] as $whitelisted) {
            if (ipSubnetCheck($ip, $whitelisted)) {
                return true;
            }
        }
    }
    return false;
}

function checkIPBlacklist() {
    global $security_settings;
    $ip = ip2long(getIP());
    if ($ip) { // only ipv4 supported here
        foreach ($security_settings["ip_ban_list"] as $ban) {
            if (ipSubnetCheck($ip, $ban)) {
                //trigger_error("Banned: ".getIP()." (blacklist: {$ban})");
                return false;
            }
        }
    }
    return true;
}

function checkNastyHosts() {
    $result = '';
    if ($ch = curl_init()) {
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_URL, getNastyHostsServer().getIP().'?source=fiab');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:64.0) Gecko/20100101 Firefox/64.0');
        $result = @curl_exec($ch);
        curl_close($ch);
    }
    $hostnames_array = @json_decode($result, true);

    if ($hostnames_array && isset($hostnames_array['status']) && $hostnames_array['status'] == 200) {
        $hostnames_array['service'] = 'NastyHosts';
        return $hostnames_array;
    }
    return array();
}


function checkIPHub($api_key) {
    $result = '';
    if ($ch = curl_init()) {
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_URL, 'http://v2.api.iphub.info/ip/'.getIP());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:64.0) Gecko/20100101 Firefox/64.0');
        $headers = array(
            'X-Key: '.$api_key
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = @curl_exec($ch);
        curl_close($ch);
    }
    $hostnames_iphub_array = @json_decode($result, true);
    if ($hostnames_iphub_array && isset($hostnames_iphub_array['block'])) {
        $hostnames_array = array();
        $hostnames_array['service'] = 'IPHub';
        $hostnames_array['status']=200;
        $hostnames_array['asn']=array();
        $hostnames_array['country']=array();
        if ($hostnames_iphub_array['block']==1) {
            $hostnames_array['suggestion'] = 'deny';
        } else {
            $hostnames_array['suggestion'] = 'allow';
        }
        if (isset($hostnames_iphub_array['asn'])) {
            $hostnames_array['asn']['asn'] = $hostnames_iphub_array['asn'];
        }
        if (isset($hostnames_iphub_array['isp'])) {
            $hostnames_array['asn']['name'] = $hostnames_iphub_array['isp'];
        }
        if (isset($hostnames_iphub_array['countryCode'])) {
            $hostnames_array['country']['code'] = $hostnames_iphub_array['countryCode'];
        }
        if (isset($hostnames_iphub_array['countryName'])) {
            $hostnames_array['country']['country']=$hostnames_iphub_array['countryName'];
        }
        return $hostnames_array;
    }
    return array();
}

function checkProxyCheck($api_key) {
    $result = '';
    if ($ch = curl_init()) {
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_URL, 'https://proxycheck.io/v2/'.getIP().'?key='.$api_key.'&vpn=1&asn=1&node=1&time=1&inf=0&port=1&seen=1&days=7&tag=msg');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:64.0) Gecko/20100101 Firefox/64.0');
        $result = @curl_exec($ch);
        curl_close($ch);
    }
    $hostnames_proxycheck_array = @json_decode($result, true);
    if ($hostnames_proxycheck_array && isset($hostnames_proxycheck_array['status']) && ($hostnames_proxycheck_array['status']=='ok')) {
        $hostnames_array = array();
        $hostnames_array['service'] = 'ProxyCheck';
        $hostnames_array['status']=200;
        $hostnames_array['asn']=array();
        $hostnames_array['country']=array();
        if (isset($hostnames_proxycheck_array[getIP()])) {
            $hostnames_proxycheck_array = $hostnames_proxycheck_array[getIP()];
            if ($hostnames_proxycheck_array['proxy']=='yes') {
                $hostnames_array['suggestion'] = 'deny';
            } else {
                $hostnames_array['suggestion'] = 'allow';
            }
            if (isset($hostnames_proxycheck_array['asn'])) {
                $hostnames_array['asn']['asn'] = str_replace('AS', '', $hostnames_proxycheck_array['asn']);
            }
            if (isset($hostnames_proxycheck_array['provider'])) {
                $hostnames_array['asn']['name'] = $hostnames_proxycheck_array['provider'];
            }
            if (isset($hostnames_proxycheck_array['isocode'])) {
                $hostnames_array['country']['code'] = $hostnames_proxycheck_array['isocode'];
            }
            if (isset($hostnames_proxycheck_array['country'])) {
                $hostnames_array['country']['country']=$hostnames_proxycheck_array['country'];
            }
            return $hostnames_array;
        }
    }
    return array();
}

function checkProxyData($hostnames_array) {
    global $security_settings;

    $result = array();
    $result['action'] = 'skip';
    $result['message_client'] = '';
    $result['message_log'] = $hostnames_array['service'].': Unavailable.';
    if (isset($hostnames_array['suggestion']) && ($hostnames_array['suggestion']=='deny')) {
        $result['action'] = 'deny';
        $result['message_client'] = 'Your IP address has been blacklisted by '.$hostnames_array['service'].'.';
        $result['message_log'] = 'Banned by '.$hostnames_array['service'].'.';
        return $result;
    }
    if (isset($hostnames_array['asn']) && isset($hostnames_array['asn']['asn'])) {
        foreach ($security_settings['asn_ban_list'] as $ban) {
            if ($ban == $hostnames_array['asn']['asn']) {
                $result['action'] = 'deny';
                $result['message_client'] = 'Your ASN has been blacklisted.';
                $result['message_log'] = $hostnames_array['service'].': Banned by ASN.';
                return $result;
            }
        }
    }
    if (isset($hostnames_array['country']) && isset($hostnames_array['country']['code'])) {
        foreach ($security_settings['country_ban_list'] as $ban) {
            if ($ban == $hostnames_array['country']['code']) {
                $result['action'] = 'deny';
                $result['message_client'] = 'Your country '.$hostnames_array['country']['code'].' has been blacklisted.';
                $result['message_log'] = $hostnames_array['service'].': Banned by Country.';
                return $result;
            }
        }
    }
    if ((isset($hostnames_array['hostnames'])) && (is_array($hostnames_array['hostnames']))) {
        foreach ($security_settings['hostname_ban_list'] as $ban) {
            foreach ($hostnames_array['hostnames'] as $hostname) {
                if (stripos($hostname, $ban) !== false) {
                    $result['action'] = 'deny';
                    $result['message_client'] = 'Your hostname '.$hostname.' has been blacklisted.';
                    $result['message_log'] = $hostnames_array['service'].': Banned by Hostname.';
                    return $result;
                }
            }
        }
    }

    $result['action'] = 'allow';
    $result['message_client'] = '';
    $result['message_log'] = $hostnames_array['service'].': Seems legit.';

    return $result;
}

function checkCaptcha() {
    global $data, $captcha, $faucet_settings_array;

    switch ($captcha['selected']) {
        case 'reCaptcha':
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.$data['recaptcha_private_key'].'&response='.(array_key_exists('g-recaptcha-response', $_POST) ? $_POST['g-recaptcha-response'] : '').'&remoteip='.getIP();
            $resp = json_decode(file_get_contents($url), true);
            return $resp['success'];
        break;
        case 'SolveMedia':
            require_once('libs/solvemedialib.php');
            $resp = solvemedia_check_answer(
                $data['solvemedia_verification_key'],
                getIP(),
                (array_key_exists('adcopy_challenge', $_POST) ? $_POST['adcopy_challenge'] : ''),
                (array_key_exists('adcopy_response', $_POST) ? $_POST['adcopy_response'] : ''),
                $data['solvemedia_auth_key']
            );
            return $resp->is_valid;
        break;
        case 'WebMinePool':
            require_once('libs/webminepool.php');
            $webminepool = new webminepool();
            return $webminepool->check($data['webminepool_private_key'], $_POST['captcha_token']);
        break;
        case 'CoinHive':
            require_once('libs/coinhive.php');
            $coinhiveobj = new coinhive();
            return $coinhiveobj->checkResult($_POST['coinhive-captcha-token']);
        break;
        case 'hCaptcha':
            require_once('libs/hcaptcha.php');
            $hcaptchaobj = new hcaptcha();
            return $hcaptchaobj->checkResult($_POST['h-captcha-response']);
        break;
        /*
        case 'RainCaptcha':
            $client = new SoapClient('https://raincaptcha.com/captcha.wsdl');
            $response = $client->send($faucet_settings_array['raincaptcha_secret_key'], $_POST['rain-captcha-response'], getIP());
            if ($response->status === 1) {
                return true;
            } else {
                return false;
            }
        break;
        */
    }

    return false;
}

function releaseAddressLock($address) {
    global $sql, $dbtable_prefix;
    $q = $sql->prepare("DELETE FROM ".$dbtable_prefix."Address_Locks WHERE address = ?");
    $q->execute([$address]);
}

function claimAddressLock($address) {
    global $sql, $dbtable_prefix;
    $q = $sql->prepare("DELETE FROM ".$dbtable_prefix."Address_Locks WHERE address = ? AND TIMESTAMPDIFF(MINUTE, locked_since, CURRENT_TIMESTAMP()) > 5");
    $q->execute([$address]);
    $q = $sql->prepare("INSERT INTO ".$dbtable_prefix."Address_Locks (address, locked_since) VALUES (?, CURRENT_TIMESTAMP())");
    try {
        $q->execute([$address]);
    } catch (PDOException $e) {
        if($e->getCode() == 23000) {
            return false;
        } else {
            throw $e;
        }
    }
    register_shutdown_function("releaseAddressLock", $address);
    return true;
}

function releaseIPLock($ip) {
    global $sql, $dbtable_prefix;
    $q = $sql->prepare("DELETE FROM ".$dbtable_prefix."IP_Locks WHERE ip = ?");
    $q->execute([$ip]);
}

function claimIPLock($ip) {
    global $sql, $dbtable_prefix;
    $q = $sql->prepare("DELETE FROM ".$dbtable_prefix."IP_Locks WHERE ip = ? AND TIMESTAMPDIFF(MINUTE, locked_since, CURRENT_TIMESTAMP()) > 5");
    $q->execute([$ip]);
    $q = $sql->prepare("INSERT INTO ".$dbtable_prefix."IP_Locks (ip, locked_since) VALUES (?, CURRENT_TIMESTAMP())");
    try {
        $q->execute([$ip]);
    } catch (PDOException $e) {
        if($e->getCode() == 23000) {
            return false;
        } else {
            throw $e;
        }
    }
    register_shutdown_function("releaseIPLock", $ip);
    return true;
}

function checkShortlinkNotRequiredOrSolved() {
    global $faucet_settings_array, $session_prefix;

    if ($faucet_settings_array['shortlink_required']=='on') {
        if (((isset($_SESSION['shortlink'.$session_prefix]['solved'])) && ($_SESSION['shortlink'.$session_prefix]['solved']==true)) || ($_SESSION['shortlink'.$session_prefix]['enabled']==false)) {
            return true;
        }
        return false;
    }
    return true;
}

function checkClaimLimitNotExausted($address, $ip, &$limit_left = NULL) {
    global $sql, $dbtable_prefix, $faucet_settings_array;

    $limit_left=-1;

    if ((int)$faucet_settings_array['limit_number_of_claims_per_24h']>0) {
        // check address
        if (!empty($address)) {
            $q = $sql->prepare("SELECT count(id) as count_claims FROM ".$dbtable_prefix."Claimlog WHERE address LIKE ? AND time>".(time()-86400).";");
            $q->execute(array(trim($address)));
            if ($ref = $q->fetch()) {
                if ($ref[0]>=$faucet_settings_array['limit_number_of_claims_per_24h']) {
                    $limit_left=0;
                    return false;
                }
                $limit_left=$faucet_settings_array['limit_number_of_claims_per_24h']-$ref[0];
            }
        }
        // check IP
        $q = $sql->prepare("SELECT count(id) as count_claims FROM ".$dbtable_prefix."Claimlog WHERE ip LIKE ? AND address!='' AND time>".(time()-86400).";");
        $q->execute(array(trim($ip)));
        if ($ref = $q->fetch()) {
            if ($ref[0]>=$faucet_settings_array['limit_number_of_claims_per_24h']) {
                $limit_left=0;
                return false;
            }
            if (($limit_left==-1)||($faucet_settings_array['limit_number_of_claims_per_24h']-$ref[0]<$limit_left)) {
                $limit_left=$faucet_settings_array['limit_number_of_claims_per_24h']-$ref[0];
            }
        }
    }

    // there is no limit
    return true;
}

function sort_proxycheck_array($a, $b) {
    if ($a['priority'] == $b['priority']) {
        return 0;
    }
    return ($a['priority'] > $b['priority']) ? -1 : 1;
}

function getClaimError($address) {
    global $sql, $dbtable_prefix, $dbtable_shortlink_pool_prefix, $security_settings, $faucet_settings_array;

    if (!claimAddressLock($address)) {
        return 'You were locked for multiple claims, try again in 5 minutes.';
    }
    if (!claimIPLock(getIP())) {
        return 'You were locked for multiple claims, try again in 5 minutes.';
    }
    if (!checkAddressValidity($address)) {
        return 'Invalid address';
    }
    if (!checkTimeForAddress($address, $time_left)) {
        return 'You have to wait '.$time_left.' minutes';
    }
    if (!checkTimeForIP(getIP(), $time_left)) {
        return 'You have to wait '.$time_left.' minutes';
    }
    if (!checkClaimLimitNotExausted($address, getIP(), $limit_left)) {
        return 'You\'ve reached the daily claim limit of this faucet. Please come back in 24 hours.';
    }
    # AntiBotLinks START
    global $antibotlinks;
    if (!$antibotlinks->is_valid()) {
        return 'Invalid AntiBot verification!';
    }
    # AntiBotLinks END
    if (!checkCaptcha()) {
        nastyhosts_log('deny', 'Invalid captcha code.', array());
        return 'Invalid captcha code';
    }
    if (!checkAddressBlacklist($address)) {
        nastyhosts_log('deny', 'Your *coin address has been blacklisted.', array());
        return 'Your *coin address has been blacklisted.';
    }
    // check if R is allowed
    if (!empty($_GET['r'])) {
      if (!checkAddressBlacklist($_GET['r'])) {
          nastyhosts_log('deny', 'Your ref *coin address has been blacklisted.', array());
          return 'Your ref *coin address has been blacklisted.';
      }
    }
    $q = $sql->prepare("SELECT address FROM ".$dbtable_prefix."Refs WHERE id = (SELECT ref_id FROM ".$dbtable_prefix."Addresses WHERE address = ?)");
    $q->execute(array(trim($address)));
    if ($ref = $q->fetch()) {
        if (!checkAddressBlacklist(trim($ref[0]))) {
            nastyhosts_log('deny', 'Your ref *coin address has been blacklisted.', array());
            return 'Your ref *coin address has been blacklisted.';
        }
    }
    //
    if(!checkIPIsWhitelisted()) {
        if (!checkIPBlacklist()) {
            nastyhosts_log('deny', 'Your IP address has been blacklisted.', array());
            return 'Your IP address has been blacklisted.';
        }
        // proxycheck services
        $proxycheck_array = array();
        if ($faucet_settings_array['nastyhosts_enabled']=='on') {
            $proxycheck_array[] = array('service'=>'nastyhosts',
                                                    'priority'=>(int)$faucet_settings_array['nastyhosts_priority']
                                                    );
        }
        if (($faucet_settings_array['iphub_enabled']=='on') && (!empty($faucet_settings_array['iphub_api_key']))) {
            $proxycheck_array[] = array('service'=>'iphub',
                                                    'priority'=>(int)$faucet_settings_array['iphub_priority'],
                                                    'apikey'=>$faucet_settings_array['iphub_api_key']
                                                    );
        }
        if (($faucet_settings_array['proxycheck_enabled']=='on') && (!empty($faucet_settings_array['proxycheck_api_key']))) {
            $proxycheck_array[] = array('service'=>'proxycheck',
                                                    'priority'=>(int)$faucet_settings_array['proxycheck_priority'],
                                                    'apikey'=>$faucet_settings_array['proxycheck_api_key']
                                                    );
        }

        if (count($proxycheck_array)>0) {
            // delete cache older than 7 days
            $sql->exec("DELETE FROM ".$dbtable_shortlink_pool_prefix."ProxyCheck WHERE `time`<'".(time()-86400*7)."';");
            // check the cache
            $q = $sql->prepare("SELECT data FROM ".$dbtable_shortlink_pool_prefix."ProxyCheck WHERE ip = ?;");
            $q->execute(array(getIP()));
            if ($proxycheck_row = $q->fetch(PDO::FETCH_ASSOC)) {
                // in cache
                $hostnames_array = json_decode($proxycheck_row['data'], true);
                $proxyDataResult = checkProxyData($hostnames_array);
                nastyhosts_log($proxyDataResult['action'], $proxyDataResult['message_log'].' (cached)', $hostnames_array);
                if ($proxyDataResult['action']=='deny') {
                    return $proxyDataResult['message_client'];
                }
            } else {
                //if nothing in the cache
                // sort from high to low
                usort($proxycheck_array, 'sort_proxycheck_array');

                // loop the proxy checks
                foreach ($proxycheck_array as $proxycheck) {
                    $return_exact_error = '';
                    $result = 2;
                    switch ($proxycheck['service']) {
                        case 'nastyhosts':
                            $hostnames_array = checkNastyHosts();
                        break;
                        case 'iphub':
                            $hostnames_array = checkIPHub($proxycheck['apikey']);
                        break;
                        case 'proxycheck':
                            $hostnames_array = checkProxyCheck($proxycheck['apikey']);
                        break;
                    }
                    // there is a result - then
                    if (count($hostnames_array)>0) {
                        // save to cache
                        $q = $sql->prepare("INSERT INTO ".$dbtable_shortlink_pool_prefix."ProxyCheck(ip, time, data) VALUES(?, ?, ?);");
                        $q->execute(array(getIP(), time(), json_encode($hostnames_array)));
                        $proxyDataResult = checkProxyData($hostnames_array);
                        nastyhosts_log($proxyDataResult['action'], $proxyDataResult['message_log'], $hostnames_array);
                        if ($proxyDataResult['action']=='deny') {
                            return $proxyDataResult['message_client'];
                        }
                        break;
                    }
                }
            }
        }

        # WFM START
        global $wfm;
        $return_exact_error='';
        if (!$wfm->is_valid($return_exact_error)) {
            return $return_exact_error;
        }
        # WFM END
    }
    //
    if (!checkShortlinkNotRequiredOrSolved()) {
        return 'Please complete Step 1 first.';
    }
    return null;
}

function sort_shortlink_data($item1, $item2) {
    if ((int)$item1['priority'] == (int)$item2['priority']) {
        if (isset($item1['order'])) {
            return ((int)$item1['order'] > (int)$item2['order']) ? 1 : -1;
        }
        if (mt_rand(0, 1)==0) {
            return 1;
        } else {
            return -1;
        }
    }
    return ((int)$item1['priority'] < (int)$item2['priority']) ? 1 : -1;
}

function getUSDrate($force = false) {
    global $sql, $faucet_settings_array, $dbtable_prefix;

    if (($force) || ($faucet_settings_array['reward_in_USD_last_check']<time()-3600)) {
        if ($ch = curl_init()) {
            $currency_coingecko = '';
            switch ($faucet_settings_array['currency']) {
                case 'BCH':
                    $currency_coingecko = 'bitcoin-cash';
                break;
                case 'BLK':
                    $currency_coingecko = 'blackcoin';
                break;
                case 'BTC':
                    $currency_coingecko = 'bitcoin';
                break;
                case 'BTX':
                    $currency_coingecko = 'bitcore';
                break;
                case 'DASH':
                    $currency_coingecko = 'dash';
                break;
                case 'DGB':
                    $currency_coingecko = 'digibyte';
                break;
                case 'DOGE':
                    $currency_coingecko = 'dogecoin';
                break;
                case 'ETH':
                    $currency_coingecko = 'ethereum';
                break;
                case 'LTC':
                    $currency_coingecko = 'litecoin';
                break;
                case 'POT':
                    $currency_coingecko = 'potcoin';
                break;
                case 'PPC':
                    $currency_coingecko = 'peercoin';
                break;
                case 'XMR':
                    $currency_coingecko = 'monero';
                break;
                case 'XPM':
                    $currency_coingecko = 'primecoin';
                break;
                case 'ZEC':
                    $currency_coingecko = 'zcash';
                break;
            }
            if (!empty($currency_coingecko)) {
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 9);
                curl_setopt($ch, CURLOPT_TIMEOUT, 9);
                curl_setopt($ch, CURLOPT_URL, 'https://api.coingecko.com/api/v3/coins/'.$currency_coingecko.'?localization=en&sparkline=false');
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:64.0) Gecko/20100101 Firefox/64.0');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                $usd_rate_data_json = curl_exec($ch);
                curl_close($ch);
                $usd_rate_data = @json_decode($usd_rate_data_json, true);
                if (isset($usd_rate_data['market_data']['current_price']['usd'])) {
                    $faucet_settings_array['reward_in_USD_rate'] = $usd_rate_data['market_data']['current_price']['usd'];
                    $faucet_settings_array['reward_in_USD_last_check'] = time();

                    $q = $sql->prepare("UPDATE ".$dbtable_prefix."Settings SET value = ? WHERE name = ?");
                    $q->execute(array($faucet_settings_array['reward_in_USD_rate'], 'reward_in_USD_rate'));
                    $q->execute(array($faucet_settings_array['reward_in_USD_last_check'], 'reward_in_USD_last_check'));
                }
            }
        }
    }
    return $faucet_settings_array['reward_in_USD_rate'];
}

function roundRate($value) {
    global $faucet_settings_array;

    if ($faucet_settings_array['currency']=='DOGE') {
        if (($value>0) && ($value < 0.00000001)) {
            $value = 0.00000001;
        }
        $value = number_format($value, 8, '.', '');
        if ($value > 0.09) {
            $value = number_format($value, 2, '.', '');
        } elseif ($value > 0.0009) {
            $value = number_format($value, 4, '.', '');
        } elseif ($value > 0.000009) {
            $value = number_format($value, 6, '.', '');
        }
        return $value;
    } else {
        if ($value>0) {
            $value = round($value);
            if ($value<1) {
                $value = 1;
            }
        }
        return $value;
    }
}

?>